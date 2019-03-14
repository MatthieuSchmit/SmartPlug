<?php

namespace App\Controller;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\User;
use http\Exception\BadMethodCallException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;
use Symfony\Component\Security\Core\Tests\Validator\Constraints\UserPasswordValidatorTest;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout() {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/get_token", name="app_get_token")
     */
    public function getToken(Request $request, UserProviderInterface $userProvider) {

        if ($request->isMethod('POST')) {

            $params = [];
            if ($content = $request->getContent()) {
                $params = json_decode($content, true);
            }

            try {
                $user = $userProvider->loadUserByUsername($params['email']);
            } catch (\Exception $e) {
                throw new \Exception('Invalid username or password');
            }

            if(!password_verify($params['password'], $user->getPassword())) {
                throw new \Exception('Invalid username or password');
            }

            return $this->json([
                'id' => $user->getId(),
                'apiToken' => $user->getApiToken(),
                'email' => $user->getEmail()
            ]);
        }
        throw new \BadMethodCallException("");
    }

    /**
     * @Route("/check_token", name="app_check_token")
     * @param Request $request
     * @param UserProviderInterface $userProvider
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function checkToken(Request $request, UserProviderInterface $userProvider) {
        if ($request->isMethod('POST')) {

            $email = $request->query->get('email');
            $token = $request->query->get('token');

            try {
                $user = $userProvider->loadUserByUsername($email);
            } catch (\Exception $e) {
                throw new \Exception('Invalid token');
            }

            if($user->getApiToken() != $token) {
                throw new \Exception('Invalid token');
            }

            return $this->json([
                'id' => $user->getId(),
                'apiToken' => $user->getApiToken(),
                'email' => $user->getEmail()
            ]);
        }
        throw new \BadMethodCallException("");
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {

        if ($request->isMethod('POST')
            && $request->request->get('password') == $request->request->get('passwordRepeat')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $tg = new UriSafeTokenGenerator();
            $user->setApiToken($tg->generateToken());
            //$user->setApiToken(md5($user->getPassword()) . md5(time()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABS_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('test@smartplug.be')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/reset_password.html.twig',
                        ['url' => $url,'user' => $user]
                    ),
                    'text/html'
                );
                //->setBody(
                //    "blablabla voici le token pour reseter votre mot de passe : " . $url,
                //    'text/html'
                //);

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder) {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('home');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }

    /**
     * @Route("/change_password", name="app_change_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {
        return null;
    }

    /**
     * @Route("/token/change", name="change_token")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changeToken() {
        $user = $this->getUser();
        //$user->setApiToken(md5($user->getApiToken()) . md5(time()));
        $tg = new UriSafeTokenGenerator();
        $user->setApiToken($tg->generateToken());
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json([
            'token' => $user->getApiToken(),
        ]);
    }

}

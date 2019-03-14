<?php
/**
 * Created by PhpStorm.
 * User: Matthieu
 * Date: 20/02/19
 * Time: 14:04
 */

namespace App\Controller\api\v0;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserController extends AbstractController {

    /**
     * @Route(
     *     name="v0_update_user",
     *     path="/api/v0/users/{id}",
     *     methods={"PUT"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\UserController::update",
     *         "_api_resource_class"="App\Entity\User",
     *         "_api_item_operation_name"="updateUser"
     *     },
     * )
     * @param $id
     * @param User $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return User|Response
     */
    public function update($id, User $user, UserPasswordEncoderInterface $passwordEncoder) {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        // Interdiction de modifier qqun d'autre
        if ($connectedUser->getId() == $user->getId()) {

            // Test s'il faut modifier le mot de passe
            if ($user->getPlainPassword() != "" && $user->getCurrentPassword() != "" ) {

                // Test de currentPassword
                if(password_verify($user->getCurrentPassword(), $connectedUser->getPassword())) {
                    $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword()));
                    // clean
                    $user->setPlainPassword("");
                    $user->setCurrentPassword("");
                } else {
                    return new Response(
                        'User or Password invalid !',
                        403
                    );
                }
            }

            return $user;
        } else {
            return new Response(
                'Forbidden !',
                403
            );
        }
    }

    /**
     * @Route(
     *     name="v0_get_user",
     *     path="/api/v0/users/{id}",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\UserController::getOne",
     *         "_api_resource_class"="App\Entity\User",
     *         "_api_item_operation_name"="getUser"
     *     },
     * )
     * @param User $user
     * @return User|object|Response
     */
    public function getOne(User $user) {
        if ($user == $this->get('security.token_storage')->getToken()->getUser()) {
            return $user;
        } else {
            return new Response(
                'Forbidden !',
                403
            );
        }
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Matthieu
 * Date: 11/02/19
 * Time: 14:42
 */

namespace App\Controller\api\v0;

use App\Entity\AccessLog;
use App\Entity\Plug;
use App\Entity\PowerStrip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PowerStripController extends AbstractController{


    /**
     * @Route (
     *     name="v0_store_ps",
     *     path="/api/v0/power_strips",
     *     methods={"POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::storePowerStrip",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_collection_operation_name"="storePowerStrip"
     *     },
     * )
     * @param PowerStrip $data
     * @return PowerStrip $data
     */
    public function storePowerStrip(PowerStrip $data) {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        $data->setUser($connectedUser);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return $data;
    }

    /**
     * @Route (
     *     name="v0_get_all_ps",
     *     path="/api/v0/power_strips",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::getAll",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_collection_operation_name"="getPowerStrips"
     *     },
     * )
     * @return object[]
     */
    public function getAll() {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        return $this->getDoctrine()->getRepository(PowerStrip::class)->findBy(['user' => $connectedUser]);
    }

    /**
     * @Route (
     *     name="v0_get_one_ps",
     *     path="/api/v0/power_strips/{id}",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::getOne",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_item_operation_name"="getPowerStrips"
     *     },
     *     )
     * @param PowerStrip $data
     * @return PowerStrip|Response
     */
    public function getOne(PowerStrip $data) {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        /*
        $request =  Request::createFromGlobals();
        $log = new AccessLog();
        $log->setUser($connectedUser);
        $log->setPowerstrip($data);
        $log->setIP($request->getClientIp());
        $log->setMethod($request->getMethod());
        $log->setUrl($request->getPathInfo());
        $log->setDate(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();
        */

        if ($data->getUser() == $connectedUser) {
            return $data;
        } else {
            return new Response(
                'Forbidden',
                403
            );
        }
    }

    /**
     * @Route (
     *     name="v0_update_name_ps",
     *     path="/api/v0/power_strips/{id}",
     *     methods={"PUT","POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::updateName",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_item_operation_name"="putPowerStrips"
     *     },
     * )
     * @param $id
     * @param PowerStrip $data
     * @return PowerStrip|Response
     */
    public function updateName($id, PowerStrip $data) {

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($data->getUser() != $connectedUser) {
            return new Response(
                'Forbidden !',
                304
            );
        }

        return $data;
    }

    /**
     * @Route (
     *     name="v0_delete_ps",
     *     path="/api/v0/power_strips/{id}",
     *     methods={"DELETE"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::delete",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_item_operation_name"="deletePowerStrips"
     *     },
     * )
     * @param PowerStrip $data
     * @return Response
     */
    public function delete($id) {
        $ps = $this->getDoctrine()->getRepository(PowerStrip::class)->find($id);

        if (!$ps) {
            return new Response(
                'Not Found !',
                404
            );
        }

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($ps->getUser() != $connectedUser) {
            return new Response(
                'Forbidden !',
                304
            );
        }

        // TODO : Delete PS


        return new Response(
            'Power strip deleted !',
            204
        );
    }

    /**
     * @Route (
     *     name="v0_add_plug_ps",
     *     path="/api/v0/power_strips/{id}/{plugName}",
     *     methods={"POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PowerStripController::addPlug",
     *         "_api_resource_class"="App\Entity\PowerStrip",
     *         "_api_item_operation_name"="addPlugPowerStrips"
     *     },
     * )
     * @param $id
     * @param $plugName
     * @return Plug|Response
     */
    public function addPlug($id, $plugName) {
        $ps = $this->getDoctrine()->getRepository(PowerStrip::class)->find($id);
        if(!$ps) {
            return new Response(
                'Not Found !',
                404
            );
        }
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($ps->getUser() != $connectedUser) {
            return new Response(
                'Forbidden',
                403
            );
        }

        $plug = new Plug();
        $plug->setName($plugName);
        $plug->setPowerStrip($ps);

        $em = $this->getDoctrine()->getManager();
        $em->persist($plug);
        $em->flush();

        return $plug;

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Matthieu
 * Date: 11/02/19
 * Time: 17:23
 */

namespace App\Controller\api\v0;

use App\Entity\Plug;
use App\Entity\PowerStrip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlugController extends AbstractController{

    /**
     * @Route (
     *     name="v0_update_actual_state",
     *     path="/api/v0/plugs/{id}/actual/state/{state}",
     *     methods={"PUT","POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::updateActualState",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_item_operation_name"="putActualState"
     *     },
     * )
     * @param $id
     * @param $state
     * @return null|object|Response
     */
    public function updateActualState($id, $state) {

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        $plug = $this->getDoctrine()->getRepository(Plug::class)->find($id);

        if ($plug->getPowerStrip()->getUser() != $connectedUser) {
            return new Response(
                'Forbidden !',
                304
            );
        }

        $plug->setState( ($state == 1) );
        $em = $this->getDoctrine()->getManager();
        $em->persist($plug);
        $em->flush();

        return $plug;
    }

    /**
     * @Route (
     *     name="v0_update_wanted_state",
     *     path="/api/v0/plugs/{id}/wanted/state/{state}",
     *     methods={"PUT","POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::updateWantedState",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_item_operation_name"="putWantedState"
     *     },
     * )
     * @param $id
     * @param $state
     * @return null|object|Response
     */
    public function updateWantedState($id, $state) {

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        $plug = $this->getDoctrine()->getRepository(Plug::class)->find($id);

        if ($plug->getPowerStrip()->getUser() != $connectedUser) {
            return new Response(
                'Forbidden !',
                304
            );
        }

        $plug->setTempState( ($state == 1) );
        $em = $this->getDoctrine()->getManager();
        $em->persist($plug);
        $em->flush();


        return $plug;
    }

    /**
     * @Route (
     *     name="v0_update_name_plug",
     *     path="/api/v0/plugs/{id}",
     *     methods={"PUT","POST"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::updateName",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_item_operation_name"="putPlug"
     *     },
     * )
     * @param $id
     * @param Plug $data
     * @return Plug|Response
     */
    public function updateName($id, Plug $data) {

        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($data->getPowerStrip()->getUser() != $connectedUser) {
            return new Response(
                'Forbidden !',
                304
            );
        }

        return $data;
    }

    /**
     * @Route (
     *     name="v0_get_one_plug",
     *     path="/api/v0/plugs/{id}",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::getOne",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_item_operation_name"="getPlug"
     *     },
     *     )
     * @param Plug $data
     * @return Plug|Response
     */
    public function getOne(Plug $data) {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($data->getPowerStrip()->getUser() == $connectedUser) {
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
     *     name="v0_get_all_plugs",
     *     path="/api/v0/plugs",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::getAll",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_collection_operation_name"="getPlugs"
     *     },
     * )
     * @return object[]
     */
    public function getAll() {
        return [];
        //$connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        //return $this->getDoctrine()->getRepository(Plug::class)->findBy(['power_strip.user' => $connectedUser]);
    }


    /**
     * @Route (
     *     name="v0_get_states_plug",
     *     path="/api/v0/plugs/{id}/states",
     *     methods={"GET"},
     *     defaults={
     *         "_controller"="\App\Controller\api\v0\PlugController::getStates",
     *         "_api_resource_class"="App\Entity\Plug",
     *         "_api_item_operation_name"="getPlugStates"
     *     },
     * )
     * @param Plug $data
     * @return
     */
    public function getStates(Plug $data) {
        $connectedUser = $this->get('security.token_storage')->getToken()->getUser();
        if ($data->getPowerStrip()->getUser() == $connectedUser) {
            $x = ($data->getState()) ? 1 : 0;
            $y = ($data->getTempState()) ? 1 : 0;
            return $x . $y;
        } else {
            return new Response(
                'Forbidden',
                403
            );
        }
    }

}

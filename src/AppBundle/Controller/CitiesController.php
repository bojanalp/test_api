<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CityEntity;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CitiesController extends Controller {

  /**
   * @Route("/api/city", name="cities_index", methods={"GET"})
   */
  public function showAll() {
    $dbData = $this
      ->getDoctrine()
      ->getRepository(CityEntity::class)
      ->findAll();
    $cities = [];
    foreach ($dbData as $city) {
      $cities[] = $city->expose();
    }
    return new JsonResponse(["cities" => $cities]);
  }

  /**
   * @Route("/api/city/{id}", name="city_show", methods={"GET"})
   */
  public function showOne($id) {
    $dbData = $this
      ->getDoctrine()
      ->getRepository(CityEntity::class)
      ->find($id);
    $city = [];
    if (isset($dbData)) {
      $city[] = $dbData->expose();
    }
    return new JsonResponse(["city" => $city]);
  }

  /**
   * @Route("/api/city/{id}", name="city_delete", methods={"DELETE"})
   */
  public function delete($id) {
    $em = $this
      ->getDoctrine()
      ->getManager();
    $dbData = $em
      ->getRepository(CityEntity::class)
      ->find($id);
    if (isset($dbData)) {
      $em->remove($dbData);
      $em->flush();
    }
    return new RedirectResponse($this->generateUrl('cities_index'));
  }

}
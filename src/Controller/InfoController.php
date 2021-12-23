<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Region;
// use App\Entity\UsePlantSubCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class InfoController extends AbstractController
{
    /**
     * @Route("/regions", name="regions", methods={"GET"}, options={"expose"=true})
     */
    public function regions(Request $request): Response
    {
        try {
            $regions = $this->getDoctrine()
                ->getRepository(Region::class)
                ->findBy(['country' => $request->get('country')],
                    ['title_ua' => 'ASC']);

            $data = array();
            foreach ($regions as $region) {
                $data[] = ['id' => $region->getId(), 'name' => $region->getTitleUa()];
            }

            return new JsonResponse($data, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return new JsonResponse(array('message' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/cities", name="cities", methods={"GET"}, options={"expose"=true})
     */
    public function cities(Request $request): Response
    {
        try {
            $cities = $this->getDoctrine()
                ->getRepository(City::class)
                ->findBy(['region' => $request->get('region')],
                    ['title_ua' => 'ASC']);

            $data = array();
            foreach ($cities as $city) {
                $data[] = ['id' => $city->getId(), 'name' => $city->getTitleUa()];
            }

            return new JsonResponse($data, Response::HTTP_OK);

        } catch (\Exception $exception) {

            return new JsonResponse(array('message' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
        }
    }
//
//    /**
//     * @Route("/sub", name="subcategories", methods={"GET"}, options={"expose"=true})
//     */
//    public function subcategories(Request $request): Response
//    {
//        try {
//            $subcategories = $this->getDoctrine()
//                ->getRepository(UsePlantSubCategory::class)
//                ->findBy(['category' => $request->get('category')],
//                    ['title' => 'ASC']);
//
//            $data = array();
//            foreach ($subcategories as $subcategory) {
//                $data[] = ['id' => $subcategory->getId(), 'name' => $subcategory->getTitle()];
//            }
//
//            return new JsonResponse($data, Response::HTTP_OK);
//
//        } catch (\Exception $exception) {
//
//            return new JsonResponse(array('message' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
//        }
//    }
}

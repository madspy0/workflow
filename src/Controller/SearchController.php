<?php

namespace App\Controller;

use App\Repository\TownRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class SearchController extends AbstractController
{
    private $finder;

    public function __construct(PaginatedFinderInterface $finder)
    {
        $this->finder = $finder;
    }
    /**
     * @Route("/dr_search", name="search.autocomplete", methods={"GET"}, options={"expose"=true})
     */
    public function search(Request $request, TownRepository $repository): JsonResponse
    {
        $requestString = $request->get('q');
        $this->finder->find($requestString);

//        $entities =  $repository->findEntitiesByString($requestString);
//
//        if(!$entities) {
//            $result['entities']['error'] = "keine Einträge gefunden";
//        } else {
//            $result['entities'] = $this->getRealEntities($entities);
//        }
        $result['entities'] = $this->finder->find($requestString);
        return new JsonResponse($result);
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getNameUa();
        }

        return $realEntities;
    }
}
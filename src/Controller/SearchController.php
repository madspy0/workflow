<?php

namespace App\Controller;

use App\Repository\TownRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Elastica\Query;
use Elastica\Query\MatchQuery;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    private $finder;

//    public function __construct(PaginatedFinderInterface $finder)
//    {
//        $this->finder = $finder;
//    }
    /**
     * @Route("/dr_search", name="search.autocomplete", methods={"GET"}, options={"expose"=true})
     */
    public function search(Request $request, TownRepository $repository, SerializerInterface $serializer): Response
    {
        $requestString = $request->get('q');

//        $entities =  $repository->findEntitiesByString($requestString);
//
//        if(!$entities) {
//            $result['entities']['error'] = "keine Einträge gefunden";
//        } else {
//            $result['entities'] = $this->getRealEntities($entities);
//        }
     //   $query = new Query();
    //    $query->addSort(['nameUa' => 'asc']);
        $fieldQuery = new MatchQuery();
        $fieldQuery->setFieldQuery('nameUa', $requestString);
        $fieldQuery->setFieldParam('nameUa', 'analyzer', 'autocomplete');
    //    $query->setQuery($fieldQuery);
        $result = $serializer->serialize($this->finder->find($fieldQuery), 'json', ['groups' => 'searchOut']);
        return new Response($result);
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getNameUa();
        }

        return $realEntities;
    }
}

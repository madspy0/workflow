<?php

namespace App\Controller;

use App\Repository\DzkAdminOtgRepository;
use App\Repository\TownRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Elastica\Query;
use Elastica\Query\MatchQuery;
use Symfony\Component\Serializer\SerializerInterface;
use Exception;

class SearchController extends AbstractController
{
    private $finder;
    private $otgFinder;

    public function __construct(PaginatedFinderInterface $finder, PaginatedFinderInterface $otgFinder)
    {
        $this->finder = $finder;
        $this->otgFinder = $otgFinder;
    }

    /**
     * @Route("/dr_search", name="search.autocomplete", methods={"GET"}, options={"expose"=true})
     */
    public function search(Request $request, TownRepository $repository, SerializerInterface $serializer): Response
    {
        try {
            $requestString = $request->get('q');
            //   $query = new Query();
            //    $query->addSort(['nameUa' => 'asc']);
            $fieldQuery = new MatchQuery();
            $fieldQuery->setFieldQuery('nameUa', $requestString);
            $fieldQuery->setFieldParam('nameUa', 'analyzer', 'autocomplete');
            //    $query->setQuery($fieldQuery);
            $result = $serializer->serialize($this->finder->find($fieldQuery), 'json', ['groups' => 'searchOut']);
            return new Response($result);
        } catch (HttpException $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/dr_otg", name="search.otg.autocomplete", methods={"GET"}, options={"expose"=true})
     */
    public function getOtg(Request $request, DzkAdminOtgRepository $repository, SerializerInterface $serializer): Response
    {
        try {
            $requestString = $request->get('q');
            //   $query = new Query();
            //    $query->addSort(['nameUa' => 'asc']);
            $fieldQuery = new MatchQuery();
            $fieldQuery->setFieldQuery('name_otg', $requestString);
            $fieldQuery->setFieldParam('name_otg', 'analyzer', 'autocomplete');
            //    $query->setQuery($fieldQuery);
            $result = $serializer->serialize($this->otgFinder->find($fieldQuery), 'json', ['groups' => 'otgOut']);
            return new Response($result);
        } catch (HttpException $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

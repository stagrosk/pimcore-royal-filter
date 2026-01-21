<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Pimcore\Model\DataObject\Collection;
use PimcoreVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class CollectionController extends RestController
{
    #[Route('/collection', name: 'api_vendure_collection', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        return parent::indexAction($request);
    }

    #[Route('/collection/{id}', name: 'api_vendure_collection_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_UNAUTHORIZED);
        }

        $collection = Collection::getById($id);
        if (!$collection instanceof Collection) {
            return $this->handleException('Collection not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($collection);

            $data = json_decode($json, true, 1024, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing collection: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing collection', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}
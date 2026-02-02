<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Pimcore\Model\DataObject\PriceList;
use PimcoreVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class PriceListController extends RestController
{
    #[Route('/pricelist', name: 'api_vendure_pricelist', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        try {
            $listing = PriceList::getList();
            $listing->setUnpublished(false);

            $priceLists = $listing->load();
            $data = [];

            foreach ($priceLists as $priceList) {
                $json = $this->serializeToJson($priceList);
                $data[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error listing pricelists: %s', $e->getMessage()));
            return $this->handleException('Error listing pricelists', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    #[Route('/pricelist/{id}', name: 'api_vendure_pricelist_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $priceList = PriceList::getById($id);
        if (!$priceList instanceof PriceList) {
            return $this->handleException('PriceList not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($priceList);
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing pricelist: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing pricelist', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}

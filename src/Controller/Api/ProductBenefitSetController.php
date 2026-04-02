<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Pimcore\Model\DataObject\ProductBenefitSet;
use PimcoreVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class ProductBenefitSetController extends RestController
{
    #[Route('/product-benefit-set', name: 'api_vendure_product_benefit_set', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        try {
            $listing = ProductBenefitSet::getList();
            $listing->setUnpublished(false);

            $sets = $listing->load();
            $data = [];

            foreach ($sets as $set) {
                $json = $this->serializeToJson($set);
                $data[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error listing product benefit sets: %s', $e->getMessage()));
            return $this->handleException('Error listing product benefit sets', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    #[Route('/product-benefit-set/{id}', name: 'api_vendure_product_benefit_set_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $set = ProductBenefitSet::getById($id);
        if (!$set instanceof ProductBenefitSet) {
            return $this->handleException('ProductBenefitSet not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($set);
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing product benefit set: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing product benefit set', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}

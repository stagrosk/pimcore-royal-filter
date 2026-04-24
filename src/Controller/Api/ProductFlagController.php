<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\OpenDxp\Model\DataObject\ProductFlag;
use OpendxpVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class ProductFlagController extends RestController
{
    #[Route('/product-flag', name: 'api_vendure_product_flag', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        try {
            $listing = ProductFlag::getList();
            $listing->setUnpublished(false);

            $flags = $listing->load();
            $data = [];

            foreach ($flags as $flag) {
                $json = $this->serializeToJson($flag);
                $data[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error listing product flags: %s', $e->getMessage()));
            return $this->handleException('Error listing product flags', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    #[Route('/product-flag/{id}', name: 'api_vendure_product_flag_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $flag = ProductFlag::getById($id);
        if (!$flag instanceof ProductFlag) {
            return $this->handleException('ProductFlag not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($flag);
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing product flag: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing product flag', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}

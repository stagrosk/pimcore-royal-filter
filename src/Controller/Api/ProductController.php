<?php

declare(strict_types=1);

namespace App\Controller\Api;

use OpenDxp\Model\DataObject\Product;
use PimcoreVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class ProductController extends RestController
{
    #[Route('/product', name: 'api_vendure_product', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        return parent::indexAction($request);
    }

    #[Route('/product/{id}', name: 'api_vendure_product_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $product = Product::getById($id);
        if (!$product instanceof Product) {
            return $this->handleException('Product not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($product);
            $jsonCustomFields = $this->serializeToJson($product, [self::CONTEXT_GROUP_CUSTOM_FIELDS]);

            $data = json_decode($json, true, 1024, JSON_THROW_ON_ERROR);
            $dataCustomFields = json_decode($jsonCustomFields, true, 1024, JSON_THROW_ON_ERROR);

            if (!empty($dataCustomFields)) {
                $data['customFields'] = $dataCustomFields;
            }

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing product: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing product', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}
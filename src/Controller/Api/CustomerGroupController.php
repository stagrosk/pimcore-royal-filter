<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\OpenDxp\Model\DataObject\CustomerGroup;
use PimcoreVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class CustomerGroupController extends RestController
{
    #[Route('/customer-group', name: 'api_vendure_customer_group', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        try {
            $listing = CustomerGroup::getList();
            $listing->setUnpublished(false);

            $groups = $listing->load();
            $data = [];

            foreach ($groups as $group) {
                $json = $this->serializeToJson($group);
                $data[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error listing customer groups: %s', $e->getMessage()));
            return $this->handleException('Error listing customer groups', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    #[Route('/customer-group/{id}', name: 'api_vendure_customer_group_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $group = CustomerGroup::getById($id);
        if (!$group instanceof CustomerGroup) {
            return $this->handleException('CustomerGroup not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($group);
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing customer group: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing customer group', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}

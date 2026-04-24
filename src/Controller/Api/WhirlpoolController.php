<?php

declare(strict_types=1);

namespace App\Controller\Api;

use OpenDxp\Model\DataObject\Whirlpool;
use OpendxpVendureBridgeBundle\Controller\Rest\RestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class WhirlpoolController extends RestController
{
    #[Route('/whirlpool', name: 'api_vendure_whirlpool', methods: ['GET'])]
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        try {
            $page = max(1, $request->query->getInt('page', 1));
            $limit = min(200, max(1, $request->query->getInt('limit', 100)));
            $offset = ($page - 1) * $limit;

            $listing = Whirlpool::getList();
            $listing->setUnpublished(false);
            $listing->setOrderKey('id');
            $listing->setOrder('ASC');

            $total = $listing->getTotalCount();

            $listing->setLimit($limit);
            $listing->setOffset($offset);
            $whirlpools = $listing->load();

            $items = [];
            foreach ($whirlpools as $whirlpool) {
                $json = $this->serializeToJson($whirlpool);
                $items[] = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            }

            $view = $this->view([
                'items' => $items,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => (int) ceil($total / $limit),
            ]);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error listing whirlpools: %s', $e->getMessage()));
            return $this->handleException('Error listing whirlpools', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }

    #[Route('/whirlpool/{id}', name: 'api_vendure_whirlpool_by_id', methods: ['GET'])]
    public function showAction(int $id, Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            return $this->handleException('Permission denied, apikey not valid', Response::HTTP_FORBIDDEN);
        }

        $whirlpool = Whirlpool::getById($id);
        if (!$whirlpool instanceof Whirlpool) {
            return $this->handleException('Whirlpool not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $json = $this->serializeToJson($whirlpool);
            $data = json_decode($json, true, 1024, JSON_THROW_ON_ERROR);

            $view = $this->view($data);
            $view->setFormat('json');

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('[VendureAPI] Error serializing whirlpool: %s - %s', $id, $e->getMessage()));
            return $this->handleException('Error serializing whirlpool', Response::HTTP_INTERNAL_SERVER_ERROR, $e);
        }
    }
}

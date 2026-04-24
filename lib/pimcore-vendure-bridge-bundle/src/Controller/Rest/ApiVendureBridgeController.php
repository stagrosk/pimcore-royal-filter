<?php

namespace PimcoreVendureBridgeBundle\Controller\Rest;

use FOS\RestBundle\Controller\Annotations\Route;
use OpenDxp\Model\DataObject\Category\Listing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @Route("/api/pimcore-vendure-bridge")
 */
class ApiVendureBridgeController extends RestController
{
    /**
     * @Route("/categoryList", methods={"GET"})
     */
    public function categoryListAction(Request $request): Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            throw new AccessDeniedHttpException('Permission denied, apikey not valid');
        }

        $data = [];

        // get all categories
        $categoryList = new Listing();
        $categories = $categoryList->getData();
        foreach ($categories as $category) {
            $json = $this->serializeToJson($category);
            $data[] = json_decode($json, true, 1024, JSON_THROW_ON_ERROR);
        }

        $view = $this->view($data);
        $view->setFormat('json');

        return $this->handleView($view);
    }
}

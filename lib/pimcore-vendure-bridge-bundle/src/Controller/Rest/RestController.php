<?php

namespace PimcoreVendureBridgeBundle\Controller\Rest;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use OpenDxp\Model\DataObject;
use PimcoreVendureBridgeBundle\Component\Serializer\JMSSerializerFactory;
use PimcoreVendureBridgeBundle\Security\CheckConsumerPermissionsService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class RestController extends AbstractFOSRestController
{
    const CONTEXT_GROUP_API = 'api';
    const CONTEXT_GROUP_CUSTOM_FIELDS = 'api-custom-field';

    protected JMSSerializerFactory $jmsSerializerFactory;

    protected LoggerInterface $logger;

    protected CheckConsumerPermissionsService $checkConsumerPermissionsService;

    /**
     * @param \PimcoreVendureBridgeBundle\Component\Serializer\JMSSerializerFactory $jmsSerializerFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \PimcoreVendureBridgeBundle\Security\CheckConsumerPermissionsService $checkConsumerPermissionsService
     */
    public function __construct(
        JMSSerializerFactory $jmsSerializerFactory,
        LoggerInterface $logger,
        CheckConsumerPermissionsService $checkConsumerPermissionsService
    ) {
        $this->jmsSerializerFactory = $jmsSerializerFactory;
        $this->logger = $logger;
        $this->checkConsumerPermissionsService = $checkConsumerPermissionsService;
    }

    /**
     * @Route("/", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \JsonException
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): JsonResponse|Response
    {
        if (!$this->checkConsumerPermissionsService->performSecurityCheck($request)) {
            throw new AccessDeniedHttpException('Permission denied, apikey not valid');
        }

        $id = $request->get('id');
        if (empty($id)) {
            return $this->handleException('Id cannot be empty!');
        }

        $dataObject = DataObject::getById($id);
        if ($dataObject === null) {
            return $this->handleException('Object by id not found!');
        }

        $json = $this->serializeToJson($dataObject);

        // custom fields
        $jsonCustomFields = $this->serializeToJson($dataObject, [self::CONTEXT_GROUP_CUSTOM_FIELDS]);

        // final merge
        $data = json_decode($json, true, 1024, JSON_THROW_ON_ERROR);
        $dataCustomFields = json_decode($jsonCustomFields, true, 1024, JSON_THROW_ON_ERROR);

        if (!empty($dataCustomFields)) {
            $data['customFields'] = $dataCustomFields;
        }

        $view = $this->view($data);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @param string $exceptionMessage
     * @param int $statusCode
     * @param \Exception|null $exception
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function handleException(
        string $exceptionMessage,
        int $statusCode = Response::HTTP_CONFLICT,
        \Exception $exception = null,
        array $headers = []
    ): JsonResponse {
        if ($exception instanceof ConstraintViolationException) {
            if (str_contains($exceptionMessage, 'DETAIL:')) {
                $exceptionMessage = trim(str_replace('DETAIL:', '', strstr($exceptionMessage, 'DETAIL:')));
            } else {
                $exceptionMessage .= $exception->getTraceAsString();
            }
        }

        $this->logger->error($exceptionMessage, ['statusCode' => $statusCode, 'api' => true]);

        return new JsonResponse(json_encode(['error' => $exceptionMessage]), $statusCode, $headers, true);
    }

    /**
     * @param string $json
     * @param string $entityName
     * @param array $groups
     *
     * @return mixed
     */
    protected function deserializeFromJson(string $json, string $entityName, array $groups = [self::CONTEXT_GROUP_API])
    {
        return $this->jmsSerializerFactory->deserialize(
            $json,
            $entityName,
            $groups
        );
    }

    /**
     * @param $entity
     * @param array $groups
     *
     * @return string
     */
    protected function serializeToJson($entity, array $groups = [self::CONTEXT_GROUP_API]): string
    {
        return $this->jmsSerializerFactory->serialize(
            $entity,
            $groups
        );
    }
}

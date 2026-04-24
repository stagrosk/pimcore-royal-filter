<?php

namespace App\GraphQL\Helper;

use ArrayObject;
use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Bundle\DataHubBundle\GraphQL\ElementDescriptor;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Service;
use OpenDxp\Model\DataObject\AbstractObject;

class ObjectDataExtractor
{
    private Service $graphQlService;

    /**
     * @param \OpenDxp\Bundle\DataHubBundle\GraphQL\Service $graphQlService
     */
    public function __construct(Service $graphQlService)
    {
        $this->graphQlService = $graphQlService;
    }

    /**
     * @param $objectOrArrayWithId
     * @param array $args
     * @param array $context
     * @param \GraphQL\Type\Definition\ResolveInfo|null $info
     *
     * @return \ArrayObject|null
     */
    public function extract($objectOrArrayWithId, array $args = [], array $context = [], ?ResolveInfo $info = null): ?ArrayObject
    {
        $object = null;

        if ($objectOrArrayWithId instanceof AbstractObject) {
            $object = $objectOrArrayWithId;
        } elseif (is_array($objectOrArrayWithId) && array_key_exists('id', $objectOrArrayWithId)) {
            $object = AbstractObject::getById($objectOrArrayWithId['id']);
        }

        if (!$object instanceof AbstractObject) {
            return null;
        }

        $data = new ElementDescriptor($object);
        $data['id'] = $object->getId();

        $this->graphQlService->extractData($data, $object, $args, $context, $info);

        return $data;
    }

    /**
     * @param string $payload
     */
    public function setLocale(string $payload): void
    {
        $this->graphQlService->getLocaleService()->setLocale($payload);
    }

    /**
     * @param array $array
     * @param array $args
     * @param array $context
     * @param \GraphQL\Type\Definition\ResolveInfo|null $info
     *
     * @return array
     */
    public function extractList(array $array, array $args = [], array $context = [], ?ResolveInfo $info = null): array
    {
        return array_map(function ($item) use ($args, $context, $info) {
            return $this->extract($item, $args, $context, $info);
        }, $array);
    }
}

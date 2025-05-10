<?php

namespace App\Shopify\Graphql\Mutation\FIle;

use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;

class FileDeleteMutation extends BaseMutation
{
    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation fileDelete($input: [ID!]!) {
              fileDelete(fileIds: $input) {
                deletedFileIds
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array[]|\Pimcore\Model\DataObject\AbstractObject[]
     */
    public function getVariables(AbstractObject|array $object): array
    {
        // object as array of apiIds
        return [
            'input' => $object
        ];
    }
}

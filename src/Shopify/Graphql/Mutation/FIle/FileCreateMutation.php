<?php

namespace App\Shopify\Graphql\Mutation\FIle;

use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;

class FileCreateMutation extends BaseMutation
{
    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation fileCreate($files: [FileCreateInput!]!) {
              fileCreate(files: $files) {
                files {
                  id
                  fileStatus
                  alt
                  createdAt
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(AbstractObject|array $object): array
    {
//        FileCreateInput
        return [];
    }
}

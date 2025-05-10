<?php

namespace App\Shopify\Graphql\Mutation\FIle;

use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;

class FileUpdateMutation extends BaseMutation
{
    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation FileUpdate($input: [FileUpdateInput!]!) {
              fileUpdate(files: $input) {
                userErrors {
                  code
                  field
                  message
                }
                files {
                  alt
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
//        FileUpdateInput
        return [];
    }
}

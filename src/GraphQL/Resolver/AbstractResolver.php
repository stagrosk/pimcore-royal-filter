<?php

namespace App\GraphQL\Resolver;

use GraphQL\Type\Definition\ResolveInfo;

abstract class AbstractResolver
{
    /**
     * @param $source
     * @param $args
     * @param $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return mixed
     */
    abstract public function resolve($source, $args, $context, ResolveInfo $info);
}

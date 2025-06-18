<?php

namespace App\GraphQL\Type\Arguments;

interface ArgumentsInterface
{
    /**
     * The arguments for the query or mutation
     *
     * @return array
     */
    public static function args(): array;
}

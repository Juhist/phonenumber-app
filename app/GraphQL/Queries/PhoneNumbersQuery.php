<?php

namespace App\GraphQL\Queries;

use App\Models\PhoneNumber;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PhoneNumberQuery extends Query
{
    protected $attributes = [
        'name' => 'phoneNumbers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('PhoneNumber'));
    }

    public function resolve($root, $args)
    {
        return PhoneNumber::all();
    }
}
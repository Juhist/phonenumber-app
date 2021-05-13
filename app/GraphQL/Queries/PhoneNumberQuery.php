<?php

namespace App\GraphQL\Queries;

use App\Models\PhoneNumber;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PhoneNumberQuery extends Query
{
    protected $attributes = [
        'name' => 'phoneNumber',
    ];

    public function type(): Type
    {
        return GraphQL::type('PhoneNumber');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required','exists:phone_numbers,id']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return PhoneNumber::findOrFail($args['id']);
    }
}
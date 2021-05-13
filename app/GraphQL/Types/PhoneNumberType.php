<?php

namespace App\GraphQL\Types;

use App\Models\PhoneNumber;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PhoneNumberType extends GraphQLType
{
    protected $attributes = [
        'name' => 'PhoneNumber',
        'description' => 'Collection of user of phone numbers',
        'model' => PhoneNumber::class
    ];


    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id of a particular user',
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the user',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The email of the user',
            ],
            'dateOfBirth' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date of bith of the user',
            ],
            'isActive' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The active status of the user',
            ]                        
        ];
    }
}
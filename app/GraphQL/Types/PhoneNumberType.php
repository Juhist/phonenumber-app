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
                'description' => 'Id of a particular phone number',
            ],
            'phoneNumber' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The phone number of the user',
            ],
            'isDefault' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'The default status of the phone number',
            ]                        
        ];
    }
}
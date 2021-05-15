<?php

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Collection of users',
        'model' => User::class
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

    public function phones(User $user)
    {
        $user->phonenumbers->get();
    }
}
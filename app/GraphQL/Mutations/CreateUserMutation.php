<?php

namespace App\graphql\Mutations;

use App\Models\User;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createUser'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'title' => [
                'name' => 'title',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'author' => [
                'name' => 'author',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'language' => [
                'name' => 'language',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'year_published' => [
                'name' => 'year_published',
                'type' =>  Type::nonNull(Type::string()),
            ],
            'isbn' => [
                'name' => 'isbn',
                'type' =>  Type::nonNull(Type::string()),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $book = new User();
        $book->fill($args);
        $book->save();

        return $book;
    }
}
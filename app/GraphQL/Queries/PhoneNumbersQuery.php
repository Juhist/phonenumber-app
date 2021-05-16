<?php

namespace App\GraphQL\Queries;

use App\Models\PhoneNumber;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PhoneNumbersQuery extends Query
{
    protected $attributes = [
        'name' => 'phoneNumbers',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('PhoneNumber'));
    }

    public function args(): array
    {
        return [
            'field' => [
                'name' => 'field',
                'type' => Type::string(),
                'rules' => ['string']
            ],
            'sorter' => [
                'name' => 'sorter',
                'type' => Type::string(),
                'rules' => ['in:ASC,DESC']
            ],
            'skip' => [
                'name' => 'skip',
                'type' => Type::int(),
                'rules' => ['integer']
            ],
            'take' => [
                'name' => 'take',
                'type' => Type::int(),
                'rules' => ['integer']
            ],             
        ];
    }

    public function resolve($root, $args)
    {
        $query = PhoneNumber::query();
        if (isset($args['field']) && isset($args['sorter'])) {
            if ($args['sorter'] == 'ASC') 
            {
                $query->orderBy($args['field']);
            }
            else {
                $query->orderByDesc($args['field']);
            }
        }
        if (isset($args['skip']) && isset($args['take'])) {
            $query->skip($args['skip'])->take($args['take']);
        }        
        return $query->get();
    }
}
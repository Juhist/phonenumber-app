<?php

namespace App\graphql\Mutations;

use Exception;
use App\Models\User;
use App\Models\PhoneNumber;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class DeletePhoneNumberMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deletePhoneNumber'
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
                'rules' => ['required', 'exists:phone_numbers,id']
            ], 
        ];
    }

    public function resolve($root, $args)
    {
        $phone = PhoneNumber::find($args['id']);
        if ($phone->isDefault) {
            throw new Exception('Default phone number not delete.');
        }
        if (!$phone->delete()) {
            throw new Exception('Error in deleting data.');
        }
        return;
    }
}
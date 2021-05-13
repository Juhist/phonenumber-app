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
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required', 'string']
            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required','email']
            ],
            'phoneNumber' => [
                'name' => 'phoneNumber',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required','phone:HU']
            ],
            'birthOfDate' => [
                'name' => 'birthOfDate',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required','date_format:Y-m-d','before:today']
            ],
            'isActive' => [
                'name' => 'isActive',
                'type' =>  Type::nonNull(Type::boolean()),
                'rules' => ['required']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        DB::beginTransaction();
        if(!$user = User::create($args))
        {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }
        if(!PhoneNumber::create([
            'user_id' => $user->id, 
            'phoneNumber' => $args['phoneNumber'],
            'isDefault' => 1,
            ]))
        {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }        
        DB::commit();
        return $user;
    }
}
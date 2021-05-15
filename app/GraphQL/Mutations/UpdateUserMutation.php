<?php

namespace App\graphql\Mutations;

use Exception;
use App\Models\User;
use App\Models\PhoneNumber;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class UpdateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUser'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required', 'exists:users,id']
            ],            
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => [ 'string']
            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['email']
            ],
            'phoneNumber' => [
                'name' => 'phoneNumber',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['phone:HU']
            ],
            'dateOfBirth' => [
                'name' => 'dateOfBirth',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['date_format:Y-m-d','before:today']
            ],
            'isActive' => [
                'name' => 'isActive',
                'type' =>  Type::nonNull(Type::boolean())
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::find($args['id']);
        if ($args['name'] !== null) $user->name = $args['name'];
        if ($args['email'] !== null) $user->email = $args['email'];
        if ($args['dateOfBirth'] !== null) $user->dateOfBirth = $args['dateOfBirth'];
        if ($args['isActive'] !== null) $user->isActive = $args['isActive'];
        DB::beginTransaction();
        if (!$user->save()) {
            DB::rollBack();
            throw new Exception('Error in saving user data.');
        }
        if ($args['phoneNumber'] !== null) {
            $phoneNumber = PhoneNumber::where(['user_id' => $args['id'], 'isDefault' => 1])->first();
            if (!$phoneNumber) {
                DB::rollBack();
                throw new Exception('Not found phone number.');
            }
            $phoneNumber->phoneNumber = $args['phoneNumber'];
            if (!$phoneNumber->save()) {
                DB::rollBack();
                throw new Exception('Error in saving default phone number data.');
            }
        }
        DB::commit();
        return $user;
    }
}
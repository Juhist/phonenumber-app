<?php

namespace App\graphql\Mutations;

use Exception;
use App\Models\User;
use App\Models\PhoneNumber;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class DeleteUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteUser'
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
        ];
    }

    public function resolve($root, $args)
    {
        DB::beginTransaction();
        if (!PhoneNumber::where('user_id', $args['id'])->delete()) {
            DB::rollBack();
            throw new Exception('Error in deleting data.');
        }
        if (!User::find($args['id'])->delete()) {
            DB::rollBack();
            throw new Exception('Error in deleting data.');
        }
        DB::commit();
        return;
    }
}
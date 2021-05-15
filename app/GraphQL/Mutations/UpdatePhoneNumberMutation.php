<?php

namespace App\graphql\Mutations;

use Exception;
use App\Models\User;
use App\Models\PhoneNumber;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class UpdatePhoneNumberMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updatePhoneNumber'
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
            'phoneNumber' => [
                'name' => 'phoneNumber',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['phone:HU']
            ],
            'isDefault' => [
                'name' => 'isDefault',
                'type' =>  Type::nonNull(Type::boolean()),
                'rules' => ['required']
            ],                 
        ];
    }

    public function resolve($root, $args)
    {
        $phone = PhoneNumber::find($args['id']);
        if ($args['phoneNumber'] !== null) $phone->phoneNumber = $args['phoneNumber'];
        DB::beginTransaction();
        if ($args['isDefault'] !== null) {
            $others = PhoneNumber::where([
                'user_id' => $phone->user_id,
                'isDefault' => 1,
            ]);
            if ($others->count() != 0) {
                if (!$others->update(['isDefault' => 0])) {
                    DB::rollBack();
                    throw new Exception('Error in saving data.');
                }
            }
            $phone->isDefault = $args['isDefault'];
        }
        if (!$phone->save()) {
            DB::rollBack();
            throw new Exception('Error in saving user data.');
        }

        DB::commit();
        return $phone;
    }
}

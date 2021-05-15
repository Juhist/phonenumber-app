<?php

namespace App\graphql\Mutations;

use Exception;
use App\Models\PhoneNumber;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Facades\DB;

class CreatePhoneNumberMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createPhoneNumber'
    ];

    public function type(): Type
    {
        return GraphQL::type('PhoneNumber');
    }

    public function args(): array
    {
        return [
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::int(),
                'rules' => ['required', 'exists:users,id']
            ],
            'phoneNumber' => [
                'name' => 'phoneNumber',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required','phone:HU']
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
        DB::beginTransaction();
        if (!$new = PhoneNumber::create($args)) {
            DB::rollBack();
            throw new Exception('Error in saving data.');
        }
        if ($args['isDefault']) {
            $others = PhoneNumber::where([
                'user_id' => $args['user_id'],
                'isDefault' => 1,
            ])->where('id', '!=', $new->id);
            if ($others->count() != 0) {
                if (!$others->update(['isDefault' => 0])) {
                    DB::rollBack();
                    throw new Exception('Error in saving data.');
                }
            }
        }
        DB::commit();
        return $new;
    }
}
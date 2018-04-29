<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\RatingsModel;
use \App\Models\UsersModel;


class VideoRatingType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'VideoRatingType',
            'description' => 'Video rating: like | dislike',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'videoid' => Types::id(),
                    'userid' => Types::id(),
                    'rating' => Types::int(),
                    'deleted_at' => Types::string(),
                    'user' => [
                        'type' => Types::user(),
                    ],
                ];
            },

            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    public function resolveUser($videorating, $args) {
        $user = new UsersModel();
        return $user->find(['id' => $videorating->userid]);
    }
}
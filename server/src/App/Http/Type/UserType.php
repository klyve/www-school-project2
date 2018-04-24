<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\VideosModel;

class UserType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'User',
            'description' => 'Our users',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'email' => Types::string(),
                    'name' => [
                        'type' => Types::string(),
                    ],
                    'videos' => [
                        'type' => Types::listOf(Types::video()),
                    ],
                    'video' => [
                        'type' => Types::video(),
                        'args' => [
                            'id' => Types::nonNull(Types::id()),
                        ],
                    ]
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


    public function resolveVideos($user, $args) {
        $videosModel = new VideosModel();
        return $videosModel->all([
            'userid' => $user->id
        ]);
    }

    public function resolveVideo($user, $args) {
        $videosModel = new VideosModel();
        return $videosModel->find([
            'id' => $args['id']
        ]);
    }

}
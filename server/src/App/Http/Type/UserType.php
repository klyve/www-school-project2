<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\SubscriptionsModel;
use \App\Models\PlaylistsModel;

class UserType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'UserType',
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
                    ],
                    'subscriptions' => [
                        'type' => Types::listOf(Types::subscriptions())
                    ],
                    'playlists' => [
                        'type' => Types::listOf(Types::playlist())
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


    public function resolveSubscriptions($user, $args) {
        return (new SubscriptionsModel())->all([
            'userid' => $user->id,
        ]);
    }

    public function resolvePlaylists($user, $args) {
        return (new PlaylistsModel())->all([
            'userid' => $user->id,
        ]);
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
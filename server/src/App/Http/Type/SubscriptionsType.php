<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\PlaylistsModel;



class SubscriptionsType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'SubscriptionsType',
            'description' => 'Subscriptions',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'userid' => Types::id(),
                    'playlistid' => Types::id(),
                    'playlist' => Types::playlist(),
                    'user' => Types::user(),
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



    public function resolvePlaylist($sub, $args) {
        $playlistsModel = new PlaylistsModel();
        return $playlistsModel->find([
            'id' => $sub->playlistid
        ]);
    }

    public function resolveUser($sub, $args) {
        $usersModel = new UsersModel();
        return $usersModel->find([
            'id' => $sub->userid
        ]);
    }

}
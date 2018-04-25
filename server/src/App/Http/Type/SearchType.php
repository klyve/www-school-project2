<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\PlaylistsModel;

class SearchType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'SearchType',
            'description' => 'Search query',
            'fields' => function() {
                return [
                    'value' => Types::string(),
                    'limit' => Types::int(),
                    'users' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'A list of users',
                    ],
                    'playlists' => [
                        'type' => Types::listOf(Types::playlist()),
                        'description' => 'A list of playlists that matched the search',
                    ],
                    'videos' => [
                        'type' => Types::listOf(Types::video()),
                        'description' => 'A list of videos',
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


    public function resolveVideos($searchModel, $args) {
        $videosModel = new VideosModel();
        return $videosModel->search([
            'title' => $searchModel->value,
            'description' => $searchModel->value
        ], $searchModel->limit);
    }

    public function resolvePlaylists($searchModel, $args) {
        $playlistModel = new PlaylistsModel();
        return $playlistModel->search([
            'title' => $searchModel->value,
            'description' => $searchModel->value
        ], $searchModel->limit);
    }

    public function resolveUsers($searchModel, $args) {
        $usersModel = new UsersModel();
        return $usersModel->search([
            'name' => $searchModel->value,
        ], $searchModel->limit);
    }

}
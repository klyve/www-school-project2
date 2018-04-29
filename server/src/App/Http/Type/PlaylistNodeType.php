<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\PlaylistVideosModel;
use \App\Models\PlaylistTagsModel;
use \App\Models\TagsModel;


class PlaylistNodeType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'PlaylistNodeType',
            'description' => 'Playlist node',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'playlistid' => Types::id(),
                    'videoid' => Types::id(),
                    'position' => Types::int(),
                    'video' => Types::video(),
                    'deleted_at' => Types::string()
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

    public function resolveVideo($playlistNode, $args) {
        return (new VideosModel())->find([
            'id' => $playlistNode->videoid,
        ]);
    }


}
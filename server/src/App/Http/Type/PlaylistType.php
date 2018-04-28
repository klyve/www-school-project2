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





class PlaylistType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'PlaylistType',
            'description' => 'Search query',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'userid' => Types::id(),
                    'title' => Types::string(),
                    'description' => Types::string(),
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User the playlist belongs to',
                    ],
                    'videos' => [
                        'type' => Types::listOf(Types::video()),
                        'description' => 'Videos associated with the playlist',
                    ],
                    'nodes' => [
                        'type' => Types::listOf(Types::playlistNode()),
                        'description' => 'Playlist nodes',
                    ],
                    'tags' => [
                        'type' => Types::listOf(Types::tags()),
                        'description' => 'Return tags associated with a video',
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


    public function resolveVideos($playlistModel, $args) {
        $pvideoModel = new PlaylistVideosModel();
        $videos = $pvideoModel->all([
            'playlistid' => $playlistModel->id
        ]);
        $ret = [];
        foreach($videos as $video) {
            $ret[] = (new VideosModel())->find([
                'id' => $video->videoid,
            ]);
        }
        return $ret;
    }

    public function resolveUser($playlistModel, $args) {
        $usersModel = new UsersModel();
        return $usersModel->find([
            'id' => $playlistModel->userid,
        ]);
    }

    public function resolveNodes($playlistModel, $args) {
        $pvideoModel = new PlaylistVideosModel();
        $videos = $pvideoModel->all([
            'playlistid' => $playlistModel->id
        ]);
        return $videos;
    }

    public function resolveTags($playlistModel, $args) {
        $videoTags = (new PlaylistTagsModel())->all([
            'playlistid' => $playlistModel->id,
        ]);
        $ret = [];
        foreach($videoTags as $tag) {
            $ret[] = (new TagsModel())->find([
                'id' => $tag->tagid
            ]);
        }
        return $ret;
    }

}
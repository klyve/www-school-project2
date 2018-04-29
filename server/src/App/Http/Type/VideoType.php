<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\VideosModel;
use \App\Models\UsersModel;
use \App\Models\CommentsModel;
use \App\Models\VideoTagsModel;
use \App\Models\TagsModel;
use \App\Models\RatingsModel;


class VideoType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'VideoType',
            'description' => 'Videos',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'userid' => Types::id(),
                    'filevideo' => Types::string(),
                    'filethumbnail' => Types::string(),
                    'filesubtitle' => Types::string(),
                    'title' => [
                        'type' => Types::string(),
                    ],
                    'description' => [
                        'type' => Types::string(),
                    ],
                    'viewCount' => [
                        'type' => Types::string(),
                    ],
                    'comments' => [
                        'type' => Types::listOf(Types::comment()),
                    ],
                    'comment' => [
                        'type' => Types::comment(),
                        'description' => 'Return a comment by id',
                        'args' => [
                            'id' => Types::nonNull(Types::id())
                        ]
                    ],
                    'tags' => [
                        'type' => Types::listOf(Types::tags()),
                        'description' => 'Return tags associated with a video',
                    ],
                    'ratings' => [
                        'type' => Types::listOf(Types::videoRating()),
                        'description' => 'Return ratings: dislike | like | neutral'
                    ],
                    'rating' => [
                        'type' => Types::videoRating(),
                        'description' => 'Return single rating for a given userid',
                        'args' => [
                            'userid' => Types::nonNull(Types::id())
                        ]
                    ],

                    'user' => [
                        'type' => Types::user(),
                    ],
                    'fieldWithError' => [
                        'type' => Types::string(),
                        'resolve' => function() {
                            throw new \Exception("This is error field");
                        }
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

    public function resolveUser($video, $args) {
        $user = new UsersModel();
        return $user->find(['id' => $video->userid]);
    }

    public function resolveComments($video, $args) {
        $comments = new CommentsModel();
        return $comments->all(['videoid' => $video->id]);
    }

    public function resolveRatings($video, $args) {
        $ratings = new RatingsModel();
        return $ratings->all(['videoid' => $video->id]);
    }

    public function resolveRating($video, $args) {
        $ratings = new RatingsModel();
        return $ratings->find(['videoid' => $video->id, 'userid' => $video->userid]);
    }

    public function resolveComment($video, $args) {
        $comments = new CommentsModel();
        return $comments->find(['videoid' => $video->id, 'id' => $args['id']]);
    }


    public function resolveTags($video, $args) {
        $videoTags = (new VideoTagsModel())->all([
            'videoid' => $video->id,
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
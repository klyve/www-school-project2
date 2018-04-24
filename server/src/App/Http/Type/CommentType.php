<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\VideosModel;
use \App\Models\UsersModel;

class CommentType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'CommentType',
            'description' => 'Videos',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'userid' => Types::id(),
                    'videoid' => Types::id(),
                    'content' => Types::string(),
                    'video' => [
                        'type' => Types::video(),
                    ],
                    'user' => [
                        'type' => Types::user()
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

    public function resolveUser($comment, $args) {
        $user = new UsersModel();
        return $user->find(['id' => $comment->userid]);
    }
    public function resolveVideo($comment, $args) {
        $video = new VideosModel();
        return $video->find(['id' => $comment->videoid]);
    }

}
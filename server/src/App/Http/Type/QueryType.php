<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;
use \App\Models\UsersModel;
use \App\Models\VideosModel;
use \App\Models\SearchModel;
use \App\Models\PlaylistsModel;

class QueryType extends ObjectType
{
    public function __construct(){
        $config = [
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => Types::user(),
                    'description' => 'Returns user by id (in range of 1-5)',
                    'args' => [
                        'id' => Types::nonNull(Types::id())
                    ]
                ],
                'video' => [
                    'type' => Types::video(),
                    'description' => 'Return a video by id',
                    'args' => [
                        'id' => Types::nonNull(Types::id())
                    ]
                    
                ],
                'playlist' => [
                    'type' => Types::playlist(),
                    'description' => 'return a playlist by id',
                    'args' => [
                        'id' => Types::nonNull(Types::id()),
                    ],
                ],
                'search' => [
                    'type' => Types::search(),
                    'description' => 'Return search data',
                    'args' => [
                        'value' => Types::nonNull(Types::string()),
                        'limit' => Types::int(),
                    ]
                ],
                'hello' => Type::string()
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }
    public function hello() {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }

    public function search($rootValue, $args, $req, $info) {
        return new SearchModel($args);
    }
    public function user($rootValue, $args, $req, $info) {
        $user = new UsersModel();
        return $user->find([
            'id' => $args['id']
        ]);
    }
    public function video($rootValue, $args, $req, $info) {
        $video = new VideosModel();
        return $video->find([
            'id' => $args['id']
        ]);
    }

    public function playlist($rootValue, $args, $req, $info) {
        $playlist = new PlaylistsModel();
        return $playlist->find([
            'id' => $args['id']
        ]);
    }

    public function deprecatedField() {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }
}
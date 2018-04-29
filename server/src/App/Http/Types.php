<?php namespace App\Http;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use \App\Http\Type\QueryType;
use \App\Http\Type\UserType;
use \App\Http\Type\VideoType;
use \App\Http\Type\CommentType;
use \App\Http\Type\PlaylistType;
use \App\Http\Type\SearchType;
use \App\Http\Type\TagsType;
use \App\Http\Type\PlaylistNodeType;
use \App\Http\Type\VideoRatingType;

/**
 * Class Types
 *
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 *
 * @package GraphQL\Examples\Blog
 */
class Types
{
    // Object types:
    private static $query;
    private static $user;
    private static $video;
    private static $comment;
    private static $playlist;
    private static $search;
    private static $node;
    private static $tags;
    
    /**
     * @return QueryType
     */
    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }

    /**
     * @return UserType
     */
    public static function user()
    {
        return self::$user ?: (self::$user = new UserType());
    }

     /**
     * @return VideoType
     */
    public static function video()
    {
        return self::$video ?: (self::$video = new VideoType());
    }

    /**
     * @return VideoType
     */
    public static function comment()
    {
        return self::$comment ?: (self::$comment = new CommentType());
    }

    /**
     * @return VideoType
     */
    public static function playlist()
    {
        return self::$playlist ?: (self::$playlist = new PlaylistType());
    }

    /**
     * @return VideoType
     */
    public static function tags()
    {
        return self::$tags ?: (self::$tags = new TagsType());
    }

    /**
     * @return VideoType
     */
    public static function search()
    {
        return self::$search ?: (self::$search = new SearchType());
    }

    /**
     * @return NodeType
     */
    public static function playlistNode()
    {
        return self::$node ?: (self::$node = new PlaylistNodeType());
    }

    /**
     * @return VideoRatingType
     */
    public static function videoRating()
    {
        return self::$node ?: (self::$node = new VideoRatingType());
    }





    // Let's add internal types as well for consistent experience

    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return \GraphQL\Type\Definition\IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return \GraphQL\Type\Definition\IntType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * @return \GraphQL\Type\Definition\StringType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param Type $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}
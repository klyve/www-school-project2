<?php namespace App\Http\Type;

use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;


class TagsType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'TagsType',
            'description' => 'Search query',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'text' => Types::string(),
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


}
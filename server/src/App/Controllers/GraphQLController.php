<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;

use \App\Http\Types;


class GraphQLController extends Controller {

  /**
     * @api {Get}  /graphql Get model.
     * @apiName GraphQL Get.
     * @apiDescription 
     * Uses GraphQl to query the database for publicly available attributes on models.
     * https://graphql.org/
     * 
     * @apiGroup GraphQL
     */

    /**
     * @api {Post} /graphql Get model.
     * @apiName GraphQL Post.
     * @apiDescription 
     * Uses GraphQl to query the database for publicly available attributes on models.
     * https://graphql.org/
     * 
     * @apiGroup GraphQL
     */
    public function query(Request $req) {
        $data = $req->all();
        $data += ['query' => null, 'variables' => null];
        if (null === $data['query']) {
            $data['query'] = '{hello}';
        }
        $schema = new Schema([
            'query' => Types::query()
        ]);
        $result = GraphQL::executeQuery(
            $schema,
            $data['query'],
            null,
            $req,
            (array) $data['variables']
        );
        return $result->toArray();
    }
}



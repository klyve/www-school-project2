<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \GraphQL\Type\Schema;
use \GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;

use \App\Http\Types;


class GraphQLController extends Controller {


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
        return $result->toArray($debug);
    }
}



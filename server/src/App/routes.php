<?php
use MVC\Core\Route;
use MVC\Http\Request;

// Route::get('/', 'SimpleJSONController.hello')
//     ->withMiddlewares(['IsAuthenticated']);
Route::get('/hello', 'SimpleJSONController.sayHelloJSON');


Route::get('/', function() {
    return ['hey'];
});



// ->withMiddlewares(['IsAuthenticated'])
//->withValidators(['UserValidations.login']);


Route::post('user/login', 'AuthController.postLogin')
    ->withValidators(['UserValidations.login']);

Route::post('user/register', 'AuthController.postRegister')
    ->withValidators(['UserValidations.register']);


Route::put('/user/password', 'AuthController.putPassword')
    ->withMiddlewares(['IsAuthenticated'])
    ->withValidators(['UserValidations.changePassword']);



// Route::get('adsfgnjwd', 'gnrgwklgweg')
//     ->withMiddlewares(['IsAuthenticated'])
//     ->withValidators(['FileName.function.function.function']);

Route::get('error', function() {
    return \MVC\Http\Response::statusCode(201, "hello world");
});












use \GraphQL\GraphQL;
use \GraphQL\Type\Schema;
use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\Type;

Route::get('graphql', function() {

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echo' => [
            'type' => Type::string(),
            'args' => [
                'message' => Type::nonNull(Type::string()),
            ],
            'resolve' => function ($root, $args) {
                return $root['prefix'] . $args['message'];
            }
        ],
    ],
]);
$schema = new Schema([
    'query' => $queryType
]);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

$variableValues = isset($input['variables']) ? $input['variables'] : null;

try {
    $rootValue = ['prefix' => 'You said: '];
    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
    $output = $result->toArray();
} catch (\Exception $e) {
    $output = [
        'errors' => [
            [
                'message' => $e->getMessage()
            ]
        ]
    ];
}
    return $output;

    // return json_encode([]);
});

Route::post('graphql', function() {
    $queryType = new ObjectType([
        'name' => 'Query',
        'fields' => [
            'echo' => [
                'type' => Type::string(),
                'args' => [
                    'message' => Type::nonNull(Type::string()),
                ],
                'resolve' => function ($root, $args) {
                    return $root['prefix'] . $args['message'];
                }
            ],
        ],
    ]);
    $schema = new Schema([
        'query' => $queryType
    ]);
    
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    
    try {
        $rootValue = ['prefix' => 'You said: '];
        $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
        $output = $result->toArray();
    } catch (\Exception $e) {
        $output = [
            'errors' => [
                [
                    'message' => $e->getMessage()
                ]
            ]
        ];
    }
        return $output;
    
});


// Error class
Route::notFound(function() {
    echo "404 not found";
});

Route::onInternal(function() {
    die("ERROR 500");
    // return MVC\Core\View::render('500');
});

<?php namespace App\Middlewares;


use App\Models as Model;

class TestMiddleware {

    public function run($request, $next) {
        $validation = $request->validate([
          'name' => 'required|min:3|max:40',
          'email' => 'required|min:3|max:40',
        ], [
          'name' => 'Enter a name you goon!',
        ]);

        if($validation->fails()) {
          $res = new \MVC\Http\Response;

          $errors = $validation->errors();

          return $res->send([
            'error' => '135',
            'message' => $errors->firstOfAll(),
          ]);
        }


        $next($request);
    }
}

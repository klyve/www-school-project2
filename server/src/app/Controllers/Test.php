<?php namespace App\Controllers;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Controllers
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use MVC\Core\Controller;
use MVC\Http\Request;
use App\Models as Models;
use MVC\Core\View;
use App\Models\TestModel;

class Test extends Controller {
  
    public function __construct() {
        // die("WORKS");
        // var_dump($req->all());
        echo "Controller created<br />";
    }

    public function hello(Request $fn) {
        // die("Hello world from TEst");
        echo "Hello world from test <br />";
    }

    public function playlist(Request $req, TestModel $test) {
        $data = $req->all(['name', 'name2']);
        $test->find([
            'id' => 3
        ]);

        foreach($test->numbers2 as $numbers) {
            echo "Number: $numbers->number <br />";
        }
        var_dump($test->numbers->number);

        return View::render('index', $data);
    }
}

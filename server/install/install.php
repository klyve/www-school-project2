<!DOCTYPE html>
<html>
<head>
    <title>Krusskontroll / Installing...</title>

<style>
    html {
        font-size: 1.15rem;
    }
    input {
        margin: 5px 0px 5px 0px;
        width: 100%;
        height: 25px;
    }

    .input-install {
        display: none;
        color: white;
        font-size: .9rem;
        text-align: center;
        border: none;
        padding: 0px 30px;
        height: 40px
    }

    .bg-red {
        background-color:  #f44336;
    }
    .bg-blue {
        background-color: #008CBA;         
    }

    #form-button-id:hover {
        cursor: pointer;
    }

    #pre-install-output {
        display: none;
    }

</style>

</head>

<body>

<h1> Kruskontroll / <small> Installing...</small> </h1>

<?php

use \MVC\Database\Migrations;
use \MVC\Database\Seeder;
use \App\Models\UsersModel;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;

define('APP_ROOT', __DIR__."/../src");
$envpath = APP_ROOT."/App/.env";


echo "<pre id='pre-install-output'>";

function getParam($key, $default, $isPassword=false) {

    $printValue = (!empty($_POST[$key]) ? $_POST[$key] : $default . " *");
    $value = (!empty($_POST[$key]) ? $_POST[$key] : $default);
    
    if ($isPassword) {
        echo "$key : ********\n";
        return $value;
    }
    echo "$key : $printValue\n";
    return $value;
}

$isPassword=true;

$dbName = getParam('db-name', 'mvc');
$dbHost = getParam('db-host', 'http://127.0.0.1');
$dbPort = getParam('db-port', '3306');
$dbUser = getParam('db-user', 'root');
$dbPassword = getParam('db-password', '', $isPassword);

$file = fopen($envpath, "w");
if(!$file) 
    die("Can't open .env file");

fwrite($file, "KRUS_DB_NAME=$dbName\n");
fwrite($file, "KRUS_DB_HOST=$dbHost\n");
fwrite($file, "KRUS_DB_PORT=$dbPort\n");
fwrite($file, "KRUS_DB_USER=$dbUser\n");
fwrite($file, "KRUS_DB_PASS=$dbPassword\n"); // @TODO hash and salt password

fclose($file);

$adminName = getParam('admin-name', 'admin');
$adminEmail = getParam('admin-email', 'admin@kruskontroll.no');
$adminPassword = getParam('admin-password', '1234', $isPassword);

echo "\n * = default\n\n";


require_once APP_ROOT.'/mvc/bootstrap.php';
require_once APP_ROOT.'/App/config.php';

echo "Requiring".APP_ROOT."/mvc/bootstrap.php\n";
echo "Requiring".APP_ROOT."/App/config.php\n\n";

Migrations::runCLI(['refresh']);
Seeder::runCLI(['refresh']);

function createAdministrator($name, $email, $password) {

    $usersModel = new UsersModel;

    $user = $usersModel->find([
        'email' => $email
    ]);

    if($user->id) {
        echo "PANIC: Email already exists";
        return die();
    }
    
    $userid = $user->create([
        'name' => $name, 
        'email' => $email,
        'password' => Hash::password($password)
    ]);

    if (!$userid) {
        echo "PANIC: Could not insert administrator into the database";
        return die();
    }
}
createAdministrator($adminName, $adminEmail, $adminPassword);

echo "</pre>";

echo "<h2>Installation success</h2>"

?>


<form method="get" action="/">
    <input id="btn-home-id"
           type="submit"
           class="input-install bg-blue"
           value="Home page"/>
</form>


<form method="get" action="/install">
    <input id="btn-back-id"
           type="submit"
           class="input-install bg-red"
           value="Back"/>
</form>


</body>
<script>
    window.addEventListener('load', e => {

        window.setTimeout(() =>  {
            document.getElementById('pre-install-output')
                    .style
                    .display = 'block';
        }, 500)

        window.setTimeout(() => {
            Array.from(document.getElementsByClassName('input-install'))
                 .forEach(btn => {
                    btn.style
                       .display = 'inline-block';
                 })
        }, 1000)
    })
</script>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Krusskontroll - Success</title>

<style>
    html {
        font-size: 1.15rem;
    }
    input {
        margin: 5px 0px 5px 0px;
        width: 100%;
        height: 25px;
    }

    #form-button-id {
        color: white;
        font-size: .9rem;
        text-align: center;
        display: inline-block;
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

</style>

</head>

<h1> Kruskontroll / <small>Installation success</small> </h1>

<?php
echo "<pre>";

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

$APP_FOLDER = "../src/App/.env";
$file = fopen($APP_FOLDER, "w");
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

echo "\n * = default\n";
echo "</pre>";



?>


<form method="get" action="/">
    <input id="form-button-id"
           type="submit"
           class="input-install bg-blue"
           value="Home page"/>
</form>


<form method="get" action="/install">
    <input id="form-button-id"
           type="submit"
           class="input-install bg-red"
           value="Back"/>
</form>


<body>

</body>
</html>

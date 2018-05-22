## NOTE: Reading markdown on Bitbucket is not optimal! 

For the full experience read this document on HackMD instead -> https://hackmd.io/zHnwIapWTMGm08nnd-PcOQ?view - 2018-05-03

<br>

# Table of Contents

[TOC]

<br>

# Kruskontroll - Prosjekt 2 IMT2291 våren 2018

**Prosjektdeltakere**

Bjarte Klyve Larsen **[Klyve](https://github.com/klyve)**

Eldar Hauge Torkelsen **[eldarht](https://github.com/eldarht)**

Jonas J. Solsvik **[arxcis](https://github.com/arxcis)**

<br>


# Introduction

## 1.1 @TODO Test LIVE here
@TODO


## 1.2 LIVE API Documentation 

hosted here --> [arxcis.github.io/ourproject](https://arxcis.github.io/imt2291-project2-videosite/) 


<br>


## 2. Prerequisites


- MacOS, Linux or Docker (Not tested with Windows)
- node package manager(npm)
- apidoc  - node.js documentation tool
- ava     - node.js test tool
- PHP 5.4 minimum
- Apache, nginx or similar hosts to run the project
- MySQL Daemon - for database
- Python3 - for build scripts and environment setup.
- Chrome, Brave (Safari and Firefox currently not working)
- Bower  - for installing webcomponents in Polymer


<br>


## 3. Setup Development Environment

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.


<br>


Clone repo
```
$ git clone git@bitbucket.org:klyve/imt2291-prosjekt2-v2018.git <project-folder>
$ cd <project-folder>
```

<br>


Set environment variables
```
$ pip3 install -r requirements.txt

$ python3 krustool.py --init
KEY          : (default)
-------------------------
KRUS_WEB_HOST: (127.0.0.1) 
KRUS_WEB_PORT: (8080) 
KRUS_API_HOST: (127.0.0.1) 
KRUS_API_PORT: (4000) 
KRUS_DB_HOST : (127.0.0.1) 
KRUS_DB_PORT : (3306) 
KRUS_DB_NAME : (mvc) 
KRUS_DB_USER : (root) 
KRUS_DB_PASSWORD: () 

$ source .env
aliased python3 /Users/jsolsvik/git/kruskontroll/prosjekt2/krustool.py -> krustool
```

<br>


Fetch npm, composer and bower dependencies
```
$ krustool --fetch
```

<br>


Run migrations and seed the database with testdata
```
$ krustool --migseed
```

<br>


Run API server in e.g `server/` or `dist/`
```
$ krustool --serve-api <path>
```

<br>

Run Polymer server in e.g `app/`
```
$ krustool --serve-web <path>  
```

<br>


Run AVA Tests for the RestAPI endpoints
```
$ krustool --test-all
$ krustool --test <path>  # Path to ava test file. Found in apitests/
```


<br>



## 4. Build and package application

<br>


Build application to `dist/`
```
$ krustool --build-only
$ krustool --serve-api dist/
```

<br>


Package `dist/` folder to a zip file
```
$ krustool --zip 1.0
$ ls -al
...
-rw-r--r--  kruskontroll-1.0.zip   
...
```

<br>



## 5. Install application

1. Serve the `dist/` folder somewhere
2. Go to `http://<hostname>/install/index.php`

3. Fill-in installation setup form


<a href="https://imgbb.com/"><img src="https://image.ibb.co/kQAHdn/install_script.png" alt="install_script" border="0"></a>


<a href="https://imgbb.com/"><img src="https://image.ibb.co/dFcxdn/install_success.png" alt="install_success" border="0"></a>

The installation has now successfully created the database, and created an admin user in the database.

4. Click on the 'Home Page'-button and you should see the front-page.


*NOTE:* The installation script deletes itself. To install again, you have to build the distribution again.


<br>


# Back-End highlights


## 1. UML Database Diagram

<a href="https://ibb.co/jPDsB7"><img src="https://preview.ibb.co/e08QW7/uml_Database.png" alt="uml_Database" border="0"></a>


<br>


## 2. Controllers

Each controller represents a collection in the database

<a href="https://ibb.co/fWOXdn"><img src="https://image.ibb.co/i6inB7/Screen_Shot_2018_05_03_at_21_39_42.png" alt="Screen_Shot_2018_05_03_at_21_39_42" border="0" height=400 ></a><br />

Each Controller method represents an server API endpoint

*From UsersController.php*
```php
public function getUser(UsersModel $user, Request $req) {
    $myuser = $user->find([
        'id' => $req->input('id'),
    ]);
    return Response::statusCode(200, $myUser);
}
```
*NOTE*: This endpoint not actually used. We use GraphQL instead. More on this later.

<br>

## 3. Generator toolkit

The toolbox has a few handy functions, migrations and seeding
You can run these by typing `php toolkit command:subcommand` in the terminal


fileList is optional and is a space separated list of classNames, if none is provided it will run all the files.
migration should always be done before seeding to ensure the tables exists.

```
Command list

migrate:up <fileList>
migrate:down <fileList>
migrate:refresh <fileList>

seed:up <fileList>
seed:down <fileList>
seed:refresh <fileList>

```

<br>


## 4. Migrations

Migrations is a quick and simple way to add tables to the database
To create a migration create a migration class inside /App/Database/Migrations

<a href="https://ibb.co/ghOjr7"><img src="https://image.ibb.co/cNmhdn/folder_migrations.png" alt="folder_migrations" height=400 border="0"></a>


Name the file the same name as the className.
Every migrations file require a up and down function to run.
Use Schema to create tables
```php
<?php namespace App\Database\Migrations;

use \MVC\Database\Schema;

class TestMigrations {

    public function up() {
        Schema::create('test', function($table){
            $table->primary('id');
            $table->string('testString');
            $table->string('hello');
        });
    }

    public function down() {
        Schema::destroy('test2');
    }

}
```

<br>


## 5. Seeding
Seeding is a quick and simple way to add content to the database
To created a migration create a migration class inside /App/Database/Seeder

<a href="https://ibb.co/gCTHB7"><img src="https://image.ibb.co/iPeePS/folder_seeder_png.png" alt="folder_seeder_png" height=400 border="0"></a>

Name the file the same name as the className.
Every Seeding file require a up and down function to run.

Use Models to create the database information as shown below.

```php
use \MVC\Database\Schema;
use \App\Models as Models;

class TestSeeder {


    public function up() {
        Schema::insert(function(Models\NumbersModel $model) {
            $model->uid = 3;
            $model->number = 3852628;
            $model->save();
        });
    }
    public function down() {
        Schema::truncate('numbers');
    }
}
```

<br>

## 6. Locales - Multi-languages support

Different language strings are created in the `locales/` folder

<a href="https://imgbb.com/"><img src="https://image.ibb.co/b92r4S/locales.png" height=240 alt="locales" border="0"> </a>


Here is an example of a `en.json` file
```json
{
      "user": {
        "email_exists": "Email already exists"
      }
}
```
And the corresponding `no.json` file
```json
{
    "user": {
      "email_exists": "Epost allerede i bruk"
    }
}
```


This can now be referenced anywhere in the code through the `Language` class
```php
Language::get("user.email_exists");
```

Depending on which language is setup, the correct string will be shown.

<br>


## 7. Krustool Command Line Tool

To use the tool, source the .env file. The .env file is generated by the tool when you run `python3 krustool.py  --init`
```
$ source .env
aliased python3 /Users/jsolsvik/git/kruskontroll/prosjekt2/krustool.py -> krustool
```

You can get a list of all your options 
```
$ krustool --help
usage: krustool.py [-h] [-i] [-z ZIP] [-l] [-f] [-b BUILD] [-bo] [-m]
                   [-t TEST] [-ta] [-sw SERVE_WEB] [-sa SERVE_API] [-d] [-db]

optional arguments:
  -h, --help            show this help message and exit
  -i, --init            Setting up the development environment
  -z ZIP, --zip ZIP     <version>
  -l, --list            List environment variables
  -f, --fetch           Fetch depencies from bower, npm and composer
  -b BUILD, --build BUILD
                        <version>
  -bo, --build-only     Build only to /dist
  -m, --migseed         migrate:refresh + seed:refresh
  -t TEST, --test TEST  Run specific test
  -ta, --test-all       Run all tests
  -sw SERVE_WEB, --serve-web SERVE_WEB
                        <webserver-path>
  -sa SERVE_API, --serve-api SERVE_API
                        <apiserver-path>
  -d, --docker          Run docker-compose up
  -db, --dockerbuild    Run docker-compose up + build
```

The tool does a lot of the tasks which we found repetitive when developing the application. Originally it had just a few commands, but was really easy to expand the tool to do more and more. 

Python makes this easy by providing a convenient argument parser from the standard library

*ArgumentParser example:*

```python
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("-i",
                    "--init", 
                    help="Setting up the development environment", 
                    action="store_true")

parser.add_argument("-z",
                    "--zip", 
                    nargs=1, 
                    help="<version>")

argv = parser.parse_args()

if argv.zip:
    zip()

elif argv.init:
    init()
```

<br>



## 8. GraphQL types

To support the GraphQL endpoint from the back-end we have to define a lot of 'types' which will resolve the GraphQL paths. We make one type for each collection, like we do with Controllers, Migrations and Seeders

<a href="https://imgbb.com/"><img src="https://image.ibb.co/jMyjr7/folder_types.png" alt="folder_types" border="0" height=400></a>


Here is an example from UserType.php constructor
```php
use \GraphQL\Type\Definition\ObjectType;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\Type;
use \App\Http\Types;

class UserType extends ObjectType {
  public function __construct() {
    $config = [
      'name' => 'UserType',
      'description' => 'Our users',
      'fields' => function() {
        return [
          'id' => Types::id(),
          'email' => Types::string(),
          'name' => [
            'type' => Types::string(),
          ],
          'videos' => [
            'type' => Types::listOf(Types::video()),
          ],
          'subscriptions' => [
            'type' => Types::listOf(Types::subscriptions())
          ],
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
// More resolve functions here.....
}
```
The config object which is constructed here dictates how the Graph can be traversed in a typical GraphQL statement.

To make sure you can continue to traverse the graph into other types, and from those types go further to other types and so on, we have to write resolve functions like this

```php
public function resolveSubscriptions($user, $args) {
    return (new SubscriptionsModel())->all([
        'userid' => $user->id,
    ]);
}
```

Eksempel på GraphQL query som traverserer UserType.php
```
user(id:1) { 
  name, 
  subscriptions { 
    playlist { 
      title 
    } 
  } 
}  
```

Resultat

```JSON
"user": {
  "name": "username0",
  "subscriptions": [
  {
    "playlist": {
      "title": "playlist title1"
    }
  }
}
```

Her får vi tilbake alle subscriptions for en gitt bruker. I tillegg får vi mulighet til å hente data om spillelisten som brukeren har abonnert på i samme query. Dette gir oss samme funksjonalitet som SQL Join gir oss, men på en mer elegant måte.


<br>


## 9. Temp storage during Video Upload


# RestAPI and Server Endpoints

## 1. AVA Testing Framework


We are using the NodeJS AVA test framework to write test for the API endpoints. 

<a href="https://imgbb.com/"><img src="https://image.ibb.co/cz3WM7/folder_tests.png" alt="folder_tests" border="0" height=350></a> <br />


AVA should be installed as a command line tool using `npm`
```
npm install -g ava
```

Example of a test from `users.test.js`
```javascript
const credentials = {
    email: guid()+'@stud.ntnu.no',
    name: 'bjarte',
    password: 'somepassword',
    newpassword: 'hello123'
};

test.serial('Log in a user', async (t) => {
    t.plan(10);
    const res = await axios.post(`${API}/user/login`, credentials)
    t.is(res.status, 200, `Expected status code 200 got ${res.status}`);

    testDataIntegrity(res.data, ['email', 'name', 'token', 'usergroup'], t);
    
    userToken = res.data.token
    t.pass();
});
```

Here is an output of all tests. 
NOTE: The tool will start a watch-task on the tests.  CTRL+C to stop.

```
$ krustool --test-all

# Re-running migrations and seeding...

✔ comments › Post comment (316ms)
✔ comments › Edit comment (291ms)
✔ comments › Delete comment (311ms)
✔ playlists › Create playlist (170ms)
✔ playlists › Check if playlist was created (158ms)
✔ playlists › Update playlist (122ms)
✔ playlists › Check if playlist updated correctly (200ms)
✔ playlists › Delete playlist
✔ playlistvideos › Add video to playlist (110ms)
✔ playlistvideos › Check if video was added to playlist (180ms)
✔ playlistvideos › Remove video from playlist
✔ playlistvideos › Check if video was removed from playlist (227ms)
✔ playlistTag › Add tag to playlist (832ms)
✔ playlistvideos › Change playlist order
✔ playlistvideos › Check if playlist order changed correctly (133ms)
✔ playlistvideos › Change playlist order back (110ms)
✔ playlistvideos › Check if playlist order changed back again correctly
✔ playlists › Check if playlist was deleted
✔ playlistTag › Delete tag from playlist (392ms)
✔ videoratings › Like video (255ms)
✔ videoratings › Check if video is liked exactly one time (113ms)
✔ videoratings › Check like and dislike counts before (168ms)
✔ videoratings › Dislike video (124ms)
✔ videoratings › Check if video is disliked exactly one time (228ms)
✔ users › register a user (508ms)
✔ users › Log in a user (188ms)
✔ users › Refresh token

✔ videoTags › Add tag to playlist (942ms)
✔ videoratings › Check like and dislike count after  (177ms)
✔ users › Change password of user (524ms)
✔ videoratings › Delete like (151ms)
✔ videoratings › Check if like is Deleted (121ms)
✔ users › Update user email and name (165ms)
✔ users › Delete User (110ms)
- users › Log out
- users › Check if logged out
- users › Log in again
✔ videoTags › Delete tag from playlist (540ms)
✔ videoratings › Check if no like is registered by default
✔ videos › Upload thumbnail file (1.4s)
✔ videos › Upload video file (114ms)
✔ videos › Post video
✔ videos › Check if video was created
✔ videos › Put video
✔ videos › Check if video was updated
✔ videos › Delete video
✔ subscriptions › Subscribe to playlist
✔ subscriptions › Unsubscribe from playlist

45 tests passed [16:03:47]
3 tests todo
```

AVA js docs - https://github.com/avajs/ava - *2018-05-03*


<br>



## 2. apiDoc Documentation Framework

This framework documents our API endpoints. We use special comments above all Controller.Functions. Apidoc is installed as a command line tool using `npm`.
```
npm install -g apidoc
```

Standing in the root directory you can now run this command
```
apidoc -i server/ -o apidoc/ --verbose -c server/ -e .*vendor
```

Which will generate a new .gitignored folder `apidoc/`
To open the documentation
```
cd apidoc
open index.html
```

This will reveal the apidoc site for our project

<a href="https://ibb.co/npqHB7"><img src="https://preview.ibb.co/jz564S/Screen_Shot_2018_05_03_at_21_37_10.png" alt="Screen_Shot_2018_05_03_at_21_37_10" border="0"></a><br />


APIDocs documentation - http://apidocjs.com - *2018-05-03*

<br>


## 3. GraphQL Endpoint

The GraphQL endpoint replaces most if not all tranditional GET endpoints. It does more than just replace though. When you have a GraphQL endpoint you can compose the data you want in your response in new ways without changing anything on the server.

We use the GraphiQL tool for Chrome to do test queries our GraphQL endpoint

<a href="https://ibb.co/gbUzPS"><img src="https://preview.ibb.co/h1Nayn/graphiql.png" alt="graphiql" border="0"></a>

We can also use Postman for the same purpose, but it is not as convenient since we have to write a json object with the whole query on a single line.


GraphiQl chrome extension - https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en - 2018-05-03

GraphQL docs - https://graphql.org/learn/ - *2018-05-03*


<br>


## 4. Authentication - JWT (JSON Web Token) 

When a user logs in we generate a JWT token which is returned with the HTTP response

*From AuthController.php*
```php
$myUser->token = Hash::JWT(["key" => 'userid', 'value' => $myUser->id]);  
return Response::statusCode(200, $myUser);
```

In the browser, we store this token in the LocalStorage. Whenever the user wants to create a request which requires authenticationg, the token has to be supplied alongside the request.

```javascript
const res = await axios.post(`${API}/user/login`, credentials)
token = res.token // save token for later
```

When the user wants to update his credentials later on.
```javascript
const config = { 
    headers: {
        Authorization: 'Bearer ' + token;        
    }}
};

await axios.put(`${API}/user`, updatedCredentials, config)
```

To validate the user, the server will validate the token on every request which requires authorization

*From Request.php*
```php
public function token() {
    if($this->hasToken() && Hash::verifyJWT($this->_token))
        return Hash::getJWT($this->_token);
    return null;
}
```
Example of how a user is validated.

*From UsersController
```php
public function putUser(UsersModel $user, Request $req) {

    $userid =  $req->token()->userid; // Could be a valid userid or null

    $updatedUser = $user->find([
        'id' => $userid           // if null, no user is found here
    ]);

    if (!$updatedUser->id) {
        return Response::statusCode(HTTP_NOT_FOUND, "Could not find $userid");
    }

    $updatedUser->name = $req->input('name');
    $updatedUser->email = $req->input('email');
    $updatedUser->save();

    return Response::statusCode(HTTP_OK, "Updated User");
}
```

If the user is not found with the userid returned by the token, the token is invalid. Every token should represent exactly one user.


<br>

## 5. HTTP Method semantics

***GET***
* We never actually use GET. Only for testing purposes. This could be used for fetching data. 

***POST***
* Routes which create new entry in the database.
* Log in existing user, we don't want credentials in the url.
* GraphQL to send queries.

***PUT***
* Update existing entry in the database. The entry has to be at a know location. Location can be a given `videoid`and `userid` for instance.
* Create a subscription between a user and playlist. The reason why we want to do this as a PUT instead of POST is that the 'location' of the subscription is already know before you create it. It is like a toggle switch which can be on or off. In fact when you create a subscription, you are just updating an existing subscription which was in an implicit off-state.

***DELETE***
* Mark a database entry as deleted. We never delete a database entry right away. More discussion on this later.

***OPTIONS***
    * Used by the browser to check which headers are allowed. Check if Allow Access Control origin is enabled. 
* Important for Cross Origin Resource Sharing(CORS). This was relevant for us during development since we are serving Polymer and the RestAPI on different servers.


```php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-OriginalMimetype, X-OriginalFilename, X-OriginalFilesize');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');

```

<br>

# Front-end highlights

## 1. List of dependencies

bower.json
```
  "dependencies": {
    "polymer": "Polymer/polymer#^2.0.0",
    "polymer-redux": "^1.0.5",
    "app-route": "PolymerElements/app-route#^2.1.0",
    "brum-global-variable": "SieBrum/brum-global-variable#^1.0.6",
    "iron-pages": "PolymerElements/iron-pages#^2.1.1",
    "slate-font-awesome": "JeffLeFoll/slate-font-awesome#^4.7.3",
    "iron-form": "PolymerElements/iron-form#^2.3.0",
    "paper-input": "PolymerElements/paper-input#^2.2.2",
    "axios": "^0.18.0",
    "paper-toast": "PolymerElements/paper-toast#^2.1.1",
    "paper-button": "PolymerElements/paper-button#^2.1.1",
    "paper-progress": "PolymerElements/paper-progress#^2.1.1",
    "paper-header-panel": "PolymerElements/paper-header-panel#^2.1.0",
    "paper-tabs": "PolymerElements/paper-tabs#^2.1.1",
    "file-drop-zone": "PolymerVis/file-drop-zone#^2.0.3"
  },
 ```

package.json
```
  "dependencies": {
    "axios": "^0.18.0",
    "json-to-graphql-query": "^1.3.0",
    "redux": "^4.0.0",
    "redux-logger": "^3.0.6",
    "redux-thunk": "^2.2.0"
  },
```

## 2. Installation script

The installation script is run by visiting the `http://<hostname>/install/index.php` route, as shown in the Introduction section.

<a href="https://imgbb.com/"><img src="https://image.ibb.co/ePY64S/folder_install.png" alt="folder_install" border="0"></a>

After the install script is run, the install/ folder will delete itself, making sure an installation can only be run once.

The installation script does 3 things:

1. Sets environment variables
```php
$file = fopen($envpath, "w");
if(!$file) 
    die("Can't open .env file");

fwrite($file, "KRUS_DB_NAME=$dbName\n");
fwrite($file, "KRUS_DB_HOST=$dbHost\n");
// ... many more
fclose($file);
```

2. Creates the first admin user.

3. Creates the database for the application and all it's tables.


<br>

## 3. Multi-device live updating 

In the Polymer app folder to start the polymer server we run

```
$ npm start

> app@1.0.0 start /Users/jsolsvik/git/kruskontroll/prosjekt2/app
> npm run serve | npm run watch

> app@1.0.0 watch /Users/jsolsvik/git/kruskontroll/prosjekt2/app
> browser-sync start --proxy localhost:8080 --open --files "**/*.html"

[Browsersync] Proxying: http://localhost:8080
[Browsersync] Access URLs:
 -------------------------------------
       Local: http://localhost:3000
    External: http://172.18.85.64:3000
 -------------------------------------
          UI: http://localhost:3001
 UI External: http://172.18.85.64:3001
 -------------------------------------
[Browsersync] Watching files...


```

The `package.json` files defines which script is to be run when this commando is given.

```json
  // ...

  "scripts": {
    "start": "npm run serve | npm run watch",
    "serve": "polymer serve --port 8080",
    "test": "polymer test",
    "watch": "browser-sync start --proxy localhost:8080 --open --files \"**/*.html\""
  },

// ...
```
3 cool things happen because of what we define here:

1. Default browser opens automatically with the page of the application
2. Browser updates automatically when something changes
3. Other devices on your local network may access the application if they know the IP of the host machine. This is really usefull for testing on mobile devices.


Example of multi device development

<a href="https://ibb.co/cVnY17"><img src="https://preview.ibb.co/g6qWon/IMG_20180503_192537.jpg" alt="IMG_20180503_192537" border="0"></a>



</br>


# Discussion and future work

## 1. Why we use POST GraphQL instead of GET GraphQL

In this project we use `GraphQL` for fetching stuff from the database. The semantics of HTTP methods would suggest that we should use a GET /GraphQL route. However the way urlencoding works does not make this a practical solution.

The following GraphQL query
```json
/graphql?query={
  video(id: 2) {
    id
    user {
      id
      name    
    } 
  }
}
```
Would be urlencoded to the following
```
graphql%3Fquery%3D%7B%0D%0A++video%28id%3A+2%29+%7B%0D%0A++++id%0D%0A++++user+%7B%0D%0A++++++id%0D%0A++++++name++++%0D%0A++++%7D+%0D%0A++%7D%0D%0A%7D
```
This makes the URL very ugly. We support this, but in all of our request sent by the front-end we use `POST /graphql`.


It is important to support GET though, as the URL will be more easily shared as a reference to a given set of data.


<br>


## 2. Broader browser compability

**Safari live reloading bug**

When developing the Polymer front-en we ran into issues with Safari going into an infinite loop, not showing anything, when using the npm start with live reloading. It would be worth looking into fixing this bug. For now we just switched over to Chrome.


**Firefox missing thumbnails bug**

When loading the Polymer front-end with firefox no thumbnails are showing up. Also there was no error in the console. We do not know why this is happening

<a href="https://ibb.co/fG6zr7"><img src="https://preview.ibb.co/comzr7/Screen_Shot_2018_05_03_at_21_48_55.png" alt="Screen_Shot_2018_05_03_at_21_48_55" border="0"></a>


<br>


## 3. Polymer + Redux as good fit ? 

We found that because Polymer projects tend to rely mainly in .html files it gets a bit clunky to work together with other javascript frameworks. You can't simply use import {javascript} library.

Here is an example of how integrating Redux with Polymer creates wierd code

```
class VideoUpload extends VideosActions(SiteActions(ReduxStore(Polymer.Element))) {

    static get is() { return 'video-upload'; }

    static get properties() {
    return {
      prop1: {
        type: String,
        value: 'kruskontroll-app'
      },

      files: Object
    };
}
```

You have to 'wrap-inherit' from 3 different classes to compose different libraries.

@TODO Explain more why this is the case...

<br>



## 5. Why we never delete stuff

When we 'delete' something in our system, we never actually delete it.
We mark it with a timestamp, which marks the time of when a user wanted it to be deleted.

```php
$deletedUser->deleted_at = date ("Y-m-d H:i:s");
$deletedUser->save();

return Response::statusCode(HTTP_ACCEPTED, "User $userid marked for deletion");
```

I will mention 2 of the reasons for this:
1. We want to be able to revert if a mistake was made. Maybe the user regrets the deletion. Maybe a bug happened. A lot of things can go wrong.

2. Performance - Deleting something might lead to costly reallocations in the database. You don't want this to happen during the hot hours of the day, causing the web app to halt. If you just mark stuff for deletion, you can make sure the costly reallocations of storage happens during off-peak hours in the evening or night.


<br>



## 6. CORS for static files problematic

By setting the correct Access control headers in PHP we managed to enable CORS for `ttp routes. For serving static files hovewer we have a bigger problem. The static files are not served by PHP, but by the the "driver" which is out of PHP's control. 



If you use Apache you have to make the Apache host able to do CORS.
.htaccess*
```
 Header set Access-Control-Allow-Origin "*"
 ```

We used `php -S localhost` as host during development, so we did not have any control over this.



<br>


# Oppgaveteksten
Oppgaveteksten ligger i [Wikien til det originale repositoriet](https://bitbucket.org/okolloen/imt2291-project1-spring2018/wiki/).

<br>



# References

### 2018-05-03

* https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/

### 2018-05-01

* https://www.webcomponents.org/element/PolymerElements/iron-pages/elements/iron-pages
* https://github.com/tur-nr/polymer-redux/blob/master/demo/async.html
* https://www.webcomponents.org/element/PolymerElements/paper-input


### 2018-04-18

* https://github.com/RobDWaller/ReallySimpleJWT
* https://github.com/axios/axios
* https://github.com/avajs/ava
* http://carbon.nesbot.com/docs/
# Prosjekt 1 IMT2291 våren 2018

## Test LIVE HERE
®TODO

## Getting Started


### Prerequisites

You need to have these installed.

- PHP 5.4 or above
- Apache or other similar to run the project
- MySQL, for database

Development only:
- Python3 and pipi3


### Installing development environment

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

```
$ git clone <project-url> <project-folder>

$ cd <project-folder>

$ pip3 install -r requirements.txt

$ python3 cmd.py --init
KRUS_WEB_HOST: (127.0.0.1) 
KRUS_WEB_PORT: (8080) 
KRUS_API_HOST: (127.0.0.1) 
KRUS_API_PORT: (4000) 
KRUS_DB_HOST: (127.0.0.1) 
KRUS_DB_PORT: (3306) 
KRUS_DB_NAME: (mvc) 
KRUS_DB_USER: (root) 
KRUS_DB_PASSWORD: () 

$ source .env

$ krustool --help
$ krustool --fetch         # Fetch npm, composer and bower packages
$ krustool --migseed       # Run Migrations and Seed the database 

$ krustool --serve <path>  # Run API server from <path>
                           # php -S <KRUS_API_HOST>:<KRUS_API_PORT>`

$ krustool --test-all      # Run all API tests using AVA Javascript tests
```



### Building distribution

```
$ krustool --fetch          # fetch all npm, bower, composer deps
$ krustool --build-only     # build into a dist/ folder
$ krustool --zip <version>  # Create a zip `kruskontroll-<version>.zip` and deleting dist/ folder
$ krustool --build          # All of the above    
```


### Generator toolkit

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




### Migration

Migrations is a quick and simple way to add tables to the database
To created a migration create a migration class inside /App/Database/Migrations
Name the file the same name as the className.
Every migrations file require a up and down function to run.
Use Schema to create tables
```
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

### Seeding
Seeding is a quick and simple way to add content to the database
To created a migration create a migration class inside /App/Database/Seeder
Name the file the same name as the className.
Every Seeding file require a up and down function to run.

Use Models to create the database information as shown below.

```
<?php namespace App\Database\Seeder;

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

## Running the tests

Install phpunit and run the tests with the phpunit command.
Follow instructions on how to do this on the phpunit website.
If you want to generate test converage you need to install x-debug.

Coverage files will be located under `tests/_reports/coverage`

Run tests with `phpunit`
phpunit XML file located in root folder.


### End to end tests

To be written


## Deployment

To be written



# Prosjektdeltakere #

Bjarte Klyve Larsen **[Klyve](https://github.com/klyve)**

Morten Omholt-Jensen **[fill]()**

Henrik Trehjørningen **[fill]()**

Jørgen Hanssen **[fill]()**



# Oppgaveteksten #
Oppgaveteksten ligger i [Wikien til det originale repositoriet](https://bitbucket.org/okolloen/imt2291-project1-spring2018/wiki/).

# Rapporten #
Rapporten til prosjektet legger dere i Wikien til deres egen fork av repositoriet.

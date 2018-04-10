# Prosjekt 1 IMT2291 våren 2018

## Test LIVE HERE
http://web1.bjartelarsen.com


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You need to have these installed.


- PHP 5.4 or above
- Apache or other similar to run the project
- MySQL, for database



### Installing

Clone the repository and run `composer install` This should build the autoload file and the fetch the required components

Open the config file located under `src/app/config.php` Change the configs to your needs.

Incase autoloader has not been run correctly run `composer dumpautoload`

Once this is done you should run the Migration. If you are on a fresh development machine run the seeder aswell.
Check out The topic regarding seeding and migration to read more about this


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

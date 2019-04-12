# MediaINFO, Backend Developer Candidate Test

## World Cities Population

#### by Bojana Lazičić-Protić

\
Create MySQL database user and provide following parameters to parameters.yml file:

- username: test
- password: test
- database: test_api

Unpack project and move into project directory.
\
Install the dependencies and start the server:

```sh
composer install
php bin/console server:start
```

Run commands to create database (test_api) and table (cities):

```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

To populate data into database, run one of commands:

```sh
php bin/console population:get          // this will populate table with top 100 
                                        // most populated cities
php bin/console population:get [num]	// this will populate table with [num] cities
                                        // if city exist in database, it will not be 
                                        // entered again, only 'population' column will
                                        // be updated
```

Try one of available APIs:

- GET	/api/city&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;// display all cities in database
- GET	/api/city/[id]&ensp;&ensp;&ensp;&ensp;&ensp;// display city with id of [id]
- DELETE	/api/city/[id]&ensp;// delete city with id of [id] if exists and redirects to /api/city

To dump data to file and delete data from database, run one of commands (filename is automatically generated in form 'dump_[timestamp]'):

```sh
php bin/console population:clear        // dumps data to default '/tmp/' folder
php bin/console population:clear dir    // dumps data to dir folder
```

Stop the server:

```sh
php bin/console server:stop
```

### Note

I wasn't sure about following tasks, so I didn't include any code for them:

- Create and use Symfony service container for OpenDataSoft requests
- Create and use Symfony service container for managing City entities
Lumen crud api ticket
======

NOTE
----
Assignment Nipa cloud, by lumen framework in implement project.
You can import collection postman in project. 

> 1_Lumen_Nipa_cloud.postman_collection.json

Get Started
-----------

#### Requirements

To run this application on your machine, you need at least:

> docker-compose

> PHP ^7.2

> Lumen ^7.0


Application flow pattern:
---------------------
https://github.com/suraboy/lumen-crud-api-ticket

Run the docker for development:
---------------------
First you need to copy `.env.local` to `.env` for setup environment of appplication

You can start the application and run the containers in the background, use following command inside project root:

```bash
docker-compose up -d
```

Installing Dependencies via Composer
------------------------------------
Run the composer installer:

```bash
docker exec -it lumen-crud-ticket-app composer install
```
or
```bash
docker exec -it lumen-crud-ticket-app composer update
```

Database Setup
------------------------------------
You need to create database `nipa_db` in `http://localhost:8880` and Run the migration artisan command:
```bash
docker exec -it lumen-crud-ticket-app php artisan migrate
```

Running Application
------------------------------------
Open the browser
```bash
http://localhost:8000
```
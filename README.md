# TaskMan

Project: Simple API for a Task Management System
Build a simple RESTful API using Lumen that allows users to manage tasks. The API should handle basic CRUD (Create, Read, Update, Delete) operations for tasks.

## Installation

1. clone the repo 

```sh
git clone git@github.com:munenepeter/taskman.git

```
2. install dependencies & set up .env

```sh
composer install && cp .env.example .env
```
3. set up your database

```sh
psql -U postgres -c "CREATE DATABASE taskman;"
```
4. runn database migrations

```sh
php artisan migrate
```
lastly run a dev server using php, or if you whatever server you prefer

```sh
php -S localhost:8000 -t public
```

now you can proceed to runn all the api calls

> for all the API calls below, we have assumed you are running the php dev server as above, if not please update the URL


#### 1. Get all tasks (`GET /api/v1/tasks`)

```sh
curl -X GET http://localhost:8000/api/v1/tasks

```
#### 1.2 search a record using the title

```sh
curl -X GET "http://localhost:8000/api/v1/tasks?title=task"
```
#### 1.3 paginate
```sh
curl -X GET "http://localhost:8000/api/v1/tasks?items=2"
```
#### 1.4 filter by status

```sh
curl -X GET "http://localhost:8000/api/v1/tasks?status=pending"
```

> PS: you can combine the filters based on your needs for instance
>```sh
>curl -X GET "http://localhost:8000/api/v1/tasks?status=pending&due_date=2024-12-31&title=task&items=5"
>```


#### 2. Get a specific task (`GET /api/v1/tasks/{task}`)

```sh
curl -X GET http://localhost:8000/api/v1/tasks/1 #id is 1
```

#### 3. Create a new task (`POST /api/v1/tasks`)

```sh
curl -X POST http://localhost:8000//api/v1/tasks \
  -H "Content-Type: application/json" \
  -d '{
        "title": "This New",
        "description": "This task description",
        "status": "pending",
        "due_date": "2024-10-31"
      }'
```

#### 4. Update an existing task (`PUT /api/v1/tasks/{task}`)

```sh
curl -X PUT http://localhost:8000/api/v1/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{
        "title": "This Updated",
        "description": "Updated description",
        "status": "completed",
        "due_date": "2024-10-31"
      }'
```

#### 5. Delete a task (`DELETE /api/v1/tasks/{task}`)

```sh
curl -X DELETE http://localhost:8000/api/v1/tasks/1 #1 is the id
```


## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


incase of any improvements and or bugs please feel free to reach out, this was part of an interview stage!

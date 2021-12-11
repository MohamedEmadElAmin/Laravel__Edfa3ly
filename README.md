# Instructions

## 1) Setup Enviroment (Laravel 8) - PHP 7.4

Install the dependencies
```sh
composer update
```

Create the database or you can create it and update .env file
```sh
php artisan make:database
```
Create tables & Initial data
```sh
php artisan migrate
php artisan db:seed productsBundle
php artisan db:seed offersBundle
composer dump-autoload
php artisan serve
```
Run unit tests
```sh
php artisan test
```

Steps
>Register\
>Login\
>Take token and use it as bearer Token in all Authorized requests\
>Add product\
>check cart



## 2) Rest API - Guest (Anonymous)

#### Get Products 
```sh
    curl -i -H 'Accept: application/json' http://127.0.0.1:8000/api/v1/products
```
#### Response
{
    "success": true,
    "data": {
        "count": 6,
        "products": 
        [
           ......
        ]
    }
}

----

#### Get Offers
```sh
    curl -i -H 'Accept:application/json' http://127.0.0.1:8000/api/v1/offers
```
#### Response
{
    "success": true,
    "data": {
        "count": 3,
        "offers": 
        [
            .......
        ]
    }
}

----

#### Register

#### Request
`POST /Offers/`
```sh
    curl -i -H 'Accept:application/json' http://127.0.0.1:8000/api/v1/offers
```
#### Response
{
    "success": true,
    "data": {
        "count": 3,
        "offers": 
        [
            .......
        ]
    }
}

Auth --> Register      /api/v1/register
	--> Login   /api/v1/login

After login take token and use it as bearer Token

Customer 
--> /api/v1/logout
/api/v1/carts/mine
/api/v1/carts/mine/items
/api/v1/carts/mine/items
/api/v1/carts/mine/items

# Instructions

#### 1) Setup Enviroment (Laravel 8) - PHP 7.4

Install the dependencies
```sh
composer update
```

Create the database or you can create it and update .env file
```sh
php artisan make:database
```
create tables & initial data
```sh
php artisan migrate
php artisan db:seed productsBundle
php artisan db:seed offersBundle
composer dump-autoload
php artisan serve
```


#### 2) Rest API

### Get Products (Anonymous)

### Request

`GET /thing/`

    curl -i -H 'Accept: application/json' http://127.0.0.1:8000/api/v1/products
### Response

{
    "success": true,
    "data": {
        "count": 6,
        "products": 
        [
            {
                "name": "T-shirt",
                "price": "30.99",
                "weight_kg": "0.20",
                "shipping_fees": "4.00"
            },
            .......
        ]
    }
}
   


/api/v1/products
	--> Get Offers  /api/v1/offers

Auth --> Register      /api/v1/register
	--> Login   /api/v1/login

After login take token and use it as bearer Token

Customer 
--> /api/v1/logout
/api/v1/carts/mine
/api/v1/carts/mine/items
/api/v1/carts/mine/items
/api/v1/carts/mine/items

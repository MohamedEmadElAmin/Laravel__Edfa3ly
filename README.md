## Instructions


composer update
php artisan make:database
php artisan migrate
php artisan db:seed productsBundle
php artisan db:seed offersBundle
composer dump-autoload


Guest --> Get Products /api/v1/products
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
<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Routing\Route;
use Tests\TestCase;

class APIAuthTest extends TestCase
{

    public function test_db_seed_products(): void
    {
        $this->get(route('products.list'))->assertStatus(200);
        $this->get(route('products.list'))->assertJson(['success' => true]);
        $this->get(route('products.list'))->assertJsonFragment(['count' => 6]);
    }
    public function test_db_seed_offers(): void
    {
        $this->get(route('offers.list'))->assertStatus(200);
        $this->get(route('offers.list'))->assertJson(['success' => true]);
        $this->get(route('offers.list'))->assertJsonFragment(['count' => 3]);
    }


    public function test_register(): void
    {
        $this->post(route('users.register'))
            ->assertJson([
                'success' => false,
                "message" => "Validation Error."
            ]);

        $formData = ["name" => "12" , "email" => "123" , "password" => 222 , 'password_confirmation' => 123];
        $this->post(route('users.register'), $formData)
            ->assertJson([
                'success' => false,
                "message" => "Validation Error."
            ]);
    }

    public function test_login(): void
    {
        $this->post(route('users.login'))
            ->assertJson([
                'success' => false,
                "message" => "Please Enter Valid username & password"
            ]);
    }

    private function generateFormData():array
    {

    }

}

<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Http\Controllers\BaseController\CustomBaseController;
use App\Models\Cart\ShoppingCart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends CustomBaseController
{
    public function register(Request $request): Response
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|string|min:2|max:40',
                'email' => 'required|string|unique:users,email|max:50',
                'password'=> 'required|string|confirmed|min:3|max:64'
            ]);

        //Guard
        if($validator->fails())
            return $this->sendResponse(['data' => $validator->errors() , 'message' => 'Validation Error.',
                'error' => true]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        //$success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        return $this->sendResponse(['message' => 'User register successfully, Please Login.' ,'data' => $success]);
    }

    public function login(Request $request): Response
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            //his cart or new empty cart
            $shoppingCart = ShoppingCart::firstOrCreate(['user_id' => $request->user()->id]);

            $success['shoppingCart'] = $shoppingCart;
            return $this->sendResponse(['message' => 'User login successfully.' , 'data' => $success]);
        }
        else
            return $this->sendResponse(['message'=>'Please Enter Valid username & password' , 'error' => true]);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse(['message' => 'User Logout successfully.']);
    }
}

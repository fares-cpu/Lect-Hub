<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Knightu\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * register a new user
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:5|max:15|alpha_num',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'bio' => 'required|string'
        ]);
        if($validator->fails()) return ApiResponse::validationError($validator->errors()->toArray());
        //storing 
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->bio = $request->bio;
        $user->uuid = Str::uuid();
        try{
            $user->save();
        }catch(\Exception $e){
            return ApiResponse::error(['error' => $e->__toString()],
             "failed to create the user, please try again",
             500);
        }
        return ApiResponse::success(message: "user created successfully");
    }

    public function params(Request $request){
        return ApiResponse::success(
            data: [
                'api/register/' => [
                    'method' => 'post',
                    'route' => 'http://127.0.0.1:8000/api/register',
                    'parameters' => [
                        'username' => 'required|string|min:5|max:15|alpha_num',
                        'email' => 'required|email',
                        'password' => 'required|string|min:8',
                        'bio' => 'required|string'
                    ]
                ]
            ],
            message: 'routes parameters to be sent'
        );
    }
}

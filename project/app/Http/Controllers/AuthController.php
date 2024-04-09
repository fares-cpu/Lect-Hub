<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Knightu\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\Lecture;
use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * the login function.
     */
    public function login(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);
        if($validator->fails()) return ApiResponse::validationError($validator->errors()->toArray());
        //checking
        $user = User::where('email', $request->email)->first();
        if(!$user) return ApiResponse::error(message: "user not found. wrong email.", status: 404);
        if(!Hash::check($request->password, $user->password)){
            return ApiResponse::error(message: "wrong password.",status: 401);
        }
        return ApiResponse::success(data: $user->createToken($user->username)->plainTextToken
            ,message: "successfully logged in");
    }
    /**
     * the name explains everything
     */
    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();
        return ApiResponse::success(message: "successfully logged-out");
    }


    public function upload(Request $request){
        //validation
    $validator = Validator::make($request->all(),[
        'title' => 'string|required|alpha_num',
        'course' => 'string|required|alpha_num',
        'faculty' => 'string|required|alpha_num',
        'university' => 'string|required|alpha_num',
        'type' => 'string|required|alpha_num',
        'info' => 'string',
        'file' => [
            'required',
            \Illuminate\Validation\Rules\File::types(['pdf', 'docx', 'doc', 'ppt', 'pptx'])
            ->max(100*1024)
        ]
    ]);
    if($validator->fails()){
        return ApiResponse::validationError($validator->errors()->toArray());
    }
    //storing into database
    $lecture = new Lecture;
    $lecture->title = $request->title;
    $lecture->course = $request->course;
    $lecture->faculty = $request->faculty;
    $lecture->university = $request->university;
    $lecture->type = $request->type;
    $lecture->info = $request->info;
    $lecture->user_id = $request->user()->id;
    $lecture->uuid = Str::uuid();
    //storing the file into local storage
    $uploaded_file = $request->file('file');
    $filetype = $request->file('file')->extension();
    $filename =  $request->title .'.'. $filetype;
    $filepath = "storage/app/public/".$filename;

    $file = new File;
    $file->filename = $filename;
    $file->filetype = $filetype;
    $file->filepath = $filepath;
    

    



    //storing the file into the database;
    try{
        $lecture->save();
        $file->lecture_id = $lecture->id;
        $file->save();
        Storage::disk('public')->put($filename, file_get_contents($uploaded_file));
        return ApiResponse::success(message: "successfully uploaded.");
    }catch(Exception $e){
        return ApiResponse::error([
            'error'=> $e->__toString(),
            "unable to save your file. try again",
            500]);
        }
    }

    public function upload_params(Request $request){
        return ApiResponse::success(
            data: [
                'parameters'=> [
                    'title' => 'string|required|alpha_num',
                    'course' => 'string|required|alpha_num',
                    'faculty' => 'string|required|alpha_num',
                    'university' => 'string|required|alpha_num',
                    'type' => 'string|required|alpha_num',
                    'info' => 'string',
                    'file' => 'required|mimes:pdf,pptx,docx'
                ]
            ]
        );
    }
}

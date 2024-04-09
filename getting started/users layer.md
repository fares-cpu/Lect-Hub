### Lecture Archive System:
# Users System Layer

## Main Structure:

### 1. Users Table:
<br>
<img src ="./res/Screenshot (21).png" width = 840px hight = 180px>
<br><br>

### 2. Users Table Migration: 
<br>

```php
public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
            $table->string('bio');
            $table->rememberToken();
            $table->uuid();

            $table->primary('id');
        })
    }
```

### 3. User Registration and login:
* first, we've got the user register route and the function in the controller:
```php
// routes/api.php
Route::post('/register', [UserController::class, 'store']);
//Controllers/UserController.php
public function store(Request $request){
    //validation
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|min:5|max:15|alpha_num',
        'email' => 'required|email',
        'password' => 'required|string|min:8',
        'birth_date' => 'required|date'
    ]);
    if($validator->fails()) return ApiResponse::validationError($validator->errors()->toArray());
    //storing 
    $user = new User;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->birth_date = $request->birth_date;
    $user->uuid = Str::uuid();
    try{
        $user->save();
    }catch(\Exception $e){
        return ApiResponse::error(['error' => $e->__toString()],
         "failed to create the user, please try again",
         JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    return ApiResponse::success(message: "user created successfully");
}
```

* then, we have the login route and function: 
```php
//routes/api.php
Route::post('/login', [AuthController::class, 'login']);
//Controllers/AuthController.php
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
        return ApiResponse::error(message: "wrong password.",status: JsonResponse::(some proper status code));
    }
    return ApiResponse::success(data: $user->createToken($user->username)->plainTextToken
        ,message: "successfully logged in");
}
```

* the logout:
```php
//routes/api.php
Routes::post('/logout', [AuthController::class, 'logout'])->middleware('auth:santcum');
//Controllers/AuthController.php
public function logout(Request $request){
    $user = $request->user;
    $user->tokens()->delete();
    return ApiResponse::success(message: "successfully logged-out");
}
```


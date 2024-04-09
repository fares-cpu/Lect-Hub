### Application Layer:
# Lectures

while talking about the Lectures and if we reviewed Application tables, 
we shall notice that we don't need that much of tables, 
and if we are sharp enough, we may notice that the course that is the 
directory that holds the lectures has a (one to many) relationship with the faculties, 
and the same relationship with the Types.
this leads us to the conclusion: \
`we DON'T need that much of tables, all we need is one table holds the files with their: `<br>
`   path, title, course, faculty, university, type. and of course,`<br>
` id, user_id, information about the lecture, uuid, timestamps, `

## Lectures Table:
<img src="./res/Screenshot (26).png"><br>

### Lectures Table Migration:
```php
public function up(){
    Schema::create('lectures', function (Blueprint $table){
        $table->id();
        $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        $table->string('title');
        $table->string('course');
        $table->string('faculty');
        $table->string('university');
        $table->string('type');
        $table->text('info');
        $table->uuid();
        $table->timestamps();
    })
}
```

### Store Function and Route:
```php
//routes/api.php
Route::get('/upload', [LectureController::class, 'store'])->middleware('auth:santcum');
//Controllers/LectureController.php
public function store(Request $request){
    //validation
    $validator = Validator::make($request->all(),[
        'title' => 'string|required|alpha_num',
        'course' => 'string|required|alpha_num',
        'faculty' => 'string|required|alpha_num',
        'university' => 'string|required|alpha_num',
        'type' => 'string|required|alpha_num',
        'info' => 'string',
        'file' => 'required|mimes:pdf'
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
    $lecture->uuid = Str::uuid();

    $path = $request->file('file')->store('lectures');
    $lecture->path = $path;

    try{
        $lecture->save();
    }catch(/Exception $e){
        return ApiResponse::error([
            'error'=> $e->__toString(),
            "unable to save your file. try again",
            500]);
    }
    return ApiResponse::success(message: "successfully uploaded.");
}
```

### Trees Routes:
1. Universities:
```php
//routes/api.php
Route::get('/trees/universities', [LectureController::class, 'uniTree']);
//Controllers/LectureController.php
public function uniTree(Request $request){
    $unicollection = Lecture::select('university')->get();
    if($unicollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $unicollection->toArray();
    return ApiResponse::success($data, "successfully retrieved the universities");
    
}
```

2. Faculties: 
```php
//routes/api.php
Route::get('/trees/faculties', [LectureController::class , 'facTree']);
//controllers/LectureController.php
public function facTree(Request $request){
    $facollection = Lecture::select('faculty')->get();
    if($facollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $facollection->toArray();
    return ApiResponse::success($data, "successfully retrieved the faculties"); 
    
}
```

3. Types: 
```php
//routes/api.php
Routes::get('/trees/types', [LectureController::class, "typeTree"]);
//Controllers/LectureController.php
public function typeTree(Request $request){
    $typecollection = Lecture::select('type')->get();
    if($typecollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $typecollection->toArray();
    return ApiResponse::success($data, "successfully retrieved the types"); 
}
```

4. University/Faculties:
```php
//routes/api.php
Route::get('trees/{university}/faculties', [LectureController::class, 'facultiesOfUniversity']);
//Controllers/LectureController.php
public function facultiesOfUniversity(Request $request, string $university){
    $facollection = Lecture::select('faculty')->where('university', $university)->get();
    if($facollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $facollection->toArray();
    return ApiResponse::success($data, "successfully retrieved data");
}
```

5. Faculty/Universities:
```php
//routes/api.php
Route::get('/trees/{faculty}/universities', [LectureController::class, 'universitiesOfFaculty']);
//Controllers/LectureController.php
public function universitiesOfFaculty(Request $request, string $faculty){
    $unicollection = Lecture::select('university')->where('faculty', $faculty)->get();
    if($unicollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $unicollection->toArray();
    return ApiResponse::success($data, "successfully retrieved data");
}
```

6. University/Faculty/Courses:
```php
//routes/api.php
Route::get('/trees/{university}/{faculty}/courses',[LectureController::class, 'coursesOfFaculty']);
//Controllers/LectureController.php
public function coursesOfFaculty(Request $request, string $university, string $faculty){
    $coursesCollection = Lecture::select('course')
                                ->where('university', $university)
                                ->where('faculty', $faculty)
                                ->get();
    if($courseCollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $courseCollection->toArray();
    return ApiResponse::success($data, "data found");
}
```

7. Faculty/University/Courses:
```php
//routes/api.php
Route::get("/trees/{faculty}/{university}", [LectureController::class, 'coursesOfUniversity']);
//Controllers/LectureController.php
public function coursesOfUniversity(Request $request, string $faculty, string $university){
    $coursesCollection = Lecture::select('course')
                                ->where('faculty', $faculty)
                                ->where('university', $university)
                                ->get();
    if($courseCollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $courseCollection->toArray();
    return ApiResponse::success($data, "data found");
}
```

8. Types/Courses:
```php
//routes/api.php
Route::get('/tree/{type}/courses', [LectureController::class, 'coursesOfType']);
//Controllers/LectureController.php
public function coursesOfTypes(Request $request, string $type){
    $courseCollection = Lecture::select('course')->where('type', $type)->get();
    if($courseCollection->isEmpty()){
        return ApiResponse::notFound();
    }
    $data = $courseCollection->toArray();
    return ApiResponse::success($data, "successfully retrieved data");
}
```

9. Course/Lectures:
```php 
//routes/api.php
Route::get('/tree/{course}/lectures', 'lecturesOfCourse');
//Controllers/LectureController.php
public function lecturesOfCourse(Request $request, string $course){
        $lecturesCollection = Lecture::where('course', $course)->get();
        if($lecturesCollection->isEmpty()){
            return ApiResponse::notFound('no lectures were found');
        }
        $data = $lecturesCollection->toArray();
        return ApiResponse::success($data, 'successfully retrieved lectures');
    }
```


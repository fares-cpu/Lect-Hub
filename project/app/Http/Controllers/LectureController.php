<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use Knightu\Helpers\ApiResponse;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use App\Http\Resources\LectureResource;
use Illuminate\Support\Collection;

class LectureController extends Controller
{


    //*Trees:
    /**
     * returns a tree of universities
     */
    public function universities_tree(Request $request){
        try{
            $unicollection = Lecture::select(['university'])->get();
            $data = $unicollection->toArray();
            return ApiResponse::success($data, "successfully retrieved the universities");    
        }
        catch(NotFound $e){
            return ApiResponse::notFound($e);
        }
    }

    /**
     * returns a tree of faculties
     */
    public function facTree(Request $request){
        $facollection = Lecture::select('faculty')->get();
        if($facollection->isEmpty()){
            return ApiResponse::notFound("no faculties were found");
        }
        $data = $facollection->toArray();
        return ApiResponse::success($data, "successfully retrieved the faculties"); 
        
    }

    /**
     * returns a tree of types
     */
    public function typeTree(Request $request){
        $typecollection = Lecture::select('type')->get();
        if($typecollection->isEmpty()){
            return ApiResponse::notFound('no types were found');
        }
        $data = $typecollection->toArray();
        return ApiResponse::success($data, "successfully retrieved the types"); 
    }

    /**
     * returns a tree of faculties in a university
     */
    public function facultiesOfUniversity(Request $request, string $university){
        $facollection = Lecture::select('faculty')->where('university', $university)->get();
        if($facollection->isEmpty()){
            return ApiResponse::notFound('no faculties were found');
        }
        $data = $facollection->toArray();
        return ApiResponse::success($data, "successfully retrieved data");
    }

    /**
     * returns a tree of universities that has a faculty
     */
    public function universitiesOfFaculty(Request $request, string $faculty){
        $unicollection = Lecture::select('university')->where('faculty', $faculty)->get();
        if($unicollection->isEmpty()){
            return ApiResponse::notFound('no faculties were found');
        }
        $data = $unicollection->toArray();
        return ApiResponse::success($data, "successfully retrieved data");
    }

    /**
     * returns a tree of courses of a faculty
     */
    public function coursesOfFaculty(Request $request, string $university, string $faculty){
        $coursesCollection = Lecture::select('course')
                                    ->where('university', $university)
                                    ->where('faculty', $faculty)
                                    ->get();
        if($coursesCollection->isEmpty()){
            return ApiResponse::notFound('no courses were found');
        }
        $data = $coursesCollection->toArray();
        return ApiResponse::success($data, "data found");
    }

    /**
     * returns a tree of courses of a type
     */
    public function coursesOfType(Request $request, string $type){
        $courseCollection = Lecture::select('course')->where('type', $type)->get();
        if($courseCollection->isEmpty()){
            return ApiResponse::notFound('no courses were found');
        }
        $data = $courseCollection->toArray();
        return ApiResponse::success($data, "successfully retrieved data");
    }

    public function lecturesOfCourse(Request $request, string $course){
        $lecturesCollection = Lecture::where('course', $course)->get();
        if($lecturesCollection->isEmpty()){
            return ApiResponse::notFound('no lectures were found');
        }
        $data = LectureResource::collection($lecturesCollection);
        
        return ApiResponse::success($data, 'successfully retrieved lectures');
    }


    
}


<?php

namespace App\Http\Controllers;
use App\Models\Instructor;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student_Section;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return Instructor::select('instructor_id', 'instructor_name', 'email','phone_number')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::guard('sanctum')->user();
        $instructor_id = $user->instructor_id;
            $instructor = Instructor::Where('instructor_id',$instructor_id)->get(['instructor_id','instructor_name','email','phone_number']);
            if(count($instructor)>0){
                return $instructor;
            }
            else {
                return response([
                    'message'=>'No found instructor by This ID'
                ],401);
            }
        
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $validated_id = htmlspecialchars($id);

        // $instructor = Instructor::Where('instructor_id', $validated_id)->first();

        // if(!$instructor){
        //     return response([
        //         'message'=>'No Instructor found by This ID'
        //     ],401);
        // }

        // $instructor->update($request->all());

        // $response = [
        //     'instructor_id' => $instructor['instructor_id'],
        //     'instructor_name' => $instructor['instructor_name'],
        //     'email' => $instructor['email'],
        //     'phone_number' => $instructor['phone_number']
        // ];

        // return response($response, 201);

        $user = Auth::guard('sanctum')->user();
        $instructor_id = $user->instructor_id;
            $instructor = Instructor::findOrFail($instructor_id);
            
            $validatedData = $request->validate([
                'instructor_name' => 'sometimes|string|max:50',
                'email' => 'sometimes|string|email|max:50',
                'phone_number' => 'sometimes|string|max:15',
                'password' => 'sometimes|string|min:3|confirmed',
                'old_password' => 'required_with:password|string'
            ]);
    
            if(array_key_exists('password',$validatedData)){
                // check if the old password provided is correct
                if (!Hash::check($validatedData['old_password'], $instructor->password)) {
                    return response(['message' => 'Old password is not correct'], 401);
                }
                $validatedData['password'] = bcrypt($validatedData['password']);
            }
            $instructor->fill($validatedData);
            if ($instructor->isDirty()) {
                $instructor->save();
                return response(['message' => 'Instructor information updated successfully']);
            }else{
                return response(['message' => 'No changes made']);
            }
            // $instructor->update($validatedData);
    
            // return response(['message' => 'Instructor information updated successfully']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $instructor = DB::table('instructors')->where('instructor_id', $id);
        if(!$instructor->first())
        {
            return response(['message' => 'Instructor with this ID not found'], 404);
        }
        $instructor->delete();
        return response(['message' => 'Instructor is deleted'], 200);
    }


    public function showSections($instructor_id)
    {
        // $result = DB::select( DB::raw("SELECT section_id, `course_id`, `instructor_name`, `classroom`, `time` FROM sections where instructor_id = $instructor_id ") );
       $result = Section::Where('instructor_id',$instructor_id)->get();
        return $result;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentApiController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:students|email',
            'password' => 'required|confirmed',
            'age' => 'required|numeric'
        ]);

        $student = new Student();

        $student->name = $request->name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->age = $request->age;

        $student->save();
        return response()->json(['status' => 'success', 'message' => 'student saved successfully']);
    }
    public function login(Request $request)
    {
        $student = Student::where('email', $request->email)->first();

        if (isset($student)) {

            if (Hash::check($request->password, $student->password)) {

                return response()->json([
                    'status' => 'succesfully loged in',
                    'token' =>  $student->createToken('auth_token')->plainTextToken
                ]);
            }
            return response()->json(['message' => 'password not matched']);
        } else {
            return response()->json(['message' => 'email not found']);
        }
    }
    public function getstudent()
    {
        return response()->json([
            'message' => 'successful',
            'student' => auth()->user()
        ]);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        // auth()->user()->tokens()->where('id', $tokenId)->delete();
        return response()->json([
            'message' => 'user loged out successfully',
            'student' => auth()->user()->name
        ]);
    }
}

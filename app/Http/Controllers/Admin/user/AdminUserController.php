<?php

namespace App\Http\Controllers\Admin\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::paginate(100);
        return response()->json($user);

    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name'=> 'required|string',
            'password' => 'required|string|min:6',
        ]);

        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
         }

        // Create the new admin after validation passes
        $new_user = new User();
        $new_user->email = $request->email;
        $new_user->name = $request->name;
        $new_user->password = bcrypt($request->password);
        $new_user->save();

    return response()->json([
        'success' => true,
        'message' => "User Created Successfully",
        'user' => $new_user,
    ]);    
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $searchuser = User::findorfail($id);
        if ($searchuser)
        {
            return response()->json($searchuser);
        }
        else
        {
            return response()->json([
                "success"=>false,
                "message"=>"User not found",
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'email' => 'email|unique:users,email,' . $id,
        'name' => 'string',
        'password' => 'nullable|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // Update the user attributes
    $user->email = $request->input('email' , $user->email);
    $user->name = $request->input('name' , $user->name);

    // Update password if provided
    if ($request->has('password') && !empty($request->password)) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => "User Updated Successfully",
        'user' => $user,
    ]);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $searchuser = User::findorfail($id);
        
        if ($searchuser)
        {
            $searchuser->delete();
            return response()->json([
                "success"=>true,
                "message"=>"Deleted successfully",
            ]);
        }
        else
        {
            return response()->json([
                "success"=>false,
                "message"=>"User not found",
            ]);
        }
    }
}

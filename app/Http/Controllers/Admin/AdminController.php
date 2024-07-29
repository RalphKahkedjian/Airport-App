<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin;

use PHPUnit\TextUI\XmlConfiguration\FailedSchemaDetectionResult;
use function Laravel\Prompts\search;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Admin::paginate(4);
        return response()->json($admin);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the request data first
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // Create the new admin after validation passes
    $new_admin = Admin::create($request->all());

    return response()->json([
        'success' => true,
        'message' => "Admin Created Successfully",
        'admin' => $new_admin,
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $searchadmin = Admin::findorfail($id);
        if ($searchadmin)
        {
            return response()->json($searchadmin);
        }
        else
        {
            return response()->json([
                'success'=>false,
                'message'=>'Admin is not found'
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $searchadmin = Admin::findorfail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }else{
            $searchadmin->update($request->all());
            return response()->json([
                    'success'=>true,
                    'message'=>"Updated Successfully",
                    "Admin"=>$searchadmin
                ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $searchadmin = Admin::findorfail($id);
        if ($searchadmin)
        {
            $searchadmin->delete();
            return response()->json([
                "success"=>true,
                "message"=>"Deleted successfully",
            ]);
        }
        else
        {
            return response()->json([
                "success"=>false,
                "message"=>"Admin not found",
                ]);
        }
    }
}

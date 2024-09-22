<?php

namespace App\Http\Controllers\User\ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class UserTicketContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticket=Ticket::paginate(4);
        return response()->json($ticket);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ticket=Ticket::findOrFail($id);
        if($ticket)
        {
            return response()->json([
                'status'=>true,
                'ticket'=>$ticket
            ]);
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'Ticket not found'
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

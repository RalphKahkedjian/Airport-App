<?php
namespace App\Http\Controllers\Admin\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;

class AdminTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::paginate(100);
        return response()->json($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departure' => 'required|string',
            'destination' => 'required | string',
            'flight_number' => 'required|string',
            'seat_number' => 'required|string',
            'price' => 'required|numeric',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'status' => 'required|in:available,booked',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Create the new ticket after validation passes
        $new_ticket = new Ticket();
        $new_ticket->departure = $request->departure;
        $new_ticket->destination = $request->destination;
        $new_ticket->flight_number = $request->flight_number;
        $new_ticket->seat_number = $request->seat_number;
        $new_ticket->price = $request->price;
        $new_ticket->departure_time = $request->departure_time;
        $new_ticket->arrival_time = $request->arrival_time;
        $new_ticket->status = $request->status;
        $new_ticket->save();

        return response()->json([
            'success' => true,
            'message' => "Ticket Created Successfully",
            'ticket' => $new_ticket,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if($ticket){
            return response()->json($ticket);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Ticket Not Found",
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $ticket = Ticket::findOrFail($id);

    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'flight_number' => 'string',
        'seat_number' => 'string',
        'price' => 'numeric',
        'departure_time' => 'date',
        'arrival_time' => 'date',
        'status' => 'in:available,booked',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    // Update the ticket fields, take the old values if not provided
    $ticket->flight_number = $request->input('flight_number', $ticket->flight_number);
    $ticket->seat_number = $request->input('seat_number', $ticket->seat_number);
    $ticket->price = $request->input('price', $ticket->price);
    $ticket->departure_time = $request->input('departure_time', $ticket->departure_time);
    $ticket->arrival_time = $request->input('arrival_time', $ticket->arrival_time);
    $ticket->status = $request->input('status', $ticket->status);

    $ticket->save();

    return response()->json([
        'success' => true,
        'message' => "Ticket Updated Successfully",
        'ticket' => $ticket,
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        if($ticket){
            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => "Ticket Deleted Successfully",
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => "Ticket Not Found",
            ]);
        }

    }
}

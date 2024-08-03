<?php

namespace App\Http\Controllers\Admin\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;


class AdminBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allbookings = Booking::paginate(4);
        if ($allbookings) {
            return response()->json($allbookings);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'ticket_id' => 'required|integer|exists:tickets,id',
            'booking_date' => 'required|date', // Validate booking date
        ]);

        // Validate the form data
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
         // Check if the ticket is available
        $ticket = Ticket::find($request->ticket_id);
        if ($ticket->status !== 'available') {
            return response()->json(['error' => 'Ticket is not available for booking.'], 400);
        }
        
        $booking = new Booking();
        $booking-> user_id = $request->user_id;
        $booking-> ticket_id = $request->ticket_id;
        $booking->booking_date = $request->booking_date; 
        $booking->status = 'booked';
        $booking ->save();

        $ticket-> status ='booked';
        $ticket -> save();
        
        return response()->json([
         'success' => 'Ticket booked successfully.',
         'message'=> true ,
         'booking'=>$booking ,
         'price'=>$ticket->price ,
         'arrival_time'=>$ticket->arrival_time
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $searchforbooking = Booking::findorfail($id);
        if ($searchforbooking) {
            return response()->json($searchforbooking);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Booking Not Found",
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
        $searchforbooking = Booking::findorfail($id);
        if ($searchforbooking) {
            $searchforbooking->delete();
            return response()->json([
                'success' => true,
                'message' => "Booking Deleted Successfully",
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Booking Not Found",
            ]);
        }
    }
}

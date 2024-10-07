<?php

namespace App\Http\Controllers\User\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;

class UserBookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index(Request $request)
    {
        $userBookings = Booking::where('user_id', $request->user()->id)->paginate(4);

        if ($userBookings->isEmpty()) {
            return response()->json(['message' => 'No bookings found'], 404);
        }

        return response()->json($userBookings);
    }

    /**
     * Store a newly created booking for the user.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|integer|exists:users,id',
        'ticket_id' => 'required|integer|exists:tickets,id',
        'quantity' => 'required|integer|min:1|max:10',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $existingBooking = Booking::where('user_id', $request->user_id)
                              ->where('ticket_id', $request->ticket_id)
                              ->first();

    if ($existingBooking) {
        return response()->json(['error' => 'You have already booked this ticket.'], 400);
    }

    $ticket = Ticket::find($request->ticket_id);

    if ($ticket->status !== 'available') {
        return response()->json(['error' => 'Ticket is not available for booking.'], 400);
    }

    if ($ticket->spots < $request->quantity) {
        return response()->json(['error' => 'Not enough spots available.'], 400);
    }

    // Create the booking
    $booking = new Booking();
    $booking->user_id = $request->user_id;
    $booking->ticket_id = $request->ticket_id;
    $booking->quantity = $request->quantity; 
    $booking->status = 'booked';
    $booking->save();

    $ticket->spots -= $request->quantity;
    
    if ($ticket->spots == 0) {
        $ticket->status = 'booked';
    }

    $ticket->save();

    return response()->json([
        'success' => 'Ticket booked successfully.',
        'message' => true,
        'booking' => $booking,
        'price' => $ticket->price,
        'arrival_time' => $ticket->arrival_time,
    ]);
}


    /**
     * Display the specified booking of the authenticated user.
     */
    public function show(string $userId)
    {
        // Fetch bookings for the specified user ID
        $bookings = Booking::where('user_id', $userId)->get();
    
        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No bookings found for this user.',
            ], 404); // Return 404 Not Found
        }
    
        return response()->json($bookings);
    }
    
    /**
     * Update the specified booking for the user.
     */
    public function update(Request $request, string $id)
    {
        // Fetch the user's booking
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found or not authorized.'], 404);
        }

        $old_ticket = Ticket::find($booking->ticket_id);

        if ($old_ticket) {

            $validator = Validator::make($request->all(), [
                'ticket_id' => 'integer|exists:tickets,id',
                'booking_date' => 'date',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $new_ticket = Ticket::find($request->input('ticket_id'));
            if ($new_ticket && $new_ticket->status !== 'available') {
                return response()->json(['error' => 'New ticket is not available for booking.'], 400);
            }

            $old_ticket->status = 'available';
            $old_ticket->save();

            $booking->ticket_id = $request->input('ticket_id', $booking->ticket_id);
            $booking->booking_date = $request->input('booking_date', $booking->booking_date);
            $booking->status = 'booked';

            if ($new_ticket) {
                $new_ticket->status = 'booked';
                $new_ticket->save();
            }

            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'booking' => $booking,
                'old_ticket_status' => $old_ticket->status,
                'new_ticket_status' => $new_ticket->status ?? null,
            ]);
        }
    }

    /**
     * Remove the specified booking for the user.
     */
    public function destroy(string $id, Request $request)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found or not authorized.'], 404);
        }
        $ticket = Ticket::find($booking->ticket_id);
        if ($ticket) {
            $ticket->status = 'available';
            $ticket->save();
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Event $event )
    {
        $attendees = $event->attendees()->latest();

        return AttendeeResource::collection(
            $attendees->load('user')
            ->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event )
    {
        $attendee = attendee::create([
            "user_id"=>1,
            "event_id"=>$event->id
        ] );

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show( Event $event, Attendee $attendee )
    {
        return new AttendeeResource($attendee);
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
    public function destroy( Event $event, Attendee $attendee )
    {
        $attendee->delete();
        return  response(status:204);
    }
}
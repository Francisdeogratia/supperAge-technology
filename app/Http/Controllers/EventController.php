<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\UserRecord;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\Notification;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Display all events
     */
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        // Get upcoming events (public + user's events)
        $upcomingEvents = Event::with(['creator', 'attendees'])
            ->where(function($query) use ($userId) {
                $query->where('privacy', 'public')
                      ->orWhere('created_by', $userId);
            })
            ->where('event_date', '>=', now())
            ->where('status', 'published')
            ->orderBy('event_date', 'asc')
            ->paginate(12);
        
        // Get user's events
        $myEvents = Event::with(['creator', 'attendees'])
            ->where('created_by', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Get events user is attending
        $attendingEvents = Event::with(['creator', 'attendees'])
            ->whereHas('attendees', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'attending');
            })
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(6)
            ->get();
        
        return view('events.index', compact('user', 'upcomingEvents', 'myEvents', 'attendingEvents'));
    }
    
    /**
     * Show create event form
     */
    public function create()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        
        return view('events.create', compact('user'));
    }
    
    /**
     * Store new event
     */
    public function store(Request $request)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'event_date' => 'required|date|after:now',
            'event_time' => 'required',
            'location' => 'nullable|string|max:300',
            'event_type' => 'required|in:online,physical,hybrid',
            'category' => 'required|string|max:50',
            'privacy' => 'required|in:public,private',
            'max_attendees' => 'nullable|integer|min:1',
            'event_image' => 'nullable|string',
            'meeting_link' => 'nullable|url'
        ]);
        
        $event = Event::create([
            'created_by' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'location' => $request->location,
            'event_type' => $request->event_type,
            'category' => $request->category,
            'privacy' => $request->privacy,
            'max_attendees' => $request->max_attendees,
            'event_image' => $request->event_image,
            'meeting_link' => $request->meeting_link,
            'status' => 'published',
            'attendee_count' => 0
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Event created successfully!',
            'event' => $event
        ]);
    }
    
    /**
     * Show single event
     */
    public function show($eventId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }
        
        $user = UserRecord::find($userId);
        $event = Event::with(['creator', 'attendees.user'])->findOrFail($eventId);
        
        // Check if user is attending
        $isAttending = EventAttendee::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('status', 'attending')
            ->exists();
        
        $isCreator = $event->created_by == $userId;
        
        return view('events.show', compact('user', 'event', 'isAttending', 'isCreator'));
    }
    
    /**
     * RSVP to event
     */
    public function rsvp(Request $request, $eventId)
    {
        $userId = Session::get('id');
        $username = Session::get('username');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $event = Event::findOrFail($eventId);
        
        // Check if event is full
        if ($event->max_attendees && $event->attendee_count >= $event->max_attendees) {
            return response()->json(['error' => 'Event is full'], 400);
        }
        
        // Check if already attending
        $existing = EventAttendee::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();
        
        if ($existing) {
            if ($existing->status === 'attending') {
                return response()->json(['error' => 'Already attending'], 400);
            } else {
                // Update status
                $existing->update(['status' => 'attending']);
            }
        } else {
            // Create new attendee
            EventAttendee::create([
                'event_id' => $eventId,
                'user_id' => $userId,
                'status' => 'attending'
            ]);
        }
        
        // Increment count
        $event->increment('attendee_count');
        
        // Notify event creator
        if ($event->created_by != $userId) {
            Notification::create([
                'user_id' => $userId,
                'message' => "{$username} is attending your event: {$event->title}",
                'link' => route('events.show', $eventId),
                'notification_reciever_id' => $event->created_by,
                'read_notification' => 'no',
                'type' => 'event_rsvp',
                'notifiable_type' => Event::class,
                'notifiable_id' => $eventId,
                'data' => json_encode(['event_id' => $eventId, 'user_id' => $userId])
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'RSVP successful!',
            'attendee_count' => $event->attendee_count
        ]);
    }
    
    /**
     * Cancel RSVP
     */
    public function cancelRsvp($eventId)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['error' => 'Not logged in'], 401);
        }
        
        $attendee = EventAttendee::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();
        
        if ($attendee) {
            $attendee->update(['status' => 'cancelled']);
            
            $event = Event::findOrFail($eventId);
            $event->decrement('attendee_count');
            
            return response()->json([
                'success' => true,
                'message' => 'RSVP cancelled',
                'attendee_count' => $event->attendee_count
            ]);
        }
        
        return response()->json(['error' => 'Not attending'], 400);
    }
    
    /**
     * Update event
     */
    public function update(Request $request, $eventId)
    {
        $userId = Session::get('id');
        $event = Event::findOrFail($eventId);
        
        if ($event->created_by != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'nullable|string|max:300'
        ]);
        
        $event->update($request->only([
            'title', 'description', 'event_date', 'event_time', 
            'location', 'event_type', 'category', 'privacy', 
            'max_attendees', 'meeting_link'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully!'
        ]);
    }
    
    /**
     * Delete event
     */
    public function destroy($eventId)
    {
        $userId = Session::get('id');
        $event = Event::findOrFail($eventId);
        
        if ($event->created_by != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $event->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully!'
        ]);
    }


    /**
 * Show edit event form
 */
public function edit($eventId)
{
    $userId = Session::get('id');
    
    if (!$userId) {
        return redirect('/login')->with('error', 'You must be logged in.');
    }
    
    $user = UserRecord::find($userId);
    $event = Event::findOrFail($eventId);
    
    // Check if user is the creator
    if ($event->created_by != $userId) {
        return redirect()->route('events.show', $eventId)->with('error', 'Unauthorized');
    }
    
    return view('events.edit', compact('user', 'event'));
}
}
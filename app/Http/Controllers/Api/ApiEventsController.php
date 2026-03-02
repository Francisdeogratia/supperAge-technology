<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserRecord;

class ApiEventsController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $events = DB::table('events')
            ->leftJoin('users_record', 'events.user_id', '=', 'users_record.id')
            ->where(function ($q) {
                $q->whereNull('events.deleted_at');
            })
            ->orderBy('events.event_date', 'asc')
            ->paginate(20);

        $attendingIds = DB::table('event_attendees')
            ->where('user_id', $userId)
            ->pluck('event_id')
            ->toArray();

        return response()->json([
            'events' => collect($events->items())->map(fn($e) => $this->formatEvent($e, $attendingIds)),
            'total'  => $events->total(),
            'current_page' => $events->currentPage(),
            'last_page'    => $events->lastPage(),
        ]);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;
        $event  = DB::table('events')
            ->leftJoin('users_record', 'events.user_id', '=', 'users_record.id')
            ->where('events.id', $id)
            ->first();

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $isAttending = DB::table('event_attendees')
            ->where('event_id', $id)->where('user_id', $userId)->exists();

        return response()->json(['event' => $this->formatEvent($event, $isAttending ? [$id] : [])]);
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location'    => 'nullable|string|max:255',
            'event_date'  => 'nullable|date',
            'image'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->user()->id;

        $id = DB::table('events')->insertGetId([
            'user_id'         => $userId,
            'title'           => $request->title,
            'description'     => $request->description,
            'location'        => $request->location,
            'event_date'      => $request->event_date,
            'image'           => $request->image,
            'attendees_count' => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $event = DB::table('events')->where('id', $id)->first();

        return response()->json(['event' => $this->formatEvent($event, []), 'message' => 'Event created'], 201);
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->user()->id;
        $event  = DB::table('events')->where('id', $id)->first();

        if (!$event) return response()->json(['message' => 'Event not found'], 404);
        if ($event->user_id !== $userId) return response()->json(['message' => 'Unauthorized'], 403);

        DB::table('events')->where('id', $id)->delete();

        return response()->json(['message' => 'Event deleted']);
    }

    public function attend(Request $request, $id)
    {
        $userId = $request->user()->id;
        $event  = DB::table('events')->where('id', $id)->first();

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $exists = DB::table('event_attendees')->where('event_id', $id)->where('user_id', $userId)->exists();
        if (!$exists) {
            DB::table('event_attendees')->insert([
                'event_id'   => $id,
                'user_id'    => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('events')->where('id', $id)->increment('attendees_count');
        }

        return response()->json(['message' => 'RSVP confirmed', 'is_attending' => true]);
    }

    public function unattend(Request $request, $id)
    {
        $userId = $request->user()->id;
        $deleted = DB::table('event_attendees')->where('event_id', $id)->where('user_id', $userId)->delete();

        if ($deleted) {
            DB::table('events')->where('id', $id)->decrement('attendees_count');
        }

        return response()->json(['message' => 'RSVP cancelled', 'is_attending' => false]);
    }

    private function formatEvent($event, array $attendingIds): array
    {
        return [
            'id'              => $event->id,
            'title'           => $event->title,
            'description'     => $event->description ?? null,
            'location'        => $event->location ?? null,
            'event_date'      => $event->event_date ?? null,
            'image'           => $event->image ?? null,
            'attendees_count' => (int) ($event->attendees_count ?? 0),
            'is_attending'    => in_array($event->id, $attendingIds),
            'created_at'      => $event->created_at,
            'user'            => [
                'id'         => $event->user_id ?? null,
                'name'       => $event->name ?? null,
                'username'   => $event->username ?? null,
                'profileimg' => isset($event->profileimg) && $event->profileimg
                    ? (filter_var($event->profileimg, FILTER_VALIDATE_URL) ? $event->profileimg : url($event->profileimg))
                    : null,
            ],
        ];
    }
}

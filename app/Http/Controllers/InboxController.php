<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class InboxController extends Controller
{
    // Show user inbox
    public function index()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'Please login to view your inbox');
        }
        
        // Get all admin messages for this user
        $messages = DB::table('admin_messages')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get unread count
        $unreadCount = DB::table('admin_messages')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
        
        // Get current user info for navbar
        $user = DB::table('users_record')->where('id', $userId)->first();
        
        return view('inbox.index', compact('messages', 'unreadCount', 'user'));
    }
    
    // Show single message
    public function show($id)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'Please login to view messages');
        }
        
        // Get the message
        $message = DB::table('admin_messages')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
        
        if (!$message) {
            return redirect()->route('inbox.index')->with('error', 'Message not found');
        }
        
        // Mark as read
        if (!$message->is_read) {
            DB::table('admin_messages')
                ->where('id', $id)
                ->update([
                    'is_read' => 1,
                    'read_at' => now()
                ]);
        }
        
        // Get admin info
        $admin = DB::table('users_record')
            ->where('id', $message->admin_id)
            ->first();
        
        // Get current user info for navbar
        $user = DB::table('users_record')->where('id', $userId)->first();
        
        return view('inbox.show', compact('message', 'admin', 'user'));
    }
    
    // Mark message as read
    public function markAsRead($id)
    {
        $userId = Session::get('id');
        
        DB::table('admin_messages')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true, 'message' => 'Message marked as read']);
    }
    
    // Mark all messages as read
    public function markAllAsRead()
    {
        $userId = Session::get('id');
        
        DB::table('admin_messages')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true, 'message' => 'All messages marked as read']);
    }
    
    // Delete message
    public function destroy($id)
    {
        $userId = Session::get('id');
        
        DB::table('admin_messages')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();
        
        return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
    }
    
    // Get unread count (for navbar badge)
    public function getUnreadCount()
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return response()->json(['count' => 0]);
        }
        
        $count = DB::table('admin_messages')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
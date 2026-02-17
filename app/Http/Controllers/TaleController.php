<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskCenter;
use App\Models\TalesExten;
use App\Models\UserRecord;

class TaleController extends Controller
{
    public function showFromTask($task_id)
    {
        $task = TaskCenter::findOrFail($task_id);

        // Fetch tales based on the task's specialcode
        $userTales = TalesExten::where('specialcode', $task->specialcode)->get();

        // Fetch users for share dropdown
        $users = UserRecord::all();

        return view('viewtales', compact('userTales', 'users'));
    }
}


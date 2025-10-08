<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
     public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('admin.activity-log.index', compact('logs'));
    }
}

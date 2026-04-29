<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Entity;

class DashboardController extends Controller
{
    public function index()
    {
        $entityCount = Entity::query()->count();

        return view('super-admin.dashboard', compact('entityCount'));
    }
}

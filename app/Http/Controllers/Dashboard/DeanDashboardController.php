<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DeanDashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.dean.index');
    }
}
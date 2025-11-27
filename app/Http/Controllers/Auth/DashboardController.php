<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $familyTrees = Auth::user()->familyTrees; // Используем отношение, которое нужно добавить в модель User

        return view('dashboard', compact('familyTrees'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        // Asegúrate que sea *role* (no *rol*)
        $this->middleware(['auth','role:ADMIN']);
    }

    public function index()
    {
        return view('admin.dashboard');
    }
}

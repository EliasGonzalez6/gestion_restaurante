<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    // Vista pública del menú
    public function index()
    {
        $categories = Category::with(['menuItems' => function($q) {
            $q->orderBy('id');
        }])->orderBy('id')->get();
        return view('welcome', compact('categories'));
    }
}

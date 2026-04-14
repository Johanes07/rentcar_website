<?php

namespace App\Http\Controllers;
use App\Models\Car;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
   public function index(Request $request)
{
    $cars = Car::query()
        ->when($request->search, fn($q) => $q->where('nama', 'like', "%{$request->search}%"))
        ->when($request->merk, fn($q) => $q->where('merk', $request->merk))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->get();

    return view('frontend.index', compact('cars'));
}
}

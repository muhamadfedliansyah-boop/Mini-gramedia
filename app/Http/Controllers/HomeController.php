<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;

class HomeController extends Controller
{
    public function index()
    {
        $subscriptionPackages = SubscriptionPackage::all();
        return view('home', compact('subscriptionPackages'));
    }
}

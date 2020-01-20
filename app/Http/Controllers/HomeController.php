<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bgSetting = Setting::where('name', 'bg')->first();
        $bg = '#ffffff';
        if($bgSetting) {
          $bg = $bgSetting->value;
        }
        return view('home', ['bg' => $bg]);
    }

    public function update(Request $request)
    {
        if($request->has('bg')) {
            Setting::updateOrCreate(
                ['name' => 'bg'], ['value' => $request->bg]
            );
        }
        return redirect('/admin');
    }
}

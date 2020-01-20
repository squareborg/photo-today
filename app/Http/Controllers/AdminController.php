<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *    
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $bg = Setting::where('name', 'bg')->first();
        if (!$bg){
          $bg = Setting::create(['name' => 'bg', 'value' => '#ffffff']);
        }
        return view('admin', ['bg' => $bg->value]);
    }

    public function update(Request $request)
    {
        if($request->hasFile('logo')) {
            $im = new ImageManager();
            $image = $im->make($request->logo->get());
            if(Storage::disk('local')->exists('logo.jpg')) {
              Storage::disk('local')->delete('logo.jpg');
            }
            $image->save(storage_path("app/logo.jpg"), 50);
            if(Storage::disk('s3')->exists('logo.jpg')) {
              Storage::disk('s3')->delete('logo.jpg');
            }
            Storage::disk('s3')->writeStream('logo.jpg', Storage::disk('local')->readStream('logo.jpg'));
        };
        if($request->has('bg')) {
            Setting::updateOrCreate(
                ['name' => 'bg'], ['value' => $request->bg]
            );
        }
        return redirect('/admin');
    }
}

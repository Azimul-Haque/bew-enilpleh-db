<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use App\District;
// use App\Upazilla;
use App\Esheba;
use App\Eshebaimage;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;
use File;
use Session;
use Artisan;
// use Redirect;
use OneSignal;
use Purifier;
use Cache;

class EshebaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        $eshebascount = Esheba::count();
        $eshebas = Esheba::orderBy('id', 'desc')->paginate(10);
                
        return view('dashboard.eshebas.index')
                            ->withEshebascount($eshebascount)
                            ->withEshebas($eshebas);
    }

    public function indexSearch($search)
    {
        $eshebascount = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")->count();

        $eshebas = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")
                                  ->orderBy('id', 'desc')
                                  ->paginate(10);
        
        return view('dashboard.eshebas.index')
                            ->withEshebascount($eshebascount)
                            ->withEshebas($eshebas);
    }

    public function storeEsheba(Request $request)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'url'                 => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $esheba = new Esheba;
        $esheba->name = $request->name;
        $esheba->url = $request->url;
        $esheba->save();

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/eshebas/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $eshebaimage              = new Eshebaimage;
            $eshebaimage->esheba_id   = $esheba->id;
            $eshebaimage->image       = $filename;
            $eshebaimage->save();
        }
        
        Cache::forget('eshebas');
        Session::flash('success', 'E-sheba added successfully!');
        return redirect()->route('dashboard.eshebas');
    }

    public function updateEsheba(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'url'              => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $esheba = Esheba::find($id);
        $esheba->name = $request->name;
        $esheba->url = $request->url;
        $esheba->save();

        // image upload
        if($request->hasFile('image')) {
            if($esheba->eshebaimage !=null) {
               $image_path = public_path('images/eshebas/'. $esheba->eshebaimage->image);
               // dd($image_path);
               if(File::exists($image_path)) {
                   File::delete($image_path);
               }
               $eshebaimage              = Eshebaimage::where('esheba_id', $esheba->id)->first();
            } else {
               $eshebaimage              = new Eshebaimage;
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/eshebas/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            
            $eshebaimage->image       = $filename;
            $eshebaimage->save();
        }

        
        Cache::forget('eshebas');
        Session::flash('success', 'E-sheba updated successfully!');
        return redirect()->route('dashboard.eshebas');
    }
}

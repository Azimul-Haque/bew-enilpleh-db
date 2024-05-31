<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\District;
use App\Upazilla;
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

class AmbulanceController extends Controller
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

        $districts = District::all();
                
        return view('dashboard.ambulances.index')
                            ->withEshebascount($eshebascount)
                            ->withEshebas($eshebas)
                            ->withDistricts($districts);
    }

    public function indexSearch($search)
    {
        $eshebascount = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->count();
        $eshebas = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->orderBy('id', 'desc')
                                  ->paginate(10);

        $districts = District::all();
        
        return view('dashboard.ambulances.index')
                            ->withEshebascount($eshebascount)
                            ->withEshebas($eshebas)
                            ->withDistricts($districts);
    }

    public function storeEsheba(Request $request)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'ur;'                 => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $ambulance = new Esheba;
        $ambulance->district_id = $request->district_id;
        $ambulance->upazilla_id = $request->upazilla_id;
        $ambulance->name = $request->name;
        $ambulance->mobile = $request->mobile;
        $ambulance->save();

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/ambulances/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $ambulanceimage              = new Eshebaimage;
            $ambulanceimage->ambulance_id   = $ambulance->id;
            $ambulanceimage->image       = $filename;
            $ambulanceimage->save();
        }
        
        Cache::forget('ambulances' . $request->district_id);
        Cache::forget('ambulances' . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Ambulance added successfully!');
        return redirect()->route('dashboard.ambulances');
    }

    public function updateEsheba(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'image'              => 'sometimes',
        ));

        $ambulance = Esheba::find($id);
        $ambulance->district_id = $request->district_id;
        $ambulance->upazilla_id = $request->upazilla_id;
        $ambulance->name = $request->name;
        $ambulance->mobile = $request->mobile;
        $ambulance->save();

        // image upload
        if($request->hasFile('image')) {
            $image_path = public_path('images/ambulances/'. $ambulance->ambulanceimage->image);
            // dd($image_path);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/ambulances/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $ambulanceimage              = Eshebaimage::where('ambulance_id', $ambulance->id)->first();
            $ambulanceimage->image       = $filename;
            $ambulanceimage->save();
        }

        
        Cache::forget('ambulances'. $request->district_id);
        Cache::forget('ambulances'. $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Ambulance updated successfully!');
        return redirect()->route('dashboard.ambulances');
    }
}

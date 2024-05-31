<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\District;
use App\Upazilla;
use App\Ambulance;
use App\Ambulanceimage;

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
        $ambulancescount = Ambulance::count();
        $ambulances = Ambulance::orderBy('id', 'desc')->paginate(10);

        $districts = District::all();
                
        return view('dashboard.ambulances.index')
                            ->withAmbulancescount($ambulancescount)
                            ->withAmbulances($ambulances)
                            ->withDistricts($districts);
    }

    public function indexSearch($search)
    {
        $ambulancescount = Ambulance::where('name', 'LIKE', "%$search%")
                                  ->orWhere('mobile', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->count();
        $ambulances = Ambulance::where('name', 'LIKE', "%$search%")
                                  ->orWhere('mobile', 'LIKE', "%$search%")
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
                            ->withAmbulancescount($ambulancescount)
                            ->withAmbulances($ambulances)
                            ->withDistricts($districts);
    }

    public function storeAmbulance(Request $request)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'image'              => 'sometimes',
        ));

        $ambulance = new Ambulance;
        $ambulance->district_id = $request->district_id;
        $ambulance->upazilla_id = $request->upazilla_id;
        $ambulance->name = $request->name;
        $ambulance->mobile = $request->mobile;
        $ambulance->save();

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/doctors/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $doctorimage              = new Doctorimage;
            $doctorimage->doctor_id = $doctor->id;
            $doctorimage->image       = $filename;
            $doctorimage->save();
        }
        

        
        // Cache::forget('blooddonors'. $request->category . $request->district_id);
        // Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor added successfully!');
        return redirect()->route('dashboard.ambulances');
    }

    public function updateAmbulance(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'         => 'required',
            'upazilla_id'         => 'required',
            'name'                => 'required|string|max:191',
            'category'            => 'required',
            'mobile'              => 'required|string|max:191',
        ));

        $blooddonor = Ambulance::find($id);;
        $blooddonor->district_id = $request->district_id;
        $blooddonor->upazilla_id = $request->upazilla_id;
        $blooddonor->name = $request->name;
        $blooddonor->category = $request->category;
        $blooddonor->mobile = $request->mobile;
        $blooddonor->save();

        
        Cache::forget('blooddonors'. $request->category . $request->district_id);
        Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor updated successfully!');
        return redirect()->route('dashboard.ambulances');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\District;
use App\Upazilla;
use App\Blooddonor;

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
        $ambulancescount = Blooddonor::count();
        $blooddonors = Blooddonor::orderBy('id', 'desc')->paginate(10);

        $districts = District::all();
                
        return view('dashboard.blooddonors.index')
                            ->withAmbulancescount($ambulancescount)
                            ->withBlooddonors($blooddonors)
                            ->withDistricts($districts);
    }

    public function indexSearch($search)
    {
        $ambulancescount = Blooddonor::where('name', 'LIKE', "%$search%")
                                  ->orWhere('mobile', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->count();
        $ambulances = Blooddonor::where('name', 'LIKE', "%$search%")
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
        
        return view('dashboard.blooddonors.index')
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
            'category'            => 'required',
            'mobile'              => 'required|string|max:191',
        ));

        $blooddonor = new Blooddonor;
        $blooddonor->district_id = $request->district_id;
        $blooddonor->upazilla_id = $request->upazilla_id;
        $blooddonor->name = $request->name;
        $blooddonor->category = $request->category;
        $blooddonor->mobile = $request->mobile;
        $blooddonor->save();

        
        Cache::forget('blooddonors'. $request->category . $request->district_id);
        Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor added successfully!');
        return redirect()->route('dashboard.blooddonors');
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

        $blooddonor = Blooddonor::find($id);;
        $blooddonor->district_id = $request->district_id;
        $blooddonor->upazilla_id = $request->upazilla_id;
        $blooddonor->name = $request->name;
        $blooddonor->category = $request->category;
        $blooddonor->mobile = $request->mobile;
        $blooddonor->save();

        
        Cache::forget('blooddonors'. $request->category . $request->district_id);
        Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor updated successfully!');
        return redirect()->route('dashboard.blooddonors');
    }
}

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

class BlooddonorsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        $blooddonorscount = Blooddonor::count();
        $blooddonors = Blooddonor::orderBy('id', 'desc')->paginate(10);

        $districts = District::all();
                
        return view('dashboard.blooddonors.index')
                            ->withBlooddonorscount($blooddonorscount)
                            ->withBlooddonors($blooddonors)
                            ->withDistricts($districts);
    }

    public function indexSearch($search)
    {
        $blooddonorscount = Blooddonor::where('name', 'LIKE', "%$search%")
                                  ->orWhere('mobile', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->count();
        $blooddonors = Blooddonor::where('name', 'LIKE', "%$search%")
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
                            ->withBlooddonorscount($blooddonorscount)
                            ->withBlooddonors($blooddonors)
                            ->withDistricts($districts);
    }

    public function storeBloodDonor(Request $request)
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

        
        // Cache::forget('doctors'. $request->district_id);
        // Cache::forget('doctors'. $request->district_id . $request->upazilla_id);
        Session::flash('success', 'Blood Donor added successfully!');
        return redirect()->route('dashboard.blooddonors');
    }

    public function storeBloodDonor(Request $request, $id)
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

        
        // Cache::forget('doctors'. $request->district_id);
        // Cache::forget('doctors'. $request->district_id . $request->upazilla_id);
        Session::flash('success', 'Blood Donor added successfully!');
        return redirect()->route('dashboard.blooddonors');
    }

}

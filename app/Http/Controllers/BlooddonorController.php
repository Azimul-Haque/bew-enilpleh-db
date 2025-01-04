<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\District;
use App\Upazilla;
use App\Blooddonor;
use App\Blooddonormember;

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

class BlooddonorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        if(Auth::user()->role == 'editor') {
            $blooddonorscount = Auth::user()->accessibleBlooddonors()->count();
            $blooddonors = Auth::user()->accessibleBlooddonors()->paginate(10);
        } else {
            $blooddonorscount = Blooddonor::count();
            $blooddonors = Blooddonor::orderBy('id', 'desc')->paginate(10);
        }        

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
            'description'              => 'sometimes|string|max:500',
        ));

        $blooddonor = new Blooddonor;
        $blooddonor->district_id = $request->district_id;
        $blooddonor->upazilla_id = $request->upazilla_id;
        $blooddonor->name = $request->name;
        $blooddonor->category = $request->category;
        $blooddonor->mobile = $request->mobile;
        $blooddonor->save();

        if(Auth::user()->role == 'editor') {
            Auth::user()->accessibleBlooddonors()->attach($blooddonor);
        }

        
        Cache::forget('blooddonors'. $request->category . $request->district_id);
        Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor added successfully!');
        return redirect()->route('dashboard.blooddonors');
    }

    public function updateBloodDonor(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'         => 'required',
            'upazilla_id'         => 'required',
            'name'                => 'required|string|max:191',
            'category'            => 'required',
            'mobile'              => 'required|string|max:191',
            'description'              => 'sometimes|string|max:500',
        ));

        $blooddonor = Blooddonor::find($id);
        Cache::forget('blooddonors'. $blooddonor->category . $blooddonor->district_id);
        Cache::forget('blooddonors'. $blooddonor->category . $blooddonor->district_id. $blooddonor->upazilla_id);
        $blooddonor->district_id = $request->district_id;
        $blooddonor->upazilla_id = $request->upazilla_id;
        $blooddonor->name = $request->name;
        $blooddonor->category = $request->category;
        $blooddonor->mobile = $request->mobile;
        $blooddonor->description = $request->description;
        $blooddonor->save();

        Cache::forget('blooddonors'. $request->category . $request->district_id);
        Cache::forget('blooddonors'. $request->category . $request->district_id. $request->upazilla_id);
        Session::flash('success', 'Blood Donor updated successfully!');
        return redirect()->route('dashboard.blooddonors');
    }

    public function getBloodDonorMembers($id)
    {
        $blooddonor = Blooddonor::find($id);
        $blooddonormemberscount = Blooddonormember::where('blooddonor_id', $id)->count();
        $blooddonormembers = Blooddonormember::where('blooddonor_id', $id)->orderBy('id', 'desc')->paginate(10);
                
        return view('dashboard.blooddonors.members')
                            ->withBlooddonor($blooddonor)
                            ->withBlooddonormemberscount($blooddonormemberscount)
                            ->withBlooddonormembers($blooddonormembers);
    }

    public function bloodDonorMemberSearch($id, $search)
    {
        $blooddonor = Blooddonor::find($id);

        // Initialize query
        $query = Blooddonormember::where('blooddonor_id', $id);

        // If the search parameter is provided, apply it to designation, area, mobile, and telephone fields
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%')
              ->orWhere('designation', 'LIKE', '%' . $search . '%')
              ->orWhere('blood_group', 'LIKE', '%' . $search . '%')
              ->orWhere('address', 'LIKE', '%' . $search . '%')
              ->orWhere('contact', 'LIKE', '%' . $search . '%')
              ->orWhereHas('blooddonor', function ($secquery) use ($search){
                  $secquery->where('name', 'like', '%'.$search.'%');
                  $secquery->orWhere('mobile', 'like', '%'.$search.'%');
              });
        });

        $blooddonormemberscount = $query->count();
        $blooddonormembers = $query->orderBy('id', 'desc')->paginate(10);

        return view('dashboard.blooddonors.members')
                            ->withBlooddonor($blooddonor)
                            ->withBlooddonormemberscount($blooddonormemberscount)
                            ->withBlooddonormembers($blooddonormembers);
    }



    public function storeBloodDonorMember(Request $request)
    {
        $this->validate($request,array(
            'blooddonor_id'   => 'required',
            'name'            => 'required|string|max:191',
            'designation'     => 'required|string|max:191',
            'blood_group'     => 'required|string|max:191',
            'contact'         => 'required|string|max:191',
            'address'         => 'required|string|max:191',
        ));

        $blooddonormember = new Blooddonormember;
        $blooddonormember->blooddonor_id = $request->blooddonor_id;
        $blooddonormember->name = $request->name;
        $blooddonormember->designation = $request->designation;
        $blooddonormember->blood_group = $request->blood_group;
        $blooddonormember->contact = $request->contact;
        $blooddonormember->address = $request->address;
        $blooddonormember->save();

        Cache::forget('blooddonormembers'. $request->blooddonor_id);
        Session::flash('success', 'Blood Donor Member added successfully!');
        return redirect()->route('dashboard.blooddonormembers', $request->blooddonor_id);
    }



    public function updateBloodDonorMember(Request $request, $id)
    {
        $this->validate($request,array(
            'blooddonor_id'   => 'required',
            'name'            => 'required|string|max:191',
            'designation'     => 'required|string|max:191',
            'blood_group'     => 'required|string|max:191',
            'contact'         => 'required|string|max:191',
            'address'         => 'required|string|max:191',
        ));

        $blooddonormember = Blooddonormember::findOrFail($id);
        $blooddonormember->blooddonor_id = $request->blooddonor_id;
        $blooddonormember->name = $request->name;
        $blooddonormember->designation = $request->designation;
        $blooddonormember->blood_group = $request->blood_group;
        $blooddonormember->contact = $request->contact;
        $blooddonormember->address = $request->address;
        $blooddonormember->save();

        Cache::forget('blooddonormembers'. $request->blooddonor_id);
        Session::flash('success', 'Blood Donor Member updated successfully!');
        return redirect()->back();
    }

    public function deleteBloodDonorMember($blooddonor_id, $member_id)
    {
        $blooddonormember = Blooddonormember::findOrFail($member_id);
        $blooddonormember->delete();

        Cache::forget('blooddonormembers'. $blooddonor_id);
        Session::flash('success', 'Blood Donor Member deleted successfully!');
        return redirect()->back();
    }

}

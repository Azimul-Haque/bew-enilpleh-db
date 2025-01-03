<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\District;
use App\Upazilla;
use App\Hospital;

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

class HospitalController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['admin'])->only('deleteHospital');
    }

    public function index()
    {
        if(Auth::user()->role == 'editor') {
            $hospitalscount = Auth::user()->accessibleHospitals()->count();
            $hospitals = Auth::user()->accessibleHospitals()->paginate(10);
        } else {
            $hospitalscount = Hospital::count();
            $hospitals = Hospital::orderBy('id', 'desc')->paginate(10);
        }
        
        $districts = District::all();
        
        return view('dashboard.hospitals.index')
                            ->withHospitalscount($hospitalscount)
                            ->withHospitals($hospitals)
                            ->withDistricts($districts);
    }

    public function indexSearch($search)
    {
        $hospitalscount = Hospital::where('name', 'LIKE', "%$search%")
                                  ->orWhere('telephone', 'LIKE', "%$search%")
                                  ->orWhere('mobile', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->orderBy('id', 'desc')
                                  ->count();
        $hospitals = Hospital::where('name', 'LIKE', "%$search%")
                              ->orWhere('telephone', 'LIKE', "%$search%")
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

        // $sites = Site::all();
        return view('dashboard.hospitals.index')
                    ->withHospitalscount($hospitalscount)
                    ->withHospitals($hospitals)
                    ->withDistricts($districts);
    }

    public function storeHospital(Request $request)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'hospital_type'       => 'required',
            'telephone'           => 'required',
            'mobile'              => 'sometimes',
            'location'            => 'required',
            'website'            => 'sometimes|max:191',
            'address'            => 'required',
            'branch_data'            => 'sometimes',
            'branch_ids'            => 'sometimes',
            'investigation_data'            => 'sometimes',
        ));

        $hospital = new Hospital;
        $hospital->district_id = $request->district_id;
        $hospital->upazilla_id = $request->upazilla_id;
        $hospital->name = $request->name;
        $hospital->hospital_type = $request->hospital_type;
        $hospital->telephone = $request->telephone;
        if($request->mobile) {
            $hospital->mobile = $request->mobile;
        }
        $hospital->mobile = $request->mobile;
        $hospital->location = $request->location;
        if($request->website) {
            $hospital->website = $request->website;
        }
        $hospital->address = $request->address;
        if($request->branch_data) {
            $hospital->branch_data = nl2br($request->branch_data);
        }
        if($request->investigation_data) {
            $hospital->investigation_data = nl2br($request->investigation_data);
        }

        $hospital->save();

        // image upload
        // image upload
        if($request->hasFile('image1')) {
            $image    = $request->file('image1');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/hospitals/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $hospitalimage1              = new Hospitalimage;
            $hospitalimage1->hospital_id = $hospital->id;
            $hospitalimage1->image       = $filename;
            $hospitalimage1->save();
        }
        if($request->hasFile('image2')) {
            $image    = $request->file('image2');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/hospitals/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $hospitalimage2              = new Hospitalimage;
            $hospitalimage2->hospital_id = $hospital->id;
            $hospitalimage2->image       = $filename;
            $hospitalimage2->save();
        }
        if($request->hasFile('image3')) {
            $image    = $request->file('image3');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/hospitals/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $hospitalimage3              = new Hospitalimage;
            $hospitalimage3->hospital_id = $hospital->id;
            $hospitalimage3->image       = $filename;
            $hospitalimage3->save();
        }
        if($request->hasFile('image4')) {
            $image    = $request->file('image4');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/hospitals/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $hospitalimage3              = new Hospitalimage;
            $hospitalimage3->hospital_id = $hospital->id;
            $hospitalimage3->image       = $filename;
            $hospitalimage3->save();
        }
        // image upload
        // image upload

        if($request->branch_ids) {
            foreach($request->branch_ids as $brid) {
                $this->attachBranches($brid, $hospital->id);
            }
        }

        if(Auth::user()->role == 'editor') {
            Auth::user()->accessibleHospitals()->attach($hospital);
        }
        

        Cache::forget('hospitals'. $request->hospital_type . $request->district_id);
        Cache::forget('hospitals'. $request->hospital_type . $request->district_id . $request->upazilla_id);
        Session::flash('success', 'Hospital added successfully!');
        return redirect()->route('dashboard.hospitals');
    }

    public function updateHospital(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'hospital_type'       => 'required',
            'telephone'           => 'required',
            'mobile'              => 'sometimes',
            'location'            => 'required',
            'website'            => 'sometimes|max:191',
            'address'            => 'required',
            'branch_data'            => 'sometimes',
            'branch_ids'            => 'sometimes',
            'investigation_data'            => 'sometimes',
        ));

        $hospital = Hospital::findOrFail($id);
        Cache::forget('hospitals'. $hospital->hospital_type . $hospital->district_id);
        Cache::forget('hospitals'. $hospital->hospital_type . $hospital->district_id . $hospital->upazilla_id);
        $hospital->district_id = $request->district_id;
        $hospital->upazilla_id = $request->upazilla_id;
        $hospital->name = $request->name;
        $hospital->hospital_type = $request->hospital_type;
        $hospital->telephone = $request->telephone;
        if($request->mobile) {
            $hospital->mobile = $request->mobile;
        } else {
            $hospital->mobile = '';
        }
        $hospital->location = $request->location;
        if($request->website) {
            $hospital->website = $request->website;
        } else {
            $hospital->website = '';
        }
        $hospital->address = $request->address;
        if($request->branch_data) {
            $hospital->branch_data = nl2br($request->branch_data);
        }
        if($request->investigation_data) {
            $hospital->investigation_data = nl2br($request->investigation_data);
        }

        foreach($hospital->allBranches() as $oldbranch) {
            $hospital->branches()->detach($oldbranch->id);
            $oldbranch->branches()->detach($hospital->id);
        } 
        if($request->branch_ids) {
            foreach($request->branch_ids as $brid) {
                $this->attachBranches($brid, $id);
            }
        }
        $hospital->save();

        Cache::forget('hospitals'. $request->hospital_type . $request->district_id);
        Cache::forget('hospitals'. $request->hospital_type . $request->district_id . $request->upazilla_id);
        Cache::forget('hospitalbranches'. $hospital->id);
        Session::flash('success', 'Hospital updated successfully!');
        return redirect()->back();
    }

    public function deleteHospital($id)
    {
        $hospital = Hospital::find($id);
        Cache::forget('hospitals'. $hospital->hospital_type . $hospital->district_id);
        Cache::forget('hospitals'. $hospital->hospital_type . $hospital->district_id . $hospital->upazilla_id);
        $hospital->delete();

        Session::flash('success', 'Hospital deleted successfully!');
        return redirect()->route('dashboard.hospitals');
    }

    public function attachBranches($hospitalAID, $hospitalBID)
    {
        $hospitalA = Hospital::findOrFail($hospitalAID);
        $hospitalB = Hospital::findOrFail($hospitalBID);

        // Attach each hospital as a branch of the other
        $hospitalA->branches()->syncWithoutDetaching([$hospitalB->id]);
        $hospitalB->branches()->syncWithoutDetaching([$hospitalA->id]);
    }
}

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
            'mobile'              => 'required|string|max:191',
            'serial'           => 'required',
            'helpline'              => 'required',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2000',
            'medicaldepartments'            => 'required',
            'medicalsymptoms'            => 'required',
            'hospitals'            => 'required',
        ));

        $doctor = new Doctor;
        $doctor->district_id = $request->district_id;
        $doctor->upazilla_id = $request->upazilla_id;
        $doctor->name = $request->name;
        $doctor->degree = $request->degree;
        $doctor->serial = $request->serial;
        $doctor->helpline = $request->helpline;
        $doctor->save();

        if(isset($request->medicaldepartments)){
            foreach($request->medicaldepartments as $medicaldepartment_id) {
                $doctormedicaldepartment = new Doctormedicaldepartment;
                $doctormedicaldepartment->doctor_id = $doctor->id;
                $doctormedicaldepartment->medicaldepartment_id = $medicaldepartment_id;
                $doctormedicaldepartment->save();

                Cache::forget('doctors'. $medicaldepartment_id . 'departmentwise' . $request->district_id);
                Cache::forget('doctors'. $medicaldepartment_id . 'departmentwise' . $request->district_id . $request->upazilla_id);

                Cache::forget('doctors'. $medicaldepartment_id . 'symptomwise' . $request->district_id);
                Cache::forget('doctors'. $medicaldepartment_id . 'symptomwise' . $request->district_id . $request->upazilla_id);
            }            
        }

        if(isset($request->medicalsymptoms)){
            foreach($request->medicalsymptoms as $medicalsymptom_id) {
                $doctormedicalsymptom = new Doctormedicalsymptom;
                $doctormedicalsymptom->doctor_id = $doctor->id;
                $doctormedicalsymptom->medicalsymptom_id = $medicalsymptom_id;
                $doctormedicalsymptom->save();

                Cache::forget('doctors'. $medicalsymptom_id . 'departmentwise' . $request->district_id);
                Cache::forget('doctors'. $medicalsymptom_id . 'departmentwise' . $request->district_id . $request->upazilla_id);

                Cache::forget('doctors'. $medicalsymptom_id . 'symptomwise' . $request->district_id);
                Cache::forget('doctors'. $medicalsymptom_id . 'symptomwise' . $request->district_id . $request->upazilla_id);
            }            
        }

        if(isset($request->hospitals)){
            foreach($request->hospitals as $hospital_id) {
                $doctorhospital = new Doctorhospital;
                $doctorhospital->doctor_id = $doctor->id;
                $doctorhospital->hospital_id = $hospital_id;
                $doctorhospital->save();

                Cache::forget('hospitaldoctors'. $hospital_id);
            }            
        }

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

        Cache::forget('doctors'. $request->district_id);
        Cache::forget('doctors'. $request->district_id . $request->upazilla_id);
        Session::flash('success', 'Doctors added successfully!');
        return redirect()->route('dashboard.doctors');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\District;
use App\Upazilla;
use App\Hospital;
use App\Doctor;
use App\Doctorimage;
use App\Doctorhospital;
use App\Medicaldepartment;
use App\Medicalsymptom;
use App\Doctormedicaldepartment;
use App\Doctormedicalsymptom;

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

class DoctorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        $doctorscount = Doctor::count();
        $doctors = Doctor::orderBy('id', 'desc')->paginate(10);

        $districts = District::all();
        $medicaldepartments = Medicaldepartment::all();
        $medicalsymptoms = Medicalsymptom::all();
        $hospitals = Hospital::all();

        
        return view('dashboard.doctors.index')
                            ->withDoctorscount($doctorscount)
                            ->withDoctors($doctors)
                            ->withDistricts($districts)
                            ->withMedicaldepartments($medicaldepartments)
                            ->withMedicalsymptoms($medicalsymptoms)
                            ->withHospitals($hospitals);
    }

    public function indexSearch($search)
    {
        $doctorscount = Doctor::where('name', 'LIKE', "%$search%")
                                  ->orWhere('degree', 'LIKE', "%$search%")
                                  ->orWhere('serial', 'LIKE', "%$search%")
                                  ->orWhere('helpline', 'LIKE', "%$search%")
                                  ->orWhereHas('district', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })->orWhereHas('upazilla', function ($query) use ($search){
                                      $query->where('name', 'like', '%'.$search.'%');
                                      $query->orWhere('name_bangla', 'like', '%'.$search.'%');
                                  })
                                  ->count();
        $doctors = Doctor::where('name', 'LIKE', "%$search%")
                              ->orWhere('degree', 'LIKE', "%$search%")
                              ->orWhere('serial', 'LIKE', "%$search%")
                              ->orWhere('helpline', 'LIKE', "%$search%")
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
        $medicaldepartments = Medicaldepartment::all();
        $medicalsymptoms = Medicalsymptom::all();
        $hospitals = Hospital::all();

        
        return view('dashboard.doctors.index')
                            ->withDoctorscount($doctorscount)
                            ->withDoctors($doctors)
                            ->withDistricts($districts)
                            ->withMedicaldepartments($medicaldepartments)
                            ->withMedicalsymptoms($medicalsymptoms)
                            ->withHospitals($hospitals);
    }

    public function storeDoctor(Request $request)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'degree'                => 'required|string|max:191',
            'serial'           => 'required',
            'helpline'              => 'required',
            'image'            => 'sometimes',
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
            $doctor->medicaldepartments()->sync($request->tags_ids, false);
        }

        

        Cache::forget('hospitals'. $request->hospital_type . $request->district_id);
        Cache::forget('hospitals'. $request->hospital_type . $request->district_id . $request->upazilla_id);
        Session::flash('success', 'Hospital added successfully!');
        return redirect()->route('dashboard.hospitals');
    }
}

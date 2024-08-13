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
            // Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            Image::make($image)->fit(300, 175)->save($location);
            // Image::make($image)->crop(300, 175)->save($location);
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

    public function storeDoctorDept(Request $request)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
        ));

        $medicaldepartment = new Medicaldepartment;
        $medicaldepartment->name = $request->name;
        $medicaldepartment->save();

        Cache::forget('medicaldepartments');
        Session::flash('success', 'medical Department added successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function storeDoctorDept(Request $request)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
        ));

        $medicaldepartment = new Medicaldepartment;
        $medicaldepartment->name = $request->name;
        $medicaldepartment->save();

        Cache::forget('medicaldepartments');
        Session::flash('success', 'medical Department added successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function updateDoctorDept(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
        ));

        $medicaldepartment = Medicaldepartment::findOrFail($id);
        $medicaldepartment->name = $request->name;
        $medicaldepartment->save();

        Cache::forget('medicaldepartments');
        Session::flash('success', 'medical Department updated successfully!');
        return redirect()->route('dashboard.doctors');
    }
}

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
use App\Doctorserial;

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
use PDF;

class DoctorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        if(Auth::user()->role == 'editor') {
            $doctorscount = Auth::user()->accessibleDoctors()->count();
            $doctors = Auth::user()->accessibleDoctors()->paginate(10);
        } else {
            $doctorscount = Doctor::count();
            $doctors = Doctor::orderBy('id', 'desc')->paginate(10);
        }
        

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
            'specialization'                => 'required|string|max:191',
            'serial'           => 'required',
            'address'           => 'required',
            'helpline'              => 'sometimes',
            'image'                 => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2000',
            'medicaldepartments'            => 'required',
            'medicalsymptoms'            => 'required',
            'hospitals'            => 'sometimes',
            'weekdays'            => 'sometimes',
            'timefrom'            => 'sometimes',
            'timeto'            => 'sometimes',
        ));

        $doctor = new Doctor;
        $doctor->district_id = $request->district_id;
        $doctor->upazilla_id = $request->upazilla_id;
        $doctor->name = $request->name;
        $doctor->degree = $request->degree;
        $doctor->serial = $request->serial;
        $doctor->address = $request->address;
        if($request->specialization) {
            $doctor->specialization = nl2br($request->specialization);
        }
        if($request->helpline) {
            $doctor->helpline = $request->helpline;
        }
        $doctor->weekdays = $request->weekdays;
        $doctor->timefrom = $request->timefrom;
        $doctor->timeto = $request->timeto;
        $doctor->save();

        if(Auth::user()->role == 'editor') {
            Auth::user()->accessibleDoctors()->attach($doctor);
        }

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

        Cache::forget('doctors*');
        Session::flash('success', 'Doctors added successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function updateDoctor(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|string|max:191',
            'degree'                => 'required|string|max:191',
            'specialization'                => 'required|string|max:191',
            'serial'           => 'required',
            'address'           => 'required',
            'helpline'              => 'sometimes',
            'image'                 => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2000',
            'medicaldepartments'            => 'required',
            'medicalsymptoms'            => 'required',
            'hospitals'            => 'sometimes',
            'weekdays'            => 'sometimes',
            'timefrom'            => 'sometimes',
            'timeto'            => 'sometimes',
        ));

        $doctor = Doctor::findOrFail($id);
        $doctor->district_id = $request->district_id;
        $doctor->upazilla_id = $request->upazilla_id;
        $doctor->name = $request->name;
        $doctor->degree = $request->degree;
        if($request->specialization) {
            $doctor->specialization = nl2br($request->specialization);
        }
        $doctor->serial = $request->serial;
        $doctor->address = $request->address;
        if($request->helpline) {
            $doctor->helpline = $request->helpline;
        } else {
            $doctor->helpline = '';
        }
        $doctor->weekdays = $request->weekdays;
        $doctor->timefrom = $request->timefrom;
        $doctor->timeto = $request->timeto;
        $doctor->save();

        if(isset($request->medicaldepartments)){

            foreach($doctor->doctormedicaldepartments as $medicaldepartment) {
                $medicaldepartment->delete();
            }

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

            foreach($doctor->doctormedicalsymptoms as $medicalsymptom) {
                $medicalsymptom->delete();
            }

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

            foreach($doctor->doctorhospitals as $hospital) {
                $hospital->delete();
            }

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
            if($doctor->doctorimage != null) {
                $image_path = public_path('images/doctors/'. $doctor->doctorimage->image);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
                $doctorimage              = Doctorimage::where('doctor_id', $doctor->id)->first();
            } else {
                $doctorimage              = new Doctorimage;
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/doctors/'. $filename);
            // Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            Image::make($image)->fit(300, 175)->save($location);
            // Image::make($image)->crop(300, 175)->save($location);
            $doctorimage->doctor_id = $doctor->id;
            $doctorimage->image       = $filename;
            $doctorimage->save();
        }

        Cache::forget('doctors*');
        Session::flash('success', 'Doctors updated successfully!');
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
        Session::flash('success', 'Medical Department added successfully!');
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
        Session::flash('success', 'Medical Department updated successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function storeDoctorSymp(Request $request)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
        ));

        $medicalsymptom = new Medicalsymptom;
        $medicalsymptom->name = $request->name;
        $medicalsymptom->save();

        Cache::forget('medicalsymptoms');
        Session::flash('success', 'Medical Symptom added successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function updateDoctorSymp(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
        ));

        $medicalsymptom = Medicalsymptom::findOrFail($id);
        $medicalsymptom->name = $request->name;
        $medicalsymptom->save();

        Cache::forget('medicalsymptoms');
        Session::flash('success', 'Medical Symptom updated successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function doctorSerialIndex($doctor_id, $todaydate)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        $doctorserials = Doctorserial::where('doctor_id', $doctor_id)
                                     ->where('serialdate', $todaydate)
                                     ->paginate(10);

        
        return view('dashboard.doctors.doctorserials')
                            ->withDoctor($doctor)
                            ->withDoctorserials($doctorserials)
                            ->withTodaydate($todaydate);
    }

    public function getDoctorSerialPDF($doctor_id, $serialdate) {
      $order = Order::where('payment_id', $payment_id)->first();
      $doctorserials = Doctorserial::where('doctor_id', $doctor_id)
                                   ->where('serialdate', $serialdate)
                                   ->get();
      // dd($doctorserials);
      $pdf = PDF::loadView('pdf.receipt', ['order' => $order]);
      $fileName = 'Receipt_'. $payment_id .'.pdf';
      return $pdf->stream($fileName);
    }
}

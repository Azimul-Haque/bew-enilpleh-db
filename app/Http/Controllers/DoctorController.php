<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


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
            if(!in_array('doctors', Auth::user()->accessibleTables())) {
                abort(403, 'Access Denied');
            }
            $doctorscount = Auth::user()->accessibleDoctors()->count();
            $doctors = Auth::user()->accessibleDoctors()->get();

            $accessedhospitals = Auth::user()->accessibleHospitals()->get();

            foreach($accessedhospitals as $hospital) {
                foreach($hospital->doctorhospitals as $doctor) {
                    $doctors->push($doctor->doctor);
                }
            }
            $doctors = collect($doctors )->unique('id');

            // Now paginate the collection:

            // Define how many items you want per page
            $perPage = 10;

            // Get current page from the request, defaulting to 1
            $page = request()->get('page', 1);
            // Calculate the starting offset
            $offset = ($page - 1) * $perPage;
            // Slice the collection to get the items to display in current page
            $currentPageItems = $doctors->slice($offset, $perPage)->values();

            // Create LengthAwarePaginator instance
            $doctors = new LengthAwarePaginator(
                $currentPageItems,
                $doctors->count(),  // total items
                $perPage,
                $page,
                [
                    'path'  => request()->url(),
                    'query' => request()->query(),
                ]
            );

            // dd($doctors);

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
        if(Auth::user()->role == 'editor') {
            abort(403, 'Access Denied');
        }
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
            'name'                => 'required|max:191',
            'degree'                => 'required|max:191',
            'specialization'                => 'sometimes|max:191',
            'serial'           => 'required',
            'address'           => 'required',
            'helpline'              => 'sometimes',
            'medicaldepartments'            => 'required',
            'medicalsymptoms'            => 'required',
            'hospitals'            => 'sometimes',
            'weekdays'            => 'sometimes',
            'offdays'            => 'sometimes',
            'onlineserial'            => 'required',
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
        if($request->offdays) {
            $doctor->offdays = json_encode($request->offdays);
        }
        $doctor->onlineserial = $request->onlineserial;
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
        // if($request->hasFile('image')) {
        //     $image    = $request->file('image');
        //     $filename = random_string(5) . time() .'.' . "webp";
        //     $location = public_path('images/doctors/'. $filename);
        //     // Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
        //     Image::make($image)->fit(300, 175)->save($location);
        //     // Image::make($image)->crop(300, 175)->save($location);
        //     $doctorimage              = new Doctorimage;
        //     $doctorimage->doctor_id = $doctor->id;
        //     $doctorimage->image       = $filename;
        //     $doctorimage->save();
        // }

        Cache::forget('doctors*');
        Session::flash('success', 'Doctors added successfully!');
        return redirect()->route('dashboard.doctors');
    }

    public function updateDoctor(Request $request, $id)
    {
        $this->validate($request,array(
            'district_id'            => 'required',
            'upazilla_id'            => 'required',
            'name'                => 'required|max:191',
            'degree'                => 'required|max:191',
            'specialization'                => 'sometimes|max:191',
            'serial'           => 'required',
            'address'           => 'required',
            'helpline'              => 'sometimes',
            'medicaldepartments'            => 'required',
            'medicalsymptoms'            => 'required',
            'hospitals'            => 'sometimes',
            'weekdays'            => 'sometimes',
            'offdays'            => 'sometimes',
            'onlineserial'            => 'required',
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
        if($request->offdays) {
            $doctor->offdays = json_encode($request->offdays);
        }
        $doctor->onlineserial = $request->onlineserial;
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

                Cache::forget('doctors'. $medicaldepartment_id . '1' . $request->district_id);
                Cache::forget('doctors'. $medicaldepartment_id . '1' . $request->district_id . $request->upazilla_id);

                Cache::forget('doctors'. $medicaldepartment_id . '2' . $request->district_id);
                Cache::forget('doctors'. $medicaldepartment_id . '2' . $request->district_id . $request->upazilla_id);
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

                Cache::forget('doctors'. $medicalsymptom_id . '1' . $request->district_id);
                Cache::forget('doctors'. $medicalsymptom_id . '1' . $request->district_id . $request->upazilla_id);

                Cache::forget('doctors'. $medicalsymptom_id . '2' . $request->district_id);
                Cache::forget('doctors'. $medicalsymptom_id . '2' . $request->district_id . $request->upazilla_id);
            }            
        }

        if(isset($request->hospitals)){

            foreach($doctor->doctorhospitals as $olddoctorhospital) {
                $olddoctorhospital->delete();
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
        // if($request->hasFile('image')) {
        //     if($doctor->doctorimage != null) {
        //         $image_path = public_path('images/doctors/'. $doctor->doctorimage->image);
        //         if(File::exists($image_path)) {
        //             File::delete($image_path);
        //         }
        //         $doctorimage              = Doctorimage::where('doctor_id', $doctor->id)->first();
        //     } else {
        //         $doctorimage              = new Doctorimage;
        //     }
        //     $image    = $request->file('image');
        //     $filename = random_string(5) . time() .'.' . "webp";
        //     $location = public_path('images/doctors/'. $filename);
        //     // Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
        //     Image::make($image)->fit(300, 175)->save($location);
        //     // Image::make($image)->crop(300, 175)->save($location);
        //     $doctorimage->doctor_id = $doctor->id;
        //     $doctorimage->image       = $filename;
        //     $doctorimage->save();
        // }

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
        if(Auth::user()->role == 'editor') {
            if(!in_array('doctors', Auth::user()->accessibleTables())) {
                abort(403, 'Access Denied');
            }
            if(!in_array($doctor_id, Auth::user()->accessibleDoctors()->pluck('accessible_id')->toArray())) {
                // if not in accessed doctors list, check if hospital is accessed atleast
                $accessedhospitals = Auth::user()->accessibleHospitals()->get();
                $hospitaldoctorslist = collect();
                foreach($accessedhospitals as $hospital) {
                    foreach($hospital->doctorhospitals as $doctor) {
                        $hospitaldoctorslist->push($doctor->doctor);
                    }
                }
                if(!in_array($doctor_id, $hospitaldoctorslist->pluck('id')->toArray())) {
                    abort(403, 'Access Denied');
                }
            }
        }
        $doctor = Doctor::findOrFail($doctor_id);
        $doctorserials = Doctorserial::where('doctor_id', $doctor_id)
                                     ->where('serialdate', $todaydate)
                                     ->paginate(10);

        return view('dashboard.doctors.doctorserials')
                            ->withDoctor($doctor)
                            ->withDoctorserials($doctorserials)
                            ->withTodaydate($todaydate);
    }

    public function getDoctorSerialPDF($doctor_id, $serialdate)
    {
        if(Auth::user()->role == 'editor') {
            if(!in_array('doctors', Auth::user()->accessibleTables())) {
                abort(403, 'Access Denied');
            }
            if(!in_array($doctor_id, Auth::user()->accessibleDoctors()->pluck('accessible_id')->toArray())) {
                // if not in accessed doctors list, check if hospital is accessed atleast
                $accessedhospitals = Auth::user()->accessibleHospitals()->get();
                $hospitaldoctorslist = collect();
                foreach($accessedhospitals as $hospital) {
                    foreach($hospital->doctorhospitals as $doctor) {
                        $hospitaldoctorslist->push($doctor->doctor);
                    }
                }
                if(!in_array($doctor_id, $hospitaldoctorslist->pluck('id')->toArray())) {
                    abort(403, 'Access Denied');
                }
            }
        }
        $doctor = Doctor::findOrFail($doctor_id);
        $doctorserials = Doctorserial::where('doctor_id', $doctor_id)
                                   ->where('serialdate', $serialdate)
                                   ->get();
        // dd($doctorserials);
        $pdf = PDF::loadView('dashboard.doctors.pdf.serials', ['doctor' => $doctor, 'doctorserials' => $doctorserials, 'serialdate' => $serialdate]);
        $fileName = 'Doctor-Serial-'. $doctor_id . '-' . $serialdate . '.pdf';
        return $pdf->stream($fileName);
    }

    public function doctorSerialCancelSingle(Request $request)
    {
        $this->validate($request,array(
            'id'               => 'required',
            'doctor_id'        => 'required',
        ));

        $doctorserial = Doctorserial::findOrFail($request->id);

        // send sms
        // send sms
        $mobile_number = 0;
        $serialdoctor = Doctor::findOrFail($request->doctor_id);
        if(strlen($doctorserial->mobile) == 11) {
            $mobile_number = $doctorserial->mobile;
        } elseif(strlen($doctorserial->mobile) > 11) {
            if (strpos($doctorserial->mobile, '+') !== false) {
                $mobile_number = substr($doctorserial->mobile, -11);
            }
        }

        $text = "Appointment Cancelled!\n\n" .
                "Dear " . $doctorserial->name . ", we are sorry to inform you that, your appointment with " . $serialdoctor->name . " on " . date('d-m-Y', strtotime($doctorserial->serialdate)) . " has been cancelled unfortunately.\n\n" .
                "Infoline - BD Smart Seba";
        
        // NEW PANEL
        $url = config('sms.url');
        $api_key = config('sms.api_key');
        $senderid = config('sms.senderid');
        $number = $mobile_number;
        $message = $text;

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $jsonresponse = json_decode($response);

        if($jsonresponse->response_code == 202) {
            // Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        } elseif($jsonresponse->response_code == 1007) {
            // Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        } else {
            // Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        }
        // send sms
        // send sms

        Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        return redirect()->back();
    }

    public function doctorSerialCancelALL(Request $request, $doctor_id, $selecteddate)
    {
        // $this->validate($request,array(
        //     'id'               => 'required',
        //     'doctor_id'        => 'required',
        // ));

        $doctorserials = Doctorserial::where('doctor_id', $doctor_id)
                                     ->where('serialdate', $selecteddate)
                                     ->get();
        dd($doctorserials);
        // send sms
        // send sms

        $url = config('sms.url');
        $api_key = config('sms.api_key');
        $senderid = config('sms.senderid');

        $messagesArray = [];

        foreach ($doctorserials as $doctorserial) {
            $text = "Appointment Cancelled!\n\n" .
                "Dear " . $doctorserial->name . ", we are sorry to inform you that, your appointment with " . $doctorserial->doctor->name . " on " . date('d-m-Y', strtotime($doctorserial->serialdate)) . " has been cancelled unfortunately.\n\n" .
                "Infoline - BD Smart Seba";

            $messagesArray[] = [
                "to"      => $doctorserial->mobile,    // e.g., the mobile number like "88016xxxxxxxx"
                "message" => $sms->message,  // e.g., the SMS content
            ];
        }

        $messages = json_encode( [
            [
                "to" => "88016xxxxxxxx",
                "message" => "test content"
            ],
            [
                "to" => "88019xxxxxxxx",
                "message" => "test 2nd content"
            ]
        ]);
        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "messages" => $messages
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        $mobile_number = 0;
        $serialdoctor = Doctor::findOrFail($request->doctor_id);
        if(strlen($doctorserial->mobile) == 11) {
            $mobile_number = $doctorserial->mobile;
        } elseif(strlen($doctorserial->mobile) > 11) {
            if (strpos($doctorserial->mobile, '+') !== false) {
                $mobile_number = substr($doctorserial->mobile, -11);
            }
        }

        ////////////////

        $text = "Appointment Cancelled!\n\n" .
                "Dear " . $doctorserial->name . ", we are sorry to inform you that, your appointment with " . $serialdoctor->name . " on " . date('d-m-Y', strtotime($doctorserial->serialdate)) . " has been cancelled unfortunately.\n\n" .
                "Infoline - BD Smart Seba";
        
        // NEW PANEL
        $url = config('sms.url');
        $api_key = config('sms.api_key');
        $senderid = config('sms.senderid');
        $number = $mobile_number;
        $message = $text;

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $jsonresponse = json_decode($response);

        if($jsonresponse->response_code == 202) {
            // Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        } elseif($jsonresponse->response_code == 1007) {
            // Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        } else {
            // Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        }
        // send sms
        // send sms

        Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        return redirect()->back();
    }
}

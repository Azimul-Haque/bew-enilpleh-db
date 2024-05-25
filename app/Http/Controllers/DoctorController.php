<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
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
        // $this->validate($request,array(
        //     'district_id'            => 'required',
        //     'upazilla_id'            => 'required',
        //     'name'                => 'required|string|max:191',
        //     'hospital_type'       => 'required',
        //     'telephone'           => 'required',
        //     'mobile'              => 'required',
        //     'location'            => 'required',
        // ));

        // $hospital = new Hospital;
        // $hospital->district_id = $request->district_id;
        // $hospital->upazilla_id = $request->upazilla_id;
        // $hospital->name = $request->name;
        // $hospital->hospital_type = $request->hospital_type;
        // $hospital->telephone = $request->telephone;
        // $hospital->mobile = $request->mobile;
        // $hospital->location = $request->location;
        // $hospital->save();

        // Cache::forget('hospitals'. $request->hospital_type . $request->district_id);
        // Cache::forget('hospitals'. $request->hospital_type . $request->district_id . $request->upazilla_id);
        // Session::flash('success', 'Hospital added successfully!');
        // return redirect()->route('dashboard.hospitals');
    }
}

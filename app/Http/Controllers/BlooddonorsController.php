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

}

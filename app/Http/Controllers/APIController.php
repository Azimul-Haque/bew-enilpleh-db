<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\userRepository;

use App\District;
use App\Upazilla;
use App\Hospital;
use App\Doctor;
use App\Medicaldepartment;
use App\Medicalsymptom;
use App\Doctormedicaldepartment;
use App\Doctormedicalsymptom;
use App\Blooddonor;
use App\Blooddonormember;
use App\Ambulance;
use App\Ambulanceimage;
use App\Esheba;
use App\Admin;
use App\Police;
use App\Fireservice;
use App\Lawyer;
use App\Rentacar;
use App\Rentacarimage;
use App\Coaching;
use App\Rab;
use App\Rabbattalion;
use App\Rabbattaliondetail;
use App\Bus;
use App\Journalist;
use App\Newspaper;
use App\Newspaperimage;
use App\Message;
use App\Doctorserial;

use Hash;
use Carbon\Carbon;
use DB;
use OneSignal;
use Cache;

class APIController extends Controller
{
    public function test()
    {
        
    }

    public function getDistricts($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN')) {
            try {
              $districts = Cache::remember('districts', 365 * 24 * 60 * 60, function ()  {
                   $districts = District::get();
                   return $districts;
              });

              return response()->json($districts, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
            }
            catch (\Exception $e) {
              return $e->getMessage();
            }
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getUpazillas($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN')) {
            try {
              $upazillas = Cache::remember('upazillas'.$district_id, 365 * 24 * 60 * 60, function () use ($district_id) {
                   $upazillas = Upazilla::where('district_id', $district_id)->get();
                   return $upazillas;
              });
              return $upazillas;
            }
            catch (\Exception $e) {
              return $e->getMessage();
            }
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getHospitalsDistrict($softtoken, $hospital_type, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $hospitals = Cache::remember('hospitals'.$hospital_type . $district_id, 30 * 24 * 60 * 60, function () use ($hospital_type, $district_id) {
                 $hospitals = Hospital::where('hospital_type', $hospital_type)
                             // ->where('district_id', $district_id) // COMMENTED
                             ->orderBy('id', 'desc')
                             ->get();
                             // dd($hospitals);
                 foreach($hospitals as $hospital) {
                     $hospital->districtname = $hospital->district->name_bangla;
                     $hospital->upazillaname = $hospital->upazilla->name_bangla;
                     $imagestemp = collect();
                     foreach($hospital->hospitalimages as $hospitalimage) {
                        $imagestemp->push($hospitalimage->image);
                        $hospitalimage->makeHidden('id', 'hospital_id', 'created_at', 'updated_at');
                        // $hospital->push($hospitalimagetemp);
                     }
                     $hospital->images = $imagestemp;
                     $hospital->makeHidden('hospitalimages', 'district', 'upazilla', 'created_at', 'updated_at');
                     // dd($hospital);
                 }
                 return $hospitals;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'hospitals' => $hospitals,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getHospitalBranches($softtoken, $hospital_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $mainhospital = Hospital::findOrFail($hospital_id);
            $hospitals = Cache::remember('hospitalbranches'.$hospital_id, 30 * 24 * 60 * 60, function () use ($mainhospital) {
                 $hospitals = $mainhospital->allBranches();
                             // dd($hospitals);
                 foreach($hospitals as $hospital) {
                     $hospital->districtname = $hospital->district->name_bangla;
                     $hospital->upazillaname = $hospital->upazilla->name_bangla;
                     $imagestemp = collect();
                     foreach($hospital->hospitalimages as $hospitalimage) {
                        $imagestemp->push($hospitalimage->image);
                        $hospitalimage->makeHidden('id', 'hospital_id', 'created_at', 'updated_at');
                        // $hospital->push($hospitalimagetemp);
                     }
                     $hospital->images = $imagestemp;
                     $hospital->makeHidden('hospitalimages', 'district', 'upazilla', 'created_at', 'updated_at');
                 }
                 return $hospitals;
            });
            
            // dd($hospitals);
            return response()->json([
                'success' => true,
                'hospitals' => $hospitals,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getHospitalsUpazilla($softtoken, $hospital_type, $district_id, $upazilla_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $hospitals = Cache::remember('hospitals'.$hospital_type . $district_id . $upazilla_id, 30 * 24 * 60 * 60, function () use ($hospital_type, $district_id, $upazilla_id) {
                 $hospitals = Hospital::where('hospital_type', $hospital_type)
                             ->where('district_id', $district_id)
                             ->where('upazilla_id', $upazilla_id)
                             ->orderBy('id', 'desc')
                             ->get();
                             // dd($hospitals);
                 foreach($hospitals as $hospital) {
                     $hospital->districtname = $hospital->district->name_bangla;
                     $hospital->upazillaname = $hospital->upazilla->name_bangla;
                     $hospital->makeHidden('district', 'upazilla', 'created_at', 'updated_at');
                 }
                 return $hospitals;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'hospitals' => $hospitals,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getMedicalDepartments($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $medicaldepartments = Cache::remember('medicaldepartments', 30 * 24 * 60 * 60, function () {
                 $medicaldepartments = Medicaldepartment::orderBy('id', 'asc')->get();
                             
                 return $medicaldepartments;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'medicaldepartments' => $medicaldepartments,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getMedicalSymptoms($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $medicalsymptoms = Cache::remember('medicalsymptoms', 30 * 24 * 60 * 60, function () {
                 $medicalsymptoms = Medicalsymptom::orderBy('id', 'asc')->get();
                             
                 return $medicalsymptoms;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'medicalsymptoms' => $medicalsymptoms,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getDoctorsDistrict($softtoken, $medicalitemid, $datatype, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            if($datatype == 'departmentwise') {
                $doctors = Cache::remember('doctors'. $medicalitemid . $datatype . $district_id, 30 * 24 * 60 * 60, function () use ($medicalitemid, $datatype, $district_id) {
                    $doctormedicaldepartments = Doctormedicaldepartment::where('medicaldepartment_id', $medicalitemid)
                                                    ->whereHas('doctor', function($q) use ($district_id){
                                                        // $q->where('district_id', $district_id); // COMMENTED
                                                    })->get();
                    $doctorstoreturn = collect();
                    foreach($doctormedicaldepartments as $doctormedicaldepartment) {
                        $doctormedicaldepartment->id = $doctormedicaldepartment->doctor->id;
                        $doctormedicaldepartment->name = $doctormedicaldepartment->doctor->name;
                        $doctormedicaldepartment->degree = $doctormedicaldepartment->doctor->degree;
                        $doctormedicaldepartment->specialization = $doctormedicaldepartment->doctor->specialization;
                        $doctormedicaldepartment->serial = $doctormedicaldepartment->doctor->serial;
                        $doctormedicaldepartment->address = $doctormedicaldepartment->doctor->address;
                        $doctormedicaldepartment->helpline = $doctormedicaldepartment->doctor->helpline;
                        $doctormedicaldepartment->weekdays = $doctormedicaldepartment->doctor->weekdays;
                        $doctormedicaldepartment->onlineserial = $doctormedicaldepartment->doctor->onlineserial;
                        // $doctormedicaldepartment->timefrom = $doctormedicaldepartment->doctor->timefrom;
                        // $doctormedicaldepartment->timeto = $doctormedicaldepartment->doctor->timeto;
                        // $doctormedicaldepartment->image = $doctormedicaldepartment->doctor->doctorimage ? $doctormedicaldepartment->doctor->doctorimage->image : '';
                        $doctormedicaldepartment->makeHidden('doctor', 'medicaldepartment_id', 'doctor_id', 'created_at', 'updated_at');
                        $doctorstoreturn->push($doctormedicaldepartment);
                        // dd($doctorstoreturn);
                        
                    }
                    return $doctorstoreturn;
                });
            } else { // symptomwise
                $doctors = Cache::remember('doctors'. $medicalitemid . $datatype . $district_id, 30 * 24 * 60 * 60, function () use ($medicalitemid, $datatype, $district_id) {
                    $doctormedicalsymptoms = Doctormedicalsymptom::where('medicalsymptom_id', $medicalitemid)
                                                    ->whereHas('doctor', function($q) use ($district_id){
                                                        // $q->where('district_id', $district_id); // COMMENTED
                                                    })->get();
                    $doctorstoreturn = collect();
                    foreach($doctormedicalsymptoms as $doctormedicalsymptom) {
                        $doctormedicalsymptom->id = $doctormedicalsymptom->doctor->id;
                        $doctormedicalsymptom->name = $doctormedicalsymptom->doctor->name;
                        $doctormedicalsymptom->degree = $doctormedicalsymptom->doctor->degree;
                        $doctormedicalsymptom->specialization = $doctormedicalsymptom->doctor->specialization;
                        $doctormedicalsymptom->serial = $doctormedicalsymptom->doctor->serial;
                        $doctormedicalsymptom->address = $doctormedicalsymptom->doctor->address;
                        $doctormedicalsymptom->helpline = $doctormedicalsymptom->doctor->helpline;
                        $doctormedicalsymptom->weekdays = $doctormedicalsymptom->doctor->weekdays;
                        $doctormedicalsymptom->onlineserial = $doctormedicalsymptom->doctor->onlineserial;
                        // $doctormedicalsymptom->timefrom = $doctormedicalsymptom->doctor->timefrom;
                        // $doctormedicalsymptom->timeto = $doctormedicalsymptom->doctor->timeto;
                        // $doctormedicalsymptom->image = $doctormedicalsymptom->doctor->doctorimage ? $doctormedicalsymptom->doctor->doctorimage->image : '';
                        $doctormedicalsymptom->makeHidden('doctor', 'medicaldepartment_id', 'doctor_id', 'created_at', 'updated_at');
                        $doctorstoreturn->push($doctormedicalsymptom);
                        // dd($doctorstoreturn);
                    }
                    return $doctorstoreturn;
                });
            }
            
            // dd($doctors);
            return response()->json([
                'success' => true,
                'doctors' => $doctors,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getDoctorsUpazilla($softtoken, $medicalitemid, $datatype, $district_id, $upazilla_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            if($datatype == 'departmentwise') {
                $doctors = Cache::remember('doctors'. $medicalitemid . $datatype . $district_id . $upazilla_id, 30 * 24 * 60 * 60, function () use ($medicalitemid, $datatype, $district_id, $upazilla_id) {
                    $doctormedicaldepartments = Doctormedicaldepartment::where('medicaldepartment_id', $medicalitemid)
                                                    ->whereHas('doctor', function($q) use ($district_id, $upazilla_id){
                                                        $q->where('district_id', $district_id);
                                                        $q->where('upazilla_id', $upazilla_id);
                                                    })->get();
                    $doctorstoreturn = collect();
                    foreach($doctormedicaldepartments as $doctormedicaldepartment) {
                        $doctormedicaldepartment->id = $doctormedicaldepartment->doctor->id;
                        $doctormedicaldepartment->name = $doctormedicaldepartment->doctor->name;
                        $doctormedicaldepartment->degree = $doctormedicaldepartment->doctor->degree;
                        $doctormedicaldepartment->specialization = $doctormedicaldepartment->doctor->specialization;
                        $doctormedicaldepartment->serial = $doctormedicaldepartment->doctor->serial;
                        $doctormedicaldepartment->helpline = $doctormedicaldepartment->doctor->helpline;
                        $doctormedicaldepartment->image = $doctormedicaldepartment->doctor->doctorimage ? $doctormedicaldepartment->doctor->doctorimage->image : '';
                        $doctormedicaldepartment->makeHidden('doctor', 'medicaldepartment_id', 'doctor_id', 'created_at', 'updated_at');
                        $doctorstoreturn->push($doctormedicaldepartment);
                        // dd($doctorstoreturn);
                        
                    }
                    return $doctorstoreturn;
                });
            } else { // symptomwise
                $doctors = Cache::remember('doctors'. $medicalitemid . $datatype . $district_id . $upazilla_id, 30 * 24 * 60 * 60, function () use ($medicalitemid, $datatype, $district_id, $upazilla_id) {
                    $doctormedicalsymptoms = Doctormedicalsymptom::where('medicalsymptom_id', $medicalitemid)
                                                    ->whereHas('doctor', function($q) use ($district_id, $upazilla_id){
                                                        $q->where('district_id', $district_id);
                                                        $q->where('upazilla_id', $upazilla_id);
                                                    })->get();
                    $doctorstoreturn = collect();
                    foreach($doctormedicalsymptoms as $doctormedicalsymptom) {
                        $doctormedicalsymptom->id = $doctormedicalsymptom->doctor->id;
                        $doctormedicalsymptom->name = $doctormedicalsymptom->doctor->name;
                        $doctormedicalsymptom->degree = $doctormedicalsymptom->doctor->degree;
                        $doctormedicalsymptom->specialization = $doctormedicalsymptom->doctor->specialization;
                        $doctormedicalsymptom->serial = $doctormedicalsymptom->doctor->serial;
                        $doctormedicalsymptom->helpline = $doctormedicalsymptom->doctor->helpline;
                        $doctormedicalsymptom->image = $doctormedicalsymptom->doctor->doctorimage ? $doctormedicalsymptom->doctor->doctorimage->image : '';
                        $doctormedicalsymptom->makeHidden('doctor', 'medicaldepartment_id', 'doctor_id', 'created_at', 'updated_at');
                        $doctorstoreturn->push($doctormedicalsymptom);
                        // dd($doctorstoreturn);
                    }
                    return $doctorstoreturn;
                });
            }
            
            // dd($doctors);
            return response()->json([
                'success' => true,
                'doctors' => $doctors,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getHospitalDoctors($softtoken, $hospital_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $doctors = Cache::remember('hospitaldoctors'.$hospital_id, 30 * 24 * 60 * 60, function () use ($hospital_id) {
                $doctors = Doctor::whereHas('doctorhospitals', function($q) use ($hospital_id){
                                        $q->where('hospital_id', $hospital_id);
                                    })->get();
                // dd($doctors);
                foreach($doctors as $doctor) {
                    $medicaldepartments = []; // shudhu department jothesto bojhar jonno
                    foreach($doctor->doctormedicaldepartments as $doctormedicaldepartment) {
                        $medicaldepartments[] = $doctormedicaldepartment->medicaldepartment->name;
                    }
                    $doctor->medicaldepartments = $medicaldepartments;
                    $doctor->image = $doctor->doctorimage ? $doctor->doctorimage->image : '';
                    $doctor->makeHidden('doctormedicaldepartments', 'doctorimage', 'district_id', 'upazilla_id', 'created_at', 'updated_at');
                    // dd($doctorstoreturn);
                }
                return $doctors;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'doctors' => $doctors,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getBloodDonorsDistrict($softtoken, $category, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $blooddonors = Cache::remember('blooddonors' . $category . $district_id, 30 * 24 * 60 * 60, function () use ($category, $district_id) {
                 $blooddonors = Blooddonor::where('category', $category)
                                 // ->where('district_id', $district_id) // COMMENTED
                                 ->orderBy('id', 'desc')
                                 ->get();
                                 // dd($blooddonors);
                 foreach($blooddonors as $blooddonor) {
                     $blooddonor->districtname = $blooddonor->district->name_bangla;
                     $blooddonor->upazillaname = $blooddonor->upazilla->name_bangla;
                     $blooddonor->makeHidden('district', 'upazilla', 'created_at', 'updated_at');
                 }
                 return $blooddonors;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'blooddonors' => $blooddonors,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getBloodDonorsUpazilla($softtoken, $category, $district_id, $upazilla_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $blooddonors = Cache::remember('blooddonors' . $category . $district_id . $upazilla_id, 30 * 24 * 60 * 60, function () use ($category, $district_id, $upazilla_id) {
                 $blooddonors = Blooddonor::where('category', $category)
                                 ->where('district_id', $district_id)
                                 ->where('upazilla_id', $upazilla_id)
                                 ->orderBy('id', 'desc')
                                 ->get();
                                 // dd($blooddonors);
                 foreach($blooddonors as $blooddonor) {
                     $blooddonor->districtname = $blooddonor->district->name_bangla;
                     $blooddonor->upazillaname = $blooddonor->upazilla->name_bangla;
                     $blooddonor->makeHidden('district', 'upazilla', 'created_at', 'updated_at');
                 }
                 return $blooddonors;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'blooddonors' => $blooddonors,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getBloodDonorMembers($softtoken, $blooddonor_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $blooddonormembers = Cache::remember('blooddonormembers' . $blooddonor_id, 30 * 24 * 60 * 60, function () use ($blooddonor_id) {
                 $blooddonormembers = Blooddonormember::where('blooddonor_id', $blooddonor_id)
                                                      ->orderBy('id', 'desc')
                                                      ->get();
                                 // dd($blooddonormembers);
                 foreach($blooddonormembers as $blooddonormember) {
                     $blooddonormember->makeHidden('upazilla', 'created_at', 'updated_at');
                 }
                 return $blooddonormembers;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'blooddonormembers' => $blooddonormembers,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getAmbulancesDistrict($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $ambulances = Cache::remember('ambulances'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $ambulances = Ambulance::orderBy('id', 'desc')
                                 // ->where('district_id', $district_id) // COMMENTED
                                 ->get();
                                 // dd($ambulances);
                 foreach($ambulances as $ambulance) {
                     // $ambulance->districtname = $ambulance->district->name_bangla;
                     // $ambulance->upazillaname = $ambulance->upazilla->name_bangla;
                     $ambulance->image = $ambulance->ambulanceimage ? $ambulance->ambulanceimage->image : '';
                     $ambulance->makeHidden('district', 'upazilla', 'ambulanceimage', 'created_at', 'updated_at');
                 }
                 return $ambulances;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'ambulances' => $ambulances,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }


    public function getAmbulancesUpazilla($softtoken, $district_id, $upazilla_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $ambulances = Cache::remember('ambulances' . $district_id . $upazilla_id, 30 * 24 * 60 * 60, function () use ($district_id, $upazilla_id) {
                 $ambulances = Ambulance::where('district_id', $district_id)
                                 ->where('upazilla_id', $upazilla_id)
                                 ->orderBy('id', 'desc')
                                 ->get();
                                 // dd($ambulances);
                 foreach($ambulances as $ambulance) {
                     // $ambulance->districtname = $ambulance->district->name_bangla;
                     // $ambulance->upazillaname = $ambulance->upazilla->name_bangla;
                     $ambulance->image = $ambulance->ambulanceimage ? $ambulance->ambulanceimage->image : '';
                     $ambulance->makeHidden('district', 'upazilla', 'ambulanceimage', 'created_at', 'updated_at');
                 }
                 return $ambulances;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'ambulances' => $ambulances,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getEshebas($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN')) {
            $eshebas = Cache::remember('eshebas', 365 * 24 * 60 * 60, function ()  {
               $eshebas = Esheba::get();
               foreach($eshebas as $esheba) {
                   $esheba->image = $esheba->eshebaimage ? $esheba->eshebaimage->image : '';
                   $esheba->makeHidden('eshebaimage', 'created_at', 'updated_at');
               }

               return $eshebas;
            });
            return response()->json([
            'success' => true,
            'eshebas' => $eshebas,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }


    public function getAdminOfficers($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $admins = Cache::remember('admins'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $admins = Admin::where('district_id', $district_id)
                                 ->orderBy('id', 'asc')
                                 ->get();
                 foreach($admins as $admin) {
                       $admin->makeHidden('id', 'district_id', 'created_at', 'updated_at');
                   }
                 return $admins;
            });
            
            return response()->json([
                'success' => true,
                'admins' => $admins,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getPoliceOfficers($softtoken, $station_type, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $police = Cache::remember('police'  . $station_type . $district_id, 30 * 24 * 60 * 60, function () use ($station_type, $district_id) {
                 $police = Police::where('district_id', $district_id)
                                 ->where('station_type', $station_type)
                                 ->orderBy('id', 'asc')
                                 ->get();
                 foreach($police as $policesingle) {
                       $policesingle->makeHidden('id', 'district_id', 'created_at', 'updated_at');
                   }
                 return $police;
            });
            
            return response()->json([
                'success' => true,
                'police' => $police,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getFireservices($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $fireservices = Cache::remember('fireservices'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $fireservices = Fireservice::where('district_id', $district_id)
                                 ->orderBy('id', 'asc')
                                 ->get();
                 foreach($fireservices as $fireservice) {
                       $fireservice->makeHidden('id', 'district_id', 'created_at', 'updated_at');
                   }
                 return $fireservices;
            });
            
            return response()->json([
                'success' => true,
                'fireservices' => $fireservices,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getLawyers($softtoken, $court_type, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $lawyers = Cache::remember('lawyers'  . $district_id . $court_type, 30 * 24 * 60 * 60, function () use ($district_id, $court_type) {
                $lawyers = Lawyer::where(function ($query) use ($district_id) {
                                    // return $query->where('district_id', $district_id); // COMMENTED
                                    return $query->where('district_id', '<', '65');
                                })->where(function ($query) use ($court_type) {
                                    return $query->where('court_type', $court_type)
                                                 ->orWhere('court_type', 3);
                                })
                                ->orderBy('id', 'asc')
                                ->get();

                            
                foreach($lawyers as $lawyer) {
                    $lawyer->makeHidden('id', 'district_id', 'created_at', 'updated_at');
                }
                return $lawyers;
            });
            
            return response()->json([
                'success' => true,
                'lawyers' => $lawyers,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getRentacars($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $rentacars = Cache::remember('rentacars'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $rentacars = Rentacar::orderBy('id', 'desc')
                                 // ->where('district_id', $district_id) // COMMENTED
                                 ->get();
                                 // dd($rentacars);
                 foreach($rentacars as $rentacar) {
                     $rentacar->image = $rentacar->rentacarimage ? $rentacar->rentacarimage->image : '';
                     $rentacar->makeHidden('district', 'rentacarimage', 'created_at', 'updated_at');
                 }
                 return $rentacars;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'rentacars' => $rentacars,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getNewspapers($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN')) {
            $newspapers = Cache::remember('newspapers', 365 * 24 * 60 * 60, function ()  {
               $newspapers = Newspaper::get();
               foreach($newspapers as $newspaper) {
                   $newspaper->image = $newspaper->newspaperimage ? $newspaper->newspaperimage->image : '';
                   $newspaper->makeHidden('newspaperimage', 'created_at', 'updated_at');
               }

               return $newspapers;
            });
            return response()->json([
            'success' => true,
            'newspapers' => $newspapers,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCoachings($softtoken, $type, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $coachings = Cache::remember('coachings'  . $type . $district_id, 30 * 24 * 60 * 60, function () use ($type, $district_id) {
                 $coachings = Coaching::orderBy('id', 'asc')
                                 // ->where('district_id', $district_id) // COMMENTED
                                 ->where('type', $type) // 1 = Govt, 2 = Private, 3 = Coaching
                                 ->get();
                 foreach($coachings as $coaching) {
                     $imagestemp = collect();
                     foreach($coaching->coachingimages as $coachingimage) {
                        $imagestemp->push($coachingimage->image);
                        $coachingimage->makeHidden('id', 'coaching_id', 'created_at', 'updated_at');
                        // $coaching->push($coachingimagetemp);
                     }
                     $coaching->images = $imagestemp;
                     $coaching->makeHidden('coachingimages', 'id', 'district_id', 'created_at', 'updated_at');
                 }
                 return $coachings;
            });
            
            return response()->json([
                'success' => true,
                'coachings' => $coachings,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getRabs($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $rabdata = Cache::remember('rab'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                $rab = Rab::where('district_id', $district_id)->first();
                
                $battalion_data = collect();
                
                $battalion_name = $rab != null ? $rab->rabbattalion->name : '';
                $battalion_details = $rab != null ? $rab->rabbattalion->details : '';
                $battalion_map = $rab != null ? $rab->rabbattalion->map : '';
                if($rab != null) {
                   foreach($rab->rabbattalion->rabbattaliondetails as $rabbattaliondetail) {
                       $rabbattaliondetail->designation = $rabbattaliondetail->designation;
                       $rabbattaliondetail->area = $rabbattaliondetail->area;
                       $rabbattaliondetail->mobile = $rabbattaliondetail->mobile;
                       $rabbattaliondetail->telephone = $rabbattaliondetail->telephone;
                       $battalion_data->push($rabbattaliondetail);
                       $rabbattaliondetail->makeHidden('id', 'rabbattalion_id', 'created_at', 'updated_at');
                   } 
                }
                
                $battalion_info = [
                    'battalion_name' => $battalion_name,
                    'battalion_details' => $battalion_details,
                    'battalion_map' => $battalion_map,
                    'battalion_data' => $battalion_data
                ];
                
                return $battalion_info;
            });
            return response()->json([
                'success' => true,
                'battalion_info' => $rabdata,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getBusesFrom($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $buses = Cache::remember('busesfrom'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $buses = Bus::orderBy('id', 'asc')
                             // ->where('district_id', $district_id) // COMMENTED
                             ->get();
                 foreach($buses as $bus) {
                       $bus->district_from = $bus->district->name_bangla;
                       $bus->district_to = $bus->toDistrict->name_bangla;
                       $bus->makeHidden('district', 'toDistrict', 'id', 'district_id', 'to_district', 'created_at', 'updated_at');
                 }
                 return $buses;
            });
            
            return response()->json([
                'success' => true,
                'buses' => $buses,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getBusesTo($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $buses = Cache::remember('busesto'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                 $buses = Bus::orderBy('id', 'asc')
                             // ->where('to_district', $district_id) // COMMENTED
                             ->get();
                 foreach($buses as $bus) {
                       $bus->district_from = $bus->district->name_bangla;
                       $bus->district_to = $bus->toDistrict->name_bangla;
                       $bus->makeHidden('district', 'toDistrict', 'id', 'district_id', 'to_district', 'created_at', 'updated_at');
                 }
                 return $buses;
            });
            
            return response()->json([
                'success' => true,
                'buses' => $buses,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getJournalists($softtoken, $district_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $journalists = Cache::remember('journalists'  . $district_id, 30 * 24 * 60 * 60, function () use ($district_id) {
                $journalists = Journalist::orderBy('id', 'asc')
                                // ->where('district_id', $district_id) // COMMENTED
                                ->get();

                            
                foreach($journalists as $journalist) {
                    $journalist->makeHidden('id', 'district_id', 'created_at', 'updated_at');
                }
                return $journalists;
            });
            
            return response()->json([
                'success' => true,
                'journalists' => $journalists,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }















    public function generateOTP(Request $request)
    {
        $this->validate($request,array(
            'mobile'         => 'required',
            'softtoken'      => 'required|max:191'
        ));

        if($request->softtoken == env('SOFT_TOKEN')) {

            $pool = '0123456789';
            $otp = substr(str_shuffle(str_repeat($pool, 4)), 0, 4);

            $mobile_number = 0;
            if(strlen($request->mobile) == 11) {
                $mobile_number = $request->mobile;
            } elseif(strlen($request->mobile) > 11) {
                if (strpos($request->mobile, '+') !== false) {
                    $mobile_number = substr($request->mobile, -11);
                }
            }

            // SPAM PREVENTION Layer 1
            $triedlastfivedays = Userotp::where('mobile', $mobile_number)
                                        ->where('created_at', '>=', Carbon::now()->subDays(5)->toDateTimeString())
                                        ->count();

            if($triedlastfivedays < 2) {
                // SPAM PREVENTION Layer 1
                $triedlasttwentyminutes = Userotp::where('mobile', $mobile_number)
                                        ->where('created_at', '>=', Carbon::now()->subMinutes(20)->toDateTimeString())
                                        ->count();

                if($triedlasttwentyminutes < 1) {
                    // FOR PLAY CONSOLE TESTING PURPOSE
                    // FOR PLAY CONSOLE TESTING PURPOSE
                    if($mobile_number == '01751398392') {
                       $otp = env('SMS_GATEWAY_PLAY_CONSOLE_TEST_OTP');
                    }

                    // TEXT MESSAGE OTP
                    // TEXT MESSAGE OTP

                    // NEW PANEL
                    $url = config('sms.url2');
                    $api_key = config('sms.api_key');
                    $senderid = config('sms.senderid');
                    $number = $mobile_number;
                    $message = $otp . ' is your pin for BCS Exam Aid App.';

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


                    // $url = config('sms.url');
                    // $number = $mobile_number;
                    // $text = $otp . ' is your pin for BCS Exam Aid App.';

                    // $data= array(
                    //    'username'=>config('sms.username'),
                    //    'password'=>config('sms.password'),
                    //    'number'=>"$number",
                    //    'message'=>"$text",
                    // );

                    // initialize send status
                    // $ch = curl_init(); // Initialize cURL
                    // curl_setopt($ch, CURLOPT_URL,$url);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is important
                    // $smsresult = curl_exec($ch);
                    // $p = explode("|",$smsresult);
                    // $sendstatus = $p[0];
                    // dd($smsresult);
                    // send sms
                    // if($sendstatus == 1101) {
                    //    // Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
                    // } elseif($sendstatus == 1006) {
                    //    // Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
                    // } else {
                    //    // Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
                    // }

                    // Userotp::where('mobile', $number)->delete(); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...

                    $newOTP = new Userotp();
                    $newOTP->mobile = $number;
                    $newOTP->otp = $otp;
                    $newOTP->save();

                    return $otp; 
                } else {
                    return 'Requested within 5 minutes!';
                }
                // SPAM PREVENTION Layer 2
            } else {
                return 'Requested too many times!';
            }
            // SPAM PREVENTION Layer 1
            
        } else {
            return 'Invalid Soft Token';
        }
    }

    public function loginOrCreate(Request $request)
    {    
        $user = User::where('mobile', $request['mobile'])->first();
        $userotp = Userotp::where('mobile', $request['mobile'])
                          ->orderBy('id', 'DESC')
                          ->first(); // latest টা নেওয়া হচ্ছে, এটাকার প্রিভেন্ট করার জন্য OTP ডিলেট ক্রতেসি না...
        if($userotp->otp == $request['otp']) {
            if ($user) {
                // $user->is_verified = 1;
                // $user->save();
                // $this->deleteOTP($request['mobile']); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...
                // $userTokenHandler = new UserTokenHandler();
                // $user = $userTokenHandler->regenerateUserToken($user);
                // $user->load('roles');
                $userdata = [
                    'success' => true,
                    'user' => $user,
                    'message' => 'লগইন সফল হয়েছে!',
                ];
                // if($user && Hash::check($request['password'], $user->password)){
                //     $userTokenHandler = new UserTokenHandler();
                //     $user = $userTokenHandler->regenerateUserToken($user);
                //     $user->load('roles');
                //     return $user;
                // }
            } else {
                $newUser = new User();
                DB::beginTransaction();
                try {
                    $newUser->uid = $request['mobile'];
                    $newUser->mobile = $request['mobile'];
                    $newUser->name = 'No Name';
                    $package_expiry_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 23:59:59';
                    $newUser->package_expiry_date = $package_expiry_date;
                    $newUser->role = 'user';
                    $newUser->password = Hash::make('secret123');
                    $newUser->save();
                } catch (\Exception $e) {
                    DB::rollBack();
                    // throw new \Exception($e->getMessage());
                    $userdata = [
                        'success' => false,
                        'message' => 'দুঃখিত! আবার চেষ্টা করুন।',
                    ];
                }
                DB::commit();
                $user = User::where('mobile', $request['mobile'])->first();
                $user->save();
                // $this->deleteOTP($request['mobile']); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...
                $userdata = [
                    'success' => true,
                    'user' => $user,
                    'message' => 'রেজিস্ট্রেশন সফল হয়েছে!',
                ];
            }
        }  else {
            $userdata = [
                'success' => false,
                'message' => 'সঠিক OTP প্রদান করুন!',
            ];
            // throw new \Exception('Invalid OTP');
        }

        if ($userdata) {
            return response()->json($userdata, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
        return null;
    }

    public function deleteOTP($mobile)
    {
        Userotp::where('mobile', $mobile)->delete();
    }

    public function checkUid($softtoken, $phonenumber)
    {
        $user = User::where('mobile', substr($phonenumber, -11))->first();

        if($user && $softtoken == env('SOFT_TOKEN'))
        {
            return response()->json([
                'success' => true,
                'uid' => $user->uid,
                'name' => $user->name,
                'mobile' => $user->mobile,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function checkPackageValidity($softtoken, $phonenumber)
    {
        $user = User::where('mobile', substr($phonenumber, -11))->first();

        if($user && $softtoken == env('SOFT_TOKEN'))
        {
            return response()->json([
                'success' => true,
                'package_expiry_date' => date('Y-m-d H:i:s', strtotime($user->package_expiry_date)),
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function addUser(Request $request)
    {
        $this->validate($request,array(
            'uid'         => 'required|max:191|unique:users,uid',
            'name'        => 'required|max:191',
            'mobile'      => 'required|max:191',
            'onesignal_id'      => 'sometimes|max:191',
            'softtoken'   => 'required|max:191'
        ));

        if($request->softtoken == env('SOFT_TOKEN'))
        {
            $user = new User;
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $package_expiry_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 23:59:59';
            // dd($package_expiry_date);
            $user->package_expiry_date = $package_expiry_date;
            $user->name = $request->name;
            $user->role = 'user';
            $user->mobile = substr($request->mobile, -11);
            $user->password = Hash::make('12345678');
            $user->save();
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function addOneSignalData(Request $request)
    {
        $this->validate($request,array(
            'uid'         => 'required',
            'mobile'      => 'required|max:191',
            'onesignal_id'      => 'sometimes|max:191',
            'softtoken'   => 'required|max:191'
        ));

        $user = User::where('mobile', $request->mobile)->first();

        if($user && $request->softtoken == env('SOFT_TOKEN'))
        {
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $user->save();
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function updateUser(Request $request)
    {
        $this->validate($request,array(
            'mobile'         => 'required',
            'uid'         => 'required',
            'onesignal_id'         => 'sometimes',
            'name'        => 'required|max:191',
            'softtoken'   => 'required|max:191'
        ));
        // DB::beginTransaction();
        $user = User::where('mobile', $request->mobile)->first();

        if($user && $request->softtoken == env('SOFT_TOKEN'))
        {

            $user->name = $request->name;
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $user->save();
            // DB::commit();
            return response()->json([
                'success' => true
            ]); 
        } else {
            // DB::commit();
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCourses($softtoken, $coursetype)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $courses = Cache::remember('courses'.$coursetype, 10 * 24 * 60 * 60, function () use ($coursetype) {
                 $courses = Course::select('id', 'name')
                             ->where('status', 1) // take only active courses
                             ->where('type', $coursetype) // 1 = Course, 2 = BJS MT, 3 = Bar MT, 4 = Free MT, 5 = QB
                             ->orderBy('priority', 'asc')
                             ->get();
                 foreach($courses as $course) {
                     $course->examcount = $course->courseexams->count();
                     $course->makeHidden('courseexams');
                 }
                 return $courses;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'courses' => $courses,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function testCache()
    {
        // echo Cache::forget('courseexams');
    }

    public function getCourseExams($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $courseexams = Cache::remember('courseexams'.$id, 10 * 24 * 60 * 60, function () use ($id) {
                $courseexams = Courseexam::select('course_id', 'exam_id')
                                     ->where('course_id', $id)
                                     ->orderBy('exam_id', 'desc')
                                     // ->join('exams', 'exams.id', '=', 'courseexams.exam_id')
                                     // ->orderBy('exams.available_from', 'asc')
                                     // ->select('courseexams.*')
                                     ->get();

                foreach($courseexams as $courseexam) {
                    $courseexam->name = $courseexam->exam->name;
                    $courseexam->start = $courseexam->exam->available_from;
                    $courseexam->questioncount = $courseexam->exam->examquestions->count();
                    $courseexam->syllabus = $courseexam->exam->syllabus ? $courseexam->exam->syllabus : 'N/A';
                    $courseexam->alltimeavailability = $courseexam->exam->alltimeavailability;
                    $courseexam->exam->makeHidden('id', 'name', 'examcategory_id', 'price_type', 'available_from', 'available_to', 'syllabus', 'created_at', 'updated_at', 'examquestions', 'alltimeavailability');
                }
                return $courseexams;
            });
            // dd($courseexams);
            // $courseexams = $courseexams->sortByDesc('start');
            // return 'Test';
            return response()->json([
                'success' => true,
                'exams' => $courseexams,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getOtherCourseExams($softtoken, $coursetype)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $course = Course::select('id')
                             ->where('status', 1) // take only active courses
                             ->where('type', $coursetype) // 1 = Course, 2 = BJS MT, 3 = Bar MT, 4 = Free MT, 5 = QB
                             ->first(); 


            $courseexams = Cache::remember('courseexams'.$course->id, 10 * 24 * 60 * 60, function () use ($course) {
                $courseexams = Courseexam::select('course_id', 'exam_id')
                                         ->where('course_id', $course->id)
                                         ->orderBy('exam_id', 'desc')
                                         ->get();

                foreach($courseexams as $courseexam) {
                    $courseexam->name = $courseexam->exam->name;
                    $courseexam->start = $courseexam->exam->available_from;
                    $courseexam->questioncount = $courseexam->exam->examquestions->count();
                    $courseexam->syllabus = $courseexam->exam->syllabus ? $courseexam->exam->syllabus : 'N/A';
                    $courseexam->alltimeavailability = $courseexam->exam->alltimeavailability;
                    $courseexam->exam->makeHidden('id', 'name', 'examcategory_id', 'price_type', 'available_from', 'available_to', 'syllabus', 'created_at', 'updated_at', 'examquestions', 'alltimeavailability');
                }
                return $courseexams;
            });
            // dd($courseexams);

            return response()->json([
                'success' => true,
                'exams' => $courseexams,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCourseExamQuestions($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $examquestions = Examquestion::select('exam_id', 'question_id')
                                     ->where('exam_id', $id)
                                     ->get();

            foreach($examquestions as $examquestion) {
                $examquestion = $examquestion->makeHidden(['question_id']);
                if($examquestion->question->questionexplanation) {
                    $examquestion->question->explanation = $examquestion->question->questionexplanation->explanation;
                }if($examquestion->question->questionimage) {
                    $examquestion->question->image = $examquestion->question->questionimage->image;
                }
                $examquestion->question = $examquestion->question->makeHidden(['topic_id', 'difficulty', 'created_at', 'updated_at', 'questionexplanation', 'questionimage']);
            }
            $exam = Exam::findOrFail($id);
            $exam->participation++;
            $exam->save();

            return response()->json([
                'success' => true,
                'questions' => $examquestions,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getTopicExamQuestions($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $topicquestions = Question::where('topic_id', $id)->orderBy(DB::raw('RAND()'))
                                      ->take(20)
                                      ->get();

            foreach($topicquestions as $topicquestion) {
                // dd($topicquestion);
                if($topicquestion->questionexplanation) {
                    $topicquestion->explanation = $topicquestion->questionexplanation->explanation;
                }if($topicquestion->questionimage) {
                    $topicquestion->image = $topicquestion->questionimage->image;
                }
                $topicquestion = $topicquestion->makeHidden(['topic_id', 'difficulty', 'created_at', 'updated_at', 'questionexplanation', 'questionimage']);
            }
            // dd($topicquestions);

            $topic = Topic::findOrFail($id);
            $topic->participation++;
            $topic->save();

            return response()->json([
                'success' => true,
                'questions' => $topicquestions,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getTopics($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $topics = Cache::remember('topics', 10 * 24 * 60 * 60, function () {
                $topics = Topic::all();
                return $topics;
            });
            return response()->json([
                'success' => true,
                'topics' => $topics,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getPackages($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $packages = Package::select('id', 'name', 'tagline', 'duration', 'price', 'strike_price', 'suggested')
                               ->where('status', 1)->get();

            return response()->json([
                'success' => true,
                'packages' => $packages,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function paymentProceed(Request $request)
    {
        $this->validate($request,array(
            'user_number'    =>   'required',
            'package_id'     =>   'required',
            'amount'         =>   'required',
            'trx_id'         =>   'required'
        ));

        $user = User::where('mobile', substr($request->user_number, -11))->first();
        $package = Package::findOrFail($request->package_id);
        
        if($request->softtoken == env('SOFT_TOKEN')) {
            if($user) {
                $temppayment = new Temppayment;
                $temppayment->user_id = $user->id;
                $temppayment->package_id = $request->package_id;
                $temppayment->uid = $user->uid;
                $temppayment->trx_id = $request->trx_id;
                $temppayment->amount = $request->amount;
                $temppayment->save();

                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'success' => false
                ]);
            }
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function storeMessage(Request $request)
    {
        $this->validate($request,array(
            'name'    =>   'required',
            'mobile'    =>   'required',
            'message'    =>   'required',
        ));
        
        $message = new Message;
        $message->name = $request->name;
        $message->mobile = $request->mobile;
        $message->message = $request->message;
        $message->save();
        
        return response()->json([
            'success' => true
        ]);
    }

    public function storeDoctorSerial(Request $request)
    {
        $this->validate($request,array(
            'doctor_id'     =>   'required',
            'name'          =>   'required',
            'mobile'        =>   'required',
            'serialdate'    =>   'required',
        ));
        
        $doctorserial = new Doctorserial;
        $doctorserial->doctor_id = $request->doctor_id;
        $doctorserial->name = $request->name;
        $doctorserial->mobile = $request->mobile;
        $doctorserial->serialdate = $request->serialdate;
        $doctorserial->save();

        // send sms
        $mobile_number = 0;
        $serialdoctor = Doctor::findOrFail($request->doctor_id);
        if(strlen($request->mobile) == 11) {
            $mobile_number = $request->mobile;
        } elseif(strlen($request->mobile) > 11) {
            if (strpos($request->mobile, '+') !== false) {
                $mobile_number = substr($request->mobile, -11);
            }
        }

        $text = "Appointment\n\n"
                "Dear " . $request->name . ",\n" .
                "Your appointment for" . $serialdoctor->name . " is submitted successfully.\n" .
                "We will notify you once we approve it.\n\n" .
                "Customs and VAT Co-operative Society (CVCS).\n" .
                "Login: https://cvcsbd.com/login";
        
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
        // NEW PANEL
        
        return response()->json([
            'success' => true
        ]);
    }











    public function getPaymentHistory($softtoken, $phonenumber)
    {
        $user = User::where('mobile', substr($phonenumber, -11))->first();

        if($user && $softtoken == env('SOFT_TOKEN'))
        {
            foreach($user->payments as $payment) {
                $payment->makeHidden(['id', 'user_id', 'package_id', 'uid', 'payment_status', 'card_type', 'store_amount', 'updated_at']);
            }
            // dd($user->payments);
            return response()->json([
                'success' => true,
                'paymenthistory' => $user->payments,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function sendSingleNotification(Request $request)
    {
        $this->validate($request,array(
            'mobile'               => 'required',
            'onesignal_id'         => 'required',
            'headings'             => 'required',
            'message'              => 'required',
            'softtoken'            => 'required|max:191'
        ));

        if($request->softtoken == env('SOFT_TOKEN'))
        {

            // $user = User::where('mobile', substr($request->mobile, -11))->first();
            
            OneSignal::sendNotificationToUser(
                $request->message,
                // ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
                $request->onesignal_id, // user theke na, direct input theke...
                $url = null, 
                $data = null, // array("answer" => $charioteer->answer), // to send some variable
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        }
        return response()->json([
            'success' => true,
            'onesignal_id' => $request->onesignal_id
        ]); 
    }

    public function testNotification()
    {
        OneSignal::sendNotificationToUser(
            'test',
            // ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
            "13cc498f-ebf7-4bb1-9ea6-2c8da09e0b31",
            $url = null, 
            $data = null, // array("answer" => $charioteer->answer), // to send some variable
            $buttons = null, 
            $schedule = null,
            $headings = 'Test',
        ); 

        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\Police;
use App\Fireservice;
use App\Lawyer;
use App\Rentacar;
use App\Rentacarimage;
use App\Coaching;
use App\Rab;
use App\Rabbattalion;
use App\District;

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

class AdminandothersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        $this->middleware(['admin'])->only('index', 'indexSearch');
    }

    public function index()
    {
        $districts = District::all();
                
        return view('dashboard.admins.index')
                            ->withDistricts($districts);
    }

    public function indexSingle($district_id)
    {
        $district = District::find($district_id);
        $adminscount = Admin::where('district_id', $district_id)->count();
        $admins = Admin::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.admins.single')
                            ->withDistrict($district)
                            ->withAdminscount($adminscount)
                            ->withAdmins($admins);
    }

    public function indexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $adminscount = Admin::where('district_id', $district_id)
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('mobile', 'LIKE', "%$search%")
                            ->count();
        $admins = Admin::where('district_id', $district_id)
                       ->where('name', 'LIKE', "%$search%")
                       ->orWhere('mobile', 'LIKE', "%$search%")
                       ->orderBy('id', 'asc')
                       ->paginate(10);
                
        return view('dashboard.admins.single')
                            ->withDistrict($district)
                            ->withAdminscount($adminscount)
                            ->withAdmins($admins);
    }

    public function storeAdmin(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
        ));

        $admin = new Admin;
        $admin->district_id = $district_id;
        $admin->name = $request->name;
        $admin->mobile = $request->mobile;
        $admin->save();

        Cache::forget('admins' . $district_id);
        Session::flash('success', 'Admin officer added successfully!');
        return redirect()->route('dashboard.admins.districtwise', $district_id);
    }

    public function updateAdmin(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
        ));

        $admin = Admin::find($id);
        $admin->district_id = $district_id;
        $admin->name = $request->name;
        $admin->mobile = $request->mobile;
        $admin->save();

        Cache::forget('admins' . $district_id);
        Session::flash('success', 'Admin officer updated successfully!');
        return redirect()->route('dashboard.admins.districtwise', $district_id);
    }

    public function policeIndex()
    {
        $districts = District::all();
                
        return view('dashboard.police.index')
                            ->withDistricts($districts);
    }

    public function policeIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $policecount = Police::where('district_id', $district_id)->count();
        $police = Police::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.police.single')
                            ->withDistrict($district)
                            ->withPolicecount($policecount)
                            ->withPolice($police);
    }

    public function policeIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $policecount = Police::where('district_id', $district_id)
                             ->where('name', 'LIKE', "%$search%")
                             ->orWhere('mobile', 'LIKE', "%$search%")->count();
        $police = Police::where('district_id', $district_id)
                        ->where('name', 'LIKE', "%$search%")
                        ->orWhere('mobile', 'LIKE', "%$search%")
                        ->orderBy('id', 'asc')
                        ->paginate(10);
                
        return view('dashboard.police.single')
                            ->withDistrict($district)
                            ->withPolicecount($policecount)
                            ->withPolice($police);
    }

    public function storePolice(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'station_type'       => 'required|integer',
            'mobile'              => 'required|string|max:191',
        ));

        $police = new Police;
        $police->district_id = $district_id;
        $police->name = $request->name;
        $police->station_type = $request->station_type;
        $police->mobile = $request->mobile;
        $police->save();

        Cache::forget('police' . $request->station_type . $district_id);
        Session::flash('success', 'Police officer added successfully!');
        return redirect()->route('dashboard.police.districtwise', $district_id);
    }

    public function updatePolice(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'station_type'       => 'required|integer',
            'mobile'              => 'required|string|max:191',
        ));

        $police = Police::find($id);
        $police->district_id = $district_id;
        $police->name = $request->name;
        $police->station_type = $request->station_type;
        $police->mobile = $request->mobile;
        $police->save();

        Cache::forget('police' . $request->station_type . $district_id);
        Session::flash('success', 'Police officer updated successfully!');
        return redirect()->route('dashboard.police.districtwise', $district_id);
    }

    public function fireserviceIndex()
    {
        $districts = District::all();
                
        return view('dashboard.fireservices.index')
                            ->withDistricts($districts);
    }

    public function fireserviceIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $fireservicescount = Fireservice::where('district_id', $district_id)->count();
        $fireservices = Fireservice::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.fireservices.single')
                            ->withDistrict($district)
                            ->withFireservicescount($fireservicescount)
                            ->withFireservices($fireservices);
    }

    public function fireserviceIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $fireservicescount = Fireservice::where('district_id', $district_id)
                                 ->where('name', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")->count();
        $fireservices = Fireservice::where('district_id', $district_id)
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('mobile', 'LIKE', "%$search%")
                            ->orderBy('id', 'asc')
                            ->paginate(10);

        return view('dashboard.fireservices.single')
                            ->withDistrict($district)
                            ->withFireservicescount($fireservicescount)
                            ->withFireservices($fireservices);
    }

    public function storeFireservice(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
        ));

        $fireservice = new Fireservice;
        $fireservice->district_id = $district_id;
        $fireservice->name = $request->name;
        $fireservice->mobile = $request->mobile;
        $fireservice->save();

        Cache::forget('fireservices' . $district_id);
        Session::flash('success', 'Fireservice officer added successfully!');
        return redirect()->route('dashboard.fireservices.districtwise', $district_id);
    }

    public function updateFireservice(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
        ));

        $fireservice = Fireservice::find($id);
        $fireservice->district_id = $district_id;
        $fireservice->name = $request->name;
        $fireservice->mobile = $request->mobile;
        $fireservice->save();

        Cache::forget('fireservices' . $district_id);
        Session::flash('success', 'Fireservice officer updated successfully!');
        return redirect()->route('dashboard.fireservices.districtwise', $district_id);
    }

    public function lawyerIndex()
    {
        $districts = District::all();
                
        return view('dashboard.lawyers.index')
                            ->withDistricts($districts);
    }

    public function lawyerIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $lawyerscount = Lawyer::where('district_id', $district_id)->count();
        $lawyers = Lawyer::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.lawyers.single')
                            ->withDistrict($district)
                            ->withLawyerscount($lawyerscount)
                            ->withLawyers($lawyers);
    }

    public function lawyerIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $lawyerscount = Lawyer::where('district_id', $district_id)
                                 ->where('name', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")
                                 ->orWhere('court', 'LIKE', "%$search%")->count();

        $lawyers = Lawyer::where('district_id', $district_id)
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('mobile', 'LIKE', "%$search%")
                            ->orWhere('court', 'LIKE', "%$search%")
                            ->orderBy('id', 'asc')
                            ->paginate(10);

        return view('dashboard.lawyers.single')
                            ->withDistrict($district)
                            ->withLawyerscount($lawyerscount)
                            ->withLawyers($lawyers);
    }

    public function storeLawyer(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'court_type'          => 'required',
            'court'               => 'required',
        ));

        $lawyer = new Lawyer;
        $lawyer->district_id = $district_id;
        $lawyer->court_type = $request->court_type;
        $lawyer->name = $request->name;
        $lawyer->mobile = $request->mobile;
        $lawyer->court = $request->court;
        $lawyer->save();

        Cache::forget('lawyers' . $district_id . $request->court_type);
        Session::flash('success', 'Lawyer added successfully!');
        return redirect()->route('dashboard.lawyers.districtwise', $district_id);
    }

    public function updateLawyer(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'court_type'          => 'required',
            'court'               => 'required',
        ));

        $lawyer = Lawyer::find($id);
        $lawyer->district_id = $district_id;
        $lawyer->court_type = $request->court_type;
        $lawyer->name = $request->name;
        $lawyer->mobile = $request->mobile;
        $lawyer->court = $request->court;
        $lawyer->save();

        Cache::forget('lawyers' . $district_id . 1);
        Cache::forget('lawyers' . $district_id . 2);
        Cache::forget('lawyers' . $district_id . 3);
        Session::flash('success', 'Lawyer updated successfully!');
        return redirect()->route('dashboard.lawyers.districtwise', $district_id);
    }

    public function rentacarIndex()
    {
        $districts = District::all();
                
        return view('dashboard.rentacars.index')
                            ->withDistricts($districts);
    }

    public function rentacarIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $rentacarscount = Rentacar::where('district_id', $district_id)->count();
        $rentacars = Rentacar::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.rentacars.single')
                            ->withDistrict($district)
                            ->withRentacarscount($rentacarscount)
                            ->withRentacars($rentacars);
    }

    public function rentacarIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $rentacarscount = Rentacar::where('district_id', $district_id)
                                 ->where('name', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")->count();

        $rentacars = Rentacar::where('district_id', $district_id)
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('mobile', 'LIKE', "%$search%")
                            ->orderBy('id', 'asc')
                            ->paginate(10);

        return view('dashboard.rentacars.single')
                            ->withDistrict($district)
                            ->withRentacarscount($rentacarscount)
                            ->withRentacars($rentacars);
    }

    public function storeRentacar(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'           => 'required|string|max:191',
            'mobile'         => 'required|string|max:191',
            'image'          => 'sometimes',
        ));

        $rentacar = new Rentacar;
        $rentacar->district_id = $district_id;
        $rentacar->name = $request->name;
        $rentacar->mobile = $request->mobile;
        $rentacar->save();

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/rentacars/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $rentacarimage              = new Rentacarimage;
            $rentacarimage->rentacar_id   = $rentacar->id;
            $rentacarimage->image       = $filename;
            $rentacarimage->save();
        }

        Cache::forget('rentacars' . $district_id);
        Session::flash('success', 'Rent-a-Car added successfully!');
        return redirect()->route('dashboard.rentacars.districtwise', $district_id);
    }

    public function updateRentacar(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'           => 'required|string|max:191',
            'mobile'         => 'required|string|max:191',
            'image'          => 'sometimes',
        ));

        $rentacar = Rentacar::find($id);
        $rentacar->district_id = $district_id;
        $rentacar->name = $request->name;
        $rentacar->mobile = $request->mobile;
        $rentacar->save();

        // image upload
        if($request->hasFile('image')) {
            $image_path = public_path('images/rentacars/'. $rentacar->rentacarimage->image);
            // dd($image_path);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/rentacars/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $rentacarimage              = Rentacarimage::where('rentacar_id', $rentacar->id)->first();
            $rentacarimage->image       = $filename;
            $rentacarimage->save();
        }

        Cache::forget('rentacars' . $district_id);
        Session::flash('success', 'Rent-a-Car updated successfully!');
        return redirect()->route('dashboard.rentacars.districtwise', $district_id);
    }

    public function coachingIndex()
    {
        $districts = District::all();
                
        return view('dashboard.coachings.index')
                            ->withDistricts($districts);
    }

    public function coachingIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $coachingscount = Coaching::where('district_id', $district_id)->count();
        $coachings = Coaching::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.coachings.single')
                            ->withDistrict($district)
                            ->withCoachingscount($coachingscount)
                            ->withCoachings($coachings);
    }

    public function coachingIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $coachingscount = Coaching::where('district_id', $district_id)
                                 ->where('name', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")
                                 ->orWhere('address', 'LIKE', "%$search%")->count();

        $coachings = Coaching::where('district_id', $district_id)
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('mobile', 'LIKE', "%$search%")
                            ->orWhere('address', 'LIKE', "%$search%")
                            ->orderBy('id', 'asc')
                            ->paginate(10);

        return view('dashboard.coachings.single')
                            ->withDistrict($district)
                            ->withCoachingscount($coachingscount)
                            ->withcoachings($coachings);
    }

    public function storeCoaching(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'address'             => 'required|string|max:191',
        ));

        $coaching = new Coaching;
        $coaching->district_id = $district_id;
        $coaching->name = $request->name;
        $coaching->mobile = $request->mobile;
        $coaching->address = $request->address;
        $coaching->save();

        Cache::forget('coachings' . $district_id);
        Session::flash('success', 'Coaching added successfully!');
        return redirect()->route('dashboard.coachings.districtwise', $district_id);
    }

    public function updateCoaching(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
            'address'             => 'required|string|max:191',
        ));

        $coaching = Coaching::find($id);
        $coaching->district_id = $district_id;
        $coaching->name = $request->name;
        $coaching->mobile = $request->mobile;
        $coaching->address = $request->address;
        $coaching->save();

        Cache::forget('coachings' . $district_id);
        Session::flash('success', 'Coaching updated successfully!');
        return redirect()->route('dashboard.coachings.districtwise', $district_id);
    }

    public function rabIndex()
    {
        $districts = District::all();
        $rabbattalions = Rabbattalion::all();
                
        return view('dashboard.rabs.index')
                            ->withDistricts($districts)
                            ->withRabbattalions($rabbattalions);
    }

    public function storeRabbattalion(Request $request)
    {
        $this->validate($request,array(
            'name'            => 'required|string|max:191',
            'details'         => 'required',
            'map'             => 'required|image',
        ));

        $rabbattalion = new Rabbattalion;
        $rabbattalion->name = $request->name;
        $rabbattalion->details = $request->details;
        // image upload
        if($request->hasFile('map')) {
            $image    = $request->file('map');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/rabbattalions/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $rabbattalion->map       = $filename;
        }
        $rabbattalion->save();

        Cache::forget('rabbattalions');
        Session::flash('success', 'RAB Battalion added successfully!');
        return redirect()->route('dashboard.rabs');
    }

    public function updateRabbattalion(Request $request, $id)
    {
        $this->validate($request,array(
            'name'            => 'required|string|max:191',
            'details'         => 'required',
            'map'             => 'sometimes|image',
        ));

        $rabbattalion = Rabbattalion::find($id);
        $rabbattalion->name = $request->name;
        $rabbattalion->details = $request->details;
        // image upload
        if($request->hasFile('map')) {
            $image_path = public_path('images/rabbattalions/'. $rabbattalion->map);
            // dd($image_path);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $image    = $request->file('map');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/rabbattalions/'. $filename);
            Image::make($image)->resize(300, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $rabbattalion->image       = $filename;
        }
        $rabbattalion->save();

        Cache::forget('rabbattalions');
        Session::flash('success', 'RAB Battalion updated successfully!');
        return redirect()->route('dashboard.rabs');
    }

}

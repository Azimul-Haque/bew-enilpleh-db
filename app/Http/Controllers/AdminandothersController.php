<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
use App\Police;
use App\Fireservice;
use App\Lawyer;
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
            'court_type'              => 'required',
            'court'              => 'required',
        ));

        $lawyer = new Lawyer;
        $lawyer->district_id = $district_id;
        $lawyer->court_type = $request->court_type;
        $lawyer->name = $request->name;
        $lawyer->mobile = $request->mobile;
        $lawyer->court = $request->court;
        $lawyer->save();

        Cache::forget('lawyers' . $district_id);
        Session::flash('success', 'Lawyer added successfully!');
        return redirect()->route('dashboard.lawyers.districtwise', $district_id);
    }

    public function updateLawyer(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'mobile'              => 'required|string|max:191',
        ));

        $lawyer = Lawyer::find($id);
        $lawyer->district_id = $district_id;
        $lawyer->name = $request->name;
        $lawyer->mobile = $request->mobile;
        $lawyer->save();

        Cache::forget('lawyers' . $district_id);
        Session::flash('success', 'Lawyer updated successfully!');
        return redirect()->route('dashboard.lawyers.districtwise', $district_id);
    }


}

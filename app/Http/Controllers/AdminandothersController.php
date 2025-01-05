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
use App\Coachingimage;
use App\Rab;
use App\Rabbattalion;
use App\Rabbattaliondetail;
use App\Bus;
use App\Journalist;
use App\Newspaper;
use App\Newspaperimage;
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
        $this->middleware(['admin'])->only('index', 'indexSearch', 'lawyerIndex', 'busIndex', 'rentacarIndex', 'newspaperIndex', 'journalistIndex');
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
            'description'          => 'sometimes|string|max:500',
        ));

        $rentacar = new Rentacar;
        $rentacar->district_id = $district_id;
        $rentacar->name = $request->name;
        $rentacar->mobile = $request->mobile;
        $rentacar->description = $request->description;
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
            'description'          => 'sometimes|string|max:500',
        ));

        $rentacar = Rentacar::find($id);
        $rentacar->district_id = $district_id;
        $rentacar->name = $request->name;
        $rentacar->mobile = $request->mobile;
        $rentacar->description = $request->description;
        $rentacar->save();

        // image upload
        if($request->hasFile('image')) {
            if($rentacar->rentacarimage != null) {
              $image_path = public_path('images/rentacars/'. $rentacar->rentacarimage->image);
              // dd($image_path);
              if(File::exists($image_path)) {
                  File::delete($image_path);
              }
              $rentacarimage              = Rentacarimage::where('rentacar_id', $rentacar->id)->first();
            } else {
                $rentacarimage            = new Rentacarimage;
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/rentacars/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $rentacarimage->rentacar_id = $rentacar->id;
            $rentacarimage->image       = $filename;
            $rentacarimage->save();
        }

        Cache::forget('rentacars' . $district_id);
        Session::flash('success', 'Rent-a-Car updated successfully!');
        return redirect()->route('dashboard.rentacars.districtwise', $district_id);
    }

    public function journalistIndex()
    {
        $districts = District::all();
                
        return view('dashboard.journalists.index')
                            ->withDistricts($districts);
    }

    public function journalistIndexSingle($district_id)
    {
        $district = District::find($district_id);
        $journalistscount = Journalist::where('district_id', $district_id)->count();
        $journalists = Journalist::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.journalists.single')
                            ->withDistrict($district)
                            ->withJournalistscount($journalistscount)
                            ->withJournalists($journalists);
    }

    public function journalistIndexSearch($district_id, $search)
    {
        $district = District::find($district_id);
        $journalistscount = Journalist::where('district_id', $district_id)
                                 ->where('name', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")
                                 ->orWhere('court', 'LIKE', "%$search%")->count();

        $query = Journalist::query();
        $query->where('district_id', $district_id);
        $searchParam = $search;
        $query->where(function ($q) use ($searchParam) {
            $q->where('name', 'like', '%' . $searchParam . '%')
                ->orWhere('mobile', 'like', '%' . $searchParam . '%')
                ->orWhere('affiliation', 'like', '%' . $searchParam . '%');
        });

        $journalists = $query->paginate(10);

        return view('dashboard.journalists.single')
                            ->withDistrict($district)
                            ->withJournalistscount($journalistscount)
                            ->withJournalists($journalists);
    }

    public function storeJournalist(Request $request, $district_id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'affiliation'         => 'required',
            'mobile'              => 'required|string|max:191',
        ));

        $journalist = new Journalist;
        $journalist->district_id = $district_id;
        $journalist->name = $request->name;
        $journalist->affiliation = $request->affiliation;
        $journalist->mobile = $request->mobile;
        $journalist->save();

        Cache::forget('journalists' . $district_id);
        Session::flash('success', 'Journalist added successfully!');
        return redirect()->route('dashboard.journalists.districtwise', $district_id);
    }

    public function updateJournalist(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'affiliation'         => 'required',
            'mobile'              => 'required|string|max:191',
        ));

        $journalist = Journalist::find($id);
        $journalist->district_id = $district_id;
        $journalist->name = $request->name;
        $journalist->affiliation = $request->affiliation;
        $journalist->mobile = $request->mobile;
        $journalist->save();

        Cache::forget('journalists' . $district_id);
        Session::flash('success', 'Journalist updated successfully!');
        return redirect()->route('dashboard.journalists.districtwise', $district_id);
    }

    public function coachingIndex()
    {
        if(Auth::user()->role == 'editor' || !in_array('coachings', Auth::user()->accessibleTables())) {
            abort(403, 'Access Denied');
        }
        $districts = District::all();
                
        return view('dashboard.coachings.index')
                            ->withDistricts($districts);
    }

    public function coachingIndexSingle($district_id)
    {
        if(Auth::user()->role == 'editor' || !in_array('coachings', Auth::user()->accessibleTables())) {
            abort(403, 'Access Denied');
        }        
        $district = District::find($district_id);
        $coachingscount = Coaching::where('district_id', $district_id)->count();
            $coachings = Coaching::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.coachings.single')
                            ->withDistrict($district)
                            ->withCoachingscount($coachingscount)
                            ->withCoachings($coachings);
    }

    public function coachingIndexSingleForEditor()
    {
        if(!in_array('coachings', Auth::user()->accessibleTables())) {
            abort(403, 'Access Denied');
        }
        $coachingscount = Auth::user()->accessibleCoachings()->count();
        $coachings = Auth::user()->accessibleCoachings()->paginate(10);
                
        return view('dashboard.coachings.singleforeditors')
                            ->withCoachingscount($coachingscount)
                            ->withCoachings($coachings);
    }

    public function coachingIndexSearch($district_id, $search)
    {
        if(Auth::user()->role == 'editor') {
            abort(403, 'Access Denied');
        }
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
            'type'                => 'required',
            'mobile'              => 'required|string|max:191',
            'address'             => 'required|string|max:191',
            'image1'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image2'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image3'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image4'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'description'             => 'sometimes|string|max:500',
        ));

        $coaching = new Coaching;
        $coaching->district_id = $district_id;
        $coaching->name = $request->name;
        $coaching->type = $request->type;
        $coaching->mobile = $request->mobile;
        $coaching->address = $request->address;
        $coaching->description = $request->description;
        $coaching->save();

        if(Auth::user()->role == 'editor') {
            Auth::user()->accessibleCoachings()->attach($coaching);
        }

        // image upload
        // image upload
        if($request->hasFile('image1')) {
            $image    = $request->file('image1');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage1              = new Coachingimage;
            $coachingimage1->coaching_id = $coaching->id;
            $coachingimage1->image       = $filename;
            $coachingimage1->save();
        }
        if($request->hasFile('image2')) {
            $image    = $request->file('image2');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage2              = new Coachingimage;
            $coachingimage2->coaching_id = $coaching->id;
            $coachingimage2->image       = $filename;
            $coachingimage2->save();
        }
        if($request->hasFile('image3')) {
            $image    = $request->file('image3');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage3              = new Coachingimage;
            $coachingimage3->coaching_id = $coaching->id;
            $coachingimage3->image       = $filename;
            $coachingimage3->save();
        }
        if($request->hasFile('image4')) {
            $image    = $request->file('image4');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage4              = new Coachingimage;
            $coachingimage4->coaching_id = $coaching->id;
            $coachingimage4->image       = $filename;
            $coachingimage4->save();
        }
        // image upload
        // image upload

        Cache::forget('coachings' . 1 . $district_id);
        Cache::forget('coachings' . 2 . $district_id);
        Cache::forget('coachings' . 3 . $district_id);
        Session::flash('success', 'Coaching added successfully!');
        return redirect()->route('dashboard.coachings.districtwise', $district_id);
    }

    public function updateCoaching(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'type'                => 'required',
            'mobile'              => 'required|string|max:191',
            'address'             => 'required|string|max:191',
            'image1'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image2'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image3'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'image4'            => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1000',
            'description'             => 'sometimes|string|max:500',
        ));

        $coaching = Coaching::find($id);
        $coaching->district_id = $district_id;
        $coaching->name = $request->name;
        $coaching->type = $request->type;
        $coaching->mobile = $request->mobile;
        $coaching->address = $request->address;
        $coaching->description = $request->description;
        $coaching->save();

        // image upload
        // image upload
        if($request->hasFile('image1')) {
            if($coaching->coachingimages) {
                foreach($coaching->coachingimages as $coachingimage) {
                    $image_path = public_path('images/coachings/'. $coachingimage->image);
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    $coachingimage->delete();
                }
            }
            $image    = $request->file('image1');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage1              = new Coachingimage;
            $coachingimage1->coaching_id = $coaching->id;
            $coachingimage1->image       = $filename;
            $coachingimage1->save();
        }
        if($request->hasFile('image2')) {
            if($coaching->coachingimages) {
                foreach($coaching->coachingimages as $coachingimage) {
                    $image_path = public_path('images/coachings/'. $coachingimage->image);
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    $coachingimage->delete();
                }
            }
            $image    = $request->file('image2');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage2              = new Coachingimage;
            $coachingimage2->coaching_id = $coaching->id;
            $coachingimage2->image       = $filename;
            $coachingimage2->save();
        }
        if($request->hasFile('image3')) {
            if($coaching->coachingimages) {
                foreach($coaching->coachingimages as $coachingimage) {
                    $image_path = public_path('images/coachings/'. $coachingimage->image);
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    $coachingimage->delete();
                }
            }
            $image    = $request->file('image3');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage3              = new Coachingimage;
            $coachingimage3->coaching_id = $coaching->id;
            $coachingimage3->image       = $filename;
            $coachingimage3->save();
        }
        if($request->hasFile('image4')) {
            if($coaching->coachingimages) {
                foreach($coaching->coachingimages as $coachingimage) {
                    $image_path = public_path('images/coachings/'. $coachingimage->image);
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                    $coachingimage->delete();
                }
            }
            $image    = $request->file('image4');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/coachings/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $coachingimage4              = new Coachingimage;
            $coachingimage4->coaching_id = $coaching->id;
            $coachingimage4->image       = $filename;
            $coachingimage4->save();
        }
        // image upload
        // image upload

        Cache::forget('coachings' . 1 . $district_id);
        Cache::forget('coachings' . 2 . $district_id);
        Cache::forget('coachings' . 3 . $district_id);
        Session::flash('success', 'Coaching updated successfully!');

        // return redirect()->route('dashboard.coachings.districtwise', $district_id);
        return redirect()->back();
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
            Image::make($image)->resize(400, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $rabbattalion->map       = $filename;
        }
        $rabbattalion->save();

        // RAB cache niye kaaj ache
        // RAB cache niye kaaj ache
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
            Image::make($image)->resize(500, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $rabbattalion->map       = $filename;
        }
        $rabbattalion->save();

        // RAB cache niye kaaj ache
        // RAB cache niye kaaj ache
        Cache::forget('rabbattalions');
        Session::flash('success', 'RAB Battalion updated successfully!');
        return redirect()->route('dashboard.rabs');
    }

    public function detailsRabbattalion($id)
    {
        $rabbattallion = Rabbattalion::findOrFail($id);
        $rabbattallionofficerscount = Rabbattaliondetail::where('rabbattalion_id', $id)->count();
        $rabbattallionofficers = Rabbattaliondetail::where('rabbattalion_id', $id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.rabs.rabbbatalionsdetails')
                            ->withRabbattallion($rabbattallion)
                            ->withRabbattallionofficerscount($rabbattallionofficerscount)
                            ->withRabbattallionofficers($rabbattallionofficers);
        
    }

    public function detailsRabbattalionSearch($battalion_id, $search)
    {
        $rabbattallion = Rabbattalion::findOrFail($battalion_id);
        
        $rabbattallionofficerscount = Rabbattaliondetail::where('rabbattalion_id', $battalion_id)
                                 ->where('designation', 'LIKE', "%$search%")
                                 ->orWhere('area', 'LIKE', "%$search%")
                                 ->orWhere('mobile', 'LIKE', "%$search%")
                                 ->orWhere('telephone', 'LIKE', "%$search%")->count();

        // Initialize query
        $query = Rabbattaliondetail::where('rabbattalion_id', $battalion_id);

        // If the search parameter is provided, apply it to designation, area, mobile, and telephone fields
        $query->where(function($q) use ($search) {
            $q->where('designation', 'LIKE', '%' . $search . '%')
              ->orWhere('area', 'LIKE', '%' . $search . '%')
              ->orWhere('mobile', 'LIKE', '%' . $search . '%')
              ->orWhere('telephone', 'LIKE', '%' . $search . '%');
        });

        // Get the results
        $rabbattallionofficers = $query->orderBy('id', 'asc')->paginate(10);

        return view('dashboard.rabs.rabbbatalionsdetails')
                            ->withRabbattallion($rabbattallion)
                            ->withRabbattallionofficerscount($rabbattallionofficerscount)
                            ->withRabbattallionofficers($rabbattallionofficers);
    }

    public function storeDetailsRabbattalion(Request $request, $battalion_id)
    {
        $this->validate($request,array(
            'designation'       => 'required|string|max:191',
            'area'              => 'required',
            'mobile'            => 'required|string|max:191',
            'telephone'         => 'sometimes',
        ));

        $rabbattaliondetail = new Rabbattaliondetail;
        $rabbattaliondetail->rabbattalion_id = $battalion_id;
        $rabbattaliondetail->designation = $request->designation;
        $rabbattaliondetail->area = $request->area;
        $rabbattaliondetail->mobile = $request->mobile;
        if($request->telephone != '') {
            $rabbattaliondetail->telephone = $request->telephone;
        }
        $rabbattaliondetail->save();

        // RAB cache niye kaaj ache
        // RAB cache niye kaaj ache
        Cache::forget('rabbattaliondetail' . $battalion_id);
        Session::flash('success', 'RAB Officer added successfully!');
        return redirect()->route('dashboard.rabbattalions.details', $battalion_id);
    }

    public function updateDetailsRabbattalion(Request $request, $battalion_id, $id)
    {
        $this->validate($request,array(
            'designation'       => 'required|string|max:191',
            'area'              => 'required',
            'mobile'            => 'required|string|max:191',
            'telephone'         => 'sometimes',
        ));

        $rabbattaliondetail = Rabbattaliondetail::findOrFail($id);
        $rabbattaliondetail->rabbattalion_id = $battalion_id;
        $rabbattaliondetail->designation = $request->designation;
        $rabbattaliondetail->area = $request->area;
        $rabbattaliondetail->mobile = $request->mobile;
        if($request->telephone != '') {
            $rabbattaliondetail->telephone = $request->telephone;
        } else {
            $rabbattaliondetail->telephone = '';
        }
        $rabbattaliondetail->save();

        // RAB cache niye kaaj ache
        // RAB cache niye kaaj ache
        Cache::forget('rabbattaliondetail' . $battalion_id);
        Session::flash('success', 'RAB Officer updated successfully!');
        return redirect()->route('dashboard.rabbattalions.details', $battalion_id);
    }

    public function updateDistrictRabbattalion(Request $request, $district_id)
    {
        $this->validate($request,array(
            'rabbattalion_id'       => 'required'
        ));

        $checkdistrictrab = Rab::where('district_id', $district_id)->first();

        
        if($checkdistrictrab != null) {
            $checkdistrictrab->rabbattalion_id = $request->rabbattalion_id;
            $checkdistrictrab->save();
        } else {
            $rab = new Rab;
            $rab->district_id = $district_id;
            $rab->rabbattalion_id = $request->rabbattalion_id;
            $rab->save();
        }

        Cache::forget('rabs' . $district_id);
        Session::flash('success', 'District and RAB Battalion updated successfully!');
        return redirect()->route('dashboard.rabs');
    }

    public function busIndex()
    {
        $districts = District::all();
                
        return view('dashboard.buses.index')
                            ->withDistricts($districts);
    }

    public function busIndexSingle($district_id)
    {
        $districts = District::all();
        $district = District::find($district_id);
        $busescount = Bus::where('district_id', $district_id)->count();
        $buses = Bus::where('district_id', $district_id)->orderBy('id', 'asc')->paginate(10);
                
        return view('dashboard.buses.single')
                            ->withDistricts($districts)
                            ->withDistrict($district)
                            ->withBusescount($busescount)
                            ->withBuses($buses);
    }

    public function busIndexSearch($district_id, $search)
    {
        $districts = District::all();
        $district = District::find($district_id);
        $busescount = Bus::where('district_id', $district_id)
                                 ->where('bus_name', 'LIKE', "%$search%")
                                 ->orWhere('route_info', 'LIKE', "%$search%")
                                 ->orWhere('contact', 'LIKE', "%$search%")
                                 ->orWhere('bus_type', 'LIKE', "%$search%")->count();

        // Initialize query
        $query = Bus::where('district_id', $district_id);

        // If the search parameter is provided, apply it to rest of the fields
        $query->where(function($q) use ($search) {
            $q->where('bus_name', 'LIKE', '%' . $search . '%')
              ->orWhere('route_info', 'LIKE', '%' . $search . '%')
              ->orWhere('bus_type', 'LIKE', '%' . $search . '%')
              ->orWhere('contact', 'LIKE', '%' . $search . '%');
        });

        // Get the results
        $buses = $query->orderBy('id', 'asc')->paginate(10);

        return view('dashboard.buses.single')
                            ->withDistricts($districts)
                            ->withDistrict($district)
                            ->withBusescount($busescount)
                            ->withBuses($buses);
    }

    public function storeBus(Request $request, $district_id)
    {
        $this->validate($request,array(
            'to_district'      => 'required',
            'bus_name'         => 'required|string|max:191',
            'route_info'       => 'required|string',
            'bus_type'         => 'required|string|max:191',
            'fare'             => 'required|string|max:191',
            'starting_time'    => 'required|string|max:191',
            'counter_address'    => 'required|string|max:191',
            'contact'          => 'required|string|max:191',
        ));

        $bus = new Bus;
        $bus->district_id = $district_id;
        $bus->to_district = $request->to_district;
        $bus->bus_name = $request->bus_name;
        $bus->route_info = $request->route_info;
        $bus->bus_type = $request->bus_type;
        $bus->fare = $request->fare;
        $bus->starting_time = $request->starting_time;
        $bus->counter_address = $request->counter_address;
        $bus->contact = $request->contact;
        $bus->save();


        Cache::forget('busesfrom' . $district_id);
        Cache::forget('busesto' . $request->to_district);
        Session::flash('success', 'Bus added successfully!');
        return redirect()->route('dashboard.buses.districtwise', $district_id);
    }

    public function updateBus(Request $request, $district_id, $id)
    {
        $this->validate($request,array(
            'to_district'      => 'required',
            'bus_name'         => 'required|string|max:191',
            'route_info'       => 'required|string',
            'bus_type'         => 'required|string|max:191',
            'fare'             => 'required|string|max:191',
            'starting_time'    => 'required|string|max:191',
            'counter_address'    => 'required|string|max:191',
            'contact'          => 'required|string|max:191',
        ));

        $bus = Bus::find($id);
        // $bus->district_id = $district_id; // dorkar nai
        $bus->to_district = $request->to_district;
        $bus->bus_name = $request->bus_name;
        $bus->route_info = $request->route_info;
        $bus->bus_type = $request->bus_type;
        $bus->fare = $request->fare;
        $bus->starting_time = $request->starting_time;
        $bus->counter_address = $request->counter_address;
        $bus->contact = $request->contact;
        $bus->save();

        Cache::forget('busesfrom' . $district_id);
        Cache::forget('busesto' . $request->to_district);
        Session::flash('success', 'Bus added successfully!');
        return redirect()->route('dashboard.buses.districtwise', $district_id);
    }

    public function newspaperIndex()
    {
        $newspaperscount = Newspaper::count();
        $newspapers = Newspaper::orderBy('id', 'desc')->paginate(10);
                
        return view('dashboard.newspapers.index')
                            ->withNewspaperscount($newspaperscount)
                            ->withNewspapers($newspapers);
    }

    public function newspaperIndexSearch($search)
    {
        $newspaperscount = Newspaper::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")->count();

        $newspapers = Newspaper::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")
                                  ->orderBy('id', 'desc')
                                  ->paginate(10);
        
        return view('dashboard.newspapers.index')
                            ->withNewspaperscount($newspaperscount)
                            ->withNewspapers($newspapers);
    }

    public function storeNewspaper(Request $request)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'url'                 => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $newspaper = new Newspaper;
        $newspaper->name = $request->name;
        $newspaper->url = $request->url;
        $newspaper->save();

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/newspapers/'. $filename);
            Image::make($image)->fit(300, 70)->save($location);
            $newspaperimage              = new Newspaperimage;
            $newspaperimage->newspaper_id   = $newspaper->id;
            $newspaperimage->image       = $filename;
            $newspaperimage->save();
        }
        
        Cache::forget('newspapers');
        Session::flash('success', 'Newspaper added successfully!');
        return redirect()->route('dashboard.newspapers');
    }

    public function updateNewspaper(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'url'              => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $newspaper = Newspaper::find($id);
        $newspaper->name = $request->name;
        $newspaper->url = $request->url;
        $newspaper->save();

        // image upload
        if($request->hasFile('image')) {
            if($newspaper->newspaperimage !=null) {
               $image_path = public_path('images/newspapers/'. $newspaper->newspaperimage->image);
               // dd($image_path);
               if(File::exists($image_path)) {
                   File::delete($image_path);
               }
               $newspaperimage              = Newspaperimage::where('newspaper_id', $newspaper->id)->first();
            } else {
               $newspaperimage              = new Newspaperimage;
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/newspapers/'. $filename);
            Image::make($image)->fit(300, 70)->save($location);
            $newspaperimage->newspaper_id   = $newspaper->id;
            $newspaperimage->image       = $filename;
            $newspaperimage->save();
        }

        
        Cache::forget('newspapers');
        Session::flash('success', 'Newspaper updated successfully!');
        return redirect()->route('dashboard.newspapers');
    }
}

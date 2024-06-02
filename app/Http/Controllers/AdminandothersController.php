<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
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
        $admins = Admin::where('district_id', $district_id)->orderBy('id', 'desc')->paginate(10);
                
        return view('dashboard.admins.single')
                            ->withDistrict($district)
                            ->withAdminscount($adminscount)
                            ->withAdmins($admins);
    }

    public function indexSearch($search)
    {
        $eshebascount = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")->count();

        $eshebas = Esheba::where('name', 'LIKE', "%$search%")
                                  ->orWhere('url', 'LIKE', "%$search%")
                                  ->orderBy('id', 'desc')
                                  ->paginate(10);
        
        return view('dashboard.eshebas.index')
                            ->withEshebascount($eshebascount)
                            ->withEshebas($eshebas);
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
        Session::flash('success', 'E-sheba added successfully!');
        return redirect()->route('dashboard.eshebas');
    }

    public function updateEsheba(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                => 'required|string|max:191',
            'url'              => 'required|string|max:191',
            'image'               => 'sometimes',
        ));

        $esheba = Esheba::find($id);
        $esheba->name = $request->name;
        $esheba->url = $request->url;
        $esheba->save();

        // image upload
        if($request->hasFile('image')) {
            $image_path = public_path('images/eshebas/'. $esheba->eshebaimage->image);
            // dd($image_path);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/eshebas/'. $filename);
            Image::make($image)->fit(200, 200)->save($location);
            $eshebaimage              = Eshebaimage::where('esheba_id', $esheba->id)->first();
            $eshebaimage->image       = $filename;
            $eshebaimage->save();
        }

        
        Cache::forget('eshebas');
        Session::flash('success', 'E-sheba updated successfully!');
        return redirect()->route('dashboard.eshebas');
    }
}

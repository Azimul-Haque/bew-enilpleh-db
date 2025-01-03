<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Message;
use App\Notification;

use App\Hospital;
use App\Doctor;
use App\Blooddonor;
use App\Coaching;

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

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->except('clear');
        $this->middleware(['admin'])->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'getUser', 'getMessages', 'updateMessage', 'getNotifications');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // if user is a manager, redirect him to his profile
        // if user is a manager, redirect him to his profile
        if(Auth::user()->role == 'user') {
            abort(403, 'Access Denied');
        } 

        // $totalsites = Site::count();
        $totalusers = User::count();

        $last14daysusersdaily = DB::table('users')
                                    ->select('created_at', DB::raw('COUNT(*) as totalusers'))
                                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                    ->orderBy('created_at', 'DESC')
                                    ->take(14)
                                    ->get();
        $daysforchartc = [];
        foreach ($last14daysusersdaily as $key => $days) {
            $daysforchartc[] = date_format(date_create($days->created_at), "M d");
        }
        $daysforchartc = json_encode(array_reverse($daysforchartc));

        $totalusersforchartc = [];
        foreach ($last14daysusersdaily as $key => $days) {
            $totalusersforchartc[] = $days->totalusers;
        }
        $originalforcumulitive = array_reverse($totalusersforchartc);
        $totalusersforchartc = json_encode(array_reverse($totalusersforchartc));

        $totaluserscumulitiveforchartc = [];
        $cumulitiveusersprimary = 0;
        foreach ($originalforcumulitive as $totalusersforc) {
            $cumulitiveusersprimary += $totalusersforc;
            $totaluserscumulitiveforchartc[] = $cumulitiveusersprimary;
        }
        $totaluserscumulitiveforchartc = json_encode($totaluserscumulitiveforchartc);
        // dd($totaluserscumulitiveforchartc);

        return view('dashboard.index')->withTotalusers($totalusers)
                                      ->withDaysforchartc($daysforchartc)
                                      ->withTotalusersforchartc($totalusersforchartc)
                                      ->withTotaluserscumulitiveforchartc($totaluserscumulitiveforchartc);
    }

    public function clearQueryCache()
    {
        Cache::flush();
        Session::flash('success', 'সকল কোয়েরি ক্যাশ মুছে দেওয়া হয়েছে!');
        return redirect()->route('dashboard.index');
    }









    public function getUsers()
    {
        $userscount = User::count();
        $users = User::where('name', '!=', null)->orderBy('id', 'asc')->paginate(10);

        $hospitals = Hospital::all();
        $doctors = Doctor::all();
        $blooddonors = Blooddonor::all();
        $coachings = Coaching::all();

        return view('dashboard.users.index')
                    ->withUsers($users)
                    ->withUserscount($userscount)
                    ->withHospitals($hospitals)
                    ->withDoctors($doctors)
                    ->withBlooddonors($blooddonors)
                    ->withCoachings($coachings);
    }

    public function getUsersSearch($search)
    {
        $userscount = User::where('name', 'LIKE', "%$search%")
                          ->orWhere('email', 'LIKE', "%$search%")
                          ->orWhere('mobile', 'LIKE', "%$search%")
                          ->orWhere('uid', 'LIKE', "%$search%")
                          ->orWhere('onesignal_id', 'LIKE', "%$search%")
                          ->orderBy('id', 'desc')
                          ->count();
        $users = User::where('name', 'LIKE', "%$search%")
                     ->orWhere('email', 'LIKE', "%$search%")
                     ->orWhere('mobile', 'LIKE', "%$search%")
                     ->orWhere('uid', 'LIKE', "%$search%")
                     ->orWhere('onesignal_id', 'LIKE', "%$search%")
                     ->orderBy('id', 'desc')
                     ->paginate(10);

        $hospitals = Hospital::all();
        $doctors = Doctor::all();
        $blooddonors = Blooddonor::all();
        $coachings = Coaching::all();

        return view('dashboard.users.index')
                    ->withUsers($users)
                    ->withUserscount($userscount)
                    ->withHospitals($hospitals)
                    ->withDoctors($doctors)
                    ->withBlooddonors($blooddonors)
                    ->withCoachings($coachings);
    }

    public function storeUser(Request $request)
    {
        // dd(serialize($request->sitecheck));
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'role'        => 'required',
            'hospitals'   => 'sometimes',
            'doctors'   => 'sometimes',
            'blooddonors'   => 'sometimes',
            'coachings'   => 'sometimes',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();

        if(isset($request->hospitals)){
            foreach($request->hospitals as $hospital_id) {
                $hospital = Hospital::find($hospital_id);
                $user->accessibleHospitals()->attach($hospital);
            }            
        }

        if(isset($request->doctors)){
            foreach($request->doctors as $doctors_id) {
                $doctor = Doctor::find($doctors_id);
                $user->accessibleDoctors()->attach($doctor);
            }            
        }

        if(isset($request->blooddonors)){
            foreach($request->doctors as $doctors_id) {
                $doctor = Doctor::find($doctors_id);
                $user->accessibleDoctors()->attach($doctor);
            }            
        }

        Session::flash('success', 'User created successfully!');
        return redirect()->route('dashboard.users');
    }

    public function updateUser(Request $request, $id)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile,'.$id,
            'role'        => 'required',
            'hospitals'   => 'sometimes',
            'doctors'   => 'sometimes',
            'blooddonors'   => 'sometimes',
            'coachings'   => 'sometimes',
            'password'    => 'nullable|string|min:8|max:191',
        ));

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        if(isset($request->hospitals)){
            $user->accessibleHospitals()->detach();
            foreach($request->hospitals as $hospital_id) {
                $hospital = Hospital::find($hospital_id);
                $user->accessibleHospitals()->attach($hospital);
            }            
        }
        if(isset($request->doctors)){
            $user->accessibleDoctors()->detach();
            foreach($request->doctors as $doctors_id) {
                $doctor = Doctor::find($doctors_id);
                $user->accessibleDoctors()->attach($doctor);
            }            
        }
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        Session::flash('success', 'User updated successfully!');
        return redirect()->route('dashboard.users');
    }

    public function sendExpiredSMS(Request $request)
    {
        $this->validate($request,array(
            'randtotalhidden'         => 'required',
            'randtotalvisible'        => 'required|string|max:191',
            'sms'                     => 'required|string',
        ));
        if($request->randtotalhidden == $request->randtotalvisible) {
            $paidusersids = DB::table('payments')->select('user_id')->groupBy('user_id')->get()->pluck('user_id')->toArray();
            $users = User::select('name', 'mobile')
                         ->where('package_expiry_date', '<', Carbon::now())
                         ->whereIn('id', $paidusersids)
                         ->orderBy('package_expiry_date', 'asc')
                         ->get();
            
            foreach ($users as $user) {
                $mobile_number = 0;
                if(strlen($user->mobile) == 11) {
                    $mobile_number = $user->mobile;
                } elseif(strlen($user->mobile) > 11) {
                    if (strpos($user->mobile, '+') !== false) {
                        $mobile_number = substr($user->mobile, -11);
                    }
                }
                $numbersarray[] = $mobile_number;
            }
            $numbersstr = implode (",", $numbersarray);
            // dd($numbersstr);
            
            $url = config('sms.url');
            $number = $mobile_number;
            $text = $request->sms; // . ' Customs and VAT Co-operative Society (CVCS).';
            $data= array(
                'username'=>config('sms.username'),
                'password'=>config('sms.password'),
                'number'=>"$numbersstr",
                'message'=>"$text",
            );
            // initialize send status
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is important
            $smsresult = curl_exec($ch);

            // $sendstatus = $result = substr($smsresult, 0, 3);
            $p = explode("|",$smsresult);
            $sendstatus = $p[0];
            // send sms
            if($sendstatus == 1101) {
                Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
            } elseif($sendstatus == 1006) {
                Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
            } else {
                Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
            }
        } else {
            Session::flash('warning', 'অংক মেলেনি!');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getUser($id)
    {
        $user = User::find($id);
        
        // dd($totaldeposit);

        return view('dashboard.users.single')
                    ->withUser($user);
    }

    public function getUserWithOtherPage($id)
    {
        $user = User::find($id);

        // dd($totalexpense);

        return view('dashboard.users.singleother')
                    ->withUser($user);
    }

    public function updateBulkPackageDate(Request $request)
    {
        $this->validate($request,array(
            'numbers'                      => 'required',
            'packageexpirydatebulk'        => 'required',
        ));

        $numbersarray = explode(',', $request->numbers);

        $counter = 0;
        foreach($numbersarray as $number) {
            $user = User::where('mobile', 'LIKE', '%' . $number . '%')->first();
            if($user) {
                $user->package_expiry_date = date('Y-m-d', strtotime($request->packageexpirydatebulk)) . ' 23:59:59';
                $user->save();
                $counter++;
            }
        }

        Session::flash('success', $counter . ' Users updated successfully!');
        return redirect()->route('dashboard.users');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();

        Session::flash('success', 'User deleted successfully!');
        return redirect()->route('dashboard.users');
    }

    public function getPackages()
    {
        $packages = Package::all();
        
        return view('dashboard.packages.index')->withPackages($packages);
    }

    public function getMessages()
    {
        $messages = Message::orderBy('id', 'desc')->paginate(12);

        return view('dashboard.messages.index')->withMessages($messages);
    }

    public function updateMessage(Request $request, $id)
    {
        $message = Message::find($id);
        $message->status = 1;
        $message->save();

        Session::flash('success', 'Message updated successfully!');
        return redirect()->route('dashboard.messages');
    }

    public function deleteMessage($id)
    {
        $message = Message::find($id);
        $message->delete();

        Session::flash('success', 'Message deleted successfully!');
        return redirect()->route('dashboard.messages');
    }

    public function sendSingleNotification(Request $request, $id)
    {
        $user = User::find($id);
        if($user->onesignal_id !=null) {
            OneSignal::sendNotificationToUser(
                $request->message,
                $user->onesignal_id,
                $url = null, 
                $data = null,
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );  

            Session::flash('success', 'Notification sent successfully!');
            return redirect()->route('dashboard.users');
        } else {
            Session::flash('warning', 'OneSignal ID নেই');
            return redirect()->route('dashboard.users');
        }
    }

    public function sendSingleSMS(Request $request, $id)
    {
        $this->validate($request,array(
            'message'           => 'required',
        ));

        $user = User::find($id);

        // send sms
        $mobile_number = 0;
        if(strlen($user->mobile) == 11) {
            $mobile_number = $user->mobile;
        } elseif(strlen($user->mobile) > 11) {
            if (strpos($user->mobile, '+') !== false) {
                $mobile_number = substr($user->mobile, -11);
            }
        }

        // $url = config('sms.url');
        // $number = $mobile_number;
        // $text = $request->message;

        // NEW PANEL
        $url = config('sms.url2');
        $api_key = config('sms.api_key');
        $senderid = config('sms.senderid');
        $number = $mobile_number;
        $message = $request->message;

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
            Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        } elseif($jsonresponse->response_code == 1007) {
            Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        } else {
            Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        }
        // NEW PANEL
        
        // $data= array(
        //     'username'=>config('sms.username'),
        //     'password'=>config('sms.password'),
        //     'number'=>"$number",
        //     'message'=>"$text",
        // );

        // // initialize send status
        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is important
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
        // // dd($smsresult);
        // // send sms
        // if($sendstatus == 1101) {
        //     Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        // } elseif($sendstatus == 1006) {
        //     Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        // } else {
        //     Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        // }

        return redirect()->back();
    }

    public function getNotifications()
    {
        $notifications = Notification::orderBy('id', 'desc')->paginate(12);

        return view('dashboard.notifications.index')->withNotifications($notifications);
    }

    public function sendNotification(Request $request)
    {
        $this->validate($request,array(
            'type'         => 'required',
            'headings'     => 'required',
            'message'      => 'required',
        ));

        if($request->type == 'premium') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'equal', 'value' => 'Premium']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'free') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'not_exists']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'all') {
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = null, 
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'update') {
            // LIVE HOILE ETA DEOA HOBE
            // LIVE HOILE ETA DEOA HOBE
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = array("a" => 'update'),
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );

            // OneSignal::sendNotificationToUser(
            //     $request->message,
            //     ['716ffeb3-f6c2-4a4a-a253-710f339aa863'],
            //     $url = null, 
            //     $data = array("a" => 'update'),
            //     $buttons = null, 
            //     $schedule = null,
            //     $headings = $request->headings,
            // );
        }

        $notification = new Notification;
        $notification->type = $request->type;
        $notification->headings = $request->headings;
        $notification->message = $request->message;
        $notification->save();

        Session::flash('success', 'নোটিফিকেশন সফলভাবে পাঠানো হয়েছে!');
        return redirect()->route('dashboard.notifications');
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        $notification->delete();

        Session::flash('success', 'Notification deleted successfully!');
        return redirect()->route('dashboard.notifications');
    }

    public function sendAgainNotification(Request $request)
    {
        $this->validate($request,array(
            'type'         => 'required',
            'headings'     => 'required',
            'message'      => 'required',
        ));

        if($request->type == 'premium') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'equal', 'value' => 'Premium']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'free') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'not_exists']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'all') {
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = null, 
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'update') {
            // LIVE HOILE ETA DEOA HOBE
            // LIVE HOILE ETA DEOA HOBE
            // OneSignal::sendNotificationToAll(
            //     $request->message,
            //     $url = null, 
            //     $data = array("a" => 'update'),
            //     $buttons = null, 
            //     $schedule = null,
            //     $headings = $request->headings,
            // );
            
            OneSignal::sendNotificationToUser(
                $request->message,
                ['716ffeb3-f6c2-4a4a-a253-710f339aa863'],
                $url = null, 
                $data = array("a" => 'update'),
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        }

        $notification = new Notification;
        $notification->type = $request->type;
        $notification->headings = $request->headings;
        $notification->message = $request->message;
        $notification->save();

        Session::flash('success', 'নোটিফিকেশন সফলভাবে পাঠানো হয়েছে!');
        return redirect()->route('dashboard.notifications');
    }

    // test html question data
    // public function getExamSolvePDF($softtoken, $examid)
    // {
    //     if($softtoken == env('SOFT_TOKEN'))
    //     {
    //         $exam = Exam::findOrFail($examid);

    //         return view('index.pdf.test')->withExam($exam);

    //         $pdf = PDF::loadView('index.pdf.examsolvepdf', ['exam' => $exam]);
    //         $fileName = 'Single-Exam-Solve-Sheet-' . $exam->id . '.pdf';
    //         return $pdf->stream($fileName); // download/stream
    //     } else {
    //         return response()->json([
    //             'success' => false
    //         ]);
    //     }
    // }


    

    public function getComponents()
    {
        return view('components');
    }

    // clear configs, routes and serve
    public function clear()
    {
        Artisan::call('route:clear');
        // Artisan::call('optimize');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Session::flush();
        return 'Config and Route Cached. All Cache Cleared';
    }
}

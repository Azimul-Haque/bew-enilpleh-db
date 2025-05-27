<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('dashboard.index') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>ড্যাশবোর্ড</p>
    </a>
</li>

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.users') }}" class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }} {{ Request::is('dashboard/users/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>ব্যবহারকারীগণ</p>
    </a>
</li>
@endif

{{-- @if(Auth::user()->role == 'admin' || in_array('hospitals', Auth::user()->accessibleTables())) --}}
<li class="nav-item">
    <a href="{{ route('dashboard.hospitals') }}" class="nav-link {{ Request::is('dashboard/hospitals') ? 'active' : '' }} {{ Request::is('dashboard/hospitals/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-hospital"></i>
        <p>হাসপাতাল তালিকা</p>
    </a>
</li>
{{-- @endif --}}

{{-- @if(Auth::user()->role == 'admin' || in_array('doctors', Auth::user()->accessibleTables()) || in_array('hospitals', Auth::user()->accessibleTables())) --}}
<li class="nav-item">
    <a href="{{ route('dashboard.doctors') }}" class="nav-link {{ Request::is('dashboard/doctors') ? 'active' : '' }} {{ Request::is('dashboard/doctors/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-md"></i>
        <p>ডাক্তার তালিকা</p>
    </a>
</li>
{{-- @endif --}}

{{-- @if(Auth::user()->role == 'admin' || in_array('blooddonors', Auth::user()->accessibleTables())) --}}
<li class="nav-item">
    <a href="{{ route('dashboard.blooddonors') }}" class="nav-link {{ Request::is('dashboard/blooddonors') ? 'active' : '' }} {{ Request::is('dashboard/blooddonors/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tint"></i>
        <p>রক্তদাতা তালিকা</p>
    </a>
</li>
{{-- @endif --}}

@if(Auth::user()->role == 'admin' || Auth::user()->role == 'editor')
<li class="nav-item">
    <a href="{{ route('dashboard.ambulances') }}" class="nav-link {{ Request::is('dashboard/ambulances') ? 'active' : '' }} {{ Request::is('dashboard/ambulances/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-ambulance"></i>
        <p>অ্যাম্বুলেন্স</p>
    </a>
</li>
@endif

{{-- @if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.eshebas') }}" class="nav-link {{ Request::is('dashboard/eshebas') ? 'active' : '' }} {{ Request::is('dashboard/eshebas/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-external-link-alt"></i>
        <p>ই-সেবা তালিকা</p>
    </a>
</li>
@endif --}}

{{-- @if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.admins') }}" class="nav-link {{ Request::is('dashboard/admins') ? 'active' : '' }} {{ Request::is('dashboard/admins/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-tie"></i>
        <p>প্রশাসন কর্মকর্তাগণ</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.police') }}" class="nav-link {{ Request::is('dashboard/police') ? 'active' : '' }} {{ Request::is('dashboard/police/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-shield"></i>
        <p>পুলিশ কর্মকর্তাগণ</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.fireservices') }}" class="nav-link {{ Request::is('dashboard/fireservices') ? 'active' : '' }} {{ Request::is('dashboard/fireservices/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-fire-extinguisher"></i>
        <p>ফায়ার সার্ভিস</p>
    </a>
</li>
@endif 

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.rabs') }}" class="nav-link {{ Request::is('dashboard/rabs') ? 'active' : '' }} {{ Request::is('dashboard/rabs/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-shield"></i>
        <p>র‍্যাব</p>
    </a>
</li>
@endif --}}

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.lawyers') }}" class="nav-link {{ Request::is('dashboard/lawyers') ? 'active' : '' }} {{ Request::is('dashboard/lawyers/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gavel"></i>
        <p>আইনজীবী</p>
    </a>
</li>
@elseif(Auth::user()->role == 'editor')
<li class="nav-item">
    <a href="{{ route('dashboard.lawyers.districtwise', Auth::user()->district_id) }}" class="nav-link {{ Request::is('dashboard/lawyers') ? 'active' : '' }} {{ Request::is('dashboard/lawyers/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gavel"></i>
        <p>আইনজীবী</p>
    </a>
</li>
@endif


@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.coachings') }}" class="nav-link {{ Request::is('dashboard/coachings') ? 'active' : '' }} {{ Request::is('dashboard/coachings/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>শিক্ষা প্রতিষ্ঠান</p>
    </a>
</li>
@elseif(Auth::user()->role == 'editor' || Auth::user()->role == 'manager')
<li class="nav-item">
    <a href="{{ route('dashboard.coachings.singleforeditor') }}" class="nav-link {{ Request::is('dashboard/coachings') ? 'active' : '' }} {{ Request::is('dashboard/coachings/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>শিক্ষা প্রতিষ্ঠান</p>
    </a>
</li>
@endif



@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.buses') }}" class="nav-link {{ Request::is('dashboard/buses') ? 'active' : '' }} {{ Request::is('dashboard/buses/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bus"></i>
        <p>বাস</p>
    </a>
</li>
@elseif(Auth::user()->role == 'editor')
<li class="nav-item">
    <a href="{{ route('dashboard.buses.districtwise', Auth::user()->district_id) }}" class="nav-link {{ Request::is('dashboard/buses') ? 'active' : '' }} {{ Request::is('dashboard/buses/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bus"></i>
        <p>বাস</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.rentacars') }}" class="nav-link {{ Request::is('dashboard/rentacars') ? 'active' : '' }} {{ Request::is('dashboard/rentacars/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-car"></i>
        <p>রেন্ট-এ-কার</p>
    </a>
</li>
@elseif(Auth::user()->role == 'editor')
<li class="nav-item">
    <a href="{{ route('dashboard.rentacars.districtwise', Auth::user()->district_id) }}" class="nav-link {{ Request::is('dashboard/rentacars') ? 'active' : '' }} {{ Request::is('dashboard/rentacars/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-car"></i>
        <p>রেন্ট-এ-কার</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin' || Auth::user()->role == 'editor')
<li class="nav-item">
    <a href="{{ route('dashboard.newspapers') }}" class="nav-link {{ Request::is('dashboard/newspapers') ? 'active' : '' }} {{ Request::is('dashboard/newspapers/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-newspaper"></i>
        <p>দৈনিক পত্রিকা</p>
    </a>
</li>
@endif

{{-- @if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.journalists') }}" class="nav-link {{ Request::is('dashboard/journalists') ? 'active' : '' }} {{ Request::is('dashboard/journalists/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gavel"></i>
        <p>সাংবাদিকরৃন্দ</p>
    </a>
</li>
@endif --}}


@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.messages') }}" class="nav-link {{ Request::is('dashboard/messages') ? 'active' : '' }} {{ Request::is('dashboard/messages/*') ? 'active' : '' }}">
        <i class="nav-icon far fa-envelope"></i>
        <p>
            মেসেজসমূহ
            @if($unresolvedmessagecount > 0)
            ({{ $unresolvedmessagecount }})
            @endif
        </p>
    </a>
</li>
@endif

{{-- @if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.notifications') }}" class="nav-link {{ Request::is('dashboard/notifications') ? 'active' : '' }} {{ Request::is('dashboard/notification/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bell"></i>
        <p>নোটিফিকেশন</p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
<li class="nav-item">
    <a href="{{ route('dashboard.blogs') }}" class="nav-link {{ Request::is('dashboard/blogs') ? 'active' : '' }} {{ Request::is('dashboard/blogs/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-pen-nib"></i>
        <p>ব্লগ</p>
    </a>
</li>
@endif --}}

{{-- <li class="nav-item">
    <a href="{{ route('dashboard.components') }}" class="nav-link {{ Request::is('dashboard/components') ? 'active' : '' }}">
        <i class="nav-icon fas fa-laptop-code"></i>
        <p>Components</p>
    </a>
</li> --}}
<li class="nav-item">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>লগআউট</p>
    </a>
</li>

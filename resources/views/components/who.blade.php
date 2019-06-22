@if(Auth::guard('web')->check())
    <p>You Are logged In as a <strong>USER</strong></p>
@else
    <p>You Are Not logged In as a <strong>USER</strong></p>
@endif

@if(Auth::guard('admin')->check())
    <p>You Are logged In as a <strong>ADMIN</strong></p>
@else
    <p>You Are Not logged In as a <strong>ADMIN</strong></p>
@endif

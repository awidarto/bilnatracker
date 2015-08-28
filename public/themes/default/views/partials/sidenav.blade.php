@section('sidenav')
	<?php
		$role = Auth::user()->role;
		$permissions = Auth::user()->permissions;

        /*
        <dd><a href="{{ URL::to('document') }}"><i class="foundfa fa-page sidemenu"></i> <br/>Documents</a></dd>
        <dd><a href="{{ URL::to('opportunity') }}"><i class="foundfa fa-heart sidemenu"></i> <br/>Opportunity</a></dd>
        <dd><a href="{{ URL::to('tender') }}"><i class="foundfa fa-idea sidemenu"></i> <br/>Tender</a></dd>
        <dd><a href="{{ URL::to('qc') }}"><i class="foundfa fa-checkmark sidemenu"></i> <br/>Quality</a></dd>

        @if(Auth::permit('document'))
            <dd><a href="{{ URL::to('warehouse') }}"><i class="foundfa fa-cart sidemenu"></i> <br/>Warehouse</a></dd>
        @endif

        <dd><a href="{{ URL::to('finance') }}"><i class="foundfa fa-graph sidemenu"></i> <br/>Finance</a></dd>
        <dd><a href="{{ URL::to('hr') }}"><i class="foundfa fa-people sidemenu"></i> <br/>HRD</a></dd>
        <dd><a href="{{ URL::to('activity/download') }}"><i class="foundfa fa-down-arrow sidemenu"></i> <br/>Download</a></dd>
        <dd><a href="{{ URL::to('activity/upload') }}"><i class="foundfa fa-up-arrow sidemenu"></i> <br/>Upload</a></dd>
        <dd><a href="{{ URL::to('user/people') }}"><i class="foundfa fa-address-book sidemenu"></i> <br/>People</a></dd>

        */

	?>
    <div class="one columns mobile">
      <dl class="vertical tabs">
        <dd><a href="{{ URL::base() }}"><i class="foundfa fa-home sidemenu"></i> <br/>Home</a></dd>
        <dd><a href="{{ URL::to('requests/incoming') }}"><i class="foundfa fa-down-arrow sidemenu"></i> <br/>Incoming Requests</a></dd>
        <dd><a href="{{ URL::to('requests/outgoing') }}"><i class="foundfa fa-up-arrow sidemenu"></i> <br/>Outgoing Requests</a></dd>
        <dd><a href="{{ URL::to('message') }}"><i class="foundfa fa-mail sidemenu"></i> <br/>Messages</a></dd>
        <dd><a href="{{ URL::to('opportunity') }}"><i class="foundfa fa-idea sidemenu"></i> <br/>Opportunity</a></dd>
        <dd><a href="{{ URL::to('tender') }}"><i class="foundfa fa-idea sidemenu"></i> <br/>Tender</a></dd>
        <dd><a href="{{ URL::to('project') }}"><i class="foundfa fa-idea sidemenu"></i> <br/>Projects</a></dd>
        <dd><a href="{{ URL::to('employee') }}"><i class="foundfa fa-people sidemenu"></i> <br/>Human Resources</a></dd>
        <dd><a href="{{ URL::to('user/profile') }}"><i class="foundfa fa-settings sidemenu"></i> <br/>Profile</a></dd>
        <dd><a href="{{ URL::to('search') }}"><i class="foundfa fa-search sidemenu"></i> <br/>Search</a></dd>
        <dd><a href="{{ URL::to('content/view/help') }}"><i class="foundfa fa-smiley sidemenu"></i> <br/>Help</a></dd>
      </dl>
    </div>

@endsection
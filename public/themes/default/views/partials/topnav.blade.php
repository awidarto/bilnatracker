
<ul class="nav navbar-nav">

    @if(Auth::check())

        <li class="dropdown dropdown-light {{ sa('dashboard') }}">
            <a href="{{ URL::to('dashboard') }}" class="dropdown-toggle" >
                <i class="fa fa-bar-chart-o"></i> Dashboard</span>
            </a>
        </li>
        <li class="dropdown dropdown-light">
            <a href="" data-toggle="dropdown" class="dropdown-toggle">Assets <span class="caret"></span></a>
            <ul class="dropdown-menu pull-left">
                <li class="dropdown dropdown-light">
                    <a class="{{ sa('asset') }}" href="{{ URL::to('asset') }}"  ><i class="fa fa-th-list"></i> Assets List</a>
                </li>
                <li class="dropdown dropdown-light">
                    <a class="{{ sa('rack') }}" href="{{ URL::to('rack') }}"  ><i class="fa fa-th-list"></i> Racks</a>
                </li>
                <li class="dropdown dropdown-light">
                    <a class="{{ sa('assetlocation')}}" href="{{ URL::to('assetlocation') }}" ><i class="fa fa-sitemap"></i> Locations</a>
                </li>
                <li class="dropdown dropdown-light">
                    <a class="{{ sa('assettype')}}" href="{{ URL::to('assettype') }}" ><i class="fa fa-sitemap"></i> Device Type</a>
                </li>

            </ul>
        </li>
        <li class="dropdown dropdown-light">
            <a href="" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-bar-chart-o"></i> Reports <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown dropdown-light">
                    <a href="{{ URL::to('report/siteaccess') }}" class="{{ sa('report/siteaccess') }}" ><i class="fa fa-globe"></i> Site Access</a>
                </li>
                <li class="dropdown dropdown-light">
                    <a href="{{ URL::to('report/activity') }}" class="{{ sa('report/activity') }}" ><i class="fa fa-refresh"></i> Activity</a>
                </li>
            </ul>
        </li>
        <li class="dropdown dropdown-light">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle" >
                <i class="fa fa-cogs"></i> System <span class="caret"></span>
              </a>
            <ul class="dropdown-menu">
                <li><a href="{{ URL::to('user') }}" class="{{ sa('user') }}" ><i class="fa fa-group"></i> Admins</a></li>
                <li><a href="{{ URL::to('usergroup') }}" class="{{ sa('usergroup') }}" ><i class="fa fa-group"></i> Group</a></li>
                <li><a href="{{ URL::to('option') }}" class="{{ sa('option') }}" ><i class="fa fa-wrench"></i> Options</a></li>
            </ul>
        </li>

    @endif
</ul>

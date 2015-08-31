      <div class="sidebar">
        <div class="logopanel" style="padding:6px;padding-left:15px;padding-top:10px;">
          <h1>
            <a href="{{ URL::to('/') }}" style="background: url({{ URL::to('/') }}/images/logo_bilna_2015_2.jpg) no-repeat;background-size: 80px auto;">
            </a>
          </h1>
        </div>
        <div class="sidebar-inner">
          <ul class="nav nav-sidebar">
            <li><a href="{{ URL::to('/') }}"><i class="icon-home"></i><span>Dashboard</span></a></li>

            @if(Ks::can('view','incoming'))
            <li><a href="{{ URL::to('incoming') }}"><i class="fa fa-arrow-circle-down"></i> Incoming</a></li>
            @endif

            @if(Ks::can('view','dispatcher'))
            <li class="nav-parent">
              <a href="{{ URL::to('employee') }}"><i class="fa fa-arrows-alt"></i><span>Dispatcher</span> <span class="fa arrow"></span></a>
              <ul class="children collapse">
                <li><a href="{{ URL::to('zoning') }}"><i class="fa fa-bullseye"></i> Device Zone Assignment</a></li>
                <li><a href="{{ URL::to('courierassign') }}"><i class="fa fa-user-plus"></i> Courier Assignment</a></li>
              </ul>
            </li>
            @endif
            @if(Ks::can('view','tracker'))
            <li class="nav-parent">
              <a href="{{ URL::to('employee') }}"><i class="icon-users"></i><span>Tracker</span> <span class="fa arrow"></span></a>
              <ul class="children collapse">
                <li><a href="{{ URL::to('dispatched') }}"><i class="fa fa-paper-plane-o"></i> In Progress</a></li>
                <li><a href="{{ URL::to('delivered') }}"><i class="fa fa-check-circle-o"></i> Delivery Status</a></li>
                <li><a href="{{ URL::to('canceled') }}"><i class="fa fa-times"></i> Canceled Order</a></li>
                <li><a href="{{ URL::to('orderarchive') }}"><i class="fa fa-history"></i> Order Archive</a></li>
              @if(Ks::can('view','log'))
                <li><a href="{{ URL::to('deliverylog') }}"><i class="fa fa-list-ul"></i> Delivery Log</a></li>
              @endif
              </ul>
            </li>
            @endif
            @if(Ks::can('view','assets'))
            <li class="nav-parent">
              <a href="#"><i class="fa fa-cog"></i><span>Assets</span> <span class="fa arrow"></span></a>
              <ul class="children collapse">
                <li><a href="{{ URL::to('device') }}"><i class="fa fa-mobile-phone"></i> Devices</a></li>
                <li><a href="{{ URL::to('parsedevice') }}"><i class="fa fa-ge"></i> Parse Devices</a></li>
              </ul>
            </li>
            @endif
            @if(Ks::can('view','reports'))
            <li class="nav-parent {{ hsa( array('gl','coa') ) }} ">
              <a href=""><i class="fa fa-table"></i><span>Reports</span><span class="fa arrow"></span></a>
              <ul class="children collapse">
                {{--
                <li class="{{ sa('gl') }}" ><a href="{{ URL::to('gl') }}"> General Ledger</a></li>
                <li class="{{ sa('coa') }}" ><a href="{{ URL::to('coa') }}"> Chart Of Accounts</a></li>
                --}}
              </ul>
            </li>
            @endif
            @if(Ks::can('view','system'))

            <li class="nav-parent">
              <a href=""><i class="fa fa-cogs"></i><span>System </span><span class="fa arrow"></span></a>

                <ul class="children collapse">

                    <li class="{{ sa('user') }}" >
                      <a href="{{ URL::to('user') }}" class="{{ sa('user') }}" ><i class="fa fa-group"></i> Users</a>
                    </li>
                    <li class="{{ sa('usergroup') }}">
                      <a href="{{ URL::to('usergroup') }}" class="{{ sa('usergroup') }}" ><i class="fa fa-group"></i> Roles</a>
                    </li>
                    <li class="{{ sa('holiday') }}"><a href="{{ URL::to('holiday') }}"><i class="fa fa-calendar"></i> Holidays</a></li>
                    <li class="{{ sa('option') }}">
                      <a href="{{ URL::to('option') }}" class="{{ sa('option') }}" ><i class="fa fa-wrench"></i> Options</a>
                    </li>
                </ul>
            </li>

            @endif
          </ul>
          <!-- SIDEBAR WIDGET FOLDERS -->
          <div class="sidebar-widgets">
            <p class="menu-title widget-title">Folders <span class="pull-right"><a href="#" class="new-folder"> <i class="icon-plus"></i></a></span></p>
            <ul class="folders">
              <li>
                <a href="#"><i class="icon-doc c-primary"></i>My documents</a>
              </li>
              <li>
                <a href="#"><i class="icon-picture"></i>My images</a>
              </li>
              <li><a href="#"><i class="icon-lock"></i>Secure data</a>
              </li>
              <li class="add-folder">
                <input type="text" placeholder="Folder's name..." class="form-control input-sm">
              </li>
            </ul>
          </div>
          <div class="sidebar-footer clearfix">
            <a class="pull-left footer-settings" href="#" data-rel="tooltip" data-placement="top" data-original-title="Settings">
            <i class="icon-settings"></i></a>
            <a class="pull-left toggle_fullscreen" href="#" data-rel="tooltip" data-placement="top" data-original-title="Fullscreen">
            <i class="icon-size-fullscreen"></i></a>
            <a class="pull-left" href="#" data-rel="tooltip" data-placement="top" data-original-title="Lockscreen">
            <i class="icon-lock"></i></a>
            <a class="pull-left btn-effect" href="#" data-modal="modal-1" data-rel="tooltip" data-placement="top" data-original-title="Logout">
            <i class="icon-power"></i></a>
          </div>
        </div>
      </div>

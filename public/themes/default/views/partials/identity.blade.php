            <ul class="nav navbar-nav navbar-right hidden-xs">
                <li class="hidden-xs">
                    <a href="{{ URL::to('profile')}}">
                        {{ Auth::user()->fullname }}
                    </a>
                </li>
                <li class="hidden-xs">
                    <a href="{{ URL::to('profile')}}">
                        <img src="{{ Auth::user()->avatar }}" class="avatar pull-left img-circle" alt="user" title="user">
                    </a>
                </li>
                <li>
                    <a href="{{ URL::to('logout')}}" style="font-size:20px;"><i class="fa fa-sign-out"></i></a>
                </li>
            </ul>



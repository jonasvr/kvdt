{{--<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">--}}
<nav class="navbar navbar-default" role="navigation" style="margin-bottom: 0">
<div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">Kot van de toekomst</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        @if (Auth::guest())
            <li><a href="{{ url('/login') }}"><i class="fa fa-google fa-fw"></i>Login</a></li>
        @else
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                {{ Auth::user()->name }} <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ url('/profile') }}"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li class="divider"></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
        @endif
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="/home"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="{{ URL::route('calendars') }}"><i class="fa fa-calendar fa-fw"></i> Calendars</a>
                </li>
                <li>
                    <a href="{{ URL::route('events') }}"><i class="fa fa-calendar-o fa-fw"></i> Events</a>
                </li>
                <li>
                    <a href="{{ URL::route('alarms') }}"><i class="fa fa-bell fa-fw"></i> Alarms</a>
                </li>
                <li class="divider"></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-exclamation-triangle fa-fw"></i> Emergency Settings <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="{{ URL::route('numbers') }}"><i class="fa fa-mobile fa-fw"></i> Numbers</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('mails') }}"><i class="fa fa-at fa-fw"></i> Mails</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('mess') }}"><i class="fa fa-envelope fa-fw"></i> Messages</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>

            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>

<header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <!-- <a href="{{ url('/') }}" class="navbar-brand">{{ config('app.name', 'Laravel') }}</a> -->
          <a href="https://djss.com/"><img class="header-logo" src="{{ URL::asset('public/image/logo.png') }}"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="    padding: 39px;">
          <ul class="nav navbar-nav">
            @if(!Auth::user())
            <!-- <li class="active"><a href="{{ route('home') }}">Home</a></li> -->
            <li><a href="https://www.gamerzbyte.com">Home</a></li>
            <li class="active"><a href="{{ route('events') }}">Events</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Community<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="https://community.gamerzbyte.com/blogs">Blogs</a></li>
                <li><a href="https://community.gamerzbyte.com/">Forums</a></li>
                <li class="divider"></li>
                <li><a href="https://chat.gamerzbyte.com/" target="_blank" >Discord - Chat</a></li>
              </ul>
            </li>
            @endif


            

            @if(Auth::user())

              <!-- <li class="active"><a href="{{ route('home') }}">Home</a></li> -->
            <li><a href="https://www.gamerzbyte.com">Home</a></li>
            <li><a href="{{ route('events') }}">Our Events</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Community<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="https://community.gamerzbyte.com/blogs">Blogs</a></li>
                <li><a href="https://community.gamerzbyte.com/">Forums</a></li>
                <li class="divider"></li>
                <li><a href="https://chat.gamerzbyte.com/" target="_blank" >Discord - Chat</a></li>
              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">@ Me <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
              <li><a href="{{ route('user.myprofile') }}">My Profile</a></li>
              <li><a href="{{ route('transactions') }}">My Transactions</a></li>
              <li><a href="{{ route('user-leaderboard') }}">Leaderboard</a></li>
                @if(!Auth::user()->hasRole('admin'))
                <li class="divider"></li>
                <li><a href="{{ route('myaccount') }}">My account</a></li>
                @endif
              </ul>
            </li>
              @if(!Auth::user()->hasRole('admin'))
              <li><a href="https://community.gamerzbyte.com/" target="_blank">Community</a></li>
              @endif
              @if(Auth::user()->hasRole('admin'))
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Masters <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('event-type') }}">Event Type</a></li>
                  <li><a href="{{ route('event-format') }}">Event Format</a></li>
                  <li><a href="{{ route('winner-position') }}">Winner Position</a></li>
                  <li><a href="{{ route('game') }}">Games</a></li>
                  <li><a href="{{ route('users') }}">Users</a></li>
                  <li><a href="{{ route('leaderboard-point') }}">Leaderboard Points</a></li>
                  <li><a href="{{ route('leaderboard-lavel') }}">Leaderboard Lavels</a></li>
                  <li><a href="{{ route('bonus-rule') }}">Bonus Rules</a></li>
                  <li><a href="{{ route('banner') }}">Banners</a></li>
                  <li><a href="{{ route('promo-code') }}">Promocodes</a></li>
                </ul>
              </li>
              @endif
              @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator'))
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Manage<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('manage-events') }}">Manage Events</a></li>
                  <li><a href="{{ route('event.create') }}">Add Event</a></li>
                  <li><a href="{{ route('event-winner') }}">Add Winners</a></li>
                  <li><a href="{{ route('winner-request') }}">Winner Request</a></li>
                </ul>
              </li>
              @endif

              @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('moderator') )
              <li><a href="{{ route('event-rule') }}">Event Rules</a></li>
              @endif
              
               @if(Auth::user()->hasRole('admin'))
               <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">More<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('messages') }}">Bulk SMS</a></li>
                  <li><a href="{{ route('id-verification') }}">ID Verification</a></li>
                  <li><a href="{{ route('withdrawal-requests') }}">Withdrawal</a></li>
                  <li><a href="{{ route('send-notification') }}">Notification</a></li>
                </ul>
              </li>
              @endif
            @endif
            
            
           
          </ul>
          <!-- <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
            </div>
          </form> -->
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            @if(Auth::user())
            
            <!-- User Account Menu -->
            <li class="">
              <a href="{{ url('/logout') }}" class="">Logout</a>
            </li>
            <!-- <li class="dropdown user user-menu"> -->
              <!-- Menu Toggle Button -->
              <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ url('/') }}/public/app-assets/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ ucfirst(Auth::user()->firstname) }}</span>
              </a> -->
<!--               <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="{{ url('/') }}/public/app-assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                  <p>
                    {{ ucfirst(Auth::user()->firstname) }} {{ Auth::user()->lastname }}
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul> -->
            </li>
            @else
            <li><a href="{{ url('/login') }}">Login</a></li>
            <li class="">
              <a href="{{ url('/register') }}" class="">SignUp</a>
            </li>
            @endif
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
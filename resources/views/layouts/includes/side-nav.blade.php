<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">
				
        <div class="sidebar-header">
            <div class="sidebar-title">
                Navigation
            </div>
            <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>
    
        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                
                    <ul class="nav nav-main">
                        <li class="{{ Request::is('dashboard') ? 'nav-active' : '' }}">
                            <a class="nav-link" href="{{ url('/dashboard') }}">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                <span>Dashboard</span>
                            </a>                        
                        </li>
                        <li class="{{ Request::is('client') ? 'nav-active' : '' }}">
                            <a class="nav-link" href="{{ route('clients.index') }}">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                <span>Client</span>
                            </a>                        
                        </li>
                        <!-- <li class="nav-parent">
                            <a class="nav-link" href="#">
                                <i class="fas fa-list-alt" aria-hidden="true"></i>
                                <span>Forms</span>
                            </a>
                            <ul class="nav nav-children">
                                <li>
                                    <a class="nav-link" href="forms-basic.html">
                                        Basic
                                    </a>
                                </li>
                            </ul>
                        </li> -->
    
                    </ul>
                </nav>
            </div>
    
            <script>
                // Maintain Scroll Position
                if (typeof localStorage !== 'undefined') {
                    if (localStorage.getItem('sidebar-left-position') !== null) {
                        var initialPosition = localStorage.getItem('sidebar-left-position'),
                            sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                        
                        sidebarLeft.scrollTop = initialPosition;
                    }
                }
            </script>
            
    
        </div>
    
    </aside>
    <!-- end: sidebar -->

<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a class="navbar-brand">
            <!--Logo start-->
            <div class="logo-main">
                <div class="logo-normal">
                    <img src="{{ asset('main/img/logo-hr.png') }}" width="190" height="50">
                </div>
                <div class="logo-mini">
                    <img src="{{ asset('main/img/logo.png') }}" width="50" height="50">
                </div>
            </div>
            <!--logo End-->
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <!-- Sidebar Menu Start -->
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'dashboard') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.dashboard')}}">
                        <i class="fa fa-dashboard"></i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#sidebar-special" role="button" aria-expanded="false" aria-controls="sidebar-special">
                        <i class="fa fa-piggy-bank"></i>
                        <span class="item-name">Wallets</span>
                        <i class="right-icon">
                            <svg class="icon-18" xmlns="http://www.w3.org/2000/svg" width="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </i>
                    </a>
                    <ul class="sub-nav collapse" id="sidebar-special" data-bs-parent="#sidebar-menu">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'wallets') ? 'active' : ''; ?>" href="{{route('admin.wallets')}}">
                              <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                        <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                </i>
                              <i class="sidenav-mini-icon"> W </i>
                              <span class="item-name">Wallets</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'virtual accounts') ? 'active' : ''; ?>" href="{{route('admin.virtualAccounts')}}">
                                <i class="icon">
                                    <svg class="icon-10" xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor">
                                        <g>
                                        <circle cx="12" cy="12" r="8" fill="currentColor"></circle>
                                        </g>
                                    </svg>
                                </i>
                                <i class="sidenav-mini-icon"> V </i>
                                <span class="item-name">Virtual Accounts</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'users') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.users')}}">
                        <i class="fa fa-users"></i>
                        <span class="item-name">Users</span>
                    </a>
                </li>
                <li class="nav-item" id="adminPart">
                    <a class="nav-link <?php echo ($page == 'admins') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.admins')}}">
                        <i class="fa fa-users">
                        </i>
                        <span class="item-name">Admins</span>
                    </a>
                </li>
                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'services') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.services')}}">
                        <i class="fa fa-sitemap"></i>
                        <span class="item-name">Services</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'providers') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.providers')}}">
                        <i class="fa fa-sitemap"></i>
                        <span class="item-name">Providers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'billers') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.billers')}}">
                        <i class="fa fa-sitemap"></i>
                        <span class="item-name">Billers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'packages') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.packages')}}">
                        <i class="fa fa-briefcase"></i>
                        <span class="item-name">Packages</span>
                    </a>
                </li>
                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'payments') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.historyPayments')}}">
                        <i class="fa fa-credit-card"></i>
                        <span class="item-name">Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'orders') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.historyOrders')}}">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="item-name">Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'transactions') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.historyTransactions')}}">
                        <i class="fa fa-table"></i>
                        <span class="item-name">Transactions</span>
                    </a>
                </li>
                <li>
                    <hr class="hr-horizontal">
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'announcements') ? 'active' : ''; ?>" aria-current="page" href="{{route('admin.announcements')}}">
                        <i class="fa fa-bell"></i>
                        <span class="item-name">Announcements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'referrals') ? 'active' : ''; ?>" href="{{ route('admin.referrals')}}">
                        <i class="fa fa-users"></i>
                        <span class="item-name">Referrals</span>
                    </a>
                </li> 
                <li class="nav-item ">
                    <a class="nav-link <?php echo ($page == 'categories') ? 'active' : ''; ?>" href="{{route('admin.categories')}}">
                        <i class="fa fa-gears"></i>
                        <span class="item-name">Categories</span>
                    </a>
                </li>
                <li class="nav-item mb-5 pb-3">
                    <a class="nav-link <?php echo ($page == 'switches') ? 'active' : ''; ?>" href="{{route('admin.switches')}}">
                        <i class="fa fa-gears"></i>
                        <span class="item-name">Switches</span>
                    </a>
                </li>
            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer"></div>
</aside>
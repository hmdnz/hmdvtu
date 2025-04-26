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
                    <a class="nav-link static-item disabled d-flex align-items-center justify-content-center" href="#" tabindex="-1">
                        <span class="default-icon text-center">
                            <center>
                                <i class="fa fa-user-circle primary-text" style="font-size: 50px"></i>
                                <h6>@<span>{{auth()->user()->username}}</span></h6>
                                <p class="mb-1">
                                    @if(is_null(auth()->user()->wallet))
                                        &#8358;  {{'0.00'}}
                                    @else
                                        &#8358; {{ number_format(auth()->user()->wallet->mainBalance, 2, '.', ',')}}
                                    @endif
                                </p>
                            </center>
                        </span>
                        <span class="mini-icon"><i class="fa fa-user-circle" ></i></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($page == 'index') ? 'active' : '' }}" aria-current="page" href="{{route('user.dashboard')}}">
                        <i class="icon">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-20">
                                <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($page == 'wallet') ? 'active' : '' }}" aria-current="page" href="{{route('user.wallet')}}">
                        <i class="fa fa-piggy-bank"></i>
                        <span class="item-name">Wallet</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'wallet-topup') ? 'active' : ''; ?>" aria-current="page" href="{{route('user.walletTopUp')}}">
                        <i class="fa fa-credit-card"></i>
                        <span class="item-name">Fund Wallet</span>
                    </a>
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Services</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'buy-airtime') ? 'active' : ''; ?>" aria-current="page" href="{{route('user.buyAirtime')}}">
                        <i class="fa fa-phone-square-alt"></i>
                        <span class="item-name">Buy Airtime</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'buy-data') ? 'active' : ''; ?>" aria-current="page" href="{{route('user.buyData')}}">
                        <i class="fa fa-wifi"></i>
                        <span class="item-name">Buy Data</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'buy-sms') ? 'active' : ''; ?>" aria-current="page" href="{{route('user.buySMS')}}">
                        <i class="fa fa-comments"></i>
                        <span class="item-name">Bulk SMS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'buy-cable') ? 'active' : ''; ?>" href="{{route('user.buyCable')}}" aria-current="page">
                        <i class="fa fa-tv"></i>
                        <span class="item-name">Buy Cable</span>
                    </a>
                </li>
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">History</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'payments') ? 'active' : ''; ?>" href="{{route('user.payments')}}">
                        <i class="fa fa-credit-card"></i>
                        <span class="item-name">Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($page == 'orders') ? 'active' : ''; ?>" href="{{route('user.orders')}}">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="item-name">Orders</span>
                    </a>
                </li>
                <li class="nav-item mb-5">
                    <a class="nav-link <?php echo ($page == 'transactions') ? 'active' : ''; ?>" href="{{route('user.transactions')}}">
                        <i class="fa fa-table"></i>
                        <span class="item-name">Transactions</span>
                    </a>
                </li>
                <li class="nav-item mb-5 pb-5">
                    <a class="nav-link <?php echo ($page == 'referrals') ? 'active' : ''; ?>" href="{{route('user.referrals')}}">
                        <i class="fa fa-users"></i>
                        <span class="item-name">Referrals</span>
                    </a>
                </li>
            </ul>
            <!-- Sidebar Menu End -->
        </div>
    </div>
    <div class="sidebar-footer mt-4 pt-4"></div>
</aside>
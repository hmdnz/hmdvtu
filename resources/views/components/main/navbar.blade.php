<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{route('index')}}" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1 class="sitename">ZaumaData</h1>
        </a>
    
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{route('index')}}" class="active">Home</a></li>
                <li><a href="/#about">About</a></li>
                <li><a href="/#services">Services</a></li>
                <li><a href="/#pricing">Pricing</a></li>
                <li><a href="/#contact">Contact</a></li>
              </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="{{route('user.login')}}">Sign In</a>
    </div>
</header>
<header id="header" class="header-yellow" style="background: #0c1618;width: auto;">
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search" id="navbar" style="width: auto;">
            <div class="container-fluid"><a id="home-link" href="{{ route('admin.admin_view_users') }}"><img id="logo" src="{{ asset('assets/img/yellow_logo.png')}} "></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-1" style="margin-top: 1px;width: auto;">
                    <ul class="navbar-nav"></ul>
                    <form class="d-flex me-auto navbar-form" target="_self" style="text-align: center;border-width: 10px;border-color: #0c1618;transform: translate(324px);">
                        <div class="d-flex align-items-center" id="search-bar"><a id="magnifying-glass-link" href="#"><i class="fa fa-search fa fa-search" id="search-icon" style="color: #efc11a;border-color: #efc11a;"></i></a><input class="form-control search-field" type="search" id="search-input" name="search"></div>
                    </form><a id="trending-link" href="{{route('general_feed')}}"><i class="fa fa-arrow-up bi bi-question nav-link" id="i3" style="font-size: 25px;"></i></a><a id="faq-link-admin" href="{{ route('faq') }}"><i class="fa fa-question bi bi-question nav-link" id="i3"></i></a><a id="about-link-admin" class="nav-link" href="{{ route('about-us') }}"><i class="fa fa-info bi bi-question" id="i4"></i></a>
                    @if(Auth::guard('admin')->check())
                    <a href="{{ route('admin.logout') }}"><button class="btn btn-primary register" id="home-logout" type="button" style="min-width: auto;width: auto;padding: 10px;">Logout</button></a>
                    @endif
                </div>
            </div>
        </nav>
    </header>
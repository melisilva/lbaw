<header id="header" class="header-yellow" style="width: auto;">
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search" id="navbar" style="width: auto;">
            <div class="container-fluid"><a id="home-link"href="{{ route('login') }}"><img id="logo" src="{{ asset('assets/img/logo.png')}} "></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-1" style="margin-top: 1px;width: auto;">
                    <ul class="navbar-nav"></ul>
                    <form class="d-flex me-auto navbar-form" target="_self" style="text-align: center;border-width: 10px;border-color: #0c1618;transform: translate(324px);">
                       <div class="d-flex align-items-center" id="search-bar" style="align-self: center;"><a id="magnifying-glass-link" href="#"><i class="fa fa-search fa fa-search" id="search-icon"></i></a><input class="form-control search-field" type="search" id="search-input" name="search"></div>
                    </form><span class="navbar-text"> </span><a id="trending-link" href="{{route('general_feed')}}"><i class="fa fa-arrow-up bi bi-question nav-link" id="i1" style="font-size: 25px;"></i></a><a id="faq-link-1" href="{{ route('faq') }}"><i class="fa fa-question bi bi-question nav-link" id="i2"></i></a><a id="about-link" class="nav-link" href="{{ route('about-us') }}"><i class="fa fa-info bi bi-question" id="i-3"></i></a><a href="{{ route('register') }}"><button class="btn btn-primary register" id="home-register" type="button" style="min-width: auto;width: auto;padding: 10px;">Registar</button></a>
                </div>
            </div>
        </nav>
</header>
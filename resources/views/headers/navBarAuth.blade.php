<header id="header" class="header-yellow" style="width: auto;">
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search" id="navbar" style="width: auto;">
            <div class="container-fluid"><a id="feed-link" href="{{ route('for_you_feed') }}"><img id="logo" src="{{ asset('assets/img/logo.png')}} "></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse align-items-center" id="navcol-1" style="margin-top: 1px;width: auto;">
                    <ul class="navbar-nav"></ul>
                    <form class="d-flex me-auto navbar-form" target="_self" style="text-align: center;border-width: 10px;border-color: #0c1618;transform: translate(324px);margin: 0px;margin-right: 0px;">
                       <div class="d-flex align-items-center" id="search-bar" style="align-self: center;"><a id="magnifying-glass-link" href="#"><i class="fa fa-search fa fa-search" id="search-icon"></i></a><input class="form-control search-field" type="search" id="search-input" name="search">
                            <div class="dropdown" style="z-index: 1000;margin-right: 0px;margin-left: 5px;"><a class="dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" href="#"></a>
                                <div class="dropdown-menu" style="width: 460px;border-radius: 3px; left: -450px; top: 35px;">
                                    <form>
                                        <p style="padding: 10px;">Autores da Publicação<input class="form-control" type="text" id="user-filter" style="margin: 0px;margin-left: 0px;" placeholder="A pesquisa só será feita neste utilizador!"></p>
                                        <p style="padding: 10px;">Termos Incluídos<input class="form-control" type="text" id="term-filter" style="margin: 0px;margin-left: 0px;" placeholder="Valores fornecidos aqui estarão incluídos nos resultados!"></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="dropdown dropstart" id="user-btn" href="" aria-expanded="false" style="margin-right: 15px;"><a class="dropdown-toggle a" aria-expanded="false" data-bs-toggle="dropdown" id="dropdown-link" href="#user-tab"><img id="user-pic" class="rounded-circle" src="{{ asset(App\Models\User::find(Auth::id())->fotoperfil)}} " style="margin-right: 0px;" href="#user-tab"></a>
                        <div class="dropdown-menu dropleft-menu-start" id="user-tab">
                            <h6 class="dropdown-header" id="dh1">Notificações</h6>
                            <div class="toast-container" id="notifications">
                                <div class="toast fade hide" role="alert" id="toast-1">
                                    <div class="toast-header"><img class="me-2"><strong class="me-auto">Notificação</strong><small>10 min ago</small><button class="btn-close ms-2 mb-1 close" data-bs-dismiss="toast"></button></div>
                                    <div class="toast-body" role="alert">
                                        <p>Corpo da notificação</p>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider" id="dv1"></div>
                            <h6 class="dropdown-header" id="dv2">Navegação</h6><a class="dropdown-item" id="user-btn-1" href="{{ route('show_profile', ['id' => Auth::id()]) }}">Perfil</a><a class="dropdown-item" id="user-btn-2" href="{{ route('config_view', ['id' => Auth::id()]) }}">Configurações</a><a class="dropdown-item" href="{{ route('for_you_feed') }}">Feed Personalizado</a> <a class="dropdown-item" id="user-btn-3" href="{{ route('logout') }}">Terminar Sessão</a>
                        </div>
                    </div><a id="trending-link" href="{{route('general_feed')}}"><i class="fa fa-arrow-up bi bi-question nav-link" id="i1" style="font-size: 25px;"></i></a><a id="faq-link" href="{{ route('faq') }}"><i class="fa fa-question bi bi-question nav-link" style="padding: 0px;margin-top: 0px;margin-right: 0px;"></i></a><a id="about-link" class="nav-link" href="{{ route('about-us') }}"><i class="fa fa-info bi bi-question"></i></a>
                </div>
            </div>
        </nav>
</header>
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->

        <li class="nav-item menu-open">
            <a href="#" class="nav-link">
            <i class="nav-icon fa fa-utensils "></i>
            <p>
                Menu
                <i class="right fas fa-angle-left"></i>
            </p>
            </a>
            <ul class="nav nav-treeview">
                @if(session('sector')=='Comedor' || session('sector')=='Sistemas')
                    <li class="nav-item">
                        <a href="/menu/showMenu" class="nav-link">
                        <i class="nav-icon fa fa-plus"></i>
                        <p>
                            Crear o Editar Menu
                            <span class="right badge badge-danger">New</span>
                        </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/menu/showPedido" class="nav-link">
                        <i class="nav-icon fa fa-clipboard-list"></i>
                        <p>Pedidos por semana</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/menu/showPedidosDia" class="nav-link">
                        <i class="nav-icon fa fa-clipboard-list"></i>
                        <p>Pedidos por dia</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="/menu/showUserMenu" class="nav-link">
                    <i class="nav-icon fa fa-address-card"></i>
                    <p>Menu Usuario</p>
                    </a>
                </li>
                
            </ul>
        </li>
        @if(session('sector')=='Rrhh' || session('sector')=='Sistemas')
            <li class="nav-item menu-open">
                <a href="#" class="nav-link">
                <i class="nav-icon fa fa-restroom"></i>
                <p>
                    RRHH
                    <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/rrhh/crearColaborador" class="nav-link">
                        <i class="nav-icon fa fa-plus"></i>
                        <p>
                            Crear Colaborador
                        </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/rrhh/editarColaborador" class="nav-link">
                        <i class="fa fa-pen nav-icon"></i>
                        <p>
                            Editar Colaborador
                        </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/rrhh/mesColaborador" class="nav-link">
                        <i class="fa fa-clipboard-check nav-icon"></i>
                        <p>
                            Mes Colaborador
                        </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/rrhh/showPuntuacion" class="nav-link">
                        <i class="nav-icon fa fa-star"></i>
                        <p>
                            Puntuacion Diaria
                        </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/rrhh/showPuntuacionMensual" class="nav-link">
                        <i class="nav-icon fa fa-star"></i>
                        <p>
                            Puntuacion Mensual
                        </p>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(session()->exists('userLogin') && session('userLogin')==true)
            <li class="nav-item">
                <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                    <i class="nav-icon fa fa-share"></i>
                    <p>Logout</p>
                </a>
                
            </li>
        @endif

        <form id="logout-form" action="/signout" method="GET" class="d-none">
            @csrf
        </form>
        

    </ul>
</nav>
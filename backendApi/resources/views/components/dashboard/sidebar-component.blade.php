<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <livewire:dashboard.authenticated-user-info-component
        />

    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('app.dashboard') }}" class="nav-link {{ request()->routeIs('app.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Clientes -->
            <li class="nav-item ">
                <a href="{{route('app.dashboard.clients')}}" class="nav-link {{ request()->routeIs('app.dashboard.clients.*') || request()->routeIs('app.dashboard.form.client') || request()->routeIs('app.dashboard.clone.client')  ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Clientes
                        {{-- <i class="fas fa-angle-left right"></i> --}}
                        <span class="badge badge-info right">{{$clientCounter ?? 0}}</span>
                    </p>
                </a>

            </li>

            <!-- Vendas -->
            <li class="nav-item {{ request()->routeIs('vendas.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('vendas.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-copy"></i>
                    <p>
                        Vendas
                        <i class="fas fa-angle-left right"></i>
                        <span class="badge badge-info right">6</span>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('vendas.historico') }}" class="nav-link {{ request()->routeIs('vendas.historico') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Histórico</p>
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Produtos -->
            <li class="nav-item {{ request()->routeIs('produtos.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Produtos
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('produtos.index') }}" class="nav-link {{ request()->routeIs('produtos.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Produtos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('app.dashboard.categories') }}" class="nav-link {{ request()->routeIs('app.dashboard.categories') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Categorias</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('app.dashboard.subcategories') }}" class="nav-link {{ request()->routeIs('app.dashboard.subcategories') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Subcategorias</p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('produtos.etiquetas') }}" class="nav-link {{ request()->routeIs('produtos.etiquetas') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Etiquetas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('app.dashboard.suppliers') }}" class="nav-link {{ request()->routeIs('app.dashboard.suppliers') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Fornecedores</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('produtos.taxas') }}" class="nav-link {{ request()->routeIs('produtos.taxas') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Taxas</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Estoque -->
            <li class="nav-item {{ request()->routeIs('estoque.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('estoque.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tree"></i>
                    <p>
                        Estoque
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('estoque.produto') }}" class="nav-link {{ request()->routeIs('estoque.produto') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Estoque por produto</p>
                        </a>
                    </li>

                </ul>
            </li>

            <!-- Unidades -->
            <li class="nav-item">
                <a href="{{ route('app.dashboard.units') }}" class="nav-link {{ request()->routeIs('app.dashboard.units') || request()->routeIs('app.dashboard.form.unit') || request()->routeIs('app.dashboard.edit.unit') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>
                        Unidades
                        <span class="badge badge-info right">{{$unitCounter ?? 0}}</span>
                    </p>
                </a>

            </li>

            <!-- Utilizadores -->
             <li class="nav-item">
                <a href="{{ route('app.dashboard.users') }}" class="nav-link {{ request()->routeIs('app.dashboard.users') || request()->routeIs('app.dashboard.form.user') || request()->routeIs('app.dashboard.clone.user') || request()->routeIs('app.dashboard.rename.password') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Utilizadores
                        <span class="badge badge-info right">{{$userCounter ?? 0}}</span>
                    </p>
                </a>

            </li>

            <!-- Relatórios -->

             <li class="nav-item {{ request()->routeIs('app.dashboard.report.*') ? 'menu-open' : '' }}">
                <a  class="nav-link {{ request()->routeIs('app.dashboard.reportuser') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file"></i>
                    <p>
                        Relatórios
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('app.dashboard.report.user') }}" class="nav-link {{ request()->routeIs('app.dashboard.report.user') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Utilizadores</p>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</div>

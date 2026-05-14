<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="clsTitlePage m-0">@yield('page-title', 'Dashboard')</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="@yield('homepage-group-url', 'app.dashboard')">@yield('page-breadcrumb-group', 'Home') </a></li>
                <li class="breadcrumb-item active">
                    <a class='text-secondary' href="@yield('page-url', route('app.dashboard'))">
                        @yield('page-breadcrumb', 'Dashboard')
                    </a>

                </li>

                 
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->

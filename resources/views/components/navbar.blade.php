<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Dashboard
                                    </span>
                                </a>
                            </li>

                            @role('Admin')
                                <li class="nav-item dropdown {{ request()->is('role*') || request()->is('user*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-master" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Data
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item {{ Route::is('role*') ? 'active' : '' }}" href="{{ route('role.index') }}">
                                            Data Level / Role
                                        </a>
                                        <a class="dropdown-item {{ Route::is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                                            Data Pengguna (Users)
                                        </a>
                                    </div>
                                </li>
                            @endrole

                            @if (
                                auth()->user()->can('lihat investor') || 
                                auth()->user()->can('lihat type investasi') ||
                                auth()->user()->can('lihat kategori investasi')
                            )
                                <li class="nav-item dropdown {{ request()->is('investor*') || request()->is('type*') || request()->is('kategori*') || request()->is('module-investor*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-investor" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Manajemen Investor
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        @can('lihat investor')
                                            <a class="dropdown-item {{ Route::is('investor*') ? 'active' : '' }}" href="{{ route('investor.index') }}">
                                                Data Investor
                                            </a>
                                        @endcan
                                        @can('lihat type investasi')
                                            <a class="dropdown-item {{ Route::is('type*') ? 'active' : '' }}" href="{{ route('type.index') }}">
                                                Tipe Investasi
                                            </a>
                                        @endcan
                                        @can('lihat kategori investasi')
                                            <a class="dropdown-item {{ Route::is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                                                Kategori Investasi
                                            </a>
                                        @endcan
                                    </div>
                                </li>
                            @endif

                            @if (auth()->user()->can('lihat transfer'))
                                <li class="nav-item {{ Route::is('transfer*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('transfer.index') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            @if (!Auth::user()->hasRole('Investor'))
                                                Transfer Pendapatan
                                            @else
                                                History Pendapatan
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            @role("Admin")
                                <li class="nav-item {{ Route::is('setting') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('setting') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Pengaturan
                                        </span>
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
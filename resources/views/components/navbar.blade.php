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
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-home"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Dashboard
                                    </span>
                                </a>
                            </li>
                            @role('Admin')
                                <li class="nav-item dropdown {{ request()->is('role*') || request()->is('user*') ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Master Data
                                        </span>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item {{ Route::is("role*") ? 'active' : '' }}" href="{{ route('role.index') }}" rel="noopener">
                                            Data Level
                                        </a>
                                        <a class="dropdown-item {{ Route::is("user*") ? 'active' : '' }}" href="{{ route('user.index') }}" rel="noopener">
                                            Data Users
                                        </a>
                                    </div>
                                </li>
                            @endrole
                            @if (
                                auth()->user()->can('lihat investor') || 
                                auth()->user()->can('lihat type investasi') ||
                                auth()->user()->can('lihat kategori investasi')
                            )
                                
                            @endif
                            <li class="nav-item dropdown {{ request()->is('module-investor*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-databricks"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l9 5l9 -5v-3l-9 5l-9 -5v-3l9 5l9 -5v-3l-9 5l-9 -5l9 -5l5.418 3.01" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Investor
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    @can('lihat type investasi')
                                        <a class="dropdown-item {{ Route::is('type*') ? 'active' : '' }}" href="{{ route('type.index') }}" rel="noopener">
                                            Data Type Investasi
                                        </a>
                                    @endcan
                                    @can('lihat kategori investasi')
                                        <a class="dropdown-item {{ Route::is('kategori*') ? 'active' : '' }}" href="{{ route('kategori.index') }}" rel="noopener">
                                            Data Katgeori Investasi
                                        </a>
                                    @endcan
                                    @can('lihat investor')
                                        <a class="dropdown-item {{ Route::is('investor*') ? 'active' : '' }}" href="{{ route('investor.index') }}" rel="noopener">
                                            Data Investor
                                        </a>
                                    @endcan
                                </div>
                            </li>
                            @if (auth()->user()->can('lihat transfer'))
                                <li class="nav-item {{ Route::is('transfer*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('transfer.index') }}">
                                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-businessplan"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6m-5 0a5 3 0 1 0 10 0a5 3 0 1 0 -10 0" /><path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M5 15v1m0 -8v1" /></svg>
                                        </span>
                                        <span class="nav-link-title">
                                            Transfer Pendapatan
                                        </span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
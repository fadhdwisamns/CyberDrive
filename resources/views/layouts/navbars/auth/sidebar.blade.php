<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            {{-- Path gambar diperbaiki dengan asset() --}}
            <img src="{{ asset('assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-3 font-weight-bold">Soft UI Dashboard Laravel</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            {{-- =================================== --}}
            {{-- MENU INI TAMPIL UNTUK SEMUA USER --}}
            {{-- =================================== --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('drive.index') ? 'active' : '' }}" href="{{ route('drive.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-folder-17 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">My Drive</span>
                </a>
            </li>



            {{-- ============================================ --}}
            {{-- SEMUA MENU DI BAWAH INI HANYA TAMPIL UNTUK ADMIN --}}
            {{-- ============================================ --}}
            @if (auth()->user()->role == 'admin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">ADMIN MENU</h6>
                </li>
                
                {{-- MENU DASHBOARD ADMIN --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>


                {{-- MENU USER MANAGEMENT --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    {{-- Path href dan kondisi active diubah ke rute baru --}}
                    <a class="nav-link {{ Route::is('admin.user.page') ? 'active' : '' }}" href="{{ route('admin.user.page') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-info text-sm opacity-10"></i>
                        </div>
                        {{-- Anda bisa ganti teks ini sesuai nama halaman baru Anda --}}
                        <span class="nav-link-text ms-1">Halaman User</span>
                    </a>
                </li>
                
                {{-- MENU ACTIVITY LOG --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.activity-log.index') ? 'active' : '' }}" href="{{ route('admin.activity-log.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Activity Log</span>
                    </a>
                </li>

                {{-- MENU FILE BROWSER --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.file-browser.*') ? 'active' : '' }}" href="{{ route('admin.file-browser.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-folder-17 text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">File Browser</span>
                    </a>
                </li>

                {{-- MENU SETTINGS --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.settings.index') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-settings-gear-65 text-secondary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('drive.index') ? 'active' : '' }}" href="{{ route('drive.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-folder-17 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Semua File</span>
                    </a>
                </li>
            @endif


            {{-- =================================== --}}
            {{-- MENU INI TAMPIL UNTUK SEMUA USER --}}
            {{-- =================================== --}}
          <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('drive.recents') ? 'active' : '' }}" href="{{ route('drive.recents') }}">
                  <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="ni ni-time-alarm text-info text-sm opacity-10"></i>
                  </div>
                  <span class="nav-link-text ms-1">Baru Dilihat</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('drive.trash') ? 'active' : '' }}" href="{{ route('drive.trash') }}">
                  <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="ni ni-fat-remove text-danger text-sm opacity-10"></i>
                  </div>
                  <span class="nav-link-text ms-1">Sampah</span>
              </a>
          </li>
        </ul>
    </div>
</aside>
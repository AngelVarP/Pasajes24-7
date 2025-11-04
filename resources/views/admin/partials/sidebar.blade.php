<aside class="admin-sidebar w-64 hidden md:flex md:flex-col fixed inset-y-0 z-50">
    <div class="flex items-center justify-center h-20 border-b border-slate-200">
        <a href="/">
            <img src="{{ asset('images/logo-pasajes24-7.png') }}" alt="Logo Pasajes24/7" class="h-20 w-auto">
        </a>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="pt-4 pb-2 px-4">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Gestión</span>
        </div>

        <a href="{{ route('admin.viajes.index') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.viajes.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span>Viajes</span>
        </a>

        <a href="{{ route('admin.rutas.index') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.rutas.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Rutas</span>
        </a>

        <a href="{{ route('admin.empresas.index') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.empresas.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V7a2 2 0 00-2-2h-3V3a1 1 0 00-1-1H10a1 1 0 00-1 1v2H6a2 2 0 00-2 2v6"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Empresas</span>
        </a>

        <a href="{{ route('admin.ciudades.index') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.ciudades.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.09M19.945 11H19a2 2 0 00-2 2v1a2 2 0 01-2 2 2 2 0 00-2 2v2.945M16 3.935V5.5A2.5 2.5 0 0113.5 8h-.09M12 21v-2.055A2.5 2.5 0 0114.5 16h-.09M12 3c-1.355 0-2.67.291-3.838.813a10.98 10.98 0 00-6.817 6.817C.791 11.33 0 12.645 0 14c0 1.355.791 2.67 1.345 3.838a10.98 10.98 0 006.817 6.817C9.33 24.209 10.645 25 12 25c1.355 0 2.67-.791 3.838-1.345a10.98 10.98 0 006.817-6.817C23.209 16.67 24 15.355 24 14c0-1.355-.791-2.67-1.345-3.838a10.98 10.98 0 00-6.817-6.817C14.67 2.791 13.355 2 12 2z"/>
            </svg>
            <span>Ciudades</span>
        </a>

        <a href="{{ route('admin.reservas.index') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Reservas</span>
        </a>

        <div class="pt-6 pb-2 px-4">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Reportes</span>
        </div>

        <a href="{{ route('admin.reportes.puntualidad') }}"
           class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.reportes.puntualidad') ? 'active' : '' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 3.055A9.001 9.001 0 1021 12h-8"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 3v9h9"/>
            </svg>
            <span>Puntualidad</span>
        </a>
    </nav>

    <div class="mt-auto p-4 border-t border-slate-200">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 transition duration-150">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

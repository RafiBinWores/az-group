    <aside id="sidebar"
        class="fixed top-0 left-0 z-10 w-64 h-screen p-4 space-y-6 text-sm font-medium transition-all duration-300 bg-white shadow-sm sidebar-transition hidden lg:block">
        <!-- Logo -->
        <div class="flex items-center justify-between mb-6 logo-container">
            <a href="/" class="flex items-center gap-2 logo-container">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                    class="flex items-center justify-center w-14" />

                <span class="text-xl font-bold text-gray-700 label">AZ Group</span>
            </a>
        </div>

        <!-- Menu -->
        <nav class="space-y-6">
            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    MAIN
                </p>
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item {{ request()->routeIs('dashboard.index') ? 'bg-gray-100' : 'hover:bg-gray-100 hover:translate-x-2' }}">
                    <i
                        class="text-gray-500 fa-regular fa-house {{ request()->routeIs('dashboard.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }}"></i>
                    <span
                        class="text-sm font-medium text-gray-500 {{ request()->routeIs('dashboard.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }} label">Dashboard</span>
                </a>
            </div>

            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    OPERATIONS
                </p>

                <ul class="space-y-2">
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item hover:bg-gray-100 hover:translate-x-2">
                            <i class="text-gray-500 fa-regular fa-chart-pie group-hover:text-gray-700"></i>
                            <span class="text-sm font-medium text-gray-500 group-hover:text-gray-700 label">Production
                                Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item hover:bg-gray-100 hover:translate-x-2">
                            <i class="text-gray-500 fa-regular fa-box-open-full group-hover:text-gray-700"></i>
                            <span class="text-sm font-medium text-gray-500 group-hover:text-gray-700 label">Finishing
                                Report</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    MANAGEMENT
                </p>

                <ul class="mt-3 space-y-1">
                    <li>
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item {{ request()->routeIs('roles.index') ? 'bg-gray-100' : 'hover:bg-gray-100 hover:translate-x-2' }}">
                            <i
                                class="fa-regular fa-screen-users text-gray-500 {{ request()->routeIs('roles.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }}"></i>
                            <span
                                class="text-sm font-medium text-gray-500 {{ request()->routeIs('roles.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }} label">Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item {{ request()->routeIs('users.index') ? 'bg-gray-100' : 'hover:bg-gray-100 hover:translate-x-2' }}">
                            <i
                                class="fa-regular fa-users text-gray-500 {{ request()->routeIs('users.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }}"></i>
                            <span
                                class="text-sm font-medium text-gray-500 {{ request()->routeIs('users.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }} label">Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- Sidebar (Mobile/Tablet/Medium) -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 z-30 bg-black/25 hidden lg:hidden"></div>
    <aside id="mobileSidebar"
        class="fixed top-0 left-0 z-40 w-64 h-full p-4 space-y-6 text-sm font-medium transition-transform duration-300 bg-white shadow-lg transform -translate-x-full lg:hidden">
        <!-- Logo + Close -->
        <a href="/" class="flex items-center justify-between mb-8 logo-container">
            <div class="flex items-center gap-2 logo-container">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                    class="flex items-center justify-center w-14" />
                <span class="text-xl font-bold text-gray-700 label">AZ Group</span>
            </div>
            <button id="mobileSidebarClose" class="text-xl text-gray-700 cursor-pointer hover:text-black">
                <i class="fa-regular fa-xmark"></i>
            </button>
        </a>
        <!-- Menu (reuse same as desktop) -->
        <nav class="space-y-6">
            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    MAIN
                </p>
                <a href="/"
                    class="flex items-center gap-3 px-2 py-2 mt-2 text-gray-900 bg-gray-100 rounded-md item hover:bg-gray-200">
                    <i class="fa-regular fa-house"></i>
                    <span class="label">Dashboard</span>
                </a>
            </div>

            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    OPERATIONS
                </p>

                <ul class="space-y-2">
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item hover:bg-gray-100 hover:translate-x-2">
                            <i class="text-gray-500 fa-regular fa-chart-pie group-hover:text-gray-700"></i>
                            <span class="text-sm font-medium text-gray-500 group-hover:text-gray-700 label">Production
                                Report</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <p class="mb-1 text-xs font-semibold tracking-widest text-gray-400 label font-ibm">
                    MANAGEMENT
                </p>

                <ul class="mt-3 space-y-1">
                    <li>
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item {{ request()->routeIs('roles.index') ? 'bg-gray-100' : 'hover:bg-gray-100 hover:translate-x-2' }}">
                            <i
                                class="fa-regular fa-screen-users text-gray-500 {{ request()->routeIs('roles.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }}"></i>
                            <span
                                class="text-sm font-medium text-gray-500 {{ request()->routeIs('roles.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }} label">Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-3 px-2 py-2 mt-2 duration-150 rounded-md group item {{ request()->routeIs('users.index') ? 'bg-gray-100' : 'hover:bg-gray-100 hover:translate-x-2' }}">
                            <i
                                class="fa-regular fa-users text-gray-500 {{ request()->routeIs('users.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }}"></i>
                            <span
                                class="text-sm font-medium text-gray-500 {{ request()->routeIs('users.index') ? 'text-gray-700' : 'group-hover:text-gray-700' }} label">Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

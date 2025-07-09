    <div class="flex items-center justify-between px-8 bg-white py-4 shadow lg:bg-transparent lg:shadow-none">
        <div class="flex items-center gap-4">
            <!-- hamburger icon (Mobile/Tablet/Medium) -->
            <button id="mobileSidebarOpen" class="text-xl text-gray-500 cursor-pointer hover:text-black lg:hidden">
                <i class="fa-regular fa-bars"></i>
            </button>
            <!-- hamburger icon (Desktop, only after collapse) -->
            <button id="mainToggleSidebar" class="hidden text-xl text-gray-500 cursor-pointer hover:text-black lg:inline">
                <i class="fa-regular fa-bars"></i>
            </button>

            {{-- page heder --}}
            <h1 class="hidden text-2xl font-semibold md:block">{{ $header ?? 'Hi, Welcome Back!' }}</h1>
        </div>
        <!-- Top-bar end -->

        <div class="flex items-center gap-8">
            <img src="{{ asset('assets/images/bangladesh.png') }}" alt="Bangladeshi Flag" class="h-10" />

            <!-- Search button -->
            <div class="relative">
                <button id="searchToggle" class="text-xl text-gray-600 hover:text-gray-800 cursor-pointer">
                    <i class="text-xl font-medium fa-regular fa-magnifying-glass"></i>
                </button>

                <!-- Floating Search Box -->
                <div id="searchBox"
                    class="absolute right-0 z-50 hidden p-2 bg-white border border-gray-200 shadow-lg rounded-xl top-10 md:w-80">
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-100 rounded-xl">
                        <i class="text-gray-500 fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search..."
                            class="w-full text-sm placeholder-gray-400 bg-gray-100 border-none outline-none" />
                    </div>
                </div>
            </div>

            <!-- Notification Dropdown -->
            <div class="relative">
                <!-- Bell Button -->
                <button id="notifyToggle" class="relative text-xl text-gray-600 hover:text-gray-800">
                    <i class="fa-regular fa-bell"></i>
                    <!-- <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">4</span> -->
                </button>

                <!-- Dropdown -->
                <!-- <div id="notifyDropdown" class="hidden absolute md:right-0 z-50 mt-3 md:w-96 w-full bg-white border border-gray-200 rounded-xl shadow-xl">
  
                    <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                    <a href="#" class="text-sm text-blue-600 hover:underline">Mark all as read</a>
                    </div>

                    
                    <div class="divide-y">
                    
                    <div class="flex items-start gap-3 px-4 py-3">
                        <img src="./assets/images/placeholder.png" class="w-9 h-9 rounded-full" />
                        <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">James Lemire</p>
                        <p class="text-sm text-gray-600">It will seem like simplified English.</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap">1 hour ago</span>
                    </div>
                    </div>

                
                    <div class="text-center px-4 py-3 border-t">
                    <a href="#" class="text-sm text-blue-600 hover:underline">View More..</a>
                    </div>
                </div> -->
            </div>


            <!-- Profile button -->
            <div class="relative inline-block text-left">
                <!-- Profile Button -->
                <button id="profileDropdownBtn" class="flex items-center gap-2 focus:outline-none cursor-pointer">
                    @if (!empty(auth()->user()->avatar))
                                    <img class="w-8 h-8 rounded-full ring-1 ring-[#99c041]" src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <svg viewBox="0 0 61.7998 61.7998" xmlns="http://www.w3.org/2000/svg" fill="#000000" class="w-8 h-8 rounded-full ring-1 ring-[#99c041]"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g data-name="Layer 2" id="Layer_2"> <g data-name="—ÎÓÈ 1" id="_ÎÓÈ_1"> <circle cx="30.8999" cy="30.8999" fill="#485a69" r="30.8999"></circle> <path d="M23.242 38.592l15.92.209v12.918l-15.907-.121-.013-13.006z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M53.478 51.993A30.814 30.814 0 0 1 30.9 61.8a31.225 31.225 0 0 1-3.837-.237A30.699 30.699 0 0 1 15.9 57.919a31.033 31.033 0 0 1-7.857-6.225l1.284-3.1 13.925-6.212c0 4.535 1.84 6.152 7.97 6.244 7.57.113 7.94-1.606 7.94-6.28l12.79 6.281z" fill="#d5e1ed" fill-rule="evenodd"></path> <path d="M39.165 38.778v3.404c-2.75 4.914-14 4.998-15.923-3.59z" fill-rule="evenodd" opacity="0.11"></path> <path d="M31.129 8.432c21.281 0 12.987 35.266 0 35.266-12.267 0-21.281-35.266 0-35.266z" fill="#ffe8be" fill-rule="evenodd"></path> <path d="M18.365 24.045c-3.07 1.34-.46 7.687 1.472 7.658a31.973 31.973 0 0 1-1.472-7.658z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M44.14 24.045c3.07 1.339.46 7.687-1.471 7.658a31.992 31.992 0 0 0 1.471-7.658z" fill="#f9dca4" fill-rule="evenodd"></path> <path d="M43.409 29.584s1.066-8.716-2.015-11.752c-1.34 3.528-7.502 4.733-7.502 4.733a16.62 16.62 0 0 0 3.215-2.947c-1.652.715-6.876 2.858-11.61 1.161a23.715 23.715 0 0 0 3.617-2.679s-4.287 2.322-8.44 1.742c-2.991 2.232-1.66 9.162-1.66 9.162C15 18.417 18.697 6.296 31.39 6.226c12.358-.069 16.17 11.847 12.018 23.358z" fill="#ecbe6a" fill-rule="evenodd"></path> <path d="M23.255 42.179a17.39 17.39 0 0 0 7.958 6.446l-5.182 5.349L19.44 43.87z" fill="#ffffff" fill-rule="evenodd"></path> <path d="M39.16 42.179a17.391 17.391 0 0 1-7.958 6.446l5.181 5.349 6.592-10.103z" fill="#ffffff" fill-rule="evenodd"></path> <path d="M33.366 61.7q-1.239.097-2.504.098-.954 0-1.895-.056l1.031-8.757h2.41z" fill="#3dbc93" fill-rule="evenodd"></path> <path d="M28.472 51.456l2.737-2.817 2.736 2.817-2.736 2.817-2.737-2.817z" fill="#3dbc93" fill-rule="evenodd"></path> </g> </g> </g></svg>
                                @endif
                    {{-- <img src="{{ asset('assets/images/placeholder.png') }}" alt="User"
                        class="w-8 h-8 rounded-full ring-2 ring-lime-800" /> --}}
                    <span class="font-semibold text-slate-800 hidden sm:inline">{{ auth()->user()->name }}</span>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdown"
                    class="hidden absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-xl bg-white shadow-md border border-gray-200">
                    <!-- Header -->
                    <div class="p-5 border-b border-b-gray-300">
                        <p class="text-[15px] font-montserrat font-semibold text-slate-800">Rafi Bin Wores</p>
                        <p class="text-xs font-ibm text-gray-400">rafibinwores@email.com</p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fa-regular fa-user w-5 mr-2 text-gray-400"></i> <span
                                class="font-ibm text-[15px]">Profile</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fa-regular fa-gear w-5 mr-2 text-gray-400"></i> <span
                                class="font-ibm text-[15px]">Settings</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fa-solid fa-lock-keyhole w-5 mr-2 text-gray-400"></i> <span
                                class="font-ibm text-[15px]">Lock screen</span>
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-t-gray-300">
                        <a href="{{ route('auth.logout') }}" class="flex items-center px-4 py-3 text-sm hover:bg-gray-50 rounded-xl">
                            <i class="fa-regular fa-arrow-right-from-bracket w-5 mr-2 text-gray-400"></i> <span
                                class="font-ibm text-[15px]">Logout</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
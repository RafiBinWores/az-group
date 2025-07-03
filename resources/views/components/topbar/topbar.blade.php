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
                    <img src="{{ asset('assets/images/placeholder.png') }}" alt="User"
                        class="w-8 h-8 rounded-full ring-1 ring-lime-800" />
                    <span class="font-semibold text-slate-800 hidden sm:inline">Rafi Bin Wores</span>
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
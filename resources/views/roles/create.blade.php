<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">Create role | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">Create Role</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-6">
        <div class="px-6 py-4 border-b border-gray-200 text-gray-700 font-semibold text-lg">
            Role Information
        </div>

        <div class="p-6 font-ibm">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="font-semibold">Role Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter role name"
                        class="w-full border mt-3 outline-[#99c041] border-gray-300 px-3 py-2  rounded-xl">
                    <span class="error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="mb-4">
                    <label class="font-semibold mb-2 block">Assign Permissions</label>
                    <div class="flex flex-row items-center flex-wrap gap-6">
                        @foreach ($permissions as $permission)
                            <div class="flex flex-row flex-wrap items-center select-none gap-2">
                                <label class="text-slate-400">
                                    <input type="checkbox" value="{{ $permission->name }}" name="permissions[]"
                                        class="h-[1px] opacity-0 overflow-hidden absolute whitespace-nowrap w-[1px] peer">
                                    <span
                                        class="peer-checked:border-[#99c041] peer-checked:shadow-[#99c041]/10 peer-checked:text-[#99c041] peer-checked:before:border-[#99c041] peer-checked:before:bg-[#99c041] peer-checked:before:opacity-100 peer-checked:before:scale-100 peer-checked:before:content-['✓'] flex flex-col items-center justify-center w-28 min-h-[80px] rounded-lg shadow-lg transition-all duration-200 cursor-pointer relative border-slate-300 border-[3px] bg-white before:absolute before:w-5 before:h-5 before:border-[3px]  before:rounded-full before:top-1 before:left-1 before:opacity-0 before:transition-transform before:scale-0 before:text-white before:text-xs before:flex before:items-center before:justify-center hover:border-[#99c041] hover:before:scale-100 hover:before:opacity-100">
                                        <span
                                            class="transition-all duration-300 text-center text-sm">{{ $permission->name }}</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <span class="error-permissions error text-red-500 text-xs mt-1 block"></span>
                </div>

                <div class="flex items-center gap-4 mt-5">
                    <a href="{{ route('roles.index') }}"
                        class="bg-red-400 cursor-pointer hover:bg-red-500 text-white px-4 py-2 rounded-xl"><i
                            class="fa-regular fa-xmark pe-1"></i> Cancel</a>
                    <button type="submit"
                        class="bg-[#99c041] cursor-pointer hover:bg-[#89bb13] text-white px-4 py-2 rounded-xl"><i
                            class="fa-regular fa-file-spreadsheet pe-1"></i> Create</button>
                </div>
            </form>
        </div>

    </div>


    @push('scripts')
        <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/roles/roleCreate.js') }}"></script>
    @endpush

</x-layouts.app>

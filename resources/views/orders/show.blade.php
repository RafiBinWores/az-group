<x-layouts.app>
    {{-- Page title --}}
    <x-slot name="title">order details | AZ Group</x-slot>
    {{-- Page title end --}}

    {{-- Page header --}}
    <x-slot name="header">order details</x-slot>
    {{-- Page header end --}}

    {{-- Notifications --}}
    <x-notification.notification-toast />

    {{-- Page Content --}}
    <div class="bg-white shadow-sm w-full rounded-lg mt-4">
        <div class="border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="text-gray-700 font-semibold text-lg">
                    Style No : <span class="text-[#99c041]">{{ $order->style_no }}</span>
                    <p class="text-sm mb-3 mt-1">Garment Type: <span
                            class="font-normal">{{ $order->garmentTypes->pluck('name')->join(', ') ?: 'N/A' }}
                        </span></p>
                    <p class="text-sm mb-3 mt-1">Total Quantity: <span
                            class="font-normal">{{ $order->order_qty }}
                        </span></p>
                    <p class="text-sm">Date: <span class="font-normal">{{ $order->created_at->format('M d, Y') }}
                        </span></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('orders.export', $order->id) }}"
                        class="inline-block px-4 py-2 bg-sky-500 rounded hover:bg-sky-700 duration-200">
                        <i class="fa-regular fa-file-xls text-white"></i>
                    </a>
                    <a class="bg-green-500 px-4 py-2 rounded hover:bg-green-600 duration-200"
                        href="{{ route('orders.edit', $order->id) }}"><i
                            class="fa-regular fa-pen text-white"></i></a>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Color
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Quantity
                        </th>
                    </tr>
                </thead>
                @php
                    $totalQty = collect($order->color_qty ?? [])->sum('qty');
                @endphp

                <tbody>
                    @foreach ($order->color_qty as $row)
                        <tr class="border-b border-gray-200">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $row['color'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $row['qty'] }}
                            </td>
                        </tr>
                    @endforeach
                    {{-- Total Row --}}
                    <tr class="font-bold">
                        <td class="px-6 py-4 text-center">Total</td>
                        <td class="px-6 py-4">{{ $totalQty }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
</x-layouts.app>

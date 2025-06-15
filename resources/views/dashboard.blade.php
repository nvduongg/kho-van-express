<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng điều khiển (Dashboard)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Tổng quan hoạt động</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {{-- Card Tổng số đơn hàng --}}
                        <div class="bg-blue-50 p-6 rounded-lg shadow-md flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-700">Tổng số Đơn hàng</p>
                                <p class="text-3xl font-bold text-blue-900 mt-1">{{ $totalOrders }}</p>
                            </div>
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>

                        {{-- Card Tổng doanh thu 30 ngày qua --}}
                        <div class="bg-green-50 p-6 rounded-lg shadow-md flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-700">Doanh thu (30 ngày qua)</p>
                                <p class="text-3xl font-bold text-green-900 mt-1">{{ number_format($revenueLast30Days, 0, ',', '.') }} VNĐ</p>
                            </div>
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 10v2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>

                        {{-- Card Tổng số sản phẩm --}}
                        <div class="bg-purple-50 p-6 rounded-lg shadow-md flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-700">Tổng số Sản phẩm</p>
                                <p class="text-3xl font-bold text-purple-900 mt-1">{{ $totalProducts }}</p>
                            </div>
                            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m-4 0h8m-4 0h.01"></path></svg>
                        </div>

                        {{-- Card Tổng số kho hàng --}}
                        <div class="bg-red-50 p-6 rounded-lg shadow-md flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-700">Tổng số Kho hàng</p>
                                <p class="text-3xl font-bold text-red-900 mt-1">{{ $totalWarehouses }}</p>
                            </div>
                            <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                        </div>
                    </div>

                    <h4 class="text-xl font-bold text-gray-800 mb-4">Trạng thái Đơn hàng & Chuyến hàng</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Thống kê trạng thái Đơn hàng --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <p class="text-lg font-semibold text-gray-700 mb-3">Đơn hàng</p>
                            <ul class="space-y-2">
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đang chờ:</span>
                                    <span class="font-bold text-blue-600">{{ $pendingOrders }}</span>
                                </li>
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đã hoàn thành:</span>
                                    <span class="font-bold text-green-600">{{ $completedOrders }}</span>
                                </li>
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đã hủy:</span>
                                    <span class="font-bold text-red-600">{{ $cancelledOrders }}</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Thống kê trạng thái Chuyến hàng --}}
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                            <p class="text-lg font-semibold text-gray-700 mb-3">Chuyến hàng</p>
                            <ul class="space-y-2">
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đang chờ:</span>
                                    <span class="font-bold text-yellow-600">{{ $pendingShipments }}</span>
                                </li>
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đang vận chuyển:</span>
                                    <span class="font-bold text-blue-600">{{ $inTransitShipments }}</span>
                                </li>
                                <li class="flex justify-between items-center text-gray-700">
                                    <span>Đã giao:</span>
                                    <span class="font-bold text-green-600">{{ $deliveredShipments }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Bạn có thể thêm biểu đồ hoặc các bảng tóm tắt khác ở đây --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
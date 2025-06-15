<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Báo cáo Vận chuyển') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tổng quan Vận chuyển</h3>

                    {{-- Form Lọc --}}
                    <form action="{{ route('reports.shipment') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Từ ngày xuất:</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Đến ngày xuất:</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái:</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả</option>
                                    @foreach($shipmentStatuses as $s)
                                        <option value="{{ $s }}" {{ $selectedStatus == $s ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Phương tiện:</label>
                                <select id="vehicle_id" name="vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả</option>
                                    @foreach($allVehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ $selectedVehicleId == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->license_plate }} ({{ $vehicle->make }})
                                        </option>
                                    @endforeach
                                    <option value="no_vehicle" {{ $selectedVehicleId == 'no_vehicle' ? 'selected' : '' }}>Chưa gán phương tiện</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-start">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Xem Báo cáo
                            </button>
                            <a href="{{ route('reports.shipment') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Đặt lại
                            </a>
                        </div>
                    </form>

                    {{-- Tổng quan thống kê --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm font-medium text-blue-700">Tổng số chuyến hàng:</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $shipments->count() }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm font-medium text-green-700">Đã giao:</p>
                            <p class="text-2xl font-bold text-green-900">{{ $statusCounts['delivered'] ?? 0 }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm font-medium text-yellow-700">Đang vận chuyển:</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $statusCounts['in_transit'] ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="text-md font-medium text-gray-800 mb-3">Thống kê theo Trạng thái:</h4>
                            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg shadow-inner">
                                @forelse ($statusCounts as $status => $count)
                                    <li>
                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $status)) }}:</span> {{ $count }} chuyến
                                    </li>
                                @empty
                                    <li>Không có chuyến hàng nào theo trạng thái.</li>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-md font-medium text-gray-800 mb-3">Thống kê theo Phương tiện:</h4>
                            <ul class="list-disc list-inside bg-gray-50 p-4 rounded-lg shadow-inner">
                                @forelse ($vehicleCounts as $vehicleData)
                                    <li>
                                        <span class="font-medium">{{ $vehicleData['name'] }}:</span> {{ $vehicleData['count'] }} chuyến
                                    </li>
                                @empty
                                    <li>Không có chuyến hàng nào theo phương tiện.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Danh sách chi tiết các chuyến hàng --}}
                    <h4 class="text-md font-medium text-gray-800 mb-3">Danh sách Chi tiết Chuyến hàng:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Chuyến</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phương tiện</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kho xuất</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kho đích</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày xuất</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày giao dự kiến</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($shipments as $shipment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('shipments.show', $shipment->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $shipment->tracking_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($shipment->order && $shipment->order->customer)
                                                #ORD{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }} ({{ $shipment->order->customer->name }})
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $shipment->vehicle->license_plate ?? 'Chưa gán' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $shipment->originWarehouse->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $shipment->destinationWarehouse->name ?? 'Giao thẳng tới khách hàng' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $shipment->shipment_date ? $shipment->shipment_date->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : 'Chưa xác định' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($shipment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($shipment->status == 'in_transit') bg-blue-100 text-blue-800
                                                @elseif($shipment->status == 'delivered') bg-green-100 text-green-800
                                                @elseif($shipment->status == 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có chuyến hàng nào được tìm thấy trong khoảng thời gian và bộ lọc đã chọn.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
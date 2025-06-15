<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Vận chuyển') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Danh sách Chuyến hàng</h3>
                        <a href="{{ route('shipments.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Tạo Chuyến hàng mới
                        </a>
                    </div>

                    {{-- Thông báo thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Thành công!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 3.152a1.2 1.2 0 1 1-1.697-1.697l3.152-2.651-3.152-2.651a1.2 1.2 0 1 1 1.697-1.697l2.651 3.152 2.651-3.152a1.2 1.2 0 1 1 1.697 1.697l-3.152 2.651 3.152 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
                            </span>
                        </div>
                    @endif

                    {{-- Filter Form --}}
                    <form action="{{ route('shipments.index') }}" method="GET" class="mb-4 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700">Lọc theo Đơn hàng:</label>
                                <select id="order_id" name="order_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả đơn hàng</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ request('order_id') == $order->id ? 'selected' : '' }}>
                                            #ORD{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} ({{ $order->customer->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Lọc theo Phương tiện:</label>
                                <select id="vehicle_id" name="vehicle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả phương tiện</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->license_plate }} ({{ $vehicle->make }} {{ $vehicle->model }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Lọc theo Trạng thái:</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach($shipmentStatuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="shipment_date_from" class="block text-sm font-medium text-gray-700">Từ ngày xuất:</label>
                                <input type="date" id="shipment_date_from" name="shipment_date_from" value="{{ request('shipment_date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mt-4">
                            <div>
                                <label for="shipment_date_to" class="block text-sm font-medium text-gray-700">Đến ngày xuất:</label>
                                <input type="date" id="shipment_date_to" name="shipment_date_to" value="{{ request('shipment_date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div class="col-span-3 flex items-center justify-start">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Tìm kiếm
                                </button>
                                @if(request('order_id') || request('vehicle_id') || request('status') || request('shipment_date_from') || request('shipment_date_to'))
                                    <a href="{{ route('shipments.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Xóa lọc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã Chuyến hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phương tiện</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kho xuất</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày xuất</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($shipments as $shipment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $shipment->tracking_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Kiểm tra tồn tại của order và customer trước khi truy cập --}}
                                            @if($shipment->order && $shipment->order->customer)
                                                <a href="{{ route('orders.show', $shipment->order->id) }}" class="text-blue-600 hover:text-blue-900">
                                                    #ORD{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }} ({{ $shipment->order->customer->name }})
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Kiểm tra tồn tại của vehicle trước khi truy cập --}}
                                            @if($shipment->vehicle)
                                                {{ $shipment->vehicle->license_plate }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Kiểm tra tồn tại của originWarehouse trước khi truy cập --}}
                                            {{ $shipment->originWarehouse->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- Đảm bảo shipment_date không null trước khi format --}}
                                            {{ $shipment->shipment_date ? $shipment->shipment_date->format('d/m/Y') : 'N/A' }}
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
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('shipments.show', $shipment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                                            <a href="{{ route('shipments.edit', $shipment->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Sửa</a>
                                            <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa chuyến hàng này? Thao tác này sẽ hoàn lại tồn kho nếu chuyến hàng đang trong quá trình vận chuyển!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có chuyến hàng nào được tìm thấy.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $shipments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
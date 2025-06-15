<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Chuyến hàng') . ': ' . $shipment->tracking_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin Chuyến hàng</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Mã Chuyến hàng:</strong> {{ $shipment->tracking_number }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Đơn hàng:</strong>
                                    {{-- Kiểm tra tồn tại của order và customer --}}
                                    @if($shipment->order && $shipment->order->customer)
                                        <a href="{{ route('orders.show', $shipment->order->id) }}" class="text-blue-600 hover:text-blue-900">
                                            #ORD{{ str_pad($shipment->order->id, 5, '0', STR_PAD_LEFT) }} (Khách hàng: {{ $shipment->order->customer->name }})
                                        </a>
                                    @else
                                        N/A (Đơn hàng đã bị xóa hoặc không hợp lệ)
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Phương tiện:</strong>
                                    {{-- Kiểm tra tồn tại của vehicle --}}
                                    @if($shipment->vehicle)
                                        <a href="{{ route('vehicles.show', $shipment->vehicle->id) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $shipment->vehicle->license_plate }} ({{ $shipment->vehicle->make }} {{ $shipment->vehicle->model }})
                                        </a>
                                    @else
                                        Chưa gán
                                    @endif
                                </p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Kho xuất phát:</strong>
                                    {{-- Kiểm tra tồn tại của originWarehouse --}}
                                    {{ $shipment->originWarehouse->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Kho đích:</strong>
                                    {{-- Kiểm tra tồn tại của destinationWarehouse --}}
                                    {{ $shipment->destinationWarehouse->name ?? 'Giao thẳng tới khách hàng' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Địa chỉ giao hàng (Đơn hàng):</strong>
                                    {{-- Kiểm tra tồn tại của order trước khi truy cập shipping_address --}}
                                    {{ $shipment->order->shipping_address ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Ngày Xuất hàng:</strong> {{ $shipment->shipment_date ? $shipment->shipment_date->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Ngày Giao hàng dự kiến:</strong> {{ $shipment->delivery_date ? $shipment->delivery_date->format('d/m/Y') : 'Chưa xác định' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Trạng thái:</strong>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($shipment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($shipment->status == 'in_transit') bg-blue-100 text-blue-800
                                        @elseif($shipment->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($shipment->status == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p><strong class="font-medium">Ghi chú:</strong> {{ $shipment->notes ?? 'Không có.' }}</p>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Các sản phẩm trong Đơn hàng này</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá tại ĐH</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Kiểm tra tồn tại của order và orderItems trước khi lặp --}}
                                @if($shipment->order && $shipment->order->orderItems->count() > 0)
                                    @foreach ($shipment->order->orderItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Sản phẩm không tồn tại' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->sku ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-base font-bold text-gray-900">Tổng số lượng sản phẩm:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900">{{ $shipment->order->orderItems->sum('quantity') }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-lg font-bold text-gray-900">Tổng tiền đơn hàng:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">{{ number_format($shipment->order->total_amount, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có sản phẩm nào trong đơn hàng này hoặc đơn hàng không tồn tại.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>


                    <div class="mb-4 text-sm text-gray-500">
                        <p>Ngày tạo: {{ $shipment->created_at->format('d/m/Y H:i') }}</p>
                        <p>Cập nhật lần cuối: {{ $shipment->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('shipments.edit', $shipment->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('shipments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
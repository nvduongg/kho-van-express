<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Đơn hàng') . ': #ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin Đơn hàng</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Mã Đơn hàng:</strong> #ORD{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Khách hàng:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Ngày Đặt hàng:</strong> {{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : 'N/A' }}</p>                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Trạng thái:</strong>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Địa chỉ Giao hàng:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Thành phố:</strong> {{ $order->shipping_city ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Tỉnh/Bang:</strong> {{ $order->shipping_state ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Mã bưu chính:</strong> {{ $order->shipping_zip_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p><strong class="font-medium">Ghi chú:</strong> {{ $order->notes ?? 'Không có.' }}</p>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Các mục trong Đơn hàng</h3>
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($order->orderItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->sku }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có sản phẩm nào trong đơn hàng này.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-base font-bold text-gray-900">Tổng số lượng sản phẩm:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900">{{ $order->orderItems->sum('quantity') }}</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-lg font-bold text-gray-900">Tổng tiền đơn hàng:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4 text-sm text-gray-500">
                        <p>Ngày tạo: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p>Cập nhật lần cuối: {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('orders.edit', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
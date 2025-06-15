<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Đơn hàng') . ': #ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin Đơn hàng</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-4">
                                    <x-input-label for="customer_id" :value="__('Khách hàng')" />
                                    <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="order_date" :value="__('Ngày Đặt hàng')" />
                                    <x-text-input id="order_date" class="block mt-1 w-full" type="date" name="order_date" :value="old('order_date', $order->order_date ? $order->order_date->format('Y-m-d') : '')" required />                                    <x-input-error :messages="$errors->get('order_date')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="status" :value="__('Trạng thái')" />
                                    <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        @foreach($orderStatuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <x-input-label for="shipping_address" :value="__('Địa chỉ Giao hàng')" />
                                    <x-text-input id="shipping_address" class="block mt-1 w-full" type="text" name="shipping_address" :value="old('shipping_address', $order->shipping_address)" />
                                    <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="shipping_city" :value="__('Thành phố')" />
                                    <x-text-input id="shipping_city" class="block mt-1 w-full" type="text" name="shipping_city" :value="old('shipping_city', $order->shipping_city)" />
                                    <x-input-error :messages="$errors->get('shipping_city')" class="mt-2" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <x-input-label for="shipping_state" :value="__('Tỉnh/Bang')" />
                                        <x-text-input id="shipping_state" class="block mt-1 w-full" type="text" name="shipping_state" :value="old('shipping_state', $order->shipping_state)" />
                                        <x-input-error :messages="$errors->get('shipping_state')" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-input-label for="shipping_zip_code" :value="__('Mã bưu chính')" />
                                        <x-text-input id="shipping_zip_code" class="block mt-1 w-full" type="text" name="shipping_zip_code" :value="old('shipping_zip_code', $order->shipping_zip_code)" />
                                        <x-input-error :messages="$errors->get('shipping_zip_code')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Ghi chú')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $order->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Các mục trong Đơn hàng</h3>
                        <div id="order-items-container">
                            @forelse (old('product_ids', $order->orderItems->pluck('product_id')->toArray()) as $index => $product_id)
                                @php
                                    $current_product_id = old('product_ids.' . $index, $product_id);
                                    $current_quantity = old('quantities.' . $index, $order->orderItems->get($index)->quantity ?? 1);
                                    $product_data = $products->firstWhere('id', $current_product_id);
                                    $current_price = $product_data->price ?? 0;
                                @endphp
                                <div class="flex items-center gap-4 mb-4 order-item-row p-4 border border-gray-200 rounded-md bg-gray-50">
                                    <div class="flex-grow">
                                        <x-input-label :value="__('Sản phẩm')" />
                                        <select name="product_ids[]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 product-select" required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}" data-price="{{ $prod->price }}" {{ $prod->id == $current_product_id ? 'selected' : '' }}>
                                                    {{ $prod->name }} (SKU: {{ $prod->sku }}) - {{ number_format($prod->price, 0, ',', '.') }} VNĐ
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('product_ids.' . $index)" class="mt-2" />
                                    </div>
                                    <div class="w-24">
                                        <x-input-label :value="__('Số lượng')" />
                                        <x-text-input type="number" name="quantities[]" class="block mt-1 w-full quantity-input" min="1" value="{{ $current_quantity }}" required />
                                        <x-input-error :messages="$errors->get('quantities.' . $index)" class="mt-2" />
                                    </div>
                                    <div class="w-32">
                                        <x-input-label :value="__('Tổng cộng')" />
                                        <span class="block mt-1 w-full py-2 px-3 text-gray-700 total-price">{{ number_format($current_price * $current_quantity, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md remove-item">Xóa</button>
                                </div>
                            @empty
                                <div class="flex items-center gap-4 mb-4 order-item-row p-4 border border-gray-200 rounded-md bg-gray-50">
                                    <div class="flex-grow">
                                        <x-input-label :value="__('Sản phẩm')" />
                                        <select name="product_ids[]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 product-select" required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }} (SKU: {{ $product->sku }}) - {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <x-input-label :value="__('Số lượng')" />
                                        <x-text-input type="number" name="quantities[]" class="block mt-1 w-full quantity-input" min="1" value="1" required />
                                    </div>
                                    <div class="w-32">
                                        <x-input-label :value="__('Tổng cộng')" />
                                        <span class="block mt-1 w-full py-2 px-3 text-gray-700 total-price">0 VNĐ</span>
                                    </div>
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md remove-item">Xóa</button>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" id="add-item" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mt-4">
                            Thêm Sản phẩm
                        </button>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Đơn hàng') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('order-items-container');
            const addItemButton = document.getElementById('add-item');
            const productOptions = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name . ' (SKU: ' . $p->sku . ')', 'price' => $p->price])->toArray());

            function updateTotalPrice(row) {
                const productSelect = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const totalPriceSpan = row.querySelector('.total-price');

                const selectedProductId = productSelect.value;
                const quantity = parseInt(quantityInput.value) || 0;

                const selectedProduct = productOptions.find(p => p.id == selectedProductId);
                if (selectedProduct) {
                    const price = selectedProduct.price;
                    const total = price * quantity;
                    totalPriceSpan.textContent = total.toLocaleString('vi-VN') + ' VNĐ';
                } else {
                    totalPriceSpan.textContent = '0 VNĐ';
                }
            }

            function addEventListenersToRow(row) {
                row.querySelector('.product-select').addEventListener('change', () => updateTotalPrice(row));
                row.querySelector('.quantity-input').addEventListener('input', () => updateTotalPrice(row));
                row.querySelector('.remove-item').addEventListener('click', function () {
                    row.remove();
                });
                updateTotalPrice(row); // Initial calculation
            }

            // Add event listeners to existing rows (in case of old input or loaded data)
            container.querySelectorAll('.order-item-row').forEach(row => {
                addEventListenersToRow(row);
            });

            addItemButton.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.className = 'flex items-center gap-4 mb-4 order-item-row p-4 border border-gray-200 rounded-md bg-gray-50';
                newRow.innerHTML = `
                    <div class="flex-grow">
                        <x-input-label :value="__('Sản phẩm')" />
                        <select name="product_ids[]" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 product-select" required>
                            <option value="">Chọn sản phẩm</option>
                            ${productOptions.map(product => `<option value="${product.id}" data-price="${product.price}">${product.name} (SKU: ${product.sku}) - ${product.price.toLocaleString('vi-VN')} VNĐ</option>`).join('')}
                        </select>
                    </div>
                    <div class="w-24">
                        <x-input-label :value="__('Số lượng')" />
                        <x-text-input type="number" name="quantities[]" class="block mt-1 w-full quantity-input" min="1" value="1" required />
                    </div>
                    <div class="w-32">
                        <x-input-label :value="__('Tổng cộng')" />
                        <span class="block mt-1 w-full py-2 px-3 text-gray-700 total-price">0 VNĐ</span>
                    </div>
                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md remove-item">Xóa</button>
                `;
                container.appendChild(newRow);
                addEventListenersToRow(newRow);
            });
        });
    </script>
    @endpush
</x-app-layout>
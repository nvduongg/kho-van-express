<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Mục Tồn kho') . ': ' . $inventory->product->name . ' tại ' . $inventory->warehouse->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="product_id" :value="__('Sản phẩm')" />
                            <select id="product_id" name="product_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 bg-gray-100 cursor-not-allowed" disabled>
                                <option value="{{ $inventory->product->id }}" selected>
                                    {{ $inventory->product->name }} (SKU: {{ $inventory->product->sku }})
                                </option>
                            </select>
                            {{-- Gửi product_id qua hidden input vì select bị disabled --}}
                            <input type="hidden" name="product_id" value="{{ $inventory->product->id }}">
                            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="warehouse_id" :value="__('Kho hàng')" />
                            <select id="warehouse_id" name="warehouse_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 bg-gray-100 cursor-not-allowed" disabled>
                                <option value="{{ $inventory->warehouse->id }}" selected>
                                    {{ $inventory->warehouse->name }} ({{ $inventory->warehouse->address }})
                                </option>
                            </select>
                            {{-- Gửi warehouse_id qua hidden input vì select bị disabled --}}
                            <input type="hidden" name="warehouse_id" value="{{ $inventory->warehouse->id }}">
                            <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="quantity" :value="__('Số lượng tồn kho')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', $inventory->quantity)" required min="0" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="min_stock_level" :value="__('Mức tồn kho tối thiểu')" />
                            <x-text-input id="min_stock_level" class="block mt-1 w-full" type="number" name="min_stock_level" :value="old('min_stock_level', $inventory->min_stock_level)" min="0" />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="max_stock_level" :value="__('Mức tồn kho tối đa (Tùy chọn)')" />
                            <x-text-input id="max_stock_level" class="block mt-1 w-full" type="number" name="max_stock_level" :value="old('max_stock_level', $inventory->max_stock_level)" min="0" />
                            <x-input-error :messages="$errors->get('max_stock_level')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Mục Tồn kho') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
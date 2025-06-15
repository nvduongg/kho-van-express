<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Mục Tồn kho mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('inventory.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="product_id" :value="__('Sản phẩm')" />
                            <select id="product_id" name="product_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Chọn sản phẩm</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="warehouse_id" :value="__('Kho hàng')" />
                            <select id="warehouse_id" name="warehouse_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Chọn kho hàng</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }} ({{ $warehouse->address }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('warehouse_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="quantity" :value="__('Số lượng tồn kho')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity')" required min="0" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="min_stock_level" :value="__('Mức tồn kho tối thiểu')" />
                            <x-text-input id="min_stock_level" class="block mt-1 w-full" type="number" name="min_stock_level" :value="old('min_stock_level', 0)" min="0" />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="max_stock_level" :value="__('Mức tồn kho tối đa (Tùy chọn)')" />
                            <x-text-input id="max_stock_level" class="block mt-1 w-full" type="number" name="max_stock_level" :value="old('max_stock_level')" min="0" />
                            <x-input-error :messages="$errors->get('max_stock_level')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Thêm Mục Tồn kho') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
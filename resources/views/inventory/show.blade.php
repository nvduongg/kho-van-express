<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Mục Tồn kho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p><strong class="font-medium">Sản phẩm:</strong> {{ $inventory->product->name }} (SKU: {{ $inventory->product->sku }})</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Kho hàng:</strong> {{ $inventory->warehouse->name }} ({{ $inventory->warehouse->address }})</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Số lượng tồn kho:</strong>
                            <span class="font-bold {{ $inventory->quantity < $inventory->min_stock_level ? 'text-red-600' : 'text-green-600' }}">
                                {{ $inventory->quantity }} {{ $inventory->product->unit ?? 'đơn vị' }}
                            </span>
                        </p>
                        @if ($inventory->quantity < $inventory->min_stock_level)
                            <p class="text-sm text-red-500 mt-1">Sản phẩm này đang dưới mức tồn kho tối thiểu!</p>
                        @endif
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Mức tồn kho tối thiểu:</strong> {{ $inventory->min_stock_level }} {{ $inventory->product->unit ?? 'đơn vị' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Mức tồn kho tối đa:</strong> {{ $inventory->max_stock_level ?? 'Không đặt' }} {{ $inventory->product->unit ?? 'đơn vị' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Ngày tạo:</strong> {{ $inventory->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Cập nhật lần cuối:</strong> {{ $inventory->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
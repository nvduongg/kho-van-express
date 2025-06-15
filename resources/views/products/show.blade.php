<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Sản phẩm') . ': ' . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="flex-shrink-0">
                            @if ($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded-md shadow-md">
                            @else
                                <img src="{{ asset('images/default_product.png') }}" alt="No Image" class="w-48 h-48 object-cover rounded-md shadow-md border border-gray-200">
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ $product->description ?? 'Không có mô tả.' }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                                <div>
                                    <p><strong class="font-medium">SKU:</strong> {{ $product->sku }}</p>
                                    <p><strong class="font-medium">Giá:</strong> {{ number_format($product->price ?? 0, 0, ',', '.') }} VNĐ</p>
                                    <p><strong class="font-medium">Đơn vị:</strong> {{ $product->unit ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p><strong class="font-medium">Trọng lượng:</strong> {{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</p>
                                    <p><strong class="font-medium">Kích thước (DxRxC):</strong>
                                        @if ($product->length && $product->width && $product->height)
                                            {{ $product->length }}x{{ $product->width }}x{{ $product->height }} cm
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p><strong class="font-medium">Trạng thái:</strong>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Ngày tạo: {{ $product->created_at->format('d/m/Y H:i') }}</p>
                                <p>Cập nhật cuối: {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
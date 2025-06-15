<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Sản phẩm') . ': ' . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-4">
                                    <x-input-label for="sku" :value="__('Mã SKU')" />
                                    <x-text-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku', $product->sku)" required autofocus />
                                    <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="name" :value="__('Tên Sản phẩm')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="price" :value="__('Giá bán (VNĐ)')" />
                                    <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $product->price)" />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="unit" :value="__('Đơn vị tính')" />
                                    <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $product->unit)" placeholder="Ví dụ: cái, hộp, kg" />
                                    <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <x-input-label for="weight" :value="__('Trọng lượng (kg)')" />
                                    <x-text-input id="weight" class="block mt-1 w-full" type="number" step="0.01" name="weight" :value="old('weight', $product->weight)" />
                                    <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="length" :value="__('Chiều dài (cm)')" />
                                    <x-text-input id="length" class="block mt-1 w-full" type="number" step="0.01" name="length" :value="old('length', $product->length)" />
                                    <x-input-error :messages="$errors->get('length')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="width" :value="__('Chiều rộng (cm)')" />
                                    <x-text-input id="width" class="block mt-1 w-full" type="number" step="0.01" name="width" :value="old('width', $product->width)" />
                                    <x-input-error :messages="$errors->get('width')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="height" :value="__('Chiều cao (cm)')" />
                                    <x-text-input id="height" class="block mt-1 w-full" type="number" step="0.01" name="height" :value="old('height', $product->height)" />
                                    <x-input-error :messages="$errors->get('height')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Ảnh Sản phẩm')" />
                            @if ($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded-md mb-2">
                                <label for="remove_image" class="inline-flex items-center">
                                    <input id="remove_image" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remove_image" value="1">
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Xóa ảnh hiện tại') }}</span>
                                </label>
                            @else
                                <p class="text-sm text-gray-500 mb-2">Chưa có ảnh sản phẩm.</p>
                            @endif
                            <input id="image" type="file" name="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Hoạt động') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Sản phẩm') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
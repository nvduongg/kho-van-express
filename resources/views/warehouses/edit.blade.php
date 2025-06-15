<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Kho hàng') . ': ' . $warehouse->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Sử dụng phương thức PUT cho cập nhật --}}

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Tên Kho')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $warehouse->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Địa chỉ')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $warehouse->address)" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <x-input-label for="city" :value="__('Thành phố')" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $warehouse->city)" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="state" :value="__('Tỉnh/Bang')" />
                                <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state', $warehouse->state)" />
                                <x-input-error :messages="$errors->get('state')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="zip_code" :value="__('Mã bưu điện')" />
                                <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code', $warehouse->zip_code)" />
                                <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $warehouse->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Hoạt động') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Kho') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
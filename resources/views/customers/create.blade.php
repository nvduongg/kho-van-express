<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tạo Khách hàng mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-4">
                                    <x-input-label for="name" :value="__('Tên Khách hàng')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="phone" :value="__('Số điện thoại')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <x-input-label for="address" :value="__('Địa chỉ')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="mb-4">
                                        <x-input-label for="city" :value="__('Thành phố')" />
                                        <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
                                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-input-label for="state" :value="__('Tỉnh/Bang')" />
                                        <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" />
                                        <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                    </div>
                                    <div class="mb-4">
                                        <x-input-label for="zip_code" :value="__('Mã bưu chính')" />
                                        <x-text-input id="zip_code" class="block mt-1 w-full" type="text" name="zip_code" :value="old('zip_code')" />
                                        <x-input-error :messages="$errors->get('zip_code')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="notes" :value="__('Ghi chú')" />
                                    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Tạo Khách hàng') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
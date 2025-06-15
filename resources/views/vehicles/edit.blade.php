<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Phương tiện') . ': ' . $vehicle->license_plate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-4">
                                    <x-input-label for="license_plate" :value="__('Biển số xe')" />
                                    <x-text-input id="license_plate" class="block mt-1 w-full" type="text" name="license_plate" :value="old('license_plate', $vehicle->license_plate)" required autofocus />
                                    <x-input-error :messages="$errors->get('license_plate')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="make" :value="__('Hãng xe')" />
                                    <x-text-input id="make" class="block mt-1 w-full" type="text" name="make" :value="old('make', $vehicle->make)" required />
                                    <x-input-error :messages="$errors->get('make')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="model" :value="__('Mẫu xe')" />
                                    <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model', $vehicle->model)" required />
                                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="type" :value="__('Loại phương tiện')" />
                                    <select id="type" name="type" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn loại</option>
                                        @foreach($vehicleTypes as $type)
                                            <option value="{{ $type }}" {{ old('type', $vehicle->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <x-input-label for="capacity_weight" :value="__('Tải trọng (tấn)')" />
                                    <x-text-input id="capacity_weight" class="block mt-1 w-full" type="number" step="0.01" name="capacity_weight" :value="old('capacity_weight', $vehicle->capacity_weight)" min="0" />
                                    <x-input-error :messages="$errors->get('capacity_weight')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="capacity_volume" :value="__('Thể tích (m³)')" />
                                    <x-text-input id="capacity_volume" class="block mt-1 w-full" type="number" step="0.01" name="capacity_volume" :value="old('capacity_volume', $vehicle->capacity_volume)" min="0" />
                                    <x-input-error :messages="$errors->get('capacity_volume')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="status" :value="__('Trạng thái')" />
                                    <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn trạng thái</option>
                                        @foreach($vehicleStatuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $vehicle->status) == $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="notes" :value="__('Ghi chú')" />
                                    <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $vehicle->notes) }}</textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Phương tiện') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
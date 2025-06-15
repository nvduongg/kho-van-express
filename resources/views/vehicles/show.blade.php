<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Phương tiện') . ': ' . $vehicle->license_plate }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Biển số xe:</strong> {{ $vehicle->license_plate }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Hãng xe:</strong> {{ $vehicle->make }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Mẫu xe:</strong> {{ $vehicle->model }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Loại phương tiện:</strong> {{ $vehicle->type }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Tải trọng:</strong> {{ $vehicle->capacity_weight ? $vehicle->capacity_weight . ' tấn' : 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Thể tích:</strong> {{ $vehicle->capacity_volume ? $vehicle->capacity_volume . ' m³' : 'N/A' }}</p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Trạng thái:</strong>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($vehicle->status == 'available') bg-green-100 text-green-800
                                        @elseif($vehicle->status == 'in_use') bg-blue-100 text-blue-800
                                        @elseif($vehicle->status == 'maintenance') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-4">
                                <p><strong class="font-medium">Ghi chú:</strong> {{ $vehicle->notes ?? 'Không có.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 mt-4 text-sm text-gray-500">
                        <p>Ngày tạo: {{ $vehicle->created_at->format('d/m/Y H:i') }}</p>
                        <p>Cập nhật lần cuối: {{ $vehicle->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
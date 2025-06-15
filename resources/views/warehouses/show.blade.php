<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Kho hàng') . ': ' . $warehouse->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p><strong class="font-medium">Tên Kho:</strong> {{ $warehouse->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Địa chỉ:</strong> {{ $warehouse->address }}, {{ $warehouse->city }}, {{ $warehouse->state }} {{ $warehouse->zip_code }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Mô tả:</strong> {{ $warehouse->description ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Trạng thái:</strong>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $warehouse->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $warehouse->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Ngày tạo:</strong> {{ $warehouse->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Cập nhật lần cuối:</strong> {{ $warehouse->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('warehouses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
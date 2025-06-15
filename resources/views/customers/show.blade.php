<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết Khách hàng') . ': ' . $customer->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p><strong class="font-medium">Tên Khách hàng:</strong> {{ $customer->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Email:</strong> {{ $customer->email }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Số điện thoại:</strong> {{ $customer->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Địa chỉ:</strong> {{ $customer->address ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Thành phố:</strong> {{ $customer->city ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Tỉnh/Bang:</strong> {{ $customer->state ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Mã bưu chính:</strong> {{ $customer->zip_code ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Ghi chú:</strong> {{ $customer->notes ?? 'Không có.' }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Ngày tạo:</strong> {{ $customer->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <p><strong class="font-medium">Cập nhật lần cuối:</strong> {{ $customer->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                            {{ __('Chỉnh sửa') }}
                        </a>
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Quay lại Danh sách') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Khách hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Danh sách Khách hàng</h3>
                        <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Thêm Khách hàng mới
                        </a>
                    </div>

                    {{-- Thông báo thành công --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Thành công!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.697l-2.651 3.152a1.2 1.2 0 1 1-1.697-1.697l3.152-2.651-3.152-2.651a1.2 1.2 0 1 1 1.697-1.697l2.651 3.152 2.651-3.152a1.2 1.2 0 1 1 1.697 1.697l-3.152 2.651 3.152 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
                            </span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Khách hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điện thoại</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->phone ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $customer->address }}, {{ $customer->city }}@if($customer->state), {{ $customer->state }}@endif @if($customer->zip_code) ({{ $customer->zip_code }})@endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('customers.show', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Sửa</a>
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng này? Thao tác này có thể ảnh hưởng đến các đơn hàng liên quan!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có khách hàng nào được tìm thấy.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
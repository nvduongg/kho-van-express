<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Tồn kho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Danh sách Tồn kho</h3>
                        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Thêm Mục Tồn kho mới
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

                    {{-- Filter Form --}}
                    <form action="{{ route('inventory.index') }}" method="GET" class="mb-4 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Lọc theo Kho:</label>
                                <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả kho</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Lọc theo Sản phẩm:</label>
                                <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tất cả sản phẩm</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (SKU: {{ $product->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Tìm kiếm
                                </button>
                                @if(request('warehouse_id') || request('product_id'))
                                    <a href="{{ route('inventory.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Xóa lọc
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kho hàng</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng tồn</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mức tối thiểu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($inventoryItems as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item->product->name }} (SKU: {{ $item->product->sku }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->warehouse->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-bold {{ $item->quantity < $item->min_stock_level ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $item->quantity }} {{ $item->product->unit }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->min_stock_level }} {{ $item->product->unit }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('inventory.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Xem</a>
                                            <a href="{{ route('inventory.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Sửa</a>
                                            <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa mục tồn kho này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có mục tồn kho nào được tìm thấy.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $inventoryItems->links() }} {{-- Hiển thị phân trang --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
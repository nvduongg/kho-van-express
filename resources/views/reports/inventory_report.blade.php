<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Báo cáo Tồn kho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tình hình Tồn kho Sản phẩm tại các Kho</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    @foreach($warehouses as $warehouse)
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $warehouse->name }}</th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng Tồn kho</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reportData as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product['sku'] }}</td>
                                        @foreach($warehouses as $warehouse)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                {{ $product['warehouse_stocks'][$warehouse->id] ?? 0 }} {{ $product['unit'] }}
                                            </td>
                                        @endforeach
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                            {{ $product['total_stock'] }} {{ $product['unit'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + $warehouses->count() }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có dữ liệu tồn kho nào được tìm thấy.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50">
                                    <td colspan="2" class="px-6 py-4 text-right text-base font-bold text-gray-900">Tổng tồn kho tất cả sản phẩm:</td>
                                    @foreach($warehouses as $warehouse)
                                        @php
                                            $totalStock = 0;
                                            foreach ($reportData as $product) {
                                                $totalStock += $product['warehouse_stocks'][$warehouse->id] ?? 0;
                                            }
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900 text-center">
                                            {{ $totalStock }}
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900 text-right">
                                        {{ array_sum(array_column($reportData, 'total_stock')) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
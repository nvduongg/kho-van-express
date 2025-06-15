<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Báo cáo Doanh thu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Doanh thu theo thời gian</h3>

                    {{-- Form Lọc --}}
                    <form action="{{ route('reports.revenue') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700">Theo:</label>
                                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Ngày</option>
                                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Tuần</option>
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Tháng</option>
                                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Năm</option>
                                </select>
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Từ ngày:</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Đến ngày:</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Xem Báo cáo
                                </button>
                                <a href="{{ route('reports.revenue') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Đặt lại
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Biểu đồ (Sử dụng Chart.js) --}}
                    <div class="mb-8">
                        <canvas id="revenueChart"></canvas>
                    </div>

                    {{-- Bảng dữ liệu chi tiết --}}
                    <h4 class="text-md font-medium text-gray-800 mb-3">Dữ liệu chi tiết:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reportData as $label => $amount)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($amount, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Không có dữ liệu doanh thu trong khoảng thời gian đã chọn.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50">
                                    <td class="px-6 py-4 text-right text-base font-bold text-gray-900">Tổng cộng:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-gray-900 text-right">
                                        {{ number_format(array_sum($data), 0, ',', '.') }} VNĐ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Thêm thư viện Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Có thể thay đổi thành 'line', 'pie' tùy ý
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($data),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Biểu đồ Doanh thu'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return value.toLocaleString('vi-VN') + ' VNĐ'; // Định dạng tiền tệ Việt Nam
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Thời gian'
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return tooltipItem.yLabel.toLocaleString('vi-VN') + ' VNĐ';
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
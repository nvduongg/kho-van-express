<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Chuyến hàng') . ': ' . $shipment->tracking_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="mb-4">
                                    <x-input-label for="order_id" :value="__('Đơn hàng')" />
                                    <select id="order_id" name="order_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn đơn hàng</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id', $shipment->order_id) == $order->id ? 'selected' : '' }}>
                                                #ORD{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} ({{ $order->customer->name }}) - {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('order_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="vehicle_id" :value="__('Phương tiện (Tùy chọn)')" />
                                    <select id="vehicle_id" name="vehicle_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Không gán phương tiện</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $shipment->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->license_plate }} ({{ $vehicle->make }} {{ $vehicle->model }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="origin_warehouse_id" :value="__('Kho xuất phát')" />
                                    <select id="origin_warehouse_id" name="origin_warehouse_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn kho xuất</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('origin_warehouse_id', $shipment->origin_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('origin_warehouse_id')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="destination_warehouse_id" :value="__('Kho đích (Tùy chọn)')" />
                                    <select id="destination_warehouse_id" name="destination_warehouse_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Giao thẳng đến khách hàng / Không có kho đích</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('destination_warehouse_id', $shipment->destination_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('destination_warehouse_id')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <x-input-label for="tracking_number" :value="__('Mã Theo dõi (Tùy chọn)')" />
                                    <x-text-input id="tracking_number" class="block mt-1 w-full" type="text" name="tracking_number" :value="old('tracking_number', $shipment->tracking_number)" />
                                    <x-input-error :messages="$errors->get('tracking_number')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="shipment_date" :value="__('Ngày Xuất hàng')" />
                                    {{-- Kiểm tra null trước khi format --}}
                                    <x-text-input id="shipment_date" class="block mt-1 w-full" type="date" name="shipment_date" :value="old('shipment_date', $shipment->shipment_date ? $shipment->shipment_date->format('Y-m-d') : '')" required />
                                    <x-input-error :messages="$errors->get('shipment_date')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="delivery_date" :value="__('Ngày Giao hàng dự kiến (Tùy chọn)')" />
                                    {{-- Kiểm tra null trước khi format --}}
                                    <x-text-input id="delivery_date" class="block mt-1 w-full" type="date" name="delivery_date" :value="old('delivery_date', $shipment->delivery_date ? $shipment->delivery_date->format('Y-m-d') : '')" />
                                    <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="status" :value="__('Trạng thái')" />
                                    <select id="status" name="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        @foreach($shipmentStatuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $shipment->status) == $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="notes" :value="__('Ghi chú')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes', $shipment->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Cập nhật Chuyến hàng') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">order detail</h2>
                    <div class="border-t-2">
                        <ul class="list-none p-4 overflow-y-auto" id="order-detail">
                            <li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                <span class="w-[20%] text-center">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[30%] text-center">Price</span>
                            </li>
                        </ul>
                    </div>
                    <div class="border-t-2 text-end font-black capitalize py-4 text-xl">total price: <span id="order-total-price"></span></div>
                </div>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div
            class="notify fixed top-2 right-2 w-fit h-fit bg-white py-3 px-6 sm:rounded-e-lg z-50 max-w-[300px] border-s-4 border-green-400">
            <div class="text-center text-sm">{{ session('success') }}</div>
        </div>
    @endif
</x-app-layout>
<script>

    const renderOrderDetail = (orders) => {
        const orderDetail = $("#order-detail");
        let cartTotalPrice = 0;

        if (orders) {
            orderDetail.html(`<li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                <span class="w-[20%] text-center">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[30%] text-center">Price</span>
                            </li>`);
        }

        console.log(orders);

        orders && orders.length > 0 && orders.forEach((item, index) => {
            cartTotalPrice += item.total_price;
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-full text-lg">
                    <span class="w-[20%] text-center">${index + 1}</span>
                    <span class="w-[30%] text-center">${item.product_name}</span>
                    <span class="w-[20%] text-center">${item.quantity}</span>
                    <span id="price-${item.product_id}" class="w-[30%] text-center">${item.total_price}</span>
                </li>`;
            orderDetail.html(orderDetail.html() + htmlContent);
        });
        $('#order-total-price').html(cartTotalPrice);
    }

    const getOrderDetail = async () => {
        const url = '/api/order/order-detail/{{ $orderId }}';

        console.log(url);

        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            console.log(response.data);
            renderOrderDetail(response.data[0].order_item);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getOrderDetail();

</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div id="step-1" class="step-content bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">list ordered</h2>
                    <ul id="list-orders" class="border-t-2 p-6">

                    </ul>
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
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    const renderListOrder = (orders) => {
        const listOrders = $('#list-orders');

        orders && orders.length > 0 && orders.forEach(item => {
            const htmlContent =
                `<li id="order-${item.id}" class="shadow-[0px_0px_15px_0_rgb(0,0,0,0.4)] flex justify-between items-center rounded gap-[5%] text-xl mb-4">
                    <div class="w-[85%] flex justify-between items-center ps-6 py-6">
                        <div>Order Time: ${item.order_time}</div>
                        <div>Price: ${item.total_price}</div>
                    </div>
                    <a href="{{ route('order-detail', ['id' => '']) }}/${item.id}" class="w-[10%] text-center py-6 bg-green-500 text-white cursor-pointer">View</a>
                </li>`;
            listOrders.html(listOrders.html() + htmlContent);
        });
    }

    const getListOrder = async () => {
        const url = '/api/order/list-order';

        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            renderListOrder(response.data);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getListOrder();

</script>

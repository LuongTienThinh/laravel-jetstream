<?php
$page = 1;
$search = '';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$url = request()->path();

?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div id="step-1" class="step-content bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">invoice payment</h2>
                    <div class="border-t-2">
                        <ul class="list-none p-4 overflow-y-auto" id="product-list">
                            <li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                <span class="w-[20%] text-center">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[30%] text-center">Price</span>
                            </li>
                        </ul>
                    </div>
                    <div class="border-t-2 text-end font-black capitalize py-4 text-xl">total price: <span id="cart-total-price"></span></div>
                    <div class="text-end"><button id="submit-step-1" class="submit-step px-10 py-4 h-fit rounded bg-blue-400 text-white text-center">Next</button></div>
                </div>
                <div id="step-2" class="step-content hidden bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">payment method</h2>
                    <div id="payment-method" class="border-t-2 py-4 text-xl flex flex-col gap-2">
                    </div>
                    <div class="text-end pt-4 border-t-2"><button id="submit-step-2" class="submit-step px-10 py-4 h-fit rounded bg-blue-400 text-white text-center">Next</button></div>
                </div>
                <div id="step-3" class="step-content hidden bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">invoice payment</h2>
                    <div class="border-t-2 text-xl py-4">
                        <div>Name: {{ Auth::user()->name }}</div>
                        <div>Email: {{ Auth::user()->email }}</div>
                    </div>
                    <div class="text-xl">List products</div>
                    <div class="border-t-2">
                        <ul class="list-none p-4 overflow-y-auto" id="product-list-confirmed">
                            <li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                <span class="w-[20%] text-center">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[30%] text-center">Price</span>
                            </li>
                        </ul>
                    </div>
                    <div class="border-t-2 text-end font-black capitalize pt-4 text-xl">total price: <span id="cart-total-price-confirmed"></span></div>
                    <div class="text-end font-black capitalize py-4 text-xl">payment method: <span id="payment-method-step-3"></span></div>
                    <div class="text-end"><button id="finish-checkout" class="px-10 py-4 h-fit rounded bg-blue-400 text-white text-center">Finish</button></div>
                </div>
            </div>
        </div>
        <div id="product-area"></div>
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

    let paymentMethods = [];
    let orderData = {};

    const handleStep = () => {
        const submits = $('.submit-step');
        const finishCheckout = $('#finish-checkout');

        submits.each(function () {
            $(this).on('click', () => {
                const step = parseInt(this.id.split('-')[2]);

                $(`#step-${step}`).addClass('hidden');
                $(`#step-${step + 1}`).removeClass('hidden');

                if (step === 2) {
                    const method = paymentMethods.find(item => item.id === orderData.payment_method_id).method;
                    console.log(orderData);
                    $('#payment-method-step-3').html(method);
                }
            });
        });

        finishCheckout.on('click', async () => {
            const url = `http://127.0.0.1:8000/api/payment/create-order`;
            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(orderData),

                success: function(response) {
                    window.location.href = '{{ route('cart') }}';
                    console.log('Tạo đơn hàng thành công', response);
                },
                error: function(error) {
                    console.error('Lỗi khi tạo đơn hàng', error);
                }
            });
        });
    }
    handleStep();

    const handleUpdateCartPrice = (newPrice) => {
        $('#cart-total-price').html(newPrice);
        $('#cart-total-price-confirmed').html(newPrice);
    }

    const renderProducts = (products) => {
        const productList = $("#product-list");
        const productListConfirmed = $("#product-list-confirmed");

        let cartTotalPrice = 0;

        if (products) {
            productList.html(`<li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                <span class="w-[20%] text-center">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[30%] text-center">Price</span>
                            </li>`);
            productListConfirmed.html(`<li class="p-2 flex flex-nowrap items-center w-full font-black text-lg">
                                            <span class="w-[20%] text-center">No</span>
                                            <span class="w-[30%] text-center">Product Name</span>
                                            <span class="w-[20%] text-center">Quantity</span>
                                            <span class="w-[30%] text-center">Price</span>
                                        </li>`);
        }

        products.forEach((item, index) => {
            cartTotalPrice += item.total_price;
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-full text-lg">
                    <span class="w-[20%] text-center">${index + 1}</span>
                    <span class="w-[30%] text-center">${item.name}</span>
                    <span class="w-[20%] text-center">${item.quantity}</span>
                    <span id="price-${item.product_id}" class="w-[30%] text-center">${item.total_price}</span>
                </li>`;
            productList.html(productList.html() + htmlContent);
            productListConfirmed.html(productListConfirmed.html() + htmlContent);
        });
        handleUpdateCartPrice(cartTotalPrice);
    }

    const getProductsInCart = async () => {
        const data = {
            page: parseInt('<?php echo $page; ?>'),
            search: '<?php echo $search; ?>'
        };

        const url = `http://127.0.0.1:8000/api/cart?page=${data.page}&search=${data.search}`;
        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            $('#pagination').html("");

            orderData = {
                ...orderData,
                products: response.data.products,
            }
            renderProducts(response.data.products);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getProductsInCart();

    const handleChangePaymentMethod = (event, id) => {
        orderData = {
            ...orderData,
            payment_method_id: id,
        }
        console.log(orderData);
    }

    const renderPaymentMethods = (methods) => {
        const paymentMethod = $('#payment-method');
        methods.forEach((item) => {
            const htmlContent =
                `<div>
                    <label>
                        <input type="radio" name="payment-method" onchange="handleChangePaymentMethod(event, ${item.id})" ${item.method === 'cash' ? 'checked' : ''}>
                        <span id="method-${item.id}" class="capitalize">${item.method}</span>
                    </label>
                </div>`;
            paymentMethod.html(paymentMethod.html() + htmlContent);
        });
    }

    const getPaymentMethods = async () => {
        const url = `http://127.0.0.1:8000/api/payment/method`;
        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            $('#payment-method').html("");

            paymentMethods = [...response.data];
            orderData = {
                ...orderData,
                payment_method_id: paymentMethods.find(item => item.method === 'cash').id
            }
            renderPaymentMethods(paymentMethods);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getPaymentMethods();

</script>

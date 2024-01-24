<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
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
                    <h2 class="uppercase text-center font-black text-4xl mb-6">address</h2>
                    <div id="address" class="border-t-2 py-4 text-xl flex items-center flex-wrap gap-y-4">
                        <div class="flex justify-center items-center w-1/2">
                            <label class="me-4 w-1/4" for="provinces">Province:</label>
                            <select class="w-3/5" name="provinces" id="provinces">
                                <option value="" hidden selected>Select the provinces</option>
                            </select>
                        </div>
                        <div class="flex justify-center items-center w-1/2">
                            <label class="me-4 w-1/4" for="districts">District:</label>
                            <select class="w-3/5" name="districts" id="districts" disabled>
                                <option value="" hidden selected>Select the districts</option>
                            </select>
                        </div>
                        <div class="flex justify-center items-center w-1/2">
                            <label class="me-4 w-1/4" for="wards">Ward:</label>
                            <select class="w-3/5" name="wards" id="wards" disabled>
                                <option value="" hidden selected>Select the wards</option>
                            </select>
                        </div>
                        <div class="flex justify-center items-center w-1/2">
                            <label class="me-4 w-1/4" for="number-address">Address</label>
                            <input class="w-3/5" type="text" name="number-address" id="number-address" placeholder="Enter your number address, street" disabled>
                        </div>
                    </div>
                    <div class="text-end pt-4 border-t-2"><button id="submit-step-2" class="submit-step px-10 py-4 h-fit rounded bg-blue-400 text-white text-center">Next</button></div>
                </div>
                <div id="step-3" class="step-content hidden bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">payment method</h2>
                    <div id="payment-method" class="border-t-2 py-4 text-xl flex flex-col gap-2">
                    </div>
                    <div class="text-end pt-4 border-t-2"><button id="submit-step-3" class="submit-step px-10 py-4 h-fit rounded bg-blue-400 text-white text-center">Next</button></div>
                </div>
                <div id="step-4" class="step-content hidden bill-wrap p-4">
                    <h2 class="uppercase text-center font-black text-4xl mb-6">invoice payment</h2>
                    <div class="border-t-2 text-xl py-4">
                        <div>Name: {{ $user->name }}</div>
                        <div>Email: {{ $user->email }}</div>
                        <div>
                            Address:
                            <span id="user-number_address">{{ $user->number_address }},</span>
                            <span id="user-ward"></span>
                            <span id="user-district"></span>
                            <span id="user-province"></span>
                        </div>
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
                    <div class="text-end font-black capitalize py-4 text-xl">payment method: <span id="payment-method-step-4"></span></div>
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

                if (step === 3) {
                    const method = paymentMethods.find(item => item.id === orderData.payment_method_id).method;
                    $('#payment-method-step-4').html(method);
                } else if (step === 2) {
                    const province = $('#provinces');
                    const district = $('#districts');
                    const ward = $('#wards');
                    const numberAddress = $('#number-address');
                    const data = {
                        'province': province.children(':selected').text() + "#" + province.val(),
                        'district': district.children(':selected').text() + "#" + district.val(),
                        'ward': ward.children(':selected').text() + "#" + ward.val(),
                        'number_address': numberAddress.val(),
                    }
                    const url = `/api/user/address/update`;
                    $.ajax({
                        url: url,
                        method: 'PUT',
                        contentType: 'application/json',
                        data: JSON.stringify(data),

                        success: function(response) {
                            console.log('Cập nhật địa chỉ thành công', response);

                            $('#user-ward').html(ward.children(':selected').text() + ',');
                            $('#user-district').html(district.children(':selected').text() + ',');
                            $('#user-province').html(province.children(':selected').text());
                        },
                        error: function(error) {
                            console.error('Lỗi khi cập nhật địa chỉ', error);
                        }
                    });
                }
            });
        });

        finishCheckout.on('click', async () => {
            const url = `/api/payment/create-order`;
            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(orderData),

                success: function(response) {
                    window.location.href = '{{ route('order') }}';
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

        products && products.forEach((item, index) => {
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
        const url = `/api/cart`;
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
        const url = `/api/payment/method`;
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

    const renderAddresses = (addresses, data, defaultValue) => {
        const selectAddresses = $(`#${addresses}`);

        selectAddresses.html(`<option hidden selected>Select the ${addresses}</option>`);

        data && data.forEach((item) => {
            const htmlContent = defaultValue === item.name
                ? `<option value="${item.code}" selected>${item.name}</option>`
                : `<option value="${item.code}">${item.name}</option>`;

            selectAddresses.html(selectAddresses.html() + htmlContent);
        });
    }

    const getAddresses = async (addresses, code, defaultValue) => {
        const url = addresses === 'provinces'
            ? `https://provinces.open-api.vn/api/`
            : addresses === 'districts'
                ? `https://provinces.open-api.vn/api/p/${code}?depth=2`
                : `https://provinces.open-api.vn/api/d/${code}?depth=2`;
        try {
            let response = await $.ajax({
                url: url,
                method: 'GET',
            });

            response = (Array.isArray(response) ? response : response[addresses]).sort((a, b) => {
                return a.name.localeCompare(b.name);
            });

            renderAddresses(addresses, response, defaultValue);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    const selectAddressesChange = () => {
        const provinces = $('#provinces');
        const districts = $('#districts');
        const wards = $('#wards');
        const address = $('#number-address');

        // Province
        if (`{{ $user->province }}`.length === 0) {
            getAddresses('provinces');
        } else {
            const userProvince = '{{ $user->province }}'.split('#');
            const provinceName = userProvince[0];

            getAddresses('provinces', undefined, provinceName);
            districts.prop('disabled', false);
        }

        // District
        if (`{{ $user->district }}`.length !== 0) {
            const userDistrict = '{{ $user->district }}'.split('#');
            const districtName = userDistrict[0];

            getAddresses('districts', provinces.val() || '{{ $user->province }}'.split('#')[1], districtName);
            wards.prop('disabled', false);
        }

        // Ward
        if (`{{ $user->ward }}`.length !== 0) {
            const userWard = '{{ $user->ward }}'.split('#');
            const wardName = userWard[0];

            getAddresses('wards', districts.val() || '{{ $user->district }}'.split('#')[1], wardName);
            address.prop('disabled', false);
        }

        // Number address
        if (`{{ $user->number_address }}`.length !== 0) {
            address.val(`{{ $user->number_address }}`);
        }

        // Change province
        provinces.on('change', function () {
            districts.prop('disabled', false);
            wards.prop('disabled', true);
            getAddresses('districts', provinces.val());
        });

        // Change district
        districts.on('change', function () {
            wards.prop('disabled', false);
            getAddresses('wards', districts.val());
        });

        // Change ward
        wards.on('change', function () {
            address.prop('disabled', false);
        });
    }
    selectAddressesChange();

</script>

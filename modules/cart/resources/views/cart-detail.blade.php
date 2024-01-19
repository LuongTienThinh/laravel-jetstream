<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="product-wrap p-4">
                    <div class="mb-6">
                        <div class="text-center text-3xl tracking-wider font-black">{{ __('List Products In Cart') }}</div>
                    </div>
                    <div class="border-t-2">
                        <ul class="list-none p-4 overflow-y-auto" id="product-list">
                            <li class="p-2 flex flex-nowrap items-center w-full font-black">
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex items-center justify-evenly border-t-2">
                        <div class="text-center font-black capitalize py-4 text-xl w-3/5">total price: <span id="cart-total-price"></span></div>
                        <a href="@auth {{ route('checkout') }} @else {{ route('login') }} @endif" id="btn-checkout" class="px-10 py-3 h-fit rounded bg-blue-400 text-white text-center">Check out</a>
                    </div>
                    <div class="border-t-2 px-6 pt-4" id="pagination">
                    </div>
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

    const handleEditCart = () => {
        const btnEdits = $('.btn-edit-cart');
        btnEdits.each(function () {
            $(this).on('click', () => {
                const cartId = this.id.split('-')[1];
                const cartItemId = this.id.split('-')[2];

                const [price, quantity] = [
                    $(`#price-${cartItemId}`).html(),
                    $(`#cart-quantity-${cartId}-${cartItemId}`).val(),
                ];

                const url = `/api/cart/edit/${cartItemId}`;
                $.ajax({
                    url: url,
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        product_id: cartItemId,
                        quantity: parseInt(quantity),
                        total_price: parseFloat(price),
                    }),
                    success: function(response) {
                        window.location.href = '{{ route('cart') }}';
                        console.log('Cập nhật thành công', response);
                    },
                    error: function(error) {
                        console.error('Lỗi khi cập nhật', error);
                    }
                });
            });
        });
    }

    const handleRemoveFromCart = () => {
        const btnRemoves = $('.btn-remove-cart');
        btnRemoves.each(function() {
            $(this).on('click', () => {
                const cartItemId = this.id.split('-')[2];

                const url = `/api/cart/delete/${cartItemId}`;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        window.location.href = '{{ route('cart') }}';
                        console.log('Xóa thành công', response);
                    },
                    error: function(error) {
                        console.error('Lỗi khi xóa', error);
                    }
                });
            });
        });
    }

    const handleUpdateCartPrice = (newPrice) => {
        $('#cart-total-price').html(newPrice);
    }

    const handleChangeQuantity = (event, basePrice, baseQuantity) => {
        const id = event.target.id.split('-')[3];
        const cartId = event.target.id.split('-')[2];

        const productPriceHtml = $(`#price-${id}`);

        const prevPrice = parseFloat(productPriceHtml.html());
        const currPrice = basePrice * event.target.value;
        const currCartPrice = parseFloat($('#cart-total-price').html());

        productPriceHtml.html(currPrice);
        handleUpdateCartPrice(currCartPrice - prevPrice + currPrice);

        if (parseInt(event.target.value) !== baseQuantity) {
            $(`#edit-${cartId}-${id}`).removeClass('hidden');
        } else {
            $(`#edit-${cartId}-${id}`).addClass('hidden');
        }
    }

    const renderProducts = (products) => {
        const productList = $("#product-list");
        let cartTotalPrice = 0;

        if (products && products.length > 0) {
            productList.html(`<li class="p-2 flex flex-nowrap items-center w-full font-black">
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>`);
            $('#btn-checkout').removeClass('pointer-events-none opacity-75');
        } else {
            $('#btn-checkout').addClass('pointer-events-none opacity-75');
        }

        products && products.length > 0 && products.forEach((item) => {
            cartTotalPrice += item.total_price;
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-full">
                    <span class="w-[30%] text-center">${item.name}</span>
                    <span class="w-[20%] text-center">
                        <input id="cart-quantity-${item.cart_id}-${item.product_id}" class="w-[70%] text-center" type="number"
                            name="quantity" id="create-quantity" min="1" max="${item.quantity_in_stock}" value="${item.quantity}"
                            onchange="handleChangeQuantity(event, ${item.base_price}, ${item.quantity})" required>
                    </span>
                    <span id="price-${item.product_id}" class="w-[20%] text-center">${item.total_price}</span>
                    <div class="action w-[30%] text-center">
                        <button id="remove-${item.cart_id}-${item.product_id}" class="btn-remove-cart px-6 py-2 rounded bg-red-400 text-white">{{ __('Remove from cart') }}</button>
                        <button id="edit-${item.cart_id}-${item.product_id}" class="hidden btn-edit-cart px-6 py-2 rounded bg-green-400 text-white">{{ __('Save') }}</button>
                    </div>
                </li>`;
            productList.html(productList.html() + htmlContent);
        });
        handleUpdateCartPrice(cartTotalPrice);
        handleRemoveFromCart();
        handleEditCart();
    }

    const getProductsInCart = async () => {
        const url = `/api/cart`;
        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            $('#pagination').html("");

            renderProducts(response.data.products);
            paginationProduct(response.data);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getProductsInCart();
</script>

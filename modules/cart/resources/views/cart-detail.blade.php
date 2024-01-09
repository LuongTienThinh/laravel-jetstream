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
                <div class="product-wrap p-4">
                    <div class="flex justify-between items-center mb-6">
                        <span>{{ __('List Products In Cart') }}</span>
                        <div class="relative w-[50%]">
                            <label for="search"></label>
                            <input class="border-gray-400 focus:outline-0 focus:ring-0 rounded-lg w-full" type="text"
                                   name="search" id="search" placeholder="Search here...">
                            <button id="search-product"
                                    class="absolute top-0 right-0 h-full bg-blue-400 text-white px-4 rounded-e-lg">Search</button>

                        </div>
                        <div></div>
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
                    <div class="border-t-2 text-center font-black capitalize py-4 text-xl">total price: <span id="cart-total-price"></span></div>
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

    const handleEditCart = () => {
        const btnEdits = $('.btn-edit-cart');
        btnEdits.each(function () {
            $(this).on('click', () => {
                const cart_id = this.id.split('-')[1];
                const product_id = this.id.split('-')[2];

                const [price, quantity] = [
                    $(`#price-${product_id}`).html(),
                    $(`#cart-quantity-${cart_id}-${product_id}`).val(),
                ];

                const url = `http://127.0.0.1:8000/api/cart/edit/${cart_id}-${product_id}`;
                $.ajax({
                    url: url,
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        cart_id,
                        product_id,
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
                const cart_id = this.id.split('-')[1];
                const product_id = this.id.split('-')[2];

                const url = `http://127.0.0.1:8000/api/cart/delete/${cart_id}-${product_id}`;
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
        const cart_id = event.target.id.split('-')[2];

        const productPriceHtml = $(`#price-${id}`);

        const prevPrice = parseFloat(productPriceHtml.html());
        const currPrice = basePrice * event.target.value;
        const currCartPrice = parseFloat($('#cart-total-price').html());

        productPriceHtml.html(currPrice);
        handleUpdateCartPrice(currCartPrice - prevPrice + currPrice);

        if (parseInt(event.target.value) !== baseQuantity) {
            $(`#edit-${cart_id}-${id}`).removeClass('hidden');
        } else {
            $(`#edit-${cart_id}-${id}`).addClass('hidden');
        }
    }

    const paginationProduct = (paginate, searchContent) => {
        const paginationHtml = $('#pagination');

        const htmlPrev = paginate.prev ?
            `<a href="<?php echo $url; ?>?page=<?php echo $page - 1; ?>${searchContent ? `&search=${searchContent}` : ''}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">« Previous</a>` :
            `<span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">« Previous</span>`;

        const htmlNext = paginate.next ?
            `<a href="<?php echo $url; ?>?page=<?php echo $page + 1; ?>${searchContent ? `&search=${searchContent}` : ''}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">Next »</a>` :
            `<span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">Next »</span>`;

        const htmlPaginate =
            `<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                ${htmlPrev}
                ${htmlNext}
            </nav>`;

        paginationHtml.html(paginationHtml.html() + htmlPaginate);
    }

    const renderProducts = (products) => {
        const productList = $("#product-list");
        let cartTotalPrice = 0;

        if (products) {
            productList.html(`<li class="p-2 flex flex-nowrap items-center w-full font-black">
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>`);
        }

        products.forEach((item) => {
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
        const data = {
            page: parseInt('<?php echo $page; ?>'),
            search: '<?php echo $search; ?>'
        };

        console.log(data);

        const url = `http://127.0.0.1:8000/api/cart/10?page=${data.page}&search=${data.search}`;
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

    const getProductsFiltered = async (searchContent) => {
        const response = await fetch(`http://127.0.0.1:8000/api/product?search=${searchContent}&page=1`);
        return await response.json();
    }

    const search = async () => {
        const categories = await getAllCategories();

        const searchContent = $('#search');

        try {
            const result = await getProductsFiltered(searchContent.val());

            $('#pagination').html("");

            renderProducts(result.data.products, categories.data);
            paginationProduct(result.data, searchContent.val());

            searchContent.val('');
        } catch (error) {
            console.error('Error:', error);
        }
    }

    $('#search-product').click(search);

    $('#search').keyup(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $('#search-product').click();
        }
    });
</script>

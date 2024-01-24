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
                        <span>{{ __('List Products') }}</span>
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
                            <li class="p-2 flex flex-nowrap items-center w-[130%] font-black">
                                <span class="w-[10%]">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Category</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>
                        </ul>
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

    const showModal = (btn, modal, close, submit) => {
        $(btn).on('click', () => {
            $(modal).removeClass('hidden');
            $(modal).keyup(function(event) {
                if (event.which === 13) {
                    event.preventDefault();
                    submit.click();
                }
            });
        });

        $(close).on('click', () => {
            $(modal).addClass('hidden');
        });
    };

    const renderModalAddCart = () => {
        const btnAddCart = $('.btn-add-cart');
        const modalAddCart = $('.add-cart');
        const closeAddCart = $('.close-add-cart');
        const submitAddCart = $('.submit-add-cart');

        const handleSubmit = (item) => {
            const id = item.id.split('-')[1];

            const [price, quantity] = [
                $(`#total-price-${id}`).html(),
                $(`#cart-quantity-${id}`).val(),
            ];

            const url = `/api/cart/create`;

            $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    product_id: id,
                    quantity: parseInt(quantity),
                    total_price: parseFloat(price),
                }),
                success: function(response) {
                    window.location.href = '{{ route('list_product') }}';
                    console.log('Thêm thành công', response);
                },
                error: function(error) {
                    console.error('Lỗi khi thêm', error);
                }
            });
        }

        submitAddCart.each(function() {
            $(this).on('click', () => {
                handleSubmit(this);
            });
        });

        btnAddCart.each(function(index) {
            showModal(this, modalAddCart.eq(index), closeAddCart.eq(index), submitAddCart.eq(index));
        });
    }

    const handleChangeQuantity = (event, basePrice) => {
        const id = event.target.id.split('-')[2];
        $(`#total-price-${id}`).html(basePrice * event.target.value);
    }

    const renderSelectCategories = (currentItem, categories) => {
        return categories.reduce((acc, curr) => {
            if (currentItem && curr.id === currentItem.category_id) {
                return acc + `\n<option value="${curr.id}" selected>${curr.name}</option>`;
            }
            return acc + `\n<option value="${curr.id}">${curr.name}</option>`;
        }, ``);
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

        if (products && products.length > 0) {
            productList.html(`<li class="p-2 flex flex-nowrap items-center w-[130%] font-black">
                                <span class="w-[10%]">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Category</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>`);
        }

        products && products.length > 0 && products.forEach((item) => {
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-[130%]">
                    <span class="w-[10%]">${item.no}</span>
                    <span class="w-[30%] text-center">${item.name}</span>
                    <span class="w-[20%] text-center">${item.price}</span>
                    <span class="w-[20%] text-center">${item.quantity}</span>
                    <span class="w-[20%] text-center">${item.category_name}</span>
                    <div class="action w-[30%] text-center">
                        <button class="btn-add-cart px-6 py-2 rounded bg-green-400 text-white">{{ __('Add to cart') }}</button>
                    </div>
                </li>
                <div class="hidden add-cart w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                    <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                        <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                            <h2 class="uppercase text-center text-2xl">{{ __('Add product to cart') }}</h2>
                            <ul class="list-none p-4 overflow-y-auto" id="product-list mt-6">
                                <li class="p-2 flex flex-nowrap items-center w-full font-black">
                                    <span class="w-[30%] text-center">Product Name</span>
                                    <span class="w-[20%] text-center">Category</span>
                                    <span class="w-[20%] text-center">Price</span>
                                    <span class="w-[20%] text-center">Quantity</span>
                                    <span class="w-[20%] text-center">Total price</span>
                                </li>
                                <li class="p-2 flex flex-nowrap items-center w-full">
                                    <span class="w-[30%] text-center">${item.name}</span>
                                    <span class="w-[20%] text-center">${item.category_name}</span>
                                    <span class="w-[20%] text-center">${item.price}</span>
                                    <span class="w-[20%] text-center">
                                        <input id="cart-quantity-${item.id}" class="w-[70%] text-center" type="number"
                                            name="quantity" min="1" max="${item.quantity}" value="1"
                                            onchange="handleChangeQuantity(event, ${item.price})" required>
                                    </span>
                                    <span id="total-price-${item.id}" class="w-[20%] text-center">${item.price}</span>
                                </li>
                            </ul>
                            <div class="text-center">
                                <button id="submit-${item.id}" class="submit-add-cart px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add to cart') }}</button>
                            </div>
                            <button class="close-add-cart absolute top-2 right-2 px-2">x</button>
                        </div>
                    </div>
                </div>`;
            productList.html(productList.html() + htmlContent);
        });
        renderModalAddCart();
    }

    const getAllCategories = async () => {
        const url = '/api/category';
        let result = null;
        try {
            result = await $.ajax({
                url: url,
                method: 'GET',
            });

        } catch (error) {
            console.error('Error', error)
        }

        return result;
    }

    const getAllProducts = async () => {
        const data = {
            page: parseInt('<?php echo $page; ?>'),
            search: '<?php echo $search; ?>'
        };

        const url = `/api/product/get-list?page=${data.page}&search=${data.search}`;
        try {
            const categories = await getAllCategories();
            const response = await $.ajax({
                url: url,
                method: 'GET',
            });

            $('#pagination').html("");

            renderProducts(response.data.products, categories.data);
            paginationProduct(response.data);

        } catch (error) {
            console.error('Error:', error);
        }
    }
    getAllProducts();

    const getProductsFiltered = async (searchContent) => {
        const response = await fetch(`/api/product?search=${searchContent}&page=1`);
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

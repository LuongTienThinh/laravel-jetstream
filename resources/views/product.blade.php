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
                        <span>{{ __('List Products') }}</span>
                        <div class="relative w-[50%]">
                            <input class="border-gray-400 focus:outline-0 focus:ring-0 rounded-lg w-full" type="text"
                                name="search" id="search" placeholder="Search here...">
                            <button id="search-product"
                                class="absolute top-0 right-0 h-full bg-blue-400 text-white px-4 rounded-e-lg">Search</button>

                        </div>
                        <button
                            class="btn-add-modal px-6 py-2 rounded bg-blue-400 text-white">{{ __('New Product') }}</button>
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

    const renderModalAdd = () => {
        const btnAdd = $('#btn-add-product');
        btnAdd.on('click', () => {
            const [name, price, quantity, category] = [
                $(`#create-name`).val(),
                $(`#create-price`).val(),
                $(`#create-quantity`).val(),
                $(`#create-category`).val()
            ];

            const url = 'http://127.0.0.1:8000/api/product/create';

            $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    name,
                    price: parseFloat(price),
                    quantity: parseInt(quantity),
                    category_id: parseInt(category)
                }),
                success: function(response) {
                    window.location.href = '{{ route('product_web') }}';
                    console.log('Thêm thành công', response);
                },
                error: function(error) {
                    console.error('Lỗi khi thêm', error);
                }
            });
        });

        showModal($('.btn-add-modal'), $('.add-product'), $('.close-add-product'), btnAdd);
    }

    const renderModalEdit = () => {
        const btnEdits = $('.btn-edit-product');
        const modalEdits = $('.edit-product');
        const closeEdits = $('.close-edit-product');
        const submitEdits = $('.submit-edit-product');

        const handleSubmit = (item) => {
            const id = item.id.split('-')[1];

            const [name, price, quantity, category] = [
                $(`#edit-name-${id}`).val(),
                $(`#edit-price-${id}`).val(),
                $(`#edit-quantity-${id}`).val(),
                $(`#edit-category-${id}`).val()
            ];

            const url = `http://127.0.0.1:8000/api/product/edit/${id}`;

            $.ajax({
                url: url,
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    name,
                    price: parseFloat(price),
                    quantity: parseInt(quantity),
                    category_id: parseInt(category)
                }),
                success: function(response) {
                    window.location.href = '{{ route('product_web') }}';
                    console.log('Cập nhật thành công', response);
                },
                error: function(error) {
                    console.error('Lỗi khi cập nhật', error);
                }
            });
        }

        submitEdits.each(function() {
            $(this).on('click', () => {
                handleSubmit(this);
            });
        });

        btnEdits.each(function(index) {
            showModal(this, modalEdits.eq(index), closeEdits.eq(index), submitEdits.eq(index));
        });
    }

    const handleDelete = () => {
        const btnDeletes = $('.btn-delete-product');
        btnDeletes.each(function() {
            $(this).on('click', () => {
                const id = this.id.split('-')[1];

                const url = `http://127.0.0.1:8000/api/product/delete/${id}`;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        window.location.href = '{{ route('product_web') }}';
                        console.log('Xóa thành công', response);
                    },
                    error: function(error) {
                        console.error('Lỗi khi xóa', error);
                    }
                });
            });
        });
    }

    const renderSelectCategories = (currentItem, categories) => {
        return selectCategories = categories.reduce((acc, curr) => {
            if (currentItem && curr.id == currentItem.category_id) {
                return acc + `\n<option value="${curr.id}" selected>${curr.name}</option>`;
            }
            return acc + `\n<option value="${curr.id}">${curr.name}</option>`;
        }, ``);
    }

    const renderAddProduct = (categories) => {
        const htmlContent =
            `<div class="hidden add-product w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                    <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                        <h2 class="uppercase text-center text-2xl">{{ __('New product') }}</h2>
                        <div class="mt-6">
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                <input class="w-[70%]" type="text" name="name" id="create-name" placeholder="Product name" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="price">{{ __('Price') }}:</label>
                                <input class="w-[70%]" type="number" name="price" id="create-price" placeholder="Product price" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="quantity">{{ __('Quantity') }}:</label>
                                <input class="w-[70%]" type="number" name="quantity" id="create-quantity"
                                    placeholder="Product quantity" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="category_id">{{ __('Category') }}:</label>
                                <select class="w-[70%]" name="category_id" id="create-category">
                                    ${renderSelectCategories(null, categories)}
                                </select>
                            </div>
                            <div class="text-center mt-6">
                                <button id="btn-add-product"
                                    class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add product') }}</button>
                            </div>
                        </div>
                        <button class="close-add-product absolute top-2 right-2 px-2">x</button>
                    </div>
                </div>
            </div>`;
        $("#product-area").html($("#product-area").html() + htmlContent);
        renderModalAdd();
    }

    const paginationProduct = (paginate, searchContent) => {
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

        $('#pagination').html($('#pagination').html() + htmlPaginate);
    }

    const renderProducts = (products, categories) => {
        if (products) {
            $("#product-list").html(`<li class="p-2 flex flex-nowrap items-center w-[130%] font-black">
                                <span class="w-[10%]">No</span>
                                <span class="w-[30%] text-center">Product Name</span>
                                <span class="w-[20%] text-center">Price</span>
                                <span class="w-[20%] text-center">Quantity</span>
                                <span class="w-[20%] text-center">Category</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>`);
        }

        products.forEach((item) => {
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-[130%]">
                    <span class="w-[10%]">${item.no}</span>
                    <span class="w-[30%] text-center">${item.name}</span>
                    <span class="w-[20%] text-center">${item.price}</span>
                    <span class="w-[20%] text-center">${item.quantity}</span>
                    <span class="w-[20%] text-center">${item.category_name}</span>
                    <div class="action w-[30%] text-center">
                        <button class="btn-edit-product px-6 py-2 rounded bg-green-400 text-white">{{ __('Edit') }}</button>
                        <button id="delete-${item.id}" type="submit" class="btn-delete-product px-6 py-2 rounded bg-red-400 text-white">{{ __('Delete') }}</button>
                    </div>
                </li>
                <div class="hidden edit-product w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                    <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                        <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                            <h2 class="uppercase text-center text-2xl">{{ __('Edit product') }}</h2>
                            <div class="mt-6">
                                <div class="flex items-center justify-around py-2">
                                    <label class="w-20" for="name">{{ __('Name') }}</label>
                                    <input class="w-[70%]" type="text" name="name" id="edit-name-${item.id}" placeholder="Product name" required value="${item.name}">
                                </div>
                                <div class="flex items-center justify-around py-2">
                                    <label class="w-20" for="price">{{ __('Price') }}:</label>
                                    <input class="w-[70%]" type="number" name="price" id="edit-price-${item.id}"
                                        placeholder="Product price" required value="${item.price}">
                                </div>
                                <div class="flex items-center justify-around py-2">
                                    <label class="w-20" for="quantity">{{ __('Quantity') }}:</label>
                                    <input class="w-[70%]" type="number" name="quantity" id="edit-quantity-${item.id}"
                                        placeholder="Product quantity" required
                                        value="${item.quantity}">
                                </div>
                                <div class="flex items-center justify-around py-2">
                                    <label class="w-20" for="category_id">{{ __('Category') }}:</label>
                                    <select class="w-[70%]" name="category_id" id="edit-category-${item.id}">
                                        ${renderSelectCategories(item, categories)}
                                    </select>
                                </div>
                                <div class="text-center mt-6">
                                    <button type="submit" id="edit-${item.id}" class="submit-edit-product px-6 py-2 rounded bg-blue-400 text-white">{{ __('Update') }}</button>
                                </div>
                            </div>
                            <button class="close-edit-product absolute top-2 right-2 px-2">x</button>
                        </div>
                    </div>
                </div>`;
            $("#product-list").html($("#product-list").html() + htmlContent);
        });
        renderModalEdit();
        handleDelete();
    }

    const getAllCategories = async () => {
        const url = 'http://127.0.0.1:8000/api/category';
        let result = null;
        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json'
            });

            result = response;

        } catch (error) {
            console.error('Error', error)
        }

        return result;
    }

    const getAllProducts = async () => {
        console.log('<?php echo $page; ?>');
        const url = 'http://127.0.0.1:8000/api/product';
        try {
            const categories = await getAllCategories();
            const response = await $.ajax({
                url: url,
                method: 'GET',
                data: {
                    page: parseInt('<?php echo $page; ?>'),
                    search: '<?php echo $search; ?>'
                },
                dataType: 'json'
            });

            @php @endphp

            $('#pagination').html("");

            renderAddProduct(categories.data);
            renderProducts(response.data.products, categories.data);
            paginationProduct(response.data);
            
        } catch (error) {
            console.error('Error:', error);
        }
    }
    getAllProducts();

    const getProductsFiltered = async (searchContent) => {
        const response = await fetch(`http://127.0.0.1:8000/api/product?search=${searchContent}&page=1`);
        const result = await response.json();
        <?php $page = 1; ?>
        return result;
    }

    const search = async () => {
        const categories = await getAllCategories();

        const searchContent = $('#search').val();

        try {
            const result = await getProductsFiltered(searchContent);

            $('#pagination').html("");
            
            renderProducts(result.data.products, categories.data);
            paginationProduct(result.data, searchContent);

            $('#search').val('');
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

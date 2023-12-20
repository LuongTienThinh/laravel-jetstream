<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product') }}
        </h2>
    </x-slot>

    <div class="py-12" id="product-area">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="product-wrap p-4">
                    <div class="flex justify-between items-center mb-6">
                        <span>{{ __('List Products') }}</span>
                        <div class="relative w-[50%]">
                            <input class="border-gray-400 focus:outline-0 focus:ring-0 rounded-lg w-full" type="text"
                                name="search" id="search" placeholder="Search here...">
                            <button id="search-product"
                                class="absolute top-0 right-0 h-full bg-blue-400 text-white px-4 rounded-e-lg"
                                type="submit">Search</button>
                        </div>
                        <button
                            class="btn-add-product px-6 py-2 rounded bg-blue-400 text-white">{{ __('New Product') }}</button>
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
                    @if (isset($products))
                        <div class="border-t-2 px-6 pt-4">{{ $products->links() }}</div>
                    @endif
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
    const showModal = (btn, modal, close) => {
        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });
        close.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    const renderModalAdd = () => {
        const btnAdd = document.querySelector('.btn-add-product');
        const modalAdd = document.querySelector('.add-product');
        const closeAdd = document.querySelector('.close-add-product');
        showModal(btnAdd, modalAdd, closeAdd);
    }

    const renderModalEdit = () => {
        const btnEdits = document.querySelectorAll('.btn-edit-product');
        const modalEdits = document.querySelectorAll('.edit-product');
        const closeEdits = document.querySelectorAll('.close-edit-product');
        const submitEdits = document.querySelectorAll('.submit-edit-product');
        btnEdits.forEach((btnEdit, index) => {
            showModal(btnEdit, modalEdits[index], closeEdits[index]);
        });
        submitEdits.forEach(submitEdit => {
            submitEdit.addEventListener('click', () => {
                const id = submitEdit.id.split('-')[1];
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
                        category: parseInt(category)
                    }),
                    success: function(response) {
                        console.log('Cập nhật thành công', response);
                        window.location.href = '{{ route('product_web') }}';
                    },
                    error: function(error) {
                        console.error('Lỗi khi cập nhật', error);
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
                        <form class="mt-6">
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                <input class="w-[70%]" type="text" name="name" id="name" placeholder="Product name" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="price">{{ __('Price') }}:</label>
                                <input class="w-[70%]" type="number" name="price" id="price" placeholder="Product price" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="quantity">{{ __('Quantity') }}:</label>
                                <input class="w-[70%]" type="number" name="quantity" id="quantity"
                                    placeholder="Product quantity" required>
                            </div>
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="category">{{ __('Category') }}:</label>
                                <select class="w-[70%]" name="category" id="category">
                                    ${renderSelectCategories(null, categories)}
                                </select>
                            </div>
                            <div class="text-center mt-6">
                                <button type="submit"
                                    class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add product') }}</button>
                            </div>
                        </form>
                        <button class="close-add-product absolute top-2 right-2 px-2">x</button>
                    </div>
                </div>
            </div>`;
        $("#product-area").html($("#product-area").html() + htmlContent);
        renderModalAdd();
    }

    const renderProducts = (products, categories) => {
        if (products) {
            $("#product-list").html("");
        }

        products.forEach((item, i) => {
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center w-[130%]">
                    <span class="w-[10%]">${i + 1}</span>
                    <span class="w-[30%] text-center">${item.name}</span>
                    <span class="w-[20%] text-center">${item.price}</span>
                    <span class="w-[20%] text-center">${item.quantity}</span>
                    <span class="w-[20%] text-center">${item.category_name}</span>
                    <div class="action w-[30%] text-center">
                        <button class="btn-edit-product px-6 py-2 rounded bg-green-400 text-white">{{ __('Edit') }}</button>
                        <button type="submit" class="btn-delete-product px-6 py-2 rounded bg-red-400 text-white">{{ __('Delete') }}</button>
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
                                    <label class="w-20" for="category">{{ __('Category') }}:</label>
                                    <select class="w-[70%]" name="category" id="edit-category-${item.id}">
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
    }

    const getAllProducts = async () => {
        const response = await fetch(`http://127.0.0.1:8000/api/product`);
        const result = await response.json();
        renderAddProduct(result.data.categories);
        renderProducts(result.data.products, result.data.categories);
    }
    getAllProducts();

    const getProductsFiltered = async (searchContent) => {
        const response = await fetch(`http://127.0.0.1:8000/api/product/search?search=${searchContent}`)
        const result = await response.json();
        return result;
    }

    console.log($('#search-product'), $('#search'));

    $('#search-product').click(async () => {
        console.log('Button clicked');
        const searchContent = $('#search').val();
        console.log('Search content:', searchContent);

        try {
            const result = await getProductsFiltered(searchContent);
            console.log('Filtered products:', result.data.products);
            console.log('Filtered categories:', result.data.categories);
            renderProducts(result.data.products, result.data.categories);
            $('#search').val('');
        } catch (error) {
            console.error('Error:', error);
        }
    });

    $('#search').keyup(function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $('#search-product').click();
        }
    });
</script>

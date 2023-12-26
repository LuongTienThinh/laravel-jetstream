<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="category-wrap p-4">
                    <div class="flex justify-between items-center mb-6">
                        <span>{{ __('List Categories') }}</span>
                        <button
                            class="btn-add-modal px-6 py-2 rounded bg-blue-400 text-white">{{ __('New Category') }}</button>
                    </div>
                    <div class="border-t-2">
                        <ul class="list-none p-4 overflow-y-auto" id="category-list">
                            <li class="p-2 flex flex-nowrap items-center justify-between font-black">
                                <span class="w-[10%]">No</span>
                                <span class="w-[30%] text-center">Category Name</span>
                                <span class="action w-[30%] text-center">Action</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="category-area"></div>
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
        const btnAdd = $('#btn-add-category');
        btnAdd.on('click', () => {
            const name = $(`#create-name`).val();

            const url = 'http://127.0.0.1:8000/api/category/create';

            $.ajax({
                url: url,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    name,
                }),
                success: function(response) {
                    window.location.href = '{{ route('category_web') }}';
                    console.log('Thêm thành công', response);
                },
                error: function(error) {
                    console.error('Lỗi khi thêm', error);
                }
            });
        });

        showModal($('.btn-add-modal'), $('.add-category'), $('.close-add-category'), btnAdd);
    }

    const renderModalEdit = () => {
        const btnEdits = $('.btn-edit-category');
        const modalEdits = $('.edit-category');
        const closeEdits = $('.close-edit-category');
        const submitEdits = $('.submit-edit-category');
        console.log(btnEdits, modalEdits, closeEdits, submitEdits);

        btnEdits.each(function(index) {
            showModal(this, modalEdits.eq(index), closeEdits.eq(index), submitEdits.eq(index));
        });

        submitEdits.each(function() {
            $(this).on('click', () => {
                const id = this.id.split('-')[1];

                const name = $(`#edit-name-${id}`).val();

                const url = `http://127.0.0.1:8000/api/category/edit/${id}`;

                $.ajax({
                    url: url,
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        name,
                    }),
                    success: function(response) {
                        window.location.href = '{{ route('category_web') }}';
                        console.log('Cập nhật thành công', response);
                    },
                    error: function(error) {
                        console.error('Lỗi khi cập nhật', error);
                    }
                });
            });
        });
    }

    const handleDelete = () => {
        const btnDeletes = $('.btn-delete-category');
        btnDeletes.each(function() {
            $(this).on('click', () => {
                const id = this.id.split('-')[1];

                const url = `http://127.0.0.1:8000/api/category/delete/${id}`;
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        window.location.href = '{{ route('category_web') }}';
                        console.log('Xóa thành công', response);
                    },
                    error: function(error) {
                        console.error('Lỗi khi xóa', error);
                    }
                });
            });
        });
    }

    const renderAddCategories = () => {
        const htmlContent =
            `<div class="hidden add-category w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                    <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                        <h2 class="uppercase text-center text-2xl">{{ __('New category') }}</h2>
                        <div class="mt-6">
                            <div class="flex items-center justify-around py-2">
                                <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                <input class="w-[70%]" type="text" name="name" id="create-name" placeholder="Category name"
                                    required>
                            </div>
                            <div class="text-center mt-6">
                                <button type="submit" id="btn-add-category"
                                    class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add category') }}</button>
                            </div>
                        </div>
                        <button class="close-add-category absolute top-2 right-2 px-2">x</button>
                    </div>
                </div>
            </div>`;
        $("#category-area").html($("#category-area").html() + htmlContent);
        renderModalAdd();
    }

    const renderCategories = (categories) => {
        if (categories) {
            $('#category-list').html(`<li class="p-2 flex flex-nowrap items-center justify-between font-black">
                                        <span class="w-[10%]">No</span>
                                        <span class="w-[30%] text-center">Category Name</span>
                                        <span class="action w-[30%] text-center">Action</span>
                                    </li>`);
        }

        categories.forEach((item, i) => {
            const htmlContent =
                `<li class="p-2 flex flex-nowrap items-center justify-between">
                    <span class="w-[10%]">${i + 1}</span>
                    <span class="w-[30%] text-center">${item.name}</span>
                    <div class="action w-[30%] text-center">
                        <button class="btn-edit-category px-6 py-2 rounded bg-green-400 text-white">{{ __('Edit') }}</button>
                        <button id="delete-${item.id}" type="submit" class="btn-delete-category px-6 py-2 rounded bg-red-400 text-white">{{ __('Delete') }}</button>
                    </div>
                </li>
                <div class="hidden edit-category w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                    <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                        <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                            <h2 class="uppercase text-center text-2xl">{{ __('Edit category') }}
                            </h2>
                            <div class="mt-6">
                                <div class="flex items-center justify-around py-2">
                                    <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                    <input class="w-[70%]" type="text" name="name" id="edit-name-${item.id}" placeholder="Category name"
                                    required value="${item.name}">
                                </div>
                                <div class="text-center mt-6">
                                    <button type="submit" id="edit-${item.id}" class="submit-edit-category px-6 py-2 rounded bg-blue-400 text-white">{{ __('Update') }}</button>
                                </div>
                            </div>
                            <button class="close-edit-category absolute top-2 right-2 px-2">x</button>
                        </div>
                    </div>
                </div>`;
            $('#category-list').html($('#category-list').html() + htmlContent);
        });

        renderModalEdit();
        handleDelete();
    }

    const getAllCategories = async () => {
        const url = 'http://127.0.0.1:8000/api/category';
        try {
            const response = await $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json'
            });

            renderAddCategories();
            renderCategories(response.data);

        } catch (error) {
            console.error('Error', error)
        }
    }
    getAllCategories();
</script>

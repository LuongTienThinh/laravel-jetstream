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
                        <button class="btn-add-category px-6 py-2 rounded bg-blue-400 text-white">{{ __('New Category') }}</button>
                    </div>
                    <div class="border-t-2">
                        @if (isset($categories))
                            <ul class="list-none p-4 overflow-y-auto">
                                <li class="p-2 flex flex-nowrap items-center justify-between font-black">
                                    <span class="w-[10%]">No</span>
                                    <span class="w-[30%] text-center">Category Name</span>
                                    <span class="action w-[30%] text-center">Action</span>
                                </li>
                                @foreach ($categories as $category)
                                <li class="p-2 flex flex-nowrap items-center justify-between">
                                    @php $index = $loop->index + 1 + $categories->perPage() * ($categories->currentPage() - 1) @endphp
                                    <span class="w-[10%]">{{ $index }}</span>
                                    <span class="w-[30%] text-center">{{ $category->name }}</span>
                                    <div class="action w-[30%] text-center">
                                        <button id="{{ $category->id }}-edit" class="btn-edit-category px-6 py-2 rounded bg-green-400 text-white">{{ __('Edit') }}</button>
                                        <form action="{{ route('category_delete', ['id' => $category->id]) }}" method="POST" class="inline-block">
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $category->id }}">
                                            <button type="submit" class="btn-delete-category px-6 py-2 rounded bg-red-400 text-white">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </li>
                                <div class="hidden edit-category w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                                    <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                                        <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                                            <h2 class="uppercase text-center text-2xl">{{ __('Edit category') }}</h2>
                                            <form class="mt-6" action="{{ route('category_edit', ['id' => $category->id]) }}" method="post">
                                                @method('PUT')
                                                <div class="flex items-center justify-around py-2">
                                                    <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                                    <input class="w-[70%]" type="text" name="name" id="name" placeholder="Category name" required value="{{ $category->name }}">
                                                </div>
                                                <div class="text-center mt-6">
                                                    <button type="submit" class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Update') }}</button>
                                                </div>
                                            </form>
                                            <button class="close-edit-category absolute top-2 right-2 px-2">x</button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @if (isset($categories))
                    <div class="border-t-2 px-6 pt-4">{{ $categories->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="hidden add-category w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
        <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
            <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                <h2 class="uppercase text-center text-2xl">{{ __('New category') }}</h2>
                <form class="mt-6" action="{{ route('category_create') }}" method="post">
                    <div class="flex items-center justify-around py-2">
                        <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                        <input class="w-[70%]" type="text" name="name" id="name" placeholder="Category name" required>
                    </div>
                    <div class="text-center mt-6">
                        <button type="submit" class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add category') }}</button>
                    </div>
                </form>
                <button class="close-add-category absolute top-2 right-2 px-2">x</button>
            </div>
        </div>
    </div>
    @if (session('success'))
    <div class="notify fixed top-2 right-2 w-fit h-fit bg-white py-3 px-6 sm:rounded-e-lg z-50 max-w-[300px] border-s-4 border-green-400">
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

const btnAdd = document.querySelector('.btn-add-category');
const modalAdd = document.querySelector('.add-category');
const closeAdd = document.querySelector('.close-add-category');
showModal(btnAdd, modalAdd, closeAdd);

const btnEdits = document.querySelectorAll('.btn-edit-category');
const modalEdits = document.querySelectorAll('.edit-category');
const closeEdits = document.querySelectorAll('.close-edit-category');
btnEdits.forEach((btnEdit, index) => {
    showModal(btnEdit, modalEdits[index], closeEdits[index]);
});

</script>
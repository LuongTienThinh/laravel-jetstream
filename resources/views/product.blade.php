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
                        <button class="btn-add-product px-6 py-2 rounded bg-blue-400 text-white">{{ __('New Product') }}</button>
                    </div>
                    <div class="border-t-2">
                        @if (isset($products))
                            <ul class="list-none p-4">
                                <li class="p-2 flex items-center">
                                    <span class="w-[30%] text-center">Product Name</span>
                                    <span class="w-[20%] text-center">Price</span>
                                    <span class="w-[20%] text-center">Quantity</span>
                                    <span class="action w-[30%] text-center">Action</span>
                                </li>
                                @foreach ($products as $product)
                                <li class="p-2 flex items-center">
                                    <span class="w-[30%]">{{ $product->name }}</span>
                                    <span class="w-[20%] text-center">{{ $product->price }}</span>
                                    <span class="w-[20%] text-center">{{ $product->quantity }}</span>
                                    <div class="action w-[30%] text-center">
                                        <button id="{{ $product->id }}-edit" class="btn-edit-product px-6 py-2 rounded bg-green-400 text-white">{{ __('Edit') }}</button>
                                        <form action="{{ route('product_delete', ['id' => $product->id]) }}" method="POST" class="inline-block">
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <button type="submit" class="btn-delete-product px-6 py-2 rounded bg-red-400 text-white">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </li>
                                <div class="hidden edit-product w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
                                    <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
                                        <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                                            <h2 class="uppercase text-center text-2xl">{{ __('Edit product') }}</h2>
                                            <form class="mt-6" action="{{ route('product_edit', ['id' => $product->id]) }}" method="post">
                                                @method('PUT')
                                                <div class="flex items-center justify-around py-2">
                                                    <label class="w-20" for="name">{{ __('Name') }}:{{ session('success') }}</label>
                                                    <input class="w-[70%]" type="text" name="name" id="name" placeholder="Product name" required value="{{ $product->name }}">
                                                </div>
                                                <div class="flex items-center justify-around py-2">
                                                    <label class="w-20" for="price">{{ __('Price') }}:</label>
                                                    <input class="w-[70%]" type="number" name="price" id="price" placeholder="Product price" required value="{{ $product->price }}">
                                                </div>
                                                <div class="flex items-center justify-around py-2">
                                                    <label class="w-20" for="quantity">{{ __('Quantity') }}:</label>
                                                    <input class="w-[70%]" type="number" name="quantity" id="quantity" placeholder="Product quantity" required value="{{ $product->quantity }}">
                                                </div>
                                                <div class="text-center mt-6">
                                                    <button type="submit" class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Update') }}</button>
                                                </div>
                                            </form>
                                            <button class="close-edit-product absolute top-2 right-2 px-2">x</button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    @if (isset($products))
                    <div class="border-t-2 px-6 pt-4">{{ $products->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="hidden add-product w-screen h-screen fixed inset-0 z-50 bg-slate-500/25">
        <div class="absolute inset-1/2 translate-x-[-50%] translate-y-[-50%] w-full h-fit">
            <div class="container relative mx-auto bg-white p-6 sm:rounded-lg max-w-[80%]">
                <h2 class="uppercase text-center text-2xl">{{ __('New product') }}</h2>
                <form class="mt-6" action="{{ route('product_create') }}" method="post">
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
                        <input class="w-[70%]" type="number" name="quantity" id="quantity" placeholder="Product quantity" required>
                    </div>
                    <div class="text-center mt-6">
                        <button type="submit" class="px-6 py-2 rounded bg-blue-400 text-white">{{ __('Add product') }}</button>
                    </div>
                </form>
                <button class="close-add-product absolute top-2 right-2 px-2">x</button>
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

const btnAdd = document.querySelector('.btn-add-product');
const modalAdd = document.querySelector('.add-product');
const closeAdd = document.querySelector('.close-add-product');
showModal(btnAdd, modalAdd, closeAdd);

const btnEdits = document.querySelectorAll('.btn-edit-product');
const modalEdits = document.querySelectorAll('.edit-product');
const closeEdits = document.querySelectorAll('.close-edit-product');
btnEdits.forEach((btnEdit, index) => {
    showModal(btnEdit, modalEdits[index], closeEdits[index]);
});

</script>
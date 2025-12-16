<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuestros Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Importar Productos</h3>
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900" for="file_input">Subir archivo (CSV, XLSX)</label>
                        <input name="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_input" type="file">
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Importar</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="h-48 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-cover h-full w-full">
                            @else
                                <span class="text-gray-400">Sin imagen</span>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-700 text-base mb-4 truncate">
                                {{ $product->description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-900 font-bold text-xl">{{ $product->price }}€</span>
                                <button class="bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600 text-sm">Ver más</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12 text-gray-500">
                        No hay productos disponibles. ¡Importa algunos para empezar!
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
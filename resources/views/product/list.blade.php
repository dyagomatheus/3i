<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Produtos / Modelos
                    <a
                        href="{{route('product.create')}}"
                            class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-green-700 rounded-full shadow ripple hover:shadow-lg hover:bg-green-800 focus:outline-none"
                        >
                         Novo
                    </a>
                </div>
            @if (session('error'))
                <div class="row p-6">
                    <div class="text-red-600">
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="row p-6">
                    <div class="text-green-600">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            </div>
            @if (auth()->user()->type == 'admin')
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('product.search')}}" method="POST" class="ml-2">
                    @csrf
                    <div class="relative mr-6 my-2">
                        <input value="{{session('product_name')}}" type="text" name="product_name" id="product_name" class="bg-purple-white shadow rounded border-0 p-3" placeholder="Produto / Modelo">
                        <button
                            class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-blue-700 rounded-full shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
                        >
                            Pesquisar
                        </button>
                        <a
                        href="{{route('product.index')}}"
                            class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-red-700 rounded-full shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
                        >
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
            @endif
        <!-- Table -->
        <table class='mt-3 mx-auto w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
            <thead class="bg-gray-50">
                <tr class="text-gray-600 text-left">
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Produto / Modelos
                    </th>

                    @if (auth()->user()->type == 'admin')

                    <th class="font-semibold text-sm uppercase px-6 py-4">

                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$product->name}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        @if (auth()->user()->type == 'admin')
                        <td class="px-6 py-4 text-center">
                            <a href="{{route('product.edit', $product->id)}}" class="text-purple-800 hover:underline">Editar</a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{route('product.delete', $product->id)}}" class="text-red-800 hover:underline">Deletar</a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{$products->links()}}

        </div>
    </div>
</x-app-layout>

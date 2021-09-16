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
                    Devoluções
                </div>
            </div>
            @if (auth()->user()->type == 'admin')
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('devolution.search')}}" method="POST" class="ml-2">
                    @csrf
                    <div class="relative mr-6 my-2">
                        <select name="status" id="status" class="bg-purple-white shadow rounded border-0 p-3">
                            <option value="" disabled selected>-- Pesquisar Status -- </option>
                            <option value="Enviado" {{session('status') =='Enviado'? 'selected' : ''}} >Enviado</option>
                            <option value="Recebido" {{session('status') =='Recebido'? 'selected' : ''}}>Recebido</option>
                            <option value="Em processamento" {{session('status') =='Em processamento'? 'selected' : ''}}>Em processamento</option>
                            <option value="Aprovado Parcial" {{session('status') =='Aprovado Parcial'? 'selected' : ''}}>Aprovado Parcial</option>
                            <option value="Negado" {{session('status') == 'Negado'? 'selected' : ''}}>Negado</option>
                        </select>
                        <input value="{{session('date')}}" type="date" name="date" id="date" class="bg-purple-white shadow rounded border-0 p-3" placeholder="Data">
                        <select name="client_id" id="client_id" class="bg-purple-white shadow rounded border-0 p-3">
                            <option value="" disabled selected>-- Pesquisar Cliente -- </option>
                            @foreach ($clients as $client)
                                <option value="{{$client->id}}" {{session('client_id') == $client->id ? 'selected' : ''}}>{{$client->name}}</option>
                            @endforeach
                        </select>
                        <select name="product_id" id="product_id" class="bg-purple-white shadow rounded border-0 p-3">
                            <option value="" disabled selected>-- Pesquisar Produto -- </option>
                            @foreach ($products as $product)
                                <option value="{{$product->id}}" {{session('product_id') == $product->id ? 'selected' : ''}}>{{$product->name}}</option>
                            @endforeach
                        </select>
                        <button
                            class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-blue-700 rounded-full shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
                        >
                            Pesquisar
                        </button>
                        <a
                        href="{{route('dashboard')}}"
                            class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-red-700 rounded-full shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
                        >
                            Limpar
                        </a>

                        <a href="{{route('devolution.export.excel')}}"
                        class="inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-blue-700 rounded-full shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
                    >
                        Exportar Excel
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
                        Processo
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Produto
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Cliente
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Quantidade
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Valor NF
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4 text-center">
                        Número NF
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4 text-center">
                        status
                    </th>
                    @if (auth()->user()->type == 'admin')

                    <th class="font-semibold text-sm uppercase px-6 py-4">

                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">

                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($devolutions as $devolution)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$devolution->number}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$devolution->product->name}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$devolution->client->name}}
                                    </p>
                                    @foreach ($devolution->client->users as $user)
                                        <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                            {{$user->email}}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                {{$devolution->qty}}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                {{$devolution->value}}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{$devolution->number_nf}}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-black-800 bg-blue-200 font-semibold px-2 rounded-full">
                                {{ \App\Models\Devolution::status($devolution->id)->status ?? 'Enviado'}}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{route('devolution.show', $devolution->id)}}" class="text-purple-800 hover:underline">Detalhes</a>
                        </td>
                        @if (auth()->user()->type == 'admin')
                        <td class="px-6 py-4 text-center">
                            <a href="{{route('devolution.edit', $devolution->id)}}" class="text-purple-800 hover:underline">Editar</a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$devolutions->links()}}

        </div>
    </div>
</x-app-layout>

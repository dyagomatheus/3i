<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2>
                <a class="px-4 py-2 text-xs font-semibold tracking-wider border-2 border-gray-300 rounded hover:bg-gray-200 text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" href="{{ URL::previous() }}">Voltar</a>
                @if (auth()->user()->type == 'admin')
                    <a href="{{route('devolution.edit', $devolution->id)}}" class="text-purple-800 hover:underline">Editar</a>
                @endif
            </h2>        <!-- This is an example component -->
        <div class="mb-8 flex items-center justify-center px-4">

            <div class="max-w-4xl  bg-white w-full rounded-lg shadow-xl">
                <div class="p-4 border-b">
                    <h2 class="text-2xl ">
                        {{$devolution->product->name}}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Detalhes da devolução
                    </p>
                </div>
                <div>
                    <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            Processo:
                        </p>
                        <p>
                            {{$devolution->group->number}}
                        </p>
                    </div>
                    <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            Número da NF:
                        </p>
                        <p>
                            {{$devolution->number_nf}}
                        </p>
                    </div>
                    <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            Data NF
                        </p>
                        <p>
                            {{$devolution->date_nf}}
                        </p>
                    </div>
                    <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            Valor
                        </p>
                        <p>
                            {{$devolution->value}}
                        </p>
                    </div>
                    <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                        <p class="text-gray-600">
                            Defeito
                        </p>
                        <p>
                            {{$devolution->defect}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Histórico Devolução
        </h2>
        <table class='mt-3 mx-auto w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
            <thead class="bg-gray-50">
                <tr class="text-gray-600 text-left">
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Data
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Status
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Tempo
                    </th>
                    <th class="font-semibold text-sm uppercase px-6 py-4">
                        Comentário
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($devolution->statuses as $status)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$status->created_at}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="">
                                        {{$status->status}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                {{$status->time}}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                {{$status->comment}}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
    </div>
</x-app-layout>

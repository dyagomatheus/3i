<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Defeito
        </h2>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
        <h2 class="mb-4">
            <a class="px-4 py-2 text-xs font-semibold tracking-wider border-2 border-gray-300 rounded hover:bg-gray-200 text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" href="{{ URL::previous() }}">Voltar</a>
        </h2>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{route('defect.update', $defect->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="text-xl text-gray-600">Nome</label></br>
                        <input type="text" class="border-2 border-gray-300 p-2 w-full" name="name" id="name" value="{{$defect->name}}"></input>
                    </div>

                    <div class="flex p-1">
                        <button role="submit" class="p-3 bg-blue-500 text-white hover:bg-blue-400">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

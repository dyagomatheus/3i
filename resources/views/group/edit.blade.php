<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar processo
        </h2>
</x-slot>
@if (session('error'))
    <div class="row">
        <div class="alert alert-danger">
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif
@if (session('success'))
    <div class="row">
        <div class="alert alert-success">
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="mb-4">
            <a class="px-4 py-2 text-xs font-semibold tracking-wider border-2 border-gray-300 rounded hover:bg-gray-200 text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" href="{{ URL::previous() }}">Voltar</a>
        </h2>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{route('group.update', $group->id)}}">
                    @csrf
                    <div class="mb-4">
                        <label class="text-xl text-gray-600">Processo <span class="text-red-500">*</span></label></br>
                        <input type="text" disabled value="{{$group->number}}" class="border-2 border-gray-300 p-2 w-full" name="title" id="title" value="" required></input>
                    </div>
                    <div class="mb-4">
                        <label class="text-xl text-gray-600">Cliente</label></br>
                        <input type="text" value="{{$group->client->name}}" disabled class="border-2 border-gray-300 p-2 w-full" name="description" id="description" placeholder="(Optional)"></input>
                    </div>
                    <div class="mb-4">
                        <label class="text-xl text-gray-600">Status</label></br>
                        <select class="border-2 border-gray-300 p-2 w-full" name="status">
                            <option value="Enviado" {{\App\Models\ProcessDevolution::status($group->id)->status == 'Enviado' ? 'selected' : ''}}>Enviado</option>
                            <option value="Recebido" {{\App\Models\ProcessDevolution::status($group->id)->status == 'Recebido' ? 'selected' : ''}}>Recebido</option>
                            <option value="Em Processamento"{{\App\Models\ProcessDevolution::status($group->id)->status == 'Em Processamento' ? 'selected' : ''}}>Em Processamento</option>
                            <option value="Aprovado Parcial" {{\App\Models\ProcessDevolution::status($group->id)->status == 'Aprovado Parcial' ? 'selected' : ''}}>Aprovado Parcial</option>
                            <option value="Negado" {{\App\Models\ProcessDevolution::status($group->id)->status == 'Negado' ? 'selected' : ''}}>Negado</option>
                        </select>
                    </div>
                    <div class="mb-8">
                        <label class="text-xl text-gray-600" cols>Comentário <span class="text-red-500">*</span></label></br>
                        <textarea class="border-2 border-gray-500" cols="125" name="comment"></textarea>
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

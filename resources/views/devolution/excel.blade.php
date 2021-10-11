<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RMA</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Processo</th>
                <th>Model</th>
                <th>Amount</th>
                <th>Defect</th>
                <th>Status</th>
                <th>Data</th>
                <th>Obs</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($devolutions as $devolution)
            <tr>
                <td>{{ $devolution->client->name }}</td>
                <td>{{ $devolution->number }}</td>
                <td>{{ $devolution->product->name }}</td>
                <td>{{ $devolution->defect }}</td>
                <td>{{ \App\Models\Devolution::status($devolution->id)->status ?? 'Enviado'}}</td>
                <td>{{ \App\Models\Devolution::status($devolution->id)->created_at }}</td>
                <td>{{ \App\Models\Devolution::status($devolution->id)->comment }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

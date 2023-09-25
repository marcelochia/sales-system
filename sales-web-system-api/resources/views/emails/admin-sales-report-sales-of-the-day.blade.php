<!DOCTYPE html>
<html>
<head>
    <title>Relatório Diário de Vendas Administrativo</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Relatório Diário de Vendas Administrativo</h1>
    <p>Segue o relatório de vendas realizadas em: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>Vendedor</th>
                <th>Quantidade de Vendas</th>
                <th>Valor Total das Vendas</th>
                <th>Valor Total das Comissões</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale['seller'] }}</td>
                <td>{{ $sale['totalOfSales'] }}</td>
                <td>R$ {{ number_format($sale['totalSalesValue'], 2, ',', '.') }}</td>
                <td>R$ {{ number_format($sale['totalCommissionValue'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td>{{ $totals['quantityOfSales'] }}</td>
                <td>R$ {{ number_format($totals['sumOfSalesValue'], 2, ',', '.') }}</td>
                <td>R$ {{ number_format($totals['sumOfCommissionValue'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>

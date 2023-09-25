<!DOCTYPE html>
<html>
<head>
    <title>Relatório Diário de Vendas</title>

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
    <h1>Relatório Diário de Vendas</h1>
    <p>Segue o relatório de vendas realizadas em: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>Quantidade de Vendas</th>
                <th>Valor Total das Vendas</th>
                <th>Valor Total da Comissão</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalOfSales }}</td>
                <td>R$ {{ number_format($totalSalesValue, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($totalCommissionValue, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p>Obrigado pelo seu trabalho!</p>
</body>
</html>

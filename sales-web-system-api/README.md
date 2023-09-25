# Endpoints

### Vendedores (Sellers)
- **GET /api/sellers**: Retorna a lista de todos os vendedores.
- **POST /api/sellers**: Cria um novo vendedor.
- **GET /api/sellers/{id}**: Retorna os detalhes do vendedor com o ID especificado.
- **GET /api/sellers/{id}/sales**: Retorna as vendas associadas ao vendedor com o ID especificado.
- **POST /api/sellers/{id}/sales/send-report**: Envia um relatório para o vendedor com o ID especificado.
- **PUT /api/sellers/{id}**: Atualiza os detalhes do vendedor com o ID especificado.
- **DELETE /api/sellers/{id}**: Exclui o vendedor com o ID especificado.

### Vendas (Sales)
- **GET /api/sales**: Retorna a lista de todas as vendas.
- **POST /api/sales**: Registra uma nova venda.
- **GET /api/sales/daily-total**: Retorna o total de vendas diárias.
- **GET /api/sales/{id}**: Retorna os detalhes da venda com o ID especificado.
- **DELETE /api/sales/{id}**: Exclui a venda com o ID especificado.
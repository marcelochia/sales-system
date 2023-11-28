# Endpoints

### Vendedores (Sellers)
- **GET /api/sellers**: Retorna a lista de todos os vendedores.
- **POST /api/sellers**: Cria um novo vendedor.

    | Parâmetro | Tipo      | Descrição |
    | --------- | --------- | --------- |
    | name	    | string	| Obrigatório. Nome do vendedor. Deve conter entre 3 e 255 caracteres. |
    | email	    | string    | Obrigatório. Endereço de e-mail do vendedor. Deve ser um e-mail válido conforme o padrão RFC. |

    Exemplo:
    ```json
    {
        "name": "Nome do Vendedor",
        "email": "vendedor@example.com"
    }
    ```

- **GET /api/sellers/{id}**: Retorna os detalhes do vendedor com o ID especificado.
- **GET /api/sellers/{id}/sales**: Retorna as vendas associadas ao vendedor com o ID especificado.
- **POST /api/sellers/{id}/sales/send-report**: Envia um relatório para o vendedor com o ID especificado.

    | Parâmetro | Tipo  | Descrição |
    | --------- | ----- | --------- |
    | date      | date  | Obrigatório. Data do relatório no formato YYYY-MM-DD. |

    Exemplo:
    ```json
    {
        "date": "2023-01-01"
    }
    ```

- **PUT /api/sellers/{id}**: Atualiza os detalhes do vendedor com o ID especificado.

    | Parâmetro | Tipo      | Descrição |
    | --------- | --------- | --------- |
    | name	    | string	| Obrigatório. Nome do vendedor. Deve conter entre 3 e 255 caracteres. |
    | email	    | string    | Obrigatório. Endereço de e-mail do vendedor. Deve ser um e-mail válido conforme o padrão RFC. |

    Exemplo:
    ```json
    {
        "name": "Nome do Vendedor Atualizado",
        "email": "vendedor@example.com"
    }
    ```

- **DELETE /api/sellers/{id}**: Exclui o vendedor com o ID especificado.

### Vendas (Sales)
- **GET /api/sales**: Retorna a lista de todas as vendas.
- **POST /api/sales**: Registra uma nova venda.

    | Parâmetro | Tipo      | Descrição |
    | --------- | --------- | --------- |
    | value     | numeric	| Obrigatório. Valor da venda. |
    | date      | date	    | Obrigatório. Data da venda no formato YYYY-MM-DD. |
    | seller_id | integer	| Obrigatório. ID do vendedor associado à venda. |

    Exemplo:
    ```json
    {
        "value": 150.75,
        "date": "2023-01-01",
        "seller_id": 1
    }
    ```

- **GET /api/sales/daily-total**: Retorna o total de vendas diárias.
- **GET /api/sales/{id}**: Retorna os detalhes da venda com o ID especificado.
- **DELETE /api/sales/{id}**: Exclui a venda com o ID especificado.
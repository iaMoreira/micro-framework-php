# Micro Framework PHP

## Desafio 

Você foi convidado para desenvolver a API de um projeto. Para isso, foi escrito uma
pequena documentação das funcionalidades necessárias no projeto. Os desenvolvedores
frontend usarão a sua API para criar um aplicativo pessoal para monitorar quantas vezes o
usuário bebeu água.

### Regras de négocio:

- Na criação de um usuário, retornar um erro se o usuário já existe;
- No login, alertar que o usuário não existe ou que a senha está inválida;
- Na edição e na remoção do usuário, limitar-se apenas ao usuário autenticado;
- Paginação na lista de usuários (**Não implementado**);
- Criar um serviço que liste o histórico de registros de um usuário (retornando a data e a quantidade em mL de cada registro);
- Criar um serviço que liste o ranking do usuário que mais bebeu água hoje (considerando os ml e não a quantidade de vezes), retornando o nome e a quantidade em mililitros (mL).

### Observações de implementação:
- O projeto deve ser desenvolvido em PHP e com banco de dados relacional;
- Não deve ser utilizado nenhum framework (Laravel, Slim framework, Doctrine, etc.);
- Todas as entradas e saídas devem ser no formato JSON;
- Se possível, a API deve seguir o padrão REST;
- É desejável que o código use o método Programação Orientada a Objetos;
- Projetos plagiados serão desconsiderados.


## Instalação e Configuração

Instale as dependências:

`composer install`

Copie o arquivo de exemplo de configuração `config/database.example.ini` para `config/database.ini` e preeencha todas as informações necessárias para conectar ao banco de dados:  

`cp config/database.example.ini config/database.ini`


Insira as seguintes tabelas no banco de dados para executar a aplicação:

```sql
--- Exemplo em MySQL

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `drinks` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `drink_ml` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Por último, para testar a api configure o __Postmam__ com a coleção `postman/Api.postman_collection.json` e as variáveis de ambiente `postman/api.postman_environment.json`. A aplicação pode ser acessada a partir do arquivo `public/index.php`, para servidores com Apache é necessário acessar apenas por `http://{host}/public` ou configurar o VirtualHost direto para dentro dedo diretório `/public`.


### Lista de possíveis rotas para teste:

Operação            |       |  Entrada              | Saída | Header  | Middleware | Descrição |
--------------------|-------|-----------------------|-------|---------|-----------| ----|
**/login**          | POST  | email* password*      | **token** user_id email name drink_counter ||| autenticar com um usuário             |
**/users/**         | POST  | email* name* password*| (usuário cadastrado)              |        | |criar um novo usuário                  |
**/users/**         | GET   |                       | (array de usuários)               | token* | auth | obter a lista de usuários         |
**/users/:id**      | GET   |                       | user_id email name drink_counter  | token* | auth | informações do usuário         |
**/users/:id**      | PUT   | email name password   | (usuário atualizado)              | token* | auth owner | editar o usuário corrente|
**/users/:id**      | DELETE|                       |                                   | token* | auth owner | apagar o usuário corrente | 
**/users/:id/drink**| POST  | drink_ml              | user_id email name drink_counter  | token* | auth owner | incrementar o contador de quantas vezes bebeu água |
**/users/:id/drink**| GET   |                       | (array de drinks)                 | token* | auth | listar todas vezes que o usuário beberam até o momento |
**/users/drinks/ranking** | GET   |                 | (array de usuários com total de mls)| token* | auth | obter a ranking do dia |


## Considerações Finais
Esta aplicação PHP é baseado no modelo MVCS com adicionais de _Repository Pattern_ e _Template Method_. O código foi implementado utilizando principios do SOLID e as boas práticas de POO no PHP. O framework implementado teve inspiração com outros como Laravel e Spring Boot, por preferencia do autor. Apesar do app contemplar os requisitos propostos pelo desafio, 
ainda há itens que podem ser agregados para melhorias e consistência do mesmo, para que em outro momento possa derivar outros apps dessa mesma base do projeto. Esses itens são: TDD, Migrations, tratamento de exceções desconhecidas, adição de outros Patterns entre outros.

### Implementações Atuais
  - MVCS (Model View Controller Service)
  - Repository Pattern
  - Template Method
  - DI (Dependency Injection)
  - JWT (Json Web Token)
  - Middlewares
  - JsonResource
  - Handle Transaction

### Implementações Futuras
  - TDD
  - Migrations
  - Handle Exceptions
# Dataseed Teste Tecnico

## Descrição do Projeto
Este projeto foi desenvolvido para o teste técnico da Dataseed. O projeto consiste em um microserviço utilizando PHP provendo os itens abaixo:

-  Criar as API's públicas: 
    - login, cadastro e esqueci minha senha.
	
-  Criar as API's privadas:
	- API's CRUD de usuários contendo as informações: login, nome, email, senha, status 

## Tecnologias utilizadas
- PHP 8.1-fpm
- Laravel 10
- MySQL
- Docker
- Docker Compose 
- Nginx

## Requisitos
- [Docker](https://www.docker.com/)
- Docker Compose
- Make - Opcional 
    - > Utilizado para facilitar a execução dos comandos, se não tiver o make, basta executar os comandos do arquivo Makefile, que estão no arquivo Makefile na raiz do projeto, caso queira executar os comandos manualmente, basta abrir o arquivo Makefile e executar os comandos que estão dentro dele.
        Caso queira instalar o make, basta seguir esse breve artigo: [Instalando o make](https://linuxhint.com/install-make-ubuntu/)

## Instalação

Descompacte o arquivo .zip e acesse a pasta do projeto via terminal, após isso execute o comando abaixo:

```sh 
make cp // copia o arquivo .env.example para .env

make up
```
Depois de executar o comando acima, precisamos entar no container e instalar as dependecias do projeto, isso fara que a pasta vendor apareça, para isso execute o comando abaixo:

```sh
make shell

// Estando dentro do container execute o comando abaixo:
dev@3340c1570ca1:/var/www$ composer install
```
Vamos gerar a chave do projeto, para isso execute o comando abaixo:

```sh
dev@3340c1570ca1:/var/www$ php artisan key:generate

// Rodar as Migrações
dev@3340c1570ca1:/var/www$ php artisan migrate
``` 

## Testando as API's
Use o [Postman](https://www.postman.com/) ou [Insomnia](https://insomnia.rest/) para testar as API's, abaixo segue a documentação das API's.

## Documentação das API's

### `API's Públicas`

#### Login
- Endpoint: http://localhost/api/login
- Método: POST
- Body (JSON): 
    - email: string
    - password: string
- Retorno (JSON):
    - token: string (Token JWT, que sera usado nas rotas que exigirem autenticação)
    - token_type: string
    - expires_in: int

#### Cadastro
- Endpoint: http://localhost/api/register
- Método: POST
- Body (JSON): 
    - login: string
    - name: string
    - email: string
    - password: string
- Retorno (JSON):
    - Status: 204(No Content)

#### Esqueci minha senha
- Endpoint: http://localhost/api/forgot-password
- Método: POST
- Body (JSON): 
    - email: string
- Retorno (JSON):
    - Status: 200(Email enviado com sucesso!)
> Obs: Este endpoint vai enviar um email com um codigo de 4 caracteres para o usuário redefinir a senha. O código sera enviado para o email inserido no body da requisição. Por isso não esqueça de configurar o email no arquivo .env. Caso não possa o fazer isso, basta fazer uma consulta na base de dados na tabela `password_reset_tokens`, que tera o código gerado para o email inserido no body da requisição.

### `API's Privadas`

#### Listar usuários
- Endpoint: http://localhost/api/users
- Método: GET
- Header:
    - Authorization: Bearer {token}
- Retorno (JSON):
    - users: array
        - id: int
        - login: string
        - name: string
        - email: string
        - status: string
    
#### Criar usuário
- Endpoint: http://localhost/api/users
- Método: POST
- Header:
    - Authorization: Bearer {token}
- Body (JSON):
    - login: string
    - name: string
    - email: string
    - password: string
    - status: string( active | disabled ) - Opcional

- Retorno (JSON):
    - status: 201(Created)

#### Atualizar usuário
- Endpoint: http://localhost/api/users/{id}
- Método: PUT
- Header:
    - Authorization: Bearer {token}
- Body (JSON):
    - login: string
    - name: string
    - email: string
    - password: string
    - status: string( active | disabled ) - Opcional

- Retorno (JSON):
    - status: 200(OK)

#### Deletar usuário
- Endpoint: http://localhost/api/users/{id}
- Método: DELETE
- Header:
    - Authorization: Bearer {token}
- Retorno (JSON):
    - status: 200(OK)

## Testes Unitários
Para executar os testes unitários, basta executar o comando abaixo:

```sh
make shell

// Estando dentro do container execute o comando abaixo:
dev@3340c1570ca1:/var/www$ php artisan test
```

## Autor

👤 **Tilson Mateus**

* Website: https://tilsonmateus.com


## 📝 Duvidas

Se alguma coisa não ficou claro ou você tem duvidas sobre o projeto, basta me enviar um email, que eu protamente irei te responder.
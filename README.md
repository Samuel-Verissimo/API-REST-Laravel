# Backend Challenge 20230105

## Descrição 
Esse projeto tem com o objetivo importar etc etc etc

## Tecnologias 
- Backend: PHP - Laravel 11 
- Frontend: Swagger Lib 
- Banco de dados: SQLite

## Como rodar o projeto? 
1. Faça o clone do projeto
2. Navegue até o diretório `api-food`, execute `docker-compose up --build -d` e aguarde até a conclusão
3. Crie o arquivo `.env` com base em `.env.example` 
4. Aguarde de 4 a 8 minutos, após a execução do container está sendo instalado as dependências necessárias
5. Acesse a API em `http://localhost:8444/api/documentation` e pronto! =D
6. Caso encontrar problemas, tente acessar o container e reiniciar o apache `service apache2 restart`

## Plano B
1. Caso tenha problemas para rodar com o Docker, basta ter o `PHP` e `Composer` instalado em sua máquina. 
2. Navegue até o diretório `api-food`, execute `composer install && php artisan serve && php artisan schedule:work` 
3. Acesse a API em `http://localhost:8444/api/documentation` e pronto! =D

## Observações / Anotações

>  V4 [Link](https://coodesh.com/pt/assessments/project/7cc0e28f-c9b9-4e2f-ad83-2b51f9b5feb4/intro)
>  This is a challenge by [Coodesh](https://coodesh.com/)
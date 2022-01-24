## Sobre o Projeto

Uma mini ecommerce para uma loja de discos.

## Processo de tomada de decisões
Decidi usar Laravel e ao mesmo tempo estudar as novas features das suas versões mais recentes. 
Já usamos a última versão, porém atualizamos apenas correções de segurança, as novas features
nunca são implementadas.
Além disso, o Laravel foca muito de suas melhorias em bancos relacionais, o que não uso no meu dia a dia.

### Laravel Sail
O Ecossistema Laravel antigamente usava o vagrant e até mesmo esperava que usuários instalassem as ferramentas diretamente na máquina local.
Com a chegada do Docker eles começaram a desenvolver essa ferramenta.
Um dos focos do Laravel é tornar as coisas acessíveis pra todos utilizarem de forma fácil.
Laravel Sail foi a resposta pra isso. Por ser algo novo, decidi usar para aprender sobre também. Ele nada mais é que um docker-compose on steroids.


## Instalação.

Após clonar o repositório, precisamos de alguns passos para rodar a aplicação local.
A primeira coisa que precisamos fazer é instalar as dependências do laravel.
Considerando que você não tenha o PHP nem o composer na máquina, aqui está um comando para você rodar a instalação das dependências:

### Instalando as dependências

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Este comando vai baixar uma máquina com o composer instalado e irá instalar as dependências do projeto.

### Alias
Este alias vai ajudar a usar os atalhos do sail

```shell
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
Assim poderemos usar os comandos usando apenas `sail`.

### Copiando as variáveis de ambiente.

```shell
cp .env.example .env
```

### Key:generate
Precisamos gerar uma chave de criptografia para o projeto.
```shell
sail artisan key:generate
```

### Migrations
Se você for querer rodar os endpoints das APIs na sua máquina, então precisamos rodar as migrations
```shell
sail artisan migrate
```

Este comando irá preparar o banco de dados para receber os acessos.

### Testes
Todos os endpoints estão cobertos por testes, para testa-los, basta rodar:

```shell
sail artisan test
```

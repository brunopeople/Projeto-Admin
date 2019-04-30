#Projeto Admin 

Consiste de um projeto sisteminha de cadastros onde usuarios possam ser cadastrados, editados, atualizados e excluidos. Essas ações serão apenas designiadas ao administrador do sistema que terá controle total sobre estes dados. Desenvolvido pelo Framework PHP Laravel na versão mais atualizada que é a 5.8.14.

@Feito Por: Bruno Pessoa Nunes de Melo
@Ocupação: Estudante de Pós-graduação do Curso Desenvolvimento de Aplicações Móveis pelo Instituto Métropole Digital/Desenvolvedor Full Stack Jr
@Objetivo: Processo Seletivo da LessClick para a Vaga de Desenvolvedor Full Stack Jr.
@Data: 30/04/2019


#Criando o Projeto

O primeiro passo para construção do projeto é baixar a versão mais atualizada do Laravel. Após isto digitar o comando para criarmos o projeto.

"composer create-project laravel/laravel=5.8 Admin --prefer-dist"


#Conexão com MySQL com Laravel

Encontrar o arquivo .env no diretório do arquivo do projeto, com isto configurar o arquivo com as seguintes parametros

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=admin
DB_PASSWORD=admin123

Ou podemos configurar o arquivo na pasta config/database.php definir o  arquivo com as seguintes configurações 


'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'test'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'admin123'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]),
        ],

#Migrate Table do Laravel 5.8 para o MySQL Banco

"php artisan make:migration create_crud_table --create=crud"

Este comando serve par criar um arquivo do tipo miagration no diretório database/migrations. Neste Arquivo definir a coluna da tabela no qual nós queremos criar a tabela. 


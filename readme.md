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



<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('primeiro_nome');
            $table->string('ultimo_nome');
            $table->string('image');
            $table->timesptamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crud');
    }
}

Agora temos migrar a tabela de Definição vindo da Aplicação do Laravel para o banco de dados. Este comando no prompt vai fazer a tabela Crud realizar as ações de CRUD do Aplicativo. 

"php artisan migrate"


#Criar o arquivo Model

O arquivo model é responsavel pela operações relacionada com o banco de dados na Class Controller. Com isto temos digitarmos no prompt para criar o arquivo. 


"php artisan make:model Crud -m"


o Comando vai criar o arquivo no diretório app e o seu código-fonte será deste jeito.


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    protected $fillable = [
    	'primeiro_nome','ultimo_nome','image'
    ]
}



#Criando o Controller in Laravel

Arquivos controllers são usados basicamente para lidar com as requisições http em Laravel. Para isto digite no prompt para criar o arquivo CrudController.php

"php artisan make:controller CrudsController --resource"

Este comando criar o arquivo CrudsController no diretório app/Http/Controllers. Após a sua criação todo a hierarquia de estruturas de pastas já está sendo configurada automaticamente e com isto todos as funções que serão necessárias para os CRUDS no arquivo Controller.



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Crud::laster()->paginate(5);
        return view('index',compact('data'))
                ->with('i',(request()->input('page',1)-1)*5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        'primeiro_nome'     => 'required',
        'ultimo_nome'       =>  'required',
        'image'             =>  'required|image|max:2048'

        ]);

        $image = $request->file('image');

        $new_name = rand(). '.' . $image->getClientOriginalExtension();
        $image->move(public_path('image'),$new_name);
        $form_data = array(
            'primeiro_nome'     =>     $request->primeiro_nome,
            'ultimo_nome'       =>     $request->ultimo_nome,
            'image'             =>     $new_name
        );

        Crud::create($form_data);

        return redirect('crud')->width('sucesso','Cadastro Realizado com Sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Crud::findOrFail($id);
        return view('view',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crud::findOrFail($id);
        return view('edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image_name = $request->hidden_image;
        $image = $request->file('image');
        if($image!= '')
        {
            $request->validate([
             'primeiro_nome'    =>  'required',
             'ultimo_nome'      =>  'required',
             'image'            =>  'image|max:2048'

            ]);

            $image_name = rand(). '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        }

        else 
        {
            $request->validate([
                'primeiro_nome'     => 'required',
                'ultimo_nome'       => 'required'
            ]);
        }

        $form_data = array(
            'primeiro_nome'         => $request->primeiro_nome,
            'ultimo_nome'           => $request->ultimo_nome,
            'image'                 => $image_name
        );

        Crud::whereId($id)->update($form_data);
        return redirect('crud')->width('sucess','atualização feito com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Crud::findOrFail($id);
        $data->delete();

        return redirect('crud')->width('sucesso','O cadastro foi deletado');
    }
}


No index() - Em Laravel o Controller Crud index() é o método raiz da class CrudController, então quando o navegador temos que entrar na url base com o nome do controller, daí ele será chamado o método index(). Por debaixo deste método iremos escrever o código para mostrar os dados vindos do arquivo MySQL. Neste código iremos primeiro pegar o dado vindo da tabela e sobre a variavel $data, depois disso nós queremos fazer uma paginação um link usando o método paginate() método in Laravel 5.8. Para sendo o dado arquivo view, para isto nós temos usar o método view() para enviar o arquivo. O código fonte podemos encontrar no método index(). 

Create()- Este método está sendo usado para carregar no arquivo create.blade.php. Neste arquivo o usuário pode inserir no formulario os dados para serem adicionados novos dados que serão arquivados no método store() vindo da classe CrudsController.php


Store()- Este método havia recebido os comandos de adição ou inserção novo cadastrados recebidos pelo método create(). Neste método, irá performar duas operações. Uma delas é fazer o upload do arquivo imagem sendo usado pelo método move() e segundo para a inserção de novos dados para a tabela do MySQL com o classe Model. Após esta ação aparecerá uma mensagem de sucesso desta ação. 

edit()- Este Método como ele mesmo diz é a edição dos dados referentes que se encontram no banco de dados e carregados para a edição ou atualização para qualquer mudanças de dados. 


update()- Método responsável pela atualização. Este método realiza duas ações como carregar a imagem do profile e atualizar os dados no banco de dados. 

delete() - Método responsável pela exclusão de um dado no bannco de dados. 


#Criar as Rodas do Aplicativo

Aqui criaremos a rota da classe CrudController. Para isto nós temos que abir o arquivo no diretório routes/web.php. Neste arquivo temos que seguir o código setar as rotas onde estas classes se encontram para serem chamadas a cada requisição. 



Route::get('/', function () {
    return view('welcome');
});

Route::resource('crud','CrudsController');


#Editando as Views 

A partir de agora será apenas implmentação do código HTML junto com blade. 

#resources/views/parent.blade.php

<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel 5.8 Crud Tutorial</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
 </head>
 <body>
  <div class="container">    
     <br />
     <h3 align="center">Cadastro Clientes</h3>
     <br />
     @yield('main')
    </div>
 </body>
</html>


#index.blade.php


@extends('parent')

@section('main')

<table class="table table-bordered table-striped">
 <tr>
  <th width="10%">Image</th>
  <th width="35%">First Name</th>
  <th width="35%">Last Name</th>
  <th width="30%">Action</th>
 </tr>
 @foreach($data as $row)
  <tr>
   <td><img src="{{ URL::to('/') }}/images/{{ $row->image }}" class="img-thumbnail" width="75" /></td>
   <td>{{ $row->first_name }}</td>
   <td>{{ $row->last_name }}</td>
   <td>
    
   </td>
  </tr>
 @endforeach
</table>
{!! $data->links() !!}
@endsection


#create.blade.php


@extends('parent')

@section('main')
@if($errors->any())
<div class="alert alert-danger">
 <ul>
  @foreach($errors->all() as $error)
  <li>{{ $error }}</li>
  @endforeach
 </ul>
</div>
@endif
<div align="right">
 <a href="{{ route('crud.index') }}" class="btn btn-default">Back</a>
</div>

<form method="post" action="{{ route('crud.store') }}" enctype="multipart/form-data">

 @csrf
 <div class="form-group">
  <label class="col-md-4 text-right">Digite o primeiro Nome:</label>
  <div class="col-md-8">
   <input type="text" name="primeiro_nome" class="form-control input-lg" />
  </div>
 </div>
 <br />
 <br />
 <br />
 <div class="form-group">
  <label class="col-md-4 text-right">Digie o ultimo nome:</label>
  <div class="col-md-8">
   <input type="text" name="ultimo_nome" class="form-control input-lg" />
  </div>
 </div>
 <br />
 <br />
 <br />
 <div class="form-group">
  <label class="col-md-4 text-right">Selecione imagem de Perfil</label>
  <div class="col-md-8">
   <input type="file" name="image" />
  </div>
 </div>
 <br /><br /><br />
 <div class="form-group text-center">
  <input type="submit" name="add" class="btn btn-primary input-lg" value="Add" />
 </div>

</form>

@endsection


#view.blade.php

@extends('parent')

@section('main')

<div class="jumbotron text-center">
 <div align="right">
  <a href="{{ route('crud.index') }}" class="btn btn-default">Back</a>
 </div>
 <br />
 <img src="{{ URL::to('/') }}/images/{{ $data->image }}" class="img-thumbnail" />
 <h3>Primeiro Nome - {{ $data->primeiro_nome }} </h3>
 <h3>Ultimo Nome - {{ $data->ultimo_nome }}</h3>
</div>
@endsection



#edit.blade.php


@extends('parent')

@section('main')
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div align="right">
                <a href="{{ route('crud.index') }}" class="btn btn-default">Back</a>
            </div>
            <br />
     <form method="post" action="{{ route('crud.update', $data->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
      <div class="form-group">
       <label class="col-md-4 text-right">Digite o Primeiro Nome:</label>
       <div class="col-md-8">
        <input type="text" name="primeiro_nome" value="{{ $data->primeiro_nome }}" class="form-control input-lg" />
       </div>
      </div>
      <br />
      <br />
      <br />
      <div class="form-group">
       <label class="col-md-4 text-right">Digite o ultimo Nome</label>
       <div class="col-md-8">
        <input type="text" name="ultimo_nome" value="{{ $data->ultimo_nome) }}" class="form-control input-lg" />
       </div>
      </div>
      <br />
      <br />
      <br />
      <div class="form-group">
       <label class="col-md-4 text-right">Selecione a imagem do perfil</label>
       <div class="col-md-8">
        <input type="file" name="image" />
              <img src="{{ URL::to('/') }}/images/{{ $data->image }}" class="img-thumbnail" width="100" />
                        <input type="hidden" name="hidden_image" value="{{ $data->image }}" />
       </div>
      </div>
      <br /><br /><br />
      <div class="form-group text-center">
       <input type="submit" name="edit" class="btn btn-primary input-lg" value="Edit" />
      </div>
     </form>

@endsection










<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

 class ClientesController extends Controller{
	public function  index(Request $request){
	var_dump($request->query());
	exit();

		$clientes = [
		'Alyson Souza',
		'Anderson Silva',
		'Maria Lima',
		'Pedro Paulo',
		'Denilson Moraes'
	];

	$html = "<ul>";
	foreach ($clientes as $cliente) {
		$html .= "<li>$cliente</li>";
	}

	$html .= "</ul>";


	return $html;
	}
}
<?php 
class folha_funcionariosModel extends Model{
 
	public $tipos = array('folha'    => 'inteiro',
						  'funci'	 => 'inteiro',
						  'nome'	 => 'texto',
						  'cpf'	     => 'texto',
						  'rg'		 => 'texto',
						  'salario'  => 'moeda',
						  'inss'     => 'moeda',
						  'admissao' => 'data',
						  'taxa'     => 'moeda',
						  'cargo' 	 => 'texto',);

	public $limiteDeLinhas = 200000;
}
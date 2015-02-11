<?php 
class informacoesModel extends Model 
{
 
	public $tipos = array('nome'			 => 'nome',
						  'logo'			 => 'imagem',
						  'pastor'			 => 'nome',
						  'cnpj'			 => 'texto',
						  'estado'			 => 'estado',
						  'cidade'			 => 'cidade',
						  'bairro'		     => 'texto',
						  'rua'				 => 'texto',
						  'numero'			 => 'texto',
						  'telefone'		 => 'telefone',
						  'email'			 => 'email',
						  'site'			 => 'texto',
						  'face'			 => 'texto',
						  'qtd_membros' 	 => 'inteiro',
						  'qtd_usuarios' 	 => 'inteiro',
						  'total_patrimonio' => 'moeda',
						  'saldo'			 => 'moeda');

	public function novoNome($nomeImagem)
	{
		$extensao = $this->pegaExtensao($nomeImagem);
		$novoNome = 'logo.' . $extensao;

		return $novoNome;
	}

}
?>
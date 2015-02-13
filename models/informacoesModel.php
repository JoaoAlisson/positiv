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
						  'total_patrimonio' => 'moeda',
						  'saldo'			 => 'moeda',
						  'plano'			 => 'inteiro',
						  'ativo'			 => 'inteiro');

	public function novoNome($nomeImagem)
	{
		$extensao = $this->pegaExtensao($nomeImagem);
		$novoNome = 'logo.' . $extensao;

		return $novoNome;
	}

	public function qtdMembros()
	{
		$query = $this->prepare('SELECT membros FROM ' . PREFIXO . 'qtds WHERE id = 1');
		$query->execute();
		$qtd = $query->fetchAll(PDO::FETCH_ASSOC);
		return $qtd[0]['membros'];
	}
}
?>
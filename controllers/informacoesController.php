<?php
class informacoes extends Controller
{
	public $nome = array("Infomações da Igreja", "");

	public $campos = array('nome'			  => 'Nome da Igreja',
						   'logo'			  => 'Logomarca',
						   'pastor'			  => 'Pastor Responsável',
						   'cnpj'			  => 'CNPJ',
						   'estado'			  => 'Estado',
						   'cidade'			  => 'Cidade',
						   'bairro'		      => 'Bairro',
						   'rua'			  => 'Rua',
						   'numero'		  	  => 'Número',
					       'telefone'		  => 'Telefone',
						   'email'			  => 'Email',						   
						   'site'			  => 'Site da Igreja',
						   'face'			  => 'Facebook',
						   'qtd_membros' 	  => 'Qtd de Membros',
						   'qtd_usuarios' 	  => 'Qtd de Usuários',
						   'total_patrimonio' => 'Total de Patrimônio',
						   'saldo'			  => 'Saldo de Caixa');
	
	public $cor = "teal";

	public $inalteraveis = array('qtd_membros','qtd_usuarios', 'total_patrimonio', 'saldo');

	public $obrigatorios = array('nome');

	public $icone = "university";

	public $regraUsuarios = array("Administrador" => "tudo");

	public function index()
	{
		$id = "1";

		$retorno = $this->model->visualizar($id);
		$retorno['nome'] = $this->nome;
		$retorno['id'] = $id;
		$campos =  $this->campos;

		$retorno['campos'] = $campos;
		$retorno['tipos']  = $this->model->tipos;	
		$retorno['cor']    = $this->informacoes['cor'];
		$retorno['icone']  = $this->informacoes['icone'];
		$retorno['dono']   = Sessao::pegar('dono');

		$this->dados($retorno);		
	}

	public function editar() 
	{
		$id = "1";

		if(Sessao::pegar('dono') != 1)
			header('location: ' . URL . 'informacoes/');


		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require RAIZ . SEPARADOR . "views" . SEPARADOR . "erro" . SEPARADOR ."negado.phtml";
				exit();
			}else{ 
				header('location: '.URL.'erro/negado');
			}
		}
				
		$retorno;

		if(isset($_POST['id'])){
			$campos = array();
			foreach ($this->campos as $key => $value) {
				$inalt = false;
				if(isset($this->inalteraveis)){
					if(in_array($key, $this->inalteraveis))
						$inalt = true;
				}
				if($inalt == false)
					$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";
			}
			$this->usarLayout(false);
			$retornou = $this->model->atualizar($campos, $_POST['id']);
			$retorno['retorno'] = $retornou; 					
		}else{

			$retorno = $this->model->pegar($id);
			$retorno['nome'] = $this->nome;

			$campos =  $this->campos;
			foreach ($campos as $campo => $value) {
				if(isset($this->inalteraveis)){
					if(in_array($campo, $this->inalteraveis))
						unset($campos[$campo]);
				}
			}			

			$retorno['campos'] = $campos;	
			$retorno['cor'] = $this->informacoes['cor'];
			$retorno['icone'] = $this->informacoes['icone'];
		}
		$retorno['id'] = $id;
		$this->dados($retorno);
	}

	public function imagens(){

		if(!isset($this->GET['img']))
			exit();

		if($this->GET['img'] == "")
			exit();

		$this->usarLayout(false);
		$imagem['img'] = $this->GET['img'];
		$this->dados($imagem);
		$this->renderizar("CRUD/imagens");
	}	
}
?>
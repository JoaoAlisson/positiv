<?php 
class visitantes extends ControllerCRUD{

	public $nome = array("Visitante","Visitantes");

	public $cor = "teal";

	public $icone = "male";

	public $campos = array("nome"     	=> "Nome",
						   "face"	    => "Facebook (http://facebook.com/exemplo)",
						   "cpf"		=> "CPF",
						   "nascimento" => "Data de Nascimento",
						   "sexo"		=> "Sexo",
 						   "estadocivil"=> "Estado Civil",
 						   "conjuge"	=> "Conjugue",
 						   "igreja"     => "Igreja",
 						   "telefone"   => "Telefone",
 						   "celular"	=> "Celular",
 						   "profissao"  => "Profissão",
 						   "email"	    => "Email",
 						   "estado"     => "Estado",
 						   "cidade"	    => "Cidade",
 						   "bairro"     => "Bairro",
 						   "rua"	    => "Rua",
 						   "numero"	    => "Numero",
						   "observacoes"=> "Observações");

	public $icones = array("nome" => "user");

	public $placeholders = array("nome" => "Isira seu nome");

	public $listar = array("face","nome", "celular", "nascimento");

	public $filtros = array("nome");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function index(){
		parent::index();
		$this->renderizar("visitantes/index");
	}

	public function aniversariantes(){
	//pega os quatro primeiros campos, menos campos do tipo imagem
		$cont = 0;
		$campos = array();
		if(isset($this->listar)){
			foreach ($this->listar as $key => $campo) {
				if($this->model->tipos[$campo] == "facebook")
					$campos[$campo] = "facebook";
				else
					$campos[$campo] = $this->campos[$campo];
			}
		}else{
			foreach ($this->campos as $campo => $nomeDoCampo) {
				if($cont == 4)
					break;

				if($this->model->tipos[$campo] != 'imagem')
					$campos[$campo] = $nomeDoCampo;
				$cont++;
			}
		}

		$filtros = array();

		$retorno['nome'] = $this->nome;
		$retorno['campos'] = $campos;

		if(isset($_POST['filtro'])){
			$retorno['filtro'] = true;
			$this->usarLayout(false);
		}else{
			if(isset($this->filtros)){
				foreach ($this->filtros as $key => $filtro)
					$filtros[$filtro] = $this->campos[$filtro];
				$retorno["filtros"] = $filtros;
			}
		}

		$camposPegar = array_keys($campos);
		array_push($camposPegar, "id");

		$pagina = isset($this->GET['pag']) ? $this->GET['pag'] : null;
		$ordem = "";
		//valida campos informados para ordenação
		if(isset($this->GET['ordem'])){
			$ordem = isset($this->campos[$this->GET['ordem']]) ? $this->GET['ordem'] : null;
		}else{
			$ordem = null;
		}

		//valida se o valor de ordenação eh "ASC" ou "DESC"
		$ordenacao = "";
		if(isset($this->GET['ordenacao'])){
			if($this->GET['ordenacao'] != "ASC" && $this->GET['ordenacao'] != "DESC")
				$ordenacao = null;
			else
				$ordenacao = $this->GET['ordenacao'];
		}else{
			$ordenacao = null;
		}

		if($ordem == "" && $ordenacao == ""){
			$ordem = "aniversario";
			$ordenacao = "ASC";
		}		

		if(!isset($campos[$ordem]) && $ordem != null)
			$ordem = null;

		$onde = array();
		$filtrosValores = array();
		if(isset($this->filtros)){
			foreach ($this->filtros as $key => $filtro) {
				if (isset($this->GET[$filtro])) {
					if ($this->GET[$filtro] != "") {
						$onde[$filtro] = $this->GET[$filtro];
						$filtrosValores[$filtro] = $this->GET[$filtro];
					}
				}
			}
		}

		//if(!isset($_POST['filtro']))
			$retorno['filtrosValores'] = $filtrosValores;

		$mes = "";
		$retorno['mes'] = 0;
		if(isset($this->filtros)){
			if(isset($this->GET['mes'])){
				$mes = (int)$this->GET['mes'];
				$retorno['mes'] = $mes;
				if($mes == "")
					$mes = "DAY(`".PREFIXO."visitantes`.`nascimento`) = day(NOW())";
				else
					$mes = "MONTH(`".PREFIXO."visitantes`.`nascimento`) = $mes";
			}
		}
		if($mes == "")
			$mes = "DAY(`".PREFIXO."visitantes`.`nascimento`) = day(NOW())";

		$qtdPorPg = isset($this->qtdPorPagina) ? $this->qtdPorPagina : 8;
		$busca = $this->model->pegarPagina($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao, null, $mes);

		$itens = $busca[$this->informacoes['nomeController']];

		foreach ($itens as $linha => $campos) {
			foreach ($campos as $campo => $valor) {
				if(!isset($retorno['campos'][$campo]) && $campo != "id")
					unset($itens[$linha][$campo]);
			}
		}

		$retorno['itens'] = $itens;
		$retorno['tipos'] = $this->model->tipos;
		$retorno['controller'] = $this->informacoes['nomeController'];
		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];
		$retorno['qtdPaginas'] = $busca['qtdPaginas'];
		$retorno['qtd'] = $busca['qtd'];
		$retorno['pagina'] = $busca['pagina'];
		$retorno['ordem'] = $ordem;
		$retorno['ordenacao'] = $ordenacao;

		$this->dados($retorno);
	}	
}
?>
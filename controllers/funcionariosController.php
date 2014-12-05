<?php 
class funcionarios extends ControllerCRUD{

	public $nome = array("Funcionário","Funcionários");

	public $campos = array("membro"		=> "Membro",
						   "func"		=> "Func",
						   "cargo"		=> "Cargo",
						   "salario"	=> "Salário",
						   "inss"		=> "Calcular INSS",
						   "descricao"  => "Observações");

	public $cor = "black";

	public $obrigatorios = array("membro", "func");

	public $icone = "male";

	public $listar = array("cargo", "salario");

	public $filtros = array("cargo");

	public $regraUsuarios = array("Administrador" => "tudo", "Atendente" => "ver");

	public $qtdPorPagina = 10;
	private $tipoIndex = 1;

	public function cadastrar(){
		
		$retorno = array();

		if(isset($_POST['subMembro'])){

			$this->usarLayout(false);

			if($_POST['membro'] != 0 && $_POST['membro'] != ""){

				$inss = (isset($_POST['inss'])) ? 1 : 0;
				$campos = array("membro" 	=> $_POST['membro'],
								"func"		=> 0,
								"cargo"  	=> $_POST['cargo'],
								"salario"	=> $_POST['salario'],
								"inss"      => $inss,
								"descricao" => $_POST['descricao']);

				$retornou = $this->model->inserir($campos);
				$retorno['retorno'] = $retornou; 
			}

		}

		if(isset($_POST['subNaoMembro'])){

			$this->usarLayout(false);

			if($_POST['cargo2'] == 0 || $_POST['cargo2'] == ""){
				$retorno['retorno'][0] = "erro";
				$retorno['retorno'][1] = "O funcionário não foi cadastrado";
				$retorno['retorno'][2]['cargo2'][0] = "Campo Obrigatório"; 
			}else{
				require RAIZ . SEPARADOR . "models" . SEPARADOR . "func_nao_membroModel.php";

				$nMembro = new func_nao_membroModel();

				$campos = array();
				$tipos = $this->outrosCampos();
				foreach ($tipos as $key => $value) 
					$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";

				$retornou = $nMembro->inserir($campos);
				$retorno['retorno'] = $retornou;
			}

		}

		$campos = $this->outrosCampos();

		$aba = (isset($this->GET['ab'])) ? $this->GET['ab'] : 1;
		$aba = ($aba != 2) ? 1 : 2;

		$retorno['aba'] = $aba;

		$retorno['campos'] = $campos;
		$this->dados($retorno);
	}

	public function index(){
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
		$ar["nome"] = "nome";
		$retorno['campos'] = array_merge($ar, $retorno['campos']);
		
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

		if(!isset($campos[$ordem]) && $ordem != null)
			$ordem = null;

		$onde = array();
		$filtrosValores = array();
		if(isset($this->filtros)){
			array_push($this->filtros, "nome");
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

		$qtdPorPg = isset($this->qtdPorPagina) ? $this->qtdPorPagina : 8;
		unset($camposPegar['nome']);
		$busca = $this->model->pegarPaginaThis($pagina, $qtdPorPg, $camposPegar, $onde, null, $ordem, $ordenacao);

		//print_r($busca);
		$itens = $busca[$this->informacoes['nomeController']];

		foreach ($itens as $linha => $campos) {
			foreach ($campos as $campo => $valor) {
				if(!isset($retorno['campos'][$campo]) && $campo != "id"){
						unset($itens[$linha][$campo]);
				}else{
					if($campo == "nome"){
						$nome = $itens[$linha][$campo];
						unset($itens[$linha][$campo]);
						$ar['nome'] = $nome;
						$itens[$linha] = array_merge($ar, $itens[$linha]);
					}					
				}				
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

	private function outrosCampos(){
		   $tipos = array("nome"       => "nome",
						  "face"	   => "facebook",
						  "cpf"		   => "cpf",
						  "rg"		   => "texto",
						  "sexo"	   => "sexo",
						  "foto"       => "imagem",
						  "estadocivil"=> array("Solteiro", "Casado", "Divorciado"),
						  "conjuge"	   => "texto",
					      "nascimento" => "data",
					      "telefone"   => "telefone",
					      "celular"	   => "telefone",
					      "email"	   => "email",
					      "estado"     => "estado",
					      "cidade"	   => "cidade",
					      "bairro"     => "texto",
					      "rua"		   => "texto",
					      "numero"	   => "texto");
		return $tipos;
	}
}
?>
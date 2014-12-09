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

	public function editar(){

		$id = "";
		if(isset($this->GET['cod']))
			$id = $this->GET['cod'];
		if(isset($_POST['idSet']))
			$id = $_POST['idSet'];
		if(isset($_POST['id']))
			$id = $_POST['id'];

		if($id == "")
			header('location: '. URL . $this->nomeController());


		if($this->permissao == "ver" || $this->permissao == "nenhuma"){
			if(isset($_POST['ajaxPg'])){
				require "views/erro/index.php";
				exit();
			}else{ 
				header('location: '.URL.'erro');
			}
		}
				
		$retorno;

		if(isset($_POST['id'])){

			$this->usarLayout(false);
			$retorno = $this->model->pegar($_POST['id'], array('func', 'membro'));
			$idFunc = $retorno['funcionarios']['func'];
			$idMembro = $retorno['funcionarios']['membro'];

			if($idMembro != 0){
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
				
				unset($campos['membro']);
				unset($campos['func']);
				$retornou = $this->model->atualizar($campos, $_POST['id']);
				$retorno['retorno'] = $retornou;
			}else{
				
				if($_POST['cargo'] == 0 || $_POST['cargo'] == ""){
					$retorno['retorno'][0] = "erro";
					$retorno['retorno'][1] = "O funcionário não foi cadastrado";
					$retorno['retorno'][2]['cargo'][0] = "Campo Obrigatório"; 
				}else{

					$outrosCampos = $this->outrosCampos();
					$campos = array();
					foreach ($outrosCampos as $key => $value)
						$campos[$key] = isset($_POST[$key]) ? $_POST[$key] : "";

					require RAIZ . SEPARADOR . 'models' . SEPARADOR . 'func_nao_membroModel.php';
					$modelFunc = new func_nao_membroModel();
					$retornou = $modelFunc->atualizar($campos, $idFunc);
					$retorno['retorno'] = $retornou;

					if($retorno['retorno'][0] == 'ok'){
						$inss = (isset($_POST['inss'])) ? 1 : 0;
						$campos = array("cargo"  	=> $_POST['cargo'],
										"salario"	=> $_POST['salario'],
										"inss"      => $inss,
										"descricao" => $_POST['descricao']);

						$retornou = $this->model->atualizar($campos, $_POST['id']);
						$retorno['retorno'] = $retornou;						
					}
				}
			}				
		}else{

			$retorno = $this->model->pegar($id);
			$retorno['nome'] = $this->nome;

			$idMembro = $retorno['funcionarios']['membro'];
			if($idMembro != 0){
				$nomeFuncionario = $this->model->pegar($idMembro, array("nome"), "membros");
				$nomeFuncionario = $nomeFuncionario['membros']['nome'];

				$nomeFuncionario = "<a onclick=\"redir('membros', '$idMembro')\">$nomeFuncionario</a>";
			}else{
				$idFunc = $retorno['funcionarios']['func'];
				$valores2 = $this->model->pegar($idFunc, null, "func_nao_membro");
				$nomeFuncionario = $valores2['func_nao_membro']['nome'];
				$this->renderizar('func_nao_membro/editar');		
				$retorno['campos2'] = $this->outrosCampos();
				$retorno['valores2'] = $valores2['func_nao_membro'];

			}
			$retorno['nomeFuncionario'] = $nomeFuncionario;
	
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

	public function deletar(){
		
		$retorno;
		if(!isset($_POST['id']) || !isset($_POST['model'])){
			$retorno['flag'] = "erro";
			$retorno['mensagem'] = "Não foi possível deletar";
		}else{
			$id = $_POST['id'];
			
			$idFunc = $this->model->pegar($id, array("func"));
			$idFunc = $idFunc['funcionarios']['func'];	
			if($idFunc != "" && $idFunc != 0){

				require RAIZ . SEPARADOR . 'models' . SEPARADOR . 'func_nao_membroModel.php';
				$func = new func_nao_membroModel();	
				$func->deletar($idFunc);			
			}
			$retorno = $this->model->deletar($id);		
		}
	
		echo json_encode($retorno);
	}	

	public function visualizar(){
		$id = "";
		if(isset($this->GET['cod']))
			$id = $this->GET['cod'];
		if(isset($_POST['idSet']))
			$id = $_POST['idSet'];

		if($id == "")
			header('location: '. URL . $this->nomeController());

		$retorno = $this->model->visualizar($id);
		$retorno['nome'] = $this->nome;
		$retorno['id'] = $id;
		$campos =  $this->campos;

		$retorno['campos'] = $campos;
		$retorno['tipos'] = $this->model->tipos;

		if($retorno['funcionarios']['inss'] == 1)
			$retorno['funcionarios']['inss'] = "Sim";
		else
			$retorno['funcionarios']['inss'] = "Não";

		$retorno['funcionarios']['salario'] = "R$ ".$retorno['funcionarios']['salario'];
		
		if($retorno['funcionarios']['func']){

			unset($retorno['campos']['func']);
			$idFunc = $this->model->pegar($id, array("func"));
			$idFunc = $idFunc['funcionarios']['func'];

			require RAIZ . SEPARADOR . 'controllers' . SEPARADOR . 'func_nao_membroController.php';
			require RAIZ . SEPARADOR . 'models' . SEPARADOR . 'func_nao_membroModel.php';
			$func = new func_nao_membroModel();
			$vars = get_class_vars('func_nao_membro');
			$camposFunc = $vars['campos'];

			$dadosFunc = $func->visualizar($idFunc);

			$retorno['funcionarios'] = array_merge($retorno['funcionarios'], $dadosFunc['func_nao_membro']);
			$retorno['tipos'] = array_merge($retorno['tipos'], $func->tipos);
			$retorno['campos'] = array_merge($retorno['campos'], $camposFunc);

		}else{

			unset($retorno['campos']['membro']);
			$idMembro = $this->model->pegar($id, array("membro"));
			$idMembro = $idMembro['funcionarios']['membro'];

			require RAIZ . SEPARADOR . 'controllers' . SEPARADOR . 'membrosController.php';
			require RAIZ . SEPARADOR . 'models' . SEPARADOR . 'membrosModel.php';
			$membro = new membrosModel();
			$vars = get_class_vars('membros');
			$camposMembros = $vars['campos'];

			$dadosMembro = $membro->visualizar($idMembro);

			$retorno['funcionarios'] = array_merge($retorno['funcionarios'], $dadosMembro['membros']);
			$retorno['tipos'] = array_merge($retorno['tipos'], $membro->tipos);
			$retorno['campos'] = array_merge($retorno['campos'], $camposMembros);
			$retorno['funcionarios']['nome'] = "<a onclick=\"redir('membros', '$idMembro', 'Igreja');\">".$retorno['funcionarios']['nome']."</a>";
		}

		$retorno['cor'] = $this->informacoes['cor'];
		$retorno['icone'] = $this->informacoes['icone'];

		$this->dados($retorno);
		$this->renderizar('CRUD/visualizar');
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
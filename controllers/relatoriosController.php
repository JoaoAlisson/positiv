<?php 
class Relatorios extends Controller
{

	public function index()
	{
		
	}

	public function membros()
	{
		if(!isset($_POST['postado']))
		{
			$model = $this->pegaModel('membros');
			$consagracoes = $model->consagracoes();
			$this->dados($consagracoes);
		}
		else
		{
			$this->membrosPdf();
		}
	}

	public function visitantes()
	{
		if(isset($_POST['postado']))
			$this->visitantesPdf();
	}

	private function visitantesPdf()
	{
		$this->pdf();
		$model 	 = $this->pegaModel('visitantes');
		$campos  = array("nome", "celular", "nascimento");

		$sexo		 = isset($_POST['sexo'])		? $_POST['sexo'] 		: "";
		$estadoCivil = isset($_POST['estadocivil']) ? $_POST['estadocivil'] : "";

		$onde = array("sexo"		=> $sexo,
					  "estadocivil"	=> $estadoCivil);

		$membros = $model->pegarVisitantes($campos, $onde);

		$dados['nome']	  = "Relatório de Visitantes";
		//$dados['subTitulo']	= "Folha de Dezembro";

		$dados['titulos'] = array("nome" 	    => array("Nome", 10),
								  "celular"     => array("Celular", 2.7),
							      "nascimento"  => array("Dt. Nascimento", 3.5));

		$dados['tipos']   = array('nascimento' => 'data');

		$filtros = $this->escreveFiltrosVisitantes($onde, $model);
		if($filtros != "")
			$dados['filtros'] = $filtros;

		$dados['linhas']  = $membros;
		$this->dados($dados);
	}

	private function escreveFiltrosVisitantes(&$onde, &$model)
	{
		$retorna = "";

		$sexo = "";
		if($onde['sexo'] != "")
			$sexo = ($onde['sexo'] == 1) ? "Masculino" : "Feminino";

		$stdCivilArray = array('Solteiro', 'Casado', 'Divorciado');
		$estadocivil = "";
		if($onde['estadocivil'] != "")
			$estadocivil = in_array($onde['estadocivil'], $stdCivilArray) ? $onde['estadocivil'] : "";

		if($sexo != "")
			$retorna .= ($retorna != "") ? ", " . $sexo 	   : $sexo;

		if($estadocivil != "")
			$retorna .= ($retorna != "") ? ", " . $estadocivil : $estadocivil;

		$retorna .= ($retorna != "") ? "." : "";

		return $retorna;		
	}

	private function membrosPdf()
	{
		$this->pdf();
		$model 	 = $this->pegaModel('membros');
		$campos  = array("nome", "celular", "nascimento");

		$consagracao = isset($_POST['consagracao']) ? $_POST['consagracao'] : "";
		$sexo		 = isset($_POST['sexo'])		? $_POST['sexo'] 		: "";
		$estadoCivil = isset($_POST['estadocivil']) ? $_POST['estadocivil'] : "";
		$batizado	 = isset($_POST['batizado']) 	? $_POST['batizado']	: "";

		$onde = array("consagracao" => $consagracao,
					  "sexo"		=> $sexo,
					  "estadocivil"	=> $estadoCivil,
					  "batizado"	=> $batizado);

		$membros = $model->pegarMembros($campos, $onde);

		$dados['nome']	  = "Relatório de Membros";
		//$dados['subTitulo']	= "Folha de Dezembro";

		$dados['titulos'] = array("nome" 	    => array("Nome", 10),
								  "consagracao" => array("Consagração", 3.5),
								  "celular"     => array("Celular", 2.7),
							      "nascimento"  => array("Dt. Nascimento", 3.5));

		$dados['tipos']   = array('nascimento' => 'data');

		$filtros = $this->escreveFiltrosMembros($onde, $model);
		if($filtros != "")
			$dados['filtros'] = $filtros;

		$dados['linhas']  = $membros;
		$this->dados($dados);
	}

	private function escreveFiltrosMembros(&$onde, &$model)
	{
		$retorna = "";
		if($onde['consagracao'] != "")
			$retorna = $model->pegaConsagracao($onde['consagracao']);

		$sexo = "";
		if($onde['sexo'] != "")
			$sexo = ($onde['sexo'] == 1) ? "Masculino" : "Feminino";

		$stdCivilArray = array('Solteiro', 'Casado', 'Divorciado');
		$estadocivil = "";
		if($onde['estadocivil'] != "")
			$estadocivil = in_array($onde['estadocivil'], $stdCivilArray) ? $onde['estadocivil'] : "";

		$batizado = "";
		if($onde['batizado'] != "")
			$batizado = ($onde['batizado'] == 1) ? "Batizado" : "Não Batizado";

		if($sexo != "")
			$retorna .= ($retorna != "") ? ", " . $sexo 	   : $sexo;

		if($estadocivil != "")
			$retorna .= ($retorna != "") ? ", " . $estadocivil : $estadocivil;

		if($batizado != "")
			$retorna .= ($retorna != "") ? ", " . $batizado    : $batizado;

		$retorna .= ($retorna != "") ? "." : "";

		return $retorna;
	}

	private function pdf($pdf = "pdf")
	{
		$_POST['usuario'] = Sessao::pegar('usuario');
		$this->usarLayout(false);
		$this->renderizar('relatorios/'.$pdf);		
	}

	private function pegaModel($class)
	{
		$this->importa($class);
		$className = $class . "Md";
		return new $className();
	}

	private function importa($class)
	{
		$caminho = RAIZ . SEPARADOR . 'relatoriosModels' . SEPARADOR;
		require_once($caminho . 'classePdo.php');
		require_once($caminho . $class . 'Md.php');		
	}

	private function mes($key)
	{
		$mes = array("", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junio", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		return $mes[$key];
	}

	public function aniversariantesMembros()
	{	

		if(isset($_POST['mes']))
		{
			$mes = (int)$_POST['mes'];
			if($mes < 1 || $mes > 12)
				unset($_POST['mes']);
		}

		if(!isset($_POST['mes']))
		{
			$mes = date('m');
			$dados['mesValor'] = $mes;
			$dados['mesNome']  = $this->mes($mes);
			$this->dados($dados);
		}
		else
		{
			$this->membrosAniversariantesPdf();
		}	
	}

	private function membrosAniversariantesPdf()
	{
		$this->pdf();
		$model 	 = $this->pegaModel('aniversariantesMembros');
		$campos  = array("nome", "consagracao", "celular", "nascimento");

		$mes = $_POST['mes'];

		$membros = $model->pegarMembros($campos, $mes);

		$mes = $this->mes($mes);
		$dados['nome']	  = "Aniversariantes de $mes";
		$dados['subTitulo']	= "(Membros)";

		$dados['titulos'] = array("nome" 	    => array("Nome", 10),
								  "consagracao" => array("Consagração", 3.5),
								  "celular"     => array("Celular", 2.7),
							      "nascimento"  => array("Dt. Nascimento", 3.5));

		$dados['tipos']   = array('nascimento' => 'data');

		$dados['linhas']  = $membros;
		$this->dados($dados);
	}	

	public function aniversariantesVisitantes()
	{	

		if(isset($_POST['mes']))
		{
			$mes = (int)$_POST['mes'];
			if($mes < 1 || $mes > 12)
				unset($_POST['mes']);
		}

		if(!isset($_POST['mes']))
		{
			$mes = date('m');
			$dados['mesValor'] = $mes;
			$dados['mesNome']  = $this->mes($mes);
			$this->dados($dados);
		}
		else
		{
			$this->visitantesAniversariantesPdf();
		}	
	}

	private function visitantesAniversariantesPdf()
	{
		$this->pdf();
		$model 	 = $this->pegaModel('aniversariantesVisitantes');
		$campos  = array("nome", "celular", "nascimento");

		$mes = $_POST['mes'];

		$membros = $model->pegarVisitantes($campos, $mes);

		$mes = $this->mes($mes);
		$dados['nome']	  = "Aniversariantes de $mes";
		$dados['subTitulo']	= "(Visitantes)";

		$dados['titulos'] = array("nome" 	    => array("Nome", 10),
								  "celular"     => array("Celular", 2.7),
							      "nascimento"  => array("Dt. Nascimento", 3.5));

		$dados['tipos']   = array('nascimento' => 'data');

		$dados['linhas']  = $membros;
		$this->dados($dados);
	}

	public function patrimonio()
	{
		if(!isset($_POST['postado']))
		{
			$model = $this->pegaModel('patrimonio');
			$ministerios = $model->ministerios();
			$this->dados($ministerios);
		}
		else{
			$this->patrimonioPdf();
		}
	}

	private function patrimonioPdf()
	{
		$this->pdf("somaPdf");

		$model 	 = $this->pegaModel('patrimonio');
		$campos  = array("codigo", "cod", "nome", "quantidade", "total");

		$ministerio = isset($_POST['ministerio']) ? $_POST['ministerio'] : "";
		$situacao   = isset($_POST['situacao'])   ? $_POST['situacao']  : "";

		$onde = array("ministerio" => $ministerio,
					  "situacao"   => $situacao);

		$membros = $model->pegarPatrimonios($campos, $onde);

		$dados['nome']	  = "Relatório de Patrimônio";
		//$dados['subTitulo']	= "Folha de Dezembro";

		$dados['titulos'] = array("codigo" 	 	 => array("Codigo", 0.7),
								  "cod"			 => array("Cod. Sistem.", 1.1),
								  "nome"    	 => array("Nome", 3.3),
								  "ministerio"   => array("Ministério", 1.5),
								  "quantidade"   => array("Qtd", 0.5), 
								  "total"     	 => array("Total (R$)", 1));

		$dados['tipos']   = array('total' => 'moeda');

		$filtros = $this->escreveFiltrosPdf($onde, $model);
		if($filtros != "")
			$dados['filtros'] = $filtros;

		$dados['linhas']  = $membros;
		$this->dados($dados);			
	}

	private function escreveFiltrosPdf(&$onde, &$model)
	{
		$retorna = "";
		if($onde['ministerio'] != "")
			$retorna = 'Ministério ' . $model->pegaMinisterio($onde['ministerio']);

		$situacao = "";
		if($onde['situacao'] != ""){
			$situacaoArray = array('Disponivel' 	=> 'Disponível',
								   'Em Manutencao'	=> 'Em Manutenção',
								   'Doado'			=> 'Doado',
								   'Com Defeito'	=> 'Com Defeito');
			$situacao = isset($situacaoArray[$onde['situacao']]) ? $situacaoArray[$onde['situacao']] : "";
		}

		if($situacao != "")
			$retorna .= ($retorna != "") ? ", " . $situacao : $situacao;

		$retorna .= ($retorna != "") ? "." : "";

		return $retorna;		
	}

	public function funcionarios()
	{
		if(!isset($_POST['postado']))
		{
			$model = $this->pegaModel('funcionarios');
			$cargos = $model->cargos();
			$this->dados($cargos);
		}
		else{
			$this->funcionariosPdf();
		}	
	}

	private function funcionariosPdf()
	{
		$this->pdf("somaPdf");

		$model 	 = $this->pegaModel('funcionarios');
		$campos  = array("salario");

		$cargo    = isset($_POST['cargo'])    ? $_POST['cargo'] : "";
		$situacao = isset($_POST['situacao']) ? $_POST['situacao'] : "";
		$inss     = isset($_POST['inss']) 	  ? $_POST['inss'] : "";


		$onde = array("cargo"    => $cargo,
					  "situacao" => $situacao,
					  "inss"	 => $inss);

		$funcionarios = $model->pegarFuncionarios($campos, $onde);

		$dados['nome']	  = "Relatório de Funcionários";

		$dados['titulos'] = array("nome" 	  => array("Nome", 4),
								  "cargo"	  => array("Cargo", 2.1),
								  "cpf"    	  => array("Cpf", 1.3),
								  "salario"   => array("Salário", 1),);

		$dados['tipos']   = array('salario' => 'moeda');

		$filtros = $this->escrvFiltFuncionarios($onde, $model);
		if($filtros != "")
			$dados['filtros'] = $filtros;

		$dados['linhas']  = $funcionarios;
		$this->dados($dados);			
	}

	private function escrvFiltFuncionarios(&$onde, &$model)
	{
		$retorna = "";
		if($onde['cargo'] != "")
			$retorna = 'Cargo: ' . $model->pegaCargo($onde['cargo']);
		
		$situacao = "";
		if($onde['situacao'] != ""){
			$situacaoArray = array('Ativo' 	   => 'Ativo',
								   'De Ferias' => 'De Férias',
								   'Demitido'  => 'Demitido');
			$situacao = isset($situacaoArray[$onde['situacao']]) ? $situacaoArray[$onde['situacao']] : "";
		}

		$inss = "";
		if($onde['inss'] != "")
			$inss = ($onde['inss'] == 1) ? "Com Cálculo de INSS" : "Sem Cálculo de INSS";

		if($situacao != "")
			$retorna .= ($retorna != "") ? ", " . $situacao : $situacao;

		if($inss != "")
			$retorna .= ($retorna != "") ? ", " . $inss : $inss;

		$retorna .= ($retorna != "") ? "." : "";

		return $retorna;
	}

	public function programacoes()
	{
		if(isset($_POST['postado']))
			$this->programacoesPdf();
	}

	private function programacoesPdf()
	{
		$this->pdf();

		$model 	 = $this->pegaModel('programacoes');
		$campos  = array("nome", "inicio", "fim", "local");

		$inicio = isset($_POST['inicio']) ? $_POST['inicio'] : "";
		$fim    = isset($_POST['fim']) 	  ? $_POST['fim']    : "";

		$onde = array("inicio" => $inicio,
					  "fim"    => $fim);

		$funcionarios = $model->pegarEventos($campos, $onde);

		$dados['nome']	  = "Relatório de Programações";

		$dados['titulos'] = array("nome" 	  => array("Evento", 4),
								  "local"     => array("Local", 2),
								  "inicio"	  => array("Início", 1),
								  "fim"    	  => array("Fim", 1));

		$dados['tipos']   = array('inicio' => 'data',
								  'fim'	   => 'data');

		$filtros = $this->escrvFiltProgramacoes($onde);
		if($filtros != "")
			$dados['filtros'] = $filtros;

		$dados['linhas']  = $funcionarios;
		$this->dados($dados);			
	}

	private function escrvFiltProgramacoes($onde)
	{	
		$retorna = "";
		if($onde['inicio'] != "" && $onde['fim'] != "")
		{
			$retorna = "Entre " . $onde['inicio'] . " e " . $onde['fim'] . ".";
		}
		else
		{
			if($onde['inicio'] != "")
				$retorna = "Desde " . $onde['inicio'] . ".";
			else
				$retorna = "Até " . $onde['fim'] . ".";
		}

		return $retorna;
	}

	public function folhas_de_pagamentos()
	{
		if(isset($_POST['postado']))
			$this->folhasPdf();
	}

	private function folhasPdf()
	{
		$this->pdf('folhasPdf');

		$model 	 = $this->pegaModel('folhas');

		$ano = isset($_POST['ano']) ? $_POST['ano'] : "2014";

		$folhas = $model->pegarFolhas($ano);

		$dados['nome']	  = "Folhas de Pagamentos";
		$dados['subTitulo']	= "Ano de $ano";

		$dados['titulos'] = array("mes" 	  => array("Mês", 1),
								  "qtdFuncionarios" => array("Funcs.", 0.5),
								  "salarios"  => array("Salários", 1),
								  "inss"	  => array("INSS", 1),
								  "abonos"    => array("Abonos", 1),
								  "descontos" => array("Descontos", 1),
								  "total"	  => array("Total", 1));

		$dados['tipos']   = array('salarios'  => 'moeda',
								  'inss'      => 'moeda',
								  'abonos'	  => 'moeda',
								  'descontos' => 'moeda',
								  'mes'		  => 'mes');

		$dados['linhas']  = $folhas;
		$this->dados($dados);
	}
}
?>
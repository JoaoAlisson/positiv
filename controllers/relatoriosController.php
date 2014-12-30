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

	private function pdf()
	{
		$_POST['usuario'] = Sessao::pegar('usuario');
		$this->usarLayout(false);
		$this->renderizar('relatorios/pdf');		
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
		$_POST['usuario'] = Sessao::pegar('usuario');
		$this->usarLayout(false);
		$this->renderizar('relatorios/patrimonioPdf');

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
}
?>
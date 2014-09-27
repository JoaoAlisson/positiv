<?php 
class Model extends Database{
	public $tipoUsuario = array();
	public $permissao = "";
	function __construct(){
		parent::__construct();
		//$this->tipoUsuario = $this->pegaTipoUsuario('1');
	}
	
	public function pegaTipoUsuario($idUsuario){
		$tabela = PREFIXO."usuariostipos";
		$sql = "SELECT tipo FROM $tabela WHERE id_usuario = {$idUsuario}";
		$query = $this->prepare($sql);
		$query->execute();
		$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

		$retorno = array();
		foreach ($resultado as $key => $value)
			array_push($retorno, $value['tipo']);

		return $retorno; 
	}

	public function inserirPermissao($permissao){
		$this->permissao = $permissao;
	}

	//$this->campo("campoTabela", array("nomeSingular", "nomePlural"), "tipoDoCampo", "validacao");
	private $campos = array();

	//Esta variável eh um ponteiro para o array com os dados
	//O motivo eh para que as funções de validação alterem o valores antes deles serem salvos
	public $dados;

	public function formataSaida(&$retorno){
		if(isset($retorno[0])){
			foreach ($retorno as $item => $campos) {
				foreach ($campos as $campo => $valor) {
					if($campo != "id"){
						if($this->tipos[$campo] == "moeda")
							$retorno[$item][$campo] = "R$ ". number_format($valor, 2, ',', '.');
						else
							$retorno[$item][$campo]  = htmlentities(stripslashes($valor), ENT_QUOTES);			
					}
				}
			}
		}else{
			foreach ($retorno as $campo => $valor) {
				if($campo != "id"){
					if($this->tipos[$campo] == "moeda")
						$retorno[$campo] = number_format($valor, 2, ',', '.');	
					else
						$retorno[$campo]  = htmlentities(stripslashes($valor), ENT_QUOTES);											
				}				
			}
		}
	}

	public function validar(&$dados, $validarObrig = "true"){

		$this->dados = &$dados;

		$validacoes = array();
		foreach ($dados as $chave => $valor) {
			
			//verifica se é obrigatório e se naum está vazio
			if($validarObrig == "true"){
				if(in_array($chave, $this->obrigatorios)){
					if($valor == "" || $valor == null)
						$validacoes[$chave][0] = "Campo obrigatório";
				}
			}	

			//validações personalizadas
			if(isset($this->validacoes[$chave])){
				if(is_array($this->validacoes[$chave])){
					foreach ($this->validacoes[$chave] as $campoVal => $funcao) {

						$retornoFunc = $this->$funcao($valor);
						if($retornoFunc != "ok"){
							if(isset($validacoes[$chave][0]))
								array_push($validacoes[$chave], $retornoFunc);
							else
								$validacoes[$chave][0] = $retornoFunc;						
						}
					}
				}else{
					//realiza as validações dos tipos de dados
					$var = $this->validacoes[$chave];
					$retornouValid = $this->$var($valor);
					if($retornouValid != "ok"){
						if(isset($validacoes[$chave][0]))
							array_push($validacoes[$chave], $retornouValid);
						else
							$validacoes[$chave][0] = $retornouValid;	
					}					
				}	
			}

			$funcaoValidacao = isset($this->tipos[$chave]) ? 'validar'.ucfirst($this->tipos[$chave]) : 'validarTexto';
			$validacaoRetorno = $this->$funcaoValidacao($valor, $chave);
			if(is_array($validacaoRetorno)){
				if(isset($validacoes[$chave][0]))
					$validacoes[$chave] = array_merge($validacoes[$chave], $validacaoRetorno);
				else
					$validacoes[$chave] = $validacaoRetorno;
			}
		}

		$retorno;
		if(empty($validacoes)){

			$retorno[0] = "ok";
			$retorno[1] = "Cadastrado com Sucesso!";

		}else{
			$retorno[0] = "erro";
			$retorno[1] = "Nao foi salvo";
			$retorno[2] = $validacoes;
		}
		return $retorno;
	}

	public function validarTexto($texo, $campo = null){
		$retorno = "ok";
		//if($campo != null)
			//$this->dados[$campo] = mysql_real_escape_string($texto);

		return $retorno;
	}

	public function validarTextoLongo($texo){
		$retorno = "ok";

		return $retorno;
	}	

	public function validarNumero($valor, $campo = null){

		$retorno;
		$valor = str_replace(".", "", $valor);
		$valor = str_replace(",", ".", $valor);

		if(is_numeric($valor) || $valor == null){
			$retorno = "ok";
			if($campo != null)
				$this->dados[$campo] = $valor;
		}else{
			$retorno[0] = "Valor inválido";
		}

		return $retorno;		
	}

	public function validarImagem(){
		return "ok";
	}	

	public function validarData($data){
		// 00/00/0000
		$data = explode("/", $data);
		$dia = isset($data[0]) ? $data[0] : "0";
		$mes = isset($data[1]) ? $data[1] : "0";
		$ano = isset($data[2]) ? $data[2] : "0";

		$retorno;

		if(isset($data[3])){
			$retorno[0] = "Data inválida";
		}else{
			if(is_numeric($dia) && is_numeric($mes) && is_numeric($ano)){

				if(checkdate($mes, $dia, $ano))
					$retorno = "ok";
				else
					$retorno[0] = "Data inválida";
			}else{
				$retorno[0] = "Data inválida";
			}	
		}

		return $retorno; 
	}		

	public function validarHora(){
		return "ok";
	}

	public function validarInteiro($valor, $campo = null){
		$valor = str_replace(",", ".", $valor);
		$valor = (float) $valor;
		$int = (int) $valor;

		$retorno;
		if(($valor - $int) == 0){
			$retorno = "ok";
			if($campo != null)
				$this->dados[$campo] = $valor;
		}else{
			$retorno[0] = "O valor deve ser inteiro";
		}

		return $retorno;
	}

	public function validarMoeda($valor, $campo = null){
		return $this->validarNumero($valor, $campo);
	}

	public function padraoEmail($email){
		$padrao = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
		if(preg_match($padrao ,$email))
			return true;
		else
			return false;
	}

	public function validarEmailOff($email){
		$valido = $this->padraoEmail($email);

		$retorno;
		if($valido)
			$retorno = "ok";
		else
			$retorno[0] = "Email inválido";

		return $retorno;
	}

	public function validarEmail($email){

		$valido = $this->padraoEmail($email);

		if($valido){
			$dominio = explode('@',$email);
			if(!checkdnsrr($dominio[1],'MX'))
				$valido = false;	
		}	

		$retorno;
		if($valido)
			$retorno = "ok";
		else
			$retorno[0] = "Email inválido";

		return $retorno;
	}	

	public function validarTelefone($telefone){
		// (00) 00000-0000 ou (00) 00000-0000
	
		$telNormal = false;
		if(preg_match("/\([0-9]{2}\) [0-9]{4}-[0-9]{4}$/", $telefone))
			$telNormal = true;

		$telSaoPaulo = false;	
		if(preg_match("/\([0-9]{2}\) [0-9]{5}-[0-9]{4}$/", $telefone))
			$telSaoPaulo = true;

		$retorno;
		if($telNormal || $telSaoPaulo)
			$retorno = "ok";
		else
			$retorno[0] = "Telefone inválido";

		return $retorno;
	}	

	public function validarCpf($cpf){

		$cpf = str_replace(array(".", "-"), "", $cpf);

		$valido;
	    $cpf = str_pad(preg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
		
	    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'){
			$valido = false;
	    }
		else{
	        for ($t = 9; $t < 11; $t++) {
	            for ($d = 0, $c = 0; $c < $t; $c++) {
	                $d += $cpf{$c} * (($t + 1) - $c);
	            }
	 
	            $d = ((10 * $d) % 11) % 10;
	 
	            if ($cpf{$c} != $d) {
	                return false;
	            }
	        }
	 
	        $valido = true;
	    }
	    $retorno;
	    if($valido)
	    	$retorno = "ok";
	    else
	    	$retorno[0] = "CPF inválido";
	    return $retorno;
	}		
}
?>
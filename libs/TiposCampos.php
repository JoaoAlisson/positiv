<?php
class TiposCampos{
	public function tipo($tipo){
		if(is_array($tipo)){
			if(isset($tipo["relacao"]))
				return $this->chaveEstrangeira($tipo);
			else
				return $this->enum($tipo);
		}else{
			if(method_exists($this, $tipo))
				return $this->$tipo();
		}
	}

	private function enum($tipo){
		$tamanho = strlen($this->removeAcentos($tipo[0]));
		foreach ($tipo as $key => $valor)
			$tamanho = (strlen($this->removeAcentos($valor)) > $tamanho) ? strlen($this->removeAcentos($valor)) : $tamanho;

		return "varchar ($tamanho)";
	}

	private function chaveEstrangeira($tipo){
		return "int(11)";
	}

	private function inteiro(){
		return "int(11)";
	}

	private function estado(){
		return "int(2)";
	}	

	private function cidade(){
		return "int(4)";
	}

	private function texto(){
		return "varchar (255)";
	}

	private function facebook(){
		return "varchar (255)";
	}

	private function nome(){
		return "varchar (255)";
	}	

	private function imagem(){
		return "varchar (100)";
	}	

	private function moeda(){
		return "double";
	}	

	private function textoLongo(){
		return "longtext";
	}

	private function login(){
		return "varchar (100)";
	}

	private function senha(){
		return "varchar (40)";
	}	

	private function numero(){
		return "double";
	}

	private function data(){
		return "date";
	}

	private function email(){
		return "varchar (255)";
	}

	private function emailOff(){
		return $this->email();
	}

	private function telefone(){
		return "varchar (15)";
	}

	private function cpf(){
		return "varchar (20)";
	}

	private function sexo(){
		return "int(1)";
	}

/*
	Esta função foi tirada do site: http://www.douglaspasqua.com/2013/09/17/removendo-acentuacao-no-php-utf-8/
 */
    public function removeAcentos($value){   
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
                 
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        $value = strtr($value, $mapping);
                 
        return $value;
    }		
}
?>
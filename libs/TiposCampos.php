<?php
class TiposCampos{
	public function tipo($tipo){
		if(method_exists($this, $tipo))
			return $this->$tipo();
	}

	private function inteiro(){
		return "int(11)";
	}

	private function texto(){
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
}
?>
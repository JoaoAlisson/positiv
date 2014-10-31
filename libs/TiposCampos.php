<?php
class TiposCampos{
	public function tipo($tipo){
		if(method_exists($this, $tipo))
			return $this->$tipo();
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
		return "varchar (30)";
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
}
?>
<?php
class estadoscidades extends Controller{

	public function cidades(){
		$this->usarLayout(false);

		$estado = isset($_POST['estado']) ? (int)$_POST['estado'] : "0";
		if($estado != 0){
			$model = new CidadesEstadosModel();
			$cidades = $model->pegarCidades($estado);

			$cidades = json_encode($cidades);
			echo $cidades;
		}	
	}

}
?>
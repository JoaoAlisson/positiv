<?php 
class caixa extends ControllerCRUD{

	public $regraUsuarios = array("Administrador" => "tudo", "financas" => "tudo");


	public function index(){
		
		$mes = (isset($this->GET['mes'])) ? (int)$this->GET['mes'] : date('m');
		$ano = 2000 + date('y');
		$ano = (isset($this->GET['ano'])) ? (int)$this->GET['ano'] : $ano;


		$onde = " YEAR(data) = '$ano' AND MONTH(data) = '$mes'";

		$dizimosOfertas = $this->model->pegarOnde($onde, array("tipo", "valor"), "dizimos_ofertas");

		$totalDizimos = 0;
		$totalOfertas = 0;
		foreach ($dizimosOfertas['dizimos_ofertas'] as $key => $entrada) {
			if($entrada['tipo'] == "Dizimo")
				$totalDizimos = $totalDizimos + $entrada['valor'];
			else
				$totalOfertas = $totalOfertas + $entrada['valor'];
		}

		$onde = " YEAR(vencimento) = '$ano' AND MONTH(vencimento) = '$mes'";

		$categorias = $this->model->pegarOnde("", array("id", "nome"), "categorias");

		$saidas = $this->model->pegarOnde($onde, array("categoria", "valor", "pago"), "saidas");

		$saidasPagas = array();
		$saidasApagar = array();
		foreach ($saidas['saidas'] as $key => $valor) {
			if($valor['pago'] == "Efetuado"){
				if(isset($saidasPagas[$valor['categoria']]))
					$saidasPagas[$valor['categoria']] = $saidasPagas[$valor['categoria']] + $valor['valor'];
				else
					$saidasPagas[$valor['categoria']] = $valor['valor'];

			}else{
				if(isset($saidasApagar[$valor['categoria']]))
					$saidasApagar[$valor['categoria']] = $saidasApagar[$valor['categoria']] + $valor['valor'];
				else
					$saidasApagar[$valor['categoria']] = $valor['valor'];
			}
		}

		$entradas = $this->model->pegarOnde($onde, array("categoria", "valor", "pago"), "entradas");

		$entradasPagas = array();
		$entradasApagar = array();
		foreach ($entradas['entradas'] as $key => $valor) {
			if($valor['pago'] == "Efetuado"){
				if(isset($entradasPagas[$valor['categoria']]))
					$entradasPagas[$valor['categoria']] = $entradasPagas[$valor['categoria']] + $valor['valor'];
				else
					$entradasPagas[$valor['categoria']] = $valor['valor'];

			}else{
				if(isset($entradasApagar[$valor['categoria']]))
					$entradasApagar[$valor['categoria']] = $entradasApagar[$valor['categoria']] + $valor['valor'];
				else
					$entradasApagar[$valor['categoria']] = $valor['valor'];
			}
		}

		$arrayCateg = array();
		foreach ($categorias['categorias'] as $key => $valor)
			$arrayCateg[$valor['id']] = $valor['nome'];

		$dados['categorias'] = $arrayCateg;
		$dados['mes'] = $mes;
		$dados['ano'] = $ano;

		$dados['dizimos'] = $totalDizimos;
		$dados['ofertas'] = $totalOfertas;

		$dados['saidasPagas'] = $saidasPagas;
		$dados['saidasApagar'] = $saidasApagar;

		$dados['entradasPagas'] = $entradasPagas;
		$dados['entradasApagar'] = $entradasApagar;

		$dados['saldo'] = $this->model->pegarSaldo();
		//$saldo;
		$this->dados($dados);
	}
}?>
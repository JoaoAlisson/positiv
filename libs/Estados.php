<?php 
class Estados{

	private $sql = "";
	private $campos = "";	

	function __construct(){
		$tabela = PREFIXO."estados";
		$this->sql = "CREATE TABLE IF NOT EXISTS `$tabela` (
					  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
					  `estado` varchar(20) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;";

		$this->campos = "INSERT INTO `$tabela` (`id`, `estado`) VALUES
					(01, 'Acre'),
					(02, 'Alagoas'),
					(03, 'Amazonas'),
					(04, 'Amapá'),
					(05, 'Bahia'),
					(06, 'Ceará'),
					(07, 'Distrito Federal'),
					(08, 'Espírito Santo'),
					(09, 'Goiás'),
					(10, 'Maranhão'),
					(11, 'Minas Gerais'),
					(12, 'Mato Grosso do Sul'),
					(13, 'Mato Grosso'),
					(14, 'Pará'),
					(15, 'Paraíba'),
					(16, 'Pernambuco'),
					(17, 'Piauí'),
					(18, 'Paraná'),
					(19, 'Rio de Janeiro'),
					(20, 'Rio Grande do Norte'),
					(21, 'Rondônia'),
					(22, 'Roraima'),
					(23, 'Rio Grande do Sul'),
					(24, 'Santa Catarina'),
					(25, 'Sergipe'),
					(26, 'São Paulo'),
					(27, 'Tocantins');"; 
	}

	public function criarEstados(){
		mysql_query($this->sql);
		mysql_query($this->campos);
	}
}
?>
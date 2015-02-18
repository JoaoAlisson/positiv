<?php

class Uteis
{
	public static $sis_user; 

	public static function nomeIgreja($nome)
	{	
		$lower = Uteis::removeAcentos($nome);
		$lower = strtolower($lower);

		if(strlen(stristr($lower, 'igreja')) != 0 || strlen(stristr($lower, 'congregacao')) != 0)			
			return $nome;
		else
			return 'Igreja ' . $nome;
	}

	public static function removeAcentos($texto)
	{
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";
                 
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        $texto = strtr($texto, $mapping);
                 
        return $texto;		
	}

	public static function setSis_user($valor) 
	{
		Uteis::$sis_user = $valor;
	}

	public static function url() 
	{
		return URL . Uteis::$sis_user . '/';
	}
}
?>
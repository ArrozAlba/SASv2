<?php 
class cnumero_letra
{
	var $arr_letra;
	var $ldec_numero;
	var $io_msg;
	var $io_function;
	function cnumero_letra()
	{
		require_once("class_mensajes.php");
		require_once("class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->io_function=new class_funciones();
		$this->arr_letra[1] = "Un";
		$this->arr_letra[2] = "Dos";
		$this->arr_letra[3] = "Tres";
		$this->arr_letra[4] = "Cuatro";
		$this->arr_letra[5] = "Cinco";
		$this->arr_letra[6] = "Seis";
		$this->arr_letra[7] = "Siete";
		$this->arr_letra[8] = "Ocho";
		$this->arr_letra[9] = "Nueve";
		$this->arr_letra[10] = "Diez";
		$this->arr_letra[11] = "Once";
		$this->arr_letra[12] = "Doce";
		$this->arr_letra[13] = "Trece";
		$this->arr_letra[14] = "Catorce";
		$this->arr_letra[15] = "Quince";
		$this->arr_letra[16] = "Dieciseis";
		$this->arr_letra[17] = "Diecisiete";
		$this->arr_letra[18] = "Dieciocho";
		$this->arr_letra[19] = "Diecinueve";
		$this->arr_letra[20] = "Veinte";
		$this->arr_letra[21]= "Veintiun";
		$this->arr_letra[22]= "Veintidos";
		$this->arr_letra[23] = "Veintitres";
		$this->arr_letra[24] = "Veinticuatro";
		$this->arr_letra[25]= "Veinticinco";
		$this->arr_letra[26]= "Veintiséis";
		$this->arr_letra[27]= "Veintisiete";
		$this->arr_letra[28]= "Veintiocho";
		$this->arr_letra[29]= "Veintinueve";
		$this->arr_letra[30] = "Treinta";
		$this->arr_letra[31]= "Cuarenta";
		$this->arr_letra[32] = "Cincuenta";
		$this->arr_letra[33]= "Sesenta";
		$this->arr_letra[34] = "Setenta";
		$this->arr_letra[35] = "Ochenta";
		$this->arr_letra[36] = "Noventa";
		$this->arr_letra[37] = "Cien";
		$this->arr_letra[38]= "Ciento";
		$this->arr_letra[39]= "Doscientos";
		$this->arr_letra[40]= "Trescientos";
		$this->arr_letra[41]= "Cuatrocientos";
		$this->arr_letra[42] = "Quinientos";
		$this->arr_letra[43] = "Seiscientos";
		$this->arr_letra[44] = "Setecientos";
		$this->arr_letra[45] = "Ochocientos";
		$this->arr_letra[46] = "Novecientos";
		$this->arr_letra[47] = "Mil";
		
	}

	function uf_convertir_letra($adec_numero,$ls_prefijo,$ls_sufijo)
	{
		$this->ldec_numero=$adec_numero;
		$ls_cadena=sprintf("%.2f",$this->ldec_numero);
		$this->ldec_numero=floatval($ls_cadena);
		$ls_montoletra="";
		$ldec_bolivares = substr($ls_cadena,0,-3);
		$ldec_centimos = $this->ldec_numero-$ldec_bolivares;
		if($this->ldec_numero > 999999999999)
		{
			$this->io_msg("El monto es demasiado Grande,no puede ser convertido a letras");
			return false;
		}
		else
		{
			$ls_montoletra=$ls_prefijo.strtoupper($this->uf_letra($ldec_bolivares).' Bolivares '.$this->io_function->iif_string("'$ldec_centimos' >'0'", " Con ".floatval(round($ldec_centimos,2)*100)." Centimos"," Con 00 Centimos ")).$ls_sufijo;
		}
		unset($this->arr_letra);
		return $ls_montoletra;
	}
	
	function uf_letra($ldec_numero)
	{
		$ls_cadena="";
		$ls_tira = "";
		switch (true)
		{
			case $ldec_numero==0:
				$ls_tira = "Cero";
				break;
			case ($ldec_numero>= 1)&&($ldec_numero<=1000000):
				$ls_tira = $this->uf_menor_millon($ldec_numero);
				break;				
			case  ($ldec_numero>= 1000001)&&($ldec_numero<=999999999999):
				$ldec_resto = fmod($ldec_numero, 1000000);	
				//print "resto $ldec_resto";
				$ldec_numero = intval($ldec_numero / 1000000);
				//print "numero $ldec_numero";
				$ls_tira = $this->uf_menor_millon($ldec_numero).$this->io_function->iif_string("'$ldec_numero'=='1'"," Millon "," Millones ").$this->uf_menor_millon($ldec_resto);
				break;				
			default:
				$ls_tira = false;
		}
		return $ls_tira;
	}
	
	function uf_menor_mil($ldec_numero)
	{
		$ls_tira = "";
		do
		{ 
			switch (true){
				case ($ldec_numero>=1)&&($ldec_numero<= 29):
					$ls_tira = $ls_tira.$this->arr_letra[$ldec_numero];
					$ldec_numero = 0;
					break;					
				case ($ldec_numero>=30)&&($ldec_numero<= 99):
					$ldec_resto = fmod($ldec_numero,10);
					$ldec_numero = intval($ldec_numero / 10);
					
					$li_temp=$ldec_numero+27;
					$ls_tira = $ls_tira.$this->arr_letra[$li_temp];
					$ldec_numero = $ldec_resto;
					if($ldec_resto > 0)
					
					{	$ls_tira = $ls_tira." y ";}
					break;	
				case $ldec_numero == 100:
					$ldec_resto = $ldec_numero % 10;
					$ldec_numero=intval($ldec_numero / 10);
					$ls_tira = $ls_tira.$this->arr_letra[37];
					$ldec_numero = $ldec_resto;
					break;
				case ($ldec_numero>=101)&&($ldec_numero<= 1000):
					$ldec_resto = fmod($ldec_numero,100);
					$ldec_numero=intval($ldec_numero / 100);
					$li_temp=$ldec_numero+37;
					$ls_tira = $ls_tira.$this->arr_letra[$li_temp];
					$ldec_numero= $ldec_resto;
					if($ldec_resto > 0)
					{	$ls_tira = $ls_tira."  ";}
					break;				
			}
		}while( $ldec_numero > 0);
		return $ls_tira;
	}
	
	function uf_menor_millon($ldec_numero)
	{
		$ls_tira = "";
		switch (true){ 
			case ($ldec_numero>=1)&&($ldec_numero<= 1000):
				$ls_tira = $this->uf_menor_mil($ldec_numero);
				break;				
			case ($ldec_numero>=1001)&&($ldec_numero<= 999999):
				$ldec_resto = fmod($ldec_numero,1000);
				$ldec_numero = intval($ldec_numero / 1000);
				$ls_tira = $this->uf_menor_mil($ldec_numero)." Mil ".$this->uf_menor_mil($ldec_resto);
				break;				
			case $ldec_numero == 1000000:
				$ls_tira = "Un Millón de ";
				break;				
		}
		return $ls_tira;
	}

}
?>
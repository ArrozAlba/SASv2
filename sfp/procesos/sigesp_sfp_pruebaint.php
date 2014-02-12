<?php
require_once('../class_folder/dao/class_sigesp_int.php');
require_once('../class_folder/dao/class_sigesp_int_scg.php');
require_once("../class_folder/dao/class_fecha.php");
require_once("../class_folder/dao/class_mensajes.php");
require_once("../class_folder/dao/class_funciones.php");
require_once("../class_folder/dao/class_sql.php");
require_once("../class_folder/dao/class_datastore.php");
//require_once("../class_folder/dao/sigesp_include.php");

echo "pruebas del modulo integrador<br>";

//cargar el comprobante con data que viene de formulacion, este arrglo se llena a partir de la formulacion

$ls_codemp  = "0001";
$ls_procede = "SFPG";
$ls_comprobante = "asdf43";	
$ls_fecha     = "2008-01-01";
$ls_descripcion = "Formulacion de presupuesto de ingresos";
$is_tipo ="N";
$is_tipocomp ="1";

//luego de hacer la formulacion (incluir en la tabla).
//- Formar el comprobante contable(llamar al correlativo de comprobantes).
//-cargar cuentas(hacer la consulta en la tabla conversiones, luego traer la cuenta contable1, traer del formulario la cuenta contable2 y el monto).
//-una vez traida la informacion de las cuentas crear  el arreglo de cuentas, montos y operacion.
// y lamar a las funciones correspondientes de acuerdo a esta prueba.
// estas funciones se encargaran de incluir la informacion en las tablas de contabilidad
// hacer una prueba con el proceso completo de incluir .
//luego cuando se actualiza algun monto.
//y tambien cuando se elimina una operacion de formulacion sea de gastos o ingresos.


$obInt= new class_sigesp_int_scg(); 
$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);

if($ls_valido)
{
	echo "lo consigio";
}
else
{
	echo "no lo consiguio";
	$result = $obInt->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipocomp,$ls_descripcion,$is_tipo);
	if($result)
	{
		//cargar las cuentas para el movimiento
		$arCuentas[1]["sc_cuenta"]="0001";
		$arCuentas[1]["operacion"]="D";
		$arCuentas[1]["monto"]=1000;
		$arCuentas[1]["documento"]=1000;
		$arCuentas[1]["procede_doc"]="SFPFGI";
		$arCuentas[2]["sc_cuenta"]="0002";
		$arCuentas[2]["operacion"]="H";
		$arCuentas[2]["monto"]=1000;
		$arCuentas[2]["documento"]=1000;
		$arCuentas[2]["procede_doc"]="SFPFGI";
		$ldec_monto_anterior=0;
		$ldec_monto_actual=	$arCuentas[2]["monto"]=1000;
	//para cada cuenta del movimiento
	foreach($arCuentas as $registroCuenta)
	{
		if(!$obInt->uf_valida_procedencia($registroCuenta["procede_doc"],&$ls_desproc ))
		{
			$msg->message("Procedencia ".$ls_procede_doc." es invalida");
			return false;	
		}
			
		$ls_valido = $obInt->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
	if($ls_valido)
	{
			//$ld_fecha=$fun->uf_convertirdatetobd($id_fecha);
		//	echo "estamos aqui";
		//	die();
			$lb_valido = $obInt->uf_scg_procesar_insert_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$is_tipo,$registroCuenta["sc_cuenta"],$registroCuenta["procede_doc"],$registroCuenta["documento"],$registroCuenta["operacion"],$ls_descripcion,$ldec_monto_anterior,$registroCuenta["monto"]);
				
		if(!$lb_valido)
		{
			$msg->message("Error al registrar movimiento contable. ".$int_scg->is_msg_error);
			return false;
		}
	}
		
}	
}

}

function PasarDatos($ObjDao,$ObJson,$evento)
{
	$ArDao = $ObjDao->getAttributeNames();
	foreach($ObjDao as $IndiceD =>$valorD)
	{
		foreach($ObJson as $Indice =>$valor)
		{
			if($Indice==$IndiceD)
			{
				$ObjDao->$Indice = utf8_decode($valor);					
			}
			else
			{
				
				$GLOBALS[$Indice] = $valor;
				
			}	
		}
	}
}

function GenerarJson($Datos)
{
	global $ArJson,$json;
	$obj = $Datos[0];
		if(is_object($obj))
		{
			foreach($obj as $Propiedad=>$valor)
			{
				$i=0;
				foreach($Datos as $obj)
				{
		
					if(array_key_exists($Propiedad,$ArJson))	
					{	
						
						$arRegistros[$i][$Propiedad]= $Datos[$i]->$Propiedad;
						$i++;
					}
				
				}
		
					
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
			
		}
}
?>
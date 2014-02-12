<?php
/***********************************************************************************
* @librería que contiene las funciones comunes usadas es todas las clases
* @fecha de creación: 16/05/2008 
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

/****************************************************************************
* 				FUNCIONES PARA EL MANEJO DE LOS DATOS
****************************************************************************/

/**************************************************************************
* @Convertir un objeto en un arreglo asociativo. 
* @Esta función convierte un objeto en un arreglo asociativo de iteración
* a través de sus propiedades públicas debido a que esta función usa
* el foreach, el constructor y las iteraciones son respetados.  
* También trabaja con arreglo de objetos. 
*
* @Parámetros: objeto $var 
* @Valor de retorno: arreglo.
* @Función predeterminada.
***************************************************************************/
function object_to_array($var) 
{
    $result = array();
    $references = array();

    // loop over elements/properties
    foreach ($var as $key => $value) 
    {
        // recursively convert objects
        if (is_object($value) || is_array($value)) 
	{
            // but prevent cycles
            if (!in_array($value, $references)) 
	    {
                $result[$key] = object_to_array($value);
                $references[] = $value;
            }
        } 
	else 
	{
            // simple values are untouched
            $result[$key] = $value;
        }
    }
    return $result;
}


/********************************************************************************
* @Convertir un valor a JSON
* @Esta función devuelve una representación JSON de $param. Utiliza json_encode
* Para lograr esto, convierte arreglos y objetos que contengan objetos a 
* arreglos asociativos en primer lugar. Así, los objetos que no expongan 
* (todas) sus propiedades directamente sino sólo a través de interfaz de 
* iteración, también son codificados correctamente.
*
* @Parámetros: objeto $param 
* @Valor de retorno: objeto Json.
* @Función predeterminada.
********************************************************************************/ 
function json_encode2($param) {
    if (is_object($param) || is_array($param)) {
        $param = object_to_array($param);
    }
    return json_encode($param);
}


/*******************************************************
* @Función para obtener el objeto Json de acuerdo a la 
* ejecución de un Execute en el modelo.
* @paràmetros: $datos
* @retorno: $textJson arreglo de objetos
********************************************************/
function generarJson($datos)
{
	global $json;
	$j=0;
	$arRegistros = '';
   	while(!$datos->EOF)
   	{   
		$i=0;
    	foreach ($datos->fields as $Propiedad=>$valor)
    	{
     		if (!is_numeric($Propiedad))
     		{
				$campo = $datos->FetchField($i);
				$tipo =  $datos->MetaType($campo->type);
				if ($tipo == 'D' || $tipo == 'T') 
				{
					$valor = convertirFecha($valor);
				}
				if ($tipo=='N')
				{
					$valor = number_format($valor,2,',','.');
				}
				$Propiedad = strtolower($Propiedad);
      			$arRegistros[$j][$Propiedad] = utf8_encode(rtrim($valor));
     		} 
			$i++;  
    	}  
    	$datos->MoveNext(); 
		$j++;
   	}
   //aqui se pasa el arreglo de arreglos a un objeto json
   	$textJso = array('raiz'=>$arRegistros);
   	$textJson = json_encode($textJso);    		
   	return $textJson;
}



/***********************************************************************************
* @Función que retorna el objeto JSON cargado con las variables de SESION
* @parametros: 
* @retorno: 
* @fecha de creación: 17/11/2008
* @autor: Johny Porras
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function generarJsonSesion()
{
	global $json;
	$i=0;
	foreach($_SESSION as $Propiedad=>$valor)
	{
		if(!is_numeric($Propiedad))
		{
			$Propiedad = strtolower($Propiedad);
			$arRegistros[$Propiedad]= utf8_encode($valor);
		}		
	}
	$TextJson = json_encode($arRegistros);
	return $TextJson;
}


/*****************************************************************
* Función para evaluar si coinciden los nombres de las propiedades 
* del objeto JSON con los del objeto DAO y copiar sus valores.
* parametros: objDao: objeto DAO, objJson: objeto JSON, 
* evento: operación. 
* fecha de creación:
* autor: Johny Porras.
******************************************************************/
function pasarDatos($objDao,$objSon,$evento='')
{
	$arDao = $objDao->getAttributeNames();
	foreach ($objDao as $IndiceD =>$valorD)
	{
		foreach ($objSon as $Indice =>$valor)
		{
			if ($Indice==$IndiceD)
			{
				$objDao->$Indice = utf8_decode($valor);					
			}
			else
			{				
				$evento[$Indice] = $valor;			
			}
		}
	}
}


/**************************************************************************************
* 							FUNCIONES PARA MANEJO DE FECHAS
***************************************************************************************/

/******************************************************
* @Función para convertir la fecha que viene de la base 
* @de datos con formato año-mm-dd al formato dia/mes/año
* @parametros: $fecha: fecha a convertir.
* @retorno: $fechamostrar: fecha a mostrar.
* @fecha de creación: 06/08/2008
* @autor:
*******************************************************/
function convertirFecha($fecha)
{
	$fechamostrar='';
	$pos  = strpos($fecha,'-'); 
	$pos2 = strpos($fecha,'/'); 
	if (($pos==4) || ($pos2==4))
	{
		$fechamostrar=(substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4)); 
	}
	elseif(($pos==2)||($pos2==2))
	{
		$fechamostrar=$fecha;
	}
	return $fechamostrar;
} 

 
/************************************************************************************
* @Función para convertir la fecha que viene del
* @formulario con formato dia/mes/año al formato año-mes-dia
* @parametros: $fecha: fecha a convertir.
* @retorno: $fechamostrar: fecha a mostrar.
* @fecha de creación: 28/08/2008
* @autor:
*************************************************************************************/
function convertirFechaBd($fecha)
{
	if (trim($fecha)=='')
	{
		$fecha='1900-01-01';
	}
	$fechabd   = ''; 
	$posicion  = strpos($fecha,'/');
	$posicion2 = strpos($fecha,'-');
	if (($posicion==2) || ($posicion2==2))
	{
		$fechabd = (substr($fecha,6,4).'-'.substr($fecha,3,2).'-'.substr($fecha,0,2)); 
	}
	elseif (($posicion==4) || ($posicion2==4))
	{
		$fechabd = str_replace('/','-',$fecha);
	}
	return $fechabd;
} 


/***********************************************************************************
* @Función que valida que al tener dos fechas (un periodo de tiempo) la fecha que 
* inicia el periodo no sea mayor a la fecha que cierra el periodo; es decir que 
* las fechas no esten solapadas.
* @parametros: fecha_desde, fecha_hasta
* @retorno: Fecha valida
* @fecha de creación: 24/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/
function compararFecha($fecdesde,$fechasta)
{		
	$fechavalida = false;
	$fecdesdeaux = convertirFechaBd($fecdesde);
	$fechastaaux = convertirFechaBd($fechasta);
	
	if (($fecdesdeaux=="")||($fechastaaux==""))
	{
		$fechavalida = false;
	}
	else
	{
		$anodesde = substr($fecdesdeaux,0,4);
		$mesdesde = substr($fecdesdeaux,5,2);
		$diadesde = substr($fecdesdeaux,8,2);
		$anohasta = substr($fechastaaux,0,4);
		$meshasta = substr($fechastaaux,5,2);
		$diahasta = substr($fechastaaux,8,2);
		
		if($anodesde < $anohasta)
		{
			$fechavalida = true;
		}
		elseif ($anodesde==$anohasta)
		{
			if ($mesdesde < $meshasta)
			{
				$fechavalida = true;
			}
			elseif ($mesdesde==$meshasta)
			{
				if ($diadesde <= $diahasta)
				{
					$fechavalida=true;
				}
			}
		}
	}
	return $fechavalida;
}


/***********************************************************************************
* @Función que retorna el último día del mes 
* @parametros: $mes, $anio
* @retorno: Fecha final
* @fecha de creación: 04/11/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function ultimoDiaMes ($mes, $anio)
{
	$dia=28; 
	while (checkdate($mes, ($dia + 1),$anio))
	{ 
	   $dia++; 
	} 
	$fecha=$anio.'-'.$mes.'-'.$dia;
	return $fecha; 
} 


/***********************************************************************************
* @Función que retorna le suma a una fecha la cantidad de días indicados
* @parametros: $fecha, $dias
* @retorno: Fecha con días sumando
* @fecha de creación: 04/11/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function sumarDias ($fecha, $ndias)
{
	if ($ndias > 0)
	{
		$dia=substr($fecha,8,2);      
		$mes=substr($fecha,5,2);      
		$anio=substr($fecha,0,4);      
		$ultimo_dia=date("d",mktime(0, 0, 0,$mes+1,0,$anio));
		$dias_adelanto=$ndias;
		$siguiente=$dia+$dias_adelanto;
		if ($ultimo_dia < $siguiente)
		{        
			$dia_final=$siguiente-$ultimo_dia;
			$mes++;         
			if($ndias=='365')
			{
				$dia_final=$dia;
			}    
			if($mes=='13')
			{            
				$anio++;
				$mes='01';        
			}      
			$fecha_final=$anio.'-'.str_pad($mes,2,"0",0).'-'.str_pad($dia_final,2,"0",0); 
		}
		else   
		{ 
			$fecha_final=$anio.'-'.str_pad($mes,2,"0",0).'-'.str_pad($siguiente,2,"0",0); 
		} 
		$dia=substr($fecha_final,8,2);
		$mes=substr($fecha_final,5,2);
		$anio=substr($fecha_final,0,4);
		while(checkdate($mes,$dia,$anio)==false)
		{ 
		   $dia=$dia-1; 
		   break;
		} 
		$fecha_final=$anio.'-'.$mes.'-'.$dia;
	}
	else
	{
		$fecha_final=convertirFechaBd($fecha);
	}
	return $fecha_final;
}	


/***********************************************************************************
* @Función para validar la fecha en cuanto al mes de apertura de la misma
* @parametros: 
* @retorno: 
* @fecha de creación: 26/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function validarFechaMes()
{
	
}


/***********************************************************************************
* @Función para validar la fecha en cuanto al mes de apertura de la misma
* @parametros: 
* @retorno: 
* @fecha de creación: 26/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function validarFechaPeriodo($fecha,$codemp)
{
	$ano = 0 ; 
	$mes = 0;
	$anoperiodo = 0;
	$mesperiodo = 0;
    $fecha = ""; 
    $periodofinal = ""; 
    $valido = true;
    $fecha = convertirFechaBd($fecha);
    $ano   = intval(substr($fecha,0,4));
    $mes   = intval(substr($fecha,5,2));
    $anoperiodo   = intval(substr($fecha,0,4));
    $mesperiodo   = intval(substr($fecha,5,2));
    $periodofinal = '31/12/'.$anoperiodo;    
    if ($ano == $anoperiodo)
	{
		if ($mes >= $mesperiodo)
		{
		   if (validarFechaMes($codemp,$fecha))
		   {
		 	  $valido = true;
		   }
		   else	 
		   {
			  $valido = false;
		 	  //$this->is_msg_error = 'Mes no esta Abierto';
		 	  echo 'Mes no esta Abierto';
			  return false;
		   }
		}
		else 
		{  
			$valido = false;	
		}
	}
	else 
	{ 
		$valido = false;	
	}

	if(!valido)
	{
		$fec = $fecha;
		$fec = substr($fec,8,2).'/'.substr($fec,5,2).'/'.substr($fec,0,4);
		echo 'La fecha es invalida, debe estar comprendido entre ['.$fec.'-'.($periodofinal).']';
	}
	return $valido;				
}


/***************************************************************************************
* 			FUNCIONES PARA MANEJO DE LOS ARCHIVOS XML
****************************************************************************************/

/***********************************************************************************
* @Función que escribe en un archivo de Texto los Resultados de la Conversión
* @parametros: 
* @retorno: 
* @fecha de creación: 22/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function  escribirArchivo(&$archivo,$mensaje)
{
	$mensaje=$mensaje."\r\n";
	if ($archivo)			
	{
		@fwrite($archivo,$mensaje);
	}
}	
	
	
/***********************************************************************************
* @Función para abrir el archivo xml de configuración de base de datos.
* @parametros: $ruta, $archivo
* @retorno: $doc documento xml
* @fecha de creación: 30/07/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function abrirArchivoXml($ruta,$archivo)
{
	$doc = new DOMDocument();
	$doc->preserveWhiteSpace = true;
	$doc->load($ruta.$archivo);
	return $doc;
}


/********************************************************************************
* 				FUNCIONES PARA EL MANEJO DE LAS SESIONES
********************************************************************************/

/***********************************************************************************
* @Función para crear las variables de sesión para la base de datos seleccionada
* @parametros: $documento, $bd base de datos 
* @retorno: $valorbd 
* @fecha de creación: 31/07/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function obtenerEmpresa($documento,$bd)
{
	$i=0;
	$conexiones = $documento->getElementsByTagName('conexion');
	if($conexiones)
	{ 
		foreach ($conexiones as $conexion)
		{	
			$io_campo = $conexion->getElementsByTagName('nombre');	
			$valorbd= rtrim($io_campo->item(0)->nodeValue);
			if ($valorbd==$bd)
			{
				$io_campo = $conexion->getElementsByTagName('servidor');
				$_SESSION['sigesp_servidor']  = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('gestor');
				$_SESSION['sigesp_gestor']    = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('login');
				$_SESSION['sigesp_usuario']   = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('password');
				$_SESSION['sigesp_clave']     = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('basedatos');
				$_SESSION['sigesp_basedatos'] = rtrim($io_campo->item(0)->nodeValue);
				$_SESSION['sigesp_intentos'] = 0;
				$io_campo = $conexion->getElementsByTagName('logo');
				$_SESSION['sigesp_logo'] = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('ancho');
				$_SESSION['sigesp_ancho'] = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('alto');
				$_SESSION['sigesp_alto'] = rtrim($io_campo->item(0)->nodeValue);
				
				// Cuando todos estén migrados se debe quitar esta line a de código
				$_SESSION['ls_hostname'] = $_SESSION['sigesp_servidor'];
				$_SESSION['ls_login'] = $_SESSION['sigesp_usuario'];
				$_SESSION['ls_password'] = $_SESSION['sigesp_clave'];
				$_SESSION['ls_database'] = $_SESSION['sigesp_basedatos'];
				$_SESSION['ls_gestor'] = $_SESSION['sigesp_gestor'];
				$_SESSION['ls_width']    = $_SESSION['sigesp_ancho'];
				$_SESSION['ls_height']   = $_SESSION['sigesp_alto'];	
				$_SESSION['ls_logo']     = $_SESSION['sigesp_logo'];					
				return $valorbd;
			}
			$i++;	
		}
	}
}


/***********************************************************************************
* @Función para crear las variables de sesión para una base de datos de destino.
* (para el transferir usuario)
* @parametros: $documento, $bd base de datos 
* @retorno: $valorbd 
* @fecha de creación: 18/11/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function crearConexionDestino($documento,$bd)
{
	$i=0;
	$conexiones = $documento->getElementsByTagName('conexion');
	if($conexiones)
	{ 
		foreach ($conexiones as $conexion)
		{	
			$io_campo = $conexion->getElementsByTagName('basedatos');	
			$valorbd= rtrim($io_campo->item(0)->nodeValue);
			if ($valorbd==$bd)
			{
				$io_campo = $conexion->getElementsByTagName('servidor');
				$_SESSION['sigesp_servidor_destino']  = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('gestor');
				$_SESSION['sigesp_gestor_destino']    = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('login');
				$_SESSION['sigesp_usuario_destino']   = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('password');
				$_SESSION['sigesp_clave_destino']     = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('basedatos');
				$_SESSION['sigesp_basedatos_destino'] = rtrim($io_campo->item(0)->nodeValue);
				$_SESSION['sigesp_intentos_destino']=0;
				
				// Cuando todos estén migrados se debe quitar esta line a de código
				$_SESSION['ls_hostname_destino'] = $_SESSION['sigesp_servidor_destino'];
				$_SESSION['ls_login_destino'] = $_SESSION['sigesp_usuario_destino'];
				$_SESSION['ls_password_destino'] = $_SESSION['sigesp_clave_destino'];
				$_SESSION['ls_database_destino'] = $_SESSION['sigesp_basedatos_destino'];
				$_SESSION['ls_gestor_destino'] = $_SESSION['sigesp_gestor_destino'];
				return $valorbd;
			}
			$i++;	
		}
	}
}


/***********************************************************************************
* @Función para obtener las base de datos del archivo xml. 
* @parametros: $documento, $datos
* @retorno: $datos
* @fecha de creación: 30/07/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function obtenerConexionbd($documento,&$datos)
{
	$li_i=0;
	$conexiones=$documento->getElementsByTagName('conexion');
	if($conexiones)
	{ 
		foreach ($conexiones as $conexion)
		{
			$io_campo = $conexion->getElementsByTagName('basedatos');
			$datos[$li_i]['codbasedatos'] = rtrim($io_campo->item(0)->nodeValue);
			$io_campo = $conexion->getElementsByTagName('nombre');
			$datos[$li_i]['basedatos'] = rtrim($io_campo->item(0)->nodeValue);
			$li_i++;
		}
	}
}


/***********************************************************************************
* @Función para obtener el valor de una variable de sessión
* @parametros: $valor, $valordefecto
* @retorno: $valor
* @fecha de creación: 03/09/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function obtenerValorSession($valor,$valordefecto)
{
	if(array_key_exists($valor,$_SESSION))
	{
		$valor=$_SESSION[$valor];
	}
	else
	{
		$valor=$valordefecto;
	}
	return $valor; 
}


/***********************************************************************************
* @Función para que valida si una sessión está activa
* @parametros: 
* @retorno: $sessionvalida
* @fecha de creación: 08/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/
function validarSession()
{
	$sessionvalida = true;
	if (array_key_exists('la_empresa',$_SESSION))
	{
		$sesion = $_SESSION['session_activa']; 
		$tiempo = $_SESSION['tiempo_session'];
		if (time()-$sesion < $tiempo) 
		{ 
			$sessionvalida = true;
		}
	}
	if($sessionvalida==false)
	{
		session_unset(); 
		$arreglo[0]['mensaje'] = obtenerMensaje('SESION_EXPIRADA'); 
		$arreglo[0]['valido']  = false;
		$respuesta  = array('raiz'=>$arreglo);
		$respuesta  = json_encode($respuesta);
		//echo $respuesta;
	}
	return $sessionvalida;
}


/***********************************************************************************
* @Función que obtiene la base de datos de apertura y carga las variables de session
* @parametros: 
* @retorno:
* @fecha de creación: 20/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function obtenerBdApertura($documento,$bd)
{
	$conexiones = $documento->getElementsByTagName('conexion');
	if($conexiones)
	{ 
		foreach ($conexiones as $conexion)
		{	
			$io_campo = $conexion->getElementsByTagName('nombre');	
			$valorbd= rtrim($io_campo->item(0)->nodeValue);
			if ($valorbd==$bd)
			{
				$io_campo = $conexion->getElementsByTagName('servidor');
				$_SESSION['sigesp_servidor_apr']  = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('gestor');
				$_SESSION['sigesp_gestor_apr']    = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('login');
				$_SESSION['sigesp_usuario_apr']   = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('password');
				$_SESSION['sigesp_clave_apr']     = rtrim($io_campo->item(0)->nodeValue);
				$io_campo = $conexion->getElementsByTagName('basedatos');
				$_SESSION['sigesp_basedatos_apr'] = rtrim($io_campo->item(0)->nodeValue);

				return $valorbd;
			}	
		}
	}
}	


/********************************************************************************
* 								OTRAS FUNCIONES
********************************************************************************/

/***********************************************************************************
* @Función para obtener el mensaje Según el tipo de Mensaje
* @parametros: $tipo
* @retorno: $mensaje
* @fecha de creación: 07/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function obtenerMensaje($tipo,$mensaje='')
{
	switch($tipo)
	{
		case 'USUARIO_NOACTIVO':
			$mensaje=utf8_encode('El usuario no esta Activo. Por favor Contacte con el Administrador del Sistema');
		break;
		case 'SESION_EXPIRADA':
			$mensaje=utf8_encode('Su sessión ha expirado. Por favor ingrese nuevamente al sistema.');
		break;
		case 'ACCION_NO_VALIDA':
			$mensaje=utf8_encode('El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema.');
		break;
		case 'DATOS_NO_VALIDO':
			$mensaje=utf8_encode('Los Datos no son válidos.');
		break;
		case 'OPERACION_EXITOSA':
			$mensaje=utf8_encode('La operación se realizó de manera exitosa.');
		break;
		case 'OPERACION_FALLIDA':
			$mensaje=utf8_encode('Ocurrio un error al realizar la operación.');
		break;
		case 'REGISTRO_EXISTE':
			$mensaje=utf8_encode('El Registro que está tratando de agregar ya existe.');
		break;
		case 'REGISTRO_NO_EXISTE':
			$mensaje=utf8_encode('El Registro que está tratando de actualizar ó eliminar no existe.');
		break;
		
		case 'ARCHIVO_NO_EXISTE':
			$mensaje=utf8_encode('No Existen Archivos.');
		break;
		
		case 'DATA_NO_EXISTE':
			$mensaje=utf8_encode('No Existen Datos.');
		break;
		
		case 'RELACION_OTRAS_TABLAS':
			$mensaje=utf8_encode('El Registro que está tratando de eliminar está en: '.$mensaje);
	}
	return $mensaje; 
}


/*************************************************************
*@Función que rellena una cadena con ceros a la izquierda 
* y le suma un número.
*@Parametros: $cod = Cadena a la cual se la sumara un numero,
* $cantidad longitud total de la cadena.								
*@Valor de retorno: cadena con la nueva cifra									
*@Fecha de Creación: 
*@Autor: Victor Mendoza							
**************************************************************/
function agregarUno($cod, $cantidad)
{
	$suma = intval($cod) + 1;
    $cad = str_pad(intval($suma), $cantidad, '0', STR_PAD_LEFT);
    return $cad;

}


/************************************************************
* @Función para rellenar una cadena con ceros a la izquierda
* @parametros: $cadena, $longitud
* @retorno: $aux: cadena.
* @fecha de creación: 01/09/2008
* @autor:
************************************************************/
function cerosIzquierda($cadena,$longitud)
{ 
	$long = 0;
	$aux = $cadena;
	$pos = strlen($cadena);
	$long = $longitud-$pos;
	for ($i=0; $i<$long; $i++)
	{  
   		$aux = '0'.$aux;   
	}
	return $aux; 
} 

/***********************************************************************************
* @Función que valida un texto.
* @parametros: 
* @retorno:
* @fecha de creación: 10/12/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
function validarTexto($valor,$inicio,$longitud,$valordefecto)
{
	$nuevovalor = $valor;
	$nuevovalor = trim($nuevovalor);
	
	if(($nuevovalor=='')||($nuevovalor==NULL))
	{
		$nuevovalor = $valordefecto;
	}
	else
	{
		$nuevovalor = str_replace("'","",$nuevovalor);
		$nuevovalor = str_replace('"',"",$nuevovalor);
		$nuevovalor = str_replace('\\',"",$nuevovalor);
		$nuevovalor = substr($nuevovalor,$inicio,$longitud);
	}
   	return $nuevovalor; 
} 
	

?>
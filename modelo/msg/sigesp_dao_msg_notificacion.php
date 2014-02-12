<?php
/***********************************************************************************
* @Clase compartida para el envio de notificaciones al correo.
* @fecha de creación: 10/07/2008
* @autor: Ing. Gusmary Balza.
* **************************
* @fecha modificacion 25/08/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion Se agrego las funciones para que tomara los valores directos de la base de datos de la configuración del servidor
***********************************************************************************/

include_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/phpMailer_v2.1/class.phpmailer.php'); //esta ruta por la prueba de inicio
include_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php'); //esta ruta por la prueba de inicio
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/mcd/sigesp_dao_mcd_configuracion.php');
require_once('sigesp_dao_msg_sistema.php');

class Notificacion extends PHPMailer
{
	public $sistema;
	public $host;
	public $puerto;	
	public $titulo;
	public $usuario;
	public $operacion;
	public $tipo;
	public $mensaje;
	public $valido;
	public $objConfiguracion;
	public $objSistema;
	
/***********************************************************************************
* @Función que Envía una notificación por correo
* @parametros: 
* @retorno: mensaje //  Donde se verifica el mensaje que da la clase que envía correo
*			valido  //  Devuelve true ó false 
* @fecha de creación: 25/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function enviarNotificacion()
	{	
		// Obtener los parámetros de configuración.
		$this->objConfiguracion = new Configuracion();
		$this->objConfiguracion->codempresa=$_SESSION['sigesp_codempresa'];
		$this->objConfiguracion->obtenerConfiguracion();
		$this->valido = true;
		// Verificamos si esta configurado el servidor para enviar correos
		if($this->objConfiguracion->msjenvio)
		{
			if($this->cargarCorreos())
			{
				// Actualizar los parametrod de configuración a las variables de las librerías de envío
				if($this->objConfiguracion->msjsmtp)
				{
					$this->IsSMTP(); // Es un Servidor SMTP
				}
				$this->Host	= $this->objConfiguracion->msjservidor;  
				$this->Port = $this->objConfiguracion->msjpuerto;
				//método para permitir los mensajes en formato html
				$this->IsHTML(true);
				//definir la dirección de correo y el nombre que se desea mostrar 				
				$this->From		= "notificacion@sigesp.com.ve";
				$this->FromName	= "NOTIFICACIÓN SIGESP";
	
	
				//definir asunto y cuerpo del mensaje, Body para formato html y AltBody en caso de que no lo acepte
				$this->Subject	= "NOTIFICACIÓN SIGESP"; 
				$this->configurarCuerpo();
				$this->Body		= $this->cuerpo;
				if(!$this->Send())
				{
					$this->mensaje = "Problema enviando correo electrónico: ".$this->ErrorInfo;
					$this->valido = false;
				} 
				else
				{
					$this->mensaje = "Mensaje enviado correctamente."; 
				}
			}
		}
		unset($this->objConfiguracion);
	}	
	
/***********************************************************************************
* @Función que Configura el Cuerpo del correo según el tipo de notificación
* @parametros: 
* @retorno: cuerpo //  Cuerpo del correo
* @fecha de creación: 25/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function configurarCuerpo()
	{	
		// Obtener los parámetros de configuración.
		switch( $this->tipo )
		{
			case "NOTIFICACION":
				$this->cuerpo = "<table width='602' border='0' cellpadding='2' cellspacing='2'>".
								"  <tr> ".
								"    <td colspan='2' align=center style=font:Castellar; font-size:12px; color:#1F5B97><div align='center'>".$this->titulo."</div></td>".
								"  </tr> ".
								"  <tr> ".
								"    <td width='109' style=font:Verdana; font-size:11px; color:#2626FF><div align='right'>Usuario</div></td> ".
								"    <td width='479' ><div align='left'>".$this->usuario."</div></td> ".
								"  </tr> ".
								"  <tr> ".
								"    <td height='37' style=font:Verdana; font-size:11px; color:#2626FF><div align='right'>Operaci&oacute;n</div></td>".
								"    <td><div align='left'>".$this->operacion."</div></td> ".
								"  </tr> ".
								"</table>";
				break;
			case "ERROR":
				$this->cuerpo = "<table width='602' border='0' cellpadding='2' cellspacing='2'>".
								"  <tr> ".
								"    <td colspan='2' align=center style=font:Castellar; font-size:12px; color:#1F5B97><div align='center'>".$this->titulo."</div></td>".
								"  </tr> ".
								"  <tr> ".
								"    <td width='109' style=font:Verdana; font-size:11px; color:#2626FF><div align='right'>Usuario</div></td> ".
								"    <td width='479' ><div align='left'>".$this->usuario."</div></td> ".
								"  </tr> ".
								"  <tr> ".
								"    <td height='37' style=font:Verdana; font-size:11px; color:#2626FF><div align='right'>Operaci&oacute;n</div></td>".
								"    <td><div align='left'>".$this->operacion."</div></td> ".
								"  </tr> ".
								"  <tr> ".
								"    <td height='37' style=font:Verdana; font-size:11px; color:#2626FF><div align='right'>Error</div></td>".
								"    <td><div align='left'>".$this->operacion."</div></td> ".
								"  </tr> ".
								"</table>";
				break;
		}
	}	
	
/***********************************************************************************
* @Función que obtiene las direcciones de correo de los admiistradores del sistema 
* @parametros: 
* @retorno: si Existen o no direcciones de correo
* @fecha de creación: 25/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function cargarCorreos()
	{	
		$existen=false;
		$this->objSistema = new Sistema();
		$this->objSistema->codempresa=$_SESSION['sigesp_codempresa'];
		$this->objSistema->codsistema=$this->sistema;
		$result=$this->objSistema->obtenerUsuarios();
		while(!$result->EOF)
		{
			$correcto=validaciones($result->fields["email"],100,'email');
			if($correcto)
			{
				$this->AddAddress($result->fields["email"], $result->fields["apellido"]." ".$result->fields["nombre"]);
				$existen=true;
			}
			$result->MoveNext();
		}
		unset($this->objSistema);
		return $existen;
	}	
}
?>
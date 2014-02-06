<?php
//Clase compartida que permitirá validar información proveniente de los archivos xml del sistema de créditos.
class class_funciones_xml
{
  var $io_docxml;
  
  function class_funciones_xml()
  {
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: class_funciones_xml
	//		   Access: public 
	//	  Description: Constructor de la Clase
	//	   Creado Por: Ing. Néstor Falcón.
	// Fecha Creación: 02/06/2007 							Fecha Última Modificación : 02/06/2007
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    require_once("class_sql.php");
	require_once("class_mensajes.php");
	require_once("sigesp_include.php");
	require_once("class_funciones.php");
	$io_include   = new sigesp_include();
	$ls_conect    = $io_include->uf_conectar();
	$this->io_sql = new class_sql($ls_conect);
	$this->io_msg = new class_mensajes();
	$this->io_funcion = new class_funciones();
  }

function uf_validar_banco($as_codemp,$as_codban)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_banco
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_codban = Código del Banco a buscar.
  //	      Returns: $lb_existe = True si el banco es encontrado, False de lo contrario.
  //	  Description: Función que localiza el código del Banco para esa empresa en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 06/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  $lb_existe = false;
  $ls_sql  = "SELECT codban FROM scb_banco WHERE codemp='".$as_codemp."' AND codban='".$as_codban."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_banco;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_cuenta_bancaria($as_codemp,$as_codban,$as_ctaban)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_cuenta_bancaria
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_codban = Código del Banco.
  //                   $as_ctaban = Número de la Cuenta Bancaria.  
  //	      Returns: $lb_existe = True si la Cuenta Bancaria es encontrada para la empresa y 
  //                                banco proporcionados como parámetros, False de lo contrario.
  //	  Description: Función que localiza el la Cuenta Bancaria del Banco para esa empresa en la 
  //                   Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 06/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT ctaban 
                FROM scb_ctabanco 
			   WHERE codemp='".$as_codemp."' 
			     AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_cuenta_bancaria;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_proveedor($as_codemp,$as_codpro)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_proveedor
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_codpro = Código del Proveedor.
  //	      Returns: $lb_existe = True si el Código del Proveedor es encontrado para la empresa proporcionada como parámetro,
  //                                False de lo contrario.
  //	  Description: Función que localiza el la Cuenta Bancaria del Banco para esa empresa en la 
  //                   Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 06/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT cod_pro 
                FROM rpc_proveedor
			   WHERE codemp='".$as_codemp."' 
				 AND cod_pro='".$as_codpro."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_proveedor;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_beneficiario($as_codemp,$as_cedben)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_beneficiario
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_cedben = Cédula del Beneficiario.
  //	      Returns: $lb_existe = True si la Cédula del Beneficiario es encontrada para la empresa proporcionada como parámetro,
  //                                False de lo contrario.
  //	  Description: Función que localiza la Cédula del Beneficiario para esa empresa en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT ced_bene 
                FROM rpc_beneficiario
			   WHERE codemp='".$as_codemp."' 
				 AND trim(ced_bene)='".trim($as_cedben)."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_beneficiario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_load_movimiento_bancario($as_codemp,$as_numdoc,$as_codban,$as_ctaban,$as_codope)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_load_movimiento_bancario
  //		   Access: private
  //	    Arguments: $as_numdoc = Número del Documento.
  //                   $as_codban = Código del Banco a buscar.
  //                   $as_ctaban = Número de la Cuenta Bancaria asociada al Banco $as_codban.
  //                   $as_codope = Código de la Operación. Sólo válidos para este caso DP=Depositos. NC=Notas de Crédito.
  //	      Returns: $lb_existe = True si el Movimiento Bancario es encontrado, False de lo contrario.
  //	  Description: Función que localiza el Movimiento Bancario para esa empresa en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 06/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT numdoc 
                FROM scb_movbco
			   WHERE codemp='".$as_codemp."' 
			     AND numdoc='".$as_numdoc."'
				 AND codban='".$as_codban."'
				 AND ctaban='".$as_ctaban."'
				 AND codope='".$as_codope."'
				 AND estmov='N'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_load_movimiento_bancario;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_scgcuenta($as_codemp,$as_scgcta)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_scgcuenta
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_scgcta = Código Contable de la Cuenta dentro del Plan de Cuentas de Contabilidad.
  //	      Returns: $lb_existe = True si la Cuenta Contable es encontrada para la empresa proporcionada como parámetro,
  //                                False de lo contrario.
  //	  Description: Función que localiza la Cuenta Contable para esa empresa en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT sc_cuenta 
                FROM scg_cuentas
			   WHERE codemp='".$as_codemp."' 
				 AND trim(sc_cuenta)='".trim($as_scgcta)."'
				 AND status ='C'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_scgcuenta;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_spicuenta($as_codemp,$as_spicta)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_spicuenta
  //		   Access: private
  //	    Arguments: $as_codemp = Código de la Empresa.
  //                   $as_spicta = Código Presupuestario de la Cuenta dentro del Plan de Cuentas del Presupuesto de Ingreso.
  //	      Returns: $lb_existe = True si la Cédula del Beneficiario es encontrada para la empresa proporcionada como parámetro,
  //                                False de lo contrario.
  //	  Description: Función que localiza el Código Presupuestario de la Cuenta para esa empresa en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT spi_cuenta 
                FROM spi_cuentas
			   WHERE codemp='".$as_codemp."' 
				 AND trim(spi_cuenta)='".trim($as_spicta)."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_spicuenta;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	   echo $this->io_sql->message;
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}

function uf_validar_spioperacion($as_spiope)
{
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_validar_spioperacion
  //		   Access: private
  //	    Arguments: $as_spiope = Código de la Operación de Ingreso a registrar.
  //	      Returns: $lb_existe = True si la operación es encontrada, False de lo contrario.
  //	  Description: Función que la operación de ingreso a registrar en la Base de Datos.
  //	   Creado Por: Ing. Nestor Falcón.
  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  $lb_existe = false;
  $ls_sql  = "SELECT operacion 
                FROM spi_operaciones
			   WHERE trim(operacion)='".trim($as_spiope)."'";
  $rs_data = $this->io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $this->io_msg->message("CLASE->class_funciones_xml.php->MÉTODO->uf_validar_spioperacion;ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	 }
  else
     {
	   if ($row=$this->io_sql->fetch_row($rs_data))
	      {
		    $lb_existe = true;
		  }
	 }
  return $lb_existe;
}


function uf_validar_tiposolicitud($as_codtipsol)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_validar_tiposolicitud
	//		   Access: private
	//	    Arguments: as_codtipsol Código del tipo de solicitud
	//	      Returns: Retorna si el tipo solicitud filtrado existe 
	//	  Description: Retorna si el tipo solicitud filtrado existe 
	//	   Creado Por: Ing. Yesenia Moreno de Lang
	//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_existe=false;
	$ls_sql="SELECT estope, modsep ". 
			"  FROM sep_tiposolicitud ".
			" WHERE codtipsol = '".$as_codtipsol."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->class XML MÉTODO->uf_validar_tiposolicitud ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$ls_estope=$row["estope"];
			$ls_modsep=$row["modsep"];
			if(($ls_estope=='O')&&($ls_modsep=='O'))
			{
				$lb_existe=true;
			}
			else
			{
				$this->io_msg->message("El tipo de solicitud ".$as_codtipsol.". no existe.");	
			}
		}
	}
	return $lb_existe;
}

function uf_validar_unidadadministrativa($as_codemp, $as_coduniadm)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_validar_unidadadministrativa
	//		   Access: private
	//	    Arguments: as_codemp Código de Empresa
	//	   			   as_coduniadm Código del tipo de solicitud
	//	      Returns: Retorna si el tipo solicitud filtrado existe 
	//	  Description: Retorna si el tipo solicitud filtrado existe 
	//	   Creado Por: Ing. Yesenia Moreno de Lang
	//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_existe=false;
	$ls_sql="SELECT codemp ". 
			"  FROM spg_unidadadministrativa ".
			" WHERE codemp = '".$as_codemp."'";
			"   AND coduniadm = '".$as_coduniadm."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->class XML MÉTODO->uf_validar_unidadadministrativa ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_existe=true;
		}
		else
		{
			$this->io_msg->message("La Unidad Administrativa ".$as_coduniadm.". no existe.");	
		}
	}
	return $lb_existe;
}

function uf_validar_estructuraunidad($as_codemp,$as_coduniadm,$as_estcla,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
									$as_codestpro5)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_validar_unidadadministrativa
	//		   Access: private
	//	    Arguments: as_codemp Código de Empresa
	//	   			   as_coduniadm Código del tipo de solicitud
	//	   			   as_estcla Estatus de Clasificación
	//	   			   as_codestpro1 Código de Estructura Presupuestaria 1
	//	   			   as_codestpro2 Código de Estructura Presupuestaria 2
	//	   			   as_codestpro3 Código de Estructura Presupuestaria 3
	//	   			   as_codestpro4 Código de Estructura Presupuestaria 4
	//	   			   as_codestpro5 Código de Estructura Presupuestaria 5
	//	      Returns: Retorna si el tipo solicitud filtrado existe 
	//	  Description: Retorna si el tipo solicitud filtrado existe 
	//	   Creado Por: Ing. Yesenia Moreno de Lang
	//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_existe=false;
	$ls_sql="SELECT codemp ". 
			"  FROM spg_dt_unidadadministrativa ".
			" WHERE codemp = '".$as_codemp."'";
			"   AND coduniadm = '".$as_coduniadm."'".
			"   AND estcla = '".$as_estcla."'".
			"   AND codestpro1 = '".$as_codestpro1."'".
			"   AND codestpro2 = '".$as_codestpro2."'".
			"   AND codestpro3 = '".$as_codestpro3."'".
			"   AND codestpro4 = '".$as_codestpro4."'".
			"   AND codestpro5 = '".$as_codestpro5."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->class XML MÉTODO->uf_validar_estructuraunidad ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_existe=true;
		}
		else
		{
			$this->io_msg->message("La estructura presupuestaria ".$as_codestpro1."-".$as_codestpro2."-".$as_codestpro3."-".$as_codestpro4."-".$as_codestpro5."-".$as_estcla." no se encuentra en la Unidad Administrativa ".$as_coduniadm.".");	
		}
	}
	return $lb_existe;
}

function uf_validar_conceptosep($as_codconsep,$as_spg_cuenta)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_validar_conceptosep
	//		   Access: private
	//	    Arguments: as_codconsep Código del Concepto
	//	    		   as_spg_cuenta Cuenta Presupuestaria
	//	      Returns: Retorna si el Código del Concepto filtrado existe 
	//	  Description: Retorna si el Código del Concepto filtrado existe 
	//	   Creado Por: Ing. Yesenia Moreno de Lang
	//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_existe=false;
	$ls_sql="SELECT spg_cuenta ". 
			"  FROM sep_conceptos ".
			" WHERE codconsep = '".$as_codconsep."'".
			"   AND spg_cuenta = '".$as_spg_cuenta."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->class XML MÉTODO->uf_validar_conceptosep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_existe=true;
		}
		else
		{
			$this->io_msg->message("El concepto ".$as_codconsep." con la cuenta ".$as_spg_cuenta.". no existe.");	
		}
	}
	return $lb_existe;
}

function uf_validar_cuentaspresupuestarias($as_codemp,$as_spg_cuenta,$as_estcla,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
										   $as_codestpro5)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_validar_unidadadministrativa
	//		   Access: private
	//	    Arguments: as_codemp Código de Empresa
	//	   			   as_spg_cuenta Cuenta Presupuestaria
	//	   			   as_estcla Estatus de Clasificación
	//	   			   as_codestpro1 Código de Estructura Presupuestaria 1
	//	   			   as_codestpro2 Código de Estructura Presupuestaria 2
	//	   			   as_codestpro3 Código de Estructura Presupuestaria 3
	//	   			   as_codestpro4 Código de Estructura Presupuestaria 4
	//	   			   as_codestpro5 Código de Estructura Presupuestaria 5
	//	      Returns: Retorna si el tipo solicitud filtrado existe 
	//	  Description: Retorna si el tipo solicitud filtrado existe 
	//	   Creado Por: Ing. Yesenia Moreno de Lang
	//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	$lb_existe=false;
	$ls_sql="SELECT codemp ". 
			"  FROM spg_cuentas ".
			" WHERE codemp = '".$as_codemp."'".
			"   AND spg_cuenta = '".$as_spg_cuenta."'".
			"   AND estcla = '".$as_estcla."'".
			"   AND codestpro1 = '".$as_codestpro1."'".
			"   AND codestpro2 = '".$as_codestpro2."'".
			"   AND codestpro3 = '".$as_codestpro3."'".
			"   AND codestpro4 = '".$as_codestpro4."'".
			"   AND codestpro5 = '".$as_codestpro5."'";
	$rs_data = $this->io_sql->select($ls_sql);
	if ($rs_data===false)
	{
		$this->io_msg->message("CLASE->class XML MÉTODO->uf_validar_cuentaspresupuestarias ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_data))
		{
			$lb_existe=true;
		}
		else
		{
			$this->io_msg->message("La cuenta ".$as_spg_cuenta." no existe en La estructura presupuestaria ".$as_codestpro1."-".$as_codestpro2."-".$as_codestpro3."-".$as_codestpro4."-".$as_codestpro5."-".$as_estcla.".");	
		}
	}
	return $lb_existe;
}

function uf_load_archivos($as_path)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_archivos
	//		   Access: public
	//		 Argument: as_path        // directorio para leer los archivos
	//	  Description: Función que los archivos que se encuentran en un directorio especifico
	//	   Creado Por: Ing. Luis Anibal Lang
	// Fecha Creación: 04/07/2008								Fecha Última Modificación : 
	//////////////////////////////////////////////////////////////////////////////
	$ls_dir = opendir($as_path);
	$li_i=0;
	$la_archivos="";
	while ($ls_archivo = readdir($ls_dir))
	{ 
	   $ls_extension=$this->uf_load_extension($ls_archivo);
	   if($ls_extension=="xml")
	   {
			$li_i++;
			$la_archivos["filnam"][$li_i]=$ls_archivo;
	   }
	}
	closedir($ls_dir);
	if($la_archivos=="")
	{
		$this->io_msg->message("La Carpeta esta vacia.");
	} 
	return $la_archivos;
}

function uf_load_extension($as_archivo)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_load_extension
	//		   Access: public
	//		 Argument: as_archivo // nombre del archivo
	//	  Description: Función que obtiene la extension de un archivo
	//	   Creado Por: Ing. Luis Anibal Lang
	// Fecha Creación: 04/07/2008								Fecha Última Modificación : 
	//////////////////////////////////////////////////////////////////////////////
	$li_posicion = strrpos($as_archivo,'.')+1;
	$ls_extension =  substr($as_archivo,$li_posicion);
	return $ls_extension;
}

function uf_mover_xml($as_filnam,$as_rutori,$as_dirdes)
{
  //////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_mover_xml
  //		   Access: public
  //		 Argument: $as_filnam = Nombre del archivo xml a copiar. 
  //                   $as_rutori = Ruta completa de ubicación del archivo xml a copiar. 
  //                   $as_dirdes = Directorio destino.
  //	  Description: Función que realiza una copia del archivo $as_filnam al directorio
  //                   $as_dirdes, y eliminando el original del directorio $as_rutori.
  //	   Creado Por: Ing. Néstor Falcón.
  //   Fecha Creación: 09/08/2008		   Fecha Última Modificación : 09/08/08.
  //////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  if (file_exists($as_rutori))
     {
	   if (is_dir($as_dirdes))//Verificación del Directorio destino.
	      {
			@chmod($as_dirdes,0777);
			$ls_rutdes = $as_dirdes.'/'.$as_filnam;//Se concatena el nombre del archivo para ser copiado a su destino.
			if (copy($as_rutori,$ls_rutdes))
			   {
				 @chmod($as_rutori,0777);
				 unlink($as_rutori); 
			   } 	  
		    else
			   {
				 echo "Error en copia de archivo !!!";
				 return false;
			   }
		  }
	   else
	      {
		    echo "Directorio Destino no encontrado !!!";
			return false;
		  }
	 }
  else
     {
	   echo "Archivo $as_rutori, no encontrado !!!";
	   return false;
	 }
  return $lb_valido;
}

function uf_update_xml_procesado($as_filname,$as_dirdes,$as_tagnam,$ab_estxml,$as_conxml)
{
  ///////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_mover_xml
  //		   Access: public
  //		 Argument: $as_filnam = Nombre del archivo xml a copiar. 
  //                   $as_rutori = Ruta completa de ubicación del archivo xml a copiar. 
  //                   $as_dirdes = Directorio destino.
  //                   $as_tagnam = Nombre de la Etiqueta padre del archivo xml a buscar 
  //                                para la modificación.
  //                   $as_elemod = Nombre del elemento hijo asociado a la etiqueta a buscar 
  //                                para la modificación.
  //	  Description: Función que realiza una copia del archivo $as_filnam al directorio
  //                   $as_dirdes, y eliminando el original del directorio $as_rutori.
  //	   Creado Por: Ing. Néstor Falcón.
  //   Fecha Creación: 09/08/2008		   Fecha Última Modificación : 10/08/08.
  ////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $lb_estxml = "F";
  if ($ab_estxml==1)
     {
	   $lb_estxml = "T";
	 }
  $ls_filnam = $as_dirdes.'/'.$as_filname;
  if (is_dir($as_dirdes))//Verificación del Directorio destino.
	 {
	   //chmod($as_dirdes,0777);
	   if (file_exists($ls_filnam) && is_writable($ls_filnam))
          {
		    $io_docxml = new DOMDocument();
		    $io_docxml->load($ls_filnam);
			$registros = $io_docxml->getElementsByTagName("$as_tagnam");
			foreach ($registros as $registro)
			        {
				      $io_datos = $registro->getElementsByTagName('dato');
				      foreach ($io_datos as $io_dato)
				              {
								$io_campo = $io_dato->getElementsByTagName("estatus");
								$io_campo->item(0)->nodeValue = "$lb_estxml";

								$io_campo = $io_dato->getElementsByTagName("concepto");
								$io_campo->item(0)->nodeValue = utf8_encode("$as_conxml");

								$io_docxml->save($ls_filnam);
							  }
		            }
		  }
	   else
	      {
		    $this->io_msg->message("Archivo no encontrado o sin Permisos !!!");
		  }	 
	 }
  else
     {
	   $this->io_msg->message("Error en Ruta, Directorio Destino no encontrado !!!");
	 }
  return $lb_valido;
}

function uf_update_xml_progpago($as_filnam,$as_codban,$as_nomban,$as_ctaban)
{
  ///////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_update_xml_progpago (METODO INCONCLUSO PENDIENTE PARA SU MODIFICACION)	
  //		   Access: public
  //		 Argument: $as_filnam = Nombre del archivo xml a copiar. 
  //                   $as_rutori = Ruta completa de ubicación del archivo xml a copiar. 
  //                   $as_dirdes = Directorio destino.
  //                   $as_tagnam = Nombre de la Etiqueta padre del archivo xml a buscar 
  //                                para la modificación.
  //                   $as_elemod = Nombre del elemento hijo asociado a la etiqueta a buscar 
  //                                para la modificación.
  //	  Description: Función que realiza una copia del archivo $as_filnam al directorio
  //                   $as_dirdes, y eliminando el original del directorio $as_rutori.
  //	   Creado Por: Ing. Néstor Falcón.
  //   Fecha Creación: 09/08/2008		   Fecha Última Modificación : 10/08/08.
  ////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_filnam = $as_dirdes.'/'.$as_filnam;
  if (is_dir($as_dirdes))//Verificación del Directorio destino.
	 {
	   //chmod($as_dirdes,0777);
	   if (file_exists($ls_filnam) && is_writable($ls_filnam))
          {
		    $io_docxml = new DOMDocument();
		    $io_docxml->load($ls_filnam);
			$registros = $io_docxml->getElementsByTagName("LIQUIDETALLE");
			foreach ($registros as $registro)
			        {
				      $io_datos = $registro->getElementsByTagName('dato');
				      foreach ($io_datos as $io_dato)
				              {
							    $io_campo = $io_dato->getElementsByTagName("codban");
								$io_campo->item(0)->nodeValue = "$as_codban";

							    $io_campo = $io_dato->getElementsByTagName("nomban");
								$io_campo->item(0)->nodeValue = "$as_nomban";

							    $io_campo = $io_dato->getElementsByTagName("ctaban");
								$io_campo->item(0)->nodeValue = "$as_ctaban";
							  }
				    }
          }
     }		  
}

function uf_update_xml_solicitud($as_filname,$as_dirdes,$as_tagnam,$as_numsol)
{
  ///////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_update_xml_solicitud
  //		   Access: public
  //		 Argument: $as_filnam = Nombre del archivo xml a copiar. 
  //                   $as_rutori = Ruta completa de ubicación del archivo xml a copiar. 
  //                   $as_dirdes = Directorio destino.
  //                   $as_tagnam = Nombre de la Etiqueta padre del archivo xml a buscar 
  //                                para la modificación.
  //	  Description: Función que realiza una copia del archivo $as_filnam al directorio
  //                   $as_dirdes, y eliminando el original del directorio $as_rutori.
  //	   Creado Por: Ing. Néstor Falcón.
  //   Fecha Creación: 09/08/2008		   Fecha Última Modificación : 10/08/08.
  ////////////////////////////////////////////////////////////////////////////////////////

  $lb_valido = true;
  $ls_filnam = $as_dirdes.'/'.$as_filname;
  if (is_dir($as_dirdes))//Verificación del Directorio destino.
	 {
	   //chmod($as_dirdes,0777);
	   if (file_exists($ls_filnam) && is_writable($ls_filnam))
          {
		    $io_docxml = new DOMDocument();
		    $io_docxml->load($ls_filnam);
			$registros = $io_docxml->getElementsByTagName("$as_tagnam");
			foreach ($registros as $registro)
			        {
				      $io_datos = $registro->getElementsByTagName('dato');
				      foreach ($io_datos as $io_dato)
				              {
								$io_campo = $io_dato->getElementsByTagName("numsol");
								$io_campo->item(0)->nodeValue = "$as_numsol";

								$io_campo = $io_dato->getElementsByTagName("estatus");
								$io_campo->item(0)->nodeValue = "T";

								$io_campo = $io_dato->getElementsByTagName("concepto");
								$io_campo->item(0)->nodeValue = "EL credito se registro correctamente.";

								$io_docxml->save($ls_filnam);
							  }
		            }
		  }
	   else
	      {
		    $this->io_msg->message("Archivo no encontrado o sin Permisos !!!");
		  }	 
	 }
  else
     {
	   $this->io_msg->message("Error en Ruta, Directorio Destino no encontrado !!!");
	 }
  return $lb_valido;
}

function uf_update_xml_liquidacion($as_filnam,$as_dirdes,$as_codban,$as_ctaban,$as_numdoc,$as_codope,$as_chevau,$as_conmov,$ad_fecmov,$ab_estxml,$as_conxml)
{
  ///////////////////////////////////////////////////////////////////////////////////////
  //	     Function: uf_update_xml_liquidacion
  //		   Access: public
  //		 Argument: $as_filnam = Nombre del archivo xml a copiar. 
  //                   $as_dirdes = Directorio destino.
  //                   $as_codban = Código del Banco.
  //                   $as_ctaban = Cuenta Bancaria.
  //                   $as_codope = Código de la operación CH=Cheque y ND=Nota de Débito.
  //                   $as_chevau = Número del Cheque Voucher para el caso de los cheques.
  //                   $ab_estxml = Booleano que indicará si la operación fué o no exitosa.
  //                   $as_conxml = Concepto con el resultado de la Operación.
  //	  Description: Función que realiza la actualizacion de los archivos previstos para realizar
  //                   liquidaciones una vez copiados a la carpeta scc/liquidacion/procesados.
  //	   Creado Por: Ing. Néstor Falcón.
  //   Fecha Creación: 30/07/2008		   Fecha Última Modificación : 30/07/08.
  ////////////////////////////////////////////////////////////////////////////////////////


  $lb_valido = true;
  $lb_estxml = "F";
  $ad_fecmov=$this->io_funcion->uf_convertirdatetobd($ad_fecmov);
  if ($ab_estxml==1)
     {
	   $lb_estxml = "T";
	   $as_conxml = "Movimiento Registrado con Éxito !!!";
	 }
  $ls_filnam = $as_dirdes.'/'.$as_filnam;
  if (is_dir($as_dirdes))//Verificación del Directorio destino.
	 {
	   //chmod($as_dirdes,0777);
	   if (file_exists($ls_filnam) && is_writable($ls_filnam))
          {
		    $io_docxml = new DOMDocument("1.0");
			$io_docxml->formatOutput = true;
		    $io_docxml->load($ls_filnam);
			$registros = $io_docxml->getElementsByTagName("SCB_MOVBCO");
			foreach ($registros as $registro)
			        {
				      $io_datos = $registro->getElementsByTagName('dato');
				      foreach ($io_datos as $io_dato)
				              {
								if ($as_codope=='CH')
								   {
									 $io_campo = $io_dato->getElementsByTagName("codban");
									 $io_campo->item(0)->nodeValue = "$as_codban";
	
									 $io_campo = $io_dato->getElementsByTagName("ctaban");
									 $io_campo->item(0)->nodeValue = "$as_ctaban";
									 
									 $io_campo = $io_dato->getElementsByTagName("chevau");
								     $io_campo->item(0)->nodeValue = "$as_chevau";
								   }
								$io_campo = $io_dato->getElementsByTagName("numdoc");
								$io_campo->item(0)->nodeValue = "$as_numdoc";
								
								$io_campo = $io_dato->getElementsByTagName("conmov");
								$io_campo->item(0)->nodeValue = utf8_encode("$as_conmov");

								$io_campo = $io_dato->getElementsByTagName("estatus");
								$io_campo->item(0)->nodeValue = "$lb_estxml";

								$io_campo = $io_dato->getElementsByTagName("concepto");
								$io_campo->item(0)->nodeValue = utf8_encode("$as_conxml");
								
								$io_campo = $io_dato->getElementsByTagName("fecmov");
								$io_campo->item(0)->nodeValue = "$ad_fecmov";
									
								$io_docxml->save($ls_filnam);
							  }
		            }
		  }
	   else
	      {
		    $this->io_msg->message("Archivo no encontrado o sin Permisos !!!");
		  }	 
	 }
  else
     {
	   $this->io_msg->message("Error en Ruta, Directorio Destino no encontrado !!!");
	 }
  return $lb_valido;
}

///////////////////////////////////////////////////////
//Carga de Datos desde los archivos xml según Etapa. //
///////////////////////////////////////////////////////


	///////////////////////////////////////////////////////////////////////////////////////////////
	// Aprobación de Créditos 
	// Creación de la Solicitud de Ejecución Presupuestaria
	///////////////////////////////////////////////////////////////////////////////////////////////
	function uf_cargar_sep_solicitud($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_rpc_beneficiario
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo la cabecera del comprobante generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SEP_SOLICITUD');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$solicitudes = $registro->getElementsByTagName('dato');
				foreach ($solicitudes as $solicitud)
				{
					$io_campo = $solicitud->getElementsByTagName("numsol");
					$ls_numsol= rtrim($io_campo->item(0)->nodeValue);
					if($ls_numsol=='')
					{
						$io_campo = $solicitud->getElementsByTagName("numsol");
						$lr_datos[$li_i]['numsol']= rtrim($io_campo->item(0)->nodeValue);
						
						$io_campo = $solicitud->getElementsByTagName("codtipsol");
						$lr_datos[$li_i]['codtipsol']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("coduniadm");
						$lr_datos[$li_i]['coduniadm']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("estcla");
						$lr_datos[$li_i]['estcla']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("codestpro1");
						$lr_datos[$li_i]['codestpro1']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("codestpro2");
						$lr_datos[$li_i]['codestpro2']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("codestpro3");
						$lr_datos[$li_i]['codestpro3']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("codestpro4");
						$lr_datos[$li_i]['codestpro4']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("codestpro5");
						$lr_datos[$li_i]['codestpro5']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("consol");
						$lr_datos[$li_i]['consol']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("monto");
						$lr_datos[$li_i]['monto']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $solicitud->getElementsByTagName("tipo_destino");
						$lr_datos[$li_i]['tipo_destino']= $io_campo->item(0)->nodeValue;

						$io_campo = $solicitud->getElementsByTagName("ced_bene");
						$lr_datos[$li_i]['ced_bene']= $io_campo->item(0)->nodeValue;
						
						$li_i++;	  
					}
				}
			}
		}
		return $lr_datos;
	}	

	function uf_cargar_set_dt_conceptos($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_set_dt_conceptos
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el detalle de una sep
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SEP_DT_CONCEPTOS');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$solicitudes = $registro->getElementsByTagName('dato');
				foreach ($solicitudes as $solicitud)
				{
					$io_campo = $solicitud->getElementsByTagName("codconsep");
					$lr_datos[$li_i]['codconsep']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $solicitud->getElementsByTagName("moncon");
					$lr_datos[$li_i]['moncon']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $solicitud->getElementsByTagName("spg_cuenta");
					$lr_datos[$li_i]['spg_cuenta']= $io_campo->item(0)->nodeValue;
				}	
			}		
		}
		return $lr_datos;
	}	

	function uf_obtener_nrosep($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_rpc_beneficiario
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo la cabecera del comprobante generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$ls_numsol="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SEP_SOLICITUD');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$solicitudes = $registro->getElementsByTagName('dato');
				foreach ($solicitudes as $solicitud)
				{
					$io_campo = $solicitud->getElementsByTagName("numsol");
					$ls_numsol= rtrim($io_campo->item(0)->nodeValue);
				}
			}
		}
		return $ls_numsol;
	}	

	///////////////////////////////////////////////////////////////////////////////////////////////
	// II Etapa de Integración.
	// Creación del Compromiso del Crédito con sus detallesPresupuestarios de Gasto
	///////////////////////////////////////////////////////////////////////////////////////////////
	function uf_cargar_rpc_beneficiario($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_rpc_beneficiario
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el beneficiario generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('RPC_BENEFICIARIO');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$beneficiarios = $registro->getElementsByTagName('dato');
				foreach ($beneficiarios as $beneficiario)
				{
					$io_campo = $beneficiario->getElementsByTagName("codemp");
					$lr_datos[$li_i]['codemp']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $beneficiario->getElementsByTagName("ced_bene");
					$lr_datos[$li_i]['ced_bene']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $beneficiario->getElementsByTagName("codpai");
					$lr_datos[$li_i]['codpai']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codest");
					$lr_datos[$li_i]['codest']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codmun");
					$lr_datos[$li_i]['codmun']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codpar");
					$lr_datos[$li_i]['codpar']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codtipcta");
					$lr_datos[$li_i]['codtipcta']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("rifben");
					$lr_datos[$li_i]['rifben']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("nombene");
					$lr_datos[$li_i]['nombene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("apebene");
					$lr_datos[$li_i]['apebene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("dirbene");
					$lr_datos[$li_i]['dirbene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("telbene");
					$lr_datos[$li_i]['telbene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("celbene");
					$lr_datos[$li_i]['celbene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("email");
					$lr_datos[$li_i]['email']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("sc_cuenta");
					$lr_datos[$li_i]['sc_cuenta']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codbansig");
					$lr_datos[$li_i]['codbansig']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("foto");
					$lr_datos[$li_i]['foto']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("fecregben");
					$lr_datos[$li_i]['fecregben']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("nacben");
					$lr_datos[$li_i]['nacben']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("numpasben");
					$lr_datos[$li_i]['numpasben']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $beneficiario->getElementsByTagName("tipconben");
					$lr_datos[$li_i]['tipconben']= $io_campo->item(0)->nodeValue;
					$li_i++;
				}
			} 
		}
		return $lr_datos;
	}	

	function uf_cargar_sigesp_cmp($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_rpc_beneficiario
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo la cabecera del comprobante generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SIGESP_CMP');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$comprobantes = $registro->getElementsByTagName('dato');
				foreach ($comprobantes as $comprobante)
				{
					$io_campo = $comprobante->getElementsByTagName('codemp');
					$lr_datos[$li_i]['codemp']= $io_campo->item(0)->nodeValue;

					$io_campo = $comprobante->getElementsByTagName("procede");
					$lr_datos[$li_i]['procede']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $comprobante->getElementsByTagName("comprobante");
					$lr_datos[$li_i]['comprobante']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("fecha");
					$lr_datos[$li_i]['fecha']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("descripcion");
					$lr_datos[$li_i]['descripcion']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("tipo_comp");
					$lr_datos[$li_i]['tipo_comp']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("tipo_destino");
					$lr_datos[$li_i]['tipo_destino']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("cod_pro");
					$lr_datos[$li_i]['cod_pro']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("ced_bene");
					$lr_datos[$li_i]['ced_bene']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("total");
					$lr_datos[$li_i]['total']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $comprobante->getElementsByTagName("numpolcon");
					$lr_datos[$li_i]['numpolcon']= $io_campo->item(0)->nodeValue;
					
					$li_i++;	  
				}
			}
		}
		return $lr_datos;
	}	

	function uf_cargar_spg_dt_cmp($as_filnam,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_rpc_beneficiario
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo la cabecera del comprobante generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 03/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SPG_DT_CMP');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$comprobantes = $registro->getElementsByTagName('dato');
				foreach ($comprobantes as $comprobante)
				{
					$io_campo = $registro->getElementsByTagName("codemp");
					$lr_datos[$li_i]['codemp']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("procede");
					$lr_datos[$li_i]['procede']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("comprobante");
					$lr_datos[$li_i]['comprobante']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("fecha");
					$lr_datos[$li_i]['fecha']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban']= $io_campo->item(0)->nodeValue;
		
					if(($lr_datos[$li_i]['codemp']==$as_codemp)&&
					   ($lr_datos[$li_i]['procede']==$as_procede)&&
					   ($lr_datos[$li_i]['comprobante']==$as_comprobante)&&
					   ($lr_datos[$li_i]['fecha']==$as_fecha)&&
					   ($lr_datos[$li_i]['codban']==$as_codban)&&
					   ($lr_datos[$li_i]['ctaban']==$as_ctaban))
					{
						$io_campo = $registro->getElementsByTagName("estcla");
						$lr_datos[$li_i]['estcla']= $io_campo->item(0)->nodeValue;

						$io_campo = $registro->getElementsByTagName("codestpro1");
						$lr_datos[$li_i]['codestpro1']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("codestpro2");
						$lr_datos[$li_i]['codestpro2']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("codestpro3");
						$lr_datos[$li_i]['codestpro3']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("codestpro4");
						$lr_datos[$li_i]['codestpro4']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("codestpro5");
						$lr_datos[$li_i]['codestpro5']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("spg_cuenta");
						$lr_datos[$li_i]['spg_cuenta']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("procede_doc");
						$lr_datos[$li_i]['procede_doc']= $io_campo->item(0)->nodeValue;
		
						$io_campo = $registro->getElementsByTagName("documento");
						$lr_datos[$li_i]['documento']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("operacion");
						$lr_datos[$li_i]['operacion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("descripcion");
						$lr_datos[$li_i]['descripcion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("monto");
						$lr_datos[$li_i]['monto']= $io_campo->item(0)->nodeValue;
					
						$li_i++;	  
					}
					else
					{
						$lr_datos[$li_i]['estcla']= "";
						$lr_datos[$li_i]['codestpro1']= "";
						$lr_datos[$li_i]['codestpro2']= "";
						$lr_datos[$li_i]['codestpro3']= "";
						$lr_datos[$li_i]['codestpro4']= "";
						$lr_datos[$li_i]['codestpro5']= "";
						$lr_datos[$li_i]['spg_cuenta']= "";
						$lr_datos[$li_i]['procede_doc']= "";
						$lr_datos[$li_i]['documento']= "";
						$lr_datos[$li_i]['operacion']= "";
						$lr_datos[$li_i]['descripcion']= "";
						$lr_datos[$li_i]['monto']= "";
					}
				}	
			}		
		}
		return $lr_datos;
	}	

	///////////////////////////////////////////////////////////////////////////////////////////////
	// III Etapa de Integración.
	// Creación del Compromiso del Crédito con sus detallesPresupuestarios de Gasto
	///////////////////////////////////////////////////////////////////////////////////////////////
	function uf_cargar_desembolso($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_desembolso
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el desembolso generado por la cobranza 
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 14/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('Solicitud_Desembolso');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$desembolsos = $registro->getElementsByTagName('dato');
				foreach ($desembolsos as $desembolso)
				{
					$io_campo = $desembolso->getElementsByTagName("ced_bene");
					$lr_datos[$li_i]['ced_bene']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $desembolso->getElementsByTagName("prestamo");
					$lr_datos[$li_i]['prestamo']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $desembolso->getElementsByTagName("monto");
					$lr_datos[$li_i]['monto']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $desembolso->getElementsByTagName("procede");
					$lr_datos[$li_i]['procede']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $desembolso->getElementsByTagName("fecreg");
					$lr_datos[$li_i]['fecreg']= $io_campo->item(0)->nodeValue;
					$li_i++;
				}
			} 
		}
		return $lr_datos;
	}	


///////////////////////////////////////////////////////////////////////////////////////////////
// IV Etapa de Integración. Liquidación.
// Programación del Pago y Emisión de los Cheques.
///////////////////////////////////////////////////////////////////////////////////////////////

	function uf_cargar_solicitudes_pago($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_solicitudes_pago
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //	  Description: Devuelve mediante un arreglo las Solicitudes de pago dispuestas para la programación de Pagos.
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $li_i = 1;
	  $io_docxml = new DOMDocument();
	  $io_docxml->load($as_filnam);
	  $registros = $io_docxml->getElementsByTagName("LIQUIDACION");
					
	  foreach ($registros as $registro)
			  {
				$io_cedben = $registro->getElementsByTagName("ced_bene");
				$lr_datos[$li_i]['ced_bene'] = $io_cedben->item(0)->nodeValue;
						  
				$io_numsol = $registro->getElementsByTagName("numsol");
				$lr_datos[$li_i]['numsol'] = $io_numsol->item(0)->nodeValue;
						  
				$io_monto = $registro->getElementsByTagName("monto");
				$lr_datos[$li_i]['monto'] = $io_monto->item(0)->nodeValue;
						  
				$io_fecliq = $registro->getElementsByTagName("fecliq");
				$lr_datos[$li_i]['fecliq'] = $io_fecliq->item(0)->nodeValue; 
						  
				$io_estatus = $registro->getElementsByTagName("estatus");
				$lr_datos[$li_i]['estatus'] = $io_estatus->item(0)->nodeValue;
				$li_i++;	  
			  }
	  return $lr_datos;
	}	

	function uf_cargar_detalles_desembolso($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco_scg
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //      Descripción: Devuelve mediante un arreglo los detalles contables del movimiento generado por la cobranza o
	  //                   recuperación del credito
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$detalles = $io_docxml->getElementsByTagName("LIQUIDETALLE");
		
		if ($detalles)
		   { 
		     foreach ($detalles as $detalle)
			         {  
				       $registros = $detalle->getElementsByTagName('dato');
				       foreach ($registros as $registro)
				               {					
								  $ced_benes = $registro->getElementsByTagName("ced_bene");
								  $lr_datos[$li_i]['ced_bene'] = $ced_benes->item(0)->nodeValue;
								  
								  $numsols = $registro->getElementsByTagName("numsol");
								  $lr_datos[$li_i]['numsol'] = $numsols->item(0)->nodeValue;
								  
								  $nombenealternos = $registro->getElementsByTagName("nombenealterno");
								  $lr_datos[$li_i]['nombenalt'] = $nombenealternos->item(0)->nodeValue;
								  
								  $montos = $registro->getElementsByTagName("monto");
								  $lr_datos[$li_i]['mondet'] = $montos->item(0)->nodeValue;
								  
							      $li_i++;
				               }
		             } 
		   }
		return $lr_datos;
	}
	
	function uf_cargar_liquidaciones($as_filnam)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_liquidaciones
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo los datos ascciados a la liquidación del Crédito 
		//	   Creado Por: Ing. Néstor Falcón
		//   Fecha Creación: 23/07/2008 							Fecha Última Modificación : 23/07/2008.
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		  $li_i = 1;
		  $io_docxml = new DOMDocument();
		  $io_docxml->load($as_filnam);
		  $registros = $io_docxml->getElementsByTagName("SCB_MOVBCO");
						
		  foreach ($registros as $registro)
				  {
					$io_cedben = $registro->getElementsByTagName("ced_bene");
					$lr_datos[$li_i]['ced_bene'] = $io_cedben->item(0)->nodeValue;
					
					$io_nombenalt = $registro->getElementsByTagName("nombenealterno");
					$lr_datos[$li_i]['nombenalt'] = $io_nombenalt->item(0)->nodeValue;
							  
					$io_codban = $registro->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban'] = $io_codban->item(0)->nodeValue;
							  
					$io_ctaban = $registro->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban'] = $io_ctaban->item(0)->nodeValue;
	
					$io_numdoc = $registro->getElementsByTagName("numdoc");
					$lr_datos[$li_i]['numdoc'] = $io_numdoc->item(0)->nodeValue;

					$io_monto = $registro->getElementsByTagName("monto");
					$lr_datos[$li_i]['monto'] = $io_monto->item(0)->nodeValue;
	
					$io_fecmov = $registro->getElementsByTagName("fecmov");
					$lr_datos[$li_i]['fecmov'] = $io_fecmov->item(0)->nodeValue;
					
					$io_conmov = $registro->getElementsByTagName("conmov");
					$lr_datos[$li_i]['conmov'] = $io_conmov->item(0)->nodeValue;
					
					$io_docnum = $registro->getElementsByTagName("documento");
					$lr_datos[$li_i]['documento'] = $io_docnum->item(0)->nodeValue;

					$io_codope = $registro->getElementsByTagName("codope");
					$lr_datos[$li_i]['codope'] = $io_codope->item(0)->nodeValue;
					$li_i++;	  
				  }
		  return $lr_datos;
	}
	
	function uf_cargar_detalles_spg($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco_scg
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //      Descripción: Devuelve mediante un arreglo los detalles contables del movimiento generado por la cobranza o
	  //                   recuperación del credito
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 25/07/2007 							Fecha Última Modificación : 25/07/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$detalles = $io_docxml->getElementsByTagName("SCB_MOVBCO_SPG");
		
		if ($detalles)
		   { 
		     foreach ($detalles as $detalle)
			         { 
				       $registros = $detalle->getElementsByTagName('dato');
				       foreach ($registros as $registro)
				               {					
								  $io_spgcta = $registro->getElementsByTagName("spg_cuenta");
								  $lr_datos[$li_i]['spgcta'] = $io_spgcta->item(0)->nodeValue;
								  
								  $io_codestpro1 = $registro->getElementsByTagName("codestpro1");
								  $lr_datos[$li_i]['codestpro1'] = $io_codestpro1->item(0)->nodeValue;
								  
								  $io_codestpro2 = $registro->getElementsByTagName("codestpro2");
								  $lr_datos[$li_i]['codestpro2'] = $io_codestpro2->item(0)->nodeValue;

								  $io_codestpro3 = $registro->getElementsByTagName("codestpro3");
								  $lr_datos[$li_i]['codestpro3'] = $io_codestpro3->item(0)->nodeValue;

								  $io_codestpro4 = $registro->getElementsByTagName("codestpro4");
								  $lr_datos[$li_i]['codestpro4'] = $io_codestpro4->item(0)->nodeValue;

								  $io_codestpro5 = $registro->getElementsByTagName("codestpro5");
								  $lr_datos[$li_i]['codestpro5'] = $io_codestpro5->item(0)->nodeValue;

								  $io_estcla = $registro->getElementsByTagName("estcla");
								  $lr_datos[$li_i]['estcla'] = $io_estcla->item(0)->nodeValue;

								  $io_monto = $registro->getElementsByTagName("monto");
								  $lr_datos[$li_i]['monto'] = $io_monto->item(0)->nodeValue;
								  
							      $li_i++;
				               }
		             } 
		   }
		return $lr_datos;
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////
// V Etapa de Integración.
// Creación del Movimiento Bancario con sus detalles Contables y/o Presupuestarios de Ingresos.
///////////////////////////////////////////////////////////////////////////////////////////////

	function uf_cargar_scb_movbco($as_filnam,$as_tipcar)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //				   $as_tipcar = Tipo de la Carga del Movimiento, P = Primary Key o C = Carga completa del Movimiento.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //	  Description: Devuelve mediante un arreglo la cabecera del movimiento bancario generado por la cobranza o 
	  //                   recuperación del crédito.
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  $li_i = 1;
	  $io_docxml = new DOMDocument();
	  $io_docxml->load($as_filnam);
	  $registros = $io_docxml->getElementsByTagName("SCB_MOVBCO");
					
	  foreach ($registros as $registro)
			  {
				$io_codemp = $registro->getElementsByTagName("codemp");
				$lr_datos[$li_i]['codemp'] = $io_codemp->item(0)->nodeValue;
						  
				$io_codban = $registro->getElementsByTagName("codban");
				$lr_datos[$li_i]['codban'] = $io_codban->item(0)->nodeValue;
						  
				$io_ctaban = $registro->getElementsByTagName("ctaban");
				$lr_datos[$li_i]['ctaban'] = $io_ctaban->item(0)->nodeValue;
						  
				$io_numdoc = $registro->getElementsByTagName("numdoc");
				$lr_datos[$li_i]['numdoc'] = $io_numdoc->item(0)->nodeValue; 
						  
				$io_codope = $registro->getElementsByTagName("codope");
				$lr_datos[$li_i]['codope'] = $io_codope->item(0)->nodeValue;
						  
				if ($as_tipcar=="C")
				   {
					 $io_cedben = $registro->getElementsByTagName("ced_bene");
					 $lr_datos[$li_i]['ced_bene'] = $io_cedben->item(0)->nodeValue;
					 
					 $io_conmov = $registro->getElementsByTagName("conmov");
				     $lr_datos[$li_i]['conmov'] = $io_conmov->item(0)->nodeValue;
					 
					 $io_fecmov = $registro->getElementsByTagName("fecmov");
					 $lr_datos[$li_i]['fecmov'] = $io_fecmov->item(0)->nodeValue;
						  
					 $io_nomproben = $registro->getElementsByTagName("nomproben");
					 $lr_datos[$li_i]['nomproben'] = $io_nomproben->item(0)->nodeValue;
							  
					 $io_monto = $registro->getElementsByTagName("monto");
					 $lr_datos[$li_i]['monto'] = $io_monto->item(0)->nodeValue;
				   }
				$li_i++;	  
			  }
	  return $lr_datos;
	}	

	function uf_cargar_scb_movbco_scg($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco_scg
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //      Descripción: Devuelve mediante un arreglo los detalles contables del movimiento generado por la cobranza o
	  //                   recuperación del credito
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$detalles = $io_docxml->getElementsByTagName("SCB_MOVBCO_SCG");
		
		if ($detalles)
		   { 
		     foreach ($detalles as $detalle)
			         {  
				       $registros = $detalle->getElementsByTagName('dato');
				       foreach ($registros as $registro)
				               {					
								  $codemps = $registro->getElementsByTagName("codemp");
								  $lr_datos[$li_i]['codemp'] = $codemps->item(0)->nodeValue;
								  
								  $codbans = $registro->getElementsByTagName("codban");
								  $lr_datos[$li_i]['codban'] = $codbans->item(0)->nodeValue;
								  
								  $ctabans = $registro->getElementsByTagName("ctaban");
								  $lr_datos[$li_i]['ctaban'] = $ctabans->item(0)->nodeValue;
								  
								  $numdocs = $registro->getElementsByTagName("numdoc");
								  $lr_datos[$li_i]['numdoc'] = $numdocs->item(0)->nodeValue;
								  
								  $codopes = $registro->getElementsByTagName("codope");
								  $lr_datos[$li_i]['codope'] = $codopes->item(0)->nodeValue;
								  
								  $estmovs = $registro->getElementsByTagName("estmov");
								  $lr_datos[$li_i]['estmov'] = $estmovs->item(0)->nodeValue;
				
								  $sgc_cuentas = $registro->getElementsByTagName("scg_cuenta");
								  $lr_datos[$li_i]['scg_cuenta'] = $sgc_cuentas->item(0)->nodeValue;
								  
								  $debhabs = $registro->getElementsByTagName("debhab");
								  $lr_datos[$li_i]['debhab'] = $debhabs->item(0)->nodeValue;
								  
								  $desmovs = $registro->getElementsByTagName("desmov");
								  $lr_datos[$li_i]['desmov'] = $desmovs->item(0)->nodeValue;
								  
								  $montos = $registro->getElementsByTagName("monto");
								  $lr_datos[$li_i]['monto'] = $montos->item(0)->nodeValue;
								  $li_i++;
				               }
		             } 
		   }
		return $lr_datos;
	}

	function uf_load_contable_liquidacion($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco_scg
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //      Descripción: Devuelve mediante un arreglo los detalles contables del movimiento generado por la cobranza o
	  //                   recuperación del credito
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$detalles = $io_docxml->getElementsByTagName("SCB_MOVBCO_SCG");
		
		if ($detalles)
		   { 
		     foreach ($detalles as $detalle)
			         {  
				       $registros = $detalle->getElementsByTagName('dato');
				       foreach ($registros as $registro)
				               {					
								  $sgc_cuentas = $registro->getElementsByTagName("scg_cuenta");
								  $lr_datos[$li_i]['scg_cuenta'] = $sgc_cuentas->item(0)->nodeValue;
								  
								  $montos = $registro->getElementsByTagName("monto");
								  $lr_datos[$li_i]['monto'] = $montos->item(0)->nodeValue;
								  $li_i++;
				               }
		             } 
		   }
		return $lr_datos;
	}

	function uf_cargar_scb_movbco_spi($as_filnam)
	{
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  //	     Function: uf_cargar_scb_movbco_spi
	  //		   Access: private
	  //	    Arguments: $as_filnam = Archivo xml leido.
	  //	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
	  //      Descripción: Devuelve mediante un arreglo los detalles de ingreso del movimiento generado por la cobranza o
	  //                   recuperación del credito
	  //	   Creado Por: Ing. Nestor Falcón.
	  //   Fecha Creación: 02/06/2007 							Fecha Última Modificación : 03/06/2007
	  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  
	  $li_i = 1;
	  $io_docxml = new DOMDocument();
	  $io_docxml->load($as_filnam);
	  $detalles = $io_docxml->getElementsByTagName("SCB_MOVBCO_SPI");
		
		if ($detalles)
		   { 
		     foreach ($detalles as $detalle)
			         {  
				       $registros = $detalle->getElementsByTagName('dato');
				       foreach ($registros as $registro)
				               {					
								 $codemps = $registro->getElementsByTagName("codemp");
								 $lr_datos[$li_i]['codemp'] = $codemps->item(0)->nodeValue;
							  
								 $codbans = $registro->getElementsByTagName("codban");
								 $lr_datos[$li_i]['codban'] = $codbans->item(0)->nodeValue;
							  
								 $ctabans = $registro->getElementsByTagName("ctaban");
								 $lr_datos[$li_i]['ctaban'] = $ctabans->item(0)->nodeValue;
							  
								 $numdocs = $registro->getElementsByTagName("numdoc");
								 $lr_datos[$li_i]['numdoc'] = $numdocs->item(0)->nodeValue;
							  
								 $codopes = $registro->getElementsByTagName("codope");
								 $lr_datos[$li_i]['codope'] = $codopes->item(0)->nodeValue;
									  
								 $estmovs = $registro->getElementsByTagName("estmov");
								 $lr_datos[$li_i]['estmov'] = $estmovs->item(0)->nodeValue;

								 $sgc_cuentas = $registro->getElementsByTagName("spi_cuenta");
								 $lr_datos[$li_i]['spi_cuenta'] = $sgc_cuentas->item(0)->nodeValue;
							  
								 $desmovs = $registro->getElementsByTagName("desmov");
								 $lr_datos[$li_i]['desmov'] = $desmovs->item(0)->nodeValue;
							  
								 $operacion = $registro->getElementsByTagName("operacion");
								 $lr_datos[$li_i]['operacion'] = $operacion->item(0)->nodeValue;
							  
								 $montos = $registro->getElementsByTagName("monto");
								 $lr_datos[$li_i]['monto'] = $montos->item(0)->nodeValue;
								 $li_i++;
							   }
	                 }
	        }
	 return $lr_datos;
	}


//////////////////////////////////////////////////////////////////////
//Carga de Datos desde los archivos xml para las Cuentas por Cobrar //
/////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////////////////////////
	// I Etapa de Integración.
	// Creación del Devengado
	///////////////////////////////////////////////////////////////////////////////////////////////
	function uf_cargar_spi_dt_cmp($as_filnam,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_spi_dt_cmp
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el detalle para los ingreso
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 16/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SPI_DT_CMP');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$comprobantes = $registro->getElementsByTagName('dato');
				foreach ($comprobantes as $comprobante)
				{
					$io_campo = $registro->getElementsByTagName("codemp");
					$lr_datos[$li_i]['codemp']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("procede");
					$lr_datos[$li_i]['procede']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("comprobante");
					$lr_datos[$li_i]['comprobante']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("fecha");
					$lr_datos[$li_i]['fecha']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban']= $io_campo->item(0)->nodeValue;
		
					if(($lr_datos[$li_i]['codemp']==$as_codemp)&&
					   ($lr_datos[$li_i]['procede']==$as_procede)&&
					   ($lr_datos[$li_i]['comprobante']==$as_comprobante)&&
					   ($lr_datos[$li_i]['fecha']==$as_fecha)&&
					   ($lr_datos[$li_i]['codban']==$as_codban)&&
					   ($lr_datos[$li_i]['ctaban']==$as_ctaban))
					{
						$io_campo = $registro->getElementsByTagName("spi_cuenta");
						$lr_datos[$li_i]['spi_cuenta']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("procede_doc");
						$lr_datos[$li_i]['procede_doc']= $io_campo->item(0)->nodeValue;
		
						$io_campo = $registro->getElementsByTagName("documento");
						$lr_datos[$li_i]['documento']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("operacion");
						$lr_datos[$li_i]['operacion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("descripcion");
						$lr_datos[$li_i]['descripcion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("monto");
						$lr_datos[$li_i]['monto']= $io_campo->item(0)->nodeValue;
					
						$li_i++;	  
					}
					else
					{
						$lr_datos[$li_i]['spi_cuenta']= "";
						$lr_datos[$li_i]['procede_doc']= "";
						$lr_datos[$li_i]['documento']= "";
						$lr_datos[$li_i]['operacion']= "";
						$lr_datos[$li_i]['descripcion']= "";
						$lr_datos[$li_i]['monto']= "";
					}
				}	
			}		
		}
		return $lr_datos;
	}	

	function uf_cargar_scg_dt_cmp($as_filnam,$as_codemp,$as_procede,$as_comprobante,$as_fecha,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cargar_scg_dt_cmp
		//		   Access: private
		//	    Arguments: $as_filnam = Archivo xml leido.
		//	      Returns: $lr_datos  = Arreglo cargado con la información leida desde el archivo xml.
		//	  Description: Devuelve mediante un arreglo el detalle para los detalles contables
		//	   Creado Por: Ing. Yesenia Moreno de Lang
		//   Fecha Creación: 16/07/2008 							Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_i = 1;
		$lr_datos="";
		$io_docxml = new DOMDocument();
		$io_docxml->load($as_filnam);
		$registros = $io_docxml->getElementsByTagName('SCG_DT_CMP');
		if($registros)
		{ 
			foreach ($registros as $registro)
			{
				$comprobantes = $registro->getElementsByTagName('dato');
				foreach ($comprobantes as $comprobante)
				{
					$io_campo = $registro->getElementsByTagName("codemp");
					$lr_datos[$li_i]['codemp']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("procede");
					$lr_datos[$li_i]['procede']= $io_campo->item(0)->nodeValue;
		
					$io_campo = $registro->getElementsByTagName("comprobante");
					$lr_datos[$li_i]['comprobante']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("fecha");
					$lr_datos[$li_i]['fecha']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("codban");
					$lr_datos[$li_i]['codban']= $io_campo->item(0)->nodeValue;
					
					$io_campo = $registro->getElementsByTagName("ctaban");
					$lr_datos[$li_i]['ctaban']= $io_campo->item(0)->nodeValue;
		
					if(($lr_datos[$li_i]['codemp']==$as_codemp)&&
					   ($lr_datos[$li_i]['procede']==$as_procede)&&
					   ($lr_datos[$li_i]['comprobante']==$as_comprobante)&&
					   ($lr_datos[$li_i]['fecha']==$as_fecha)&&
					   ($lr_datos[$li_i]['codban']==$as_codban)&&
					   ($lr_datos[$li_i]['ctaban']==$as_ctaban))
					{
						$io_campo = $registro->getElementsByTagName("sc_cuenta");
						$lr_datos[$li_i]['sc_cuenta']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("procede_doc");
						$lr_datos[$li_i]['procede_doc']= $io_campo->item(0)->nodeValue;
		
						$io_campo = $registro->getElementsByTagName("documento");
						$lr_datos[$li_i]['documento']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("operacion");
						$lr_datos[$li_i]['operacion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("descripcion");
						$lr_datos[$li_i]['descripcion']= $io_campo->item(0)->nodeValue;
						
						$io_campo = $registro->getElementsByTagName("monto");
						$lr_datos[$li_i]['monto']= $io_campo->item(0)->nodeValue;
					
						$li_i++;	  
					}
					else
					{
						$lr_datos[$li_i]['sc_cuenta']= "";
						$lr_datos[$li_i]['procede_doc']= "";
						$lr_datos[$li_i]['documento']= "";
						$lr_datos[$li_i]['operacion']= "";
						$lr_datos[$li_i]['descripcion']= "";
						$lr_datos[$li_i]['monto']= "";
					}
				}	
			}		
		}
		return $lr_datos;
	}
}
?>
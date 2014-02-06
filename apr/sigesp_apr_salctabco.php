<?PHP
class sigesp_apr_salctabco
{
	var $io_sql_origen;
	var $io_sql_destino;
	var $io_mensajes;
	var $io_funciones;
	var $io_validacion;
	var $is_msg_error;
	var	$lo_archivo;
		
	    function sigesp_apr_salctabco()
		{
		  require_once("class_folder/class_validacion.php");
		  require_once("../shared/class_folder/class_fecha.php");
		  require_once("../shared/class_folder/class_funciones.php");
		  require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");
		  
		  $this->ls_database_source = $_SESSION["ls_database"];
	      $this->ls_database_target = $_SESSION["ls_data_des"];
		  $this->io_mensajes        = new class_mensajes();		
		  $this->io_funciones       = new class_funciones();
		  $this->io_validacion      = new class_validacion();
		  $this->io_fecha           = new class_fecha();
		  $this->io_rcbsf		    = new sigesp_c_reconvertir_monedabsf();
		  $io_conect			    = new sigesp_include();
		  $io_conexion_origen       = $io_conect->uf_conectar();
		  $io_conexion_destino      = $io_conect->uf_conectar($this->ls_database_target);
		  $this->io_sql_origen      = new class_sql($io_conexion_origen);
	      $this->io_sql_destino     = new class_sql($io_conexion_destino);
		  $ld_fecha                 = date("_d-m-Y");
		  $ls_nombrearchivo         = "resultado/".trim($_SESSION["la_empresa"]["sigemp"])."_saldos_banco_result_".$ld_fecha.".txt";
		  $this->lo_archivo         = @fopen("$ls_nombrearchivo","a+");
		  $this->dat                = $_SESSION["la_empresa"];
		}
	
function uf_select_banco($as_codemp)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_banco
//	Access       public
//	Arguments    $as_codemp
//	Returns	     rs_data. Retorna una resulset
//	Description  Devuelve un resulset con todos los bancos registrados para dicho 
//              codigo de empresa.
//////////////////////////////////////////////////////////////////////////////

   $lb_valido = true;
   $ls_sql    = "SELECT codban,nomban FROM scb_banco WHERE codemp='".$as_codemp."'ORDER BY nomban ASC ";
   $rs_data   = $this->io_sql_origen->select($ls_sql);
   if ($rs_data===false)
	  {
		$lb_valido = false;
		print $this->io_sql_origen->message;
	  }
   else
	  {
		$li_numrows = $this->io_sql_origen->num_rows($rs_data);	   
		if ($li_numrows>0)
		   {  
			 $lb_valido = true;
		   }
	  }
   return $rs_data;         
}
		
function uf_select_cuentas($as_codemp,$as_codban)
{
//////////////////////////////////////////////////////////////////////////////
//	Funcion      uf_select_tipo_cuenta
//	Access       public
//	Description  Devuelve un resulset con todos los tipos de 
//              cuentas 
//////////////////////////////////////////////////////////////////////////////

   $ls_sql=" SELECT * FROM scb_ctabanco WHERE codemp='".$as_codemp."' AND codban='".$as_codban."' ORDER BY ctaban";
   $rs_data   = $this->io_sql_origen->select($ls_sql);
   if ($rs_data===false)
	  {
		$lb_valido = false;
		print $this->io_sql_origen->message;
	  }
   else
	  {
		$li_numrows = $this->io_sql_origen->num_rows($rs_data);	   
		if ($li_numrows>0)
		   {  
			 $lb_valido = true;
		   }
	  }
   return $rs_data;         
}	
		
function uf_calcular_saldo_colocacion($as_codban,$as_numcol) 
{
	/*------------------------------------------------------------------
		- Funcion que calcula el saldo de las colocaciones
		- Retorna el saldo si se ejecuto correctamente, de lo contrario
		  retorna falso.
		- Elaborado por Ing. Laura Cabré.
		- Fecha: 12/01/2007			
	//-----------------------------------------------------------------*/
	$lb_valido=true;
	//Calculando el monto de los Creditos positivos (no anulados)
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT COALESCE(SUM(monmovcol),0) AS monto
			   FROM scb_movcol
			  WHERE codemp='$ls_codemp' 
			    AND codban='$as_codban' 
				AND numcol='$as_numcol' 
				AND (codope='CH' OR codope='ND' OR codope='RE')	
				AND estcol<>'A'";
	$io_recordset=$this->io_sql_origen->select($ls_sql);
	if(($io_recordset===false))
	{
		$lb_valido=false;
		$ls_cadena="Error al Calcular el saldo de las colocaciones. (Creditos no anulados)\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		if($row=$this->io_sql_origen->fetch_row($io_recordset))
		{
			$ldec_creditostmp=$row["monto"];
			$this->io_sql_origen->free_result($io_recordset);
			$lb_valido=true;					
		}			
	}
	if($lb_valido)//  Calculando el monto de los Creditos negativos (anulados)
	{
		$ls_sql="SELECT COALESCE(SUM(monmovcol),0) AS monto
				   FROM scb_movcol
				  WHERE codemp='$ls_codemp' 
				    AND codban='$as_codban' 
					AND numcol='$as_numcol' 
					AND (codope='CH' OR codope='ND' OR codope='RE') 
					AND estcol='A'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if(($io_recordset===false))
		{
			$lb_valido=false;
			$ls_cadena="Error al Calcular el saldo de las colocaciones. (Creditos anulados)\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			if($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ldec_creditos_negativostmp=$row["monto"];
				$this->io_sql_origen->free_result($io_recordset);
				$lb_valido=true;					
			}
		}				
	}
	if($lb_valido)//  Calculando el monto de los Debitos positivos (no anulados)
	{
		$ls_sql="SELECT COALESCE(SUM(monmovcol),0) AS monto
				FROM ".$this->ls_database_source.".scb_movcol
				WHERE codemp='$ls_codemp' AND codban='$as_codban' AND numcol='$as_numcol'
				AND (codope='DP' OR codope='NC') AND estcol<>'A'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if(($io_recordset===false))
		{
			$lb_valido=false;
			$ls_cadena="Error al Calcular el saldo de las colocaciones (Debitos no anulados).\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			if($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ldec_debitostmp=$row["monto"];
				$this->io_sql_origen->free_result($io_recordset);
				$lb_valido=true;					
			}				
		}				
	}
	if($lb_valido)//  Calculando el monto de los Debitos negativos (anulados)
	{
		$ls_sql="SELECT COALESCE(SUM(monmovcol),0) AS monto
				   FROM scb_movcol
				  WHERE codemp='$ls_codemp' 
				    AND codban='$as_codban' 
					AND numcol='$as_numcol'
				    AND (codope='DP' OR codope='NC') 
					AND estcol='A'";
		$io_recordset=$this->io_sql_origen->select($ls_sql);
		if(($io_recordset===false))
		{
			$lb_valido=false;
			$ls_cadena="Error al Calcular el saldo de las colocaciones (Debitos anulados).\r\n".$this->io_sql_origen->message."\r\n";
			$ls_cadena=$ls_cadena.$ls_sql."\r\n";
			if ($this->lo_archivo)			
			{
				@fwrite($this->lo_archivo,$ls_cadena);
			}
		}
		else
		{
			if($row=$this->io_sql_origen->fetch_row($io_recordset))
			{
				$ldec_debitos_negativostmp=$row["monto"];
				$this->io_sql_origen->free_result($io_recordset);
				$lb_valido=true;					
			}				
		}				
	}
	if($lb_valido)
	{
		$ldec_debitos  = $ldec_debitostmp  - $ldec_debitos_negativostmp;
		$ldec_creditos = $ldec_creditostmp - $ldec_creditos_negativostmp;
		$ldec_saldo    = $ldec_creditos    - $ldec_debitos; 
		return ($ldec_debitos - $ldec_creditos);
	}
	else
	{
		return $lb_valido;
	}			
}
		
function uf_calcular_saldo_documento($as_codban,$as_ctaban)
{
	/*------------------------------------------------------------------
		- Funcion que calcula el saldo de los documentos
		- Retorna el saldo si se ejecuto correctamente, de lo contrario
		  retorna falso.
		- Elaborado por Ing. Laura Cabré.
		- Fecha: 12/01/2007			
	//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$lb_valido=true;
	$ls_sql="SELECT codope AS operacion, (monto-monret) AS monto, estmov AS estado
			   FROM scb_movbco
			  WHERE codemp='".$ls_codemp."' 
				AND codban='".$as_codban."' 
				AND ctaban='".$as_ctaban."'";
	
	$io_recordset=$this->io_sql_origen->select($ls_sql);
	if(($io_recordset===false))
	{
		$lb_valido=false;
		$ls_cadena="Error al Calcular el saldo de los documentos (Debitos anulados).\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
	}
	else
	{
		$ldec_debitostmp=0;
		$ldec_creditostmp=0;
		$ldec_debitos_negativostmp=0;
		$ldec_creditos_negativostmp=0;
		$li_numrows = $this->io_sql_origen->num_rows($io_recordset);
		if ($li_numrows>0)
		   {
			 while($row=$this->io_sql_origen->fetch_row($io_recordset))
				  {
					$ls_operacion = $row["operacion"];
					$ls_estado    = $row["estado"];
					$ldec_monto   = $row["monto"];
					if((($ls_operacion=="CH") || ($ls_operacion=="ND") || ($ls_operacion=="RE")) && ($ls_estado!="A"))
					  {	
						$ldec_creditostmp+=$ldec_monto;
					  }	
					elseif((($ls_operacion=="CH") || ($ls_operacion=="ND") || ($ls_operacion=="RE")) && ($ls_estado=="A"))
					  {	
						$ldec_creditos_negativostmp+=$ldec_monto;
					  } 	
					elseif((($ls_operacion=="DP") || ($ls_operacion=="NC")) && ($ls_estado!="A"))
					  {	
						$ldec_debitostmp+=$ldec_monto;
					  }
					elseif((($ls_operacion=="DP") || ($ls_operacion=="NC")) && ($ls_estado=="A"))
					  {
						$ldec_debitos_negativostmp+=$ldec_monto;
					  }				
				  }				 
		   }
	}
 if ($lb_valido)
	{
	  $ldec_debitos  = $ldec_debitostmp  - $ldec_debitos_negativostmp;
	  $ldec_creditos = $ldec_creditostmp - $ldec_creditos_negativostmp;
	  $ldec_saldo    = number_format($ldec_debitos,2,".","") - number_format($ldec_creditos,2,".","");				
	  return $ldec_saldo;
	}
 else
	{
	  return $lb_valido;
	}
}
		
function uf_verificar_apertura($as_numdoc, $as_codban, $as_ctaban) 
{
/*------------------------------------------------------------------
	- Funcion que determina si ya se realizo la apertura
	- Retorna 0 si no se ha llevado a cabo la apertura, de lo contrario retorna 1,
	  si ocurre error retorna 0.	
	- Elaborado por Ing. Laura Cabré.
	- Fecha: 12/01/2007			
//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT COUNT(numdoc) as cantidad
			   FROM scb_movbco
		 	  WHERE codemp='$ls_codemp' 
			    AND codban='$as_codban' 
				AND ctaban='$as_ctaban' 
			    AND numdoc='$as_numdoc'";
	$io_recordset=$this->io_sql_destino->select($ls_sql);
	if(($io_recordset===false))
	{
		
		$ls_cadena="Error al Verificar Apertura.\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		//print $ls_cadena;
		return false;
	}
	else
	{
		if($row=$this->io_sql_destino->fetch_row($io_recordset))
		{
			if($row["cantidad"]>0)
				return 1;// Indica que ya existe la apertura
			else
				return 0;// Indica que no existe la apertura	  
		}
	}		
}
		
function uf_verificar_colocacion($as_codban, $as_ctaban,$as_numcol)
{
 /*------------------------------------------------------------------
	- Funcion que determina si existen colocaciones
	- Retorna 0 si no hay colocaciones, de lo contrario retorna 1,
	  si ocurre error retorna 0.	
	- Elaborado por Ing. Laura Cabré.
	- Fecha: 12/01/2007			
//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$ls_sql = "SELECT COUNT(numcol) AS cantidad
			     FROM scb_colocacion
			    WHERE ctaban='$as_ctaban' 
				  AND codban='$as_codban' 
				  AND codemp='$ls_codemp' 
				  AND numcol='$as_numcol'";
	$io_recordset=$this->io_sql_destino->select($ls_sql);
	if(($io_recordset===false))
	{
	
		$ls_cadena="Error al Verificar Colocación.\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		//print $ls_cadena;
		return false;
	}
	else
	{
		if($row=$this->io_sql_destino->fetch_row($io_recordset))
		{
			if($row["cantidad"]>0)
				return 1;// Indica que ya existe la apertura
			else
				return 0;// Indica que no existe la apertura	  
		}
	}

}
		
function uf_verificar_cuenta($as_codban, $as_ctaban)
{
 /*------------------------------------------------------------------
	- Funcion que determina si existen la cuenta
	- Retorna 0 si no existe, de lo contrario retorna 1,
	  si ocurre error retorna 0.	
	- Elaborado por Ing. Laura Cabré.
	- Fecha: 12/01/2007			
//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT ctaban FROM scb_ctabanco WHERE codemp='$ls_codemp' AND codban='$as_codban' AND ctaban='$as_ctaban'";
	$io_recordset=$this->io_sql_destino->select($ls_sql);
	if(($io_recordset===false))
	{
		
		$ls_cadena="Error al Verificar Cuenta.\r\n".$this->io_sql_destino->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		//print $ls_cadena;
		return false;
	}
	else
	{
		if($this->io_sql_destino->fetch_row($io_recordset))
		{
			return 1;// Indica que ya existe la apertura					
		}
		else
		{
			return 0;// Indica que no existe la apertura	  
		}
	}
}
		
function uf_verificar_documento($as_codban, $as_ctaban,$as_numdoc,$as_codope)
{
	 /*------------------------------------------------------------------
		- Funcion que determina si existen el documento
		- Retorna 0 si no existe, de lo contrario retorna 1,
		  si ocurre error retorna 0.	
		- Elaborado por Ing. Laura Cabré.
		- Fecha: 12/01/2007			
	//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT numdoc 
	           FROM scb_movbco 
			  WHERE codemp='$ls_codemp' 
			    AND codban='$as_codban' 
				AND ctaban='$as_ctaban' 
		 	    AND numdoc='$as_numdoc' 
				AND codope='$as_codope'";
	$io_recordset=$this->io_sql_destino->select($ls_sql);
	if(($io_recordset===false))
	{
		
		$ls_cadena="Error al Verificar Documento.\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		return false;
	}
	else
	{
		if($this->io_sql_destino->fetch_row($io_recordset))
		{
			return 1;// Indica que ya existe la apertura					
		}
		else
		{
			return 0;// Indica que no existe la apertura	  
		}
	}
}

function uf_verificar_movimiento_colocacion($as_codban, $as_ctaban,$as_numcol,$as_numdoc,$as_codope,$as_estcol)
{
 /*------------------------------------------------------------------
	- Funcion que determina si existen el movimiento de colocacion en la nueva BD
	- Retorna 0 si no existe, de lo contrario retorna 1,
	  si ocurre error retorna 0.	
	- Elaborado por Ing. Laura Cabré.
	- Fecha: 16/01/2007			
//-----------------------------------------------------------------*/
	$ls_codemp=$this->dat["codemp"];
	$ls_sql="SELECT numcol 
			   FROM scb_movcol
			  WHERE codemp='$ls_codemp' 
			    AND codban='$as_codban' 
				AND ctaban='$as_ctaban' 
		 	    AND numcol='$as_numcol' 
				AND numdoc='$as_numdoc' 
				AND codope='$as_codope'
			    AND estcol='$as_estcol'";
	$io_recordset=$this->io_sql_destino->select($ls_sql);
	if(($io_recordset===false))
	{
		
		$ls_cadena="Error al Verificar Movimiento de Colocación.\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		return false;
	}
	else
	{
		if($this->io_sql_destino->fetch_row($io_recordset))
		{
			return 1;// Indica que ya existe la apertura					
		}
		else
		{
			return 0;// Indica que no existe la apertura	  
		}
	}
}
		
function uf_trasladar_saldos($ad_periodoviejo,$ad_periodonuevo,$as_codban,$as_ctaban,$ab_transito)
{
	 /*------------------------------------------------------------------
		- Funcion que se encarga de trasladar los saldos
		- Retorna true si la operacion se ejecuto correctamente, de lo contrario, false
		- Elaborado por Ing. Laura Cabré.
		- Fecha: 12/01/2007			
	//-----------------------------------------------------------------*/

	set_time_limit(0);
	$ls_codemp=$this->dat["codemp"];
	$ds_cuenta = new class_datastore();
	$ds_colocaciones = new class_datastore();
	$ds_transito = new class_datastore();
	$ds_doc_repetidos = new class_datastore();
	$lb_valido=false;
	$ad_periodoviejo=$this->io_funciones->uf_convertirdatetobd($ad_periodoviejo);
	$ad_periodonuevo=$this->io_funciones->uf_convertirdatetobd($ad_periodonuevo);
	
	// -----------------Se obtiene la cuenta bancaria

	$ls_sql="SELECT 0.0000  AS saldo
			   FROM scb_ctabanco
			  WHERE codemp='$ls_codemp' 
				AND codban='$as_codban' 
				AND ctaban='$as_ctaban'
			  GROUP BY codban,ctaban,codtipcta
			  ORDER BY codban,ctaban";
	
	$io_recordset=$this->io_sql_origen->select($ls_sql);
	if(($io_recordset===false))
	{
		
		$ls_cadena="Error al trasladar saldos - Obtención de la cuenta bancaria- .\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		return false;
	}
	else
	{
		if($this->io_sql_origen->fetch_row($io_recordset))
		{
			$ds_cuenta->data=$this->io_sql_origen->obtener_datos($io_recordset);					
			$lb_valido=true;								
		}				
	}
	// -----------------Se obtiene las colocaciones------------
	$ls_sql="SELECT numcol,codtipcol,codban,ctaban, 0.0000  AS saldo
			   FROM scb_colocacion
			  GROUP by codban,ctaban,numcol,codtipcol";
	$io_recordset=$this->io_sql_origen->select($ls_sql);
	if(($io_recordset===false))
	{
		$ls_cadena="Error al trasladar saldos - Obtención de las colocaciones- .\r\n".$this->io_sql_origen->message."\r\n";
		$ls_cadena=$ls_cadena.$ls_sql."\r\n";
		if ($this->lo_archivo)			
		{
			@fwrite($this->lo_archivo,$ls_cadena);
		}
		return false;
	}
	else
	{
		if($this->io_sql_origen->fetch_row($io_recordset))
		{
			$ds_colocaciones->data=$this->io_sql_origen->obtener_datos($io_recordset);
			$lb_valido=true;								
		}	
	}
	
	if($lb_valido)
	{	
		//-------------Calculando los saldos de los documentos 
	$ldec_saldo_documentos_temp=0;
		$ldec_saldo_documentos_temp=$this->uf_calcular_saldo_documento($as_codban,$as_ctaban);
		if($ldec_saldo_documentos_temp===false)
			return false;					
		$ds_cuenta->updateRow("saldo",$ldec_saldo_documentos_temp,1);
		
		//-------------Calculando los saldos de las colocaciones-----------//
		$li_totcol = $ds_colocaciones->getRowCount("numcol");
		for($li_i=1;$li_i<=$li_totcol;$li_i++)
		{
			$ls_codban=$ds_colocaciones->getValue("codban",$li_i);
			$ls_numcol=$ds_colocaciones->getValue("numcol",$li_i);
			$ls_ctaban=$ds_colocaciones->getValue("ctaban",$li_i);
			$ldec_saldo=$this->uf_calcular_saldo_colocacion($ls_codban,$ls_numcol);
			
			if($ldec_saldo===false)
				return false;
			else
				$ds_colocaciones->updateRow("saldo",$ldec_saldo,$li_i);				
				
		}			
		
		//-------------Se chequean los movimientos en transito
		
			if($ab_transito)//Si esta tildada la opcion de cheques en transito
			{
				$ls_sql="SELECT *
						   FROM scb_movbco 
						  WHERE codemp='$ls_codemp' 
							AND codban='$as_codban' 
							AND ctaban='$as_ctaban' 
							AND estcon=0 
							AND (estmov='C' OR estmov='L')
						  ORDER BY codban,ctaban,numdoc";
				$io_recordset=$this->io_sql_origen->select($ls_sql);
				if(($io_recordset===false))
				{
					
					$ls_cadena="Error al Trasladar saldos - Obtención de los movimientos en tránsito- .\r\n".$this->io_sql_origen->message."\r\n";
					$ls_cadena=$ls_cadena.$ls_sql."\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
					return false;
				}
				else //Si no ocurrio error
				{
					$lb_valido=true;
					if($this->io_sql_origen->fetch_row($io_recordset))
					{
						$ds_transito->data=$this->io_sql_origen->obtener_datos($io_recordset);																
					}
				}						
				// Movimientos en transito en forma resumida
				$ls_sql="SELECT codban,ctaban,codope,SUM(monto-monret) AS total, estmov 
						   FROM scb_movbco 
						  WHERE codemp='$ls_codemp' 
							AND codban='$as_codban' 
							AND ctaban='$as_ctaban' 
							AND estcon=0 
							AND (estmov='C' OR estmov='L')
						  GROUP by codban,ctaban,codope,estmov 
						  ORDER BY codban,ctaban";
				$io_recordset=$this->io_sql_origen->select($ls_sql);
				if(($io_recordset===false))
				{
					
					$ls_cadena="Error al Trasladar saldos -Obtención de los movimientos en tránsito resumidos- .\r\n".$this->io_sql_origen->message."\r\n";
					$ls_cadena=$ls_cadena.$ls_sql."\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
					return false;
				}
				else
				{
					$lb_valido=true;						
					$ldec_debitostmp=0;
					$ldec_creditostmp=0;
					$ldec_debitos_negativostmp=0;
					$ldec_creditos_negativostmp=0;
					$li_numrows = $this->io_sql_origen->num_rows($io_recordset);
					if ($li_numrows>0)
					   {
						 while($row=$this->io_sql_origen->fetch_row($io_recordset))
							  {
								 $ls_operacion = $row["codope"];
								 $ls_estado    = $row["estmov"];
								 $ldec_monto   = $row["total"];
								 if((($ls_operacion=="CH") || ($ls_operacion=="ND") || ($ls_operacion=="RE")) && ($ls_estado!="A"))
								 {	
									$ldec_creditostmp+=$ldec_monto;
								 }	
								 elseif((($ls_operacion=="CH") || ($ls_operacion=="ND") || ($ls_operacion=="RE")) && ($ls_estado=="A"))
								 {	
									$ldec_creditos_negativostmp+=$ldec_monto;
								 }	
								 elseif((($ls_operacion=="DP") || ($ls_operacion=="NC")) && ($ls_estado!="A"))
								 {	
									$ldec_debitostmp+=$ldec_monto;
								 }
								 elseif((($ls_operacion=="DP") || ($ls_operacion=="NC")) && ($ls_estado=="A"))
								 {
									$ldec_debitos_negativostmp+=$ldec_monto;
								 }				
							  }
					   }
					}
				 if ($lb_valido)//Si los mov en transito se encontraron sin error
					{
					  $ldec_debitos  = $ldec_debitostmp  - $ldec_debitos_negativostmp;
					  $ldec_creditos = $ldec_creditostmp - $ldec_creditos_negativostmp;
					  $ldec_saldo_tmp= $ds_cuenta->getValue("saldo",1);
					  $ds_cuenta->updateRow("saldo",($ldec_saldo_tmp + $ldec_creditos - $ldec_debitos),1);
					}
				}//Fin del else de los mov en transito resumidos
			}//Fin del select de los movimientos en transito.
			//-------Se realiza el traspaso de los saldos de banco-------------------//


			$lb_existe=$this->uf_verificar_cuenta($as_codban, $as_ctaban);
			if($lb_existe===1)//si existe la cuenta
			{
				$ldec_saldo=$ds_cuenta->getValue("saldo",1);
				if($ldec_saldo>=0)
				{
					$ls_operacion="NC";
				}
				else
				{
					$ls_operacion="ND";
				}
				$ldec_saldoaux = abs($ds_cuenta->getValue("saldo",1));
				$ldec_saldo    = $this->io_rcbsf->uf_convertir_monedabsf($ldec_saldoaux,4,1,1000,1);
				
				$ls_numdoc = "0000000APERTURA";
				$lb_existe=$this->uf_verificar_apertura($ls_numdoc,$as_codban, $as_ctaban);
				if($lb_existe===0)//si no exist la apertura, insertamos en la tabla movbco
				{
					
					$ls_sql="INSERT INTO scb_movbco (codemp,numdoc,codban,ctaban,codope,fecmov,conmov,codconmov,
							tipo_destino,estmov,monto,montoaux,estbpd,estcon,estimpche,monret,monretaux,cod_pro,
							ced_bene,chevau,feccon,monobjret,monobjretaux,nomproben)
							VALUES ('$ls_codemp','$ls_numdoc','$as_codban','$as_ctaban','$ls_operacion','$ad_periodoviejo',
							'SALDO INICIAL DE LA CUENTA','---','','L',".$ldec_saldo.",".$ldec_saldoaux.",'M',0,1,
							0.0000,0.0000,'----------','----------','','1900-01-01',".$ldec_saldo.",".$ldec_saldoaux.",'')";
					
					$li_row=$this->io_sql_destino->execute($ls_sql);
					if($li_row===false)
					{
						$lb_valido=false;
						$ls_cadena="Error al Insertar apertura -Inserción del documento de la Apertura- .\r\n".$this->io_sql_destino->message."\r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
					}
					else
					{
						$lb_valido=true;
					}	
				}										
			}						
			else
			{
				$lb_valido=false;
				if($lb_existe===0)//No existe la cuenta
				{
					$ls_cadena="La cuenta No. $as_ctaban no existe en la nueva Base de Datos, Favor revise \r\n";
					$ls_cadena=$ls_cadena.$ls_sql."\r\n";
					if ($this->lo_archivo)			
					{
						@fwrite($this->lo_archivo,$ls_cadena);
					}
					$this->io_mensajes->message("La cuenta No. $as_ctaban no existe en la nueva Base de Datos, Favor revise");
				}
				else
				{
					$this->io_mensajes->message("Error al buscar la cuenta");
				}
			}
		//------Se insertan los movimientos en transito si la opcion esta tildada
	
		$la_indices_repetidos=array();
		if($ab_transito && $lb_valido)
		{
			$li_totrow=$ds_transito->getRowCount("numdoc");
			$la_indices_repetidos=array();
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codban       = $ds_transito->getValue("codban",$li_i);
				$ls_ctaban       = trim($ds_transito->getValue("ctaban",$li_i));
				$ls_numdoc       = $ds_transito->getValue("numdoc",$li_i);
				$ls_codope       = $ds_transito->getValue("codope",$li_i);
				$ls_estmov       = $ds_transito->getValue("estmov",$li_i);
				$ls_fecmov       = $this->io_validacion->uf_valida_fecha($ds_transito->getValue("fecmov",$li_i),"1900-01-01");
				$ls_conmov       = $ds_transito->getValue("conmov",$li_i).". Fecha original del Documento:".$this->io_funciones->uf_convertirdatetobd($ls_fecmov);
				$ls_nomproben    = $ds_transito->getValue("nomproben",$li_i);
				$ls_codpro       = $this->io_funciones->uf_cerosizquierda($ds_transito->getValue("cod_pro",$li_i),10);
				$ls_cedbene      = $ds_transito->getValue("ced_bene",$li_i);
				$ls_chevau       = $ds_transito->getValue("chevau",$li_i);
				$ls_tipodestino  = $ds_transito->getValue("tipo_destino",$li_i);
				$ls_codconmov    = $ds_transito->getValue("codconmov",$li_i);
				$ld_montoaux     = $ds_transito->getValue("monto",$li_i);
				$ld_monto        = $this->io_rcbsf->uf_convertir_monedabsf($ld_montoaux,4,1,1000,1);
				$ls_estcon       = $ds_transito->getValue("estcon",$li_i);
				$ls_estcobing    = $ds_transito->getValue("estcobing",$li_i);
				$ls_esttra       = $ds_transito->getValue("esttra",$li_i);
				$ls_estimpche    = $ds_transito->getValue("estimpche",$li_i);
				$ld_monobjretaux = $this->io_validacion->uf_valida_monto($ds_transito->getValue("monobjret",$li_i),0);
				$ld_monobjret    = $this->io_rcbsf->uf_convertir_monedabsf($ld_monobjretaux,4,1,1000,1);
				$ld_monretaux    = $this->io_validacion->uf_valida_monto($ds_transito->getValue("monret",$li_i),0);
				$ld_monret       = $this->io_rcbsf->uf_convertir_monedabsf($ld_monretaux,4,1,1000,1);
				$ls_procede      = $ds_transito->getValue("procede",$li_i);
				$ls_comprobante  = $ds_transito->getValue("comprobante",$li_i);
				$ls_fecha        = $this->io_validacion->uf_valida_fecha($ds_transito->getValue("fecha",$li_i),'1900-01-01');
				$ls_id_mco       = $ds_transito->getValue("id_mco",$li_i);
				$ls_comprobante  = $ds_transito->getValue("comprobante",$li_i);
				$ls_emicheproc   = $ds_transito->getValue("emicheproc",$li_i);
				$ls_emicheced    = $ds_transito->getValue("emicheced",$li_i);
				$ls_emichenom    = $ds_transito->getValue("emichenom",$li_i);
				$ls_emichefec    = $this->io_validacion->uf_valida_fecha($ds_transito->getValue("emichefec",$li_i),'1900-01-01');
				$ls_estmovint    = $ds_transito->getValue("estmovint",$li_i);
				$ls_codusu       = $ds_transito->getValue("codusu",$li_i);
				$ls_codopeidb    = $ds_transito->getValue("codopeidb",$li_i);
				$ld_aliidbaux    = $this->io_validacion->uf_valida_monto($ds_transito->getValue("aliidb",$li_i),0);
				$ld_aliidb       = $this->io_rcbsf->uf_convertir_monedabsf($ls_aliidbaux,4,1,1000,1);
				$ls_feccon       = $this->io_validacion->uf_valida_fecha($ds_transito->getValue("feccon",$li_i),'1900-01-01');
				$ls_estreglib    = $ds_transito->getValue("estreglib",$li_i);
				$ls_numcarord    = $ds_transito->getValue("numcarord",$li_i);
				$ls_numpolcon    = $ds_transito->getValue("numpolcon",$li_i);
				$ls_coduniadmsig = $ds_transito->getValue("coduniadmsig",$li_i);
				$ls_codbansig    = $ds_transito->getValue("codbansig",$li_i);
				$ls_fecordpagsig = $this->io_validacion->uf_valida_fecha($ds_transito->getValue("fecordpagsig",$li_i),'1900-01-01');
				$ls_tipdocressig = $ds_transito->getValue("tipdocressig",$li_i);
				$ls_numdocressig = $ds_transito->getValue("numdocressig",$li_i);
				$ls_estmodordpag = $ds_transito->getValue("estmodordpag",$li_i);
				$ls_codfuefin    = $ds_transito->getValue("codfuefin",$li_i);
				$ls_forpagsig    = $ds_transito->getValue("forpagsig",$li_i);
				$ls_medpagsig    = $ds_transito->getValue("medpagsig",$li_i);
				$ls_codestprosig = $ds_transito->getValue("codestprosig",$li_i);
				$ls_nrocontrolop = $ds_transito->getValue("nrocontrolop",$li_i);
				$ls_fechaconta   = $ds_transito->getValue("fechaconta",$li_i);
				$ls_fechaconta   = $this->io_validacion->uf_valida_fecha($ls_fechaconta,"1900-01-01");
				$ls_fechaanula   = $ds_transito->getValue("fechaanula",$li_i);
				$ls_fechaanula   = $this->io_validacion->uf_valida_fecha($ls_fechaanula,"1900-01-01");
				
				$lb_existe       = $this->uf_verificar_cuenta($ls_codban, $ls_ctaban);
				$li_temp=1;
				if ($lb_existe===1)//si la cuenta existe
				   {
					 $lb_existe=$this->uf_verificar_documento($ls_codban, $ls_ctaban,$ls_numdoc,$ls_codope);
					 if ($lb_existe===1)//Si el documento ya existe en la bd, guardamos su indice para luego imprimirlo.
						{
						  array_push($la_indices_repetidos,$li_i);														
						}
					else //Se inserta en la nueva bd
					{
						$ls_sql = "INSERT INTO scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, 
						                                   tipo_destino, codconmov, fecmov, conmov, nomproben, monto, estbpd, 
														   estcon, estcobing, esttra, chevau, estimpche, monobjret, monret, 
														   procede, comprobante, fecha, id_mco, emicheproc, emicheced, emichenom, 
														   emichefec, estmovint, codusu, codopeidb, aliidb, feccon, estreglib, 
														   numcarord, numpolcon, coduniadmsig, codbansig, fecordpagsig, 
														   tipdocressig, numdocressig, estmodordpag, codfuefin, forpagsig, 
														   medpagsig, codestprosig, nrocontrolop, fechaconta, fechaanula, 
														   montoaux, monobjretaux, monretaux, aliidbaux) 
												    VALUES ('".$ls_codemp."','".$ls_codban."','".$ls_ctaban."','".$ls_numdoc."',
													        '".$ls_codope."','L','".$ls_codpro."','".$ls_cedbene."','".$ls_tipodestino."',
															'".$ls_codconmov."','".$ls_fecmov."','".$ls_conmov."','".$ls_nomproben."',
															".$ld_monto.",'D','".$ls_estcon."','".$ls_estcobing."','".$ls_esttra."',
															'".$ls_chevau."','".$ls_estimpche."',".$ld_monobjret.",".$ld_monret.",
															'".$ls_procede."','".$ls_comprobante."','".$ls_fecha."','".$ls_id_mco."',
															'".$ls_emicheproc."','".$ls_emicheced."','".$ls_emichenom."','".$ls_emichefec."','".$ls_estmovint."',
								                            '".$ls_codusu."','".$ls_codopeidb."',".$ld_aliidb.",'".$ls_feccon."',
															'".$ls_estreglib."','".$ls_numcarord."','".$ls_numpolcon."','".$ls_coduniadmsig."',
															'".$ls_codbansig."','".$ls_fecordpagsig."','".$ls_tipdocressig."','".$ls_numdocressig."',
															'".$ls_estmodordpag."','".$ls_codfuefin."','".$ls_forpagsig."','".$ls_medpagsig."',
															'".$ls_codestprosig."','".$ls_nrocontrolop."','".$ls_fechaconta."','".$ls_fechaanula."',
															".$ld_montoaux.",".$ld_monobjretaux.",".$ld_monretaux.",".$ld_aliidbaux.")";
						$li_row=$this->io_sql_destino->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$ls_cadena="Error al Insertar apertura -Inserción de los movimientos en tránsito-.\r\n".$this->io_sql_destino->message."\r\n";
							$ls_cadena=$ls_cadena.$ls_sql."\r\n";
							if ($this->lo_archivo)			
							{
								@fwrite($this->lo_archivo,$ls_cadena);
							}
						}
						else
						{
							$lb_valido=true;
						}
					}	
				}
				else
				{
					$lb_valido=false;
					if($lb_existe===0)//No existe la cuenta
					{
						$ls_cadena="La cuenta No. $ls_ctaban no existe en la nueva Base de Datos, Favor revise \r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
						$this->io_mensajes->message("La cuenta No. $as_ctaban no existe en la nueva Base de Datos, Favor revise");
					}
					else
					{
						$this->io_mensajes->message("Error al buscar la cuenta");
					}
				}
			}
		}//Fin de la inserción de los movimientos en transito.	
	
		//-------------Se insertan los movimientos de colocacion--------------------------------//
	
		if($lb_valido)
		{
		
			$li_totrow=$ds_colocaciones->getRowCount("numcol");
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_codban=$ds_colocaciones->getValue("codban",$li_i);
				$ls_ctaban=$ds_colocaciones->getValue("ctaban",$li_i);
				$ls_numcol=$ds_colocaciones->getValue("numcol",$li_i);
				$ldec_saldo=$ds_colocaciones->getValue("saldo",$li_i);
				$lb_existe=$this->uf_verificar_colocacion($ls_codban, $ls_ctaban,$ls_numcol);
				if($lb_existe===1)//Si la colocacion existe en la nueva bd
				{
					if($ldec_saldo >= 0)
						$ls_operacion = "NC";
					else
						$ls_operacion = "ND";
					
					$ldec_saldoaux = abs($ldec_saldo);
					$lb_existe=$this->uf_verificar_movimiento_colocacion($ls_codban, $ls_ctaban,$ls_numcol,"0000000APERTURA",
																		 $ls_operacion,"L");
				
					if($lb_existe===0)
					{
						
						$ldec_saldo = $this->io_rcbsf->uf_convertir_monedabsf($ldec_saldoaux,4,1,1000,1);
						$ls_sql="INSERT INTO scb_movcol	(codemp,codban,ctaban,numcol,numdoc,codope,estcol,fecmovcol,
														 monmovcol,monmovcolaux,tasmovcol,conmov,estcob,esttranf)
												 VALUES ('$ls_codemp','$ls_codban','$ls_ctaban','$ls_numcol',
														 '0000000APERTURA','$ls_operacion','L','$ad_periodoviejo',
														 ".$ldec_saldo.",".$ldec_saldoaux.",0,'SALDO INICIAL DE LA COLOCACION',0,0)";
						$li_row=$this->io_sql_destino->execute($ls_sql);
						if($li_row===false)
						{
							$lb_valido=false;
							$ls_cadena="Error al Insertar apertura - Inserción de movimientos de colocación- .\r\n".$this->io_sql_destino->message."\r\n";
							$ls_cadena=$ls_cadena.$ls_sql."\r\n";
							if ($this->lo_archivo)			
							{
								@fwrite($this->lo_archivo,$ls_cadena);
							}
						}
						else
						{
							$lb_valido=true;
						}
					}
					else
					{
						if($lb_existe===1)
							$lb_valido=true;
						else
							$lb_valido=false;
					}
				}
				else
				{
					$lb_valido=false;
					if($lb_existe===0)//No existe la cuenta
					{
						$ls_cadena="La colocación No. $ls_numcol NO existe en la nueva Base de Datos, Favor revise \r\n";
						$ls_cadena=$ls_cadena.$ls_sql."\r\n";
						if ($this->lo_archivo)			
						{
							@fwrite($this->lo_archivo,$ls_cadena);
						}
						$this->io_mensajes->message("La colocación No. $ls_numcol NO existe en la nueva Base de Datos, Favor revise");
					}
					else
					{
						$this->io_mensajes->message("Error al buscar la colocacion");
					}
					
				}
			}
		}
		if($lb_valido)
		{
			
			$li_totrow=count($la_indices_repetidos);
			if($li_totrow>0)
			{
				$ls_fecha    = date("Y_m_d_H_i");
				$ls_nombre   = "resultado/documentos_repetidos".$ls_fecha.".txt";
				$lo_archivo2  = @fopen("$ls_nombre","a+");
				$this->io_mensajes->message("Se ha generado un archivo (documentos_repetidos.txt), el cual contiene los documentos que no se pueden traspasar, debido a que ya se encuentran registrados en la nueva Base de Datos.");
			
				for($li_i=0;$li_i<$li_totrow;$li_i++)
				{
					$li_indice=$la_indices_repetidos[$li_i];
					$ls_numdoc=$ds_transito->getValue("numdoc",$li_indice);
					$ls_cadena="El Movimiento No. $ls_numdoc no pudo ser traspasado debido a que ya existía en la nueva Base de Datos \r\n\r\n";
					if ($lo_archivo2)			
					{
						@fwrite($lo_archivo2,$ls_cadena);
					}							
				}
			}					
			@fwrite($this->lo_archivo,"Proceso ejecutado sin errores");		
		}
		return $lb_valido;						
	}		
}
?>
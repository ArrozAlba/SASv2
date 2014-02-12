<?php
ini_set('precision','15');
require_once("../class_folder/sigesp_conexion_dao.php");
require_once('../librerias/php/general/funciones.php');
class class_sigesp_int
{
	var $is_modo  ="";          // modalidad de integración 
	var $is_codemp="";          // códigto de la empresa
	var $is_msg_error="";       // instancia que contiene un mensaje de error de transacción.
	var $is_procedencia="";     // representa el origen del documento a nivel del proceso y sistema en cuastión
	var $is_comprobante="";     // representa el N° de comprobante a generar.
	var $is_descripcion="";     // insdica la descripción del comprobante.
	var $is_tipo="";            // variable que indica si es B=beneficiario o P=proveedor    
	var $is_fuente="";          // representa la fuente o el código proveedor/beneficiario.
	var $is_proc="";
	var $is_cod_prov="";        // código de proveedor
	var $is_ced_ben="";         // cedula del beneficiario
	var $as_codban="";        // código de banco
	var $as_ctaban="";         // cedula del banco
	var $is_log_transacciones=""; // se refiere  a la información de la  transación que se esta procesando
	var $is_salto="";           
	var $ib_OverrideCheck;   // Override Check SPG
	var $ib_AutoConta;       // Automática Contabilizacion  
	var $ib_db_error ;       // instancia booleana que indica si existe un error de base de datos.
	var $ib_procesando_cmp ; // instancia booleana que indica si se esta procesando un comprobante
                           // si es cierto o True invalida el proceso. 	
	var $idec_monto=0 ;       // representa el monto del comprobante
	var $idec_monto_debe=0;   // representa el monto debe de los movimientos contables
	var $idec_monto_haber=0;  // representa el monto haber de los movimientos contables	
	var $id_fecha;
	var $resultset;
	var $ii_tipo_comp;        // Tipo comporbante
	var $ia_niveles=array();        // niveles del formato de la cuenta
	var $ia_niveles_scg=array();    // niveles del formato de la cuenta contable
	var $ia_niveles_spg=array();    // niveles del formato de la cuenta gasto
	var $ia_niveles_spi=array();    // niveles del formato de la cuenta
	var $sqlca;
	var $io_function;
	var $dat_emp;
	var $obj="";
	var $io_sql;
	var $io_include;
	var $io_connect;
	var $io_fecha;
	var $ib_new_comprobante;
	var $ib_spg_enlace_contable=true;  //  si es true genera el encalce contable de gasto

	//-----------------------------------------------------------------------------------------------------------------------------------
	function class_sigesp_int()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: class_sigesp_int
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  		$this->io_fecha=new class_fecha();
		$this->io_function = new class_funciones();
		//$this->io_include=new sigesp_include();
	//	$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->io_msg=new class_mensajes();
		if(array_key_exists("la_empresa",$_SESSION))
		{
			$this->dat_emp=$_SESSION["la_empresa"];
		}
		else
		{
			$this->dat_emp="";
		}
		
		$li_nivel=0;
		$this->uf_init_niveles();
	}// end function class_sigesp_int
	//-----------------------------------------------------------------------------------------------------------------------------------
		
	//-----------------------------------------------------------------------------------------------------------------------------------
	
		
function ObtenerComprobante()
{
	global $db;
	//$db->debug="1"; 
	$sql="SELECT max(comprobante) FROM sigesp_sfp_cmp";
	$Rs = $db->Execute($sql);
	if($Rs->fields[0]=='')
	{
		$Comp = AgregarUnoZ(1, 15);
	}
	else
	{
		$Comp = AgregarUnoZ($Rs->fields[0], 15);	
	}
	
	//die();
	return $Comp;
}
	

function uf_init_niveles()
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_init_niveles
		//		   Access: public 
		//	  Description: Este método realiza una consulta a los formatos de las cuentas
		//               	para conocer los niveles de la escalera de las cuentas contables, presupuestarias de Gasto y
		//					presupuestarias de Ingreso
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_formato="";
		$li_posicion=0;
		$li_indice=0;
		$this->dat_emp=$_SESSION["la_empresa"];
		//contable
		//$ls_formato = trim($this->dat_emp["formcont"])."-";//colocar la sesion de la empresa
		$ls_formato="9-9-9-99-99-99-99-99-99"."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $this->io_function->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$this->ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $this->io_function->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
		//gasto 
		$ls_formato = trim($this->dat_emp["formpre"])."-";
		$li_posicion = 1;
		$li_indice   = 1;		
		$li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;	
		do
		{
			$this->ia_niveles_spg[$li_indice] = $li_posicion ;
			$li_indice = $li_indice + 1;
			$li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
		// ingreso
		$ls_formato = trim($this->dat_emp["formspi"])."-";
		$li_posicion = 1;
		$li_indice   = 1;	
		$li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;	
		do 
		{
		    $this->ia_niveles_spi[ $li_indice ] = $li_posicion ;
			$li_indice = $li_indice + 1;
			$li_posicion = $this->io_function->uf_posocurrencia($ls_formato,"-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_total_niveles($as_formato)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_total_niveles
		//		   Access: public 
		//       Argument: as_formato //   formato de l cuenta definida en sigesp_empresa
		//	  Description: Este método retorna el numero de niveles de la cuenta
		//	      Returns: li_count // total de niveles
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_count=0;
		$i=0;
		$arr=str_split($as_formato);
		$tot=count($arr);
		for($i=0;$i<$tot;$i++) 
		{
			if($arr[$i]=="-")
			{
				$li_count=$li_count+1;
			}
		}
	    return $li_count+1;	
	}// end function uf_total_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_dividir_programatica($as_programatica) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_dividir_programatica
		//		   Access: public 
		//       Argument: as_programatica //   cadena concatenada de la estructura presupuestaria
		//	  Description: Método que separa la cadena en los niveles presupuestarios y retorna la informacion en matriz
		//	      Returns: Arreglo // programaticas separadas en un arreglo
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_est1 = substr($as_programatica,0,20);
		$as_est2 = substr($as_programatica,20,6);
		$as_est3 = substr($as_programatica,26,3);
		$as_est4 = substr($as_programatica,6,2);
		$as_est5 = substr($as_programatica,8,2);
		$arreglo[0]=$as_est1;
		$arreglo[1]=$as_est2;
		$arreglo[2]=$as_est3;
		$arreglo[3]=$as_est4;
		$arreglo[4]=$as_est5;
		return $arreglo;		
	}// end function uf_dividir_programatica
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_fill_comprobante($as_comprobante)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_fill_comprobante
		//		   Access: public 
		//       Argument: as_comprobante // Número de comprobante
		//	  Description: llena por defecto de cero a la izquierda
		//	      Returns: as_fillcmp // Número de comprobante con ceros a la izquierda hasta llegar a 15 posiciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_fillcmp=str_pad($as_comprobante, 15, "0", STR_PAD_LEFT); 
		return $as_fillcmp;
	} // end function de uf_fill_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_fill_documento($as_documento)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_fill_comprobante
		//		   Access: public 
		//       Argument: as_documento // Número de documento
		//	  Description: llena por defecto de cero a la izquierda
		//	      Returns: as_filldoc // Número de documento con ceros a la izquierda hasta llegar a 15 posiciones
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$as_filldoc=str_pad($as_documento, 15, "0", STR_PAD_LEFT); 
		return $as_filldoc;
	} // end function uf_fill_documento	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_int_config($ab_autoconta,$ab_overridecheck)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_int_config
		//		   Access: public 
		//       Argument: ab_autoconta // Contabilizar la parte contable automáticamente
		//       		   ab_overridecheck // Override Check SPG
		//	  Description: Asigna a las variables globales las que se pasan por parametros.
		//	      Returns: 
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->ib_AutoConta     = $ab_autoconta;
		$this->ib_OverrideCheck = $ab_overridecheck;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_valida_procedencia($as_procedencia,&$as_desproc)
	{
		global $db;
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_valida_procedencia
		//		   Access: public 
		//       Argument: as_procedencia // Procedencia del documento
		//       		   as_desproc // Descripción de la procdencia
		//	  Description: Este método que valida la procedencia donde proviene el documento
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT procede, desproc ".
				"  FROM sigesp_procedencias ".
				" WHERE procede='".$as_procedencia."'";
		$rs_data=$db->Execute($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_valida_procedencia ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$is_proc=$row["desproc"];
				$as_desproc=$is_proc;
			}
		   	else
			{
				$lb_valido=false;
				$sql="insert into sigesp_procedencias values('{$as_procedencia}','SFP','FIG','Formulacion de Ingresos y gastos del modulo de formulacion presupuestaria')";
				$rs_data=$db->Execute($sql);
				$lb_valido=true;
			}
		}
		return $lb_valido;
	}  // end function uf_valida_procedencia
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sigesp_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,
								   $as_cod_pro,$as_ced_bene,$adec_monto,$as_codban,$as_ctaban)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   ai_tipo_comp // Tipo de Comprobante
		//       		   as_descripcion // Descripción del Comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   adec_monto // Monto del Comprobante
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método verifica si un comprobante existe y lo actualiza ó si no existe lo inserta
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->is_codemp=$as_codemp;
		$this->is_procedencia=$as_procedencia;
		$this->is_comprobante=$as_comprobante;
		$this->id_fecha=$as_fecha;
   	    $this->ii_tipo_comp=$ai_tipo_comp;
		$this->is_descripcion=$as_descripcion;
		$this->is_tipo=$as_tipo;
		$this->as_codban=$as_codban;
		$this->as_ctaban=$as_ctaban;
		if($as_tipo=="B")
		{
			$this->is_ced_ben=$as_ced_bene;
			$this->is_cod_prov="----------"; 
		}
		if($as_tipo=="P")
		{
		   $this->is_ced_ben="----------";
		   $this->is_cod_prov=$as_cod_pro;
		}
		if($as_tipo=="-")
		{
		   $this->is_ced_ben="----------";
		   $this->is_cod_prov="----------";
		}
        if($this->uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$as_codban,$as_ctaban))
		{
		   $this->ib_new_comprobante=false;
           $lb_valido=$this->uf_sigesp_update_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,
		   												  $as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,
														  $as_codban,$as_ctaban);
		}
		else
		{
		   $this->ib_new_comprobante=true;		
 		   $lb_valido=$this->uf_sigesp_insert_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha,$ai_tipo_comp,
		   												  $as_descripcion,$as_tipo,$this->is_cod_prov,$this->is_ced_ben,
														  $as_codban,$as_ctaban);
		}
		return $lb_valido;
	} // end function uf_sigesp_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sigesp_insert_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,$sig_cuenta,$ano_presupuesto,$codinte="NULL")
	{
		global $db;
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_insert_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   ai_tipo_comp // Tipo de Comprobante
		//       		   as_descripcion // Descripción del Comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método inserta la cabecera de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fec=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="INSERT INTO sigesp_sfp_cmp (codemp, procede, comprobante, fecha, descripcion, tipo_comp,tipo_destino,sig_cuenta,ano_presupuesto,codinte) VALUES ('".$as_codemp."', '".$as_procede."', '".$as_comprobante."', '".$ls_fec."',".
				"'".$as_descripcion."', ".$ai_tipo_comp.", '".$as_tipo."','".$sig_cuenta."',".$ano_presupuesto.",{$codinte})";
		$li_numrows=$db->Execute($ls_sql);
		if($li_numrows===false)
		{
			$lb_valido=false;
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_sigesp_insert_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		return $lb_valido;
	} // end function uf_sigesp_insert_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sigesp_update_comprobante($as_codemp,$as_procede,$as_comprobante,$as_fecha,$ai_tipo_comp,$as_descripcion,$as_tipo,
										  $as_cod_prov,$as_ced_ben,$as_codban,$as_ctaban)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_update_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procede // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   ai_tipo_comp // Tipo de Comprobante
		//       		   as_descripcion // Descripción del Comprobante
		//       		   as_tipo // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Este método actualiza la cabecera de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_fecha=$this->io_function->uf_convertirdatetobd($as_fecha);
		$ls_sql="UPDATE sigesp_cmp ".
				"   SET descripcion='".$as_descripcion."' ".
		        " WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procede."' ".
				"   AND comprobante='".$as_comprobante."' ".
				"   AND fecha='".$ls_fecha."'".
				"   AND codban='".$as_codban."'".
				"   AND ctaban='".$as_ctaban."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error = "CLASE->sigesp_int MÉTODO->uf_sigesp_update_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_sigesp_update_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_sigesp_delete_comprobante()	
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sigesp_delete_comprobante
		//		   Access: public 
		//       Argument: 
		//	  Description: Método que elimina el registro de un comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ld_fecha=$this->io_function->uf_convertirdatetobd($this->id_fecha);
		$ls_sql="DELETE FROM sigesp_cmp ".
				" WHERE codemp = '".$this->is_codemp."' ".
				"   AND procede='".$this->is_procedencia."' ".
				"   AND comprobante='".$this->is_comprobante."' ".
				"   AND fecha='".$ld_fecha."'".
				"   AND codban='".$this->as_codban."'".
				"   AND ctaban='".$this->as_ctaban."'";
		$li_numrows=$this->io_sql->execute($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_sigesp_delete_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		return $lb_valido;
	} // end function uf_sigesp_delete_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_comprobante($as_codemp,$as_procedencia,$as_comprobante,$as_fecha)
	{
		global $db;
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//	  Description: Método que verifica si existe o no el comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_newfec=uf_convertirdatetobd($as_fecha);
		$ls_sql="SELECT comprobante ".
			   "  FROM sigesp_sfp_cmp ".
			   " 	WHERE codemp='".$as_codemp."' ".
			   "   AND procede='".$as_procedencia."' ".
			   "   AND comprobante='".$as_comprobante."' ".
			   "   AND fecha= '".$ls_newfec."' ";
		$li_numrows=$db->Execute($ls_sql);
	//	var_dump($li_numrows);
	//	die();
		if($li_numrows===false)
		{
				echo "no se pudo ejecutar la consulta";
		//	$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_select_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
//			return false;
		}
		else  
		{ 
			if($row=$li_numrows->FetchRow()) 
			{ 
				$lb_existe=true;
				 
			}
			else
			{
				$lb_existe=false;
				
			}  
		}
		return $lb_existe;
	} // end function uf_select_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtener_comprobante($as_codemp,$as_procedencia,$as_comprobante,$adt_fecha,$as_codban,$as_ctaban,
								    &$as_tipo_destino,&$as_ced_bene,&$as_cod_pro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtener_comprobante
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_procedencia // Procedencia del documento
		//       		   as_comprobante // Número de Comprobante
		//       		   as_fecha // Fecha del Comprobante
		//       		   as_codban // Código de Banco
		//       		   as_ctaban // Cuenta de Banco
		//       		   as_tipo_destino // Tipo de Destino si es proveedor ó  Beneficiario
		//       		   as_ced_bene // Cédula del Beneficiario
		//       		   as_cod_pro // Código de Proveedor
		//	  Description: Este método obtiene el tipo de Destino, el proveedor y beneficiario del comprobante
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_newfec=$this->io_function->uf_convertirdatetobd($adt_fecha);
		$ls_sql="SELECT tipo_destino, ced_bene, cod_pro ".
				"  FROM sigesp_cmp ".
				" WHERE codemp='".$as_codemp."' ".
				"   AND procede='".$as_procedencia."' ".
				"   AND comprobante='".$as_comprobante."'".
				"   AND fecha='".$ls_newfec."'".
			    "   AND codban='".$as_codban."'".
			    "   AND ctaban='".$as_ctaban."'";
		$lr_result = $this->io_sql->select($ls_sql);
		if($lr_result===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_obtener_comprobante ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql->fetch_row($lr_result)) 
			{ 
				$lb_existe=true;
				$as_tipo_destino=$row["tipo_destino"];
				$as_ced_bene=$row["ced_bene"];
				$as_cod_pro=$row["cod_pro"];
			}  
		}
		return $lb_existe;
	} // end function uf_obtener_comprobante
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_sql_transaction($lb_valido)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sql_transaction
		//		   Access: public 
		//       Argument: lb_valido // si el proceso fue valido ó no
		//	  Description: Este método dependiendo si los procesos anteriores fueron validos todos hace un commit sino
		//				   hace un rollback
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Wilmer Briceño
		// Modificado Por: Ing. Yesenia Moreno								Fecha Última Modificación : 31/05/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($lb_valido)
		{
			$this->io_sql->commit();
			$lb_valido=true;
		}
		else
		{
			$this->io_sql->rollback();
			$lb_valido=false;
 		}	
		return $lb_valido;
	}// end function uf_sql_transaction
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_proveedor($as_codemp,$as_codpro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_proveedor
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_codpro // Código de Proveedor
		//	  Description: Método que verifica si existe o no el proveedor
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT cod_pro ".
			    "  FROM rpc_proveedor ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND cod_pro='".$as_codpro."' ";
		$li_numrows=$this->io_sql->select($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_select_proveedor ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql->fetch_row($li_numrows)) 
			{ 
				$lb_existe=true;
			}  
		}
		return $lb_existe;
	} // end function uf_select_proveedor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_beneficiario($as_codemp,$as_cedbene)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_beneficiario
		//		   Access: public 
		//       Argument: as_codemp // Código de empresa
		//       		   as_cedbene // Código de Beneficiario
		//	  Description: Método que verifica si existe o no el beneficiario
		//	      Returns: booleano lb_valido
		//	   Creado Por: Ing. Yesenia Moreno
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_existe=false;
		$ls_sql="SELECT ced_bene ".
			    "  FROM rpc_beneficiario ".
			    " WHERE codemp='".$as_codemp."' ".
			    "   AND ced_bene='".$as_cedbene."' ";
		$li_numrows=$this->io_sql->select($ls_sql);
		if($li_numrows===false)
		{
			$this->is_msg_error="CLASE->sigesp_int MÉTODO->uf_select_beneficiario ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message);
			return false;
		}
		else  
		{ 
			if($row=$this->io_sql->fetch_row($li_numrows)) 
			{ 
				$lb_existe=true;
			}  
		}
		return $lb_existe;
	} // end function uf_select_beneficiario
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
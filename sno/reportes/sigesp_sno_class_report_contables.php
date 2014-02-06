<?php
class sigesp_sno_class_report_contables
{
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_class_report_contables()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_class_report_contables
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$this->io_conexion=$io_include->uf_conectar();
		require_once("../../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($this->io_conexion);	
		$this->DS=new class_datastore();
		$this->DS_detalle=new class_datastore();
		$this->DS_detalle_2=new class_datastore();
		require_once("../../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];
        $this->ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
		$this->li_rac=$_SESSION["la_nomina"]["racnom"];
	}// end function sigesp_sno_class_report_contables
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable nombre de la variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $ls_resultado variable buscado
		//	  Description: Función que obtiene una variable de la tabla config
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_valor="";
		$ls_sql="SELECT value ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_select_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$li_i=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_valor=$row["value"];
				$li_i=$li_i+1;
			}
			if($li_i==0)
			{
				$lb_valido=$this->uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				if ($lb_valido)
				{
					$ls_valor=$this->uf_select_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo);
				}
			}
			$this->io_sql->free_result($rs_data);		
		}
		return rtrim($ls_valor);
	}// end function uf_select_config
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_config($as_sistema, $as_seccion, $as_variable, $as_valor, $as_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_config
		//		   Access: public
		//	    Arguments: as_sistema  // Sistema al que pertenece la variable
		//				   as_seccion  // Sección a la que pertenece la variable
		//				   as_variable  // Variable a buscar
		//				   as_valor  // valor por defecto que debe tener la variable
		//				   as_tipo  // tipo de la variable
		//	      Returns: $lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Función que inserta la variable de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql->begin_transaction();		
		$ls_sql="DELETE ".
				"  FROM sigesp_config ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codsis='".$as_sistema."' ".
				"   AND seccion='".$as_seccion."' ".
				"   AND entry='".$as_variable."' ";		
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$this->io_sql->rollback();
		}
		else
		{
			switch ($as_tipo)
			{
				case "C"://Caracter
					$valor = $as_valor;
					break;

				case "D"://Double
					$as_valor=str_replace(".","",$as_valor);
					$as_valor=str_replace(",",".",$as_valor);
					$valor = $as_valor;
					break;

				case "B"://Boolean
					$valor = $as_valor;
					break;

				case "I"://Integer
					$valor = intval($as_valor);
					break;
			}
			$ls_sql="INSERT INTO sigesp_config(codemp, codsis, seccion, entry, value, type)VALUES ".
					"('".$this->ls_codemp."','".$as_sistema."','".$as_seccion."','".$as_variable."','".$valor."','".$as_tipo."')";
			$li_row=$this->io_sql->execute($ls_sql);
			if($li_row===false)
			{
				$lb_valido=false;
				$this->io_mensajes->message("CLASE->Report MÉTODO->uf_insert_config ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
				$this->io_sql->rollback();
			}
			else
			{
				$this->io_sql->commit();
			}
		}
		return $lb_valido;
	}// end function uf_insert_config	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_presupuesto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla,  spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_contable
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_modoaporte=$this->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
				$li_genrecapo=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"),1,"0");
				$ls_estctaalt=trim($this->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));
				break;
			
			case 1: // La contabilización es por nómina
				$ls_modoaporte=trim($_SESSION["la_nomina"]["conaponom"]);
				$li_genrecapo=str_pad(trim($_SESSION["la_nomina"]["recdocapo"]),1,"0");
				$ls_estctaalt=trim($_SESSION["la_nomina"]["estctaalt"]);
				break;
		}
		if ($ls_estctaalt=='1')
		{
			$ls_scctaprov='rpc_proveedor.sc_cuentarecdoc';
			$ls_scctaben='rpc_beneficiario.sc_cuentarecdoc';
		}
		else
		{
			$ls_scctaprov='rpc_proveedor.sc_cuenta';
			$ls_scctaben='rpc_beneficiario.sc_cuenta';
		}
			$ls_sql=" SELECT cuenta, denoconta, operacion, total ".
					"   FROM contableaportes_contable            ".
				    "  WHERE codemp='".$this->ls_codemp."'       ".
					"    AND codnom='".$this->ls_codnom."'       ".
					"    AND codperi='".$this->ls_peractnom."'   ".
				    "  UNION                                     ".  
					" SELECT cuenta, denoconta, operacion, total ".
				    "   FROM contableaportes_contable_intcom     ".
				    "  WHERE codemp='".$this->ls_codemp."'       ".
					"    AND codnom='".$this->ls_codnom."'       ".
					"    AND codperi='".$this->ls_peractnom."'   "; 
					
		//print $ls_modoaporte." ---  ".$li_genrecapo."<br>";
		if(($ls_modoaporte=="OC")&&($li_genrecapo=="2"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			//print "Paso 1 "."<br>";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_concepto.codprov <> '----------' ".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"	AND sno_concepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_concepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = ".$ls_scctaprov." ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_concepto.cedben <> '----------' ".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"	AND sno_concepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_concepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = ".$ls_scctaben." ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			//print "Paso 2 "."<br>";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"   AND scg_cuentas.codemp = sno_concepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_concepto.cueconpatcon ".
					" GROUP BY scg_cuentas.sc_cuenta ".
					" ORDER BY operacion, cuenta"; 
		}
		//print $ls_sql."<br><br>";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),array('0'=>'cuenta','1'=>'operacion'));
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto
		//         Access: public (desde la clase sigesp_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A , que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 31/05/2006
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_group="  GROUP BY scg_cuentas.sc_cuenta ";
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_cuentapasivo=trim($this->uf_select_config("SNO","CONFIG","CTA.CONTA","-------------------------","C"));
				$ls_modo=trim($this->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C"));
				$li_genrecdoc=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"),1,"0");
				$ls_estctaalt=trim($this->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));
				break;
				
			case 1: // La contabilización es por nómina
				$ls_cuentapasivo=trim($_SESSION["la_nomina"]["cueconnom"]);
				$ls_modo=trim($_SESSION["la_nomina"]["consulnom"]);
				$li_genrecdoc=str_pad(trim($_SESSION["la_nomina"]["recdocnom"]),1,"0");
				$ls_estctaalt=trim($_SESSION["la_nomina"]["estctaalt"]);
				break;
		}
		if ($ls_estctaalt=='1')
		{
			$ls_scctaprov='rpc_proveedor.sc_cuentarecdoc';
			$ls_scctaben='rpc_beneficiario.sc_cuentarecdoc';
		}
		else
		{
			$ls_scctaprov='rpc_proveedor.sc_cuenta';
			$ls_scctaben='rpc_beneficiario.sc_cuenta';
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
		
				$ls_sql= " SELECT cuenta, denominacion, operacion, total ".
				         "   FROM contableconceptos_contable             ".
						 "  WHERE codemp='".$this->ls_codemp."'          ".
						 "	 AND codnom='".$this->ls_codnom."'           ".
						 "	 AND codperi='".$this->ls_peractnom."'       ".
						 " UNION                                         ".
						 " SELECT cuenta, denominacion, operacion, total ".
						 "   FROM contableconceptos_contable_intercom    ".
						  "  WHERE codemp='".$this->ls_codemp."'         ".
						 "	 AND codnom='".$this->ls_codnom."'           ".
						 "	 AND codperi='".$this->ls_peractnom."'       ";
		if($ls_modo=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($li_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQLT":
						$ls_cadena="CONVERT('".$ls_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRES":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
					case "INFORMIX":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.", MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, sno_banco, scg_cuentas ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$ls_cuentapasivo."' ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_salida.codemp = sno_banco.codemp ".
						"   AND sno_salida.codnom = sno_banco.codnom ".
						"   AND sno_salida.codperi = sno_banco.codperi ".
						"   AND sno_personalnomina.codemp = sno_banco.codemp ".
						"   AND sno_personalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, scg_cuentas, sno_nomina, rpc_proveedor ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_nomina.descomnom = 'P'".
						"   AND sno_nomina.codemp = sno_salida.codemp ".
						"   AND sno_nomina.codnom = sno_salida.codnom ".
						"   AND sno_nomina.peractnom = sno_salida.codperi ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_nomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_nomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND ".$ls_scctaprov." = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, scg_cuentas, sno_nomina, rpc_beneficiario ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_nomina.descomnom = 'B'".
						"   AND sno_nomina.codemp = sno_salida.codemp ".
						"   AND sno_nomina.codnom = sno_salida.codnom ".
						"   AND sno_nomina.peractnom = sno_salida.codperi ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_nomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_nomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND ".$ls_scctaben." = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND sno_personalnomina.pagbanper = 0 ".
					"   AND sno_personalnomina.pagtaqper = 0 ".
					"   AND sno_personalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND scg_cuentas.codemp = sno_personalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_personalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_banco, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
					"   AND sno_personalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_banco.codemp ".
					"   AND sno_salida.codnom = sno_banco.codnom ".
					"   AND sno_salida.codperi = sno_banco.codperi ".
					"   AND sno_personalnomina.codemp = sno_banco.codemp ".
					"   AND sno_personalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND sno_personalnomina.pagbanper = 0 ".
					"   AND sno_personalnomina.pagtaqper = 0 ".
					"   AND sno_personalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND scg_cuentas.codemp = sno_personalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_personalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),array('0'=>'cuenta','1'=>'operacion'));
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contableconceptos_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadreconceptoaporte_aportes()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadreconceptoaporte_aportes
		//         Access: public (desde la clase sigesp_sno_r_cuadreconceptoaporte)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica,sno_concepto.estcla, sno_concepto.cueprepatcon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codconc, sno_concepto.codpro,sno_concepto.estcla, sno_concepto.cueprepatcon  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprepatcon, sum(sno_salida.valsal) as total".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codconc, sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, sno_concepto.cueprepatcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_aportes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_programatica=$row["programatica"];
				$ls_estcla=$row["estcla"];
				$ls_cuentapresupuesto=$row["cueprepatcon"];
				$li_total=$row["total"];
				$ls_sql="SELECT denominacion ".
						"  FROM spg_cuentas ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND status = 'C'".
						"   AND codestpro1 = '".substr($ls_programatica,0,25)."'".
						"   AND codestpro2 = '".substr($ls_programatica,25,25)."'".
						"   AND codestpro3 = '".substr($ls_programatica,50,25)."'".
						"   AND codestpro4 = '".substr($ls_programatica,75,25)."'".
						"   AND codestpro5 = '".substr($ls_programatica,100,25)."'".
						"   AND estcla='".$ls_estcla."'".
						"   AND spg_cuenta = '".$ls_cuentapresupuesto."'";
				$rs_data2=$this->io_sql->select($ls_sql);
				if($rs_data2===false)
				{
					$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_aportes ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					if(!$row=$this->io_sql->fetch_row($rs_data2))
					{
						$this->DS->insertRow("programatica",$ls_programatica);
						$this->DS->insertRow("cueprepatcon",$ls_cuentapresupuesto);
						$this->DS->insertRow("denominacion","No Existe la cuenta en la Estructura.");
						$this->DS->insertRow("total",$li_total);
					}
					$this->io_sql->free_result($rs_data2);
				}
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cuadreconceptoaporte_aportes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadreconceptoaporte_conceptos()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadreconceptoaporte_conceptos
		//         Access: public (desde la clase sigesp_sno_r_cuadreconceptoaporte)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica,sno_concepto.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_concepto.sigcon = 'A' ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '1'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_concepto.sigcon = 'A' ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_programatica=$row["programatica"];
				$ls_estcla=$row["estcla"];
				$ls_cuentapresupuesto=$row["cueprecon"];
				$li_total=$row["total"];
				$ls_sql="SELECT denominacion ".
						"  FROM spg_cuentas ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND status = 'C'".
						"   AND codestpro1 = '".substr($ls_programatica,0,25)."'".
						"   AND codestpro2 = '".substr($ls_programatica,25,25)."'".
						"   AND codestpro3 = '".substr($ls_programatica,50,25)."'".
						"   AND codestpro4 = '".substr($ls_programatica,75,25)."'".
						"   AND codestpro5 = '".substr($ls_programatica,100,25)."'".
						"   AND estcla = '".$ls_estcla."'".
						"   AND spg_cuenta = '".$ls_cuentapresupuesto."'";
				$rs_data2=$this->io_sql->select($ls_sql);
				if($rs_data2===false)
				{
					$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_conceptos ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					if(!$row=$this->io_sql->fetch_row($rs_data2))
					{
						$this->DS_detalle->insertRow("programatica",$ls_programatica);
						$this->DS_detalle->insertRow("cueprecon",$ls_cuentapresupuesto);
						$this->DS_detalle->insertRow("denominacion","No Existe la cuenta en la Estructura.");
						$this->DS_detalle->insertRow("total",$li_total);
					}
					$this->io_sql->free_result($rs_data2);
				}
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cuadreconceptoaporte_conceptos
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_enmohca()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto
		//         Access: public (desde la clase sigesp_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A , que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.cueprecon, spg_cuentas.denominacion, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro, sno_concepto.estcla, sno_concepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_concepto.cueprecon, spg_cuentas.denominacion, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.cueprecon, spg_cuentas.denominacion, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,  sno_concepto.estcla, sno_concepto.cueprecon, spg_cuentas.denominacion ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_concepto.cueprecon, spg_cuentas.denominacion, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon, spg_cuentas.denominacion ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon'),array('0'=>'total'),array('0'=>'programatica','1'=>'cueprecon'));
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto_enmohca
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_presupuesto_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		SUM(sno_salida.valsal) as total, ".
				"       SUBSTR(sno_concepto.codpro,1,25) AS proy1, ".
       			"		SUBSTR(sno_concepto.codpro,26,25) AS proy2, ".
				"		SUBSTR(sno_concepto.codpro,51,25) AS proy3, ".
				"		SUBSTR(sno_concepto.codpro,76,25) AS proy4, ".
				"		SUBSTR(sno_concepto.codpro,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		SUM(sno_salida.valsal) as total, ".
				"		SUBSTR(sno_unidadadmin.codprouniadm,1,25) AS proy1, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,26,25) AS proy2, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,51,25) AS proy3, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,76,25) AS proy4, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_presupuesto_proyecto_dt();
			$this->DS->group_by(array('0'=>'proy1','1'=>'proy2','2'=>'proy3','3'=>'proy4','4'=>'proy5','5'=>'cueprepatcon'),array('0'=>'total'),'cueprepatcon');
			$ls_contar=$this->DS->getRowCount("programatica");
			if ($ls_contar>0)
			{
				$this->DS->sortData('programatica');
			}
		}
		return $lb_valido;
	}// end function uf_contableaportes_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_presupuesto_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableaportes_presupuesto_proyecto_dt 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos de aportes por proyecto
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
			case "INFORMIX":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS FLOAT),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT MAX(sno_proyecto.estproproy) AS estproproy, sno_proyecto.estcla, spg_cuentas.spg_cuenta, ".
				"		sum(sno_salida.valsal) AS total, MAX(sno_concepto.codprov) AS codprov, ".$ls_cadena." AS montoparcial, ".
				"		MAX(sno_concepto.cedben) AS cedben, sno_concepto.codconc, sno_proyecto.codproy, sno_proyectopersonal.codper, ".
				"		MAX(spg_cuentas.denominacion) AS denominacion, MAX(sno_proyectopersonal.pordiames) as pordiames, ".
				"		MAX(SUBSTR(sno_proyecto.estproproy,1,25)) AS proy1, ".
          		"		MAX(SUBSTR(sno_proyecto.estproproy,26,25)) AS proy2, ".
          		"		MAX(SUBSTR(sno_proyecto.estproproy,51,25)) AS proy3, ".
          		"		MAX(SUBSTR(sno_proyecto.estproproy,76,25)) AS proy4, ".
          		"		MAX(SUBSTR(sno_proyecto.estproproy,101,25)) AS proy5 ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyecto.codproy, spg_cuentas.spg_cuenta, sno_concepto.codconc,".
				"          sno_proyecto.estcla ".
				" ORDER BY sno_proyectopersonal.codper, sno_proyecto.codproy, spg_cuentas.spg_cuenta, sno_concepto.codconc ";
		$rs_data=$this->io_sql->select($ls_sql); 
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_estclaproyant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_conceptoant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=round($row["montoparcial"],3);
				$li_total=round($row["total"],3);
				$ls_estproproy=$row["estproproy"];
				$ls_estclaproy=$row["estcla"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				$ls_proy1=$row["proy1"];
				$ls_proy2=$row["proy2"];
				$ls_proy3=$row["proy3"];
				$ls_proy4=$row["proy4"];
				$ls_proy5=$row["proy5"];
				if(($ls_codper!=$ls_codant)||(($ls_spgcuenta!=$ls_cuentaant)&&($ls_codconc!=$ls_conceptoant)))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiames<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("estcla",$ls_estclaproyant);
							$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("proy1",$ls_proy1);
							$this->DS->insertRow("proy2",$ls_proy2);
							$this->DS->insertRow("proy3",$ls_proy3);
							$this->DS->insertRow("proy4",$ls_proy4);
							$this->DS->insertRow("proy5",$ls_proy5);
						}
					}
					$li_acumulado=round($row["montoparcial"],3);
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$ls_conceptoant=$ls_codconc;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("estcla",$ls_estclaproy);
				$this->DS->insertRow("cueprepatcon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("proy1",$ls_proy1);
				$this->DS->insertRow("proy2",$ls_proy2);
				$this->DS->insertRow("proy3",$ls_proy3);
				$this->DS->insertRow("proy4",$ls_proy4);
				$this->DS->insertRow("proy5",$ls_proy5);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiames<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("estcla",$ls_estclaproyant);
				$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("proy1",$ls_proy1);
				$this->DS->insertRow("proy2",$ls_proy2);
				$this->DS->insertRow("proy3",$ls_proy3);
				$this->DS->insertRow("proy4",$ls_proy4);
				$this->DS->insertRow("proy5",$ls_proy5);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contableaportes_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_contable_proyecto
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_modoaporte=$this->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
				$li_genrecapo=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I"),1,"0");
				break;
			
			case 1: // La contabilización es por nómina
				$ls_modoaporte=trim($_SESSION["la_nomina"]["conaponom"]);
				$li_genrecapo=str_pad(trim($_SESSION["la_nomina"]["recdocapo"]),1,"0");
				break;
		}
		
			 $ls_sql=" SELECT  cuenta,  denoconta, operacion, total    ".
					 "    FROM contableaportes_contable_proyecto       ".
					 "   WHERE codemp='".$this->ls_codemp."'           ".
					 "     AND codnom='".$this->ls_codnom."'           ".
					 "     AND codperi='".$this->ls_peractnom."'       ".
					 "  UNION                                          ".
					 " SELECT cuenta,  denoconta, operacion, total     ".
					 "	 FROM contableaportes_contable_proyecto_intcom ".
					  "   WHERE codemp='".$this->ls_codemp."'           ".
					 "     AND codnom='".$this->ls_codnom."'            ".
					 "     AND codperi='".$this->ls_peractnom."'        ";
		if ($ls_estctaalt=='1')
		{
			$ls_scctaprov='rpc_proveedor.sc_cuentarecdoc';
			$ls_scctaben='rpc_beneficiario.sc_cuentarecdoc';
		}
		else
		{
			$ls_scctaprov='rpc_proveedor.sc_cuenta';
			$ls_scctaben='rpc_beneficiario.sc_cuenta';
		}
		if(($ls_modoaporte=="OC")&&($li_genrecapo=="1"))
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, rpc_proveedor ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_concepto.codprov <> '----------' ".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"	AND sno_concepto.codemp = rpc_proveedor.codemp ".
					"	AND sno_concepto.codprov = rpc_proveedor.cod_pro ".
					"   AND scg_cuentas.codemp = rpc_proveedor.codemp ".
					"   AND scg_cuentas.sc_cuenta = ".$ls_scctaprov." ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, rpc_beneficiario ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C' ".
					"   AND sno_concepto.cedben <> '----------' ".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"	AND sno_concepto.codemp = rpc_beneficiario.codemp ".
					"	AND sno_concepto.cedben = rpc_beneficiario.ced_bene ".
					"   AND scg_cuentas.codemp = rpc_beneficiario.codemp ".
					"   AND scg_cuentas.sc_cuenta = ".$ls_scctaben." ".
					" GROUP BY scg_cuentas.sc_cuenta "; //print $ls_sql;
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'H' as operacion, sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"   AND scg_cuentas.codemp = sno_concepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_concepto.cueconpatcon ".
					" GROUP BY scg_cuentas.sc_cuenta ".
					" ORDER BY operacion, cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}	
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_contable_proyecto_dt();
			$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');	
			
			$ls_contar=$this->DS_detalle->getRowCount("operacion");
			if ($ls_contar>0)
			{
				$this->DS_detalle->sortData('operacion');
			}
			
		}	
		return $lb_valido;
	}// end function uf_contableaportes_contable_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_contable_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableaportes_contable_proyecto_dt 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los aportes
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);		
				
						$ls_sql=" SELECT sc_cuenta, operacion, total, montoparcial, codprov, cedben,  ". 
								"		   codconc, codper, codproy,  denoconta,pordiames             ".
								"	FROM contableaportes_contable_proyecto_dt                         ".
								"  WHERE codemp='".$this->ls_codemp."'                                ".
								"	 AND codnom='".$this->ls_codnom."'                                ".
								"	 AND codperi='".$this->ls_peractnom."'                            ".
								"	UNION                                                             ".
								"  SELECT sc_cuenta, operacion, total, montoparcial, codprov, cedben, ".
								"		   codconc, codper, codproy,  denoconta,pordiames             ".
								"	 FROM contableaportes_contable_proyecto_dt_intcom                 ".
								"   WHERE codemp='".$this->ls_codemp."'                               ".
								"	 AND codnom='".$this->ls_codnom."'                                ".
								"	 AND codperi='".$this->ls_peractnom."'                            ";                       
						
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_operacionant="";
			$ls_denominacionant="";
			$ls_codconcant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["sc_cuenta"];
				$ls_operacion=$row["operacion"];
				$ls_denominacion=$row["denoconta"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS_detalle->insertRow("operacion",$ls_operacionant);
							$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
							$this->DS_detalle->insertRow("total",$li_montoparcial);
							$this->DS_detalle->insertRow("denoconta",$ls_denominacionant);
						}
					}
					$li_acumulado=round($row["montoparcial"],3);
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_totalant=$li_total;
					$li_pordiamesant=$li_pordiames;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS_detalle->insertRow("operacion",$ls_operacion);
				$this->DS_detalle->insertRow("cuenta",$ls_cuenta);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denoconta",$ls_denominacion);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS_detalle->insertRow("operacion",$ls_operacionant);
				$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denoconta",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
		}	
		return  $lb_valido;    
	}// end function uf_contableaportes_contable_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_presupuesto_proyecto
		//         Access: public (desde la clase sigesp_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A , que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, ".
				" 		SUBSTR(sno_concepto.codpro,1,25) AS proy1, ".
       			"		SUBSTR(sno_concepto.codpro,26,25) AS proy2, ".
       			"		SUBSTR(sno_concepto.codpro,51,25) AS proy3, ".
       			"		SUBSTR(sno_concepto.codpro,76,25) AS proy4, ".
       			"		SUBSTR(sno_concepto.codpro,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro, sno_concepto.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, ".
				" 		SUBSTR(sno_unidadadmin.codprouniadm,1,25) AS proy1, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,26,25) AS proy2, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,51,25) AS proy3, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,76,25) AS proy4, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total, ".
				" 		SUBSTR(sno_concepto.codpro,1,25) AS proy1, ".
       			"		SUBSTR(sno_concepto.codpro,26,25) AS proy2, ".
       			"		SUBSTR(sno_concepto.codpro,51,25) AS proy3, ".
       			"		SUBSTR(sno_concepto.codpro,76,25) AS proy4, ".
       			"		SUBSTR(sno_concepto.codpro,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,  sno_concepto.estcla,spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica,  sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total, ".
				" 		SUBSTR(sno_unidadadmin.codprouniadm,1,25) AS proy1, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,26,25) AS proy2, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,51,25) AS proy3, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,76,25) AS proy4, ".
       			"		SUBSTR(sno_unidadadmin.codprouniadm,101,25) AS proy5 ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta ".
				" ORDER BY programatica, cueprecon"; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_presupuesto_proyecto_dt();
			$this->DS->group_by(array('0'=>'proy1','1'=>'proy2','2'=>'proy3','3'=>'proy4','4'=>'proy5','5'=>'estcla','6'=>'cueprecon'),array('0'=>'total'),'total');		
			$ls_contar=$this->DS->getRowCount("programatica");
			if ($ls_contar>0)
			{
				$this->DS->sortData('programatica');
			}
		}
		return $lb_valido;
	}// end function uf_contableconceptos_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_presupuesto_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_presupuesto_proyecto_dt 
		//	    Arguments:
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos que son por proyectos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
			case "INFORMIX":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS FLOAT),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D
		$ls_sql="SELECT sno_proyectopersonal.codper, sno_proyectopersonal.codproy, MAX(sno_proyecto.estproproy) AS estproproy, MAX(sno_proyecto.estcla) AS estcla, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, sum(sno_salida.valsal) AS total, MAX(spg_cuentas.denominacion) AS denominacion, MAX(sno_proyectopersonal.pordiames) AS pordiames, ".
				" 		MAX(SUBSTR(sno_proyecto.estproproy,1,25)) AS proy1, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,26,25)) AS proy2, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,51,25)) AS proy3, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,76,25)) AS proy4, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,101,25)) AS proy5 ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyectopersonal.codproy,  spg_cuentas.spg_cuenta ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_proyectopersonal.codper, sno_proyectopersonal.codproy, MAX(sno_proyecto.estproproy) AS estproproy, MAX(sno_proyecto.estcla) AS estcla, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, sum(sno_salida.valsal) AS total, MAX(spg_cuentas.denominacion) AS denominacion, MAX(sno_proyectopersonal.pordiames) AS pordiames, ".
				" 		MAX(SUBSTR(sno_proyecto.estproproy,1,25)) AS proy1, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,26,25)) AS proy2, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,51,25)) AS proy3, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,76,25)) AS proy4, ".
       			"		MAX(SUBSTR(sno_proyecto.estproproy,101,25)) AS proy5 ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyectopersonal.codproy, spg_cuentas.spg_cuenta ".
				" ORDER BY codper, spg_cuenta, codproy "; //print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_estclaproyant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_codproyant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$li_montoparcial=round($row["montoparcial"],3);
				$li_total=round($row["total"],3);
				$ls_estproproy=$row["estproproy"];
				$ls_estclaproy=$row["estcla"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				$ls_codproy=$row["codproy"];
				$ls_proy1=$row["proy1"];
				$ls_proy2=$row["proy2"];
				$ls_proy3=$row["proy3"];
				$ls_proy4=$row["proy4"];
				$ls_proy5=$row["proy5"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("estcla",$ls_estclaproyant);
							$this->DS->insertRow("cueprecon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("proy1",$ls_proy1);
							$this->DS->insertRow("proy2",$ls_proy2);
							$this->DS->insertRow("proy3",$ls_proy3);
							$this->DS->insertRow("proy4",$ls_proy4);
							$this->DS->insertRow("proy5",$ls_proy5);
						}
					}
					$li_montoparcial=round($row["montoparcial"],3);
					$li_acumulado=$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_pordiamesant=$li_pordiames;
					$ls_codant=$ls_codper;
					$ls_codproyant=$ls_codproy;
					$ls_denominacionant=$ls_denominacion;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("estcla",$ls_estclaproy);
				$this->DS->insertRow("cueprecon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("proy1",$ls_proy1);
				$this->DS->insertRow("proy2",$ls_proy2);
				$this->DS->insertRow("proy3",$ls_proy3);
				$this->DS->insertRow("proy4",$ls_proy4);
				$this->DS->insertRow("proy5",$ls_proy5);
				
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("estcla",$ls_estclaproyant);
				$this->DS->insertRow("cueprecon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("proy1",$ls_proy1);
				$this->DS->insertRow("proy2",$ls_proy2);
				$this->DS->insertRow("proy3",$ls_proy3);
				$this->DS->insertRow("proy4",$ls_proy4);
				$this->DS->insertRow("proy5",$ls_proy5);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;    
	}// end function uf_contableconceptos_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable_proyecto 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_group="  GROUP BY scg_cuentas.sc_cuenta ";
		$li_parametros=trim($this->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I"));
		switch($li_parametros)
		{
			case 0: // La contabilización es global
				$ls_cuentapasivo=trim($this->uf_select_config("SNO","CONFIG","CTA.CONTA","-------------------------","C"));
				$ls_modo=trim($this->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C"));
				$li_genrecdoc=str_pad($this->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I"),1,"0");
				$ls_estctaalt=trim($this->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));
				break;
				
			case 1: // La contabilización es por nómina
				$ls_cuentapasivo=trim($_SESSION["la_nomina"]["cueconnom"]);
				$ls_modo=trim($_SESSION["la_nomina"]["consulnom"]);
				$li_genrecdoc=str_pad(trim($_SESSION["la_nomina"]["recdocnom"]),1,"0");
				$ls_estctaalt=trim($_SESSION["la_nomina"]["estctaalt"]);
				break;
		}
		if ($ls_estctaalt=='1')
		{
			$ls_scctaprov='rpc_proveedor.sc_cuentarecdoc';
			$ls_scctaben='rpc_beneficiario.sc_cuentarecdoc';
		}
		else
		{
			$ls_scctaprov='rpc_proveedor.sc_cuenta';
			$ls_scctaben='rpc_beneficiario.sc_cuenta';
		}
		// Buscamos todas aquellas cuentas contables que estan ligadas a las presupuestarias de los conceptos A y D, que se 
		// integran directamente con presupuesto, estas van por el debe de contabilidad
				$ls_sql="  SELECT cuenta, denominacion, operacion, total                ".
				        "    FROM contableconceptos_contable_proyecto                   ".
						"	WHERE  codemp='".$this->ls_codemp."'                        ". 
						"	   AND codnom='".$this->ls_codnom."'                        ".
						"	   AND codperi='".$this->ls_peractnom."'                    ".
						"	 UNION                                                      ".
						"	SELECT cuenta, denominacion, operacion, total               ".
						"     FROM contableconceptos_contable_proyecto_intercom         ".
						"	WHERE  codemp='".$this->ls_codemp."'                        ". 
						"	   AND codnom='".$this->ls_codnom."'                        ".
						"	   AND codperi='".$this->ls_peractnom."'                    ";
		if($ls_modo=="OC") // Si el modo de contabilizar la nómina es Compromete y Causa tomamos la cuenta pasivo de la nómina.
		{
			if($li_genrecdoc=="0") // No se genera Recepción de Documentos
			{
				// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
				switch($_SESSION["ls_gestor"])
				{
					case "MYSQLT":
						$ls_cadena="CONVERT('".$ls_cuentapasivo."' USING utf8) as cuenta";
						break;
					case "POSTGRES":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
					case "INFORMIX":
						$ls_cadena="CAST('".$ls_cuentapasivo."' AS char(25)) as cuenta";
						break;					
				}
				$ls_sql=$ls_sql." UNION ".
						"SELECT ".$ls_cadena.", MAX(scg_cuentas.denominacion) AS denominacion, ".
						"		CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, sno_banco, scg_cuentas ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1) ".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND scg_cuentas.sc_cuenta = '".$ls_cuentapasivo."' ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_salida.codemp = sno_banco.codemp ".
						"   AND sno_salida.codnom = sno_banco.codnom ".
						"   AND sno_salida.codperi = sno_banco.codperi ".
						"   AND sno_personalnomina.codemp = sno_banco.codemp ".
						"   AND sno_personalnomina.codban = sno_banco.codban ".
						"   AND scg_cuentas.codemp = sno_banco.codemp ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			else // Se genera Recepción de documentos
			{
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, scg_cuentas, sno_nomina, rpc_proveedor ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_nomina.descomnom = 'P'".
						"   AND sno_nomina.codemp = sno_salida.codemp ".
						"   AND sno_nomina.codnom = sno_salida.codnom ".
						"   AND sno_nomina.peractnom = sno_salida.codperi ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_nomina.codemp = rpc_proveedor.codemp ".
						"   AND sno_nomina.codpronom = rpc_proveedor.cod_pro ".
						"   AND rpc_proveedor.codemp = scg_cuentas.codemp ".
						"   AND ".$ls_scctaprov." = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
						"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
						"  FROM sno_personalnomina, sno_salida, scg_cuentas, sno_nomina, rpc_beneficiario ".
						" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
						"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
						"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
						"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
						"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
						"   AND sno_salida.valsal <> 0 ".
						"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1)".
						"   AND sno_personalnomina.pagefeper = 0 ".
						"   AND scg_cuentas.status = 'C'".
						"   AND sno_nomina.descomnom = 'B'".
						"   AND sno_nomina.codemp = sno_salida.codemp ".
						"   AND sno_nomina.codnom = sno_salida.codnom ".
						"   AND sno_nomina.peractnom = sno_salida.codperi ".
						"   AND sno_personalnomina.codemp = sno_salida.codemp ".
						"   AND sno_personalnomina.codnom = sno_salida.codnom ".
						"   AND sno_personalnomina.codper = sno_salida.codper ".
						"   AND sno_nomina.codemp = rpc_beneficiario.codemp ".
						"   AND sno_nomina.codbennom = rpc_beneficiario.ced_bene ".
						"   AND rpc_beneficiario.codemp = scg_cuentas.codemp ".
						"   AND ".$ls_scctaben." = scg_cuentas.sc_cuenta ".
						" GROUP BY scg_cuentas.sc_cuenta ";
			}
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
					"		CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND sno_personalnomina.pagbanper = 0 ".
					"   AND sno_personalnomina.pagtaqper = 0 ".
					"   AND sno_personalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.sc_cuenta = '".$ls_cuentapasivo."' ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND scg_cuentas.codemp = sno_personalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_personalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			// Buscamos todas aquellas cuentas contables de los conceptos A y D, estas van por el haber de contabilidad
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
					"		CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_banco, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND (sno_personalnomina.pagbanper = 1 OR sno_personalnomina.pagtaqper = 1) ".
					"   AND sno_personalnomina.pagefeper = 0 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_banco.codemp ".
					"   AND sno_salida.codnom = sno_banco.codnom ".
					"   AND sno_salida.codperi = sno_banco.codperi ".
					"   AND sno_personalnomina.codemp = sno_banco.codemp ".
					"   AND sno_personalnomina.codban = sno_banco.codban ".
					"   AND scg_cuentas.codemp = sno_banco.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_banco.codcuecon ".
					" GROUP BY scg_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) AS denominacion, ".
					"		CAST('H' AS char(1)) as operacion, -sum(sno_salida.valsal) as total ".
					"  FROM sno_personalnomina, sno_salida, scg_cuentas ".
					" WHERE sno_salida.codemp = '".$this->ls_codemp."' ".
					"   AND sno_salida.codnom = '".$this->ls_codnom."' ".
					"   AND sno_salida.codperi = '".$this->ls_peractnom."' ".
					"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1' OR sno_salida.tipsal = 'D' ".
					"    OR  sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
					"   AND sno_salida.valsal <> 0".
					"   AND sno_personalnomina.pagbanper = 0 ".
					"   AND sno_personalnomina.pagtaqper = 0 ".
					"   AND sno_personalnomina.pagefeper = 1 ".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND scg_cuentas.codemp = sno_personalnomina.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_personalnomina.cueaboper ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_contable_proyecto_dt();
			$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),'total');		
			$ls_contar=$this->DS_detalle->getRowCount("operacion");
			if ($ls_contar>0)
			{
				$this->DS_detalle->sortData('operacion');
			}
		}
		return  $lb_valido;    
	}// end function uf_contableconceptos_contable_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_contable_proyecto_dt()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_contable_proyecto_dt 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos por proyecto
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		
					$ls_sql=" SELECT cuenta, operacion, total, montoparcial,            ".
							"	     codper, codproy, denominacion, pordiames, codconc  ".
							"    FROM contableconceptos_contable_proyecto_dt            ".
							"   WHERE codemp='".$this->ls_codemp."'                     ".
							"	  AND codnom='".$this->ls_codnom."'                     ".
							"	  AND codperi='".$this->ls_peractnom."'                 ".
							"	UNION                                                   ".
							" SELECT cuenta, operacion, total, montoparcial,            ".
							"	     codper, codproy, denominacion, pordiames, codconc  ".
							"	FROM contableconceptos_contable_proyecto_dt_int         ".
							"   WHERE codemp='".$this->ls_codemp."'                     ".
							"	  AND codnom='".$this->ls_codnom."'                     ".
							"	  AND codperi='".$this->ls_peractnom."'                 ";						
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_contable_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_cuentaant="";
			$ls_codconcant="";
			$ls_operacionant="";
			$ls_denominacionant="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_cuenta=$row["cuenta"];
				$ls_operacion=$row["operacion"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||($ls_codconc!=$ls_codconcant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS_detalle->insertRow("operacion",$ls_operacionant);
							$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
							$this->DS_detalle->insertRow("total",$li_montoparcial);
							$this->DS_detalle->insertRow("denominacion",$ls_denominacionant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$li_totalant=$li_total;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_operacionant=$ls_operacion;
					$ls_cuentaant=$ls_cuenta;
					$ls_codconcant=$ls_codconc;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
				}
				$this->DS_detalle->insertRow("operacion",$ls_operacion);
				$this->DS_detalle->insertRow("cuenta",$ls_cuenta);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denominacion",$ls_denominacion);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS_detalle->insertRow("operacion",$ls_operacionant);
				$this->DS_detalle->insertRow("cuenta",$ls_cuentaant);
				$this->DS_detalle->insertRow("total",$li_montoparcial);
				$this->DS_detalle->insertRow("denominacion",$ls_denominacionant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;    
	}// end function uf_contableconceptos_contable_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableingresos_ingreso()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableingresos_ingreso
		//         Access: public (desde la clase sigesp_sno_r_contableingresos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas de ingresos
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 25/03/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		$this->io_sql=new class_sql($this->io_conexion);
		if ($ls_estpreing==0) ///no se maneja estructuras con los ingresos
		{
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
			$ls_sql="SELECT spi_cuentas.spi_cuenta AS cuenta, MAX(spi_cuentas.denominacion) AS denominacion, ".
					"		sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
					"   AND sno_concepto.intingcon = '1'".
					"   AND spi_cuentas.status = 'C' ".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"   AND spi_cuentas.codemp = sno_concepto.codemp ".
					"   AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta ".
					" GROUP BY spi_cuentas.spi_cuenta ";
		}
		else
		{
			$ls_sql=" SELECT spi_cuentas.spi_cuenta AS cuenta,                             ".
					"	     MAX(spi_cuentas.denominacion) AS denominacion,                ".
					"	     sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total, ".        
					"	     spi_cuentas_estructuras.codestpro1,                           ".
					"	 	 spi_cuentas_estructuras.codestpro2,                           ".
					"	 	 spi_cuentas_estructuras.codestpro3,                           ".
					"	 	 spi_cuentas_estructuras.codestpro4,                           ". 
					"	 	 spi_cuentas_estructuras.codestpro5                            ". 
				  	"  FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas,     ".
					"       spi_cuentas_estructuras, sno_unidadadmin                       ".
				 	"  WHERE sno_salida.codemp='".$this->ls_codemp."'                      ".
					"    AND sno_salida.codnom='".$this->ls_codnom."'                      ".
					"    AND sno_salida.codperi='".$this->ls_peractnom."'                  ".
					"    AND sno_salida.valsal <> 0                                        ".
					"    AND (sno_salida.tipsal = 'D'                                      ".
					"     OR sno_salida.tipsal = 'V2'                                      ". 
					"     OR sno_salida.tipsal = 'W2'                                      ".
					"     OR sno_salida.tipsal = 'P1'                                      ". 
					"     OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )        ".
					"    AND sno_concepto.intingcon = '1'                                  ".
					"    AND spi_cuentas.status = 'C'                                      ".  
					"    AND sno_personalnomina.codemp = sno_salida.codemp                 ".
					"    AND sno_personalnomina.codnom = sno_salida.codnom                 ".
					"    AND sno_personalnomina.codper = sno_salida.codper                 ".
					"    AND sno_salida.codemp = sno_concepto.codemp                       ".
					"    AND sno_salida.codnom = sno_concepto.codnom                       ". 
					"    AND sno_salida.codconc = sno_concepto.codconc                     ".
					"    AND sno_personalnomina.codemp = sno_unidadadmin.codemp            ". 
					"    AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					"    AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm      ".
					"    AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm      ".
					"    AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm      ".
					"    AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm      ".
					"    AND spi_cuentas.codemp = sno_concepto.codemp                      ".
					"    AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta              ".  
					"    AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp             ". 
					"    AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta    ".
					"    AND substr(sno_concepto.codpro,1,25)  = spi_cuentas_estructuras.codestpro1 ".
					"    AND substr(sno_concepto.codpro,26,25) = spi_cuentas_estructuras.codestpro2 ".
					"    AND substr(sno_concepto.codpro,51,25) = spi_cuentas_estructuras.codestpro3 ".
					"    AND substr(sno_concepto.codpro,76,25) = spi_cuentas_estructuras.codestpro4 ".
					"    AND substr(sno_concepto.codpro,101,25) = spi_cuentas_estructuras.codestpro5 ".
					"    AND sno_concepto.estcla = spi_cuentas_estructuras.estcla             ".
					"  GROUP BY spi_cuentas.spi_cuenta,spi_cuentas_estructuras.codestpro1,    ".
					"		    spi_cuentas_estructuras.codestpro2, spi_cuentas_estructuras.codestpro3, ".
					" 		    spi_cuentas_estructuras.codestpro4, spi_cuentas_estructuras.codestpro5  ".
				    "    UNION                                                                          ".
				    "  SELECT spi_cuentas.spi_cuenta AS cuenta,                                         ".
					"	      MAX(spi_cuentas.denominacion) AS denominacion,                            ".
					" 	      sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total,             ".
					" 	      spi_cuentas_estructuras.codestpro1,                                       ".
					"	      spi_cuentas_estructuras.codestpro2,                                       ".
					"  	      spi_cuentas_estructuras.codestpro3,                                       ".
					"	      spi_cuentas_estructuras.codestpro4,                                       ".
					"	      spi_cuentas_estructuras.codestpro5                                        ".
					"   FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas,                 ".
					"        spi_cuentas_estructuras, sno_unidadadmin                                   ".
					"  WHERE sno_salida.codemp='".$this->ls_codemp."'                                   ".
					"    AND sno_salida.codnom='".$this->ls_codnom."'                                   ".
					"    AND sno_salida.codperi='".$this->ls_peractnom."'                               ". 
					"    AND sno_salida.valsal <> 0                                                     ".
					"    AND (sno_salida.tipsal = 'D'                                                   ".     
					"     OR sno_salida.tipsal = 'V2'                                                   ".
					"     OR sno_salida.tipsal = 'W2'                                                   ".
					"     OR sno_salida.tipsal = 'P1'                                                   ". 
					"     OR sno_salida.tipsal = 'V3'                                                   ".
					"     OR sno_salida.tipsal = 'W3' )                                                 ".
					"    AND sno_concepto.intingcon = '1'                                               ". 
					"    AND spi_cuentas.status = 'C'                                                   ".
					"    AND sno_personalnomina.codemp = sno_salida.codemp                              ".
					"    AND sno_personalnomina.codnom = sno_salida.codnom                              ".
					"    AND sno_personalnomina.codper = sno_salida.codper                              ".
					"    AND sno_salida.codemp = sno_concepto.codemp                                    ".
					"    AND sno_salida.codnom = sno_concepto.codnom                                    ".  
					"    AND sno_salida.codconc = sno_concepto.codconc                                  ".
					"    AND sno_personalnomina.codemp = sno_unidadadmin.codemp                         ".
					"    AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm             ".
					"    AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm                   ".
					"    AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm                   ".
					"    AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm                   ".
					"    AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm                   ".
					"    AND spi_cuentas.codemp = sno_concepto.codemp                                   ".
					"    AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta                           ".
					"    AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp                          ".
					"    AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta                 ".
					"    AND substr(sno_unidadadmin.codprouniadm,1,25) =  spi_cuentas_estructuras.codestpro1   ". 
					"    AND substr(sno_unidadadmin.codprouniadm,26,25) = spi_cuentas_estructuras.codestpro2   ".
					"    AND substr(sno_unidadadmin.codprouniadm,51,25) = spi_cuentas_estructuras.codestpro3   ".
					"    AND substr(sno_unidadadmin.codprouniadm,76,25) = spi_cuentas_estructuras.codestpro4   ".
					"    AND substr(sno_unidadadmin.codprouniadm,101,25) = spi_cuentas_estructuras.codestpro5  ".
					"    AND sno_unidadadmin.estcla = spi_cuentas_estructuras.estcla        ".
					"   GROUP BY spi_cuentas.spi_cuenta,spi_cuentas_estructuras.codestpro1, ".
					"		     spi_cuentas_estructuras.codestpro2,  spi_cuentas_estructuras.codestpro3, ".
					" 		     spi_cuentas_estructuras.codestpro4, spi_cuentas_estructuras.codestpro5	  ";
		
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableingresos_ingreso ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableingresos_ingreso
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableingresos_contable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableingresos_contable
		//         Access: public (desde la clase sigesp_sno_r_contableingresos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas contables que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		if ($ls_estpreing==0)
		{
			// Buscamos todas aquellas cuentas contables que estan ligadas a las de ingreso de los conceptos que se 
			// integran directamente con presupuesto estas van por el haber de contabilidad
			$ls_sql="SELECT spi_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion, 'H' as operacion, ".
					"		sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas, scg_cuentas ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
					"   AND sno_concepto.intingcon = '1'".
					"   AND spi_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"   AND spi_cuentas.codemp = sno_concepto.codemp ".
					"   AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta ".
					"   AND spi_cuentas.sc_cuenta = scg_cuentas.sc_cuenta".
					"   GROUP BY spi_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta, 'D' as operacion, ".
					"		sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total ".
					"  FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas ".
					" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
					"   AND sno_salida.codnom='".$this->ls_codnom."' ".
					"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
					"   AND sno_salida.valsal <> 0 ".
					"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3' )".
					"   AND sno_concepto.intingcon = '1'".
					"   AND scg_cuentas.status = 'C'".
					"   AND sno_personalnomina.codemp = sno_salida.codemp ".
					"   AND sno_personalnomina.codnom = sno_salida.codnom ".
					"   AND sno_personalnomina.codper = sno_salida.codper ".
					"   AND sno_salida.codemp = sno_concepto.codemp ".
					"   AND sno_salida.codnom = sno_concepto.codnom ".
					"   AND sno_salida.codconc = sno_concepto.codconc ".
					"   AND scg_cuentas.codemp = sno_concepto.codemp ".
					"   AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon  ".
					"   GROUP BY scg_cuentas.sc_cuenta ";
		}
		else
		{
			$ls_sql=" SELECT spi_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion,  ".
					"	    'H' as operacion, sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total    ".
					"   FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas, scg_cuentas,          ".
					"        spi_cuentas_estructuras, sno_unidadadmin                                         ".
					"  WHERE sno_salida.codemp='".$this->ls_codemp."'                                         ".
					"    AND sno_salida.codnom='".$this->ls_codnom."'                                         ".
					"    AND sno_salida.codperi='".$this->ls_peractnom."'  ".
					"    AND sno_salida.valsal <> 0  ".
					"    AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' ".
					"         OR sno_salida.tipsal = 'W2'  ".
					"         OR sno_salida.tipsal = 'P1'  ".
					"         OR sno_salida.tipsal = 'V3'  ".
					"         OR sno_salida.tipsal = 'W3') ". 
					"    AND sno_concepto.intingcon = '1'  ".
					"    AND spi_cuentas.status = 'C'      ".
					"    AND sno_personalnomina.codemp = sno_salida.codemp  ".
					"    AND sno_personalnomina.codnom = sno_salida.codnom  ".
					"    AND sno_personalnomina.codper = sno_salida.codper  ".
					"    AND sno_salida.codemp = sno_concepto.codemp        ". 
					"    AND sno_salida.codnom = sno_concepto.codnom        ". 
					"    AND sno_salida.codconc = sno_concepto.codconc      ".  
					"    AND sno_personalnomina.codemp = sno_unidadadmin.codemp ". 
					"    AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm  ".
					"    AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm  ".
					"    AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm  ".
					"    AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm  ".
					"    AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm  ".
					"    AND spi_cuentas.codemp = sno_concepto.codemp  ".
					"    AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta   ".
					"    AND spi_cuentas.sc_cuenta = scg_cuentas.sc_cuenta      ".
					"    AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp  ".
					"    AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta  ".
					"    AND substr(sno_concepto.codpro,1,25)  = spi_cuentas_estructuras.codestpro1  ".
					"    AND substr(sno_concepto.codpro,26,25) = spi_cuentas_estructuras.codestpro2  ".
					"    AND substr(sno_concepto.codpro,51,25) = spi_cuentas_estructuras.codestpro3  ".
					"    AND substr(sno_concepto.codpro,76,25) = spi_cuentas_estructuras.codestpro4  ".
					"    AND substr(sno_concepto.codpro,101,25) = spi_cuentas_estructuras.codestpro5 ".
					"    AND sno_concepto.estcla = spi_cuentas_estructuras.estcla ".
					"    GROUP BY spi_cuentas.sc_cuenta ";
			$ls_sql=$ls_sql." UNION ".
					"    SELECT spi_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denominacion,  ".
					"	        'H' as operacion, sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total   ".
					"      FROM sno_personalnomina, sno_salida, sno_concepto, spi_cuentas, scg_cuentas,          ".
					"           spi_cuentas_estructuras, sno_unidadadmin                                         ".
					"     WHERE sno_salida.codemp='".$this->ls_codemp."'                                         ".
					"       AND sno_salida.codnom='".$this->ls_codnom."'                                         ".
					"       AND sno_salida.codperi='".$this->ls_peractnom."'          ".
					"       AND sno_salida.valsal <> 0                                ".
					"       AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2'  ".
					"            OR sno_salida.tipsal = 'W2'  ".
					"            OR sno_salida.tipsal = 'P1'  ".
					"            OR sno_salida.tipsal = 'V3'  ".
					"            OR sno_salida.tipsal = 'W3') ". 
					"       AND sno_concepto.intingcon = '1'  ".
					"       AND spi_cuentas.status = 'C'      ".
					"       AND sno_personalnomina.codemp = sno_salida.codemp  ".
					"       AND sno_personalnomina.codnom = sno_salida.codnom  ".
					"       AND sno_personalnomina.codper = sno_salida.codper  ".
					"       AND sno_salida.codemp = sno_concepto.codemp  ".
					"       AND sno_salida.codnom = sno_concepto.codnom  ".
					" 	    AND sno_salida.codconc = sno_concepto.codconc  ".
					" 	    AND sno_personalnomina.codemp = sno_unidadadmin.codemp  ".
					" 	    AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm  ".
					" 	    AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm        ".
					"	    AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm        ".
					"	    AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm        ".
					"	    AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm        ".
					"	    AND spi_cuentas.codemp = sno_concepto.codemp                        ".
					"	    AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta                ".
					"	    AND spi_cuentas.sc_cuenta = scg_cuentas.sc_cuenta                   ".
					"	    AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp               ".
					"	    AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta       ".
					"	    AND substr(sno_unidadadmin.codprouniadm,1,25) =  spi_cuentas_estructuras.codestpro1 ".
					"	    AND substr(sno_unidadadmin.codprouniadm,26,25) = spi_cuentas_estructuras.codestpro2 ".
					"	    AND substr(sno_unidadadmin.codprouniadm,51,25) = spi_cuentas_estructuras.codestpro3 ".
					"	    AND substr(sno_unidadadmin.codprouniadm,76,25) = spi_cuentas_estructuras.codestpro4 ".
					"	    AND substr(sno_unidadadmin.codprouniadm,101,25) = spi_cuentas_estructuras.codestpro5 ".
					"	    AND sno_unidadadmin.estcla = spi_cuentas_estructuras.estcla ".
					"	  GROUP BY spi_cuentas.sc_cuenta ";
				$ls_sql=$ls_sql." UNION ".
					"     SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta,    ".
					"		     'D' as operacion, sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total  ".
					"       FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, ".
					"            spi_cuentas, spi_cuentas_estructuras, sno_unidadadmin  ".
					"     WHERE sno_salida.codemp='".$this->ls_codemp."'                                         ".
					"       AND sno_salida.codnom='".$this->ls_codnom."'                                         ".
					"       AND sno_salida.codperi='".$this->ls_peractnom."'          ".
					"		 AND sno_salida.valsal <> 0   ".
					"		 AND (sno_salida.tipsal = 'D'  ".
					"             OR sno_salida.tipsal = 'V2' ". 
					"             OR sno_salida.tipsal = 'W2' ".
					"             OR sno_salida.tipsal = 'P1' ".
					"             OR sno_salida.tipsal = 'V3' ".
					"             OR sno_salida.tipsal = 'W3')". 
					"		 AND sno_concepto.intingcon = '1' ".
					"		 AND scg_cuentas.status = 'C' ".
					"		 AND sno_personalnomina.codemp = sno_salida.codemp ".
					"		 AND sno_personalnomina.codnom = sno_salida.codnom ".
					"		 AND sno_personalnomina.codper = sno_salida.codper ".
					"		 AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					"		 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					"		 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					"		 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					"		 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					"		 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					"		 AND sno_salida.codemp = sno_concepto.codemp ".
					"		 AND sno_salida.codnom = sno_concepto.codnom ".
					"		 AND sno_salida.codconc = sno_concepto.codconc ".
					"		 AND scg_cuentas.codemp = sno_concepto.codemp ".
					"		 AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon ".
					"		 AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta  ".
					"		 AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp ".
					"		 AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta ".
					"		 AND substr(sno_concepto.codpro,1,25)  = spi_cuentas_estructuras.codestpro1 ".
					"		 AND substr(sno_concepto.codpro,26,25) = spi_cuentas_estructuras.codestpro2 ".
					"		 AND substr(sno_concepto.codpro,51,25) = spi_cuentas_estructuras.codestpro3 ".
					"		 AND substr(sno_concepto.codpro,76,25) = spi_cuentas_estructuras.codestpro4 ".
					"		 AND substr(sno_concepto.codpro,101,25) = spi_cuentas_estructuras.codestpro5 ".
					"		 AND sno_concepto.estcla = spi_cuentas_estructuras.estcla ".
					"	  GROUP BY scg_cuentas.sc_cuenta  ";
				$ls_sql=$ls_sql." UNION ".
					"     SELECT scg_cuentas.sc_cuenta as cuenta, MAX(scg_cuentas.denominacion) as denoconta,    ".
					" 		     'D' as operacion, sum((sno_salida.valsal*sno_concepto.poringcon)/100) as total  ".
					"     FROM sno_personalnomina, sno_salida, sno_concepto, scg_cuentas, ".
					"          spi_cuentas, spi_cuentas_estructuras, sno_unidadadmin  ".
					"     WHERE sno_salida.codemp='".$this->ls_codemp."'                                         ".
					"       AND sno_salida.codnom='".$this->ls_codnom."'                                         ".
					"       AND sno_salida.codperi='".$this->ls_peractnom."'          ".
					"	 AND sno_salida.valsal <> 0   ".
					"	 AND (sno_salida.tipsal = 'D' ".
					"         OR sno_salida.tipsal = 'V2' ".
					"         OR sno_salida.tipsal = 'W2' ".
					"         OR sno_salida.tipsal = 'P1' ".
					"         OR sno_salida.tipsal = 'V3' ".
					"         OR sno_salida.tipsal = 'W3') ".
					"	 AND sno_concepto.intingcon = '1' ".
					"	 AND scg_cuentas.status = 'C' ".
					"	 AND sno_personalnomina.codemp = sno_salida.codemp  ".
					"	 AND sno_personalnomina.codnom = sno_salida.codnom  ".
					"	 AND sno_personalnomina.codper = sno_salida.codper  ".
					"	 AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
					"	 AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
					"	 AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
					"	 AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
					"	 AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
					"	 AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
					"	 AND sno_salida.codemp = sno_concepto.codemp ".
					"	 AND sno_salida.codnom = sno_concepto.codnom ".
					"	 AND sno_salida.codconc = sno_concepto.codconc  ".
					"	 AND scg_cuentas.codemp = sno_concepto.codemp   ".
					"	 AND scg_cuentas.sc_cuenta = sno_concepto.cueconcon  ".
					"	 AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp ".
					"	 AND spi_cuentas.codemp = sno_concepto.codemp ".
					"	 AND spi_cuentas.spi_cuenta = sno_concepto.spi_cuenta  ".
					"	 AND spi_cuentas_estructuras.codemp=spi_cuentas.codemp ".
					"	 AND spi_cuentas_estructuras.spi_cuenta= spi_cuentas.spi_cuenta ".
					"	 AND substr(sno_unidadadmin.codprouniadm,1,25) =  spi_cuentas_estructuras.codestpro1 ".
					"	 AND substr(sno_unidadadmin.codprouniadm,26,25) = spi_cuentas_estructuras.codestpro2 ".
					"	 AND substr(sno_unidadadmin.codprouniadm,51,25) = spi_cuentas_estructuras.codestpro3 ".
					"	 AND substr(sno_unidadadmin.codprouniadm,76,25) = spi_cuentas_estructuras.codestpro4 ".
					"	 AND substr(sno_unidadadmin.codprouniadm,101,25) = spi_cuentas_estructuras.codestpro5 ".
					"	 AND sno_unidadadmin.estcla = spi_cuentas_estructuras.estcla ".
					" GROUP BY scg_cuentas.sc_cuenta ";
		
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_contable ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS_detalle->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS_detalle->group_by(array('0'=>'cuenta','1'=>'operacion'),array('0'=>'total'),array('0'=>'cuenta','1'=>'operacion'));
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableingresos_contable
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
														 $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_especifico_presupuesto
		//         Access: public (desde la clase sigesp_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 09/04/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_criterio="";
		if(!empty($as_estcla))
		{
			$ls_criterio="   AND spg_cuentas.estcla = '".$as_estcla."'".
						 "   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,25,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A , que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ".
				" ORDER BY programatica, cueprecon";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableconceptos_especifico_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto_proyecto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
																  $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableconceptos_especifico_presupuesto_proyecto
		//         Access: public (desde la clase sigesp_sno_r_contableconceptos)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A , que se integran directamente con presupuesto
		$ls_criterio="";
		if(!empty($as_estcla))
		{
			$ls_criterio="   AND spg_cuentas.estcla = '".$as_estcla."'".
						 "   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,25,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_concepto.conprocon = '0' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro, sno_concepto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_concepto.conprocon = '0' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,  sno_concepto.estcla,spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica,  sno_unidadadmin.estcla, spg_cuentas.spg_cuenta as cueprecon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"       sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ".
				" ORDER BY programatica, cueprecon"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableconceptos_especifico_presupuesto_proyecto_dt($ls_criterio);
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprecon','2'=>'codded','3'=>'codtipper'),array('0'=>'total'),array('0'=>'programatica','1'=>'cueprecon','2'=>'codded','3'=>'codtipper'));		
			$ls_contar=$this->DS->getRowCount("programatica");
			if ($ls_contar>0)
			{
				$this->DS->sortData('programatica');
			}
		}
		return $lb_valido;
	}// end function uf_contableconceptos_especifico_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas_disponibilidad($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
											   $as_subnomdes,$as_subnomhas)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$this->io_sql=new class_sql($this->io_conexion);
		if(!empty($as_estcla))
		{
			$ls_criterio="   AND sno_dt_spg.estcla = '".$as_estcla."'".
						 "   AND sno_dt_spg.codestpro1 = '".str_pad($as_codestpro1,25,"0","0")."'".
						 "   AND sno_dt_spg.codestpro2 = '".str_pad($as_codestpro2,25,"0","0")."'".
						 "   AND sno_dt_spg.codestpro3 = '".str_pad($as_codestpro3,25,"0","0")."'".
						 "   AND sno_dt_spg.codestpro4 = '".str_pad($as_codestpro4,25,"0","0")."'".
						 "   AND sno_dt_spg.codestpro5 = '".str_pad($as_codestpro5,25,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		
		$ls_sql="SELECT sno_dt_spg.codestpro1, sno_dt_spg.codestpro2, sno_dt_spg.codestpro3, sno_dt_spg.codestpro4, sno_dt_spg.codestpro5,".
				"		sno_dt_spg.estcla, TRIM(sno_dt_spg.spg_cuenta) AS spg_cuenta, sno_dt_spg.monto AS monto,  ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS existe, ".
				"		(SELECT denominacion ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS denominacion ".
				"  FROM sno_dt_spg  ".
				" WHERE sno_dt_spg.codemp='".$this->ls_codemp."' ".
				"   AND sno_dt_spg.codnom='".$this->ls_codnom."' ".
				$ls_criterio.
				"GROUP BY sno_dt_spg.spg_cuenta, sno_dt_spg.monto,sno_dt_spg.codemp,sno_dt_spg.codestpro1, ".
				"sno_dt_spg.codestpro2, sno_dt_spg.codestpro3, sno_dt_spg.codestpro4, sno_dt_spg.codestpro5, ".
				"sno_dt_spg.estcla ".
				"ORDER BY sno_dt_spg.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_validar_cuentas_disponibilidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'spg_cuenta'),array('0'=>'monto','1'=>'disponibilidad'),'spg_cuenta');
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_cuentas_disponibilidad_general($ls_desnom)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_validar_cuentas
		//		   Access: private
		//		 Argument: as_numsol // Número de solicitud
		//				   as_estsol  // Estatus de la solicitud
		//	  Description: Función que busca que las cuentas presupuestarias estén en la programática seleccionada
		//				   de ser asi coloca la sep en emitida sino la coloca en registrada
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Modificado Por: Ing. Yozelin Barragan
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_sql="SELECT sno_dt_spg.codestpro1, sno_dt_spg.codestpro2, sno_dt_spg.codestpro3, sno_dt_spg.codestpro4, sno_dt_spg.codestpro5,".
				"		sno_dt_spg.estcla, TRIM(sno_dt_spg.spg_cuenta) AS spg_cuenta, sno_dt_spg.monto AS monto,  ".
				"	    (SELECT (asignado-(comprometido+precomprometido)+aumento-disminucion) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS disponibilidad, ".		
				"		(SELECT COUNT(codemp) ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS existe, ".
				"		(SELECT denominacion ".
				"		   FROM spg_cuentas ".
				"		  WHERE spg_cuentas.codemp = sno_dt_spg.codemp ".
				"			AND spg_cuentas.codestpro1 = sno_dt_spg.codestpro1 ".
				"		    AND spg_cuentas.codestpro2 = sno_dt_spg.codestpro2 ".
				"		    AND spg_cuentas.codestpro3 = sno_dt_spg.codestpro3 ".
				"		    AND spg_cuentas.codestpro4 = sno_dt_spg.codestpro4 ".
				"		    AND spg_cuentas.codestpro5 = sno_dt_spg.codestpro5 ".
				"           AND spg_cuentas.estcla=sno_dt_spg.estcla".
				"			AND spg_cuentas.spg_cuenta = sno_dt_spg.spg_cuenta) AS denominacion ".
				"  FROM sno_dt_spg  ".
				" WHERE sno_dt_spg.codemp='".$this->ls_codemp."' ".
				"   AND sno_dt_spg.codnom='".$this->ls_codnom."' ".
				"GROUP BY sno_dt_spg.spg_cuenta, sno_dt_spg.monto,sno_dt_spg.codemp,sno_dt_spg.codestpro1, ".
				"sno_dt_spg.codestpro2, sno_dt_spg.codestpro3, sno_dt_spg.codestpro4, sno_dt_spg.codestpro5, ".
				"sno_dt_spg.estcla ".
				"ORDER BY sno_dt_spg.spg_cuenta ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_validar_cuentas_disponibilidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
				$this->DS->group_by(array('0'=>'spg_cuenta'),array('0'=>'monto','1'=>'disponibilidad'),'spg_cuenta');
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	}// end function uf_validar_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableconceptos_especifico_presupuesto_proyecto_dt($as_criterio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableconceptos_especifico_presupuesto_proyecto_dt 
		//	    Arguments:
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos que son por proyectos
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
			case "INFORMIX":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS FLOAT),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D
		$ls_sql="SELECT sno_proyectopersonal.codper, sno_proyectopersonal.codproy, sno_proyecto.estproproy, sno_proyecto.estcla, spg_cuentas.spg_cuenta,".
				"		".$ls_cadena." as montoparcial, ".$ls_cadena." AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		MAX(sno_proyectopersonal.pordiames) As pordiames, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas, sno_personalnomina, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				$as_criterio.
				"   AND sno_proyectopersonal.codemp = sno_personalnomina.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_personalnomina.codnom ".
				"   AND sno_proyectopersonal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyectopersonal.codproy,  sno_proyecto.estproproy, sno_proyecto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
		$ls_sql="SELECT sno_proyectopersonal.codper, sno_proyectopersonal.codproy, sno_proyecto.estproproy, sno_proyecto.estcla, spg_cuentas.spg_cuenta, ".
				"		".$ls_cadena." as montoparcial, ".$ls_cadena." AS total, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		MAX(sno_proyectopersonal.pordiames) As pordiames, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas, sno_personalnomina, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				$as_criterio.
				"   AND sno_proyectopersonal.codemp = sno_personalnomina.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_personalnomina.codnom ".
				"   AND sno_proyectopersonal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprecon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyectopersonal.codproy, sno_proyecto.estproproy, sno_proyecto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ".
				" ORDER BY codper, spg_cuenta, codproy, codded, codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableconceptos_especifico_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_estclaproyant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_codproyant="";
			$ls_coddedant="";
			$ls_codtipperant="";
			$ls_desdedant="";
			$ls_destipperant="";
			$li_totalpersonalant=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				$ls_desded=$row["desded"];
				$ls_destipper=$row["destipper"];
				$li_totalpersonal=$row["totalpersonal"];
				$li_montoparcial=round($row["montoparcial"],3);
				$li_total=round($row["total"],2);
				$ls_estproproy=$row["estproproy"];
				$ls_estclaproy=$row["estcla"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				$ls_codproy=$row["codproy"];
				if(($ls_codper!=$ls_codant)||($ls_spgcuenta!=$ls_cuentaant))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiamesant<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("estcla",$ls_estclaproyant);
							$this->DS->insertRow("cueprecon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("codded",$ls_coddedant);
							$this->DS->insertRow("codtipper",$ls_codtipperant);
							$this->DS->insertRow("desded",$ls_desdedant);
							$this->DS->insertRow("destipper",$ls_destipperant);
							$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
						}
					}
					$li_montoparcial=round($row["montoparcial"],3);
					$li_acumulado=$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$li_pordiamesant=$li_pordiames;
					$ls_codant=$ls_codper;
					$ls_codproyant=$ls_codproy;
					$ls_denominacionant=$ls_denominacion;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("estcla",$ls_estclaproy);
				$this->DS->insertRow("cueprecon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("codded",$ls_codded);
				$this->DS->insertRow("codtipper",$ls_codtipper);
				$this->DS->insertRow("desded",$ls_desded);
				$this->DS->insertRow("destipper",$ls_destipper);
				$this->DS->insertRow("totalpersonal",$li_totalpersonal);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiamesant<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("estcla",$ls_estclaproyant);
				$this->DS->insertRow("cueprecon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("codded",$ls_coddedant);
				$this->DS->insertRow("codtipper",$ls_codtipperant);
				$this->DS->insertRow("desded",$ls_desdedant);
				$this->DS->insertRow("destipper",$ls_destipperant);
				$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;    
	}// end function uf_contableconceptos_especifico_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_estcla,
													   $as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_especifico_presupuesto
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 11/05/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_criterio="";
		if(!empty($as_estcla))
		{
			$ls_criterio="   AND spg_cuentas.estcla = '".$as_estcla."'".
						 "   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,25,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal   ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla,  spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		sum(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal   ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_especifico_presupuesto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_contableaportes_especifico_presupuesto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto_proyecto($as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
															    $as_estcla,$as_subnomdes,$as_subnomhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_contableaportes_presupuesto_proyecto
		//         Access: public (desde la clase sigesp_sno_r_contableaportes)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 17/07/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		$ls_criterio="";
		if(!empty($as_estcla))
		{
			$ls_criterio="   AND spg_cuentas.estcla = '".$as_estcla."'".
						 "   AND spg_cuentas.codestpro1 = '".str_pad($as_codestpro1,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro2 = '".str_pad($as_codestpro2,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro3 = '".str_pad($as_codestpro3,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro4 = '".str_pad($as_codestpro4,25,"0","0")."'".
						 "   AND spg_cuentas.codestpro5 = '".str_pad($as_codestpro5,25,"0","0")."'";
		}
		if(!empty($as_subnomdes))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom>='".$as_subnomdes."'";
		}
		if(!empty($as_subnomhas))
		{
			$ls_criterio= $ls_criterio."   AND sno_personalnomina.codsubnom<='".$as_subnomhas."'";
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		SUM(sno_salida.valsal) as total, sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND spg_cuentas.status = 'C'".
				"   AND sno_concepto.conprocon = '0' ".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_concepto.codpro,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_concepto.codpro,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_concepto.codpro,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_concepto.codpro,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_concepto.codpro,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_concepto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_concepto.codpro,sno_concepto.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, spg_cuentas.spg_cuenta AS cueprepatcon, MAX(spg_cuentas.denominacion) AS denominacion, ".
				"		SUM(sno_salida.valsal) as total , sno_personalnomina.codded, sno_personalnomina.codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto, spg_cuentas, sno_dedicacion, sno_tipopersonal ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND spg_cuentas.status = 'C'".
				$ls_criterio.
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_unidadadmin.codprouniadm,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_unidadadmin.codprouniadm,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_unidadadmin.codprouniadm,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_unidadadmin.codprouniadm,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_unidadadmin.codprouniadm,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_unidadadmin.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_unidadadmin.codprouniadm,sno_unidadadmin.estcla, spg_cuentas.spg_cuenta, sno_personalnomina.codded, sno_personalnomina.codtipper ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_especifico_presupuesto_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);
			}
			$this->io_sql->free_result($rs_data);
		}		
		if($lb_valido)
		{
			$lb_valido=$this->uf_contableaportes_especifico_presupuesto_proyecto_dt($ls_criterio);
			$this->DS->group_by(array('0'=>'programatica','1'=>'cueprepatcon','2'=>'codded','3'=>'codtipper'),array('0'=>'total'),'total');
			$ls_contar=$this->DS->getRowCount("programatica");
			if ($ls_contar>0)
			{
				$this->DS->sortData('programatica');
			}
		}
		return $lb_valido;
	}// end function uf_contableaportes_especifico_presupuesto_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_contableaportes_especifico_presupuesto_proyecto_dt($as_criterio)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_contableaportes_presupuesto_proyecto_dt 
		//	    Arguments: 
		//	      Returns: lb_valido true si es correcto la funcion o false en caso contrario
		//	  Description: Función que se encarga de procesar la data para la contabilización de los conceptos de aportes por proyecto
	    //     Creado por: Ing. Yesenia Moreno
	    // Fecha Creación: 17/07/2007
		///////////////////////////////////////////////////////////////////////////////////////////////////
	   	$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),3) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),3) ";
				break;					
			case "INFORMIX":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS FLOAT),3) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT MAX(sno_proyecto.estproproy) AS estproproy, sno_proyecto.estcla, MAX(spg_cuentas.spg_cuenta) AS spg_cuenta, ".
				"		".$ls_cadena." AS total, MAX(sno_concepto.codprov) AS codprov, ".$ls_cadena." AS montoparcial, ".
				"		MAX(sno_concepto.cedben) AS cedben, sno_concepto.codconc, sno_proyecto.codproy, sno_proyectopersonal.codper, ".
				"		MAX(spg_cuentnalnomina.codtipper) AS codtipper, COUNT(sno_personalnomina.codper) AS totalpersonal, ".
				"		MAX(sno_dedicas.denominacion) AS denominacion, MAX(sno_proyectopersonal.pordiames) AS pordiames, MAX(sno_personalnomina.codded) AS codded, ".
				"		MAX(sno_persoacion.desded) AS desded, MAX(sno_tipopersonal.destipper) AS destipper  ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto, spg_cuentas, sno_personalnomina, sno_dedicacion, sno_tipopersonal  ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND spg_cuentas.status = 'C' ".
				$as_criterio.
				"   AND sno_proyectopersonal.codemp = sno_personalnomina.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_personalnomina.codnom ".
				"   AND sno_proyectopersonal.codper = sno_personalnomina.codper ".
				"   AND sno_personalnomina.codemp = sno_dedicacion.codemp ".
				"   AND sno_personalnomina.codded = sno_dedicacion.codded ".
				"   AND sno_personalnomina.codemp = sno_tipopersonal.codemp ".
				"   AND sno_personalnomina.codded = sno_tipopersonal.codded ".
				"   AND sno_personalnomina.codtipper = sno_tipopersonal.codtipper ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND spg_cuentas.codemp = sno_concepto.codemp ".
				"   AND spg_cuentas.spg_cuenta = sno_concepto.cueprepatcon ".
				"   AND substr(sno_proyecto.estproproy,1,25) = spg_cuentas.codestpro1 ".
				"   AND substr(sno_proyecto.estproproy,26,25) = spg_cuentas.codestpro2 ".
				"   AND substr(sno_proyecto.estproproy,51,25) = spg_cuentas.codestpro3 ".
				"   AND substr(sno_proyecto.estproproy,76,25) = spg_cuentas.codestpro4 ".
				"   AND substr(sno_proyecto.estproproy,101,25) = spg_cuentas.codestpro5 ".
				"   AND sno_proyecto.estcla = spg_cuentas.estcla ".
				" GROUP BY sno_proyectopersonal.codper, sno_proyecto.codproy, spg_cuentas.spg_cuenta, sno_concepto.codconc, sno_personalnomina.codded, sno_personalnomina.codtipper ".
				" ORDER BY sno_proyectopersonal.codper, spg_cuentas.spg_cuenta, sno_proyecto.codproy, sno_concepto.codconc, sno_personalnomina.codded, sno_personalnomina.codtipper  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_contableaportes_especifico_presupuesto_proyecto_dt ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$ls_codant="";
			$li_acumulado=0;
			$li_totalant=0;
			$ls_programaticaant="";
			$ls_estclaproyant="";
			$ls_cuentaant="";
			$ls_denominacionant="";
			$ls_conceptoant="";
			$ls_coddedant="";
			$ls_codtipperant="";
			$ls_desdedant="";
			$ls_destipperant="";
			$li_totalpersonalant=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codper=$row["codper"];
				$ls_codded=$row["codded"];
				$ls_codtipper=$row["codtipper"];
				$ls_desded=$row["desded"];
				$ls_destipper=$row["destipper"];
				$li_totalpersonal=$row["totalpersonal"];
				$ls_codconc=$row["codconc"];
				$li_montoparcial=$row["montoparcial"];
				$li_total=$row["total"];
				$ls_estproproy=$row["estproproy"];
				$ls_estclaproy=$row["estcla"];
				$ls_spgcuenta=$row["spg_cuenta"];
				$ls_denominacion=$row["denominacion"];
				$li_pordiames=$row["pordiames"];
				if(($ls_codper!=$ls_codant)||(($ls_spgcuenta!=$ls_cuentaant)&&($ls_codconc!=$ls_conceptoant)))
				{
					if($li_acumulado!=0)
					{
						if((round($li_acumulado,3)!=round($li_totalant,3))&&($li_pordiames<1))
						{
							$li_montoparcial=round(($li_totalant-$li_acumulado),3);
							$this->DS->insertRow("programatica",$ls_programaticaant);
							$this->DS->insertRow("estcla",$ls_estclaproyant);
							$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
							$this->DS->insertRow("total",$li_montoparcial);
							$this->DS->insertRow("denominacion",$ls_denominacionant);
							$this->DS->insertRow("codded",$ls_coddedant);
							$this->DS->insertRow("codtipper",$ls_codtipperant);
							$this->DS->insertRow("desded",$ls_desdedant);
							$this->DS->insertRow("destipper",$ls_destipperant);
							$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
						}
					}
					$li_acumulado=$row["montoparcial"];
					$li_montoparcial=round($row["montoparcial"],3);
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$ls_codant=$ls_codper;
					$ls_denominacionant=$ls_denominacion;
					$li_pordiamesant=$li_pordiames;
					$ls_conceptoant=$ls_codconc;
					$li_totalant=$li_total;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				else
				{
					$li_acumulado=$li_acumulado+$li_montoparcial;
					$ls_programaticaant=$ls_estproproy;
					$ls_estclaproyant=$ls_estclaproy;
					$ls_cuentaant=$ls_spgcuenta;
					$li_totalant=$li_total;
					$ls_denominacionant=$ls_denominacion;
					$ls_coddedant=$ls_codded;
					$ls_codtipperant=$ls_codtipper;
					$ls_desdedant=$ls_desded;
					$ls_destipperant=$ls_destipper;
					$li_totalpersonalant=$li_totalpersonal;
				}
				$this->DS->insertRow("programatica",$ls_estproproy);
				$this->DS->insertRow("estcla",$ls_estclaproy);
				$this->DS->insertRow("cueprepatcon",$ls_spgcuenta);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacion);
				$this->DS->insertRow("codded",$ls_codded);
				$this->DS->insertRow("codtipper",$ls_codtipper);
				$this->DS->insertRow("desded",$ls_desded);
				$this->DS->insertRow("destipper",$ls_destipper);
				$this->DS->insertRow("totalpersonal",$li_totalpersonal);
			}
			if((number_format($li_acumulado,3,".","")!=number_format($li_totalant,3,".",""))&&($li_pordiames<1))
			{
				$li_montoparcial=round(($li_totalant-$li_acumulado),3);
				$this->DS->insertRow("programatica",$ls_programaticaant);
				$this->DS->insertRow("estcla",$ls_estclaproyant);
				$this->DS->insertRow("cueprepatcon",$ls_cuentaant);
				$this->DS->insertRow("total",$li_montoparcial);
				$this->DS->insertRow("denominacion",$ls_denominacionant);
				$this->DS->insertRow("codded",$ls_coddedant);
				$this->DS->insertRow("codtipper",$ls_codtipperant);
				$this->DS->insertRow("desded",$ls_desdedant);
				$this->DS->insertRow("destipper",$ls_destipperant);
				$this->DS->insertRow("totalpersonal",$li_totalpersonalant);
			}
			$this->io_sql->free_result($rs_data);
		}		
		return  $lb_valido;    
	}// end function uf_contableaportes_especifico_presupuesto_proyecto_dt
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadreconceptoaporte_aportes_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadreconceptoaporte_aportes_proyecto
		//         Access: public (desde la clase sigesp_sno_r_cuadreconceptoaporte)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo P2
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/05/2008 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, sno_concepto.cueprepatcon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '1'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codconc, sno_concepto.codpro, sno_concepto.estcla, sno_concepto.cueprepatcon  ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprepatcon, sum(sno_salida.valsal) as total".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codconc, sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprepatcon ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_proyecto.estproproy AS programatica, sno_proyecto.estcla, sno_concepto.cueprepatcon, ".$ls_cadena." AS total ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND (sno_salida.tipsal = 'P2' OR sno_salida.tipsal = 'V4' OR sno_salida.tipsal = 'W4')".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				" GROUP BY sno_concepto.codconc, sno_proyecto.estproproy, sno_proyecto.estcla, sno_concepto.cueprepatcon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_aportes_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_programatica=$row["programatica"];
				$ls_estcla=$row["estcla"];
				$ls_cuentapresupuesto=$row["cueprepatcon"];
				$li_total=$row["total"];
				$ls_sql="SELECT denominacion ".
						"  FROM spg_cuentas ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND status = 'C'".
						"   AND estcla = '".$ls_estcla."'".
						"   AND codestpro1 = '".substr($ls_programatica,0,20)."'".
						"   AND codestpro2 = '".substr($ls_programatica,20,6)."'".
						"   AND codestpro3 = '".substr($ls_programatica,26,3)."'".
						"   AND codestpro4 = '".substr($ls_programatica,29,2)."'".
						"   AND codestpro5 = '".substr($ls_programatica,31,2)."'".
						"   AND spg_cuenta = '".$ls_cuentapresupuesto."'";
				$rs_data2=$this->io_sql->select($ls_sql);
				if($rs_data2===false)
				{
					$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_aportes_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					if(!$row=$this->io_sql->fetch_row($rs_data2))
					{
						$this->DS->insertRow("programatica",$ls_programatica);
						$this->DS->insertRow("cueprepatcon",$ls_cuentapresupuesto);
						$this->DS->insertRow("denominacion","No Existe la cuenta en la Estructura.");
						$this->DS->insertRow("total",$li_total);
					}
					$this->io_sql->free_result($rs_data2);
				}
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cuadreconceptoaporte_aportes_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_cuadreconceptoaporte_conceptos_proyecto()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_cuadreconceptoaporte_conceptos_proyecto
		//         Access: public (desde la clase sigesp_sno_r_cuadreconceptoaporte)  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las cuentas presupuestarias que afectan los conceptos de tipo A, D, P1
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/09/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$this->io_sql=new class_sql($this->io_conexion);
		switch($_SESSION["ls_gestor"])
		{
			case "MYSQLT":
				$ls_cadena=" ROUND((SUM(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)),2) ";
				break;
			case "POSTGRES":
				$ls_cadena=" ROUND(CAST((sum(sno_salida.valsal)*MAX(sno_proyectopersonal.pordiames)) AS NUMERIC),2) ";
				break;					
		}
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que se integran directamente con presupuesto
		$ls_sql="SELECT sno_concepto.codpro as programatica, sno_concepto.estcla,sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '1'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codpro, sno_concepto.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos A y D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0".
				"   AND sno_concepto.intprocon = '0'".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_concepto.codpro as programatica, sno_concepto.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '1' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_concepto.codpro, sno_concepto.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos  D, que no se integran directamente con presupuesto
		// entonces las buscamos según la estructura de la unidad administrativa a la que pertenece el personal
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_unidadadmin.codprouniadm as programatica, sno_unidadadmin.estcla, sno_concepto.cueprecon, sum(sno_salida.valsal) as total ".
				"  FROM sno_personalnomina, sno_unidadadmin, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_concepto.intprocon = '0' ".
				"   AND sno_concepto.conprocon = '0' ".
				"   AND sno_personalnomina.codemp = sno_salida.codemp ".
				"   AND sno_personalnomina.codnom = sno_salida.codnom ".
				"   AND sno_personalnomina.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				"   AND sno_personalnomina.codemp = sno_unidadadmin.codemp ".
				"   AND sno_personalnomina.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_personalnomina.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_personalnomina.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_personalnomina.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_personalnomina.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_unidadadmin.codprouniadm, sno_unidadadmin.estcla, sno_concepto.cueprecon ";
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_proyecto.estproproy as programatica, sno_proyecto.estcla, sno_concepto.cueprecon, ".$ls_cadena." as total ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'A' OR sno_salida.tipsal = 'V1' OR sno_salida.tipsal = 'W1') ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				" GROUP BY sno_proyecto.estproproy, sno_proyecto.estcla, sno_concepto.cueprecon ";
		// Buscamos todas aquellas cuentas presupuestarias de los conceptos D , que se integran directamente con presupuesto
		$ls_sql=$ls_sql." UNION ".
				"SELECT sno_proyecto.estproproy as programatica, sno_proyecto.estcla, sno_concepto.cueprecon, ".$ls_cadena." as total ".
				"  FROM sno_proyectopersonal, sno_proyecto, sno_salida, sno_concepto ".
				" WHERE sno_salida.codemp='".$this->ls_codemp."' ".
				"   AND sno_salida.codnom='".$this->ls_codnom."' ".
				"   AND sno_salida.codperi='".$this->ls_peractnom."' ".
				"   AND (sno_salida.tipsal = 'D' OR sno_salida.tipsal = 'V2' OR sno_salida.tipsal = 'W2' OR sno_salida.tipsal = 'P1' OR sno_salida.tipsal = 'V3' OR sno_salida.tipsal = 'W3')".
				"   AND sno_concepto.sigcon = 'E' ".
				"   AND sno_salida.valsal <> 0 ".
				"   AND sno_proyectopersonal.pordiames <> 0 ".
				"   AND sno_concepto.conprocon = '1' ".
				"   AND sno_proyectopersonal.codemp = sno_proyecto.codemp ".
				"   AND sno_proyectopersonal.codproy = sno_proyecto.codproy ".
				"   AND sno_proyectopersonal.codemp = sno_salida.codemp ".
				"   AND sno_proyectopersonal.codnom = sno_salida.codnom ".
				"   AND sno_proyectopersonal.codper = sno_salida.codper ".
				"   AND sno_salida.codemp = sno_concepto.codemp ".
				"   AND sno_salida.codnom = sno_concepto.codnom ".
				"   AND sno_salida.codconc = sno_concepto.codconc ".
				" GROUP BY sno_proyecto.estproproy, sno_proyecto.estcla, sno_concepto.cueprecon ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_conceptos_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_programatica=$row["programatica"];
				$ls_estcla=$row["estcla"];
				$ls_cuentapresupuesto=$row["cueprecon"];
				$li_total=$row["total"];
				$ls_sql="SELECT denominacion ".
						"  FROM spg_cuentas ".
						" WHERE codemp='".$this->ls_codemp."' ".
						"   AND status = 'C'".
						"   AND estcla = '".$ls_estcla."'".
						"   AND codestpro1 = '".substr($ls_programatica,0,20)."'".
						"   AND codestpro2 = '".substr($ls_programatica,20,6)."'".
						"   AND codestpro3 = '".substr($ls_programatica,26,3)."'".
						"   AND codestpro4 = '".substr($ls_programatica,29,2)."'".
						"   AND codestpro5 = '".substr($ls_programatica,31,2)."'".
						"   AND spg_cuenta = '".$ls_cuentapresupuesto."'";
				$rs_data2=$this->io_sql->select($ls_sql);
				if($rs_data2===false)
				{
					$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cuadreconceptoaporte_conceptos_proyecto ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
					$lb_valido=false;
				}
				else
				{
					if(!$row=$this->io_sql->fetch_row($rs_data2))
					{
						$this->DS_detalle->insertRow("programatica",$ls_programatica);
						$this->DS_detalle->insertRow("cueprecon",$ls_cuentapresupuesto);
						$this->DS_detalle->insertRow("denominacion","No Existe la cuenta en la Estructura.");
						$this->DS_detalle->insertRow("total",$li_total);
					}
					$this->io_sql->free_result($rs_data2);
				}
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cuadreconceptoaporte_conceptos_proyecto
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
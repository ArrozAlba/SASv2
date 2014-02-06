<?php
class sigesp_snorh_c_metodo_cestaticket
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $ls_codemp;
	var $io_sno;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_metodo_cestaticket()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_metodobanco
		//		   Access: public (sigesp_snorh_r_cestaticket)
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/03/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_datastore.php");
		$this->DS=new class_datastore();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();	
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();		
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		require_once ("../shared/writeexcel/class.writeexcel_workbook.inc.php");
		require_once ("../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
	}// end function sigesp_snorh_c_metodo_cestaticket
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listado_gendisk($as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_codperi,$as_codconcdes,$as_codconchas,$as_codcestic,
									 $as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_gendisk
		//		   Access: public (desde la clase sigesp_snorh_r_cestaticket)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//	    		   as_codperi // Código del periodo
		//	    		   as_codconcdes // Código del concepto Desde del que se desea busca el personal
		//	    		   as_codconchas // Código del concepto Hasta del que se desea busca el personal
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las nóminas de cesta ticket para generar el archivo excel
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/03/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		if(!empty($as_codperi))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi='".$as_codperi."' ";
		}
		if(!empty($as_codconcdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc>='".$as_codconcdes."' ";
		}
		if(!empty($as_codconchas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codconc<='".$as_codconchas."' ";
		}
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		}
		if(!empty($as_codcestic))
		{
			$ls_criterio = $ls_criterio." AND sno_hnomina.ctmetnom = '".$as_codcestic."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.nacper) AS nacper,  ".
				"		MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
				"       MAX(sno_personal.nacper) as nacionalidad, MAX(sno_cestaticket.codprod) AS codprod, ".
				"		SUM(sno_hsalida.valsal) AS monto, MAX(sno_cestaticunidadadm.est1cestic) as codigoempresa, ".
				"		MAX(sno_cestaticunidadadm.est2cestic) as puntoentrega, MAX(sno_unidadadmin.desuniadm) as desuniadm, ".
				"		MAX(sno_cestaticket.moncestic) AS moncestic,  MAX(sno_hnomina.codorgcestic) AS codorgcestic, ".
				"       (SELECT SUM(sno_hsalida.valsal) ".
				"		   FROM sno_hsalida ".
				" 		  WHERE sno_hsalida.codemp='".$this->ls_codemp."' ".
				"   		AND sno_hsalida.codnom>='".$as_codnomdes."' ".
				"   		AND sno_hsalida.codnom<='".$as_codnomhas."' ".
				"   		AND sno_hsalida.anocur='".$as_ano."' ".
				"   	 	AND sno_hsalida.codperi='".$as_codperi."') AS montototal ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hsalida, sno_hnomina, sno_hconcepto, sno_cestaticunidadadm, ".
				"		sno_cestaticket, sno_unidadadmin ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.codperi='".$as_codperi."' ".
				"   AND (sno_hpersonalnomina.staper='1'  OR sno_hpersonalnomina.staper='2') ".
				"   AND sno_hnomina.espnom = '1' ".
				"   AND sno_hnomina.ctnom = '1' ".
				$ls_criterio.
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"   AND sno_hconcepto.codemp = sno_hsalida.codemp ".
				"   AND sno_hconcepto.codnom = sno_hsalida.codnom ".
				"   AND sno_hconcepto.anocur = sno_hsalida.anocur ".
				"   AND sno_hconcepto.codperi = sno_hsalida.codperi ".
				"   AND sno_hconcepto.codconc = sno_hsalida.codconc ".
				"   AND sno_hpersonalnomina.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"   AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hnomina.ctmetnom = sno_cestaticket.codcestic ".
				"   AND sno_cestaticket.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_cestaticket.codcestic = sno_cestaticunidadadm.codcestic ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_cestaticunidadadm.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_cestaticunidadadm.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_cestaticunidadadm.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_cestaticunidadadm.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_cestaticunidadadm.prouniadm ".
				"   AND sno_cestaticunidadadm.codemp = sno_unidadadmin.codemp ".
				"   AND sno_cestaticunidadadm.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_cestaticunidadadm.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_cestaticunidadadm.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_cestaticunidadadm.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_cestaticunidadadm.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_personal.codper,sno_personal.apeper,sno_personal.nomper,sno_personal.cedper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cestaticket_personal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("NO HAY NADA QUE REPORTAR");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cestaticket_personal
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_listado_gendisk2($as_codnomdes,$as_codnomhas,$as_ano,$as_mes,$as_codperi,$as_codcestic,$as_conceptocero,$as_orden)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_gendisk2
		//		   Access: public (desde la clase sigesp_snorh_r_cestaticket)  
		//	    Arguments: as_codnomdes // Código nómina desde
		//	  			   as_codnomhas // Código nómina hasta
		//	    		   as_ano // Año en curso
		//	  			   as_mes // mes
		//	    		   as_codperi // Código del periodo
		//	    		   as_codconcdes // Código del concepto Desde del que se desea busca el personal
		//	    		   as_codconchas // Código del concepto Hasta del que se desea busca el personal
		//				   as_conceptocero // Conceptos en cero
		//	  			   as_orden // orden por medio del cual se desea que salga el reporte
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las nóminas de cesta ticket para generar el archivo excel
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 29/03/2007 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hresumen.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hresumen.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hresumen.anocur='".$as_ano."' ";
		}
		if(!empty($as_codperi))
		{
			$ls_criterio = $ls_criterio." AND sno_hresumen.codperi='".$as_codperi."' ";
		}		
		if(!empty($as_conceptocero))
		{
			$ls_criterio = $ls_criterio." AND sno_hresumen.monnetres<>0 ";
		}
		if(!empty($as_codcestic))
		{
			$ls_criterio = $ls_criterio." AND sno_hnomina.ctmetnom = '".$as_codcestic."' ";
		}
		switch($as_orden)
		{
			case "1": // Ordena por Código de personal
				$ls_orden="ORDER BY sno_personal.codper ";
				break;

			case "2": // Ordena por Apellido de personal
				$ls_orden="ORDER BY sno_personal.apeper ";
				break;

			case "3": // Ordena por Nombre de personal
				$ls_orden="ORDER BY sno_personal.nomper ";
				break;

			case "4": // Ordena por Cédula de personal
				$ls_orden="ORDER BY sno_personal.cedper ";
				break;
		}
		$ls_sql="SELECT sno_personal.codper, MAX(sno_personal.cedper) AS cedper, MAX(sno_personal.nacper) AS nacper,  ".
				"		MAX(sno_personal.nomper) AS nomper, MAX(sno_personal.apeper) AS apeper, ".
				"       MAX(sno_personal.nacper) as nacionalidad,  ".
				"		SUM(sno_hresumen.monnetres) AS monto, MAX(sno_cestaticunidadadm.est1cestic) as codigoempresa, ".
				"		MAX(sno_cestaticunidadadm.est2cestic) as puntoentrega, MAX(sno_unidadadmin.desuniadm) as desuniadm, ".
				"		MAX(sno_cestaticket.moncestic) AS moncestic,  MAX(sno_hnomina.codorgcestic) AS codorgcestic, ".
				"       (SELECT SUM(sno_hresumen.monnetres) ".
				"		   FROM sno_hresumen  ".
				" 		  WHERE sno_hresumen.codemp='".$this->ls_codemp."' ".
				"   		AND sno_hresumen.codnom>='".$as_codnomdes."' ".
				"   		AND sno_hresumen.codnom<='".$as_codnomhas."' ".
				"   		AND sno_hresumen.anocur='".$as_ano."' ".
				"   	 	AND sno_hresumen.codperi='".$as_codperi."') AS montototal ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hresumen, sno_hnomina,  sno_cestaticunidadadm, ".
				"		sno_cestaticket, sno_unidadadmin ".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.codperi='".$as_codperi."' ".
				"   AND sno_hpersonalnomina.staper='1' ".
				"   AND sno_hnomina.espnom = '1' ".
				"   AND sno_hnomina.ctnom = '1' ".				
				$ls_criterio.
				"   AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_hresumen.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hresumen.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hresumen.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hresumen.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hresumen.codper ".				
				"   AND sno_hpersonalnomina.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"   AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom ".
				"   AND sno_hnomina.ctmetnom = sno_cestaticket.codcestic ".
				"   AND sno_cestaticket.codemp = sno_cestaticunidadadm.codemp ".
				"   AND sno_cestaticket.codcestic = sno_cestaticunidadadm.codcestic ".
				"   AND sno_hpersonalnomina.minorguniadm = sno_cestaticunidadadm.minorguniadm ".
				"   AND sno_hpersonalnomina.ofiuniadm = sno_cestaticunidadadm.ofiuniadm ".
				"   AND sno_hpersonalnomina.uniuniadm = sno_cestaticunidadadm.uniuniadm ".
				"   AND sno_hpersonalnomina.depuniadm = sno_cestaticunidadadm.depuniadm ".
				"   AND sno_hpersonalnomina.prouniadm = sno_cestaticunidadadm.prouniadm ".
				"   AND sno_cestaticunidadadm.codemp = sno_unidadadmin.codemp ".
				"   AND sno_cestaticunidadadm.minorguniadm = sno_unidadadmin.minorguniadm ".
				"   AND sno_cestaticunidadadm.ofiuniadm = sno_unidadadmin.ofiuniadm ".
				"   AND sno_cestaticunidadadm.uniuniadm = sno_unidadadmin.uniuniadm ".
				"   AND sno_cestaticunidadadm.depuniadm = sno_unidadadmin.depuniadm ".
				"   AND sno_cestaticunidadadm.prouniadm = sno_unidadadmin.prouniadm ".
				" GROUP BY sno_personal.codper,sno_personal.apeper,sno_personal.nomper,sno_personal.cedper ".
				"   ".$ls_orden;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Report MÉTODO->uf_cestaticket_personal2 ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);	
			}
			else
			{
				$lb_valido=false;
				$this->io_mensajes->message("NO HAY NADA QUE REPORTAR");
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_cestaticket_personal2
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_cestaticket($as_ruta,$as_metodo,$aa_ds_cestaticket,$as_anocurper,$as_mescurper,$as_codcli,$as_codprod,
	                               $as_punent,$ad_fecha,$aa_seguridad)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_cestaticket	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//	    		   as_metodo   // Código del metodo a banco
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//                 ad_fecha // Fecha de Procesamiento
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True 
		//	  Description: Funcion que segun el método de cestaticket genera unarchivo excel con los datos necesarios
		//	   Creado Por: Ing. Yesenia Moreno	
		// Fecha Creación: 29/03/2007 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	  	switch ($as_metodo)
		{
			case "Accord Ticket Univalor":
				$lb_valido=$this->uf_metodo_accord_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                          $as_codprod,$as_punent,$ad_fecha);
				break;

			case "Accord Ticket Multivalor":
				$lb_valido=$this->uf_metodo_accord_ticket_multivalor($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,
																	 $as_codcli,$as_codprod,$as_punent,$ad_fecha);
				break;

			case "Accord Tarjeta":
				$lb_valido=$this->uf_metodo_accord_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                           $as_codprod,$as_punent,$ad_fecha);
				break;

			case "Valeven Ticket":
				$lb_valido=$this->uf_metodo_valeven_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                           $as_codprod,$as_punent);
				break;
			
			case "Valeven Tarjeta":			    
				$lb_valido=$this->uf_metodo_valeven_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket);				
				
				break;	

			case "Banco Industrial Electronico":
				$lb_valido=$this->uf_metodo_banco_industrial_electronico($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,
											   						     $as_codcli,$as_codprod,$as_punent,$ad_fecha);
				break;
				
			case "Sodexho Tarjeta":			    
				$lb_valido=$this->uf_metodo_sodexho_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                           $as_codprod,$as_punent,$ad_fecha);
				break;
				
			case "Sodexho Ticket":
				$lb_valido=$this->uf_metodo_sodexho_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                           $as_codprod,$as_punent);
				break;
				
			case "Sodexho Ticket Plus":
				$lb_valido=$this->uf_metodo_sodexho_ticket_plus($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
				                                           		$as_codprod,$as_punent);
				break;
			
			case "IPSFA":
				$lb_valido=$this->uf_metodo_IPSFA($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,
			                                  	  $as_codcli,$as_codprod,$as_punent,$ad_fecha);				
				break;
			
			case "Todo Ticket Tarjeta":
			
				$lb_valido=$this->uf_metodo_todo_ticket_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$ad_fecha,
				                                                $aa_ds_cestaticket);				
				break;
			
			case "EfecTicket":
			
				$lb_valido=$this->uf_metodo_EfecTicket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,
											   			$as_codcli,$as_codprod,$as_punent,$ad_fecha);				
				break;
			default:
				$this->io_mensajes->message("El método seleccionado no esta disponible.");
				break;
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el disco de CESTA TICKET. ".$as_metodo;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
	}// end function uf_metodo_cestaticket
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_accord_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent,$ad_fecha)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_accord_ticket	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de accord tarjetas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Fecha Creación: 17/04/2007 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/accord_ticket.xls";
		$ls_codorgcestic=rtrim($aa_ds_cestaticket->data["codorgcestic"][1]);
		$ld_fecproc=str_replace("/","",$ad_fecha);
		$ls_destino=$as_ruta."/p_".$ls_codorgcestic."_".$as_codprod."_".$ld_fecproc."_01.xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
			$li_fila=0;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_codigoempresa=rtrim($aa_ds_cestaticket->data["codigoempresa"][$li_i]);
				$ls_puntoentrega=rtrim($aa_ds_cestaticket->data["puntoentrega"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$ls_nombre=$ls_nomper." ".$ls_apeper;
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");
				$ld_moncestic=number_format($aa_ds_cestaticket->data["moncestic"][$li_i],0,".","");
				$li_cantidad=number_format($ld_monper/$ld_moncestic,0,".","");
				$worksheet->write($li_fila,0,$ls_codigoempresa,'');
				$worksheet->write($li_fila,1,$as_codprod,'');
				$worksheet->write($li_fila,2,$ls_puntoentrega,'');
				$worksheet->write($li_fila,3,$ls_cedper,'');
				$worksheet->write($li_fila,4,$ls_nombre,'');
				$worksheet->write($li_fila,5,$ld_monper,'');
				$worksheet->write($li_fila,6,$li_cantidad,'');
				$worksheet->write($li_fila,7,$ld_moncestic,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_accord_ticket
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_accord_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent,$ad_fecha)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_accord_tarjeta	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de accord tarjetas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Fecha Creación: 29/03/2007 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/accord_tarjeta.xls";
		$ls_codorgcestic=rtrim($aa_ds_cestaticket->data["codorgcestic"][1]);
		$ld_fecproc=str_replace("/","",$ad_fecha);
		$ls_destino=$as_ruta."/c_".$ls_codorgcestic."_".$as_codprod."_".$ld_fecproc."_01.xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
			$worksheet->write(0,0,"CÉDULA",'');
			$worksheet->write(0,1,"CÓD. CLIENTE",'');
			$worksheet->write(0,2,"CÓD. PRODUCTO",'');
			$worksheet->write(0,3,"PUNTO ENTREGA",'');
			$worksheet->write(0,4,"MONTO",'');
			$li_fila=2;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_nacper=trim($aa_ds_cestaticket->data["nacper"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=$ls_nacper."-".str_replace(",","",$ls_cedper);
				$ldec_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");
				$ls_puntoentrega=rtrim($aa_ds_cestaticket->data["puntoentrega"][$li_i]);
				$ls_codigoempresa=rtrim($aa_ds_cestaticket->data["codigoempresa"][$li_i]);
				$worksheet->write($li_fila,0,$ls_cedper,'');
				$worksheet->write($li_fila,1,$ls_codigoempresa,'');
				$worksheet->write($li_fila,2,$as_codprod,'');
				$worksheet->write($li_fila,3,$ls_puntoentrega,'');
				$worksheet->write($li_fila,4,$ldec_monper,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_accord_tarjeta
	//---------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_sodexho_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent,$ad_fecha)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_accord_tarjeta	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de accord tarjetas
		//	   Creado Por: Ing. Jennifer Rivero	
		// Fecha Creación: 22/04/2008 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/sodexho_tarjeta.xls";
		$ls_codorgcestic=rtrim($aa_ds_cestaticket->data["codorgcestic"][1]);
		$ld_fecproc=str_replace("/","",$ad_fecha);
		$ls_destino=$as_ruta."/c_".$ls_codorgcestic."_".$as_codprod."_".$ld_fecproc."_01.xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		
		$worksheet->write(0,0,"CÓD. CLIENTE",'');		
		$worksheet->write(0,1,$ls_codorgcestic,'');			
		if ($li_count>0)
		{
			$worksheet->write(1,0,"CÉDULA",'');			
			$worksheet->write(1,1,"APELLIDO",'');
			$worksheet->write(1,2,"NOMBRE",'');
			$worksheet->write(1,3,"MONTO DE LA TARJETA",'');
			$li_fila=2;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_nacper=trim($aa_ds_cestaticket->data["nacper"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ldec_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");
				$ls_nombreper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);				
				$ls_apellidoper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$worksheet->write($li_fila,0,$ls_cedper,'');
				$worksheet->write($li_fila,1,$ls_apellidoper,'');				
				$worksheet->write($li_fila,2,$ls_nombreper,'');
				$worksheet->write($li_fila,3,$ldec_monper,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_sodexho_tarjeta
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_sodexho_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_sodexho_ticket	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de Valeven Ticket
		//	   Creado Por: Ing. Jennifer Rivero	
		// Fecha Creación: 23/04/2008 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/sodexho_ticket.xls";
		$ls_destino=$as_ruta."/sodexho_ticket_".$as_anocurper."_".$as_mescurper.".xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
		    $worksheet->write(0,0,"CÓD. DEL CLIENTE",'');
			$worksheet->write(1,0,"OBSERVACIÓN",'');
			$worksheet->write(2,0,"CÉDULA",'');
			$worksheet->write(2,1,"APELLIDO Y NOMBRE",'');				
			$worksheet->write(2,2,"CANTIDAD DE CHEQUES",'');
			$worksheet->write(2,3,"MONTO DEL CHEQUE",'');
			$li_fila=3;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_nacper=trim($aa_ds_cestaticket->data["nacper"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$ls_nombre=$ls_apeper.",".$ls_nomper;				
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");				
				$ld_moncestic=number_format($aa_ds_cestaticket->data["moncestic"][$li_i],2,".",""); 				
				$li_cantidad=number_format($ld_monper/$ld_moncestic,0,".","");
				
				$worksheet->write($li_fila,0,$ls_cedper,'');
				$worksheet->write($li_fila,1,$ls_nombre,'');							
				$worksheet->write($li_fila,2,$li_cantidad,'');
				$worksheet->write($li_fila,3,$ld_moncestic,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_valeven_ticket
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//---------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_sodexho_ticket_plus($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_sodexho_ticket_plus	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de Valeven Ticket
		//	   Creado Por: Ing. Jennifer Rivero	
		// Fecha Creación: 23/04/2008 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/sodexho_ticket.xls";
		$ls_destino=$as_ruta."/sodexho_ticket_".$as_anocurper."_".$as_mescurper.".xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
		    $worksheet->write(0,0,"CÓD. DEL CLIENTE",'');
			$worksheet->write(1,0,"OBSERVACIÓN",'');
			$worksheet->write(2,0,"CÉDULA",'');
			$worksheet->write(2,1,"APELLIDO Y NOMBRE",'');				
			$worksheet->write(2,2,"COD.PTO. ENTREGA",'');
			$worksheet->write(2,3,"COD. ESPECIAL",'');
			$worksheet->write(2,4,"CANTIDAD DE CHEQUES",'');
			$worksheet->write(2,5,"MONTO DEL CHEQUE",'');
			$li_fila=3;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_nacper=trim($aa_ds_cestaticket->data["nacper"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$ls_nombre=$ls_apeper.",".$ls_nomper;	
				$ls_puntoentrega=trim($aa_ds_cestaticket->data["puntoentrega"][$li_i]);
				$ls_codprod=trim($aa_ds_cestaticket->data["codprod"][$li_i]);
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");				
				$ld_moncestic=number_format($aa_ds_cestaticket->data["moncestic"][$li_i],2,".",""); 				
				$li_cantidad=number_format($ld_monper/$ld_moncestic,0,".","");
				$worksheet->write($li_fila,0,$ls_cedper,'');
				$worksheet->write($li_fila,1,$ls_nombre,'');							
				$worksheet->write($li_fila,2,$ls_puntoentrega,'');							
				$worksheet->write($li_fila,3,$ls_codprod,'');							
				$worksheet->write($li_fila,4,$li_cantidad,'');
				$worksheet->write($li_fila,5,$ld_moncestic,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_sodexho_ticket_plus
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_valeven_ticket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_valeven_ticket	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de Valeven Ticket
		//	   Creado Por: Ing. Yesenia Moreno	
		// Fecha Creación: 17/04/2007 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/valeven_ticket.xls";
		$ls_destino=$as_ruta."/valeven_ticket_".$as_anocurper."_".$as_mescurper.".xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
			$worksheet->write(0,0,"CÉDULA",'');
			$worksheet->write(0,1,"NOMBRE",'');
			$worksheet->write(0,2,"APELLIDO",'');
			$worksheet->write(0,3,"PUNTO DE ENTREGA",'');
			$worksheet->write(0,4,"DEPARTAMENTO",'');
			$worksheet->write(0,5,"CANTIDAD",'');
			$worksheet->write(0,6,"VALOR",'');
			$li_fila=1;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$ls_puntoentrega=rtrim($aa_ds_cestaticket->data["puntoentrega"][$li_i]);
				$ls_desuniadm=rtrim($aa_ds_cestaticket->data["desuniadm"][$li_i]);
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");
				$ld_moncestic=number_format($aa_ds_cestaticket->data["moncestic"][$li_i],0,".","");
				$li_cantidad=number_format($ld_monper/$ld_moncestic,0,".","");
				
				$worksheet->write($li_fila,0,$ls_cedper,'');
				$worksheet->write($li_fila,1,$ls_nomper,'');
				$worksheet->write($li_fila,2,$ls_apeper,'');
				$worksheet->write($li_fila,3,$ls_puntoentrega,'');
				$worksheet->write($li_fila,4,$ls_desuniadm,'');
				$worksheet->write($li_fila,5,$li_cantidad,'');
				$worksheet->write($li_fila,6,$ld_moncestic);
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_valeven_ticket
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------------
    function uf_metodo_valeven_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_valeven_tarjeta	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo		
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket 		
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de Valeven Tarjeta
		//	   Creado Por: Ing. Jennifer Rivero	
		// Fecha Creación: 09/07/2008								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/valeven_tarjeta.xls";
		$ls_destino=$as_ruta."/valeven_tarjeta_".$as_anocurper."_".$as_mescurper.".xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");	
	
		$lo_titulo= &$workbook->addformat();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
	    $lo_titulo->set_size('8');	
		
		if ($li_count>0)
		{
		    
			$worksheet->write(0,0,"V/E",$lo_titulo);
			$worksheet->write(0,1,"CÉDULA",$lo_titulo);
			$worksheet->write(0,2,"NOMBRE",$lo_titulo);
			$worksheet->write(0,3,"MONTO",$lo_titulo);			
			$li_fila=1;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nacper=rtrim($aa_ds_cestaticket->data["nacionalidad"][$li_i]);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);	
				$ls_nombre=substr($ls_apeper.", ".$ls_nomper,0,20);						
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","2");
				$ld_moncestic=number_format($aa_ds_cestaticket->data["moncestic"][$li_i],0,".","2");
								
				$worksheet->write($li_fila,0,$ls_nacper,'');
				$worksheet->write($li_fila,1,$ls_cedper,'');
				$worksheet->write($li_fila,2,$ls_nombre,'');				
				$worksheet->write($li_fila,3,$ld_monper,'');				
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
				unset($workbook);
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_valeven_tarjeta
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_banco_industrial_electronico($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,
													$as_punent,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_banco_industrial_electronico
		//		   Access: private 
		//	    Arguments: as_ruta  // ruta 
		//                 as_anocurper //  Año en curso
		//                 as_mescurper //  Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos CESTA TICKET   
		//                 as_codcli // Código de Cliente   
		//                 as_codprod // Código del Producto 
		//                 as_punent // Punto de Entrega
		//                 ad_fecha // Fecha de Entrega
		//	  Description: genera el archivo txt a disco para  el banco Industrial para pago de Cesta Ticket
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 30/08/2007 								
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if($li_count>0)
		{	
			$ls_nombrearchivo=$as_ruta."/cesta_ticket_".$as_anocurper."_".$as_mescurper.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			$ls_codorgcestic=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["codorgcestic"][1]);
			$ls_codorgcestic=str_pad($ls_codorgcestic,3,"0",0);
			$li_totreg=str_pad($li_count,6,"0",0);
			$ld_montototal=($aa_ds_cestaticket->data["montototal"][1]*100);
			$ldec_monto=$this->io_funciones->uf_cerosizquierda(number_format($ld_montototal,0,".",""),15);
			$ad_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$ad_fecha=str_replace("-","",$ad_fecha);
			$ls_cadena="ATMCCBDE"."900".$ls_codorgcestic.$li_totreg.$ldec_monto.$ad_fecha."\r\n";
			if ($ls_creararchivo)  //Chequea que el archivo este abierto				
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
				$lb_valido = false;
			}
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{   					
				$ls_nacper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["nacper"][$li_i]);     //nacionalidad
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["cedper"][$li_i]);     //cedula
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);			
				$ld_monto=($aa_ds_cestaticket->data["monto"][$li_i]*100);       //Monto aporte
				$ld_monto=$this->io_funciones->uf_cerosizquierda(number_format($ld_monto,0,".",""),15);
				$ls_cadena=$ls_nacper.$ls_cedper.$ld_monto."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_banco_industrial_electronico
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_accord_ticket_multivalor($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,
												$as_punent,$ad_fecha)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_accord_ticket_multivalor	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//                 as_anocurper // Año en curso
		//                 as_mescurper // Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket    
		//                 as_codcli // Código de Cliente
		//                 as_codprod // Código de Producto
		//                 as_punent // Punto de Entrega
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de accord tarjetas
		//	   Creado Por: Ing. Yesenia Moreno	
		// Fecha Creación: 11/09/2007 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/accord_ticket.xls";
		$ls_codorgcestic=rtrim($aa_ds_cestaticket->data["codorgcestic"][1]);
		$ld_fecproc=str_replace("/","",$ad_fecha);
		$ls_destino=$as_ruta."/p_".$ls_codorgcestic."_".$as_codprod."_".$ld_fecproc."_01.xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if ($li_count>0)
		{
			$li_fila=0;
			$li_i=0;
			$li_total=0;
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_codigoempresa=rtrim($aa_ds_cestaticket->data["codigoempresa"][$li_i]);
				$ls_puntoentrega=rtrim($aa_ds_cestaticket->data["puntoentrega"][$li_i]);
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nomper=rtrim($aa_ds_cestaticket->data["nomper"][$li_i]);
				$ls_apeper=rtrim($aa_ds_cestaticket->data["apeper"][$li_i]);
				$ls_nombre=$ls_nomper." ".$ls_apeper;
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],0,".","");
				$worksheet->write($li_fila,0,$ls_codigoempresa,'');
				$worksheet->write($li_fila,1,$as_codprod,'');
				$worksheet->write($li_fila,2,$ls_puntoentrega,'');
				$worksheet->write($li_fila,3,$ls_cedper,'');
				$worksheet->write($li_fila,4,$ls_nombre,'');
				$worksheet->write($li_fila,5,$ld_monper,'');
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_accord_ticket_multivalor
	//---------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_IPSFA($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,$as_codprod,$as_punent,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_IPSFA
		//		   Access: private 
		//	    Arguments: as_ruta  // ruta 
		//                 as_anocurper //  Año en curso
		//                 as_mescurper //  Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos CESTA TICKET   
		//                 as_codcli // Código de Cliente   
		//                 as_codprod // Código del Producto 
		//                 as_punent // Punto de Entrega
		//                 ad_fecha // Fecha de Entrega
		//	  Description: genera el archivo txt a disco para  el IPSFA
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 23/07/2008 								
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if($li_count>0)
		{	
			$ls_nombrearchivo=$as_ruta."/cesta_ticket_".$as_anocurper."_".$as_mescurper.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			$ls_codorgcestic=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["codorgcestic"][1]);
			$li_totreg=str_pad($li_count,6,"0",0);
			$ld_montototal=($aa_ds_cestaticket->data["montototal"][1]*100);
			$ldec_monto=$this->io_funciones->uf_cerosizquierda(number_format($ld_montototal,0,".",""),15);
			$ad_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$ad_fecha=str_replace("-","",$ad_fecha);
			$ls_cadena="atmccbde".$as_codprod.$ls_codorgcestic.$li_totreg.$ldec_monto.$ad_fecha."\r\n";
			if ($ls_creararchivo)  //Chequea que el archivo este abierto				
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
				$lb_valido = false;
			}
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{   					
				$ls_nacper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["nacper"][$li_i]);     //nacionalidad
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["cedper"][$li_i]);     //cedula
				$ls_cedper=$this->io_funciones->uf_cerosizquierda($ls_cedper,10);			
				$ld_monto=($aa_ds_cestaticket->data["monto"][$li_i]*100);       //Monto aporte
				$ld_monto=$this->io_funciones->uf_cerosizquierda(number_format($ld_monto,0,".",""),13);
				$ls_cadena=$ls_nacper.$ls_cedper."00".$ld_monto."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_IPSFA	
	//---------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_metodo_todo_ticket_tarjeta($as_ruta,$as_anocurper,$as_mescurper,$ad_fecha,$aa_ds_cestaticket)
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_todo_ticket_tarjeta	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo		
		//                 aa_ds_cestaticket // arreglo (datastore) datos cestaticket 		
		//	      Returns: lb_valido True 
		//	  Description: Funcion que genera el archivo excel para el método de Todo Ticket Tarjeta
		//	   Creado Por: Ing. María Beatriz Unda
		// Fecha Creación: 25/09/2008								
		// Modificado Por: 										Fecha Última Modificación : 		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_origen=$as_ruta."/todoticket_tarjeta.xls";
		$ls_destino=$as_ruta."/todoticket_tarjeta_".$as_anocurper."_".$as_mescurper.".xls";
		copy($ls_origen,$ls_destino);
		chmod($ls_destino,0777);
		$fname = fopen ($ls_destino,"r+");
		$workbook = &new writeexcel_workbook($fname);
		$worksheet = &$workbook->addworksheet();
		$li_count=$aa_ds_cestaticket->getRowCount("codper");	
	
		$lo_titulo= &$workbook->addformat();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
	    $lo_titulo->set_size('8');	
		
		if ($li_count>0)
		{
		    
			$worksheet->write(0,0,"Nacionalidad",'');
			$worksheet->write(0,1,"Cédula del Beneficiario",'');
			$worksheet->write(0,2,"Monto a abonar en la tarjeta",'');
			$worksheet->write(0,3,"Fecha Valor",'');			
			$li_fila=1;
			$li_i=0;
			$li_total=0;
			$ld_fecval=$this->io_fecha->suma_fechas($ad_fecha,1);
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_cedper=str_replace(".","",trim($aa_ds_cestaticket->data["cedper"][$li_i]));
				$ls_cedper=str_replace(",","",$ls_cedper);
				$ls_nacper=rtrim($aa_ds_cestaticket->data["nacionalidad"][$li_i]);								
				$ld_monper=number_format($aa_ds_cestaticket->data["monto"][$li_i],2,"","");
							
				$worksheet->write($li_fila,0,$ls_nacper,'');
				$worksheet->write($li_fila,1,$ls_cedper,'');			
				$worksheet->write($li_fila,2,$ld_monper,'');
				$worksheet->write($li_fila,3,$ld_fecval,'');				
				$li_fila=$li_fila+1;
			}
			if ($lb_valido)
			{
				$this->io_mensajes->message("El archivo ".$ls_destino." fue creado.");
				$workbook->close();
				unset($workbook);
			}
			else
			{
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
				unset($worksheet);
				unset($workbook);
				unset($fname);
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_todo_ticket_tarjeta
	//---------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_metodo_EfecTicket($as_ruta,$as_anocurper,$as_mescurper,$aa_ds_cestaticket,$as_codcli,
	                               $as_codprod, $as_punent,$ad_fecha)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_EfectTicket
		//		   Access: private 
		//	    Arguments: as_ruta  // ruta 
		//                 as_anocurper //  Año en curso
		//                 as_mescurper //  Mes en Curso
		//                 aa_ds_cestaticket // arreglo (datastore) datos CESTA TICKET   
		//                 as_codcli // Código de Cliente   
		//                 as_codprod // Código del Producto 
		//                 as_punent // Punto de Entrega
		//                 ad_fecha // Fecha de Entrega
		//	  Description: genera el archivo txt a disco para  uf_metodo_EfectTicket,  pago de Cesta Ticket
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 12/02/2009								
		// Modificado Por: 														Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_count=$aa_ds_cestaticket->getRowCount("codper");
		if($li_count>0)
		{	
			$ls_codorgcestic=rtrim($aa_ds_cestaticket->data["codorgcestic"][1]);// codigo del organismo informaciòn historica en sno_hnomina
			$ls_numope=$this->io_sno->uf_select_config("SNO","GEN_DISK_CETSA","EFECTICKET","1","I");
			$ls_numope=intval($this->io_funciones->uf_trim($ls_numope), 10);
			$ls_numope=str_pad($ls_numope,2,"0",0);
			$lb_valido=$this->io_sno->uf_insert_config("SNO","GEN_DISK_CETSA","EFECTICKET",$ls_numope+1,"I");
			$ls_nombrearchivo=$as_ruta."/SATA".$ls_codorgcestic.$ls_numope.".txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			//$ad_fecha=$this->io_funciones->uf_convertirdatetobd($ad_fecha);
			$ad_fecha=str_replace("/","",$ad_fecha);
		
			if ($ls_creararchivo)  //Chequea que el archivo este abierto				
			{
				if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
				{
					$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			else
			{
				$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
				$lb_valido = false;
			}
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{   					
				$ls_nacper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["nacper"][$li_i]);     //nacionalidad
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_cestaticket->data["cedper"][$li_i]);     //cedula
				$ls_cedper=str_pad($ls_cedper,11," ");			
				$ld_monto=($aa_ds_cestaticket->data["monto"][$li_i]*100);       //Monto aporte
				$ld_monto=$this->io_funciones->uf_cerosizquierda(number_format($ld_monto,0,".",""),21);
				$ls_cadena=$ls_nacper.$ls_cedper.$ld_monto.$ad_fecha."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end uf_metodo_EfectTicket
	//----------------------------------------------------------------------------------------------------------------------------------
}
?>
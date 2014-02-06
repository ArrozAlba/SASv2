<?php
class sigesp_sno_c_ajustes
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_nomina;
	var $ls_codemp;
	var $ls_codnom;
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_sno_c_ajustes()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_sno_c_ajustes
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("class_folder/class_funciones_nomina.php");
		$this->io_fun_nomina=new class_funciones_nomina();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->ls_codnom=$_SESSION["la_nomina"]["codnom"];			
	}// end function sigesp_sno_c_ajustes
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_cargo)
		//	  Description: Destructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($io_include);
		unset($io_conexion);
		unset($this->io_sql);	
		unset($this->io_mensajes);		
		unset($this->io_funciones);		
		unset($this->io_seguridad);
		unset($this->io_fun_nomina);
        unset($this->ls_codemp);
        unset($this->ls_codnom);
        
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_ajustaraporte($as_codperi,$as_codconc,$as_codper,$ai_valsal,$as_tipsal,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_ajustaraporte
		//		   Access: public  (sigesp_sno_p_ajustaraporte.php)
		//	    Arguments: as_codperi  // Código del Período
		//				   as_codconc  // Código del Concepto
		//				   as_codper  // Código del Personal
		//				   ai_valsal  // Valor de la Salida
		//				   as_tipsal  // Tipo de Salida
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta una salida de Tipo Q1 ó Q2 a un concepto y personal en específico
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$ls_sql="INSERT INTO sno_salida(codemp,codnom,codperi,codper,codconc,tipsal,valsal,monacusal,salsal)".
				"VALUES('".$this->ls_codemp."','".$this->ls_codnom."','".$as_codperi."','".$as_codper."',".
				"'".$as_codconc."','".$as_tipsal."',".$ai_valsal.",0.00,0.00)";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustes MÉTODO->uf_insert_ajustaraporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó la Salida de Tipo ".$as_tipsal." del concepto ".$as_codconc." Período ".$as_codperi.
							 " del personal ".$as_codper." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		return $lb_valido;
	}// end function uf_insert_ajustaraporte	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_delete_ajustaraporte($as_codperi,$as_codconc,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_delete_ajustaraporte
		//		   Access: public (sigesp_sno_p_ajustaraporte.php)
		//	    Arguments: as_codperi  // código de Período
		//				   as_codconc  // Código de concepto
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las Salidas de tipo Q1 y Q2 de este concepto y período para volver a insertarlas
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="DELETE ".
				"  FROM sno_salida ".
				" WHERE codemp='".$this->ls_codemp."' ".
				"   AND codnom='".$this->ls_codnom."' ".
				"   AND codperi='".$as_codperi."' ".
				"   AND codconc='".$as_codconc."' ".
				"   AND (tipsal='Q1' OR tipsal='Q2')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustes MÉTODO->uf_delete_ajustaraporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las Salidas de tipo Q1 y Q2 del concepto ".$as_codconc." Período ".$as_codperi.
							 " asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
    }// end function uf_delete_ajustaraporte	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ajustaraporte(&$as_codperi,&$as_codconc,&$ai_totrows,&$aa_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_load_ajustaraporte
		//		   Access : public (sigesp_sno_p_ajustaraporte.php)
		//	    Arguments : as_codperi  // Perído del que se quiere buscar la salida
		//				    as_codconc  // Código del concepto
		//				    ai_totrows  // Total de Filas
		//				    aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns : $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description : Función que obtiene las Salidas dado un concepto
		//	   Creado Por : Ing. Yesenia Moreno
		// Fecha Creación : 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper, ".
			    "	    (SELECT COALESCE(valsal,0.00) FROM sno_salida ".
				"         WHERE sno_salida.codperi = '".$as_codperi."'".
				"		    AND sno_salida.codconc = '".$as_codconc."'".
				"		    AND sno_salida.tipsal = 'P1'".
				"		    AND sno_salida.codemp = sno_personalnomina.codemp".
				"		    AND sno_salida.codnom = sno_personalnomina.codnom".
				"		    AND sno_salida.codper = sno_personalnomina.codper ) AS valor_p1, ".
			    "	    (SELECT COALESCE(valsal,0.00) FROM sno_salida".
				"         WHERE sno_salida.codperi = '".$as_codperi."'".
				"		    AND sno_salida.codconc = '".$as_codconc."'".
				"		    AND sno_salida.tipsal = 'P2'".
		        "		    AND sno_salida.codemp = sno_personalnomina.codemp".
				"		    AND sno_salida.codnom = sno_personalnomina.codnom".
				"		    AND sno_salida.codper = sno_personalnomina.codper) AS valor_p2, ".
			    "	    (SELECT COALESCE(valsal,0.00) FROM sno_salida".
				"         WHERE sno_salida.codperi = '".$as_codperi."'".
				"		    AND sno_salida.codconc = '".$as_codconc."'".
				"		    AND sno_salida.tipsal = 'Q1'".
			    "	        AND sno_salida.codemp = sno_personalnomina.codemp".
				"		    AND sno_salida.codnom = sno_personalnomina.codnom".
				"		    AND sno_salida.codper = sno_personalnomina.codper) AS valor_q1, ".
			    "	    (SELECT COALESCE(valsal,0.00) FROM sno_salida".
				"         WHERE sno_salida.codperi = '".$as_codperi."'".
				"		    AND sno_salida.codconc = '".$as_codconc."'".
				"		    AND sno_salida.tipsal = 'Q2'".
           		"		    AND sno_salida.codemp = sno_personalnomina.codemp".
				"		    AND sno_salida.codnom = sno_personalnomina.codnom".
				"		    AND sno_salida.codper = sno_personalnomina.codper) AS valor_q2 ".
				"  FROM sno_personal, sno_personalnomina".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."'".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."'".
				"   AND sno_personalnomina.staper<>'3'".
				"   AND sno_personal.codemp = sno_personalnomina.codemp".
				"   AND sno_personal.codper = sno_personalnomina.codper";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustes MÉTODO->uf_load_ajustaraporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows=$ai_totrows+1;
				$as_codper=$row["codper"];
				$as_nomper=$row["apeper"]." ".$row["nomper"];
				$ai_valorp1=$row["valor_p1"];
				$ai_valorp1=$this->io_fun_nomina->uf_formatonumerico($ai_valorp1);
				$ai_valorp2=$row["valor_p2"];
				$ai_valorp2=$this->io_fun_nomina->uf_formatonumerico($ai_valorp2);
				$ai_valorq1=$row["valor_q1"];
				$ai_valorq1=$this->io_fun_nomina->uf_formatonumerico($ai_valorq1);
				$ai_valorq2=$row["valor_q2"];
				$ai_valorq2=$this->io_fun_nomina->uf_formatonumerico($ai_valorq2);
				$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=hidden id=txtcodper".$ai_totrows." value=".$as_codper.">".$as_codper."";
				$aa_object[$ai_totrows][2]=" ".$as_nomper." ";
				$aa_object[$ai_totrows][3]="<div align='right'>".$ai_valorp1."</div>";
				$aa_object[$ai_totrows][4]="<div align='right'>".$ai_valorp2."</div>";
				$aa_object[$ai_totrows][5]="<input name=txtvalQ1".$ai_totrows." type=text id=txtvalQ1".$ai_totrows." class=sin-borde size=20 maxlength=23 value=".$ai_valorq1." onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_actualizarmonto(this); style=text-align:right>";
				$aa_object[$ai_totrows][6]="<input name=txtvalQ2".$ai_totrows." type=text id=txtvalQ2".$ai_totrows." class=sin-borde size=20 maxlength=23 value=".$ai_valorq2." onKeyPress=return(ue_formatonumero(this,'.',',',event)); onBlur=ue_actualizarmonto(this); style=text-align:right>";
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_load_ajustaraporte	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_update_ajustarsueldo($as_codper,$ai_sueact,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_update_ajustarsueldo
		//		   Access: public (sigesp_sno_p_ajustarsueldo.php)
		//	    Arguments: as_codper  // Código del Personal
		//				   ai_sueact  // Sueldo Actual
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el update ó False si hubo error en el update
		//	  Description: Funcion que actualiza el sueldo de el personal en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 16/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;	
		$ls_sql="UPDATE sno_personalnomina ".
				"   SET sueper = ".$ai_sueact." ".
				" WHERE codemp = '".$this->ls_codemp."' ".
				"   AND codnom = '".$this->ls_codnom."' ".
				"   AND codper = '".$as_codper."' ";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Ajustes MÉTODO->uf_update_ajustarsueldo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion ="Actualizó el sueldo del personal ".$as_codper." en  ".$ai_sueact." asociado a la nómina ".$this->ls_codnom;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////
		}
		
		return $lb_valido;
	}// end function uf_update_ajustarsueldo	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_obtenertotal(&$ai_totper)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_obtenertotal
		//		   Access: private 
		//	    Arguments: ai_totper  // Código del concepto
		//	      Returns: lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene el total de personas en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT COUNT(codper) AS total ".
				"  FROM sno_personalnomina ".
				" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Ajustar Sueldo MÉTODO->uf_obtenertotal ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totper=$row["total"];
			}
			$this->io_sql->free_result($rs_data);		
		}
		return $lb_valido;
	}// end function uf_obtenertotal	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_ajustarsueldo($as_codcar,$ai_sueperdes,$ai_sueperhas,&$ai_totper,&$ai_totperfil,&$ai_totrows,&$aa_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_ajustarsueldo
		//		   Access: public (sigesp_sno_p_ajustarsueldo.php)
		//	    Arguments: as_codcar  // Código de Cargo
		//				   ai_sueperdes  // sueldo del personal desde
		//				   ai_sueperhas  // sueldo del personal hasta
		//				   ai_totper  // total de personas en la nómina
		//				   ai_totperfil  // total de personas filtradas
		//				   ai_totrows  // Total de Filas
		//				   aa_object  //  Arreglo de objectos que se van a imprimir
		//	      Returns: $lb_valido True si se ejecuto el select ó False si hubo error en el select
		//	  Description: Función que obtiene el sueldo de un personal dado un ó sueldo
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/02/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$lb_valido=$this->uf_obtenertotal($ai_totper);
		if($lb_valido)
		{
			$ai_sueperdes=str_replace(".","",$ai_sueperdes);
			$ai_sueperdes=str_replace(",",".",$ai_sueperdes);
			$ai_sueperhas=str_replace(".","",$ai_sueperhas);
			$ai_sueperhas=str_replace(",",".",$ai_sueperhas);
			$ls_criterio="";
			if(($ai_sueperdes!="")&&($ai_sueperdes>0))
			{
				$ls_criterio=$ls_criterio."   AND sno_personalnomina.sueper>=".$ai_sueperdes."";
			}
			if(($ai_sueperhas!="")&&($ai_sueperhas>0))
			{
				$ls_criterio=$ls_criterio."   AND sno_personalnomina.sueper<=".$ai_sueperhas."";
			}
			if($as_codcar!="")
			{
				$ls_criterio=$ls_criterio."   AND sno_personalnomina.codcar='".$as_codcar."'";
			}		
			$ls_sql="SELECT sno_personal.codper, sno_personal.nomper, sno_personal.apeper, sno_personalnomina.sueper ".
					"  FROM sno_personal, sno_personalnomina ".
					" WHERE sno_personalnomina.codemp='".$this->ls_codemp."' ".
					"   AND sno_personalnomina.codnom='".$this->ls_codnom."' ".
					"   AND sno_personalnomina.staper<>'3' ".
					$ls_criterio.
					"   AND sno_personal.codemp = sno_personalnomina.codemp ".
					"   AND sno_personal.codper = sno_personalnomina.codper ";
			$rs_data=$this->io_sql->select($ls_sql);
			if($rs_data===false)
			{
				$this->io_mensajes->message("CLASE->Ajustes MÉTODO->uf_load_ajustarsueldo ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
				$lb_valido=false;
			}
			else
			{
				$ai_totrows=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$ai_totrows=$ai_totrows+1;
					$ls_codper=$row["codper"];
					$ls_nomper=$row["apeper"]." ".$row["nomper"];
					$li_sueper=$row["sueper"];
					$li_sueper=$this->io_fun_nomina->uf_formatonumerico($li_sueper);
					$aa_object[$ai_totrows][1]="<input name=txtcodper".$ai_totrows." type=text id=txtcodper".$ai_totrows." class=sin-borde size=10 maxlength=10 value='".$ls_codper."' readonly>";
					$aa_object[$ai_totrows][2]="<input name=txtnomper".$ai_totrows." type=text id=txtnomper".$ai_totrows." class=sin-borde size=60 maxlength=100 value='".$ls_nomper."' readonly>";
					$aa_object[$ai_totrows][3]="<input name=txtsueper".$ai_totrows." type=text id=txtsueper".$ai_totrows." class=sin-borde size=20 maxlength=23 value='".$li_sueper."' style=text-align:right readonly>";
					$aa_object[$ai_totrows][4]="<input name=txtsuenue".$ai_totrows." type=text id=txtsuenue".$ai_totrows." class=sin-borde size=20 maxlength=23 value='".$li_sueper."' onKeyPress=return(ue_formatonumero(this,'.',',',event)); style=text-align:right>";
				}
				$this->io_sql->free_result($rs_data);		
				$ai_totperfil=$ai_totrows;
			}
		}
		return $lb_valido;
	}// end function uf_load_ajustarsueldo	
	//-----------------------------------------------------------------------------------------------------------------------------------	

	
}
?>
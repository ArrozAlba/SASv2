<?php

class sigesp_sss_c_auditoria
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sss_c_auditoria()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_msg=new class_mensajes();
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion = new class_funciones();
	}
	
	function uf_sss_llenar_combo_sistemas(&$la_sistema)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_llenar_combo_sistemas
		//         Access: public 
		//      Argumento: $la_sistema // arreglo de valores que puede tomar el combo.
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de llenar el arreglo del combo de Sistemas
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación:22/02/2006									Fecha Última Modificación : 22/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT * FROM sss_sistemas".
				" ORDER BY nomsis ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->auditoria MÉTODO->uf_sss_llenar_combo_sistemas ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$la_sistema["codsis"][$li_pos]=$row["codsis"];   
				$la_sistema["nomsis"][$li_pos]=$row["nomsis"];   
			}
		}
	} // end function uf_sss_llenar_combo_sistemas

	function uf_sss_pintar_combo_sistemas($la_sistema,$ls_sistema)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_pintar_combo_sistemas
		//         Access: public 
		//      Argumento: $la_sistema // arreglo de valores que puede tomar el combo.
		//  			   $ls_sistema // item seleccionado.
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de cargar (pintar) el combo de sistemas 
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación:22/02/2006									Fecha Última Modificación : 22/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbsistemas' id='cmbsistemas' style='width:200px'>";
		print "<option>Todos</option>";
		$li_total=count($la_sistema["codsis"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($la_sistema["codsis"][$i]==$ls_sistema)
			{
				print "<option value='".$la_sistema["codsis"][$i]."' selected>".$la_sistema["nomsis"][$i]."</option>";
			}
			else
			{
				print "<option value='".$la_sistema["codsis"][$i]."'>".$la_sistema["nomsis"][$i]."</option>";
			}
		}
		print"</select>";
	} // end function uf_sss_pintar_combo_sistemas

	function uf_sss_llenar_combo_eventos(&$la_eventos)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_llenar_combo_eventos
		//         Access: public 
		//      Argumento: $la_eventos // arreglo de valores que puede tomar el combo.
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de llenar el arreglo del combo de Eventos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación:22/02/2006									Fecha Última Modificación : 22/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_sql="SELECT * FROM sss_eventos".
				" ORDER BY evento ASC";
		$rs_data=$this->io_sql->select($ls_sql);
		$li_pos=0;
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->auditoria MÉTODO->uf_sss_llenar_combo_eventos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$la_eventos["evento"][$li_pos]=$row["evento"];   
			}
		}
	}  // end function uf_sss_llenar_combo_eventos

	function uf_sss_pintar_combo_eventos($la_eventos,$ls_evento)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_pintar_combo_usuarios
		//         Access: public 
		//      Argumento: $la_eventos // arreglo de valores que puede tomar el combo.
		//  			   $ls_evento // item seleccionado.
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de llenar el arreglo del combo de Eventos
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/02/2006									Fecha Última Modificación : 22/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		print "<select name='cmbevento' id='cmbevento' style='width:120px'>";
		print "<option>Todos</option>";
		$li_total=count($la_eventos["evento"]);
		for($i=1; $i <= $li_total ; $i++)
		{			
			if($la_eventos["evento"][$i]==$ls_evento)
			{
				print "<option value='".$la_eventos["evento"][$i]."' selected>".$la_eventos["evento"][$i]."</option>";
			}
			else
			{
				print "<option value='".$la_eventos["evento"][$i]."'>".$la_eventos["evento"][$i]."</option>";
			}
		}
		print"</select>";
	} // end  function uf_sss_pintar_combo_eventos

	function uf_sss_select_registro_eventos($as_codemp,$as_codusu,$as_codsis,$as_evento,$ad_datedesde,$ad_datehasta,$inicio,
											$registros,$as_documento,&$rs_data)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_select_registro_eventos
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_codsis    // codigo de sistema
		//  			   $as_evento    // codigo de evento
		//  			   $ad_datedesde // inicio del intervalo de fecha
		//  			   $ad_datedesde // fin de intervalo de fecha
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga de verificar si las paginas de un sistema estan en la tabla de sss_sistemas_ventanas
	    //              	y los guarda en un arreglo.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 22/02/2006									Fecha Última Modificación : 22/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlint="";
		if(($ad_datedesde!="")&&($ad_datehasta!=""))
		{
			$ls_min=" 23:59:59";
			$ls_sqlint= " AND fecevetra >= '".$ad_datedesde."  00:00:00'".
			            " AND fecevetra <='".$ad_datehasta.$ls_min."'" ;
		}
		
		$ls_gestor=$_SESSION["ls_gestor"];
		switch($ls_gestor)
		{
			case "MYSQLT":
				$ls_sql= "SELECT sss_registro_eventos.codsis,sss_registro_eventos.codusu,sss_registro_eventos.evento,".
						 "       sss_registro_eventos.fecevetra,sss_registro_eventos.equevetra,sss_registro_eventos.desevetra,".
						 "       ".
						 "       (SELECT titven FROM sss_sistemas_ventanas".
						 "         WHERE sss_sistemas_ventanas.codsis=sss_registro_eventos.codsis".
						 "           AND sss_sistemas_ventanas.nomven=sss_registro_eventos.nomven)as titven". 
						 "  FROM sss_registro_eventos ".
						 " WHERE codemp = '".$as_codemp."'".
						 "   AND codusu LIKE '".$as_codusu."'".
						 "   AND codsis LIKE '".$as_codsis."'".
						 "   AND evento LIKE '".$as_evento."'".
						 "   AND desevetra LIKE '".$as_documento."'".
						 $ls_sqlint.
						 " ORDER BY numeve".
						 " LIMIT ".$inicio.",".$registros."";
			break;
			case "POSTGRES":
				$ls_sql= "SELECT sss_registro_eventos.codsis,sss_registro_eventos.codusu,sss_registro_eventos.evento,".
						 "       sss_registro_eventos.fecevetra,sss_registro_eventos.equevetra,sss_registro_eventos.desevetra,".
						 "       ".
						 "       (SELECT titven FROM sss_sistemas_ventanas".
						 "         WHERE sss_sistemas_ventanas.codsis=sss_registro_eventos.codsis".
						 "           AND sss_sistemas_ventanas.nomven=sss_registro_eventos.nomven)as titven". 
						 "  FROM sss_registro_eventos ".
						 " WHERE codemp = '".$as_codemp."'".
						 "   AND codusu LIKE '".$as_codusu."'".
						 "   AND codsis LIKE '".$as_codsis."'".
						 "   AND evento LIKE '".$as_evento."'".
						 "   AND desevetra LIKE '".$as_documento."'".
						 $ls_sqlint.
						 " ORDER BY numeve".
						 " LIMIT ".$registros." OFFSET ".$inicio."";
			
			break;
			case "INFORMIX":
				$ls_sql= "SELECT SKIP  ".$inicio." FIRST ".$registros."   sss_registro_eventos.codsis,sss_registro_eventos.codusu,sss_registro_eventos.evento,".
						 "       sss_registro_eventos.fecevetra,sss_registro_eventos.equevetra,sss_registro_eventos.desevetra,".
						 "       ".
						 "       (SELECT titven FROM sss_sistemas_ventanas".
						 "         WHERE sss_sistemas_ventanas.codsis=sss_registro_eventos.codsis".
						 "           AND sss_sistemas_ventanas.nomven=sss_registro_eventos.nomven)as titven". 
						 "  FROM sss_registro_eventos ".
						 " WHERE codemp = '".$as_codemp."'".
						 "   AND codusu LIKE '".$as_codusu."'".
						 "   AND codsis LIKE '".$as_codsis."'".
						 "   AND evento LIKE '".$as_evento."'".
						 "   AND desevetra LIKE '".$as_documento."'".
						 $ls_sqlint.
						 " ORDER BY numeve";
			
			break;
		}
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->auditoria MÉTODO->uf_sss_select_registro_eventos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$lb_valido=true;
		/*
			print "<table width=780 height=33 border=0 cellpadding=0 cellspacing=0>";
			print "<tr class=titulo-celdanew>";
			print "  <td width=46 height=20>Sistema</td>";
			print "  <td width=49>Usuario</td>";
			print "  <td width=39>Evento</td>";
			print "  <td width=174>Ventana</td>";
			print "  <td width=66 align='center'>Fecha/Hora</td>";
			print "  <td width=37>Equipo</td>";
			print "  <td width=231>Descripci&oacute;n del evento </td>";
			print "</tr>";
			$li_pos=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_pos=$li_pos+1;
				$la_eventos["codsis"][$li_pos]=$row["codsis"];   
				$la_eventos["codusu"][$li_pos]=$row["codusu"];   
				$la_eventos["evento"][$li_pos]=$row["evento"];   
				$la_eventos["titven"][$li_pos]=$row["titven"];   
				$la_eventos["fecevetra"][$li_pos]=$row["fecevetra"];   
				$la_eventos["equevetra"][$li_pos]=$row["equevetra"];   
				$la_eventos["desevetra"][$li_pos]=$row["desevetra"];   
				$la_eventos["fecevetra"][$li_pos]=date("d/m/Y H:i",strtotime($la_eventos["fecevetra"][$li_pos]));

				if(($li_pos%2!=0))
				{
					$ls_color="class=celdas-blancas";
				}
				else
				{
					$ls_color="class=celdas-azules";
				}				

				print("<tr ".$ls_color.">");
				print("<td align='center'>".$la_eventos["codsis"][$li_pos]."</td>");
				print("<td>".$la_eventos["codusu"][$li_pos]."</td>");
				print("<td>".$la_eventos["evento"][$li_pos]."</td>");
				print("<td>"."<b> ".$la_eventos["titven"][$li_pos]."</b> "."</td>");
				print("<td align='center'>".$la_eventos["fecevetra"][$li_pos]."</td>");
				print("<td>".$la_eventos["equevetra"][$li_pos]."</td>");
				print("<td>".$la_eventos["desevetra"][$li_pos]."</td>");
				print("</tr>");
			}
			print "</table> ";
			if($li_pos==0)
			{
				$lb_valido=false;
			}
		*/}
		return $lb_valido;
	} // end  function uf_sss_select_registro_eventos

	function uf_sss_obterer_total_registros($as_codemp,$as_codusu,$as_codsis,$as_evento,$ad_datedesde,$ad_datehasta,$as_documento,&$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_sss_obterer_total_registros
		//         Access: public 
		//      Argumento: $as_codemp    // codigo de empresa
		//  			   $as_codusu    // codigo de usuario
		//  			   $as_codsis    // codigo de sistema
		//  			   $as_evento    // codigo de evento
		//  			   $ad_datedesde // inicio del intervalo de fecha
		//  			   $ad_datedesde // fin de intervalo de fecha
		//	      Returns: Retorna un Booleano
		//    Description: Función que se encarga obtener el total de registros.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 29/11/2006									Fecha Última Modificación : 	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sqlint="";
		if(($ad_datedesde!="")&&($ad_datehasta!=""))
		{
			$ls_min=" 23:59:59";
			$ls_sqlint= " AND fecevetra >= '".$ad_datedesde."  00:00:00'".
			            " AND fecevetra <='".$ad_datehasta.$ls_min."'" ;
		}
		
		$ls_sql="SELECT sss_registro_eventos.codsis,sss_registro_eventos.codusu,sss_registro_eventos.evento,".
		        "       sss_registro_eventos.fecevetra,sss_registro_eventos.equevetra,sss_registro_eventos.desevetra".
				"  FROM sss_registro_eventos ".
				" WHERE codemp = '".$as_codemp."'".
				"   AND codusu LIKE '".$as_codusu."'".
				"   AND codsis LIKE '".$as_codsis."'".
				"   AND evento LIKE '".$as_evento."'".
				"   AND desevetra LIKE '".$as_documento."'".
				$ls_sqlint.
				" ORDER BY numeve";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			print $this->io_sql->message;
			$this->io_msg->message("CLASE->auditoria MÉTODO->uf_sss_obterer_total_registros ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			return false;
		}
		else
		{
			$li_pos=0;
			$ai_totrows=$this->io_sql->num_rows($rs_data);
		}
		return $lb_valido;
	} // end  function uf_sss_obterer_total_registros
	
} // end  class sigesp_sss_c_permisos_globales
?>

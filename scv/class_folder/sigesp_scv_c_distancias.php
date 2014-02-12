<?php
class sigesp_scv_c_distancias
{

	var $ls_sql;
	
	function sigesp_scv_c_distancias($conn)
	{
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		require_once("../shared/class_folder/class_mensajes.php");
		$this->seguridad = new sigesp_c_seguridad();          
		$this->io_funcion = new class_funciones();
		$this->io_sql= new class_sql($conn);		
		$this->io_msg= new class_mensajes(); 
	}
 
	function uf_scv_load_destinos($as_codpaiori,$as_codestori,$as_codciuori,&$ai_totrows,&$ao_object) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_load_destinos
		//	          Access:  public
		//	       Arguments:  $as_codpaiori  // código de pais destino
		//        			   $as_codestori  // código de estado destino
		//        			   $as_codciuori  // código de ciudad destino
		//  			   	   $ao_object    // arreglo de objetos para pintar el grid
		//  			       $ai_montot    // monto total del grid
		//	         Returns:  $lb_valido
		//	     Description:  Función que se encarga cargar las distancias entre las ciudades que tengan esos datos
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT scv_distancias.codpaides,scv_distancias.codestdes,scv_distancias.codciudes,scv_distancias.cankms,".
				" (SELECT despai FROM sigesp_pais".
				" 	 WHERE codpaides=codpai) AS despaides,".
				" (SELECT desest FROM sigesp_estados".
				"	 WHERE codpaides=codpai".
				"	 AND codestdes=codest) AS desestdes,".
				" (SELECT desciu FROM scv_ciudades".
				"	 WHERE codpaides=codpai".
				"	 AND codestdes=codest".
				"	 AND codciudes=codciu) AS desciudes".
				" FROM 	   scv_distancias ".
				" WHERE    codpaiori='".$as_codpaiori."'".
				" AND      codestori='".$as_codestori."'".
				" AND      codciuori='".$as_codciuori."'".
				" ORDER BY codpaides,codestdes,codciudes";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigecp_scv_c_distancias METODO->uf_scv_load_destinos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codpaides=$row["codpaides"];
				$ls_despaides=$row["despaides"];
				$ls_codestdes=$row["codestdes"];
				$ls_desestdes=$row["desestdes"];
				$ls_codciudes=$row["codciudes"];
				$ls_desciudes=$row["desciudes"];
				$li_cankms=$row["cankms"];
				$li_cankms=number_format($li_cankms,2,",",".");
				
				$ai_totrows++;
				$ao_object[$ai_totrows][1]="<input name=txtdespaides".$ai_totrows." type=text   id=txtdespaides".$ai_totrows." class=sin-borde size=35 value='". $ls_despaides ."' readonly>".
										   "<input name=txtcodpaides".$ai_totrows." type=hidden id=txtcodpaides".$ai_totrows." class=sin-borde size=17 value='". $ls_codpaides ."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtdesestdes".$ai_totrows." type=text   id=txtdesestdes".$ai_totrows." class=sin-borde size=20 value='". $ls_desestdes ."' readonly>".
										   "<input name=txtcodestdes".$ai_totrows." type=hidden id=txtcodestdes".$ai_totrows." class=sin-borde size=17 value='". $ls_codestdes ."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtdesciudes".$ai_totrows." type=text   id=txtdesciudes".$ai_totrows." class=sin-borde size=35 value='". $ls_desciudes ."' readonly>".
										   "<input name=txtcodciudes".$ai_totrows." type=hidden id=txtcodciudes".$ai_totrows." class=sin-borde size=17 value='". $ls_codciudes ."' readonly>";
				$ao_object[$ai_totrows][4]="<input name=txtcankms".$ai_totrows."    type=text   id=txtcankms".$ai_totrows."    class=sin-borde size=6 value='". $li_cankms ."'   onKeyPress=return(ue_formatonumero(this,'.',',',event));>";
				$ao_object[$ai_totrows][5]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // fin function uf_scv_load_destinos
	
	function uf_scv_select_distancias($as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_select_distancias
		//	          Access:  public
		//	       Arguments:  $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga de verificar la existencia de una distancia entre ciudades
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" SELECT scv_distancias.codpaiori".
				" FROM  scv_distancias".
				" WHERE codpaiori='".$as_codpaiori."'".
				" AND   codestori='".$as_codestori."'".
				" AND   codciuori='".$as_codciuori."'".
				" AND   codpaides='".$as_codpaides."'".
				" AND   codestdes='".$as_codestdes."'".
				" AND   codciudes='".$as_codciudes."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if ($rs_data===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_distancias METODO->uf_scv_select_distancias ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			
		}
		return $lb_valido;
	} // fin function uf_scv_select_distancias

	function uf_scv_insert_distancias($as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes,
									  $ai_cankms,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_insert_distancias
		//	          Access:  public
		//	       Arguments:  $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//        			   $ai_cankms    // distancia en kilometros
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga insertar una la distancia existente entre dos ciudades
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql= " INSERT INTO scv_distancias (codpaiori,codestori,codciuori,codpaides,codestdes,codciudes,cankms) ".
				 " VALUES ('".$as_codpaiori."','".$as_codestori."','".$as_codciuori."','".$as_codpaides."',".
				 "         '".$as_codestdes."','".$as_codciudes."','".$ai_cankms."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->sigecp_scv_c_distancias METODO->uf_scv_insert_distancias ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion= "Insertó la distancia entre las ciudades ".$as_codpaiori." - ".$as_codestori." - ".$as_codciuori.
							 " y la  ".$as_codpaides." - ".$as_codestdes." - ".$as_codciudes;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_insert_distancias

	function uf_scv_update_distancias($as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes,
									  $ai_cankms,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_update_distancias
		//	          Access:  public
		//	       Arguments:  $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//        			   $ai_cankms    // distancia en kilometros
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga actualizar la distancia entre dos ciudades
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$ls_sql=" UPDATE scv_distancias SET  cankms='".$ai_cankms."' ".
				" WHERE codpaiori='".$as_codpaiori."'".
				" AND   codestori='".$as_codestori."'".
				" AND   codciuori='".$as_codciuori."'".
				" AND   codpaides='".$as_codpaides."'".
				" AND   codestdes='".$as_codestdes."'".
				" AND   codciudes='".$as_codciudes."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		{
			$this->io_msg->message("CLASE->sigesp_scv_c_distancias METODO->uf_scv_update_distancias ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="UPDATE";
			$ls_descripcion= "Actualizó la distancia entre las ciudades ".$as_codpaiori." - ".$as_codestori." - ".$as_codciuori.
							 " y la  ".$as_codpaides." - ".$as_codestdes." - ".$as_codciudes;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$lb_valido=true;
		}
		return $lb_valido;
	} // fin function uf_scv_update_distancias

	function uf_scv_delete_distancias($as_codpaiori,$as_codestori,$as_codciuori,$as_codpaides,$as_codestdes,$as_codciudes,$aa_seguridad) 
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	          Metodo:  uf_scv_delete_distancias
		//	          Access:  public
		//	       Arguments:  $as_codpaiori // código de pais de origen
		//        			   $as_codestori // código de estado de origen
		//        			   $as_codciuori // código de ciudad de origen
		//        			   $as_codpaides // código de pais destino
		//        			   $as_codestdes // código de estado destino
		//        			   $as_codciudes // código de ciudad destino
		//        			   $aa_seguridad // arreglo de seguridad
		//	         Returns:  $lb_valido.
		//	     Description:  Función que se encarga eliminar la distancia entre dos ciudades
		//     Elaborado Por:  Ing. Luis Anibal Lang
		// Fecha de Creación:  04/10/2006      
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		$lb_valido=false;
		$this->io_sql->begin_transaction();
		$ls_sql=" DELETE FROM scv_distancias ".
				" WHERE codpaiori='".$as_codpaiori."'".
				" AND   codestori='".$as_codestori."'".
				" AND   codciuori='".$as_codciuori."'".
				" AND   codpaides='".$as_codpaides."'".
				" AND   codestdes='".$as_codestdes."'".
				" AND   codciudes='".$as_codciudes."'";
		$rs_data=$this->io_sql->execute($ls_sql);
		if ($rs_data===false)
		{
			$this->io_sql->rollback();
			$this->io_msg->message("CLASE->sigesp_scv_c_ciudad METODO->uf_scv_delete_distancias ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion= "Eliminó la distancia entre las ciudades ".$as_codpaiori." - ".$as_codestori." - ".$as_codciuori.
							 " y la  ".$as_codpaides." - ".$as_codestdes." - ".$as_codciudes;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               ///////////////////////////
			$this->io_sql->commit();
			$lb_valido= true;
		} 		 
		return $lb_valido;
	} // fin function uf_scv_delete_distancias
	
} // fin class sigesp_scv_c_ciudad
?>
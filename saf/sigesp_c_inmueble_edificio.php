<?php
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
class sigesp_c_inmueble_edificio
{
	var $dat;
	var $SQL;
	var $fun;
	
	function sigesp_c_inmueble_edificio()
	{
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");		
		$this->io_sql=new class_sql($io_conexion);
		$this->io_funcion=new class_funciones();	
		$this->io_msg=new class_mensajes();
		$this->seguridad= new sigesp_c_seguridad();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];	
	}
	
	function guardar($as_codact,$as_expact, $as_clasfun, $as_diract, $as_areatot, $as_areacons,$as_numpiso, $as_areatotpiso,
	                 $as_areanex, $as_lindero,$as_estlegprop, $as_avaluo, $as_feccont, $as_moncont, $as_existe, $aa_seguridad)
	{
	/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  guardar
		//	    Access:  public
		//	 Arguments:  // $as_codact // codigo del activo
		                 // $as_expact // codigo del Expediente
						 // $as_clasfun // clasificaciòn de la funciòn
						 // $as_diract // direccion del edificio
						 // $as_areatot // area total del edificio
						 // $areacons // area de la construccion
						 // $as_areatotpiso area total de los pisos
						 // $as_areanex // area de los anexos
						 // $as_lindero // linderos
						 // $as_estlegprop // estudios legales
						 //  $as_avaluo // avaluo
						 // $as_existe //inidica si el registro existo o no en l BD
		//                aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////
		$ls_valido=true;	
		switch ($as_existe)
		{
			case "FALSE":
			$ls_valido=$this->uf_saf_insert_inmueble_edificio($as_codact,$as_expact, $as_clasfun, $as_diract, $as_areatot,
	                                          $as_areacons,$as_numpiso, $as_areatotpiso, $as_areanex, $as_lindero,
											  $as_estlegprop, $as_avaluo, $as_feccont, $as_moncont, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Edificio fue Registrado");
			}			
			break;
			
			case "TRUE":
			$ls_valido=$this->uf_saf_update_inmueble_edificio($as_codact,$as_expact, $as_clasfun, $as_diract, $as_areatot,
	                                          $as_areacons,$as_numpiso, $as_areatotpiso, $as_areanex, $as_lindero,
											  $as_estlegprop, $as_avaluo, $as_feccont, $as_moncont, $aa_seguridad);
			if ($ls_valido)
			{
				$this->io_msg->message("El Edificio fue Actualizado");
			}	
			break;
		}
		return $ls_valido;
	}//fin de guardar()
//--------------------------------------------------------------------------------------------------------------------------------------	
	function  uf_saf_insert_inmueble_edificio($as_codact,$as_expact, $as_clasfun, $as_diract, $as_areatot,
	                                          $as_areacons,$as_numpiso, $as_areatotpiso, $as_areanex, $as_lindero,
											  $as_estlegprop, $as_avaluo, $as_feccont, $as_moncont, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_saf_insert_inmueble_edificio
		//	    Access:  public
		//	 Arguments:  // $as_codact // codigo del activo
		                 // $as_expact // codigo del Expediente
						 // $as_clasfun // clasificaciòn de la funciòn
						 // $as_diract // direccion del edificio
						 // $as_areatot // area total del edificio
						 // $areacons // area de la construccion
						 // $as_areatotpiso area total de los pisos
						 // $as_areanex // area de los anexos
						 // $as_lindero // linderos
						 // $as_estlegprop // estudios legales
						 //  $as_avaluo // avaluo
		//                aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;
		$ls_codemp=$this->ls_codemp;
		$as_feccont=$this->io_funcion->uf_convertirdatetobd($as_feccont);
		$as_moncont     = str_replace(".","",$as_moncont);
		$as_moncont     = str_replace(",",".",$as_moncont);		
		$as_areatot     = str_replace(".","",$as_areatot);
		$as_areatot     = str_replace(",",".",$as_areatot);		
		$as_areacons    = str_replace(".","",$as_areacons);
		$as_areacons    = str_replace(",",".",$as_areacons);		
		$as_areatotpiso = str_replace(".","",$as_areatotpiso);
		$as_areatotpiso = str_replace(",",".",$as_areatotpiso);		
		$as_areanex     = str_replace(".","",$as_areanex);
		$as_areanex     = str_replace(",",".",$as_areanex);
        $this->io_sql->begin_transaction();
		$ls_sql = " INSERT INTO saf_edificios(codemp, codact, expact, clasfun, diract, areatot, areacons, numpiso,  ". 
                  "                            areatotpiso, areanex, lindero, estlegprop, avaluo, feccont, moncont)  ".
                  "      VALUES ('".$ls_codemp."','".$as_codact."','".$as_expact."','".$as_clasfun."','".$as_diract."',".
				  "              ".$as_areatot.",".$as_areacons.",".$as_numpiso.",".$as_areatotpiso.",".$as_areanex.",
				                '".$as_lindero."','".$as_estlegprop."','".$as_avaluo."','".$as_feccont."',".$as_moncont.");" ;							
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_saf_insert_inmueble_edificio ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó el Inmuble-Edificio ".$as_expact;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_insert_grupo
//---------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function  uf_saf_update_inmueble_edificio($as_codact,$as_expact, $as_clasfun, $as_diract, $as_areatot,
	                                          $as_areacons,$as_numpiso, $as_areatotpiso, $as_areanex, $as_lindero,
											  $as_estlegprop, $as_avaluo, $as_feccont, $as_moncont, $aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_saf_update_inmueble_edificio
		//	    Access:  public
		//	 Arguments:  // $as_codact // codigo del activo
		                 // $as_expact // codigo del Expediente
						 // $as_clasfun // clasificaciòn de la funciòn
						 // $as_diract // direccion del edificio
						 // $as_areatot // area total del edificio
						 // $areacons // area de la construccion
						 // $as_areatotpiso area total de los pisos
						 // $as_areanex // area de los anexos
						 // $as_lindero // linderos
						 // $as_estlegprop // estudios legales
						 //  $as_avaluo // avaluo
		//                aa_seguridad   // arreglo de registro de seguridad
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que actuliza un edificio como un inmueble
		/////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=false;
		$ls_codemp      = $this->ls_codemp;
		$as_feccont     = $this->io_funcion->uf_convertirdatetobd($as_feccont);
		
		$as_moncont     = str_replace('.','',$as_moncont);
		$as_moncont     = str_replace(',','.',$as_moncont);		
		
		$as_areatot     = str_replace(".","",$as_areatot);
		$as_areatot     = str_replace(",",".",$as_areatot);
		
		$as_areacons    = str_replace(".","",$as_areacons);
	    $as_areacons    = str_replace(",",".",$as_areacons);
		
		$as_areatotpiso = str_replace(".","",$as_areatotpiso);
		$as_areatotpiso = str_replace(",",".",$as_areatotpiso);
		
		$as_areanex     = str_replace(".","",$as_areanex);
		$as_areanex     = str_replace(",",".",$as_areanex);
		
        $this->io_sql->begin_transaction();
		$ls_sql = " UPDATE saf_edificios ".
				  "  SET codemp='".$ls_codemp."', ". 
				  "      codact='".$as_codact."', ".
				  "      expact='".$as_expact."', ". 
				  "      clasfun='".$as_clasfun."', ".
				  "      diract='".$as_diract."', ".
				  "      areatot=".$as_areatot.", ". 
				  "	     areacons=".$as_areacons.", ".
				  "      numpiso=".$as_numpiso.", ". 
				  "      areatotpiso=".$as_areatotpiso.",  ".
				  "      areanex=".$as_areanex.",  ".
				  "      lindero='".$as_lindero."', ".
				  "      estlegprop='".$as_estlegprop."', ". 
				  "	     avaluo='".$as_avaluo."', ".
				  "      feccont='".$as_feccont."', ".
				  "      moncont=".$as_moncont." ".
				  " WHERE codemp='".$ls_codemp."'".
				  "   AND codact='".$as_codact."'".
				  "   AND expact='".$as_expact."'";
															
		$rs_data=$this->io_sql->execute($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_saf_update_inmueble_edificio ERROR->".
			                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();
			print $this->io_sql->message.'<br>';
		}
		else
		{
			$lb_valido=true;
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="se actualizó el Inmueble-Edificio ".$as_expact;
			$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$this->io_sql->commit();
		}
		return $lb_valido;
	}//fin de la uf_saf_update_inmueble_edificio
//----------------------------------------------------------------------------------------------------------------------------------------
   function uf_select_inmueble_edificio($as_codact,&$as_expact, &$as_clasfun, &$as_diract, &$as_areatot,
	                                    &$as_areacons,&$as_numpiso, &$as_areatotpiso, &$as_areanex, &$as_lindero,
										&$as_estlegprop, &$as_avaluo, &$as_feccont, &$as_moncont, &$as_existe)
   {
   /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_select_inmueble_edificio
		//	    Access:  public
		//	 Arguments:  // $as_codact // codigo del activo
		                 // $as_expact // codigo del Expediente
						 // $as_clasfun // clasificaciòn de la funciòn
						 // $as_diract // direccion del edificio
						 // $as_areatot // area total del edificio
						 // $areacons // area de la construccion
						 // $as_areatotpiso area total de los pisos
						 // $as_areanex // area de los anexos
						 // $as_lindero // linderos
						 // $as_estlegprop // estudios legales
						 //  $as_avaluo // avaluo		//               
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que busca informacion del edificio como un inmueble
	/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_existe="FALSE";
		$ls_sql=" SELECT codemp, codact, expact, clasfun, diract, areatot, areacons, numpiso, ".
				"        areatotpiso, areanex, lindero, estlegprop, avaluo, feccont, moncont  ".
				"   FROM saf_edificios                                                        ".
				"  WHERE codemp='".$this->ls_codemp."'".
				"    AND codact='".$as_codact."'";	
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_select_inmueble_edificio ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
		    $cantidad=$this->io_sql->num_rows($rs_data); 
			$row=$this->io_sql->fetch_row($rs_data);
			if ($cantidad>0)
			   {
				 $as_expact      = $row["expact"]; 
				 $as_clasfun     = $row["clasfun"];
				 $as_diract      = $row["diract"];
				 $as_areatot     = number_format($row["areatot"],2,',','.');
				 $as_areacons    = number_format($row["areacons"],2,',','.');
				 $as_numpiso     = $row["numpiso"];
				 $as_areatotpiso = number_format($row["areatotpiso"],2,',','.');
				 $as_areanex     = number_format($row["areanex"],2,',','.');
				 $as_lindero     = $row["lindero"];
				 $as_estlegprop  = $row["avaluo"];
				 $as_avaluo      = $row["estlegprop"];
				 $as_feccont     = $this->io_funcion->uf_convertirfecmostrar($row["feccont"]);	
				 $as_moncont     = number_format($row["moncont"],2,',','.');
				 $as_existe      = "TRUE";
				 $lb_valido=true;				
			}//fin del if
			$this->io_sql->free_result($rs_data);		
		}// fin del else
		return $lb_valido;
   }// fin de uf_select_inmueble_edificio
//--------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
    function uf_insertar_materiales($as_codtipest,$as_codcomp,$as_codact,$as_expact,$aa_seguridad)
	{
	 /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_insertar_materiales
		//	    Access:  public
		//	 Arguments:           
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta los materiales del edificio
	/////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_codemp=$this->ls_codemp;
	 $this->io_sql->begin_transaction();
	 $ls_sql = " INSERT INTO saf_edificiotipest(codemp, codtipest, codcomp, codact, expact) ". 
               "      VALUES ('".$ls_codemp."','".$as_codtipest."','".$as_codcomp."','".$as_codact."','".$as_expact."');" ;							
	 $rs_data=$this->io_sql->execute($ls_sql); 
	 if($rs_data===false)
	 {  
		$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_insertar_materiales ERROR->".
		                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	 else
	 {
	 	$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Insertó el Material del Edificio ".$as_expact;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$this->io_sql->commit(); 
		
	}
	return $lb_valido;
	}// fin uf_insertar_materiales
//-------------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------------
   function uf_select_saf_edificiotipest($as_codtipest,$as_codcomp,$as_codact,$as_expact,&$cantidad)
   {
   /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_select_saf_edificiotipest
		//	    Access:  public
		//	 Arguments:             
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que busca informacion del material del edificio
	/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_existe="FALSE";
		$ls_sql=" SELECT codemp, codtipest, codcomp, codact, expact ".
                "   FROM saf_edificiotipest                         ".
				"  WHERE codemp='".$this->ls_codemp."'".
				"    AND codact='".$as_codact."'".
				"    AND codtipest='".$as_codtipest."'".
				"    AND codcomp='".$as_codcomp."'".
				"    AND expact='".$as_expact."'"; 		
		$rs_data=$this->io_sql->select($ls_sql); 
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_select_saf_edificiotipest ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
		   	$cantidad=$this->io_sql->num_rows($rs_data);					
		}//fin del if
		$this->io_sql->free_result($rs_data);		
		return $cantidad;
   }// fin de uf_select_inmueble_edificio
//--------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------------
   function uf_buscar_material($as_codact,$as_expact,&$aa_object,&$totalrow)
   {
   /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_buscar_material
		//	    Access:  public
		//	 Arguments:             
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion que busca informacion del material del edificio
	/////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$as_existe="FALSE";
		$ls_sql=" SELECT saf_edificiotipest.codemp, saf_edificiotipest.codtipest, ".
				"  	     saf_edificiotipest.codcomp, saf_edificiotipest.codact,   ".
				"	     saf_edificiotipest.expact, saf_tipoestructura.dentipest, saf_componente.dencomp ".
				"	  FROM saf_edificiotipest                                                            ".
				"	  JOIN saf_tipoestructura ON (saf_edificiotipest.codemp=saf_tipoestructura.codemp    ".
				"							  AND saf_edificiotipest.codtipest=saf_tipoestructura.codtipest)  ".
				"	  JOIN saf_componente ON (saf_edificiotipest.codemp=saf_componente.codemp                 ".
				"						  AND saf_edificiotipest.codtipest=saf_componente.codtipest           ".
				"						  AND saf_edificiotipest.codcomp=saf_componente.codcomp)              ".
				"	 WHERE saf_edificiotipest.codemp='".$this->ls_codemp."' ". 
				"	   AND saf_edificiotipest.codact='".$as_codact."'". 				
				"	   AND saf_edificiotipest.expact='".$as_expact."'"; 
		$rs_data=$this->io_sql->select($ls_sql); 
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_buscar_material ERROR->".
								   $this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			
		}
		else
		{
		   	$cantidad=$this->io_sql->num_rows($rs_data);			
			if ($cantidad>0)
			{   $i=0;
				while($row=$this->io_sql->fetch_row($rs_data))
				{
					$i++;
					$ls_dentipest=$row["dentipest"];
					$ls_codtipest=$row["codtipest"];
					$ls_codcomp=$row["codcomp"];
					$ls_dencom=$row["dencomp"];		 			
					$aa_object[$i][1]="<input type=text name=txtdentipest".$i." class=sin-borde  size=30 value='". $ls_dentipest."' readonly>
										   <input type=hidden name=txtcodtipest".$i." value='".$ls_codtipest."' readonly>";
					$aa_object[$i][2]="<input type=text name=txtcodcomp".$i." class=sin-borde  size=5 value='". $ls_codcomp."' readonly>";
					$aa_object[$i][3]="<input type=text name=txtdencomp".$i." class=sin-borde  size=20 value='".$ls_dencom."' readonly>";
					$aa_object[$i][4]="<div align='center'><a href='javascript:ue_eliminar($i);'><img src='../shared/imagebank/tools20/eliminar.gif' alt='Buscar' width='15' height='15' border='0'></a></div>";	 
					
				}//fin del while
				$totalrow=$cantidad;
			}//fin del if				
		}//fin del esle
		$this->io_sql->free_result($rs_data);		
		return $cantidad;
   }// fin de uf_select_inmueble_edificio
//--------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------
    function uf_eliminar_materiales($as_codtipest,$as_codcomp,$as_codact,$as_expact,$aa_seguridad)
	{
	 /////////////////////////////////////////////////////////////////////////////////////////
		//	  Function:  uf_eliminar_materiales
		//	    Access:  public
		//	 Arguments:           
		//	    Returns: $lb_valido-----> true: operacion exitosa false: operacion no exitosa
		//	Description: Esta funcion inserta los materiales del edificio
	/////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido=true;
	 $ls_codemp=$this->ls_codemp;
	 $this->io_sql->begin_transaction();
	 $ls_sql = " DELETE FROM saf_edificiotipest ".
	           "  WHERE codemp='".$this->ls_codemp."'".
			   "    AND codtipest='".$as_codtipest."'".
			   "    AND codcomp='".$as_codcomp."'".
			   "    AND codact='".$as_codact."'".
			   "    AND expact='".$as_expact."'";							
	 $rs_data=$this->io_sql->execute($ls_sql); 
	 if($rs_data===false)
	 {  
		$this->io_msg->message("CLASE->inmueble_edificio MÉTODO->uf_eliminar_materiales ERROR->".
		                       $this->io_funcion->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
		$this->io_sql->rollback();
	 }
	 else
	 {
	 	$lb_valido=true;
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$ls_evento="INSERT";
		$ls_descripcion ="Se Eliminó el Material del Edificio ".$as_expact;
		$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		$this->io_sql->commit(); 
		
	}
	return $lb_valido;
	}// fin uf_eliminar_materiales
///--------------------------------------------------------------------------------------------------------------------------------------
}//fin de la clase sigesp_c_inmueble_edificio
?>
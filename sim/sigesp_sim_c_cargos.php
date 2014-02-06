<?php

class sigesp_sim_c_cargos
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;

	function sigesp_sim_c_cargos()
	{
		require_once("../shared/class_folder/class_sql.php");
		require_once("../shared/class_folder/class_mensajes.php");
		require_once("../shared/class_folder/sigesp_include.php");
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->io_funcion=new class_funciones();
	}
	
	function uf_sim_select_cargos($as_codemp,$as_codart,&$ai_totrows,&$ao_object)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_sim_select_cargos
		//           Access:  public (sigesp_sim_d_cargos)
		//	     Argumentos:  $as_codemp // codigo de empresa
		//  		          $as_codart  // codigo de articulo
		//  		          $ai_totrows  // total de lineas del grid
		//  		          $ao_object  // arreglo de objetos
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que busca los cargos existentes para la empresa en la tabla sigesp_cargos y se trae el 
		//                    resultado de la busqueda
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   16/02/2006							Fecha de Ultima Modificación: 16/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql=" SELECT * ".
				" FROM sigesp_cargos ".
				" WHERE codemp='". $as_codemp ."'".
				" ORDER BY codcar ASC";  
		$rs_data=$this->io_sql->select($ls_sql);

		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Cargos MÉTODO->uf_sim_select_cargosxarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{	$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{        
				$ai_totrows=$ai_totrows + 1;
			    $ls_codcar=$row["codcar"];
			    $ls_dencar=$row["dencar"];
			    $ld_porcar=$row["porcar"];

				$ls_sql1="SELECT * FROM sim_cargosarticulo".
						 " WHERE codemp= '". $as_codemp ."'".			   
						 " AND codart= '". $as_codart ."'".			   
						 " AND codcar= '". $ls_codcar ."'";
				$rs_data1=$this->io_sql->select($ls_sql1);
				if($rs_data1===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->Cargos MÉTODO->uf_sim_select_cargosxarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					if($row=$this->io_sql->fetch_row($rs_data1))
					{
						$ao_object[$ai_totrows][1]="<input name=chkagregar".$ai_totrows." type=checkbox id=chkagregar".$ai_totrows." value=1 class=sin-borde checked>";
						$ao_object[$ai_totrows][2]="<input type=text name=txtcodcar".$ai_totrows." value='".$ls_codcar."' id=txtcodcar".$ai_totrows." class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";		
						$ao_object[$ai_totrows][3]="<input type=text name=txtdencar".$ai_totrows." value='".$ls_dencar."' id=txtdencar".$ai_totrows." class=sin-borde readonly style=text-align:left   size=60 maxlength=254>";
						$ao_object[$ai_totrows][4]="<input type=text name=txtporcar".$ai_totrows." value='".$ld_porcar."' id=txtporcar".$ai_totrows." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					}
					else
					{
						$ao_object[$ai_totrows][1]="<input name=chkagregar".$ai_totrows." type=checkbox id=chkagregar".$ai_totrows." value=1  class=sin-borde>";
						$ao_object[$ai_totrows][2]="<input type=text name=txtcodcar".$ai_totrows." value='".$ls_codcar."' id=txtcodcar".$ai_totrows." class=sin-borde readonly style=text-align:center size=20 maxlength=10 >";		
						$ao_object[$ai_totrows][3]="<input type=text name=txtdencar".$ai_totrows." value='".$ls_dencar."' id=txtdencar".$ai_totrows." class=sin-borde readonly style=text-align:left   size=60 maxlength=254>";
						$ao_object[$ai_totrows][4]="<input type=text name=txtporcar".$ai_totrows." value='".$ld_porcar."' id=txtporcar".$ai_totrows." class=sin-borde readonly style=text-align:right  size=10 maxlength=20>";
					}
				}
			   
			}
			$this->io_sql->free_result($rs_data);
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		return $lb_valido;
	} // end function uf_sim_select_cargos
	
	function uf_sim_select_cargosxarticulo($as_codemp,$as_codart,$as_codcar)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_sim_select_cargosxarticulo
		//           Access:  public (sigesp_sim_d_cargos)
		//	     Argumentos:  $as_codemp    // codigo de empresa
		//  		          $as_codart    // codigo de artículo
		//  		          $as_codcar    // codigo de cargo
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que busca si un articulo tiene asociado un cargo.
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   16/02/2006							Fecha de Ultima Modificación: 16/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$ls_sql = "SELECT * FROM sim_cargosarticulo  ".
				  " WHERE codemp='".$as_codemp."'".
				  " AND codart='".$as_codart."'". 
				  " AND codcar='".$as_codcar."'" ;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Cargos MÉTODO->uf_sim_select_cargosxarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$this->io_sql->free_result($rs_data);
			}
			else
			{
				$lb_valido=false;
			}
		}
		return $lb_valido;
	} // end function uf_sim_select_cargosxarticulo

	function  uf_sim_insert_cargosxarticulo($as_codemp,$as_codart,$as_codcar,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_sim_insert_cargosxarticulo
		//           Access:  public (sigesp_sim_d_cargos)
		//	     Argumentos:  $as_codemp    // codigo de empresa
		//  		          $as_codart    // codigo de artículo
		//  		          $as_codcar    // codigo de cargo
		//  			      $aa_seguridad // arreglo de seguridad
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que le agrega un cargo a un articulo.
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   16/02/2006							Fecha de Ultima Modificación: 16/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$this->io_sql->begin_transaction();
		$ls_sql="INSERT INTO sim_cargosarticulo (codemp, codart, codcar)".
				" VALUES ('".$as_codemp."','".$as_codart."','".$as_codcar."');";
				
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Cargos MÉTODO->uf_sim_insert_cargosxarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
			$this->io_sql->rollback();

		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Agregó el cargo ".$as_codcar." asociado al Articulo ".$as_codart." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$this->io_sql->commit();
		}
		
		return $lb_valido;

	} //  end  function  uf_sim_insert_cargosxarticulo

	function uf_sim_delete_cargosxarticulo($as_codemp,$as_codart,$as_codcar,$aa_seguridad)
	{
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	       Function:  uf_sim_delete_cargosxarticulo
		//           Access:  public (sigesp_sim_d_cargos)
		//	     Argumentos:  $as_codemp    // codigo de empresa
		//  		          $as_codart    // codigo de artículo
		//  		          $as_codcar    // codigo de cargo
		//  			      $aa_seguridad // arreglo de seguridad
		//	        Returns:  Retorna un Booleano
		//	    Description:  Funcion que se encarga de eliminarle un cargo a un articulo especifico.
		//       Creado por:  Ing. Luis Anibal Lang           
		// Fecha de Cracion:   16/02/2006							Fecha de Ultima Modificación: 16/02/2006	
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		$lb_valido=true;
		$this->io_sql->begin_transaction();	
		$ls_sql = " DELETE FROM sim_cargosarticulo".
					 " WHERE codemp= '".$as_codemp. "'".
					 " AND codart= '".$as_codart. "'". 
					 " AND codcar= '".$as_codcar. "'";
		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/
		/**/$ls_archivo="C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_INVENTARIO/trans".$as_numtra."-".date('dmY').".txt";/**/
		/**/$archivo = fopen($ls_archivo, "a+");																/**/
		/**/fwrite($archivo,$ls_sql);																			/**/
		/**/fclose($archivo);																					/**/
		//------------------------------------------------------------------------------------------------------/**/ 
		$li_row=$this->io_sql->execute($ls_sql);
	
			if($li_row===false)
			{
				$this->io_msg->message("CLASE->Cargos MÉTODO->uf_sim_delete_cargosxarticulo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				$lb_valido=false;
				$this->io_sql->rollback();
			}
			else
			{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="DELETE";
				$ls_descripcion ="Eliminó el cargo ".$as_codcar." Asociado al Articulo ".$as_codart." de la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
			//////////////////////////////////         SEGURIDAD               /////////////////////////////			
				$this->io_sql->commit();
			}
		return $lb_valido;
	} //  end  function uf_sim_delete_cargosxarticulo

} // end  class sigesp_sim_c_cargos
?>

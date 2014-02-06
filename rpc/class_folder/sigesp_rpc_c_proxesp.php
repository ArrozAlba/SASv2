<?PHP
class sigesp_rpc_c_proxesp
{
	var $io_sql;
	var $io_mensajes;
	var $io_funciones;
	var $io_seguridad;
	var $io_fun_rpc;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_rpc_c_proxesp()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_rpc_c_proxesp
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación : 
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
		require_once("class_funciones_rpc.php");
		$this->io_fun_rpc=new class_funciones_rpc();
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}// end function sigesp_rpc_c_proxesp
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_destructor()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_destructor
		//		   Access: public (sigesp_sno_d_tabla)
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
		unset($this->io_fun_rpc);
	}// end function uf_destructor
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_load_especialidad($as_codpro,$as_codesp,$as_denesp,$as_codesphasta,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_especialidad
		//		   Access: public 
		//	    Arguments: as_codpro  // código de Proveedor
		//				   ai_totrows  // total de fila
		//				   ao_object  // arreglo de objetos
		//	      Returns: lb_valido True si se encontro ó False si no se encontró
		//	  Description: Funcion que obtiene todas las especialidades del proveedor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación : 30/06/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		if(!empty($as_codesp))
		{
		  $ls_criterio= $ls_criterio."   AND rpc_especialidad.codesp>='".$as_codesp."'";
		}
		if(!empty($as_codesphasta))
		{
		  $ls_criterio= $ls_criterio."   AND rpc_especialidad.codesp<='".$as_codesphasta."'";
		}
		$ls_sql="SELECT codesp, denesp, ".
				"		(SELECT COUNT(codemp) ".
				"		   FROM rpc_espexprov ".
				"         WHERE rpc_espexprov.codemp = '".$this->ls_codemp."' ".
				"			AND rpc_espexprov.cod_pro = '".$as_codpro."' ".
				"			AND rpc_espexprov.codesp = rpc_especialidad.codesp) AS existe ".
				"  FROM rpc_especialidad ".
				" WHERE codesp<>'---' ".
				//"   AND codesp like '%".$as_codesp."%'".
				"        ".$ls_criterio." ".
				"   AND denesp like '%".$as_denesp."%'".
				" ORDER BY codesp ASC"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Proveedor-Especialidad MÉTODO->uf_load_especialidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ai_totrows++;
				$ls_codesp=$row["codesp"];
				$ls_denesp=$row["denesp"];
				$li_existe=$row["existe"];
				$ls_marcado="";
				if($li_existe>0)
				{
					$ls_marcado="checked";
				}
				$ao_object[$ai_totrows][1]="<input name=txtcodesp".$ai_totrows." type=text id=txtcodesp".$ai_totrows." class=sin-borde size=6 maxlength=4 value='".$ls_codesp."' readOnly>";
				$ao_object[$ai_totrows][2]="<input name=txtdenesp".$ai_totrows." type=text id=txtdenesp".$ai_totrows." class=sin-borde size=90 maxlength=100 value='".$ls_denesp."' readOnly>";
				$ao_object[$ai_totrows][3]="<input name=chkselec".$ai_totrows." type=checkbox id=chkselec".$ai_totrows." class=sin-borde value='1' ".$ls_marcado." >";
			}
			$ai_totrows++;
			$ao_object[$ai_totrows][1]="<input name=txtcodesp".$ai_totrows." type=text id=txtcodesp".$ai_totrows." class=sin-borde size=6 maxlength=4 value='' readOnly>";
			$ao_object[$ai_totrows][2]="<input name=txtdenesp".$ai_totrows." type=text id=txtdenesp".$ai_totrows." class=sin-borde size=90 maxlength=100 value='' readOnly>";
			$ao_object[$ai_totrows][3]="<input name=chkselec".$ai_totrows." type=checkbox class=sin-borde value='1' disabled>";
			$this->io_sql->free_result($rs_data);	
		}
		return $lb_valido;
	}// end function uf_load_especialidad
	//-----------------------------------------------------------------------------------------------------------------------------------	

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_eliminar_especialidad($as_codpro,$aa_seguridad)
    {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_eliminar_especialidad
		//		   Access: public
		//	    Arguments: as_codpro  // código de Proveedor
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el delete ó False si hubo error en el delete
		//	  Description: Funcion que elimina las especialidades asociadas a un proveedor
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sql="DELETE ".
				"  FROM rpc_espexprov ".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND cod_pro='".$as_codpro."'";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Proveedor-Especialidad MÉTODO->uf_eliminar_especialidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó las Especialidades asociadas al provedor ".$as_codpro;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_eliminar_especialidad
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_especialidad($as_codpro,$as_codesp,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_insert_especialidad
		//		   Access: private
		//	    Arguments: as_codpro  // código de Proveedor
		//				   as_codesp  // Código de Especialidad
		//				   aa_seguridad  // arreglo de las variables de seguridad
		//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
		//	  Description: Funcion que inserta en la tabla de rpc_espexprov
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 24/05/2007 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO rpc_espexprov (codemp,cod_pro,codesp) VALUES ('".$this->ls_codemp."','".$as_codpro."','".$as_codesp."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
 			$lb_valido=false;
			$this->io_mensajes->message("CLASE->Proveedor-Especialidad MÉTODO->uf_insert_especialidad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message)); 
		}
		else
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="INSERT";
			$ls_descripcion ="Insertó La Especialidad ".$as_codesp." al Proveedor ".$as_codpro;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_insert_especialidad
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cliente
 // Autor:       - Ing. Gerardo Cordero		
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con 
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006     
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_producto_transf
{

 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;
 
 
function sigesp_sfc_c_producto_transf()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
    require_once("sigesp_sob_c_funciones_sob.php");
	require_once("../shared/class_folder/sigesp_sfc_c_intarchivo.php");
$this->archivo= new sigesp_sfc_c_intarchivo("/var/www/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS");	
	//$this->archivo= new //sigesp_sfc_c_intarchivo("C:/xampp/htdocs/sigesp_fac/sfc/transferencias/ARTICULOS_PTOVENTAS");
    $this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();		
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();		
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];	
	$ls_codtie=$_SESSION["ls_codtienda"];

	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}

		
	function uf_select_producto_transf($ls_codpro)
	{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_producto
		// Parameters:  - $ls_codpro( Codigo del Producto).		
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////	
	
	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$ls_cadena="SELECT * FROM sfc_producto
		            WHERE codemp='".$ls_codemp."' and codtiend='".$ls_codtie."' and codpro='".$ls_codpro."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->io_msgc="Error en uf_select_tienda ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
				$this->io_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
	}
	
function uf_guardar_producto_transf($ls_codpro,$ls_denpro,$ls_tippro,$ls_codcar,$ls_preven,$ls_codart,$ls_spicuenta,$ls_codcla,$ls_moncar,$ls_porgan,$ls_tipcos,$ls_preuni,$ls_preven1,$ls_preven2,$ls_preven3,$ls_cosfle,$ls_codsub,$ls_coduso/*,$aa_seguridad*/)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_unidad
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).		
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida). 	
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$lb_existe=$this->uf_select_producto_transf($ls_codpro);
        $li_precio=$this->funsob->uf_convertir_cadenanumero($ls_preven);
        $ls_moncar=$this->funsob->uf_convertir_cadenanumero($ls_moncar);
		$ls_porgan=$this->funsob->uf_convertir_cadenanumero($ls_porgan);
		$ls_preuni=$this->funsob->uf_convertir_cadenanumero($ls_preuni);
		$ls_preven1=$this->funsob->uf_convertir_cadenanumero($ls_preven1);
		$ls_preven2=$this->funsob->uf_convertir_cadenanumero($ls_preven2);
		$ls_preven3=$this->funsob->uf_convertir_cadenanumero($ls_preven3);
		$ls_cosfle=$this->funsob->uf_convertir_cadenanumero($ls_cosfle);
		
		if(!$lb_existe)
		{
            $ls_cadena= " INSERT INTO sfc_producto(codemp,codpro,denpro,tippro,codcar,preven,codart,spi_cuenta,codcla,moncar,porgan,tipcos,preuni,preven1,preven2,preven3,cosfle,cod_sub,id_uso,codtiend) VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_denpro."','".$ls_tippro."','".$ls_codcar."',".$li_precio.",'".$ls_codart."','".$ls_spicuenta."','".$ls_codcla."',".$ls_moncar.",".$ls_porgan.",'".$ls_tipcos."',".$ls_preuni.",".$ls_preven1.",".$ls_preven2.",".$ls_preven3.",".$ls_cosfle.",'".$ls_codsub."','".$ls_coduso."','".$ls_codtie."'); ";
	
	
	 /**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/ 
	    if (substr($ls_codpro,4,1)=='V')
		{
			
			$ls_nomarchivo="transPRODUCTO";
			$this->archivo->crear_archivo($ls_nomarchivo);
			$this->archivo->escribir_archivo($ls_cadena);
			$this->archivo->cerrar_archivo();         
					
		}
				 
			$this->io_msgc="Registro Incluido!!!";	
			$ls_evento="INSERT";	
		}
		else
		{
			$ls_cadena= "UPDATE sfc_producto SET denpro='".$ls_denpro."',tippro='".$ls_tippro."',codcar='".$ls_codcar."',preven='".$li_precio."',codart='".$ls_codart."',spi_cuenta='".$ls_spicuenta."',codcla='".$ls_codcla."',moncar=".$ls_moncar.",porgan=".$ls_porgan.",tipcos='".$ls_tipcos."',preuni=".$ls_preuni.",preven1=".$ls_preven1.",preven2=".$ls_preven2.",preven3=".$ls_preven3.",cosfle=".$ls_cosfle.",cod_sub='".$ls_codsub."',id_uso='".$ls_coduso."' WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codpro='".$ls_codpro."';";
		/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/ 
	    if (substr($ls_codpro,4,1)=='V')
		{
		 
			$ls_nomarchivo="transPRODUCTO";
			$this->archivo->crear_archivo($ls_nomarchivo);
			$this->archivo->escribir_archivo($ls_cadena);
			$this->archivo->cerrar_archivo();         
				
		}
			//print $ls_cadena;
			
			
		}
/*print $ls_cadena;*/
		
		return $lb_valido;
	}
	

	function uf_delete_producto_transf($ls_codpro)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).		
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		
		    	$ls_cadena= "DELETE FROM sfc_producto WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codpro='".$ls_codpro."';";
				/**-----------------------GENERAR ARCHIVO DE TRANSFERENCIA-----------------------------------------------/**/ 
	    if (substr($ls_codpro,4,1)=='V')
		{
		 
			$ls_nomarchivo="transPRODUCTO";
			$this->archivo->crear_archivo($ls_nomarchivo);
			$this->archivo->escribir_archivo($ls_cadena);
			$this->archivo->cerrar_archivo();         
		}
		
		return $lb_valido;
	}
}
?>

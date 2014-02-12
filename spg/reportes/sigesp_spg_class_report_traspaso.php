<?php

class sigesp_spg_class_report_traspaso
{
	var $obj="";
	var $io_sql;
	var $ds;
	var $ds_detalle;
	var $siginc;
	var $con;

	function sigesp_spg_class_report_traspaso()
	{
		require_once("../../shared/class_folder/class_sql.php");
//		require_once("../../shared/class_folder/class_datastore.php");
		require_once("../../shared/class_folder/class_mensajes.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$this->io_msg=new class_mensajes();
		$this->dat_emp=$_SESSION["la_empresa"];
		$in=new sigesp_include();
		$this->con=$in->uf_conectar();
		$this->io_sql=new class_sql($this->con);
		$this->io_funcion = new class_funciones();
		$this->ds=new class_datastore();
		$this->ds_detalle=new class_datastore();
	}

	function uf_spg_select_traspasos($ad_fecdesde,$ad_fechasta,$as_bddestino)	
	{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	         Function:   uf_sss_select_auditoria
	//	           Access:   public
	//  		Arguments:   ad_fecdesde    // fecha de inicio del periodo de busqueda
	//  			         ad_fechasta    // fecha de cierre del periodo de busqueda
	//  			         as_bddestno    // Base de Datos Destino
	//	         Returns : $lb_valido True si se creo el Data stored correctamente ó False si no se creo
	//	      Description:  Función que se encarga de realizar la busqueda  de las operaciones de traspasos presupuestarios
	//         Creado por:  Ing. Arnaldo Suárez          
	//   Fecha de Cracion:   07/08/2008							Fecha de Ultima Modificación:   20/05/2006 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_sqlint="";
		$ls_sqlbd="";
		$ls_sql_where="";
		if(!empty($as_bddestino)){$ls_sqlbd="  AND bddestino ='".$as_bddestino."'";}


			$ld_auxdesde=$this->io_funcion->uf_convertirdatetobd($ad_fecdesde);
			$ld_auxhasta=$this->io_funcion->uf_convertirdatetobd($ad_fechasta);
		
		$ls_sql=" SELECT codres, codproc, codsis, fecha, bdorigen, bddestino, descripcion ".
  				" FROM sigesp_dt_proc_cons".
				" WHERE fecha >= '".$ld_auxdesde."'".
			            " AND fecha <='".$ld_auxhasta."'" .
				" AND codproc = 'SPGTRF' AND codsis = 'SPG' ".		
				$ls_sqlbd.
				" ORDER BY codres";					
	    $rs_data=$this->io_sql->select($ls_sql);
	    $li_numrows=$this->io_sql->num_rows($rs_data);	
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Report MÉTODO->uf_spg_select_traspasos ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			//print "Filas:".$li_numrows."<br>";
			if ($li_numrows>0)
			{
				$data=$this->io_sql->obtener_datos($rs_data);
				$arrcols=array_keys($data);
				$totcol=count($arrcols);
				$this->ds->data=$data;
				$lb_valido=true;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido; 
	} // fin function uf_sss_select_auditoria	
	
} //fin  class sigesp_siv_class_report
?>

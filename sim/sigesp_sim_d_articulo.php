<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_d_articulo.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

function uf_limpiarvariables()
{
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_limpiarvariables
	//	Description: Funci�n que limpia todas las variables necesarias en la p�gina
	//////////////////////////////////////////////////////////////////////////////
	global $ls_codart,$ls_denart,$ls_codtipart,$ld_feccreart,$ls_obsart,$li_exiart,$li_eximinart,$li_eximaxart,$ls_codunimed;
	global $li_prearta,$li_preartb,$li_preartc,$li_preartd,$ld_fecvenart,$ls_spg_cuenta,$li_pesart,$li_altart,$li_ancart,$li_proart;
	global $ls_fotart,$li_exiiniart,$li_ultcosart,$li_cosproart,$disabled,$ls_dentipart,$ls_denunimed;
	global $ls_codcatsig,$ls_dencatsig,$li_estnum,$ls_sccuenta,$ls_densccuenta,$li_reoart;
	global $ls_fotowidth,$ls_fotoheight,$ls_foto,$lb_abrircargos,$ls_tippro,$ls_opctipcos;

	$ls_codart=    "";
	$ls_denart=    "";
	$ls_codtipart= "";
	$ls_codunimed= "";
	$ls_dentipart= "";
	$ls_denunimed= "";
	$ld_feccreart= date("d/m/Y");
	$ls_obsart=    "";
	$li_exiart=    "";
	$li_eximinart= "";
	$li_eximaxart= "";
	$li_reoart=    "";
	$li_codunimed= "";
	$li_prearta=   "";
	$li_preartb=   "";
	$li_preartc=   "";
	$li_preartd=   "";
	$ld_fecvenart= "";
	$ls_spg_cuenta="";
	$ls_sccuenta="";
	$ls_densccuenta="";
	$li_pesart=    "";
	$li_altart=    "";
	$li_ancart=    "";
	$li_proart=    "";
	$ls_fotart=    "";
	$li_exiiniart= "";
	$li_ultcosart= "";
	$li_cosproart= "";
	$ls_codcatsig= "";
	$ls_dencatsig= "";
	$li_estnum=    "";
	$lb_abrircargos= false;
	$disabled=     "disabled";
	$ls_fotowidth= "121";
	$ls_fotoheight="94";
	$ls_foto=      "blanco.jpg";
	//$ls_tippro="";
}

function uf_limpiarproducto(){

	global $ls_codcar,$ls_dencar,$ls_codcla,$ls_dencla,$ls_codcla1,$ls_dencla1,$ls_moncar,$ls_preven;
	global $ls_preuni,$ls_porgan,$ls_preven2,$ls_preven3,$ls_preven4,$ls_cosfle;
	global $ls_coduso,$ls_codusomac,$ls_denuso,$ls_disponobles,$ls_asignados;

	$ls_codcar="";
	$ls_dencar="";
	$ls_codcla="";
	$ls_dencla="";
	$ls_codcla1="";
	$ls_dencla1="";
    //$ls_moncar="0,00";

	//$ls_codart="";
	//$ls_denart="";
	//$ls_cosart="0,00";
	//$ls_spicuenta="";
	$ls_denospi="";
	//$ls_sccuenta="";
	//$ls_denosc="";
	$ls_forcar="";
	//$ls_preuni="0,00";
	//$ls_porgan="0,00";
	//$ls_pretot="0,00";
	//$ls_preven="0,00";
	//$ls_preven2="0,00";
	//$ls_preven3="0,00";
	//$ls_preven4="0,00";
	//$ls_cosfle="0,00";
	//$ls_cosproart="0,00";
	//$ls_opctipcos="";
	$ls_coduso="";
	$ls_denuso="";
	$ls_codusomac="";
	$ls_disponobles="";
	$ls_asignados="";

}

 function uf_print_lista($as_nombre,$as_campoclave,$as_campoimprimir,$aa_lista)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print_lista
		//		   Access : private
		//      Arguments : $as_nombre  // Nombre del Campo
		//      			$as_campoclave  // campo por medio del cual se va filtrar la lista
		//      			$as_campoimprimir  // campo que se va a mostrar
		//      			$aa_lista  // arreglo que se va a colocar en la lista
		//	  Description : Funci�n que imprime el contenido de una caja de texto multiple
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creaci�n : 26/10/2006 								Fecha �ltima Modificaci�n :
		//////////////////////////////////////////////////////////////////////////////

		if(empty($aa_lista[$as_campoclave]))
		{					
			
			$li_total=0;
		}
		else
		{
			$li_total=count($aa_lista[$as_campoclave]);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='8' style='width:300px' multiple>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			print "<option value='".$aa_lista[$as_campoclave][$li_i]."'>".$aa_lista[$as_campoimprimir][$li_i];
		}
		print "</select>";
   }  // end function uf_print_lista
?>

<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones($con);
	require_once("sigesp_sim_c_articulo.php");
	$io_siv= new sigesp_sim_c_articulo();
	require_once("class_funciones_inventario.php");
	$io_funciones_inventario= new class_funciones_inventario();
	require_once("sigesp_sim_c_articulo_transf.php");
	$io_archivo= new sigesp_sim_c_articulo_transf();
	require_once("../shared/class_folder/class_datastore.php");
	$io_datastore= new class_datastore();
	require_once("../sfc/class_folder/sigesp_sfc_c_producto.php");
	$io_producto = new sigesp_sfc_c_producto();
	//require_once("../shared/class_folder/class_datastore.php");
	//$io_datastore= new class_datastore();
	require_once("sigesp_sim_c_articuloxalmacen.php");
	$io_axa= new sigesp_sim_c_articuloxalmacen();
	require_once("../sfc/class_folder/sigesp_sfc_c_producto_transf.php");
	$io_prodarchivo= new sigesp_sfc_c_producto_transf();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_fotowidth="121";
	$ls_fotoheight="94";
	$ls_foto ="blanco.jpg";
	$ls_tiend =$_SESSION["ls_codtienda"];

    $ls_tippro=		 $_POST["cmbtippro"];

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		$ls_readonly="readonly";
	}

	$ls_venta= $io_funciones_inventario->uf_obtenervalor("hidventa","");
	if($ls_venta=="" or $ls_venta=="Si"){
		$ls_venta = "Si";
		$ls_chksi = "checked";
		$ls_chkno = "";
		
		if($ls_venta=="Si")
			{
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,false,$ls_disponibles);
			}
		
	}else{
		$ls_venta = "No";
		$ls_chksi = "";
		$ls_chkno = "checked";
	}
	
	$ls_lote= $io_funciones_inventario->uf_obtenervalor("hidlote","");
	if($ls_lote=="" or $ls_lote=="Si"){
		$ls_lote = "Si";
		$ls_chks = "checked";
		$ls_chkn = "";
	}else{
		$ls_lote = "No";
		$ls_chks = "";
		$ls_chkn = "checked";
	}

	if(array_key_exists("hidtipcos",$_POST))
	 {
	   $ls_opctipcos=$_POST["hidtipcos"];
	   //print "OPCION:".$ls_opctipcos;
	 }
	 else
	 {
	 	$ls_opctipcos="CP";
	 }

	 $ls_disabled="";

	uf_limpiarvariables();
	uf_limpiarproducto();

	$li_catalogo=$io_siv->uf_sim_select_catalogo($li_estnum);
	switch ($ls_operacion)
	{
		case "NUEVO":
			if($li_catalogo)
			{
				print("<script language=JavaScript>");
				print "window.open('sigesp_sim_cat_sigecof.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";
				print("</script>");
			}
			if($li_estnum)
			{
				$ls_emp="";
				$ls_codemp="";
				$ls_tabla="sim_articulo";
				$ls_columna="codart";

				$ls_codart=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			}
			$ls_readonly="";

			if($ls_venta=="Si")
			{
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,false,$ls_disponibles);
			}

		break;

		case "CHANGE":

			$ls_codart=      $io_funciones_inventario->uf_obtenervalor("txtcodart","");
			$ls_denart=      $io_funciones_inventario->uf_obtenervalor("txtdenart","");
			$ls_codtipart=   $io_funciones_inventario->uf_obtenervalor("txtcodtipart","");
			$ls_codunimed=   $io_funciones_inventario->uf_obtenervalor("txtcodunimed","");
			$ls_dentipart=   $io_funciones_inventario->uf_obtenervalor("txtdentipart","");
			$ls_denunimed=   $io_funciones_inventario->uf_obtenervalor("txtdenunimed","");
			$ld_feccreart=   $io_funciones_inventario->uf_obtenervalor("txtfeccreart","");
			$ls_obsart=      $io_funciones_inventario->uf_obtenervalor("txtobsart","");
			$li_prearta=     $io_funciones_inventario->uf_obtenervalor("txtprearta","");
			$li_preartb=     $io_funciones_inventario->uf_obtenervalor("txtpreartb","");
			$li_preartc=     $io_funciones_inventario->uf_obtenervalor("txtpreartc","");
			$li_preartd=     $io_funciones_inventario->uf_obtenervalor("txtpreartd","");
			$ld_fecvenart=   $io_funciones_inventario->uf_obtenervalor("txtfecvenart","");
			$ls_codcatsig=   $io_funciones_inventario->uf_obtenervalor("txtcodcatsig","");
			$ls_dencatsig=   $io_funciones_inventario->uf_obtenervalor("txtdencatsig","");
			$ls_spg_cuenta=  $io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$ls_densccuenta= $io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$li_pesart=      $io_funciones_inventario->uf_obtenervalor("txtpesart","");
			$li_altart=      $io_funciones_inventario->uf_obtenervalor("txtaltart","");
			$li_ancart=      $io_funciones_inventario->uf_obtenervalor("txtancart","");
			$li_proart=      $io_funciones_inventario->uf_obtenervalor("txtproart","");
			$ls_status=      $io_funciones_inventario->uf_obtenervalor("hidstatusc","");
			$li_ultcosart=   $io_funciones_inventario->uf_obtenervalor("txtultcosart","");
			$li_cosproart=   $io_funciones_inventario->uf_obtenervalor("txtcosproart","");
			$ls_lote=        $io_funciones_inventario->uf_obtenervalor("hidlote","");
			$li_util=        $io_funciones_inventario->uf_obtenervalor("txtutil","");
			$ls_codcla1=     $io_funciones_inventario->uf_obtenervalor("txtcodcla1","");
			$ls_codcla=      $io_funciones_inventario->uf_obtenervalor("txtcodcla","");
			$ls_denuso=      $io_funciones_inventario->uf_obtenervalor("txtcoduso","");
		
			$ls_tippro=		 $_POST["cmbtippro"];
			$ls_opctipcos=	 $_POST["hidtipcos"];

			$li_prearta=   str_replace(".","",$li_prearta);
			$li_prearta=   str_replace(",",".",$li_prearta);
			$li_preartb=   str_replace(".","",$li_preartb);
			$li_preartb=   str_replace(",",".",$li_preartb);
			$li_preartc=   str_replace(".","",$li_preartc);
			$li_preartc=   str_replace(",",".",$li_preartc);
			$li_preartd=   str_replace(".","",$li_preartd);
			$li_preartd=   str_replace(",",".",$li_preartd);
			$li_pesart=    str_replace(".","",$li_pesart);
			$li_pesart=    str_replace(",",".",$li_pesart);
			$li_altart=    str_replace(".","",$li_altart);
			$li_altart=    str_replace(",",".",$li_altart);
			$li_ancart=    str_replace(".","",$li_ancart);
			$li_ancart=    str_replace(",",".",$li_ancart);
			$li_proart=    str_replace(".","",$li_proart);
			$li_proart=    str_replace(",",".",$li_proart);

			$ls_nomfot=$HTTP_POST_FILES['txtfotart']['name'];
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codart.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotart']['type'];
			$ls_tamfot=$HTTP_POST_FILES['txtfotart']['size'];
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotart']['tmp_name'];

			if($ls_venta=="Si")
			{
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
				//$io_siv->uf_sim_select_tiendaasign_articulo($ls_codemp,$ls_codart,$ls_asignados);
				$io_producto->uf_select_producto2($ls_codart,$io_datastore);
				$ls_codusomac=$io_datastore->getValue("codusomac",1);

			}

         if($ls_venta=="Si")
			{
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,false,$ls_disponibles);
			}
		break;

		case "GUARDAR":
			$ls_valido= false;
			if($li_catalogo)
			{
				$ls_readonly="readonly";
			}
			else
			{
				$ls_readonly="";
			}

			$ls_codart=      $io_funciones_inventario->uf_obtenervalor("txtcodart","");
			$ls_denart=      $io_funciones_inventario->uf_obtenervalor("txtdenart","");
			$ls_codtipart=   $io_funciones_inventario->uf_obtenervalor("txtcodtipart","");
			$ls_codunimed=   $io_funciones_inventario->uf_obtenervalor("txtcodunimed","");
			$ls_dentipart=   $io_funciones_inventario->uf_obtenervalor("txtdentipart","");
			$ls_denunimed=   $io_funciones_inventario->uf_obtenervalor("txtdenunimed","");
			$ld_feccreart=   $io_funciones_inventario->uf_obtenervalor("txtfeccreart","");
			$ls_obsart=      $io_funciones_inventario->uf_obtenervalor("txtobsart","");
			$li_prearta=     $io_funciones_inventario->uf_obtenervalor("txtprearta","");
			$li_preartb=     $io_funciones_inventario->uf_obtenervalor("txtpreartb","");
			$li_preartc=     $io_funciones_inventario->uf_obtenervalor("txtpreartc","");
			$li_preartd=     $io_funciones_inventario->uf_obtenervalor("txtpreartd","");
			$ld_fecvenart=   $io_funciones_inventario->uf_obtenervalor("txtfecvenart","");
			$ls_codcatsig=   $io_funciones_inventario->uf_obtenervalor("txtcodcatsig","");
			$ls_dencatsig=   $io_funciones_inventario->uf_obtenervalor("txtdencatsig","");
			$ls_spg_cuenta=  $io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$ls_densccuenta= $io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$li_pesart=      $io_funciones_inventario->uf_obtenervalor("txtpesart","");
			$li_altart=      $io_funciones_inventario->uf_obtenervalor("txtaltart","");
			$li_ancart=      $io_funciones_inventario->uf_obtenervalor("txtancart","");
			$li_proart=      $io_funciones_inventario->uf_obtenervalor("txtproart","");
			$ls_status=      $io_funciones_inventario->uf_obtenervalor("hidstatusc","");
			$li_ultcosart=   $io_funciones_inventario->uf_obtenervalor("txtultcosart","");
			$li_cosproart=   $io_funciones_inventario->uf_obtenervalor("txtcosproart","");
			$ls_lote=        $io_funciones_inventario->uf_obtenervalor("hidlote","");
			$li_util=        $io_funciones_inventario->uf_obtenervalor("txtutil","");
			
			$ls_codcla1=     $io_funciones_inventario->uf_obtenervalor("txtcodcla1","");
			$ls_codcla=      $io_funciones_inventario->uf_obtenervalor("txtcodcla","");
			$ls_denuso=      $io_funciones_inventario->uf_obtenervalor("txtcoduso","");
		
			$ls_tippro=		 $_POST["cmbtippro"];
			$ls_opctipcos=	 $_POST["hidtipcos"];

			if($li_util=="")
			{
			   $li_util=0;
			}

			if($ls_lote=="Si")
			{
			    $li_lote=1;
			}
			else
			{
 				$li_lote=0;
			}
			
			$ls_nomfot=$HTTP_POST_FILES['txtfotart']['name'];
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codart.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotart']['type'];
			$ls_tamfot=$HTTP_POST_FILES['txtfotart']['size'];
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotart']['tmp_name'];

			//////////////////////Datos de Producto///////////////////////////////

			if($ls_venta=="Si")
			{
				$ls_codpro=$ls_codart;
				$ls_codcla=$_POST["txtcodcla"];
				$ls_dencla=$_POST["txtdencla"];
				$ls_codcla1=$_POST["txtcodcla1"];
				$ls_dencla1=$_POST["txtdencla1"];

				$ls_forcar=$_POST["hidforcar"];
				$ls_coduso=$_POST["txtcoduso"];
				$ls_denuso=$_POST["txtdenuso"];
				$ls_codusomac=$_POST["txtcodusomac"];
				$lb_tiendasignada = $io_funciones_inventario->uf_obtenervalor("txtasignados","");

			}/*
			else
			{
				$ls_codcla  = "000";
				$ls_codcla1 = "000";
				$ls_codusomac = "000";
				$ls_coduso  = 0;
			}*/

        if ($ls_tippro=="IT")
         {          
          
          if(($ls_codart=="")||($ls_denart=="")||($ls_spg_cuenta=="")||($ls_tippro==""))	
         {
					$io_msg->message("Debe completar todos los campos requeridos, aquellos señalados con (*)");
					$disabled="disabled";
					if($ls_venta=="Si")
					{
						$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
						//$io_siv->uf_sim_select_tiendaasign_articulo($ls_codemp,$ls_codart,$ls_asignados);
		
						$io_producto->uf_select_producto2($ls_codart,$io_datastore);
						/*$ls_codcla=$io_datastore->getValue("codcla",1);
						$ls_dencla=$io_datastore->getValue("dencla",1);
						$ls_codcla1=$io_datastore->getValue("cod_sub",1);
						$ls_dencla1=$io_datastore->getValue("den_sub",1);
						$ls_coduso=$io_datastore->getValue("id_uso",1);
						$ls_denuso=$io_datastore->getValue("denuso",1);
						$ls_codusomac=$io_datastore->getValue("codusomac",1);
						print "-2-".$ls_codcla1."<br>";*/
					}
			}	
		
		else
			{
				if( ($ls_venta=="Si") && ($ls_tippro=="") )
				{
						$io_msg->message("Debe completar todos los campos requeridos, aquellos señalados con (*)");
						$disabled="disabled";
						if($ls_venta=="Si")
						{
							$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
							//$io_siv->uf_sim_select_tiendaasign_articulo($ls_codemp,$ls_codart,$ls_asignados);
							$io_producto->uf_select_producto2($ls_codart,$io_datastore);
							$ls_codusomac=$io_datastore->getValue("codusomac",1);
			
						}
				}
				else
				{
					$lb_valido=$io_siv->uf_sim_select_cuentaspg($ls_codemp,$ls_spg_cuenta);
					if($lb_valido)
					{
						$li_prearta=   str_replace(".","",$li_prearta);
						$li_prearta=   str_replace(",",".",$li_prearta);
						$li_preartb=   str_replace(".","",$li_preartb);
						$li_preartb=   str_replace(",",".",$li_preartb);
						$li_preartc=   str_replace(".","",$li_preartc);
						$li_preartc=   str_replace(",",".",$li_preartc);
						$li_preartd=   str_replace(".","",$li_preartd);
						$li_preartd=   str_replace(",",".",$li_preartd);
						$li_pesart=    str_replace(".","",$li_pesart);
						$li_pesart=    str_replace(",",".",$li_pesart);
						$li_altart=    str_replace(".","",$li_altart);
						$li_altart=    str_replace(",",".",$li_altart);
						$li_ancart=    str_replace(".","",$li_ancart);
						$li_ancart=    str_replace(",",".",$li_ancart);
						$li_proart=    str_replace(".","",$li_proart);
						$li_proart=    str_replace(",",".",$li_proart);
						$ld_feccreart=$io_func->uf_convertirdatetobd($ld_feccreart);
						$ld_fecvenart=$io_func->uf_convertirdatetobd($ld_fecvenart);

						if ($ls_status=="C")
						{
							$io_siv->io_sql->begin_transaction();

							$lb_valido=$io_siv->uf_sim_update_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																	   $ld_feccreart, $ls_obsart, /*$li_exiart, $li_exiiniart, $li_eximinart,
																	   $li_eximaxart,*/
																	   $ls_tippro, $ls_codcla, $ls_codcla1, $ls_coduso, $ls_codusomac, $ls_opctipcos,
																	   $li_prearta, $li_preartb, $li_preartc, $li_preartd,
																	   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																	   $li_proart, $ls_nomfot, $ls_codcatsig, $li_lote, $li_util,
																	    /*$ls_sccuenta,*/ $la_seguridad);
							if($lb_valido)
							{
								$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);

							}

							if($lb_valido)
							{
								if($ls_venta=="Si"){

									$lb_valido=$io_producto->uf_guardar_productostienda($ls_codpro,"00000","0,00","0,00",$ls_opctipcos,"0,00","0,00","0,00","0,00",
																	"0,00","0,00","0,00","0,00","0,00","0,00","0,00",$lb_tiendasignada,$la_seguridad);

									if($lb_valido){
										$io_siv->io_sql->commit();
										$io_msg->message("El artículo fue actualizado.");

										$disabled="";
										uf_limpiarvariables();
										$ls_readonly="readonly";
										uf_limpiarproducto();
									}else{
										$io_siv->io_sql->rollback();
										$io_msg->message("El artículo no pudo ser actualizado.");
										$disabled="disabled";
										uf_limpiarvariables();
										$ls_readonly="readonly";
										uf_limpiarproducto();
									}
								}else{
									$io_siv->io_sql->commit();
									$io_msg->message("El artículo fue actualizado.");

									$disabled="";
									uf_limpiarvariables();
									$ls_readonly="readonly";
								}
							}
							else
							{
								$io_msg->message("El artículo no pudo ser actualizado.");
								$disabled="disabled";
								uf_limpiarvariables();
								$ls_readonly="readonly";
								uf_limpiarproducto();
							}
						}
						else
						{
							$lb_encontrado=$io_siv->uf_sim_select_articulo($ls_codemp,$ls_codart);
							if ($lb_encontrado)
							{
								$io_msg->message("El artículo ya existe.");
								$disabled="disabled";
								$io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,false,$ls_disponibles);

							}
							else
							{
								$io_siv->io_sql->begin_transaction();
								$lb_valido=$io_siv->uf_sim_insert_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																		   $ld_feccreart, $ls_obsart, /*$li_exiart, $li_exiiniart, $li_eximinart,
																		   $li_eximaxart,*/
																		   $ls_tippro, $ls_codcla, $ls_codcla1, $ls_coduso, $ls_codusomac, $ls_opctipcos,
																		   $li_prearta, $li_preartb, $li_preartc, $li_preartd,
																		   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																		   $li_proart, $ls_nomfot, $ls_codcatsig, $li_lote,$li_util,
																		   /*$ls_sccuenta,*/ $la_seguridad);
								if($lb_valido)
								{
									$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
								}
								if ($lb_valido)
								{

									if($ls_venta=="Si"){
										$lb_tiendasignada = $io_funciones_inventario->uf_obtenervalor("txtasignados","");

										$lb_valido=$io_producto->uf_guardar_productostienda($ls_codpro,"00000","0,00","0,00",$ls_opctipcos,"0,00","0,00","0,00","0,00",
																	"0,00","0,00","0,00","0,00","0,00","0,00","0,00",$lb_tiendasignada,$la_seguridad);
										if($lb_valido){
											$io_siv->io_sql->commit();
											$io_msg->message("El articulo fue registrado.");

											$lb_abrircargos=true;
											//$lb_abrircargos=false;
											$ls_readonly="readonly";
											$disabled="";
											$io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
											$io_siv->uf_sim_select_tiendaasing_articulo($ls_codemp,$ls_codart,$ls_asignados);

										}else{
											$io_siv->io_sql->rollback();
											$io_msg->message("No se pudo completar la operación.");
											$disabled ="disabled";
											//uf_limpiarvariables();
											$ls_readonly="readonly";
										}

									}else{
										$lb_abrircargos=true;
										//$lb_abrircargos=false;
										//uf_limpiarvariables();
										$ls_readonly="readonly";
										$disabled="";

										$io_siv->io_sql->commit();
										$io_msg->message("El artículo fue registrado.");
									}

								}
								else
								{
									$io_siv->io_sql->rollback();
									$io_msg->message("No se pudo incluir el artículo.");
									$disabled ="disabled";
									//uf_limpiarvariables();
									$ls_readonly="readonly";
								}

							}
						}
					}
					else
					{
						$io_msg->message("Debe incluir una cuenta presupuestaria valida");
						$disabled="disabled";
						$ls_readonly="readonly";
					}
				}
			}
		
		
		
		
		}
		else
		{	
			
			//if(($ls_codart=="")||($ld_feccreart=="")||($ls_codtipart=="")||($ls_codunimed=="")||($ls_denart=="")||($li_exiiniart=="")||($li_eximinart=="")||($li_eximaxart=="")||($ls_spg_cuenta==""))
			if(($ls_codart=="")||($ld_feccreart=="")||($ls_codtipart=="")||($ls_codunimed=="")||($ls_denart=="")||($ls_spg_cuenta=="")||($ls_tippro==""))
			{
					$io_msg->message("Debe completar todos los campos requeridos, aquellos señalados con (*)");
					$disabled="disabled";
					if($ls_venta=="Si")
					{
						$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
						//$io_siv->uf_sim_select_tiendaasign_articulo($ls_codemp,$ls_codart,$ls_asignados);
		
						$io_producto->uf_select_producto2($ls_codart,$io_datastore);
						/*$ls_codcla=$io_datastore->getValue("codcla",1);
						$ls_dencla=$io_datastore->getValue("dencla",1);
						$ls_codcla1=$io_datastore->getValue("cod_sub",1);
						$ls_dencla1=$io_datastore->getValue("den_sub",1);
						$ls_coduso=$io_datastore->getValue("id_uso",1);
						$ls_denuso=$io_datastore->getValue("denuso",1);
						$ls_codusomac=$io_datastore->getValue("codusomac",1);
						print "-2-".$ls_codcla1."<br>";*/
					}
			}
			else
			{
				if( ($ls_venta=="Si") && (($ls_tippro=="")||($ls_codcla1=="")||($ls_codcla=="")||($ls_coduso=="")) )
				{
						$io_msg->message("Debe completar todos los campos requeridos, aquellos señalados con (*)");
						$disabled="disabled";
						if($ls_venta=="Si")
						{
							$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
							//$io_siv->uf_sim_select_tiendaasign_articulo($ls_codemp,$ls_codart,$ls_asignados);
							$io_producto->uf_select_producto2($ls_codart,$io_datastore);
							$ls_codusomac=$io_datastore->getValue("codusomac",1);
			
						}
				}
				else
				{
					$lb_valido=$io_siv->uf_sim_select_cuentaspg($ls_codemp,$ls_spg_cuenta);
					if($lb_valido)
					{
						$li_prearta=   str_replace(".","",$li_prearta);
						$li_prearta=   str_replace(",",".",$li_prearta);
						$li_preartb=   str_replace(".","",$li_preartb);
						$li_preartb=   str_replace(",",".",$li_preartb);
						$li_preartc=   str_replace(".","",$li_preartc);
						$li_preartc=   str_replace(",",".",$li_preartc);
						$li_preartd=   str_replace(".","",$li_preartd);
						$li_preartd=   str_replace(",",".",$li_preartd);
						$li_pesart=    str_replace(".","",$li_pesart);
						$li_pesart=    str_replace(",",".",$li_pesart);
						$li_altart=    str_replace(".","",$li_altart);
						$li_altart=    str_replace(",",".",$li_altart);
						$li_ancart=    str_replace(".","",$li_ancart);
						$li_ancart=    str_replace(",",".",$li_ancart);
						$li_proart=    str_replace(".","",$li_proart);
						$li_proart=    str_replace(",",".",$li_proart);
						$ld_feccreart=$io_func->uf_convertirdatetobd($ld_feccreart);
						$ld_fecvenart=$io_func->uf_convertirdatetobd($ld_fecvenart);

						if ($ls_status=="C")
						{
							$io_siv->io_sql->begin_transaction();

							$lb_valido=$io_siv->uf_sim_update_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																	   $ld_feccreart, $ls_obsart, /*$li_exiart, $li_exiiniart, $li_eximinart,
																	   $li_eximaxart,*/
																	   $ls_tippro, $ls_codcla, $ls_codcla1, $ls_coduso, $ls_codusomac, $ls_opctipcos,
																	   $li_prearta, $li_preartb, $li_preartc, $li_preartd,
																	   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																	   $li_proart, $ls_nomfot, $ls_codcatsig, $li_lote, $li_util,
																	    /*$ls_sccuenta,*/ $la_seguridad);
							if($lb_valido)
							{
								$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);

							}

							if($lb_valido)
							{
								if($ls_venta=="Si"){

									$lb_valido=$io_producto->uf_guardar_productostienda($ls_codpro,"00000","0,00","0,00",$ls_opctipcos,"0,00","0,00","0,00","0,00",
																	"0,00","0,00","0,00","0,00","0,00","0,00","0,00",$lb_tiendasignada,$la_seguridad);

									if($lb_valido){
										$io_siv->io_sql->commit();
										$io_msg->message("El artículo fue actualizado.");

										$disabled="";
										uf_limpiarvariables();
										$ls_readonly="readonly";
										uf_limpiarproducto();
									}else{
										$io_siv->io_sql->rollback();
										$io_msg->message("El artículo no pudo ser actualizado.");
										$disabled="disabled";
										uf_limpiarvariables();
										$ls_readonly="readonly";
										uf_limpiarproducto();
									}
								}else{
									$io_siv->io_sql->commit();
									$io_msg->message("El artículo fue actualizado.");

									$disabled="";
									uf_limpiarvariables();
									$ls_readonly="readonly";
								}
							}
							else
							{
								$io_msg->message("El artículo no pudo ser actualizado.");
								$disabled="disabled";
								uf_limpiarvariables();
								$ls_readonly="readonly";
								uf_limpiarproducto();
							}
						}
						else
						{
							$lb_encontrado=$io_siv->uf_sim_select_articulo($ls_codemp,$ls_codart);
							if ($lb_encontrado)
							{
								$io_msg->message("El artículo ya existe.");
								$disabled="disabled";
								$io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,false,$ls_disponibles);

							}
							else
							{
								$io_siv->io_sql->begin_transaction();
								$lb_valido=$io_siv->uf_sim_insert_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																		   $ld_feccreart, $ls_obsart, /*$li_exiart, $li_exiiniart, $li_eximinart,
																		   $li_eximaxart,*/
																		   $ls_tippro, $ls_codcla, $ls_codcla1, $ls_coduso, $ls_codusomac, $ls_opctipcos,
																		   $li_prearta, $li_preartb, $li_preartc, $li_preartd,
																		   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																		   $li_proart, $ls_nomfot, $ls_codcatsig, $li_lote,$li_util,
																		   /*$ls_sccuenta,*/ $la_seguridad);
								if($lb_valido)
								{
									$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
								}
								if ($lb_valido)
								{

									if($ls_venta=="Si"){
										$lb_tiendasignada = $io_funciones_inventario->uf_obtenervalor("txtasignados","");

										$lb_valido=$io_producto->uf_guardar_productostienda($ls_codpro,"00000","0,00","0,00",$ls_opctipcos,"0,00","0,00","0,00","0,00",
																	"0,00","0,00","0,00","0,00","0,00","0,00","0,00",$lb_tiendasignada,$la_seguridad);
										if($lb_valido){
											$io_siv->io_sql->commit();
											$io_msg->message("El articulo fue registrado.");

											$lb_abrircargos=true;
											//$lb_abrircargos=false;
											$ls_readonly="readonly";
											$disabled="";
											$io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
											$io_siv->uf_sim_select_tiendaasing_articulo($ls_codemp,$ls_codart,$ls_asignados);

										}else{
											$io_siv->io_sql->rollback();
											$io_msg->message("No se pudo completar la operación.");
											$disabled ="disabled";
											//uf_limpiarvariables();
											$ls_readonly="readonly";
										}

									}else{
										$lb_abrircargos=true;
										//$lb_abrircargos=false;
										//uf_limpiarvariables();
										$ls_readonly="readonly";
										$disabled="";

										$io_siv->io_sql->commit();
										$io_msg->message("El artículo fue registrado.");
									}

								}
								else
								{
									$io_siv->io_sql->rollback();
									$io_msg->message("No se pudo incluir el artículo.");
									$disabled ="disabled";
									//uf_limpiarvariables();
									$ls_readonly="readonly";
								}

							}
						}
					}
					else
					{
						$io_msg->message("Debe incluir una cuenta presupuestaria valida");
						$disabled="disabled";
						$ls_readonly="readonly";
					}
				}
			}
			
		} //if IT	
			
			$ld_feccreart=$io_func->uf_convertirfecmostrar($ld_feccreart);
			$ld_fecvenart=$io_func->uf_convertirfecmostrar($ld_fecvenart);

		break;

		case "ELIMINAR":
			$ls_codart=    $io_funciones_inventario->uf_obtenervalor("txtcodart","");
			$lb_tiendasignada = $io_funciones_inventario->uf_obtenervalor("txtasignados","");

			if($ls_venta=="No"){
				$io_siv->io_sql->begin_transaction();
				$lb_valido=$io_siv->uf_sim_delete_articulo($ls_codemp,$ls_codart, $la_seguridad);

				if($lb_valido)
				{
					$io_siv->io_sql->commit();
					$io_msg->message("El articulo fue eliminado.");
					//$io_archivo->uf_sim_delete_articulo_transf($ls_codemp,$ls_codart,$la_seguridad);
					uf_limpiarvariables();
					$ls_readonly="readonly";
				}
				else
				{
					$io_siv->io_sql->rollback();
					$io_msg->message("No se pudo eliminar el articulo.");
					uf_limpiarvariables();
					$ls_readonly="readonly";
				}

			}else{

				//verificamos en cotizacion
				$ls_sql1="SELECT * FROM sfc_detcotizacion WHERE codemp='".$ls_codemp."' AND codart='".$ls_codart."'";

				$io_sql= new class_sql($con);
				$rs_datauni1=$io_sql->select($ls_sql1);

				if($rs_datauni1==false)
				{
					$lb_valido_detcot=false;
					$io_msg="Error en uf_select_detcotizacion ".$io_func->uf_convertirmsg($io_sql->message);
				}
				else
				{
					if($row=$io_sql->fetch_row($rs_datauni1))
					{
						$lb_valido_detcot=true; //Registro encontrado
				        $io_msg->message ("El art�culo esta enlazado con alguna Cotizacion no se puede eliminar!!!");
					}
					else
					{
						//$lb_valido_detcot=false; //"Registro no encontrado"
						//Verifcamos si existe en factura
						$ls_sql="SELECT * FROM sfc_detfactura WHERE codemp='".$ls_codemp."' AND codart='".$ls_codart."'";

						$rs_datauni=$io_sql->select($ls_sql);

						if($rs_datauni==false)
						{
							$lb_valido_detfac=false;
							$io_msg="Error en uf_select_detfactura ".$io_func->uf_convertirmsg($io_sql->message);
						}
						else
						{
							if($row=$io_sql->fetch_row($rs_datauni))
							{
								$lb_valido_detfac=true; //Registro encontrado
						        $io_msg->message ("El articulo esta enlazado con alguna Factura no se puede eliminar!!!");
							}
							else
							{
								//validamos si tiene movimientos
								$ls_sql2="SELECT codart FROM sim_dt_movimiento WHERE codemp='".$ls_codemp."' AND codart='".$ls_codart."'";
								$rs_datauni2=$io_sql->select($ls_sql2);
								if($rs_datauni2==false)
								{
									$lb_valido_detmov=false;
									$is_msg="Error en select_dt_movimiento ".$io_func->uf_convertirmsg($io_sql->message);
								}else{
									if($row=$io_sql->fetch_row($rs_datauni2))
									{
										$lb_valido_detmov=true; //Registro encontrado
								        $io_msg->message("El articulo esta enlazado con algun Movimiento no se puede eliminar!!!");
									}else{

										$io_sql->begin_transaction();

										/*$lb_valido_alm=$io_axa->uf_sim_delete_articuloxalmacen_tienda($ls_codemp,$ls_codart,$lb_tiendasignada);

										if($lb_valido_alm)
										{
										*/
											//$lb_valido_detfac=false; //"Registro no encontrado"
											$lb_valido=$io_producto->uf_delete_producto_tiendas($ls_codart,$lb_tiendasignada,$la_seguridad);
											if($lb_valido){
												$lb_valido=$io_siv->uf_sim_delete_articulo($ls_codemp,$ls_codart,$la_seguridad);

												if($lb_valido)
												{
													$io_sql->commit();
													$io_msg->message("El articulo fue eliminado.");
													uf_limpiarvariables();
													$ls_readonly="readonly";
												}
												else
												{
													$io_sql->rollback();
													$io_msg->message("No se pudo eliminar el Producto");
													$ls_readonly="readonly";
												}
											}else{
												$io_sql->rollback();
												$io_msg->message("No se pudo eliminar el Producto");
												$ls_readonly="readonly";
											}
										/*
										}
										else
										{
											$io_sql->rollback();
											$io_msg->message("No se pudo eliminar el articulo.");
											$ls_readonly="readonly";
										}*/
									}
								}
							}
						}
					}
				}
			}


		break;

		case "ue_actualizar_option":
			if ( $ls_opctipcos=="UC"){
			  $ls_opctipcos="UC";
			  }else{
			  $ls_opctipcos="CP";
			  }
		break;

		case "CARGARPROD":
			$ls_codart=      $io_funciones_inventario->uf_obtenervalor("txtcodart","");
			$ls_denart=      $io_funciones_inventario->uf_obtenervalor("txtdenart","");
			$ls_codtipart=   $io_funciones_inventario->uf_obtenervalor("txtcodtipart","");
			$ls_codunimed=   $io_funciones_inventario->uf_obtenervalor("txtcodunimed","");
			$ls_dentipart=   $io_funciones_inventario->uf_obtenervalor("txtdentipart","");
			$ls_denunimed=   $io_funciones_inventario->uf_obtenervalor("txtdenunimed","");
			$ld_feccreart=   $io_funciones_inventario->uf_obtenervalor("txtfeccreart","");
			$ls_obsart=      $io_funciones_inventario->uf_obtenervalor("txtobsart","");
			$li_prearta=     $io_funciones_inventario->uf_obtenervalor("txtprearta","");
			$li_preartb=     $io_funciones_inventario->uf_obtenervalor("txtpreartb","");
			$li_preartc=     $io_funciones_inventario->uf_obtenervalor("txtpreartc","");
			$li_preartd=     $io_funciones_inventario->uf_obtenervalor("txtpreartd","");
			$ld_fecvenart=   $io_funciones_inventario->uf_obtenervalor("txtfecvenart","");
			$ls_codcatsig=   $io_funciones_inventario->uf_obtenervalor("txtcodcatsig","");
			$ls_dencatsig=   $io_funciones_inventario->uf_obtenervalor("txtdencatsig","");
			$ls_spg_cuenta=  $io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$li_pesart=      $io_funciones_inventario->uf_obtenervalor("txtpesart","");
			$li_altart=      $io_funciones_inventario->uf_obtenervalor("txtaltart","");
			$li_ancart=      $io_funciones_inventario->uf_obtenervalor("txtancart","");
			$li_proart=      $io_funciones_inventario->uf_obtenervalor("txtproart","");
			$ls_status=      $io_funciones_inventario->uf_obtenervalor("hidstatusc","");
			$li_ultcosart=   $io_funciones_inventario->uf_obtenervalor("txtultcosart","");
			$li_cosproart=   $io_funciones_inventario->uf_obtenervalor("txtcosproart","");
			$ls_status=      $io_funciones_inventario->uf_obtenervalor("hidstatusc","");
            $ls_lote=        $io_funciones_inventario->uf_obtenervalor("hidlote","");
			$li_util=        $io_funciones_inventario->uf_obtenervalor("txtutil","");
			$ls_codcla1=     $io_funciones_inventario->uf_obtenervalor("txtcodcla1","");
			$ls_codcla=      $io_funciones_inventario->uf_obtenervalor("txtcodcla","");
			$ls_denuso=      $io_funciones_inventario->uf_obtenervalor("txtcoduso","");
		    $ls_venta=       $io_funciones_inventario->uf_obtenervalor("hidventa","");						
			
			$li_prearta=   str_replace(".","",$li_prearta);
			$li_prearta=   str_replace(",",".",$li_prearta);
			$li_preartb=   str_replace(".","",$li_preartb);
			$li_preartb=   str_replace(",",".",$li_preartb);
			$li_preartc=   str_replace(".","",$li_preartc);
			$li_preartc=   str_replace(",",".",$li_preartc);
			$li_preartd=   str_replace(".","",$li_preartd);
			$li_preartd=   str_replace(",",".",$li_preartd);
			$li_pesart=    str_replace(".","",$li_pesart);
			$li_pesart=    str_replace(",",".",$li_pesart);
			$li_altart=    str_replace(".","",$li_altart);
			$li_altart=    str_replace(",",".",$li_altart);
			$li_ancart=    str_replace(".","",$li_ancart);
			$li_ancart=    str_replace(",",".",$li_ancart);
			$li_proart=    str_replace(".","",$li_proart);
			$li_proart=    str_replace(",",".",$li_proart);

			$ls_tippro=	   $io_funciones_inventario->uf_obtenervalor("cmbtippro","");
			$ls_tipcos=	   $io_funciones_inventario->uf_obtenervalor("hidtipcos","CP");           
			
			$ls_nomfot=$HTTP_POST_FILES['txtfotart']['name'];
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codart.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotart']['type'];
			$ls_tamfot=$HTTP_POST_FILES['txtfotart']['size'];
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotart']['tmp_name'];
			$disabled="";
			
			if($ls_venta=="Si")
			{
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);
				$io_producto->uf_select_producto2($ls_codart,$io_datastore);
			}
			
			if($io_producto->uf_select_producto2($ls_codart,$io_datastore))
			{
				$ls_venta = "Si";
				$ls_chksi = "checked";
				$ls_chkno = "";

				$ls_codcla=$io_datastore->getValue("codcla",1);
				$ls_dencla=$io_datastore->getValue("dencla",1);
				$ls_codcla1=$io_datastore->getValue("cod_sub",1);
				$ls_dencla1=$io_datastore->getValue("den_sub",1);
				$ls_coduso=$io_datastore->getValue("id_uso",1);
				$ls_denuso=$io_datastore->getValue("denuso",1);
				$ls_codusomac=$io_datastore->getValue("codusomac",1);

				$ls_disponibles = array();
				$ls_valor = $io_siv->uf_sim_select_tiendadispo_articulo($ls_codemp,$ls_codart,true,$ls_disponibles);

				$ls_asignados = array();
				$io_siv->uf_sim_select_tiendaasing_articulo($ls_codemp,$ls_codart,$ls_asignados);

			}else{
				$ls_venta = "No";
				$ls_chksi = "";
				$ls_chkno = "checked";
			}
			
		break;
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Art&iacute;culo </title>
<meta http-equiv="imagetoolbar" content="no">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/ajax.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../sfc/js/validaciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">

<script language="javascript">
	if(document.all)
	{ //ie
		document.onkeydown = function(){
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505;
		}
		if(window.event.keyCode == 505){ return false;}
		}
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
.Estilo2 {font-size: 14px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo2">Sistema de Inventario</span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="7" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="32"><div align="center"></div></td>
    <td class="toolbar" width="610">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table width="700" height="647" border="0" class="formato-blanco">
      <tr>
        <td height="15" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="650" colspan="2"><div align="left">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);?>
            <table width="650" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Definici&oacute;n del Producto </td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4"> <div align="center">Los Campos en (*) son necesarios para la Incluir el Producto </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4">
                	<input name="hidstatusc" type="hidden" id="hidstatusc" value="<?print $ls_status;?>">
                	<input name="hidtipcos" type="hidden" id="hidtipcos"  value="<?print $ls_opctipcos; ?>">
                	<input name="hidstatus" type="hidden" id="hidstatus">                </td>
              </tr>
              <tr class="formato-blanco">
              	<td width="86" height="22"><div align="right">(*)Para la venta</div></td>
              	<td width="207" height="22"> <input name="rdventa" type="radio" id="rdventa" value="1" <?print $ls_chksi." ";?> onClick="by_venta('Si');">Si <input name="rdventa" type="radio" id="rdventa" value="0" <?print $ls_chkno." ";?> onClick="by_venta('No');">No <input name="hidventa" type="hidden" id="hidventa" value="<?print $ls_venta;?>"> </td>
              </tr>

              <tr>
                <td height="22" align="right">Lote</td>
                <td colspan="3" ><input name="hidlote" type="hidden" id="hidlote" value="<?print $ls_lote;?>">
                  <?php 
					  if( ($ls_lote=="Si")  || ($ls_lote=="1")  )
					  {
						  $ls_chks="checked";
						  $ls_chkn="";
					  }
					  else 
					  {
						  $ls_chks="";
						  $ls_chkn="checked";
					  }							  
					  ?>
                  <input name="rdlote" type="radio" id="rdlote" value="1" <?print $ls_chks." ";?> onClick="by_lote('Si');">
Si
<input name="rdlote" type="radio" id="rdlote" value="0" <?print $ls_chkn." ";?> onClick="by_lote('No');">
No</td>
              </tr>
              <tr>
                <td height="22" align="right">(*)Tipo de Concepto  </td>
                <td colspan="3" >
                  <label><span class="style6">
                  <select name="cmbtippro" size="1" id="cmbtippro">
				  <?php
				    if($ls_tippro=="")
					 {
				   ?>
				    <option value="" onClick="actualizar_check1();" selected>Seleccione Una</option>
                    <option value="B" onClick="actualizar_check1('B');">Bien</option>
					<option value="S" onClick="actualizar_check1('S');">Servicio</option>
					<option value="P" onClick="actualizar_check1('P');">Producto</option>
					<option value="IT" onClick="actualizar_check1('IT');">Insumo de Traslado</option>
					<option value="SP" onClick="actualizar_check1('SP');">Subproducto</option>
					
					<?php
					 }
					 elseif($ls_tippro=="B")
					 {
					?>
					 <option value="" onClick="actualizar_check1();">Seleccione Una</option>
                     <option value="B" onClick="actualizar_check1('B');" selected>Bien</option>
					 <option value="S" onClick="actualizar_check1('S');">Servicio</option>
					 <option value="P" onClick="actualizar_check1('P');">Producto</option>
					 <option value="IT" onClick="actualizar_check1('IT');">Insumo de Traslado</option>
				 	 <option value="SP" onClick="actualizar_check1('SP');">Subproducto</option>
					<?php
					 }
					 elseif($ls_tippro=="S")
					 {
					?>
					 <option value="" onClick="actualizar_check1();">Seleccione Una</option>
                     <option value="B" onClick="actualizar_check1('B');">Bien</option>
					 <option value="S" onClick="actualizar_check1('S');" selected>Servicio</option>
					 <option value="P" onClick="actualizar_check1('P');">Producto</option>
					 <option value="IT" onClick="actualizar_check1('IT');">Insumo de Traslado</option>
					 <option value="SP" onClick="actualizar_check1('SP');">Subproducto</option>
					<?php
					 }
					 elseif($ls_tippro=="P")
					 {
					?>
					 <option value="" onClick="actualizar_check1();">Seleccione Una</option>
                     <option value="B" onClick="actualizar_check1('B');">Bien</option>
					 <option value="S" onClick="actualizar_check1('S');">Servicio</option>
					 <option value="P" onClick="actualizar_check1('P');" selected>Producto</option>
					 <option value="IT" onClick="actualizar_check1('IT');">Insumo de Traslado</option>
					 <option value="SP" onClick="actualizar_check1('SP');">Subproducto</option>
					<?php
					 }
					 elseif($ls_tippro=="IT")
					 {
					?>
					 <option value="" onClick="actualizar_check1();">Seleccione Una</option>
                     <option value="B" onClick="actualizar_check1('B');">Bien</option>
					 <option value="S" onClick="actualizar_check1('S');">Servicio</option>
					 <option value="P" onClick="actualizar_check1('P');">Producto</option>
					 <option value="IT" onClick="actualizar_check1('IT');" selected>Insumo de Traslado</option>
					 <option value="SP" onClick="actualizar_check1('SP');">Subproducto</option>
					<?php
					 }
					 elseif($ls_tippro=="SP")
					 {
					?>
					 <option value="" onClick="actualizar_check1();">Seleccione Una</option>
                     <option value="B" onClick="actualizar_check1('B');">Bien</option>
					 <option value="S" onClick="actualizar_check1('S');">Servicio</option>
					 <option value="P" onClick="actualizar_check1('P');">Producto</option>
					 <option value="IT" onClick="actualizar_check1('IT');">Insumo de Traslado</option>
					 <option value="SP" onClick="actualizar_check1('SP');" selected>Subproducto</option>
					<?php
					 }
					?>
                  </select>
                  </span></label></td>
              </tr>

			  <tr class="formato-blanco">
			  <?php
			  	if($li_estnum)
				{?>
                <td height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td height="22"><input name="txtcodart" type="text" id="txtcodart" value="<?php print $ls_codart?>" size="25" maxlength="20" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890');"  onBlur="ue_rellenarcampo(this,'20')"></td>
  			  <?php
			  	}
				else
				{
			  ?>
                <td width="56" height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td width="133" height="22"><input name="txtcodart" type="text" id="txtcodart" value="<?php print $ls_codart?>" size="25" maxlength="20" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz-');"  onBlur="ue_rellenarcampo(this,'20');"></td>
			  <?php
			  	}
			  ?>
                <td width="138" rowspan="6"><div align="center"><img name="foto" id="foto" src="fotosarticulos/<?php print $ls_foto?>" width="121" height="94" class="formato-blanco"></div></td>
                <td width="9">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Denominaci&oacute;n</div></td>
                <td height="22"><input name="txtdenart" type="text" id="txtdenart" value="<?php print $ls_denart?>" size="40" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz ()#!%/[]*-+_.,:;');" ></td>
                <td height="22">&nbsp;</td>
              </tr>
            
            
             <? if($ls_tippro=="IT")              
             
             { ?>
              <tr>
                <td height="22"><div align="right"> Tipo de Producto </div></td>
                <td height="22"><input name="txtcodtipart" type="text" id="txtcodtipart" value="<?php print $ls_codtipart?>" size="6" maxlength="4" readonly>
                <a href="javascript: ue_catatipart();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdentipart" type="text" class="sin-borde" id="txtdentipart" value="<?php print $ls_dentipart?>" size="30" readonly>
                <input name="txtobstipart" type="hidden" id="txtobstipart"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> Unidad de Medida</div></td>
                <td height="22"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="6" maxlength="4" readonly>
                  <a href="javascript: ue_cataunimed();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdenunimed" type="text" class="sin-borde" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="30" readonly>
                <input name="txtunidad" type="hidden" id="txtunidad">
                <input name="txtobsunimed" type="hidden" id="txtobsunimed"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> Fecha de Creaci&oacute;n </div></td>
                <td height="22"><input name="txtfeccreart" type="text" id="txtfeccreart" value="<?php print $ld_feccreart?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
                <td height="22">&nbsp;</td>
              </tr>
                                         
              
              <? }
            else {
              ?> 
      
              <tr>
                <td height="22"><div align="right"> (*)Tipo de Producto </div></td>
                <td height="22"><input name="txtcodtipart" type="text" id="txtcodtipart" value="<?php print $ls_codtipart?>" size="6" maxlength="4" readonly>
                <a href="javascript: ue_catatipart();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdentipart" type="text" class="sin-borde" id="txtdentipart" value="<?php print $ls_dentipart?>" size="30" readonly>
                <input name="txtobstipart" type="hidden" id="txtobstipart"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Presentaci&oacute;n</div></td>
                <td height="22"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="6" maxlength="4" readonly>
                  <a href="javascript: ue_cataunimed();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdenunimed" type="text" class="sin-borde" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="30" readonly>
                <input name="txtunidad" type="hidden" id="txtunidad">
                <input name="txtobsunimed" type="hidden" id="txtobsunimed"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Fecha de Creaci&oacute;n </div></td>
                <td height="22"><input name="txtfeccreart" type="text" id="txtfeccreart" value="<?php print $ld_feccreart?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
                <td height="22">&nbsp;</td>
              </tr>
              
               <? } ?> 
              
              <tr>
                <td height="26"><div align="right">
                    <p>Observaciones</p>
                </div></td>
                <td colspan="3" rowspan="2"><textarea name="txtobsart" cols="40" rows="3" id="txtobsart" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn�opqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obsart?></textarea></td>
              </tr>
              <tr>
                <td height="27">&nbsp;</td>
              </tr>

			  <?php
			  	if($li_catalogo==1)
				{?>
              <tr>
                <td height="22" align="right">(*)SIGECOF</td>
                <td height="22" colspan="3"><label>
                  <input name="txtcodcatsig" type="text" id="txtcodcatsig" style="text-align:center" value="<?php print $ls_codcatsig?>" size="25" readonly>
                  <a href="javascript: ue_sigecof();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                  <input name="txtdencatsig" type="text" class="sin-borde" id="txtdencatsig" value="<?php print $ls_dencatsig?>" size="50" readonly>
                </label></td>
              </tr>
			  <?php
			  	}
			  ?>
              <tr>
                <td height="22"><div align="right"> (*)Cuenta Presupuestaria </div></td>
                <td height="22" colspan="3"><input name="txtspg_cuenta" type="text" id="txtspg_cuenta" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_spg_cuenta?>" size="25" maxlength="25" readonly style="text-align:center ">
			  <?php
				if($li_catalogo!=1)
				{
				?>

                    <a href="javascript: ue_cataspg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
			  <?php
			  	}
			  ?>
              </tr>

              <tr>
                <td height="22"><div align="right"> (*)Precio de Venta </div></td>
                <td height="22" colspan="3"><input name="txtprearta" type="text" id="txtprearta" value="<?php print number_format($li_prearta,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                  <p> Precio de Venta   1 </p>
                  </div></td>
                <td height="22" colspan="3"><input name="txtpreartb" type="text" id="txtpreartb" value="<?php print number_format($li_preartb,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                    <p>Precio de Venta   2 </p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpreartc" type="text" id="txtpreartc" value="<?php print number_format($li_preartc,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Precio de Venta   3 </div></td>
                <td height="22" colspan="3"><input name="txtpreartd" type="text" id="txtpreartd" value="<?php print number_format($li_preartd,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Fecha de Vencimiento </div></td>
                <td height="22" colspan="3"><input name="txtfecvenart" type="text" id="txtfecvenart"  value="<?php print $ld_fecvenart?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true" style="text-align:center "></td>
              </tr>
              <tr>
                <td height="22"><div align="right"><p>Peso</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpesart" type="text" id="txtpesart" value="<?php print number_format($li_pesart,2,',','.');?>" size="12"onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                Kg.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Altura</div></td>
                <td height="22" colspan="3"><input name="txtaltart" type="text" id="txtaltart" value="<?php print number_format($li_altart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ancho</div></td>
                <td height="22" colspan="3"><input name="txtancart" type="text" id="txtancart" value="<?php print number_format($li_ancart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Profundidad</div></td>
                <td height="22" colspan="3"><input name="txtproart" type="text" id="txtproart" value="<?php print number_format($li_proart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
mt.</td>
              </tr>              
              <tr>
                <td height="22"><div align="right">Vida Util </div></td>
                <td height="22" colspan="3"><input name="txtutil" type="text" id="txtutil" value="<?php print $li_util ?>" size="12" style="text-align:right "> 
                  Dias </td>
              </tr>              
              <tr>
                <td height="22"><div align="right"><p>Foto</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtfotart" id="txtfotart" type="file"></td>
              </tr>

              <tr>
			    <td height="22" align="right">Tipo Costo</td>
			    <td colspan="3" >
<!------------------------------------------------------------------------------------------------------------------------>
			      <?php
				  if ($ls_opctipcos=='UC')
				   {
				   ?>
                   <input type="radio" name="opctipcos" value="UC" checked="checked" onClick="actualizar_option()">
                  <label>Ultimo Costo</label>
                    <input type="radio" name="opctipcos" value="CP" onClick="actualizar_option()">
                    <label>Costo Promedio</label>
					<?php
					}
					elseif ($ls_opctipcos=='CP')
					{
					?>
					 <input type="radio" name="opctipcos" value="UC" onClick="actualizar_option()">
                   <label>Ultimo Costo</label>
                   <input type="radio" name="opctipcos" value="CP" checked="checked"  onClick="actualizar_option()">
                   <label>Costo Promedio</label>
					<?php
					}
					else
					{
					?>
					<input type="radio" name="opctipcos" value="UC" onClick="actualizar_option()">
                   <label>Ultimo Costo</label>

					<input type="radio" name="opctipcos" value="CP" checked="checked" onClick="actualizar_option()">
					<label>Costo Promedio</label>
					<?php
					}
					?>
<!------------------------------------------------------------------------------------------------------------------------>		        </td>
		      </tr>

              <tr>
                <td height="22"><div align="right">
                    <p>Ultimo Costo</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtultcosart" type="text" id="txtultcosart"  value="<?php print number_format($li_ultcosart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Costo Promedio</div></td>
                <td height="22" colspan="3"><input name="txtcosproart" type="text" id="txtcosproart"  value="<?php print number_format($li_cosproart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>
            </table>
            <!-- Articulo para la Venta -->
            <? if($ls_venta == "Si"){ ?>
            <div align="center">
            <table width="650" border="0" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Producto Para Facturaci&oacute;n </td>
              </tr>
              <tr>
              	<td colspan="4">
	        	<input name="hidtippro" type="hidden" id="hidtippro" value="<?php print $ls_tippro; ?>">
				<input name="hidforcar" type="hidden" id="hidforcar" value="<?php print $ls_forcar; ?>">

				</td>
              </tr>


              <tr>
                <td height="22" align="right">SubClasificacion</td>
                <td colspan="3" ><input name="txtcodcla1" type="text" id="txtcodcla1" size="5"  maxlength="3" value="<?php print $ls_codcla1?>" readonly="true">
                <a href="javascript:ue_catclasificacion1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdencla1" type="text" id="txtdencla1" value="<? print $ls_dencla1;?>" class="sin-borde" size="40" readonly="true">				</td>
              </tr>

			  <tr>
                <td height="22" align="right">Clasificacion</td>
                <td colspan="3" ><input name="txtcodcla" type="text" id="txtcodcla" size="5"  maxlength="3" value="<?php print $ls_codcla?>" readonly="true">
                <input name="txtdencla" type="text" id="txtdencla" value="<? print $ls_dencla;?>" class="sin-borde" size="40" readonly="true">                </td>
              </tr>
			    <tr>
                <td height="22" align="right">Uso</td>
                <td colspan="3" >
                	<input name="txtcoduso" type="text" id="txtcoduso" size="5"  maxlength="3" value="<?php print $ls_coduso?>" readonly="true">
                	<input name="txtcodusomac" type="hidden" id="txtcodusomac" value="<?php print $ls_codusomac?>" >
                	<a href="javascript:ue_catuso();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                	<input name="txtdenuso" type="text" id="txtdenuso" value="<? print $ls_denuso;?>" class="sin-borde" size="50" readonly="true">
                </td>
              </tr>


		      <tr>
                    <td height="22" colspan="4" class="titulo-ventana">Asignaci&oacute;n Unidad Operativa de Suministro</td>
              </tr>

		      <tr>

		       <td colspan="4">
		       <table border="0" width="100%">
		       	<tr class="formato-blanco">
			       	<td colspan="2" rowspan="6">
			       		<div align="center" class="titulo-celdanew"><span class="Estilo1">Disponibles</span></div>
			       		<? uf_print_lista("txtdisponibles","codtiend","dentie",$ls_disponibles);?>
			      </td>

		       		<td>
		       		<p></p>
		       		<input name="btnincluirtienda" type="button" class="boton" id="btnincluirtienda" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.txtdisponibles,form1.txtasignados);">
		       		<p></p>
		       		<input name="btnincluirtiendatodos" type="button" class="boton" id="btnincluirtiendatodos" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.txtdisponibles,form1.txtasignados);">
		       		<p></p>
		       		<input name="btnexcluirtienda" type="button" class="boton" id="btnexcluirtienda" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar1();">
		       		<p></p>
		       		<input name="btnexcluirtiendatodos" type="button" class="boton" id="btnexcluirtiendatodos" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.txtasignados,form1.txtdisponibles);">
		       		</td>

			       <td colspan="2" >
			       		<div align="center" class="titulo-celdanew"><span class="Estilo1">Asignadas</span></div>
			       		<? uf_print_lista("txtasignados","codtiend","dentie",$ls_asignados);?>
			       </td>

		       	</tr>
		       	</table>
		       </td>

		      </tr>

		      <tr>
                <td height="13" align="right">&nbsp;</td>
                <td colspan="3" >&nbsp;</td>
              </tr>

            </table>
            </div>
            <?} ?>
        </div></td>
      </tr>

      <tr>
        <td width="316" height="39">
          <div align="center">
            <input name="operacion" type="hidden" id="operacion4">
            <input name="btnregistrar" type="button" class="boton" id="btnregistrar" value="Registrar Componentes" onClick="javascript: ue_abrircomponentes(this);" <?php print $disabled?>>
        </div></td>
        <td width="355"><div align="center">
          <input name="btncargos" type="button" class="boton" id="btncargos" value="Agregar Cr&eacute;ditos" onClick="javascript: ue_abrircargos(this);" <?php print $disabled?>>
        </div></td>
      </tr>
    </table>
  </form>
  <?php
  	if($lb_abrircargos)
	{
		print "<script language=JavaScript>";
		print "f=document.form1;";
		print "codart=f.txtcodart.value;";
		print "denart=f.txtdenart.value;";
		print "window.open('sigesp_sim_d_cargos.php?codart='+codart+'&denart='+denart+'','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no');";
		print "</script>";
	}
  ?>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function actualizar_check1(dato)
{
   //alert("paso");
   f=document.form1;
   f.cmbtippro.value=dato;   
   f.submit();
}

function ue_catatipart()
{
	window.open("sigesp_catdinamic_tipoarticulo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cataunimed()
{
	f=document.form1;
	ls_status=f.hidstatusc.value;
	//if(ls_status!="C")
	//{
		window.open("sigesp_catdinamic_unidadmedida.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	//}
}

function ue_cataspg()
{
	window.open("sigesp_sim_cat_ctasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catascg()
{
	window.open("sigesp_sim_cat_ctasscg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_sigecof()
{
	window.open("sigesp_sim_cat_sigecof.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_sim_cat_articulo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_nuevo()
{
	f=document.form1;
	f.cmbtippro.value="";
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="NUEVO";
		f.action="sigesp_sim_d_articulo.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatusc.value;
	tipo = f.cmbtippro.options[f.cmbtippro.selectedIndex].value;

	ls_codart = f.txtcodart.value;

	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if(tipo == ""){
			alert("Selecione el Tipo de Concepto");
			f.cmbtippro.focus();
		}else{

			if(f.hidventa.value=="Si"){
				with(f)
				 {
				 	if( (ls_codart.substring(4,5)=="V") && (ls_codart.length == 20) ){

				 		if((f.txtcodcla.value == "000" || f.txtcodcla1.value == "000" || f.txtcoduso.value == "0") && f.cmbtippro.value!=='IT')
					 	{
					 		alert("Seleccione un codigo valido para Clasificacion, Sub-Clasificacion y Uso!!");
					 	}
					 	else if(f.txtasignados.length == 0)
					 	{
					 		alert("El Producto debe estar asignado al menos a una tienda!!");
					 	}
					 	else
					 	{
					 		if(f.cmbtippro.value!=='IT'){
					 		if( ue_valida_null(txtcodcla,"Clasificaci�n")==false )
						     {
						      txtcodcla.focus();
						     }
						     else if( ue_valida_null(txtcoduso,"Uso")==false )
						     {
						     	txtcoduso.focus();
						     }
						     else
						     {

						     	if(f.txtasignados!=null)
								{
									li_totasi=f.txtasignados.length;
								}
								for(i=0;i<li_totasi;i++)
								{
									f.txtasignados[i].selected=true;
								}

								if(f.txtdisponibles!=null)
								{
									li_totdis=f.txtdisponibles.length;
								}
								for(i=0;i<li_totdis;i++)
								{
									f.txtdisponibles[i].selected=true;
								}

						       /*f.operacion.value="ue_guardar";
						       f.action="sigesp_sfc_d_producto.php";
						       f.submit();*/
						       f.operacion.value="GUARDAR";
							   f.action="sigesp_sim_d_articulo.php";
							   f.submit();
							 }
					 	}
					 	else {
					 	if(f.txtasignados!=null)
								{
									li_totasi=f.txtasignados.length;
								}
								for(i=0;i<li_totasi;i++)
								{
									f.txtasignados[i].selected=true;
								}

								if(f.txtdisponibles!=null)
								{
									li_totdis=f.txtdisponibles.length;
								}
								for(i=0;i<li_totdis;i++)
								{
									f.txtdisponibles[i].selected=true;
								}

						       /*f.operacion.value="ue_guardar";
						       f.action="sigesp_sfc_d_producto.php";
						       f.submit();*/
						       f.operacion.value="GUARDAR";
							   f.action="sigesp_sim_d_articulo.php";
							   f.submit();	
						}
					 }

				 	}else{
				 		alert("El codigo del Producto es incorrecto, por favor verifiquelo!");
				 	}

				 }
			}else{
				f.operacion.value="GUARDAR";
				f.action="sigesp_sim_d_articulo.php";
				f.submit();
			}

		}

	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_eliminar()
{
	//li_totasi=0;
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{
		if(confirm("Seguro desea eliminar el registro?"))
		{
          
			if(f.txtasignados!=null)
			{
				
				li_totasi=f.txtasignados.length;
			} else {
			li_totasi=0;
		    }	
			//alert(li_totasi);
			for(i=0;i<li_totasi;i++)
			{
				//alert(li_totasi);
				f.txtasignados[i].selected=true;
			}
           
			//f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="sigesp_sim_d_articulo.php";
			f.submit();
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cerrar()
{
  f=document.form1;	
  if (f.cmbtippro.value!=='' || f.txtcodart.value!=='' || f.txtdenart.value!=='' || f.txtcodtipart.value!=='' || f.txtcodunimed.value!=='' || f.txtobsart.value!=='' || f.txtspg_cuenta.value!=='' || f.txtprearta.value!=='0,00' || f.txtpreartb.value!=='0,00' || f.txtpreartc.value!=='0,00' || f.txtpreartd.value!=='0,00' || f.txtpesart.value!=='0,00' || f.txtaltart.value!=='0,00' || f.txtancart.value!=='0,00' || f.txtproart.value!=='0,00' || f.txtutil.value!=='' || f.txtultcosart.value!=='0,00' || f.txtcosproart.value!=='0,00' || f.txtcodcla1.value!=='' || f.txtcodcla.value!=='' || f.txtcoduso.value!=='') 
   {
	if(confirm("¿Seguro desea Salir?, Si Sale perdera la información que ingresó"))
		{
         window.location.href="sigespwindow_blank.php";
        } 
   } 
   else 
   {
   	  window.location.href="sigespwindow_blank.php"; 
   }	
}

function ue_abrircomponentes()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	denart=ue_validarvacio(f.txtdenart.value)
	if (codart!="")
	{
		window.open("sigesp_sim_d_componentes.php?codart="+codart+"&denart="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar un articulo.");
	}
}

function ue_abrircargos()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	denart=ue_validarvacio(f.txtdenart.value)
	if (codart!="")
	{
		window.open("sigesp_sim_d_cargos.php?codart="+codart+"&denart="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un articulo.");
	}
}

function ue_imprimirbarras()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	window.open("genera_barras.php?codigo="+codart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
}
//--------------------------------------------------------
//	Funci�n que limpia las cajas de texto de las fechas
//--------------------------------------------------------
function ue_limpiar(fecha)
{
	f=document.form1;
	if(fecha=="creacion")
	{
		f.txtfeccreart.value="";
	}
	else
	{
		if(fecha=="vencimiento")
		{
			f.txtfecvenart.value="";
		}
	}

}

function catalogo_estpro1()
{
	   pagina="sigesp_sim_cat_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_sim_cat_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_sim_cat_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

//--------------------------------------------------------
//	Funci�n que valida una fecha
//--------------------------------------------------------
function valSep(oTxt){
    var bOk = false;
    var sep1 = oTxt.value.charAt(2);
    var sep2 = oTxt.value.charAt(5);
    bOk = bOk || ((sep1 == "-") && (sep2 == "-"));
    bOk = bOk || ((sep1 == "/") && (sep2 == "/"));
    return bOk;
   }

   function finMes(oTxt){
    var nMes = parseInt(oTxt.value.substr(3, 2), 10);
    var nAno = parseInt(oTxt.value.substr(6), 10);
    var nRes = 0;
    switch (nMes){
     case 1: nRes = 31; break;
     case 2: nRes = 28; break;
     case 3: nRes = 31; break;
     case 4: nRes = 30; break;
     case 5: nRes = 31; break;
     case 6: nRes = 30; break;
     case 7: nRes = 31; break;
     case 8: nRes = 31; break;
     case 9: nRes = 30; break;
     case 10: nRes = 31; break;
     case 11: nRes = 30; break;
     case 12: nRes = 31; break;
    }
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
   }

   function valDia(oTxt){
    var bOk = false;
    var nDia = parseInt(oTxt.value.substr(0, 2), 10);
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt)));
    return bOk;
   }

   function valMes(oTxt){
    var bOk = false;
    var nMes = parseInt(oTxt.value.substr(3, 2), 10);
    bOk = bOk || ((nMes >= 1) && (nMes <= 12));
    return bOk;
   }

   function valAno(oTxt){
    var bOk = true;
    var nAno = oTxt.value.substr(6);
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4));
    if (bOk){
     for (var i = 0; i < nAno.length; i++){
      bOk = bOk && esDigito(nAno.charAt(i));
     }
    }
    return bOk;
   }

   function valFecha(oTxt){
    var bOk = true;

		if (oTxt.value != ""){
		 bOk = bOk && (valAno(oTxt));
		 bOk = bOk && (valMes(oTxt));
		 bOk = bOk && (valDia(oTxt));
		 bOk = bOk && (valSep(oTxt));
		 if (!bOk){
		  alert("Fecha inv�lida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta.");
		  oTxt.value = "01/01/2005";
		  oTxt.focus();
		 }
		}

   }

  function esDigito(sChr){
    var sCod = sChr.charCodeAt(0);
    return ((sCod > 47) && (sCod < 58));
   }

//--------------------------------------------------------
//	Funci�n que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}

//Funciones de Producto
function by_venta(valor1){
	f=document.form1;	
	f.hidventa.value = valor1;
	f.operacion.value = "CHANGE";
	f.submit();
}

function by_lote(lote){
	f=document.form1;
	f.hidlote.value = lote;
	f.operacion.value = "CARGARPROD";
	f.submit();
}

function ue_catclasificacion1()
{
	f=document.form1;
	f.operacion.value="";
	pagina="../sfc/sigesp_cat_subclasificacion.php";
	popupWin(pagina,"catalogo",600,250);
}

function ue_cargarclasificacion(codcla,nomcla)
{
	f=document.form1;
	f.txtcodcla.value=codcla;
	f.txtdencla.value=nomcla;
}

function ue_cargarsubclasificacion(codcla1,nomcla1,codcla2,nomcla2)
{
	f=document.form1;
	f.txtcodcla1.value=codcla1;
	f.txtdencla1.value=nomcla1;
	f.txtcodcla.value=codcla2;
	f.txtdencla.value=nomcla2;
}

function ue_catuso()
{
		f=document.form1;
		f.operacion.value="";
		pagina="../sfc/sigesp_cat_uso.php";
		popupWin(pagina,"catalogo",600,250);
}

function ue_cargar_uso(iduso,codusomac,nomuso,descripcion,codtipouso,nomtipouso,codactividad,nomactividad)
{
	f=document.form1;
	f.txtcoduso.value=iduso;
	f.txtcodusomac.value=codusomac;
	if (descripcion=='S/D')
	{
	f.txtdenuso.value=nomuso;
	}
	else
	{
	f.txtdenuso.value=descripcion+" "+nomuso;
	}
}

function actualizar_option()
{
	f=document.form1;
	//f.operacion.value="ue_actualizar_option";
	opt = getRadioButtonSelectedValue();
	if(opt=="UC"){
		f.hidtipcos.value="UC";
	}else{
		f.hidtipcos.value="CP";
	}
	//f.action="sigesp_sim_d_articulo.php";
	//f.submit();

}

function getRadioButtonSelectedValue()
{
   for(i=0;i<document.form1.opctipcos.length;i++)
        if(document.form1.opctipcos[i].checked)
		{
		return document.form1.opctipcos[i].value;
		}
}

function ue_calprecio()
{
 f=document.form1;
 var ls_flagcos=getRadioButtonSelectedValue();


 if(ls_flagcos=="UC")
  {
    costo=parseFloat(uf_convertir_monto(f.txtcosart.value));

  }
  else
  {
    costo=parseFloat(uf_convertir_monto(f.txtcosproart.value));

  }
  ganancia=parseFloat(uf_convertir_monto(f.txtporgan.value));
  flete=parseFloat(uf_convertir_monto(f.txtcosfle.value));
  ld_precio=(costo/((100-ganancia)/100))+flete;
  ld_precio=roundNumber(ld_precio);
  f.txtpreven.value=uf_convertir(ld_precio);


}

function ue_calganancia()
{
 f=document.form1;
 var ls_flagcos=getRadioButtonSelectedValue();
 if (f.txtpreven.value=='0,00')
 {
 f.txtpreven.value='';
 }
if (f.txtpreven.value!='')
{
	precio_venta=parseFloat(uf_convertir_monto(f.txtpreven.value));
	//alert(precio_venta);
	if(ls_flagcos=="UC")
	  {
		costo=parseFloat(uf_convertir_monto(f.txtcosart.value));

	  }
	  else
	  {
		costo=parseFloat(uf_convertir_monto(f.txtcosproart.value));

	  }

	ganancia=100-((costo/precio_venta)*100);
	ganancia=roundNumber(ganancia);
	f.txtporgan.value=uf_convertir(ganancia);
	 f.txtpretot.value=f.txtpreven.value;
  }else{
  f.txtporgan.value='0,00';
  ganancia=parseFloat(uf_convertir_monto(f.txtporgan.value));
  flete=parseFloat(uf_convertir_monto(f.txtcosfle.value));
  ld_precio=(costo/((100-ganancia)/100))+flete;
  //alert (ld_precio);
  ld_precio=roundNumber(ld_precio);
  f.txtpreven.value=uf_convertir(ld_precio);


  }
}

function ue_catctasspi()
{
    f=document.form1;
	f.operacion.value="";
	pagina="../sfc/sigesp_cat_ctasspi.php";
	popupWin(pagina,"catalogo",600,250);
}

function ue_cargarctasspi(cod,deno)
{
	f=document.form1;
	f.txtspicuenta.value=cod;
    f.txtdenospi.value=deno;
}

///////////////////////////////////////////////////////////////////////////////////
function ue_quitar_tienda(ls_codart,ls_codtienda)
{
	f = document.form1;
	obj_desde = f.txtasignados;
	obj_hasta = f.txtdisponibles;
	resultado = "NO";
	// Instancia del Objeto AJAX
	quitarajax=objetoAjax();
	// Pagina donde est�n los m�todos para buscar y pintar los resultados
	quitarajax.open("POST","sigesp_sim_c_articulo_ajax.php",true);

	quitarajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	quitarajax.send("codart="+ls_codart+"&codtienda="+ls_codtienda+"&proceso=QUITAR-TIENDA");

	quitarajax.onreadystatechange=function(){
		if (quitarajax.readyState==4) {
			if(quitarajax.status==200)
			{//mostramos los datos dentro del contenedor
				resultado = quitarajax.responseText;

				if(resultado == "NO"){
					ue_pasar(obj_desde,obj_hasta)
				}else if(resultado == "SI"){
					alert("No se puede quitar el Art�culo de la tienda:\n"+ls_codtienda+", El Art�culo tiene movimientos Asociados");
				}else{
					alert("Ocurri� un Error al quitar el Art�culo de la tienda: "+ls_codtienda);
				}

			}
		}
	}

}

function ue_pasar1()
{
	f = document.form1;
	if(f.txtasignados.length > 1){
		codart = f.txtcodart.value;
		tienda = f.txtasignados.options[f.txtasignados.selectedIndex].value;
		ue_quitar_tienda(codart,tienda)
	}else{
		alert("No puede quitarse la Unidad Operativa de Suministro, El Producto debe estar asignado al menos a (1) Unidad Operativa de Suministro")
	}

}

///////////////////////////////////////////////////////////////////////////////////
function ue_pasar(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;

	for(i=0;i<totdes;i++)
	{
		if(obj_desde.options[i].selected)
		{
			asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
			asignados=obj_hasta.length;
			if (asignados< 1)
			{
				obj_hasta.options[asignados] = asignar;
			}
			else
			{
				obj_hasta.options[tothas] = asignar;
			}
			tothas=asignados + 1;

		}

	}

	ue_borrar_listaseleccionado(obj_desde);

}

function ue_pasartodos(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
		asignados=obj_hasta.length;
		if (asignados< 1)
		{
			obj_hasta.options[asignados] = asignar;
		}
		else
		{
			obj_hasta.options[tothas] = asignar;
		}
		tothas=asignados + 1;

	}

	ue_borrar_listacompleta(obj_desde);
}

function ue_borrar_listacompleta(obj)
{
	var  largo= obj.length;
	for (i=largo-1;i>=0;i--)
	{
		obj.options[i] = null;
	}
}

function ue_borrar_listaseleccionado(obj)
{
	var largo= obj.length;
	var x;
	var count=0;
	arrSelected = new Array();
	for(i=0;i<largo;i++) // se coloca en el arreglo los campos seleccionados
	{
		if(obj.options[i].selected)
		{
			arrSelected[count]=obj.options[i].value;
		}
		count++;
	}
	for(i=0;i<largo;i++) // se colocan en null los que est�n en el arreglo
	{
		for(x=0;x<arrSelected.length;x++)
		{
			if (obj.options[i].value==arrSelected[x])
			{
				obj.options[i]=null;
			}
		}
		largo = obj.length;
	}
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>

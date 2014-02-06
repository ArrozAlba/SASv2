<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_sigesp_int_spg.php");
require_once("../shared/class_folder/class_sigesp_int_spi.php");
/*require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");*/
//-----------------------------------------------------------------------------------------------------------------------------------
class sigesp_spg_class_progrep
{
   function sigesp_spg_class_progrep()
   {
		$this->io_function = new class_funciones() ;
		$this->io_include=new sigesp_include();
		$this->io_connect=$this->io_include->uf_conectar();
		$this->io_sql=new class_sql($this->io_connect);		
		$this->obj=new class_datastore();
		$this->io_msg=new class_mensajes();
		$this->int_spg=new class_sigesp_int_spg();
		$this->obj=new class_datastore();
		$this->io_seguridad= new sigesp_c_seguridad();
		$this->io_fecha=new class_fecha();
        $this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
        $this->li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		/*$this->io_rcbsf= new sigesp_c_reconvertir_monedabsf();*/
		$this->li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		$this->li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		$this->li_redconmon=$_SESSION["la_empresa"]["redconmon"];
   }
//----------------------------------------------------------------------------------------------------------------------------------
  function uf_llenar_combo_estpro1(&$rs_data)
  {
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_llenar_combo_estpro1 
	//	     Arguments:  $rs_data --> resulset  (referencia)
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que retorna un resulset con el codigo y la denominacion de la estpro1
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT codestpro1,denestpro1 FROM spg_ep1 WHERE codemp='".$this->ls_codemp."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_llenar_combo_estpro1 ERROR->".$this->io_function->uf_convertirmsg
		($this->io_sql->message));
	}
	else
	{
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $lb_valido=true;
	   }
	   else
	   {
		  $lb_valido=false;
	   }
	}
    return $lb_valido; 
  }//uf_llenar_combo_estpro1
//-----------------------------------------------------------------------------------------------------------------------------------
  function uf_llenar_combo_estpro2($as_codestpro1,&$rs_data)
  {
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_llenar_combo_estpro2 
	//	     Arguments:  $rs_data --> resulset  (referencia)
	//                   as_codestpro1 --> codigo de la estructura programatica 1 
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que retorna un resulset con el codigo y la denominacion de la estpro2
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT codestpro2,denestpro2 FROM spg_ep2 WHERE codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' ";  
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_llenar_combo_estpro2 ERROR->".$this->io_function->uf_convertirmsg
		($this->io_sql->message));
	}
	else
	{
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $lb_valido=true;
	   }
	   else
	   {
		  $lb_valido=false;
	   }
	}
    return $lb_valido; 
  }//fin uf_llenar_combo_estpro2
//----------------------------------------------------------------------------------------------------------------------------------
   function uf_llenar_combo_estpro3($as_codestpro1,$as_codestpro2,&$rs_data)
  {
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_llenar_combo_estpro3 
	//	     Arguments:  $rs_data --> resulset  (referencia)
	//                   as_codestpro1 --> codigo de la estructura programatica 1 
	//                   as_codestpro2 --> codigo de la estructura programatica 2
	//	       Returns:	 $lb_valido true si es correcto la funcion o false en caso contrario
	//	   Description:  Método que retorna un resulset con el codigo y la denominacion de la estpro3
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql=" SELECT codestpro3,denestpro3 ".
	        " FROM  spg_ep3 ".
			" WHERE codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' ";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_llenar_combo_estpro3 ERROR->".$this->io_function->uf_convertirmsg
		($this->io_sql->message));
	}
	else
	{
	   if ($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $lb_valido=true;
	   }
	   else
	   {
		  $lb_valido=false;
	   }
	}
    return $lb_valido; 
  }//fin uf_llenar_combo_estpro3
//----------------------------------------------------------------------------------------------------------------------------------
function  uf_prog_report_delete($as_codrep)
{    
	//////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_prog_report_delete
	//	        Access: public
	//       Argumente: as_codrep   // codigo del reporte
	//	   Description: Método que borrar la información contenida a la tabla 
	//                  plantila reporte del reporte especificado
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////
	$ls_sql=" DELETE FROM spg_plantillareporte WHERE codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' ";
	$li_rows_afecta=$this->io_sql->execute($ls_sql);
	if ($li_rows_afecta===false)
	{
	   $lb_valido=false;
	   $this->msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_delete ERROR->".$this->io_function->uf_convertirmsg
	   ($this->io_sql->message));
	}
	else 
	{
	  $lb_valido=true;
	}
    return $lb_valido;
}//fin  uf_prog_report_delete
//----------------------------------------------------------------------------------------------------------------------------------
function uf_prog_report_load_original($as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,
                                      $as_codestpro5,$aa_seguridad,$as_estcla)
{
	///////////////////////////////////////////////////////////////////////////////////////////
	//	     Function:  uf_prog_report_load_original
	//	       Access:  public
	//	  Description:  Método que carga la información nuevamente en la
	//					tabla spg_plantillacuentareporte. Esta información es la copia exacta
	// 					de las cuentas definidas en la tabla spg_cuentas en la tabla mencionada 
	//                  anteriormente
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido =true;
	$lb_ok=false;
	$ls_codestpro4=$as_codestpro4;
	$ls_codestpro5=$as_codestpro5;
	/*if($this->li_estmodest==2)
	{
		$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
		$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
		$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
		$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
	}*/
	if ($this->uf_prog_report_delete($as_codrep))
	{
		if ($as_codrep=='00005')
		{  
		    $ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, 	".
                    "             spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, ".
					"             pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio, 	".
                    "             agosto, septiembre, octubre,noviembre, diciembre, nivel, referencia,modrep)					".
                    " SELECT     '00005' as codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,escla,spg_cuenta,".
                    "             sc_cuenta,denominacion, status, asignado, precomprometido,  comprometido,  causado, 			".
                    "             pagado, aumento, disminucion, 1 as distribuir, enero,  febrero, 								".
					"             marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, 								".
                    "             noviembre, diciembre, nivel, referencia,'0' as modrep 										".
                    " FROM (SELECT spg_cuenta, codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,sc_cuenta,  ".
					"              denominacion, status, asignado,  precomprometido,comprometido, causado, pagado,  aumento, 	".
                    "              enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, 				".
                    "              disminucion, distribuir,noviembre, diciembre, nivel, referencia 								".
                    "        FROM  spg_cuentas 																					".
                    "        WHERE (codemp='".$this->ls_codemp."' AND spg_cuenta LIKE '401000000%' OR spg_cuenta LIKE '402000000%' OR  ".
					"               spg_cuenta LIKE '403000000%' OR   spg_cuenta LIKE '408000000%' OR spg_cuenta LIKE '410000000%' OR  ".
					"               spg_cuenta LIKE '407000000%' OR   spg_cuenta LIKE '407010100%' OR spg_cuenta LIKE '407010300%' OR  ".
					"               spg_cuenta LIKE '407030000%' OR   spg_cuenta LIKE '407030100%' OR spg_cuenta LIKE '407030300%' OR  ".
					"               spg_cuenta LIKE '407020000%' OR   spg_cuenta LIKE '408080000%' OR spg_cuenta LIKE '404000000%' OR  ".
					"               spg_cuenta LIKE '405000000%' OR   spg_cuenta LIKE '407010201%' OR spg_cuenta LIKE '407010202%' OR  ".
					"               spg_cuenta LIKE '407010401%' OR   spg_cuenta LIKE '407010402%' OR spg_cuenta LIKE '407010403%' OR  ".
					"               spg_cuenta LIKE '407010404%' OR   spg_cuenta LIKE '407010405%' OR spg_cuenta LIKE '407010406%' OR  ".
					"               spg_cuenta LIKE '407010407%' OR   spg_cuenta LIKE '407010408%' OR spg_cuenta LIKE '407010409%' )) as curd ".
                    " ORDER BY spg_cuenta ";
		}
		elseif($as_codrep=='0714')
		{
			$ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,    ".
                    "             spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado,  ".
					"             pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio,    ".
                    "             agosto, septiembre, octubre,noviembre, diciembre, nivel, referencia,modrep)                    ".
                    " SELECT     '0714' as codrep,codemp,'".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."',         ".
					"            '".$ls_codestpro4."','".$ls_codestpro5."','".$as_estcla."',spg_cuenta, sc_cuenta,               ".
                    "            denominacion, status, asignado, precomprometido, comprometido, causado,                         ".
                    "            pagado, aumento, disminucion, 1 as distribuir, enero, febrero,                                  ".
					"            marzo, abril, mayo, junio, julio, agosto, septiembre,                                           ".
                    "            octubre, noviembre, diciembre, nivel, referencia,'0' as modrep                                  ".
                    " FROM (SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido,               ".
					"              comprometido, causado, pagado, aumento, disminucion, 1 as distribuir,                         ".
                    "              enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre,                         ".
                    "              octubre, noviembre, diciembre, nivel, referencia                                              ".
                    "        FROM  spg_cuentas 																					 ".
                    "        WHERE (codemp='".$this->ls_codemp."' AND spg_cuenta LIKE '401000000%' OR spg_cuenta LIKE '402000000%' OR ".
					"               spg_cuenta LIKE '403000000%' OR   spg_cuenta LIKE '407000000%' OR spg_cuenta LIKE '408000000%' )  AND ".
					"               codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
					"               codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND ".
                    "               codestpro5='".$ls_codestpro5."' AND estcla='".$as_estcla."') as curd    ".
                    " ORDER BY spg_cuenta ";
		}
		elseif($as_codrep=='0514')
		{  
		    $ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,".
                    "             spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado,  ".
					"             pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio,    ".
                    "             agosto, septiembre, octubre,noviembre, diciembre, nivel, referencia,modrep)                    ".
                    " SELECT     '0514' as codrep,codemp,'".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."',         ".
					"            '".$ls_codestpro4."','".$ls_codestpro5."','".$as_estcla."',spg_cuenta, sc_cuenta,               ".
                    "            denominacion, status, asignado, precomprometido, comprometido, causado,                         ".
                    "            pagado, aumento, disminucion, 1 as distribuir, enero, febrero,                                  ".
					"            marzo, abril, mayo, junio, julio, agosto, septiembre,                                           ".
                    "            octubre, noviembre, diciembre, nivel, referencia,'0' as modrep                                  ".
                    " FROM (SELECT  spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido,              ".
					"               comprometido, causado, pagado, aumento, disminucion, 0 as distribuir,                        ".
                    "               enero, febrero, marzo, abril, mayo, junio, julio,                                            ".
                    "               agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia                         ".
                    "        FROM   spg_cuentas                                                                                  ".
                    "        WHERE  codemp='".$this->ls_codemp."' AND codestpro1='".$as_codestpro1."' AND                        ".
					"               codestpro2='".$as_codestpro2."' AND  codestpro3='".$as_codestpro3."' AND                     ".
					"               codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND                      ".
					"               estcla='".$as_estcla."' AND 																 ".
					"               (spg_cuenta LIKE '401050100%' OR  spg_cuenta LIKE '401050300%' OR                            ".
					"               spg_cuenta LIKE '401072200%' OR   spg_cuenta LIKE '403010000%' OR spg_cuenta LIKE '403020200%' OR  ".
					"               spg_cuenta LIKE '403020600%' OR   spg_cuenta LIKE '403040100%' OR spg_cuenta LIKE '403040300%' OR  ".
					"		        spg_cuenta LIKE '403040400%' OR   spg_cuenta LIKE '403040500%' OR spg_cuenta LIKE '403040600%' OR  ".
					"	            spg_cuenta LIKE '403040700%' OR   spg_cuenta LIKE '403060000%' OR spg_cuenta LIKE '403070100%' OR  ".
					"		        spg_cuenta LIKE '403070200%' OR   spg_cuenta LIKE '403070300%' OR spg_cuenta LIKE '403080100%' OR  ".
					"         		spg_cuenta LIKE '407010101%' OR   spg_cuenta LIKE '407010102%' OR spg_cuenta LIKE '407010103%' OR  ".
					"         		spg_cuenta LIKE '407010170%' OR   spg_cuenta LIKE '407010180%' OR spg_cuenta LIKE '407020000%' OR  ".
					"         		spg_cuenta LIKE '407020100%' OR   spg_cuenta LIKE '407030300%' OR spg_cuenta LIKE '401080000%' OR  ".
					"        		spg_cuenta LIKE '407010109%') ) as curd ".
                    " ORDER BY spg_cuenta ";
		}
		elseif($as_codrep=='0406')
		{  
		    $ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,   ".
                    "             estcla,spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,  ".
					"             causado, pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, ".
                    "             julio,agosto, septiembre, octubre,noviembre, diciembre, nivel, referencia,modrep)             ".
                    " SELECT     '0406' as codrep,codemp,'".$as_codestpro1."','".$as_codestpro2."','".$as_codestpro3."',        ".
					"            '".$ls_codestpro4."','".$ls_codestpro5."','".$as_estcla."',spg_cuenta, sc_cuenta,              ".
                    "            denominacion, status, asignado, precomprometido, comprometido, causado,                        ".
                    "            pagado, aumento, disminucion, 1 as distribuir, enero, febrero,                                 ".
					"            marzo, abril, mayo, junio, julio, agosto, septiembre,                                          ".
                    "            octubre, noviembre, diciembre, nivel, referencia,'0' as modrep                                 ".
                    " FROM (SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido,              ".
					"              comprometido, causado, pagado, aumento, disminucion, 1 as distribuir,                        ".
                    "              enero, febrero, marzo, abril, mayo, junio, julio,                                            ".
                    "              agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia                         ".
                    "        FROM  spg_cuentas 																					".
                    "        WHERE (codemp='".$this->ls_codemp."' AND spg_cuenta LIKE '401000000%' OR							". 
					"               spg_cuenta LIKE '402000000%' OR  spg_cuenta LIKE '403000000%' OR  							".
					"               spg_cuenta LIKE '408000000%' OR  spg_cuenta LIKE '408010100%' OR  							".
					"               spg_cuenta LIKE '408010200%' OR  spg_cuenta LIKE '408020000%' OR  							".
					"               spg_cuenta LIKE '408060000%' OR  spg_cuenta LIKE '408070000%' OR  							".
					"               spg_cuenta LIKE '407000000%' OR spg_cuenta LIKE '407010100%' OR   							".
					"	            spg_cuenta LIKE '407010300%' OR   spg_cuenta LIKE '408080000%' ) AND 						".
					"               codestpro1='".$as_codestpro1."' AND  codestpro2='".$as_codestpro2."' AND  					".
					"               codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND 					".
					"               codestpro5='".$ls_codestpro5."' AND estcla='".$as_estcla."') as curd 						".
                    " ORDER BY spg_cuenta ";
		}
		elseif ($as_codrep=='0506')
		{  
		    $ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,   ".
                    "             estcla,spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,  ".
					"             causado,pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio,  ".
                    "             julio,agosto, septiembre, octubre,noviembre, diciembre, nivel, referencia,modrep)             ".
                    " SELECT     '0506' as codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,         ".
                    "             spg_cuenta,sc_cuenta,denominacion, status, asignado, precomprometido,  comprometido,          ".
                    "             causado,pagado, aumento, disminucion, 1 as distribuir, enero,  febrero,                       ".
					"             marzo, abril, mayo, junio, julio, agosto, septiembre, octubre,                                ".
                    "             noviembre, diciembre, nivel, referencia,'0' as modrep                                         ".
                    " FROM (SELECT spg_cuenta, max(codemp) as codemp, '0000000000000000000' as codestpro1,                      ".
					"              '000000' as codestpro2, '000' as codestpro3, '00' as codestpro4,                             ".
					"              '00' as codestpro5, 'P' as estcla, max(sc_cuenta) as sc_cuenta,                              ".
					"              max(denominacion) as denominacion, max(status) as status, sum(asignado) as asignado,  		".
					"              sum(precomprometido) as precomprometido, sum(comprometido) as comprometido,          		".
					"              sum(causado) as causado, sum(pagado) as pagado, sum(aumento) as aumento, sum(enero) as enero,".
                    "              sum(febrero) as febrero, sum(marzo) as marzo, sum(abril) as abril, sum(mayo) as mayo,        ".
                    "              sum(junio) as junio, sum(julio) as julio, sum(agosto) as agosto,  							".
					"              sum(septiembre) as septiembre, sum(octubre) as octubre, sum(disminucion) as disminucion,     ".
					"              max(distribuir) as distribuir, sum(noviembre) as noviembre, sum(diciembre) as diciembre,     ".
					"              max(nivel) as nivel, max(referencia) as referencia                                           ".
                    "        FROM  spg_cuentas																					".
                    "        WHERE (codemp='".$this->ls_codemp."' AND spg_cuenta LIKE '40101%' OR spg_cuenta LIKE '4010101%' OR ".
					"               spg_cuenta LIKE '4010104%' OR   spg_cuenta LIKE '4010109%' OR spg_cuenta LIKE '4010118%' OR ".
					"               spg_cuenta LIKE '4010110%' OR   spg_cuenta LIKE '4010112%' OR spg_cuenta LIKE '4010199%' OR ".
					"               spg_cuenta LIKE '40102%'   OR   spg_cuenta LIKE '4010201%' OR spg_cuenta LIKE '4010202%' OR ".
					"               spg_cuenta LIKE '4010203%' OR   spg_cuenta LIKE '40103%'   OR spg_cuenta LIKE '40104%'   OR ".
					"               spg_cuenta LIKE '4010401%' OR   spg_cuenta LIKE '4010503%' OR spg_cuenta LIKE '4010407%' OR ".
					"               spg_cuenta LIKE '4010409%' OR   spg_cuenta LIKE '4010408%' OR spg_cuenta LIKE '4010406%' OR ".
					"               spg_cuenta LIKE '40105%'   OR   spg_cuenta LIKE '40106%'   OR spg_cuenta LIKE '4010604%' OR ".
					"               spg_cuenta LIKE '4010608%' OR   spg_cuenta LIKE '4010605%' OR spg_cuenta LIKE '4010613%' OR ".
					"               spg_cuenta LIKE '4010601%' OR   spg_cuenta LIKE '4010610%' OR spg_cuenta LIKE '4010603%' OR ".
					"               spg_cuenta LIKE '4010619%' OR   spg_cuenta LIKE '40107%'   OR spg_cuenta LIKE '40108%'   OR ".
					"               spg_cuenta LIKE '40109%'   OR   spg_cuenta LIKE '40196%'   OR spg_cuenta LIKE '40197%' )    ".
                    " GROUP BY spg_cuenta                                                                                       ".
					" ORDER BY spg_cuenta                                                                                       ".
					"                     )as curd	    																		";
		}
		else
		{
			$ls_sql = "  INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla, ".
					  "  spg_cuenta, sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, pagado, aumento, ".
					  "  disminucion, distribuir, enero, febrero, marzo, abril, mayo, junio, julio, agosto, septiembre, octubre,".
					  "  noviembre, diciembre, nivel, referencia,modrep)". 
					  "  SELECT '".$as_codrep."' as codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla,  ".
					  "          spg_cuenta,sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,". 
					  "  causado, pagado, aumento, disminucion, 1 as distribuir, enero, febrero,". 
					  "  marzo, abril, mayo, junio, julio, agosto, septiembre, octubre,".
					  "  noviembre, diciembre, nivel, referencia,'0' as modrep ".
					  "  FROM spg_cuentas WHERE codemp='".trim($this->ls_codemp)."'";
		}
		$rs_load=$this->io_sql->execute($ls_sql);
		if ($rs_load===false)
		{
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_load_original(INSERT) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		   //print $this->io_sql->message;
		}
		else 
		{
		   $lb_valido=true;
		}
		if ($lb_valido)
		{
             $lb_valido=$this->uf_convertir_spgplantillareporte($aa_seguridad);	
			 if($lb_valido)		
			 {
				//////////////////////////////////         SEGURIDAD               //////////////////////////////////
				   $ls_evento="INSERT";
				   $ls_descripcion =" Inserto el reporte ".$as_codrep."  ";
				   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
				 $this->io_sql->commit();
				 $lb_valido=true;
			}
			else
			{
				 $this->io_sql->rollback();
				 $lb_valido=false;
			}	
				 
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
		}	
    }
	if($as_codrep=="00004")
	{
	   $lb_ok=true;
	}
	if($lb_ok)
	{	
		// se procede a realizar un filtro a la data generada //
		if($as_codrep=="00004")
		{
			$ls_sql = " DELETE FROM spg_plantillareporte ".
					  " WHERE codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND ".
					  " NOT (spg_cuenta = '401050100' OR  ".
					  " spg_cuenta = '401050300%' OR spg_cuenta = '403010000%' OR  ".
					  " spg_cuenta = '403020700%' OR spg_cuenta = '403020200%' OR  ".
					  " spg_cuenta = '403030100%' OR spg_cuenta = '403030300%' OR  ".
					  " spg_cuenta = '403030400%' OR spg_cuenta = '403100400%' OR  ".
					  " spg_cuenta = '403030500%' OR spg_cuenta = '403040000%' OR  ".
					  " spg_cuenta = '403030600%' OR spg_cuenta = '403050100%' OR  ".
					  " spg_cuenta = '403050200%' OR spg_cuenta = '403050300%' OR  ".
					  " spg_cuenta = '403060100%' OR spg_cuenta = '407010102%' OR  ".
					  " spg_cuenta = '407010101%' OR spg_cuenta = '401071600%' OR  ".
					  " spg_cuenta = '407010103%' OR spg_cuenta = '407010106%' OR  ".
					  " spg_cuenta = '407010110%' OR spg_cuenta = '407010120%' OR  ".
					  " spg_cuenta = '407010198%' OR spg_cuenta = '407030000%' OR  ".
					  " spg_cuenta = '407020200%' OR spg_cuenta = '407020100%' OR  ".
					  " spg_cuenta = '401080000%' OR spg_cuenta = '407010301%' )  ";
		}	
		$li_rows_afecta=$this->io_sql->execute($ls_sql);
		if($li_rows_afecta===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_load_original(DELETE) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido=true;
		}
		if ($lb_valido)
		{
             $lb_valido=$this->uf_convertir_spgplantillareporte($aa_seguridad);	
			 if($lb_valido)		
			 {
				 //////////////////////////////////         SEGURIDAD               ////////////////////////////////////	
				   $ls_evento="DELETE";
				   $ls_descripcion =" Eliminar el reporte ".$as_codrep."  ";
				   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				 /////////////////////////////////         SEGURIDAD               ///////////////////////////////////
				 $this->io_sql->commit();
				 $lb_valido=true;
			}
			else
			{
				 $this->io_sql->rollback();
				 $lb_valido=false;
			}	
		}
		else
		{
			 $this->io_sql->rollback();
			 $lb_valido=false;
		}	
	}	
    return $lb_valido;
}//fin 
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_prog_report_load_data_0714($as_codrep,$aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////
	//	     Function:  uf_prog_report_load_data_0714
	//	       Access:  public
	//	  Description:  Método que carga la información nuevamente en la
	//					tabla spg_plantillacuentareporte. Esta información es la copia exacta
	// 					de las cuentas definidas en la tabla spg_cuentas en la tabla mencionada anteriormente
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación : 05/09/2006                       Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido = true;
	$la_cuenta[10]=array();
	$la_cuenta[1]='401010000';
	$la_cuenta[2]='401020000';
	$la_cuenta[3]='402010000';
	$la_cuenta[4]='402020000';
	$la_cuenta[5]='403010000';
	$la_cuenta[6]='403020000';
	$la_cuenta[7]='407010000';
	$la_cuenta[8]='407020000';
	$la_cuenta[9]='408010000';
	$la_cuenta[10]='408020000';
	
	$la_referencia[10]=array();
	$la_referencia[1]='401000000';
	$la_referencia[2]='401000000';
	$la_referencia[3]='402000000';
	$la_referencia[4]='402000000';
	$la_referencia[5]='403000000';
	$la_referencia[6]='403000000';
	$la_referencia[7]='407000000';
	$la_referencia[8]='407000000';
	$la_referencia[9]='408000000';
	$la_referencia[10]='408000000';
	for($li=1;$li<=10;$li++)
	{  
	  $ls_cuenta_arreglo=$la_cuenta[$li];
	  $ls_referencia_arreglo=$la_referencia[$li];
	  if(($lb_valido)&&(($li==1)||($li==3)||($li==5)||($li==7)||($li==9)))
	  {
		$ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, spg_cuenta, ".
				"                                   sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, ".
				"                                   pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo,  ".
				"                        		    junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia, ".
				"                                   modrep) ".
				" VALUES  ('0714','0001','00000000000000000000','000000','000','00','00','".$ls_cuenta_arreglo."','".$ls_cuenta_arreglo."', ".
				"          'Imputación en Activo Fijo','C', 0 , 0 , 0 , 0 , 0 , 0 , 0 , 1 , 0, 0 ,0 , 0 , 0 , 0 , 0 , 0 , 0 , 0 , ".
				"           0 , 0 , '0', '".$ls_referencia_arreglo."','0') ";
		$rs_load=$this->io_sql->execute($ls_sql);
		if ($rs_load===false)
		{
		   $lb_valido=false;
		   $this->io_msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_load_data_0714(INSERT) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$lb_valido = true;
		}
	 }//if	
     if(($lb_valido)&&(($li==2)||($li==4)||($li==6)||($li==8)||($li==10)))
	 {
			$ls_sql=" INSERT INTO spg_plantillareporte (codrep,codemp,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, spg_cuenta, ".
					"                                   sc_cuenta, denominacion, status, asignado, precomprometido, comprometido,causado, ".
					"                                   pagado, aumento, disminucion, distribuir, enero, febrero, marzo, abril, mayo,  ".
					"                        		    junio, julio, agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia, ".
					"                                   modrep) ".
					" VALUES  ('0714','0001','00000000000000000000','000000','000','00','00','".$ls_cuenta_arreglo."','".$ls_cuenta_arreglo."', ".
					"          'Imputación en Activo Intangible','C', 0 , 0 , 0 , 0 , 0 , 0 , 0 , 1 , 0, 0 ,0 , 0 , 0 , 0 , 0 , 0 , 0 , 0 , ".
					"           0 , 0 , '0', '".$ls_referencia_arreglo."','0') ";
			$rs_load=$this->io_sql->execute($ls_sql);
			if ($rs_load===false)
			{
			   $lb_valido=false;
			   $this->io_msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_load_data_0714(INSERT) ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
			}	
			else
			{
				$lb_valido = true;
			}
	  }//if
	}//for	
	if ($lb_valido)
	{
		//////////////////////////////////         SEGURIDAD               //////////////////////////////////
		   $ls_evento="INSERT";
		   $ls_descripcion =" Inserto el reporte con las imputaciones".$as_codrep."  ";
		   $lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
										$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
										$aa_seguridad["ventanas"],$ls_descripcion);
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		 $this->io_sql->commit();
		 $lb_valido=true;
	}
	else
	{
		 $this->io_sql->rollback();
		 $lb_valido=false;
	}	
    return $lb_valido;
}//fin uf_prog_report_load_data_0714
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_prog_report_load_data($as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_modrep)
{
	//////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_prog_report_load_data
	//	        Access: public
	//	   Description: Método que carga la información de la 
	//                  programacion de reportes por programatica 
	//                  y lo registra en un datastore.
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codestpro4=$as_codestpro4;
	$ls_codestpro5=$as_codestpro5;
	
	if($this->li_estmodest==2)
	{
		$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
		$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
		$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
		$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
	}
	
	$ls_modrep=0;
	if (($as_codrep=='00005')||($as_codrep=='0714'))// Flujo de Caja
	{
		$ls_sql = " SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  "        spg_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,".
				  "        mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,modrep ".
				  " FROM   spg_plantillareporte ".
				  " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND (modrep='".$as_modrep."' OR modrep='".$ls_modrep."') ".
				  " ORDER BY spg_cuenta ";
	}
	else
	{
		$ls_sql = " SELECT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,".
				  "        spg_cuenta,denominacion,status,asignado,distribuir,enero,febrero,marzo,abril,".
				  "        mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,nivel,referencia,modrep ".
				  " FROM   spg_plantillareporte".
				  " WHERE  codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND ".
				  "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND ".
				  "        codestpro5='".$ls_codestpro5."' AND (modrep='".$as_modrep."' OR modrep='".$ls_modrep."') ".
				  " ORDER BY spg_cuenta ";
	}
	$rs_progrep=$this->io_sql->select($ls_sql);
	if($rs_progrep===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_prog_report_load_data ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
        //print $this->io_sql->message;
	}
	else
	{
		$lb_valido=true;
	}
    if($lb_valido){return $rs_progrep; }
}///fin uf_prog_report_load_data
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_cargar_reporte($as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
                           $as_modrep,$as_estcla)
{
	//////////////////////////////////////////////////////////////////////////////
	//	      Function: uf_cargar_reporte
	//	   Description: Método que carga la información de la 
	//                  programacion de reportes por programatica 
	//                  y lo registra en un datastore.
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :  02/11/2006            Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_codestpro4=$as_codestpro4;
	$ls_codestpro5=$as_codestpro5;
	
	/*if($this->li_estmodest==2)
	{
		$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
		$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
		$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
		$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
	}*/
	
	$ls_modrep=0;
	if ($as_codrep=='00005')// Flujo de Caja
	{
		$ls_sql = " SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido, ".
				  "        comprometido, causado, pagado, aumento, disminucion, distribuir, ".
                  "        enero, febrero, marzo, abril, mayo, junio, julio, ".
                  "        agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia ".
                  "  FROM  spg_plantillareporte ".
                  "  WHERE codemp='".$this->ls_codemp."' AND (spg_cuenta LIKE '401000000%' OR spg_cuenta LIKE '402000000%' OR  ".
				  "        spg_cuenta LIKE '403000000%' OR   spg_cuenta LIKE '408000000%' OR spg_cuenta LIKE '410000000%' OR  ".
				  "        spg_cuenta LIKE '407000000%' OR   spg_cuenta LIKE '407010100%' OR spg_cuenta LIKE '407010300%' OR  ".
				  "        spg_cuenta LIKE '407030000%' OR   spg_cuenta LIKE '407030100%' OR spg_cuenta LIKE '407030300%' OR  ".
				  "        spg_cuenta LIKE '407020000%' OR   spg_cuenta LIKE '408080000%' OR spg_cuenta LIKE '404000000%' OR  ".
				  "        spg_cuenta LIKE '405000000%' OR   spg_cuenta LIKE '407010201%' OR spg_cuenta LIKE '407010202%' OR  ".
				  "        spg_cuenta LIKE '407010401%' OR   spg_cuenta LIKE '407010402%' OR spg_cuenta LIKE '407010403%' OR  ".
				  "        spg_cuenta LIKE '407010404%' OR   spg_cuenta LIKE '407010405%' OR spg_cuenta LIKE '407010406%' OR  ".
				  "        spg_cuenta LIKE '407010407%' OR   spg_cuenta LIKE '407010408%' OR spg_cuenta LIKE '407010409%') AND  ".
				  "        codrep='".$as_codrep."' ".
                  " ORDER BY spg_cuenta ";
	}
	elseif($as_codrep=="0714")
	{
	   $ls_sql=" SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido, ".
			   "        comprometido, causado, pagado, aumento, disminucion, distribuir, ".
               "        enero, febrero, marzo, abril, mayo, junio, julio, ".
               "        agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia ".
               " FROM  spg_plantillareporte ".
               " WHERE codemp='".$this->ls_codemp."' AND (spg_cuenta LIKE '401000000%' OR spg_cuenta LIKE '402000000%' OR  ".
			   "       spg_cuenta LIKE '403000000%' OR   spg_cuenta LIKE '407000000%' OR spg_cuenta LIKE '408000000%' )  AND  ".
			   "       codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
               "       codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND estcla='".$as_estcla."' AND ".
			   "       codrep='".$as_codrep."' ".
               " ORDER BY spg_cuenta";
	}
	elseif($as_codrep=="0514")
	{
	  $ls_sql=" SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido, ".
			  "        comprometido, causado, pagado, aumento, disminucion, distribuir, ".
              "        enero, febrero, marzo, abril, mayo, junio, julio, ".
              "        agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia ".
              "  FROM  spg_plantillareporte ".
              "  WHERE codemp='".$this->ls_codemp."' AND (spg_cuenta LIKE '401050100%' OR spg_cuenta LIKE '401050300%' OR ".
			  "        spg_cuenta LIKE '401072200%' OR   spg_cuenta LIKE '403010000%' OR spg_cuenta LIKE '403020200%' OR      ".
		      "        spg_cuenta LIKE '403020600%' OR   spg_cuenta LIKE '403040100%' OR spg_cuenta LIKE '403040300%' OR      ".
			  "		   spg_cuenta LIKE '403040400%' OR   spg_cuenta LIKE '403040500%' OR spg_cuenta LIKE '403040600%' OR      ".
			  "	       spg_cuenta LIKE '403040700%' OR   spg_cuenta LIKE '403060000%' OR spg_cuenta LIKE '403070100%' OR      ".
			  "		   spg_cuenta LIKE '403070200%' OR   spg_cuenta LIKE '403070300%' OR spg_cuenta LIKE '403080100%' OR      ".
			  "        spg_cuenta LIKE '407010101%' OR   spg_cuenta LIKE '407010102%' OR spg_cuenta LIKE '407010103%' OR      ".
			  "        spg_cuenta LIKE '407010170%' OR   spg_cuenta LIKE '407010180%' OR spg_cuenta LIKE '407020000%' OR      ".
			  "        spg_cuenta LIKE '407020100%' OR   spg_cuenta LIKE '407030300%' OR spg_cuenta LIKE '401080000%' OR      ".
			  "        spg_cuenta LIKE '407010109%') AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
              "        codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND ".
              "        estcla='".$as_estcla."'  AND codrep='".$as_codrep."' ".
			  " ORDER BY spg_cuenta";
	}
	elseif ($as_codrep=='0506')// Flujo de Caja
	{
		$ls_sql = " SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido, ".
				  "        comprometido, causado, pagado, aumento, disminucion, distribuir, ".
                  "        enero, febrero, marzo, abril, mayo, junio, julio, ".
                  "        agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia ".
                  "  FROM  spg_plantillareporte ".
                  "  WHERE codemp='".$this->ls_codemp."' AND (spg_cuenta LIKE '40101%' OR spg_cuenta LIKE '4010101%' OR ".
					"      spg_cuenta LIKE '4010104%' OR   spg_cuenta LIKE '4010109%' OR spg_cuenta LIKE '4010118%' OR  ".
					"      spg_cuenta LIKE '4010110%' OR   spg_cuenta LIKE '4010112%' OR spg_cuenta LIKE '4010199%' OR  ".
					"      spg_cuenta LIKE '40102%'   OR   spg_cuenta LIKE '4010201%' OR spg_cuenta LIKE '4010202%' OR  ".
					"      spg_cuenta LIKE '4010203%' OR   spg_cuenta LIKE '40103%'   OR spg_cuenta LIKE '40104%'   OR  ".
					"      spg_cuenta LIKE '4010401%' OR   spg_cuenta LIKE '4010503%' OR spg_cuenta LIKE '4010407%' OR  ".
					"      spg_cuenta LIKE '4010409%' OR   spg_cuenta LIKE '4010408%' OR spg_cuenta LIKE '4010406%' OR  ".
					"      spg_cuenta LIKE '40105%'   OR   spg_cuenta LIKE '40106%'   OR spg_cuenta LIKE '4010604%' OR  ".
					"      spg_cuenta LIKE '4010608%' OR   spg_cuenta LIKE '4010605%' OR spg_cuenta LIKE '4010613%' OR  ".
					"      spg_cuenta LIKE '4010601%' OR   spg_cuenta LIKE '4010610%' OR spg_cuenta LIKE '4010603%' OR  ".
					"      spg_cuenta LIKE '4010619%' OR   spg_cuenta LIKE '40107%'   OR spg_cuenta LIKE '40108%'   OR  ".
					"      spg_cuenta LIKE '40109%'   OR   spg_cuenta LIKE '40196%'   OR spg_cuenta LIKE '40197%' ) AND ".
				  "        estcla='".$as_estcla."' AND codrep='".$as_codrep."' ".
                  " ORDER BY spg_cuenta ";
	}
	else
	{
		$ls_sql = " SELECT spg_cuenta, codemp, sc_cuenta, denominacion, status, asignado, precomprometido, ".
			      "        comprometido, causado, pagado, aumento, disminucion, distribuir, ".
                  "        enero, febrero, marzo, abril, mayo, junio, julio, ".
                  "        agosto, septiembre, octubre, noviembre, diciembre, nivel, referencia ".
				  " FROM   spg_plantillareporte".
				  " WHERE  codemp='".$this->ls_codemp."' AND  codestpro1='".$as_codestpro1."' AND ".
				  "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
				  "        codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND ".
				  "        estcla='".$as_estcla."' AND codrep='".$as_codrep."' ".
				  " ORDER BY spg_cuenta ";
	}
	$rs_progrep=$this->io_sql->select($ls_sql);
	if($rs_progrep===false)
	{
		$lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_cargar_reporte ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
        //print $this->io_sql->message;
	}
	else
	{
		$lb_valido=true;
	}
    if($lb_valido){return $rs_progrep; }
}///fin uf_prog_report_load_data
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_obtener_nivel_cta($as_cuenta,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
	////////////////////////////////////////////////////////////////////////////////////////////
	//        Function:  uf_obtener_nivel_cta
	//          Acesso:  Public
	//      Argumentos:  as_sc_cuenta: String
	//    Descripción :  Busca en la tabla scg_pc_report el nivel de la cuenta que pasa por parametro
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	//////////////////////////////////////////////////////////////////////////////
	$ls_codestpro4=$as_codestpro4;
	$ls_codestpro5=$as_codestpro5;
	if($this->li_estmodest==2)
	{
		$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
		$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
		$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
		$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
	}
	$ls_sql = " SELECT nivel ".
			  " FROM   spg_plantillareporte ".
			  " WHERE  spg_cuenta = '".$as_cuenta."' AND codestpro1='".$as_codestpro1."' AND ".
			  "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
			  "        codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND ".
			  "        codemp='".$this->ls_codemp."' ";	
	$rs_pr = $this->io_sql->select($ls_sql);
	if ($rs_pr===false)
	{
		$li_nivel = 0; //no existen registros
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_select_scg_plantillacuentareporte ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	} 
	else
	{
	   if ($row=$this->io_sql->fetch_row($rs_pr))
	   {
		  $li_nivel = $row["nivel"];
	   }
	}
	return $li_nivel;
  }//uf_obt_nivel_cta 
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_cuenta_sin_ceros( $as_cuenta )
{    ///////////////////////////////////////////////////////////////////////  
	//       Function : uf_cuenta_sin_ceros
	//         Acceso : public
	//     Argumentos : as_cuenta
	//    Descripción : Elimina los ceros a la derecha de la cuenta contable
	//     Creado por : Ing. Yozelin Barragán
	// Fecha Creación :                   Fecha última Modificacion : 
	/////////////////////////////////////////////////////////////////////////
	$li_lenCta=0; $li_cero=1;
	$ls_cta_ceros=""; $ls_cad="";
	$lb_encontrado=true;
	//global $msg;
	$li_lenCta = strlen(trim($as_cuenta));
	$ls_cad = substr(trim($as_cuenta), strlen(trim($as_cuenta))-1, 1 );
	$li_cero = $ls_cad;
	
	if ($li_cero == 0)
	{
		$ls_cta_ceros = substr(trim($as_cuenta), 0 , 11);
	}
	
	do  
	{
		$ls_cad = substr(trim($ls_cta_ceros), strlen($ls_cta_ceros)-1, 1);
		$li_cero = intval($ls_cad);
		$li_cant=strlen($ls_cta_ceros)-1;
		if ($li_cero == 0 )
		{
			$ls_cta_ceros = substr(trim($ls_cta_ceros), 0 , $li_cant);
			$lb_encontrado=true;
		}
		else
		{
			$lb_encontrado = false;
		}
		
	}while ( $lb_encontrado == true ); 
	return $ls_cta_ceros;
 }//uf_cuenta_sin_ceros
//-----------------------------------------------------------------------------------------------------------------------------------
/* function uf_disable_cta_inferior($as_cta_ceros,$as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
 {
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_disable_cta_inferior 
	//	     Arguments:  $as_cta_ceros --> cuenta sin ceros
	//                   $as_spg_cuenta  --> codigo de la cuenta
	//                   $as_codrep       --> codigo del reporte
	//                   $as_codestpro1   --> codigo de la estructura programatica 1
	//                   $as_codestpro2   --> codigo de la estructura programatica 2   
	//                   $as_codestpro3   --> codigo de la estructura programatica 3   
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca las cuentas inferiores  de la cuenta  pasada por parametros
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	 	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	  	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
		$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
		//global $msg;
		$data[]="";
		$ls_codestpro4="00";
		$ls_codestpro5="00";
	 	$ls_sql = " SELECT * ".
		          " FROM spg_plantillareporte  ".
				  " WHERE spg_cuenta like '".$as_cta_ceros."%' and spg_cuenta <> '".$as_spg_cuenta."'  AND ".
		          "       codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND ".
				  "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
				  "       codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'  AND ".
				  "       codemp='".$this->ls_codemp."' ".
				  " ORDER BY  spg_cuenta " ;
		$rs_pr=$this->io_sql->select($ls_sql);
		$li_row=$this->io_sql->num_rows($rs_pr);
		if ($row=$this->io_sql->fetch_row($rs_pr))
		{
			while ($row=$this->io_sql->fetch_row($rs_pr))
			{	
				$ldc_asignado = $row["asignado"];
				$ls_spg_cuenta = $row["spg_cuenta"];
				if ($ldc_asignado!=0)
				{
					$li_rtn = 1 ;
					$this->io_msg->message("La cuenta ".$ls_spg_cuenta." tiene asignación. ");
					break;
				}
				else
				{
					$li_contador = $li_contador + 1;
				} 	
			} //cierre del while
			
			if ($li_contador + 1 == $li_row )
			{   
				$ls_sql = " SELECT * FROM spg_plantillareporte WHERE spg_cuenta like '".$as_cta_ceros."%' AND ".
				          " spg_cuenta <> '".$as_spg_cuenta."' and codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND ".
						  " codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND ".
						  " codestpro5='".$ls_codestpro5."' ".
						  " ORDER BY spg_cuenta " ;
                $rs_pr=$this->io_sql->select($ls_sql);
				$i=1;
				while($row=$this->io_sql->fetch_row($rs_pr))
				{
					$ls_spg_cuenta  =  $row["spg_cuenta"];
					$data[$i]=$ls_spg_cuenta;
					$i=$i+1;
				}// cierre del while rs_oaf.next (update)
			}// cierre del if (li_contador == li_row)
  }//cierre del if
  return $data;
} // fin de uf_disable_cta_inferior*/
//---------------------------------------------------------------------------------------------------------------------------------------------------------------
 function uf_disable_cta_inferior($as_cta_ceros,$as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,$as_modrep)
 {
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_disable_cta_inferior 
	//	     Arguments:  $as_cta_ceros --> cuenta sin ceros
	//                   $as_spg_cuenta  --> codigo de la cuenta
	//                   $as_codrep       --> codigo del reporte
	//                   $as_codestpro1   --> codigo de la estructura programatica 1
	//                   $as_codestpro2   --> codigo de la estructura programatica 2   
	//                   $as_codestpro3   --> codigo de la estructura programatica 3   
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca las cuentas inferiores  de la cuenta  pasada por parametros
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
	 	$li_row = 0; $li_contador=0; $li_distribuir=0; $li_nivel=0; $li_no_fila=0; $li_exec=0; $li_rtn=0;
	  	$ls_codemp=""; $ls_cod_report=""; $ls_sc_cuenta=""; $ls_denominacion=""; $ls_status="";
		$ls_tipo=""; $ls_cta_res=""; $ls_referencia=""; $ls_sql="";
		//global $msg;
		$data[]="";
		$ls_codestpro4=$as_codestpro4;
		$ls_codestpro5=$as_codestpro5;
		if($this->li_estmodest==2)
		{
			$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
			$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
			$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
			$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
			$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
		}
	 	$ls_sql = " SELECT * ".
		          " FROM spg_plantillareporte  ".
				  " WHERE spg_cuenta like '".$as_cta_ceros."%' and spg_cuenta <> '".$as_spg_cuenta."'  AND ".
		          "       codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND ".
				  "       codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
				  "       codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'  AND ".
				  "       codemp='".$this->ls_codemp."' AND modrep=".$as_modrep." ".
				  " ORDER BY  spg_cuenta " ;
		$rs_pr=$this->io_sql->select($ls_sql);
		$li_row=$this->io_sql->num_rows($rs_pr);
		if ($row=$this->io_sql->fetch_row($rs_pr))
		{
			while ($row=$this->io_sql->fetch_row($rs_pr))
			{	
				$ldc_asignado = $row["asignado"];
				$ls_spg_cuenta = $row["spg_cuenta"];
				if ($ldc_asignado!=0)
				{
					$li_rtn = 1 ;
					$this->io_msg->message("La cuenta ".$ls_spg_cuenta." tiene asignación. ");
					break;
				}
				else
				{
					$li_contador = $li_contador + 1;
				} 	
			} //cierre del while
			
			if ($li_contador + 1 == $li_row )
			{   
				$ls_sql = " SELECT * FROM spg_plantillareporte WHERE spg_cuenta like '".$as_cta_ceros."%' AND ".
				          " spg_cuenta <> '".$as_spg_cuenta."'  AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND ".
						  " codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND ".
						  " codestpro5='".$ls_codestpro5."'  AND codemp='".$this->ls_codemp."' ".
						  " ORDER BY spg_cuenta " ;
                $rs_pr=$this->io_sql->select($ls_sql);
				$i=1;
				while($row=$this->io_sql->fetch_row($rs_pr))
				{
					$ls_spg_cuenta  =  $row["spg_cuenta"];
					$data[$i]=$ls_spg_cuenta;
					$i=$i+1;
				}// cierre del while rs_oaf.next (update)
			}// cierre del if (li_contador == li_row)
  }//cierre del if
  return $data;
} // fin de uf_disable_cta_inferior
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_spg_guardar_programacion_reportes($as_status,$ad_asignado,$as_distribuir,$as_modrep,$ad_enero,$ad_febrero,$ad_marzo,
                                              $ad_abril,$ad_mayo,$ad_junio,$ad_julio,$ad_agosto,$ad_septiembre,$ad_octubre,
                                              $ad_noviembre,$ad_diciembre,$as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,
								              $as_codestpro3,$as_codestpro4,$as_codestpro5)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_spg_guardar_programacion_reportes 
	//	     Arguments:  $as_cta_ceros // cuenta sin ceros
	//                   $as_spg_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//	       Returns:	 retorna un true o false si se hizo correcto o no el update
	//	   Description:  Actualiza la tabla spi_plantillacuentareporte con los datos pasados por parametros 
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                               Fecha última Modificacion : 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $ls_codestpro4=$as_codestpro4;
    $ls_codestpro5=$as_codestpro5;
    if($as_codrep=="00005")
    {
	   $ls_sql = " UPDATE spg_plantillareporte  ".
	             " SET    status='".$as_status."', asignado='".$ad_asignado."', distribuir=".$as_distribuir.", modrep='".$as_modrep."', ".
				 "        enero='".$ad_enero."', febrero='".$ad_febrero."', marzo='".$ad_marzo."', abril='".$ad_abril."', mayo='".$ad_mayo."',".
				 "        junio='".$ad_junio."', julio='".$ad_julio."', agosto='".$ad_agosto."', septiembre='".$ad_septiembre."', ".
				 "        octubre='".$ad_octubre."', noviembre='".$ad_noviembre."', diciembre='".$ad_diciembre."' ".
				 " WHERE  codrep='".$as_codrep."'  AND codemp='".$this->ls_codemp."' AND spg_cuenta='".$as_spg_cuenta."' ";
   }
   else
   {
		 $ls_sql = " UPDATE spg_plantillareporte  ".
		           " SET    status='".$as_status."', asignado='".$ad_asignado."', distribuir=".$as_distribuir.", modrep='".$as_modrep."', ".
				   "        enero='".$ad_enero."', febrero='".$ad_febrero."', marzo='".$ad_marzo."', abril='".$ad_abril."', mayo='".$ad_mayo."',".
				   "        junio='".$ad_junio."', julio='".$ad_julio."', agosto='".$ad_agosto."', septiembre='".$ad_septiembre."', ".
				   "        octubre='".$ad_octubre."', noviembre='".$ad_noviembre."', diciembre='".$ad_diciembre."' ".
				   " WHERE  codrep='".$as_codrep."' AND  codemp='".$this->ls_codemp."'  AND  codestpro1='".$as_codestpro1."' AND  ".
				   "        codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
				   "        codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' AND spg_cuenta='".$as_spg_cuenta."' ";
   }  
   $li_rows=$this->io_sql->execute($ls_sql);
   if($li_rows===false)
   {
	    $lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_spg_guardar_programacion_reportes ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
  }
   else
   {
      if($li_rows>=0)
      {
         $lb_valido=true;
	  }
   }
  return $lb_valido;
}//uf_spg_guardar_programacion_reportes()
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_select_denominacion($as_spg_cuenta,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5,
                                &$as_denominacion)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//	      Function:  uf_select_denominacion 
	//	     Arguments:  $as_spg_cuenta  // codigo de la cuenta
	//                   $as_codrep       // codigo del reporte
	//                   $as_denominacion  // denominacion de la cuenta (referencia)
	//	       Returns:	 retorna un arreglo con las cuentas inferiores  
	//	   Description:  Busca la denominacion de la cuenta
	//     Creado por :  Ing. Yozelin Barragán
	// Fecha Creación :                        Fecha última Modificacion : 
	///////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
    $ls_codestpro4=$as_codestpro4;     
	$ls_codestpro5=$as_codestpro5; 
	if($this->li_estmodest==2)
	{
		$as_codestpro1=$this->io_function->uf_cerosizquierda($as_codestpro1,20);
		$as_codestpro2=$this->io_function->uf_cerosizquierda($as_codestpro2,6);
		$as_codestpro3=$this->io_function->uf_cerosizquierda($as_codestpro3,3);
		$ls_codestpro4=$this->io_function->uf_cerosizquierda($ls_codestpro4,2);
		$ls_codestpro5=$this->io_function->uf_cerosizquierda($ls_codestpro5,2);
	}
	$ls_sql = " SELECT denominacion ".
			  " FROM   spg_plantillareporte ".
			  " WHERE  spg_cuenta='".$as_spg_cuenta."' AND codemp='".$this->ls_codemp."' AND codrep='".$as_codrep."' AND ".
			  "        codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND ".
			  "        codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."' ";
    $rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{
	    $lb_valido=false;
		$this->io_msg->message("CLASE->class_progrep MÉTODO->uf_select_denominacion ERROR->".$this->io_function->uf_convertirmsg($this->io_sql->message));
	}
	else
	{
	   if($row=$this->io_sql->fetch_row($rs_data))
	   {
	      $as_denominacion=$row["denominacion"];
	   }
	   $this->io_sql->free_result($rs_data);
	}
    return  $lb_valido;
 }//uf_select_denominacion
//--------------------------------------------------------------------------------------------------------------------------------------------
function uf_spg_delete_cuenta($as_spg_cuenta,$as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3,$as_codestpro4,$as_codestpro5)
{
   $ls_codestpro4=$as_codestpro4;
   $ls_codestpro5=$as_codestpro5;
   
   if($as_codrep=="00005")
   {
      $ls_sql= " DELETE  FROM  spg_plantillareporte WHERE codrep='".$as_codrep."' AND codemp='".$as_codemp."' ".
	           " AND spg_cuenta='".$as_spg_cuenta."' ";
   }
   else
   {
      $ls_sql= " DELETE  FROM  spg_plantillareporte WHERE codrep='".$as_codrep."' AND codemp='".$as_codemp."' ".
	           " AND spg_cuenta='".$as_spg_cuenta."' AND  codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND ".
			   " codestpro3='".$as_codestpro3."'  AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'  "; 
   }
  
   $li_rows=$this->io_sql->execute($ls_sql);
   if($li_rows===false)
   {
	  $lb_valido=false;
	  print $this->is_msg_error = "Error en método uf_spg_delete_cuenta.".$this->io_sql->message;
	  
   }
   else
   {
      if($li_rows>=0)
      {
         $lb_valido=true;
	  }
   }
 
   if ($lb_valido)
   {
      $this->io_sql->begin_transaction();
      $lb_valido=$this->sig_int->uf_sql_transaction(true);
   }
return $lb_valido;
}//fin uf_spg_delete_cuenta
//-----------------------------------------------------------------------------------------------------------------------------------
function buscar_referencia($as_spg_cuenta,$as_codemp,$as_codrep,$as_codestpro1,$as_codestpro2,$as_codestpro3)
{
    $ls_sql="";
    $ls_codestpro4="00";     
	$ls_codestpro5="00";     

	$ls_sql = " SELECT referencia ".
			  " FROM spg_plantillareporte".
			  " WHERE  spg_cuenta='".$as_spg_cuenta."' AND codemp='".$as_codemp."' AND codrep='".$as_codrep."' AND codestpro1='".$as_codestpro1."' AND codestpro2='".$as_codestpro2."' AND codestpro3='".$as_codestpro3."' AND codestpro4='".$ls_codestpro4."' AND codestpro5='".$ls_codestpro5."'";
    $rs_progrep=$this->io_sql->select($ls_sql);
	if($rs_progrep===false)
	{
	   print $this->is_msg_error = "Error en método buscar_referencia.".$this->io_sql->message; 
	}
	else
	{
	   if($row=$this->io_sql->fetch_row($rs_progrep))
	   {
	      $ls_referencia=$row["referencia"];
	   }
	}
  return  $ls_referencia;
 }//fin 
//-----------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------
function uf_convertir_spgplantillareporte($aa_seguridad)
{
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_convertir_spgdtmpcmp
	//		   Access: private
	//	      Returns: lb_valido True si se ejecuto el insert ó False si hubo error en el insert
	//	  Description: Funcion que selecciona los campos de moneda de la tabla spg_plantillareporte e inserta el valor convertido
	//	   Creado Por: Ing. Néstor Falcón
	// Fecha Creación: 07/08/2007 								Fecha Última Modificación : 
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=true;
	$ls_sql="SELECT codemp, codrep, codestpro1, codestpro2, codestpro3, codestpro4, codestpro5, spg_cuenta, asignado, precomprometido, 
					comprometido, causado, pagado, aumento, disminucion, enero, febrero, marzo, abril, mayo, junio, 
					julio, agosto, septiembre, octubre, noviembre, diciembre".
			"  FROM spg_plantillareporte".
			" WHERE codemp='".$this->ls_codemp."'";
	$rs_data=$this->io_sql->select($ls_sql);
	if($rs_data===false)
	{ 
		$this->io_mensajes->message("CLASE->sigesp_rcm_c_spg MÉTODO->SELECT->uf_convertir_spgplantillareporte ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
		$lb_valido=false;
	}
	else
	{
		$la_seguridad="";
		while(($row=$this->io_sql->fetch_row($rs_data))&&($lb_valido))
		{
			$ls_codemp      = $row["codemp"]; 
			$ls_codrep      = $row["codrep"]; 
			$ls_codestpro1  = $row["codestpro1"];
			$ls_codestpro2  = $row["codestpro2"];
			$ls_codestpro3  = $row["codestpro3"];
			$ls_codestpro4  = $row["codestpro4"];
			$ls_codestpro5  = $row["codestpro5"]; 
			$ls_spgcta      = $row["spg_cuenta"];
			$ld_asignado   = $row["asignado"];
			$ld_precomprometido = $row["precomprometido"];
			$ld_comprometido = $row["comprometido"];
			$ld_causado = $row["causado"];
			$ld_pagado = $row["pagado"];
			$ld_aumento = $row["aumento"];
			$ld_disminucion = $row["disminucion"];
			$ld_enero = $row["enero"];
			$ld_febrero = $row["febrero"];
			$ld_marzo = $row["marzo"];
			$ld_abril = $row["abril"];
			$ld_mayo = $row["mayo"];
			$ld_junio = $row["junio"];
			$ld_julio = $row["julio"];
			$ld_agosto = $row["agosto"];
			$ld_septiembre = $row["septiembre"];
			$ld_octubre = $row["octubre"];
			$ld_noviembre = $row["noviembre"];
			$ld_diciembre = $row["diciembre"];
			
			/*$this->io_rcbsf->io_ds_datos->insertRow("campo","asignadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_asignado);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","precomprometidoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_precomprometido);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","comprometidoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_comprometido);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","causadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_causado);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","pagadoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_pagado);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","aumentoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_aumento);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","disminucionaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_disminucion);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","eneroaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_enero);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","febreroaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_febrero);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","marzoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_marzo);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","abrilaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_abril);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","mayoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_mayo);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","junioaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_junio);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","julioaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_julio);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","agostoaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_agosto);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","septiembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_septiembre);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","octubreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_octubre);

			$this->io_rcbsf->io_ds_datos->insertRow("campo","noviembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_noviembre);
			
			$this->io_rcbsf->io_ds_datos->insertRow("campo","diciembreaux");
			$this->io_rcbsf->io_ds_datos->insertRow("monto",$ld_diciembre);

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codemp");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codemp);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codrep");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codrep);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro1");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro1);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro2");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro2);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro3");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro3);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro4");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro4);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","codestpro5");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_codestpro5);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");
			
			$this->io_rcbsf->io_ds_filtro->insertRow("filtro","spg_cuenta");
			$this->io_rcbsf->io_ds_filtro->insertRow("valor",$ls_spgcta);
			$this->io_rcbsf->io_ds_filtro->insertRow("tipo","C");

			$lb_valido=$this->io_rcbsf->uf_reconvertir_datos("spg_plantillareporte",$this->li_candeccon,$this->li_tipconmon,
			                                                 $this->li_redconmon,$aa_seguridad);*/
		}
	}		
	return $lb_valido;
}// end function uf_convertir_spgplantillareporte
//-----------------------------------------------------------------------------------------------------------------------------

}// fin sigesp_spg_class_progrep 
?>
<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_cliente
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla cliente.
 // Fecha:       - 30/11/2006
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_producto
{
 var $io_funcion;
 var $io_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;

function sigesp_sfc_c_producto()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
    require_once("sigesp_sob_c_funciones_sob.php");
    require_once("../shared/class_folder/class_datastore.php");
    $this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->io_data=new class_datastore();
	$this->datoemp=$_SESSION["la_empresa"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	require_once("../shared/class_folder/class_mensajes.php");
	$this->io_msg=new class_mensajes();
}


function uf_select_producto($ls_codpro,$ls_codtienda)
{
    //////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_producto
	// Parameters:  - $ls_codpro( Codigo del Producto).
	// Parameters:  - $ls_codtienda( Codigo de la tienda).
	// Descripcion: - Funcion que busca registro del producto a sociado a una tienda en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////

    $lb_valido = false;
    $ls_codemp=$this->datoemp["codemp"];
	//$ls_codtie=$_SESSION["ls_codtienda"];

	$ls_cadena="SELECT codart, codtiend FROM sfc_producto
	            WHERE codemp='".$ls_codemp."' and codtiend='".$ls_codtienda."' and codart='".$ls_codpro."'";
	$rs_datauni=$this->io_sql->select($ls_cadena);

	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_producto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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

function uf_select_producto2($ls_codpro,&$DS)
{
    //////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_producto
	// Parameters:  - $ls_codpro( Codigo del Producto).
	// Descripcion: - Funcion que busca un registro en la bd.
	//////////////////////////////////////////////////////////////////////////////////////////

    $ls_codemp=$this->datoemp["codemp"];
	//$ls_codtie=$_SESSION["ls_codtienda"];

	/*$ls_cadena="SELECT * FROM sfc_producto
	            WHERE codemp='".$ls_codemp."' and codtiend='".$ls_codtie."' and codpro='".$ls_codpro."'";*/

	/*$ls_cadena=" SELECT p.*,u.id_uso,u.denuso,u.descripcion,c.dencla,s.den_sub,a.*,um.*,ci.denominacion as denspi".
				" FROM sfc_producto p,sfc_clasificacion c, sim_articulo a, spi_cuentas ci,sfc_subclasificacion s,sfc_uso u,sim_unidadmedida um ".
				" WHERE p.codemp='".$ls_codemp."' and p.codemp=a.codemp and p.codemp=ci.codemp and p.codcla=c.codcla and p.cod_sub=s.cod_sub and p.codart=a.codart  and p.spi_cuenta=ci.spi_cuenta ".
				" AND u.id_uso=p.id_uso AND u.codemp=p.codemp and um.codunimed=a.codunimed and p.codtiend='".$ls_codtie."' and p.codpro='".$ls_codpro."'";*/

	$ls_cadena=" SELECT p.codtiend,a.codcla,a.cod_sub,u.id_uso,u.codusomac,u.denuso,u.descripcion,c.dencla,s.den_sub,t.dentie ".
				" FROM sfc_producto p,sfc_clasificacion c, sim_articulo a, sfc_subclasificacion s, sfc_uso u, sfc_tienda t ".
				" WHERE p.codemp='".$ls_codemp."' AND p.codemp=a.codemp AND a.codcla=c.codcla AND a.cod_sub=s.cod_sub AND p.codart=a.codart ".
				" AND u.id_uso=a.id_uso AND u.codemp=p.codemp AND p.codtiend=t.codtiend AND p.codart = '".$ls_codpro."' ";
    //print $ls_cadena;
	$rs_datauni=$this->io_sql->select($ls_cadena);

	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_producto2 ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_datauni))
		{
			$la_prod=$this->io_sql->obtener_datos($rs_datauni);
			$DS->data=$la_prod;
			$lb_valido=true;
		}
		else
		{
			$lb_valido=false;
			//$this->io_msgc="Registro no encontrado";
		}
	}
	return $lb_valido;
}

function uf_select_productostienda($ls_tiendsd,$ls_tienhst,$ls_prodsd,$ls_prohst)
{
    //////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_select_productostienda
	// Parameters:  - $ls_tiendadsd, $ls_tiendahst (Rango de tiendas a buscar)
	// Parameters:  - $ls_prodsd, $ls_prohst (Rango de articulos a buscar)
	// Descripcion: - Funcion que busca el(los) articulo(s) en la rango de tiendas indicadas
	//////////////////////////////////////////////////////////////////////////////////////////

    $ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];
	//$la_objectconcepto='';

	$ls_cadena="SELECT p.*, a.denart, a.tipcos, t.dentie, c.dencar " .
			"FROM sfc_producto p, sim_articulo a, sfc_tienda t,sigesp_cargos c " .
			"WHERE p.codemp='".$ls_codemp."' and a.codemp='".$ls_codemp."' and (p.codart between '".$ls_prodsd."' and '".$ls_prohst."') " .
			"and (p.codtiend between '".$ls_tiendsd."' and '".$ls_tienhst."') and p.codart=a.codart and p.codtiend=t.codtiend " .
			"and p.codcar=c.codcar " .
			"order by p.codtiend Asc, p.codart Asc";
    //print $ls_cadena;
	$rs_datauni=$this->io_sql->select($ls_cadena);

	if($rs_datauni==false)
	{
		$lb_valido=false;
		$this->io_msgc="Error en uf_select_productostienda ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
	}
	else
	{
		if($row=$this->io_sql->fetch_row($rs_datauni))
		{
			$la_prod=$this->io_sql->obtener_datos($rs_datauni);
			$this->io_data->data=$la_prod;
			$totrow=$this->io_data->getRowCount("codart");
			$li_fila=0;
			//print $totrow;
			for($li_i=1;$li_i<=$totrow;$li_i++){
				$ls_codart=$this->io_data->getValue("codart",$li_i);
				$ls_codcar=$this->io_data->getValue("codcar",$li_i);
				$ls_dencar=$this->io_data->getValue("dencar",$li_i);
				$ls_moncar=number_format($this->io_data->getValue("moncar",$li_i),2,',','.');
				$ls_porgan=number_format($this->io_data->getValue("porgan",$li_i),2,',','.');
				$ls_tipcos=$this->io_data->getValue("tipcos",$li_i);
				if($ls_tipcos=='UC'){
					$ls_descosto='Ultimo Costo';
				}else{
					$ls_descosto='Costo Promedio';
				}
				$ls_preuni=number_format($this->io_data->getValue("preuni",$li_i),2,',','.');
				$ls_preven=number_format($this->io_data->getValue("preven",$li_i),2,',','.');
				$ls_preven1=number_format($this->io_data->getValue("preven1",$li_i),2,',','.');
				$ls_preven2=number_format($this->io_data->getValue("preven2",$li_i),2,',','.');
				$ls_preven3=number_format($this->io_data->getValue("preven3",$li_i),2,',','.');
				$ls_cosfle=number_format($this->io_data->getValue("cosfle",$li_i),2,',','.');
				$ls_min=number_format($this->io_data->getValue("minart",$li_i),2,',','.');
				$ls_max=number_format($this->io_data->getValue("maxart",$li_i),2,',','.');
				$ls_reoart=number_format($this->io_data->getValue("reoart",$li_i),2,',','.');
				$ls_ultcosart=number_format($this->io_data->getValue("ultcosart",$li_i),2,',','.');
				$ls_cosproart=number_format($this->io_data->getValue("cosproart",$li_i),2,',','.');
				$ls_codtiend=$this->io_data->getValue("codtiend",$li_i);
				$ls_denart=$this->io_data->getValue("denart",$li_i);
				$ls_dentie=$this->io_data->getValue("dentie",$li_i);

				$la_objectconcepto[$li_i][1] = "<a href='javascript: ue_editar(".$li_i.")'><img src='../shared/imagenes/edit.png' alt='Editar' width='20' height='20' border'0'></a>";
				$la_objectconcepto[$li_i][2] = "<input name=txtcodart".$li_i." type=text id=txtcodart".$li_i." value='".$ls_codart."' class=sin-borde size=18 style= text-align:left readonly='true'> " .
											"<input name=txtdenart".$li_i." type=text id=txtdenart".$li_i." value='".$ls_denart."' class=sin-borde size=21 style= text-align:left readonly='true'>";
				$la_objectconcepto[$li_i][3] = "<input name=txtcodtiend".$li_i." type=hidden id=txtcodtiend".$li_i." value='".$ls_codtiend."' >" .
											"<input name=txtdentiend".$li_i." type=text id=txtdentiend".$li_i." value='".$ls_dentie."' class=sin-borde size=21 style= text-align:rigth readonly='true'>";
				$la_objectconcepto[$li_i][4] = "<input name=txtcodcar".$li_i." type=hidden id=txtcodcar".$li_i." value='".$ls_codcar."' > " .
											"<input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' > " .
											"<input name=txtdencar".$li_i." type=text id=txtdencar".$li_i." value='".$ls_dencar."' class=sin-borde size=15 style= text-align:left readonly='true'>";
				$la_objectconcepto[$li_i][5] = "<input name=txtporgan".$li_i." type=text id=txtporgan".$li_i." value='".$ls_porgan."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][6] = "<input name=txttipcost".$li_i." type=hidden id=txttipcost".$li_i." value='".$ls_tipcos."' > " .
											"<input name=txtcosto".$li_i." type=text id=txtcosto".$li_i." value='".$ls_descosto."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][7] = "<input name=txtpreuni".$li_i." type=text id=txtpreuni".$li_i." value='".$ls_preuni."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][8] = "<input name=txtpreven".$li_i." type=text id=txtpreven".$li_i." value='".$ls_preven."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][9] = "<input name=txtpreven1".$li_i." type=text id=txtpreven1".$li_i." value='".$ls_preven1."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][10] = "<input name=txtpreven2".$li_i." type=text id=txtpreven2".$li_i." value='".$ls_preven2."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][11] = "<input name=txtpreven3".$li_i." type=text id=txtpreven3".$li_i." value='".$ls_preven3."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][12] = "<input name=txtcosfle".$li_i." type=text id=txtcosfle".$li_i." value='".$ls_cosfle."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][13] = "<input name=txtmin".$li_i." type=text id=txtmin".$li_i." value='".$ls_min."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][14] = "<input name=txtmax".$li_i." type=text id=txtmax".$li_i." value='".$ls_max."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][15] = "<input name=txtreoart".$li_i." type=text id=txtreoart".$li_i." value='".$ls_reoart."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][16] = "<input name=txtultcos".$li_i." type=text id=txtultcos".$li_i." value='".$ls_ultcosart."' class=sin-borde size=10 style= text-align:right readonly='true'>";
				$la_objectconcepto[$li_i][17] = "<input name=txtcospro".$li_i." type=text id=txtcospro".$li_i." value='".$ls_cosproart."' class=sin-borde size=10 style= text-align:right readonly='true'>";
			}
			$lb_valido=true;
		}
		else
		{
			$lb_valido=false;
			//$this->io_msgc="Registro no encontrado";
		}
	}

	$la_data=array ($la_objectconcepto,$totrow);
	return $la_data;
}

function uf_guardar_producto($ls_codpro,$ls_denpro,$ls_tippro,$ls_codcar,$ls_preven,$ls_codart,$ls_spicuenta,$ls_codcla,$ls_moncar,$ls_porgan,$ls_tipcos,$ls_preuni,$ls_preven1,$ls_preven2,$ls_preven3,$ls_cosfle,$ls_codsub,$ls_coduso,$aa_seguridad)
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

		$lb_existe=$this->uf_select_producto($ls_codpro);
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

			$this->io_msgc="Registro Incluido!!!";
			$ls_evento="INSERT";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_producto SET denpro='".$ls_denpro."',tippro='".$ls_tippro."',codcar='".$ls_codcar."',preven='".$li_precio."',codart='".$ls_codart."',spi_cuenta='".$ls_spicuenta."',codcla='".$ls_codcla."',moncar=".$ls_moncar.",porgan=".$ls_porgan.",tipcos='".$ls_tipcos."',preuni=".$ls_preuni.",preven1=".$ls_preven1.",preven2=".$ls_preven2.",preven3=".$ls_preven3.",cosfle=".$ls_cosfle.",cod_sub='".$ls_codsub."',id_uso='".$ls_coduso."' WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codpro='".$ls_codpro."';";


			//print $ls_cadena;
			$this->io_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}
		//print $ls_cadena;
		//$this->io_sql->begin_transaction();
        $li_numrows=$this->io_sql->execute($ls_cadena);

		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_producto".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->io_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion ="Inserto el Producto ".$ls_codpro." ".$ls_denpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo el Producto ".$ls_codpro." ".$ls_denpro." Asociado a la Empresa ".$ls_codemp;
					$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->io_msgc="No actualizo el registro";
				}
				else
				{
					$lb_valido=false;
					$this->io_msgc="Registro No Incluido!!!";
				}
			}

		}
		return $lb_valido;
	}


function uf_guardar_productostienda($ls_codpro,$ls_codcar,$ls_moncar,$ls_porgan,$ls_tipcos,$ls_preven,$ls_preuni,
									$ls_preven1,$ls_preven2,$ls_preven3,$ls_cosfle,$ls_minart,$ls_maxart,$ls_reoart,
									$ls_ultcosart,$ls_cosproart,$ls_tiendas,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_productostienda
		// Parameters:  - $ls_coduni( Codigo de la Unidad de Medida).
		//			    - $ls_codtun( Codigo de el tipo de Unidad de Medida).
		//			    - $ls_nomuni( Nombre de la Unidad).
		//              - $ls_desuni( Breve Descripcion de la Unidad).
		// Descripcion: - Funcion que guarda el producto enviado para cada tienda bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		$ls_codemp=$this->datoemp["codemp"];
		//$ls_codtie=$_SESSION["ls_codtienda"];

        $ls_moncar=$this->funsob->uf_convertir_cadenanumero($ls_moncar);
		$ls_porgan=$this->funsob->uf_convertir_cadenanumero($ls_porgan);
		$li_precio=$this->funsob->uf_convertir_cadenanumero($ls_preven);
		$ls_preuni=$this->funsob->uf_convertir_cadenanumero($ls_preuni);
		$ls_preven1=$this->funsob->uf_convertir_cadenanumero($ls_preven1);
		$ls_preven2=$this->funsob->uf_convertir_cadenanumero($ls_preven2);
		$ls_preven3=$this->funsob->uf_convertir_cadenanumero($ls_preven3);
		$ls_cosfle=$this->funsob->uf_convertir_cadenanumero($ls_cosfle);
		$ls_minart=$this->funsob->uf_convertir_cadenanumero($ls_minart);
		$ls_maxart=$this->funsob->uf_convertir_cadenanumero($ls_maxart);
		$ls_reoart=$this->funsob->uf_convertir_cadenanumero($ls_reoart);
		$ls_ultcosart=$this->funsob->uf_convertir_cadenanumero($ls_ultcosart);
		$ls_cosproart=$this->funsob->uf_convertir_cadenanumero($ls_cosproart);

		$tottiend = count($ls_tiendas);

		$lb_valido=false;

		if(!empty($ls_tiendas))
		{

		for($ai=0; $ai<$tottiend; $ai++)
		{
			$lb_existe=$this->uf_select_producto($ls_codpro,$ls_tiendas[$ai]);

			if(!$lb_existe)
			{
	            /*$ls_cadena= " INSERT INTO sfc_producto(codemp,codart,codcar,moncar,porgan,tipcos,preuni," .
	            			"preven,preven1,preven2,preven3,cosfle,minart,maxart,reoart,ultcosart,cosproart,codtiend) " .
	            			" VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_codcar."',".$ls_moncar.",".$ls_porgan.",'".$ls_tipcos."',".$ls_preuni."," .
	            			"".$li_precio.",".$ls_preven1.",".$ls_preven2.",".$ls_preven3.",".$ls_cosfle.",".$ls_minart.",".$ls_maxart.",".$ls_reoart."," .
	            			"".$ls_ultcosart.",".$ls_cosproart.",'".$ls_tiendas[$ai]."'); ";*/

	            $ls_cadena= " INSERT INTO sfc_producto(codemp,codart,codcar,moncar,porgan,preuni," .
	            			"preven,preven1,preven2,preven3,cosfle,minart,maxart,reoart,ultcosart,cosproart,codtiend) " .
	            			" VALUES ('".$ls_codemp."','".$ls_codpro."','".$ls_codcar."',".$ls_moncar.",".$ls_porgan.",".$ls_preuni."," .
	            			"".$li_precio.",".$ls_preven1.",".$ls_preven2.",".$ls_preven3.",".$ls_cosfle.",".$ls_minart.",".$ls_maxart.",".$ls_reoart."," .
	            			"".$ls_ultcosart.",".$ls_cosproart.",'".$ls_tiendas[$ai]."'); ";

	            //print $ls_cadena.'<br>';

				$this->io_msgc="Registro Incluido!!!";
				$ls_evento="INSERT";
			}
			else
			{
				//$ls_cadena= "UPDATE sfc_producto SET preven=".$li_precio.",tipcos='".$ls_tipcos."',preuni=".$ls_preuni."," .
				//			"preven1=".$ls_preven1.",preven2=".$ls_preven2.",preven3=".$ls_preven3." WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_tiendas[$ai]."' AND codart='".$ls_codpro."';";

				$ls_cadena="";

				//print $ls_cadena;
				$this->io_msgc="Registro Actualizado!!!";
				$ls_evento="UPDATE";
				$lb_valido=true;
			}

			if($ls_evento == "INSERT"){

				//$this->io_sql->begin_transaction();
		        $li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->is_msgc="Error en metodo uf_guardar_productostienda".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					//$this->io_sql->rollback();
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		       	}
				else
				{
					if($li_numrows>0)
					{
					$this->io_sql->commit();
						$lb_valido=true;

						if($ls_evento=="INSERT")
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="INSERT";
							$ls_descripcion ="Inserto el Producto ".$ls_codpro." de la Empresa ".$ls_codemp." y la tienda ".$ls_tiendas[$ai];
							$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
						else
						{
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="UPDATE";
							$ls_descripcion ="Actualizo el Producto ".$ls_codpro." de la Empresa ".$ls_codemp." y la tienda ".$ls_tiendas[$ai];
							$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
						//$this->io_sql->commit();
					}
					else
					{
						//$this->io_sql->rollback();
						$lb_valido=false;
						if($lb_existe)
						{
							//$lb_valido=0;
							$this->io_msgc="No actualizo el registro";
						}
						else
						{
							//$lb_valido=false;
							$this->io_msgc="Registro No Incluido!!!";
						}
					}

				}

				if(!$lb_valido){
					$ai = $tottiend+1;
				}

			}

		}// for tiendas

		}// NOT EMPTY


		return $lb_valido;
	}


function uf_update_producto($ls_codart,$ls_codtiend,$ls_codcar,$ls_moncar,$ls_porgan,$ls_cosfle,$ls_preuni
							,$ls_preven,$ls_preven1,$ls_preven2,$ls_preven3,$ls_max,$ls_min,$ls_reoart,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_update_producto
	// Parameters:  - $ls_codart( Codigo del Articulo).
	//			    - $ls_codtiend( Codigo de la tienda).
	//			    - $ls_codcar( Codigo del Cargo Asosiado).
	//				- $ls_moncar( Monto del Cargo)
	//				- $ls_porgan( Porcentaje de Gan)
	//				- $ls_cosfle( Costo flete)
	//				- $ls_preuni( Precio Unitario)
	//				- $ls_preven( Precio de Venta)
	//				- $ls_preven1( Precio de Venta1)
	//				- $ls_preven2( Precio de Venta2)
	//				- $ls_preven3( Precio de Venta3)
	//              - $ls_max( Stock Maximo)
	//              - $ls_min( Stock Minimo)
	//              - $ls_reorart( Pto de Reorden)
	// Descripcion: - Funcion que actualiza el Registro del Articulo por cada tienda.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];

	$lb_valido=true;

	$ls_cadena="Update sfc_producto set codcar='".$ls_codcar."', moncar=".$ls_moncar.", cosfle=".$ls_cosfle.", porgan=".$ls_porgan.", preuni=".$ls_preuni.", " .
			"preven=".$ls_preven.", preven1=".$ls_preven1.", preven2=".$ls_preven2.", preven3=".$ls_preven3.", maxart=".$ls_max.", minart=".$ls_min.", reoart=".$ls_reoart." " .
			"where codart='".$ls_codart."' and codtiend='".$ls_codtiend."'";

	//print $ls_cadena;
	$this->io_msgc="Registro Actualizado!!!";
	$ls_evento="UPDATE";

	$this->io_sql->begin_transaction();
    $li_numrows=$this->io_sql->execute($ls_cadena);

	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_update_producto".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->io_msgc;
		print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
   	}
	else
	{
		if($li_numrows>0)
		{
			$lb_valido=true;

			if($ls_evento=="INSERT")
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="INSERT";
				$ls_descripcion ="Insert� el Producto ".$ls_codart." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualiz� el Producto ".$ls_codart." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}

			$this->io_sql->commit();
			$lb_valido=true;
		}
		else
		{
			$this->io_sql->rollback();
			$lb_valido=false;
			$this->io_msgc="No actualiz� el registro";

		}

	}

	return $lb_valido;
}


	function uf_delete_producto($ls_codpro,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];

		$lb_existe=$this->uf_select_producto($ls_codpro);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_producto
							  WHERE codemp='".$ls_codemp."' AND codtiend='".$ls_codtie."' AND codpro='".$ls_codpro."'";
				$this->io_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->io_msgc="Error en metodo uf_delete_producto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->io_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� el Producto ".$ls_codpro." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						//$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Eliminado!!!";
						$this->io_sql->rollback();
					}

				}
		}
		else
		{
			$lb_valido=1;
			$this->io_msg->message("El Registro no Existe");
		}
		return $lb_valido;
	}

	function uf_delete_producto_tiendas($ls_codpro, $as_tiendas ,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_conceptos
		// Parameters:  - $ls_codigo( Codigo del concepto).
		// Descripcion: - Funcion que elimina la unidad de medida.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp=$this->datoemp["codemp"];
		$tottiend = count($as_tiendas);
		//$this->io_sql->begin_transaction();

		for($ai=0; $ai<$tottiend; $ai++){
			$lb_existe=$this->uf_select_producto($ls_codpro,$as_tiendas[$ai]);

			if($lb_existe)
			{
			    	$ls_cadena= " DELETE FROM sfc_producto WHERE codemp='".$ls_codemp."' " .
			    				"AND codtiend='".$as_tiendas[$ai]."' AND codart='".$ls_codpro."';";
					$this->io_msgc="Registro Eliminado!!!";

					$li_numrows=$this->io_sql->execute($ls_cadena);

					if(($li_numrows==false)&&($this->io_sql->message!=""))
					{
						$lb_valido=false;
						$this->io_msgc="Error en metodo uf_delete_producto_tiendas ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
						print $this->io_msgc;
						print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					}
					else
					{
						if($li_numrows>0)
						{
							$lb_valido=true;
							////////////////////////////////         SEGURIDAD               /////////////////////////////
							$ls_evento="DELETE";
							$ls_descripcion ="Elimin� el Producto ".$ls_codpro." Asociado a la Empresa ".$ls_codemp." y a la tienda ".$as_tiendas[$ai];
							$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
															$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
															$aa_seguridad["ventanas"],$ls_descripcion);
							/////////////////////////////////         SEGURIDAD               /////////////////////////////
						}
						else
						{
							$lb_valido=false;
							$this->is_msgc="Registro No Eliminado!!!";
							//$this->io_sql->rollback();
							$ai = $tottiend;
						}

					}
			}
			/*else
			{
				$lb_valido=1;
				$this->io_msg->message("El Registro no Existe");
			}*/

		}

		return $lb_valido;
	}

}
?>

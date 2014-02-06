<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sigesp_sfc_c_factura
 // Autor:       - Ing. Gerardo Cordero
 // Descripcion: - Clase que realiza los procesos basicos (insertar,actualizar,eliminar) con
 //                respecto a la tabla sfc_factura y sfc_detfactura.
 // Fecha:       - 16/02/2007
 //////////////////////////////////////////////////////////////////////////////////////////
class sigesp_sfc_c_factura
{

 var $io_funcion;
 var $is_msgc;
 var $io_sql;
 var $datoemp;
 var $io_msg;


function sigesp_sfc_c_factura()
{
	require_once ("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	require_once("sigesp_sob_c_funciones_sob.php"); /* se toma la funcion de convertir cadena a caracteres*/
	$this->funsob=   new sigesp_sob_c_funciones_sob();
	$this->seguridad=   new sigesp_c_seguridad();
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_datastore.php");
	$this->io_datastore=new class_datastore();
	$this->io_msg=new class_mensajes();
	}


function uf_select_factura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
                //$ls_codtie='0001';
		$ls_cadena="SELECT * FROM sfc_factura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codtiend='".$ls_codtie."';";
		//print $ls_cadena;
                
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_imprimir_factura($ls_numfac,&$ls_cadena)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_cadena="SELECT f.numfac,f.codtiend,f.fecemi,f.conpag,f.numordent,f.numcon,f.obsfac,f.numdiacre,SUM(df.canpro) as canpro,df.codart,df.prepro,df.porimp,df.costo,c.razcli,t.codtiend,t.dirtie".
	",c.dircli,c.telcli,c.cedcli,a.denart as denpro,um.denunimed FROM sfc_factura f,sfc_detfactura df,sfc_cliente c, sfc_tienda t,".
	"sim_articulo a,sfc_producto p,sim_unidadmedida um WHERE f.numfac=df.numfac AND f.codtiend=t.codtiend AND f.codemp=df.codemp AND ".
	" f.codtiend=df.codtiend AND f.codcli=c.codcli AND f.codemp=c.codemp AND f.codemp=a.codemp AND f.codemp=p.codemp ".
	" AND f.codtiend=p.codtiend AND df.codemp=c.codemp AND df.codart=a.codart AND df.codemp=a.codemp AND df.codart=p.codart ".
	" AND df.codemp=p.codemp AND df.codtiend=p.codtiend AND c.codemp=a.codemp AND c.codemp=p.codemp AND a.codart=p.codart ".
	" AND a.codemp=p.codemp AND a.codunimed=um.codunimed AND f.codemp='".$ls_codemp."' AND f.numfac='".$ls_numfac."' ".
	" GROUP BY f.numfac,f.codtiend,f.fecemi,f.conpag,f.numordent,f.numcon,df.codart,f.obsfac,f.numdiacre,df.prepro,df.porimp,df.costo,c.razcli,t.codtiend,t.dirtie,c.dircli,c.telcli,c.cedcli,a.denart,um.denunimed";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_imprimir_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_validar_cajero($ls_codusu)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
	    $ls_codtie=$_SESSION["ls_codtienda"];
		$ls_cadena="SELECT * FROM sfc_cajero
		            WHERE codemp='".$ls_codemp."' AND codusu='".$ls_codusu."' AND codtiend ilike '".$ls_codtie."';";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_validar_cajero ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				//$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_guardar_factura($ls_codcli,$ls_numfac,$ls_numcot,$ls_numor,$ls_codusu,$ls_fecemi,$ls_conpag,$ld_monto,$ls_estfaccon,$ls_montoret,$ls_esppag,$ls_montopar,$ls_codtiend,$ls_numcontrol,$ls_estcot,$la_detalles,$li_filasconcepto,$ls_codtie,$la_detapag,$li_filasfpago,$ls_coduniadm,$ld_totalforpag,$ld_totalmonret,$aa_seguridad,$ls_monbaseimponible,$ls_monexento,$ls_observaciones,$ls_dias)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_factura.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//				- $ls_numfac(N�mero de la factura)
		//			    - $ls_numcot.
		//			    - $ls_numor.
		//			    - $ls_codusu(c�digo del usuario).
		//				- $ls_fecemi(fecha de la emision de la factura).
		//				- $ls_conpag(condicion de pago: Cr�dito � Contado).
		//              - $ld_monto(monto a pagar).
		//              - $ld_estfac(Estado de la factura: Cancelada � No cancelada).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		
		echo "<div id='progress' style='position:relative;padding:0px;width:1000px;height:960px;left:25px;'>";
		echo "***********Espere, Guardando Factura ".$ls_numfac."************";
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";
		flush();
		ob_flush();
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";		
		flush();
		ob_flush();
		require_once("class_folder/sigesp_sfc_c_cotizacion.php");
		$io_cotizacion=new  sigesp_sfc_c_cotizacion();
		require_once("class_folder/sigesp_sfc_c_instpago.php");
		$io_instpago=new sigesp_sfc_c_instrpago();
		require_once("class_folder/sigesp_sfc_c_nota.php");
		$io_nota=new sigesp_sfc_c_nota();
		require_once("../shared/class_folder/class_mensajes.php");
		$io_msg=new class_mensajes();
		require_once("class_folder/sigesp_sfc_c_secuencia.php");
		$io_secuencia=new sigesp_sfc_c_secuencia();
		/************CLASE PARA LA INTEGRACION CON INVENTARIO********************/
		require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
		$io_art=  new sigesp_sim_c_articuloxalmacen();
		require_once("class_folder/sigesp_sim_c_despacho.php");
		$io_siv=  new sigesp_sim_c_despacho();
		require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
		$io_mov=    new sigesp_sim_c_movimientoinventario();
		require_once("class_folder/sigesp_sim_c_recepcion.php");
		$io_sivRe=  new sigesp_sim_c_recepcion();
		/************************************************************************/
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
		$ld_monto=$this->funsob->uf_convertir_cadenanumero($ld_monto); /* convierte cadena en numero */
		$ld_montoret=$this->funsob->uf_convertir_cadenanumero($ls_montoret); /* convierte cadena en numero */
		$ld_montopar=$this->funsob->uf_convertir_cadenanumero($ls_montopar); /* convierte cadena en numero */
		$ld_monbaseimponible=$this->funsob->uf_convertir_cadenanumero($ls_monbaseimponible); /* convierte cadena en numero */
		$ld_monexento=$this->funsob->uf_convertir_cadenanumero($ls_monexento); /* convierte cadena en numero */
		$ld_dias=$this->funsob->uf_convertir_cadenanumero($ls_dias); /* convierte cadena en numero */
		$ls_sercon=$_SESSION["ls_sercon"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		$ls_numcontrol=$ls_sercon."-".$ls_numcontrol;
		
		if ($ls_dias!=""){
		  $ld_dias=$this->funsob->uf_convertir_cadenanumero($ls_dias); /* convierte cadena en numero */
		}else{
	    $ld_dias=0;
	    }	
		$m=time() - 1800;
		$hora=date("h:i:s",$m);			
		$ls_fecemi=$this->io_funcion->uf_convertirdatetobd_hora($ls_fecemi,$hora);
		$lb_valido=false;
		if (!$lb_valido)
		{
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";
		flush();
		ob_flush();
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";
		flush();
		ob_flush();
		//echo "Pase";
		if(!$lb_existe)
		{
			
            $ls_cadena= "INSERT INTO sfc_factura (codemp,codcli,numfac,numcot,codusu,fecemi,conpag,monto,estfac," .
            		"estfaccon,montoret,esppag,montopar,codtiend,cod_caja,numcon,numordent,monexe,obsfac,numdiacre) VALUES ('".$ls_codemp."'," .
            				"'".$ls_codcli."','".$ls_numfac."','".$ls_numcot."','".$ls_codusu."','".$ls_fecemi."'," .
            						"'".$ls_conpag."',".$ld_monto.",'N','".$ls_estfaccon."',".$ld_montoret."," .
            								"'".$ls_esppag."',".$ld_montopar.",'".$ls_codtiend."'," .
            										"'".$_SESSION["ls_codcaj"]."','".$ls_numcontrol."','".$ls_numor."','".$ld_monexento."','".$ls_observaciones."','".$ld_dias."');";
		//print $ls_cadena;
			
			$ls_evento="INSERT";
			$this->is_msgc="";
		}
		else
		{
			$ls_cadena= "UPDATE sfc_factura
			             SET codcli='".$ls_codcli."', numcot='".$ls_numcot."', codusu='".$ls_codusu."', fecemi='".$ls_fecemi."', conpag='".$ls_conpag."', monto=".$ld_monto.", estfaccon='".$ls_estfaccon."', montoret=".$ld_montoret.", esppag='".$ls_esppag."', montopar=".$ld_montopar.",numcon='".$ls_numcontrol."',numordent='".$ls_numor."',monexe='".$ld_monexento."',obsfac='".$ls_observaciones."',numdiacre='".$ld_dias."' WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
						 //print $ls_cadena;

			//$this->io_msg="Registro Actualizado!!!";
			$ls_evento="UPDATE";
		}

		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_factura, ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
       	}
		else
		{
		
			if($li_numrows>0)
			{
				$lb_valido=$io_cotizacion->uf_update_cotizacionstatus($ls_numcot,$ls_estcot,$aa_seguridad);
				//print 'paso';
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
				height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
				height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
			
				$lb_valido=$this->uf_update_detallesfacturas($ls_codcli,$ls_numfac,$la_detalles,$li_filasconcepto,$ls_codtie,$aa_seguridad);
				if ($lb_valido)
				{
					//print 'p<o2';
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
							height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
							height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
							height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					$lb_valido=$io_instpago->uf_update_detalles_instrumentopago($ls_codcli,$ls_numfac,$la_detapag,$li_filasfpago,$ls_codtie,$aa_seguridad);					
					$lb_valido=true;
				}
				if ($lb_valido)
				{
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				//print 'paso';
				/*****************************************************/
				/*******INTEGRACION CON INVENTARIO********************/
				/*****************************************************/
				$ls_obsdes="Despacho por concepto de venta bajo la Factura Nro. ".$ls_numfac;
				$ls_numdoc=substr($ls_numfac,10,strlen($ls_numfac));
				$ld_fecemi=$this->io_funcion->uf_convertirdatetobd($ls_fecemi);
				$lb_valido=$io_siv->uf_sim_insert_despacho($ls_codemp,$ls_numord,$ls_numdoc,$ls_coduniadm,$ld_fecemi,$ls_obsdes,$_SESSION["la_logusr"],"1","1",$ls_coduniadm,$ls_codtie,$aa_seguridad);

				if($lb_valido)
				{
				echo "Procesando Inventario";
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
					$lb_valido=$io_siv->io_mov->uf_sim_insert_movimiento($ls_codemp,$ls_nummov,$ld_fecemi,"Despacho",$_SESSION["la_logusr"],$ls_codtie,$aa_seguridad);

					if ($lb_valido)
					{

					for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
					{
					//print 'paso';
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
						$ls_codpro=$la_detalles["codpro"][$li_i];
						$ls_codalm=$la_detalles["codalm"][$li_i];
						//print $ls_codalm;
						if ($ls_codalm=='' or strlen($ls_codalm)<10)
						{
						   if ($ls_codalm=='')
						   {
							 $ls_codalm=$this->io_funcion->uf_cerosizquierda($ls_codtie,10);
						   }
						   else
						   {
							$ls_codalm=$this->io_funcion->uf_cerosizquierda($ls_codalm,10);
						   }
						}
						$ls_cant=$la_detalles["canpro"][$li_i];
						$ls_prepro=$la_detalles["prepro"][$li_i];
						$ls_costo=$la_detalles["costo"][$li_i];
						$ls_cod_prov=$la_detalles["cod_pro"][$li_i];
						$ld_porimp=$this->funsob->uf_convertir_cadenanumero($la_detalles["porimp"][$li_i]);
						$li_preuniart=$this->funsob->uf_convertir_cadenanumero($ls_prepro);
						$ld_canpro=$this->funsob->uf_convertir_cadenanumero($ls_cant);
						$li_montotart=($li_preuniart*$ld_canpro)+(($li_preuniart*$ld_canpro)*$ld_porimp);
						$ls_unidad="D";
						$lb_valido=$io_siv->uf_sim_procesar_dt_despacho($ls_codemp,$ls_numord,$ls_codpro,$ls_codalm,$ls_unidad,$ld_canpro,$ld_canpro,$li_preuniart,$li_montotart,$li_montotart,$li_i,$ls_nummov,$ld_fecemi,$ls_numdoc,0,$ls_cod_prov,$ls_codtie,$aa_seguridad,'FAC');

						if ($lb_valido)
						{
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();
						$lb_valido=$io_art->uf_sim_disminuir_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,$ld_canpro,$ls_cod_prov,$ls_codtie,$aa_seguridad);

						}
					}
					}
					if ($lb_valido)
					{
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
								height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
								height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
						/*****************   GENERAR NOTAS     ***************/  	    
						$ld_monto=$ld_totalforpag-$ld_totalmonret;
						if ($ld_monto>0) // if sobra dinero se genera nota de crï¿½dito automï¿½tica
						{
							$ld_monto=number_format($ld_monto,2,',','.');  //convierte a formato numï¿½rico
							$ls_codcaj=$_SESSION["ls_codcaj"];
							$ls_prefijo="NC";
							$ls_serie=$_SESSION["ls_sernot"];
							$io_secuencia->uf_obtener_secuencia($ls_codcaj.$ls_codtie."not",&$ls_secuencia);
							$ls_numnot=$io_secuencia->uf_crear_codigo($ls_prefijo,$ls_serie,$ls_secuencia);
							$ls_dennot="Nota de Crédito, por sobrante en la factura Nro. ".$ls_numfac;
							$ls_tipnot="CXP"; // C:nota crï¿½dito y D:nota dï¿½bito
							$ls_fecnot=$ls_fecemi;
							$ls_estnot="P"; //P: pendiente y C: cancelado
							//****  generar nueva nota de crï¿½dito automï¿½tica  *****
							$lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numnot,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numfac,$ls_codtie,$aa_seguridad);
						
						//	$io_nota->io_sql->commit();
							$io_msg->message (utf8_decode("Se ha generado un Saldo a favor del cliente Nota de Crédito Nro ").$ls_numnot);
						}
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
						height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
									height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
									height:50px;background:red;color:red;'> </div>";
						flush();
						ob_flush();

						if  ($ls_conpag==2)
						{
							$ld_totalmonret=number_format($ld_totalmonret,2,',','.');
							$ls_dennot="Nota de Debito, por cuenta a cobrar de facturar Nro ".$ls_numfac;
							$ls_tipnot="CXC"; // C:nota crï¿½dito y D:nota dï¿½bito
							$ls_fecnot=$ls_fecemi;
							$ls_estnot="P"; //P: pendiente y C: cancelado
							//****  generar nueva nota de crï¿½dito automï¿½tica  *****
							$lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numfac,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_totalmonret,$ls_estnot,$ls_numfac,$ls_codtie,$aa_seguridad);
							//$io_nota->io_sql->commit();
							$ls_mensaje=$io_nota->is_msgc;
							$io_msg->message ("Se ha generado la Nota de Debito ".$ls_numfac);
						 }
						 elseif($ls_conpag==4)
						 {
							//print 'paso carta orden';
							 for ($li_i=1;$li_i<$li_filasfpago;$li_i++)
							 {
								  $ls_metforpago=$_POST["txtmetforpag".$li_i];
								  if ($ls_metforpago=="O")
								  {
									 $ls_numinst=$_POST["txtnuminst".$li_i];
									 $ls_moncarta=$_POST["txtmontoforpag".$li_i];
									 $ls_dennot="Nota de Debito, por carta orden registrada Nro ".$ls_numinst;
									 $ls_tipnot="CXC"; // C:nota crï¿½dito y D:nota dï¿½bito
									 $ls_fecnot=$ls_fecemi;
									 $ls_estnot="P"; //P: pendiente y C: cancelado
									 //****  generar nueva nota de crï¿½dito automï¿½tica  *****
									 $lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numinst,$ls_dennot,$ls_tipnot,$ls_fecnot,$ls_moncarta,$ls_estnot,$ls_numfac,$ls_codtie,$aa_seguridad);
								   //  $io_nota->io_sql->commit();
									 $ls_mensaje=$io_nota->is_msgc;
									 $io_msg->message ("Se ha generado la Nota de Debito ".$ls_numinst);
								   }
									 }
								 }
								 elseif($ls_conpag==3)
								 {
									$ld_monto=$ld_totalmonret-$ld_totalforpag;
									$ld_monto=number_format($ld_monto,2,',','.');
									$ls_dennot="Nota de Debito, por pago parcial de la factura Nro ".$ls_numfac;
									$ls_tipnot="CXC"; // C:nota crï¿½dito y D:nota dï¿½bito
									$ls_fecnot=$ls_fecemi;
									$ls_estnot="P"; //P: pendiente y C: cancelado
									//****  generar nueva nota de crï¿½dito automï¿½tica  *****
									$lb_valido=$io_nota->uf_guardar_nota_factura($ls_codcli,$ls_numfac,$ls_dennot,$ls_tipnot,$ls_fecnot,$ld_monto,$ls_estnot,$ls_numfac,$ls_codtie,$aa_seguridad);
								//	$io_nota->io_sql->commit();
									$ls_mensaje=$io_nota->is_msgc;
									$io_msg->message ("Se ha generado la Nota de Debito ".$ls_numfac);
						
								 }				
						}

 				}

				}
				if($ls_evento=="INSERT")
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="INSERT";
					$ls_descripcion ="Inserto la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
					$this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizo la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
					$this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
													$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
													$aa_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				}
				if ($lb_valido)
				{
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
					height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
								height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
								height:50px;background:red;color:red;'> </div>";
					flush();
					ob_flush();
	
					$io_msg->message ("Factura Nro. ".$ls_numfac." Se ha guardado exitosamente!!!");
					$this->io_sql->commit();
					$lb_valido_repfac=$this->uf_imprimir_factura($ls_numfac,&$ls_sql);
					/*if ($lb_valido_repfac==true)
					{
					$ls_formalibre=$_SESSION["ls_formalibre"];
					?>
						 <script language="JavaScript">
							var ls_sql="<?php print $ls_sql; ?>";
							var ls_numcontrol="<?php print $ls_numcontrol; ?>";
							ls_formalibre='<?php print $ls_formalibre ?>';
							if (ls_formalibre=='S')
							{
								pagina="reportes/sigesp_sfc_rep_imprimirfacturalibre.php?sql="+ls_sql+"&ls_numcontrol="+ls_numcontrol;
							}
							else
							{
								pagina="reportes/sigesp_sfc_rep_imprimirfactura.php?sql="+ls_sql;
				
							}
				
								popupWin(pagina,"catalogo",580,700);
						 </script>
					<?php
					}*/
				}
				else
				{
					$this->is_msgc="Registro No Incluido!!! - ".$this->is_msgc;
					$this->io_sql->rollback();
					if($lb_existe)
					{
						$lb_valido=0;
						$this->is_msgc="No actualizo el registro".$this->is_msgc;
					}
					else
					{
						$lb_valido=false;
						$this->is_msgc="Registro No Incluido!!!".$this->is_msgc;
					}
				}
			}
			else
			{
			$this->is_msgc="Registro No Incluido!!!".$this->is_msgc;
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->is_msgc="No actualizo el registro".$this->is_msgc;
				}
				else
				{
					$lb_valido=false;
					$this->is_msgc="Registro No Incluido!!!".$this->is_msgc;

				}
			}

		}
	}
	else
	{
	//print 'paso0000';
	$this->io_sql->rollback();
	$this->is_msgc="Registro No Incluido!!!".$this->is_msgc;
			if($lb_existe)
			{
				$lb_valido=0;
				$this->is_msgc="No actualizo el registro".$this->is_msgc;
			}
			else
			{
				$lb_valido=false;
				$this->is_msgc="Registro No Incluido!!!".$this->is_msgc;

			}

	}
		//return $lb_valido;
		
echo "</div>";
echo "<script>";
echo "document.getElementById('progress').style.display = 'none';";
echo "</script>";
///exit;
print("<script language=JavaScript>");
//print(" location.href='sigesp_sfc_d_factura.php';");
print("</script>");
return $lb_valido;
}
/*******************************************************************************************************************************/
function uf_actualizar_facturastatus($ls_numfac,$ls_estfaccon,$li_filasconcepto,$ls_numcot,$ls_numor,$li_filasfpago,$la_seguridad,$ls_obsAnul)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_guardar_factura.
		// Parameters:  - $ls_codcli( Codigo del cliente).
		//				- $ls_numfac(N�mero de la factura)
		//			    - $ls_numcot.
		//			    - $ls_codusu(c�digo del usuario).
		//				- $ls_fecemi(fecha de la emision de la factura).
		//				- $ls_conpag(condicion de pago: Cr�dito � Contado).
		//              - $ld_monto(monto a pagar).
		//              - $ld_estfac(Estado de la factura: Cancelada � No cancelada).
		// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
		//////////////////////////////////////////////////////////////////////////////////////////
		echo "<div id='progress' style='position:relative;padding:0px;
width:1000px;height:960px;left:25px;'>";
		echo "***********Espere, Anulando Factura ".$ls_numfac."************";
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";
		flush();
		ob_flush();
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
		height:50px;background:red;color:red;'> </div>";
		flush();
		ob_flush();
		require_once("class_folder/sigesp_sfc_c_nota.php");
		$io_nota=new sigesp_sfc_c_nota();
		require_once("class_folder/sigesp_sfc_c_cotizacion.php");
		$io_cotizacion=new  sigesp_sfc_c_cotizacion();
	/************CLASE PARA LA INTEGRACION CON INVENTARIO********************/
		require_once("class_folder/sigesp_sim_c_articuloxalmacen.php");
		$io_art=  new sigesp_sim_c_articuloxalmacen();
		require_once("class_folder/sigesp_sim_c_despacho.php");
		$io_siv=  new sigesp_sim_c_despacho();
		require_once("class_folder/sigesp_sim_c_movimientoinventario.php");
		$io_mov=    new sigesp_sim_c_movimientoinventario();
		require_once("class_folder/sigesp_sim_c_recepcion.php");
		$io_sivRe=  new sigesp_sim_c_recepcion();
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);
                $ls_codtie=$_SESSION["ls_codtienda"];
		$ls_codusu=$_SESSION["la_logusr"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
                    
		if(!$lb_existe)
		{
		}
		else
		{   
                    $fechaanu = date('Y/m/d');
                    
                    $cadenaAnular = ($ls_obsAnul=="")?"":",obsanu     ='".$ls_obsAnul."', fecanu='$fechaanu', codusu ='".$_SESSION["la_logusr"]."'";
		    $ls_cadena= "UPDATE sfc_factura ".
			            "SET estfaccon='".$ls_estfaccon."' ".
                                    $cadenaAnular." WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codtiend='".$ls_codtie."';";
			//print $ls_cadena;
                        
			$this->is_msgc="Registro Actualizado!!!";
			$ls_evento="UPDATE";
                   //$io_msg->message ($ls_cadena);
		}
      // print "guardar factura: ".$ls_evento.": ".$ls_cadena;

		$this->io_sql->begin_transaction();
		$li_numrows=$this->io_sql->execute($ls_cadena);
                
                
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_facturastatus".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
                }
		else
		{
			if($li_numrows>0)
			{
			echo "Procesando Inventario";
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				$lb_valido=true;
				/*******************INTEGRACION CON INVENTARIO***************************/
				$ls_serie=$_SESSION["ls_serfac"];
		 	    $ls_docum='FAC-'.$ls_serie;
		  	    $ls_docum=$ls_docum.substr($ls_numfac,strlen($ls_numfac)-4);
				
			 //print $ls_docum;
			 for ($li_i=1;$li_i<$li_filasconcepto;$li_i++)
         	 {
	              echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
		
				    // print $li_i;
					$ls_codpro=$_POST["txtcodpro".$li_i];
					$ls_prepro=$_POST["txtprepro".$li_i];
					$ls_candev=$_POST["txtcanpro".$li_i];
					$ls_porimp=$_POST["txtporcar".$li_i];
					$ls_codalm=$_POST["txtcodalm".$li_i];
					$ls_cod_pro=$_POST["txtcod_pro".$li_i];
					$ls_nompro=$_POST["txtnompro".$li_i];
					$ls_costo=$_POST["txtcosto".$li_i];
					//print $ls_costo;
					if ($ls_codalm=='' or strlen($ls_codalm)<10)
					{
					   if ($ls_codalm=='')
					   {
						 $ls_codalm=$ls_secuencia=$this->io_funcion->uf_cerosizquierda($ls_codtie,10);
					   }
					   else
					   {
						$ls_codalm=$ls_secuencia=$this->io_funcion->uf_cerosizquierda($ls_codalm,10);
					   }
					}
					if($ls_candev!="0,00"){
					$ls_candev=$this->funsob->uf_convertir_cadenanumero($ls_candev);
					$ls_prepro=$this->funsob->uf_convertir_cadenanumero($ls_prepro);
					$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp);
					$ld_iva=($ls_costo*$ls_porimp)*$ls_candev;
					$li_monsubart=$ls_costo*$ls_candev;
					$li_montotart=$li_monsubart+$ld_iva;
					$ls_fecdev=date("Y-m-d");

					$lb_valido=$io_sivRe->uf_sim_insert_recepcion($ls_codemp,$ls_docum,$ls_codalm,$ls_fecdev,"Entrada a Almacen por Anulacion de Factura",$ls_codusu,"0","1",$ls_cod_pro,$ls_codtie,&$ls_numconrec,$la_seguridad);
					if ($lb_valido)
					{
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
		
					$lb_valido=$io_sivRe->uf_sim_insert_dt_recepcion($ls_codemp,$ls_docum,$ls_codpro,"D",$ls_candev,0,$ls_costo,$li_monsubart,$li_montotart,$li_i,$ls_candev,$ls_numconrec,$ls_cod_pro,$ls_codtie,$la_seguridad);
						if ($lb_valido)
						{
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
			
							$ls_nummov=0;
							$ls_nomsol="Recepcion";

							$lb_valido=$io_mov->uf_sim_insert_movimiento($ls_codemp,&$ls_nummov,$ls_fecdev,$ls_docum,$ls_codusu,$ls_codtie,$la_seguridad);
							if($lb_valido)
							{
							echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				
								$ls_opeinv="ENT";
								$ls_promov="RPC";
								$ls_codprodoc="FAC";

								$lb_valido=$io_mov->uf_sim_insert_dt_movimiento($ls_codemp,$ls_nummov,$ls_fecdev,
								$ls_codpro,$ls_codalm,$ls_opeinv,$ls_codprodoc,$ls_docum,$ls_candev,															$ls_costo,$ls_promov,$ls_numconrec,$ls_candev,$ls_fecdev,$ls_cod_pro,$ls_codtie,$la_seguridad);
								$lb_valido=$io_art->uf_sim_aumentar_articuloxalmacen($ls_codemp,$ls_codpro,$ls_codalm,$ls_candev,$ls_cod_pro,$ls_codtie,$la_seguridad);
							}

					 	}

					}

					if($lb_valido)
					{
					echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
			
						
						$this->uf_delete_notas($ls_numfac,$la_seguridad);
						/******************************************/
                        for ($li_j=1;$li_j<$li_filasfpago;$li_j++)
		                {
						echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();
				
						//print 'paso';
							$ls_codforpago=$_POST["txtcodforpag".$li_j];
							$ls_metforpago=$_POST["txtmetforpag".$li_j];
							$ls_numinst=$_POST["txtnuminst".$li_j];
							if ($ls_metforpago=="D"  and $ls_codforpago=="03")
							 {
							   $ls_estnot="P";
							   $ls_numnot=$ls_numinst;//nï¿½mero de nota de debito usada para pagar.
							   $lb_valido=$io_nota->uf_update_actualizaestnot($ls_numnot,$ls_estnot,$la_seguridad);
							 }
		                }
                        /*********************************************/
					}
				}
			}
			$lb_valido=$io_cotizacion->uf_update_cotizacionstatusfactura($ls_numcot,'E',$la_seguridad);
		/*******************FIN INTEGRACION CON INVENTARIO***********************/
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="UPDATE";
					$ls_descripcion ="Anuló la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
					$this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
													$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
													$la_seguridad["ventanas"],$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
				if ($lb_valido)
				{
				echo "<div style='float:left;margin:5px 0px 0px 1px;width:20px;
			height:50px;background:red;color:red;'> </div>";
				flush();
				ob_flush();				
				$this->io_sql->commit();
				}
				else
				{
				$this->io_sql->rollback();
				$this->is_msgc="Registro No Incluido!!!";
				}
			}
			else
			{
				$this->io_sql->rollback();
				if($lb_existe)
				{
					$lb_valido=0;
					$this->is_msgc="No actualizo el registro";

				}
				else
				{
					$lb_valido=false;
					$this->is_msgc="Registro No Incluido!!!";

				}
			}

		}
			//exit;
echo "</div>";
echo "<script>";
echo "document.getElementById('progress').style.display = 'none';";
echo "</script>";
	return $lb_valido;
}
/*******************************************************************************************************************************/

function uf_delete_factura($ls_numfac,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (n�mero de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_factura($ls_numfac);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_factura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				$this->is_msgc="Registro Eliminado!!!";

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->is_msgc="Error en metodo uf_delete_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->is_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print "delete factura:".$ls_cadena;*/
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
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
/******************************************************************************************************************************/
/******************  FACTURA DETALLES **************************************************************************************/
/******************************************************************************************************************************/
function uf_select_detfactura($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( N�mero de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_detfactura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       /* print $ls_cadena;*/
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_detfactura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_select_detproducto($ls_numfac,$ls_codpro)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detproducto
		// Parameters:  - $ls_codpro( Codigo del producto).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_detfactura
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codpro='".$ls_codpro."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

	function uf_delete_detfactura($ls_numfac,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_detfactura
		// Parameters:  - $ls_numfac (n�mero de factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_detfactura($ls_numfac);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_detfactura
							  WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				/*$this->is_msgc="Registro Eliminado!!!";		*/

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->is_msgc="Error en metodo uf_delete_detfactura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->is_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� los Detalle Asociado a la Factura ".$ls_numfac." de la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
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



/*************************************************************************************************************/
/********************  INSTRUMENTO DE PAGO *******************************************************************/
/*************************************************************************************************************/
function uf_select_instpago($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( N�mero de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_instpago
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       /* print $ls_cadena;*/
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_instpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_delete_instpago($ls_numfac,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_detfactura
		// Parameters:  - $ls_numfac (n�mero de factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_instpago($ls_numfac);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_instpago
							  WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
				/*$this->is_msgc="Registro Eliminado!!!";		*/

				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->is_msgc="Error en metodo uf_delete_instpago ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->is_msgc;
					print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
					/*print $ls_cadena;*/
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó las Instancias de Pago Asociado a la Factura ".$ls_numfac." de la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
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
/******************************************************************************************************/
/*********************** SELECT DETALLES FACTURACION **************************************************/
/******************************************************************************************************/
function uf_select_detallesfac ($ls_numfac,&$aa_data,&$ai_rows)
	{
	 /***************************************************************************************/
	 /*	Function:	    uf_select_detallescot                                               */
     /* Access:			public                                                              */
	 /*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro   */
	 /*	Description:	Funcion que se encarga de 									        */
	 /*  Fecha:          25/03/2006                                                         */
	 /*	Autor:          GERARDO CORDERO		                                                */
	 /***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_sql="SELECT  *
				 FROM sfc_detfactura
				 WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->is_msg_error="Error en select detallesfac".$this->io_function->uf_convertirmsg($this->io_sql->message);
			print $this->is_msg_error;
		}
		else
		{
			if($la_row=$this->io_sql->fetch_row($rs_data))
			{
				$lb_valido=true;
				$ai_rows=$this->io_sql->num_rows($rs_data);    //devuelve numero de filas
				$aa_data=$this->io_sql->obtener_datos($rs_data); // devuelve datos
			}else
			{
				$ai_rows=0;
				$aa_data="";
			}
		}
		return $lb_valido;
	}
  /*****************************************************************************************/
  /*********************** GUARDAR DETALLES FACTURACION ************************************/
  /*****************************************************************************************/
	function uf_guardar_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codtie,$ls_cod_pro,$ls_costo,$aa_seguridad)
    {
	    /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          Ing. Zulheymar Rodríguez                  Ult. modif=27/03/2009    */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_canpro=$this->funsob->uf_convertir_cadenanumero($ls_canpro); /* convierte cadena en numero */
		$ls_prepro=$this->funsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
		//$ls_costo=$this->funsob->uf_convertir_cadenanumero($ls_costo); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		$ls_sql= "INSERT INTO sfc_detfactura (codemp,numfac,codart,canpro,prepro,porimp,codalm,codtiend,cod_pro,costo) " .
				"VALUES ('".$ls_codemp."','".$ls_numfac."','".$ls_codpro."',".$ls_canpro.",".$ls_prepro."," .
						"".$ls_porimp.",'".$ls_codalm."','".$ls_codtie."','".$ls_cod_pro."',".$ls_costo.");";	
						
		//print $ls_sql;	
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{

			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_guardar_detallesfac".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msgc;
		}
		else
		{
			if($li_row>0)
			{
			    //************    SEGURIDAD    **************
				  $ls_evento="INSERT";
				  $ls_descripcion ="Insertó Detalle ".$ls_codpro." de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				//**********************************************/
				//$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	}
  /*****************************************************************************************/
  /*********************** BORRAR DETALLES FACTURACION *************************************/
  /*****************************************************************************************/
	function uf_delete_detallesfac($ls_numfac,$ls_codpro,$aa_seguridad)

	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          Ing. Zulheymar Rodríguez              Ult. Mod. 27/03/2009         */
		/***************************************************************************************/
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		$ls_sql= "DELETE FROM sfc_detfactura WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' " .
				"AND codart='".$ls_codpro."';";

		//$this->io_sql->begin_transaction();
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_sql->rollback();

			//$this->is_msgc="Error en uf_select_detproducto ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);

			print"Error en metodo eliminar_detallesfac".$this->io_function->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			//*************    SEGURIDAD    **************
			$ls_evento="DELETE";
			$ls_descripcion ="Eliminó el Detalle ".$ls_codpro." de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
			$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
			//********************************************/
			$lb_valido=true;
			//S$this->io_sql->commit();
		}
		return $lb_valido;

	}
  /*****************************************************************************************/
  /*********************** UPDATE DETALLES FACTURACION *************************************/
  /*****************************************************************************************/
	function uf_update_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_cod_pro,$ls_costo,$aa_seguridad)
	 {
		 /***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          Ing. Zulheymar Rodríguez              Ult. Mod. 27/03/2009         */
		/***************************************************************************************/

		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$ls_canpro=$this->funsob->uf_convertir_cadenanumero($ls_canpro); /* convierte cadena en numero */
		$ls_prepro=$this->funsob->uf_convertir_cadenanumero($ls_prepro); /* convierte cadena en numero */
		//$ls_costo=$this->funsob->uf_convertir_cadenanumero($ls_costo); /* convierte cadena en numero */
		$ls_porimp=$this->funsob->uf_convertir_cadenanumero($ls_porimp); /* convierte cadena en numero */
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_nomtie=$_SESSION["ls_nomtienda"];
		//print $ls_codalm;
		$ls_sql="UPDATE sfc_detfactura
				SET  canpro=".$ls_canpro.", prepro=".$ls_prepro.", porimp=".$ls_porimp.", codalm=".$ls_codalm.",cod_pro='".$ls_cod_pro."',costo=".$ls_costo." WHERE codemp='".$ls_codemp."' AND codart='".$ls_codpro."' AND numfac='".$ls_numfac."' AND codtiend='".$ls_codtie."';";
		//$this->io_sql->begin_transaction();
		//print($ls_sql);
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			print "Error en metodo uf_update_detallesfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
		}
		else
		{
			if($li_row>0)
			{
				//*************    SEGURIDAD    **************
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizó la Factura ".$ls_numfac.", Detalle de la Factura ".$ls_codpro." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie." ".$ls_nomtie;
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],$aa_seguridad["ventanas"],$ls_descripcion);
				//**********************************************/
			//	$this->io_sql->commit();
				$lb_valido=true;
			}
			else
			{

				$this->io_sql->rollback();
			}

		}
		return $lb_valido;
	  }
  /*****************************************************************************************/
  /*********************** UPDATE ARREGLO DE DETALLES **************************************/
  /*****************************************************************************************/
	function uf_update_detallesfacturas($ls_codcli,$ls_numfac,$aa_detallesnuevos,$ai_totalfilasnuevas,$ls_codtie,$aa_seguridad)
	{
		/***************************************************************************************/
		/*	Function:	    uf_change_estatus_asi                                              */
		/* Access:			public                                                             */
		/*	Returns:		Boolean, Retorna true si realizo el cambio de estatus al registro  */
		/*	Description:	Funcion que se encarga de cambiar el estatus a la asignacion       */
		/*  Fecha:          25/03/2006                                                         */
		/*	Autor:          GERARDO CORDERO		                                               */
		/***************************************************************************************/
		require_once("class_folder/sigesp_sfc_c_secuencia.php");
		$io_function=new class_funciones();
		$lb_valido=false;
		$lb_update=false;
		//print 'pasoooooooooooooooDDDD';
		$ls_codemp=$this->datoemp["codemp"];
		$lb_valido=$this->uf_select_detallesfac($ls_numfac,$la_detallesviejos,$li_totalfilasviejas);
		$li_totalnuevas=$ai_totalfilasnuevas-1; //-1
		for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
		{
			$lb_existe=false;
			for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
			{
				if ($la_detallesviejos["codemp"][$li_j]=$ls_codemp && $la_detallesviejos["numfac"][$li_j]=$ls_numfac && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i])
				{
				  if($la_detallesviejos["canpro"][$li_j] != $aa_detallesnuevos["canpro"][$li_i])
					{
					  $lb_update=true;
					}
					$lb_existe = true;
				}

			}
			if (!$lb_existe)
			{
				$ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
				$ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
				$ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
				$ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
				$ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
				$ls_cod_pro=$aa_detallesnuevos["cod_pro"][$li_i];
				$ls_costo=$aa_detallesnuevos["costo"][$li_i];
				if ($ls_codalm=='' or strlen($ls_codalm)<10)
				{
				   if ($ls_codalm=='')
				   {
				  	 $ls_codalm=$this->$io_funcion->uf_cerosizquierda($ls_codtie,10);
				   }
				   else
				   {
				   	$ls_codalm=$this->io_funcion->uf_cerosizquierda($ls_codalm,10);
				   }
				}
				$lb_valido=$this->uf_guardar_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_codtie,$ls_cod_pro,$ls_costo,$aa_seguridad);
			}
			if ($lb_update)
			{

			$ls_codpro=$aa_detallesnuevos["codpro"][$li_i];
			$ls_canpro=$aa_detallesnuevos["canpro"][$li_i];
			$ls_prepro=$aa_detallesnuevos["prepro"][$li_i];
			$ls_porimp=$aa_detallesnuevos["porimp"][$li_i];
			$ls_codalm=$aa_detallesnuevos["codalm"][$li_i];
			$ls_cod_pro=$aa_detallesnuevos["cod_pro"][$li_i];
			$ls_costo=$aa_detallesnuevos["costo"][$li_i];
			if ($ls_codalm=='' or strlen($ls_codalm)<10)
				{
				   if ($ls_codalm=='')
				   {
				  	 $ls_codalm=$this->io_funcion->uf_cerosizquierda($ls_codtie,10);
				   }
				   else
				   {
				   	$ls_codalm=$this->io_funcion->uf_cerosizquierda($ls_codalm,10);
				   }
				}
			//print $ls_codalm;
			$lb_valido=$this->uf_update_detallesfac($ls_numfac,$ls_codpro,$ls_canpro,$ls_prepro,$ls_porimp,$ls_codalm,$ls_cod_pro,$ls_costo,$aa_seguridad);

			}


		}
		for ($li_j=1;$li_j<=$li_totalfilasviejas;$li_j++)
		{
			$lb_existe=false;
			for ($li_i=1;$li_i<=$li_totalnuevas;$li_i++)
			{
				if ($la_detallesviejos["codemp"][$li_j]==$ls_codemp && $la_detallesviejos["numfac"][$li_j]==$ls_numfac && $la_detallesviejos["codart"][$li_j] ==$aa_detallesnuevos["codpro"][$li_i])
				{
					$lb_existe = true;
				}
			}
			if (!$lb_existe)
			{
				$lb_valido=$this->uf_delete_detallesfac($ls_numfac,$la_detallesviejos["codart"][$li_j],$aa_seguridad);
			}
		}
	return $lb_valido;
	}

function uf_select_existencia($codemp, $codart, $codalm, $codtiend, $cod_pro)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_detfactura.
		// Parameters:  - $ls_numfac( N�mero de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT existencia FROM sim_articuloalmacen
		            WHERE codemp='".$ls_codemp."' AND codart='".$codart."' AND codalm='".$codalm."' AND codtiend='".$codtiend."'".
					" AND cod_pro='".$cod_pro."'";
		$rs_datauni=$this->io_sql->select($ls_cadena);
       //print $ls_cadena;
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_existencia ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$ls_exist=$row["existencia"];
			}

		}
		return $ls_exist;
}

/**ULTIMO*/
function uf_select_nota($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_nota
		            WHERE codemp='".$ls_codemp."' AND nro_documento='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}


function uf_delete_notas($ls_numfac,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (n�mero de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_nota($ls_numfac);
		$ls_codtie=$_SESSION["ls_codtienda"];
		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_nota WHERE codemp='".$ls_codemp."' AND nro_documento='".$ls_numfac."' AND codtiend='".$ls_codtie."'";


				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->is_msgc="Error en metodo uf_delete_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->is_msgc;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Eliminó las Notas de la Fatura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						//$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_sql->rollback();
					}

				}
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
}

function uf_update_notas($ls_numfac,$aa_seguridad)
	{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_delete_factura.
		// Parameters:  - $ls_numfac (n�mero de la factura).
		// Descripcion: - Funcion que elimina una factura.
		//////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$ls_codemp=$this->datoemp["codemp"];
		$lb_existe=$this->uf_select_nota($ls_numfac);

		if($lb_existe)
		{
		    	$ls_cadena= " DELETE FROM sfc_nota WHERE codemp='".$ls_codemp."' AND nro_documento='".$ls_numfac."'";


				$this->io_sql->begin_transaction();

				$li_numrows=$this->io_sql->execute($ls_cadena);

				if(($li_numrows==false)&&($this->io_sql->message!=""))
				{
					$lb_valido=false;
					$this->io_sql->rollback();
					$this->is_msgc="Error en metodo uf_delete_factura ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
					print $this->is_msgc;
				}
				else
				{
					if($li_numrows>0)
					{
						$lb_valido=true;
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$ls_evento="DELETE";
						$ls_descripcion ="Elimin� las Notas por Anulacion de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
						$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
														$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
														$aa_seguridad["ventanas"],$ls_descripcion);
						/////////////////////////////////         SEGURIDAD               /////////////////////////////
						$this->io_sql->commit();
					}
					else
					{
						$lb_valido=false;
						$this->io_sql->rollback();
					}

				}
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
}
/************************************ SELECT FACTURA RETENCION ******************************************************************/
/********************************************************************************************************************************/
function uf_select_facturaretencion_codded($ls_numfac,$ls_codded,$ls_numcob)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_facturaretencion
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codded='".$ls_codded."' AND numcob='".$ls_numcob."';";

		//print $ls_cadena."<br>";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_facturaretencion ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_select_facturaretencion_numfac($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_factura
		// Parameters:  - $ls_numfac( Codigo de la factura).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];
		$ls_cadena="SELECT * FROM sfc_facturaretencion
		            WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_facturaretencion_numfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

/********************************************************************************************************************************/
/************************************ GUARDAR FACTURA RETENCION *****************************************************************/
/********************************************************************************************************************************/
function uf_guardar_facturaretencion($ls_numfac,$ls_codded,$ls_codcli,$ls_monobjret,$ls_monret,$ls_codtie,$ls_numcob,$ls_comprobante,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_guardar_factura.
	// Parameters:  - $ls_codcli( Codigo del cliente).
	//				- $ls_numfac(Numero de la factura)
	//			    - $ls_numcot.
	//			    - $ls_codusu(codigo del usuario).
	//				- $ls_fecemi(fecha de la emision de la factura).
	//				- $ls_conpag(condicion de pago: Credito o Contado).
	//              - $ld_monto(monto a pagar).
	//              - $ld_estfac(Estado de la factura: Cancelada � No cancelada).
	// Descripcion: - Funcion que guarda el registro enviado bien sea insertar o actualizar.
	//////////////////////////////////////////////////////////////////////////////////////////
	$ls_codemp=$this->datoemp["codemp"];
	$lb_existe=$this->uf_select_facturaretencion_codded($ls_numfac,$ls_codded,$ls_numcob);
	$ld_monobjret=$this->funsob->uf_convertir_cadenanumero($ls_monobjret); /* convierte cadena en numero */
	$ld_monret=$this->funsob->uf_convertir_cadenanumero($ls_monret);

	if(!$lb_existe)
	{

        $ls_cadena= "INSERT INTO sfc_facturaretencion (codemp,numfac,codcli,codded,monobret,monret,codtiend,numcob,comprobante) " .
        		"VALUES ('".$ls_codemp."','".$ls_numfac."',".$ls_codcli.",'".$ls_codded."',".$ld_monobjret.",".$ld_monret.",'".$ls_codtie."','".$ls_numcob."','".$ls_comprobante."')";
		$ls_evento="INSERT";
	}
	else
	{
		$ls_cadena= "UPDATE sfc_facturaretencion ".
		            "SET monobret=".$ld_monobjret.", monret=".$ld_monret." ".
					 "WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."' AND codded='".$ls_codded."' AND codcli=".$ls_codcli." " .
					 " AND numcob='".$ls_numcob."' AND comprobante='".$ls_comprobante."' ;";
		$ls_evento="UPDATE";
	}

	//print $ls_cadena;
	$this->io_sql->begin_transaction();
	$li_numrows=$this->io_sql->execute($ls_cadena);


	if(($li_numrows==false)&&($this->io_sql->message!=""))
	{
		$lb_valido=false;
		$this->is_msgc="Error en metodo uf_guardar_facturaretencion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		$this->io_sql->rollback();
		print $this->is_msgc;
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
				$ls_descripcion ="Inserto la retencion ".$ls_codded.", del Cobro ".$ls_numcob." y de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
				$lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
			}
			else
			{
				/////////////////////////////////         SEGURIDAD               /////////////////////////////
				$ls_evento="UPDATE";
				$ls_descripcion ="Actualizo la retencion ".$ls_codded.", del Cobro ".$ls_numcob." de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
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
				$this->is_msgc="No actualizo el registro";
			}
			else
			{
				$lb_valido=false;
				$this->is_msgc="Registro No Incluido!!!";

			}
		}

	}
	return $lb_valido;
}
/********************************************************************************************************************************/
/************************************ BORRAR FACTURA RETENCION *****************************************************************/
/********************************************************************************************************************************/
function uf_delete_facturaretencion($ls_numfac,$aa_seguridad)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_delete_factura.
	// Parameters:  - $ls_numfac (número de la factura).
	// Descripcion: - Funcion que elimina una factura.
	//////////////////////////////////////////////////////////////////////////////////////////
	$lb_valido=false;
	$ls_codemp=$this->datoemp["codemp"];

	$lb_existe=$this->uf_select_facturaretencion_numfac($ls_numfac);

	if($lb_existe)
	{
	    	$ls_cadena= " DELETE FROM sfc_facturaretencion WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."'";
			$this->is_msgc="Registro Eliminado!!!";

			//$this->io_sql->begin_transaction();

			$li_numrows=$this->io_sql->execute($ls_cadena);

			if(($li_numrows==false)&&($this->io_sql->message!=""))
			{
				$lb_valido=false;
				$this->io_sql->rollback();
				$this->is_msgc="Error en metodo uf_delete_facturaretencion_numfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
				print $this->is_msgc;
				print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
				/*print "delete factura:".$ls_cadena;*/
			}
			else
			{
				if($li_numrows>0)
				{
					$lb_valido=true;
					/////////////////////////////////         SEGURIDAD               /////////////////////////////
					$ls_evento="DELETE";
					$ls_descripcion ="Elimino las Retenciones de la Fatura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp;
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

function uf_select_conceptosfac($ls_numcot)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_conceptosfac
		// Parameters:  - $ls_numcot( Codigo de la cotizaci�n).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////
	  $ls_codtie=$_SESSION["ls_codtienda"];
	    $ls_cadena="SELECT dc.numcot,dc.codart,dc.precot,dc.cancot,dc.impcot,a.denart,dc.cod_pro,rpc.nompro,p.moncar,dc.precot*dc.cancot as totpro,um.denunimed,aa.codalm as almacen, al.desalm,p.cosproart FROM
sim_articulo a,sfc_producto p,sfc_detcotizacion dc,sim_unidadmedida um, sim_articuloalmacen aa,sim_almacen al,rpc_proveedor rpc WHERE
a.codart=p.codart AND a.codemp=p.codemp AND a.codart=dc.codart AND a.codemp=dc.codemp AND a.codunimed=um.codunimed AND
a.codart=aa.codart AND a.codemp=aa.codemp AND a.codemp=al.codemp AND a.codemp=rpc.codemp AND p.codart=dc.codart AND p.codtiend=dc.codtiend AND
p.codemp=dc.codemp AND p.codart=aa.codart AND p.codtiend=aa.codtiend AND p.codemp=aa.codemp AND p.codemp=al.codemp AND p.codemp=rpc.codemp AND
dc.codart=aa.codart AND dc.codemp=aa.codemp AND dc.codalm=aa.codalm AND dc.codtiend=aa.codtiend AND dc.cod_pro=aa.cod_pro AND dc.codemp=al.codemp AND
dc.codalm=al.codalm AND dc.codemp=rpc.codemp AND dc.cod_pro=rpc.cod_pro AND aa.codalm=al.codalm AND aa.codemp=al.codemp AND aa.codemp=rpc.codemp AND
al.codemp=rpc.codemp AND dc.numcot='".$ls_numcot."' ORDER BY dc.codart,dc.cod_pro;";
//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_conceptosfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$la_producto=$this->io_sql->obtener_datos($rs_datauni);
				$this->io_datastore->data=$la_producto;
				$totrow=$this->io_datastore->getRowCount("numcot");
				$ls_monacu=0;
				$ls_moncaracu=0;
				//print $totrow;
				for($li_i=1;$li_i<=$totrow;$li_i++)
				{
				//print $li_i;
					$ls_codpro=$this->io_datastore->getValue("codart",$li_i);
					$ls_denpro=$this->io_datastore->getValue("denart",$li_i).' '.$this->io_datastore->getValue("denunimed",$li_i);
					$ls_preuni=$this->io_datastore->getValue("precot",$li_i);
					$ls_canpro=$this->io_datastore->getValue("cancot",$li_i);
					$ls_totpro=$this->io_datastore->getValue("totpro",$li_i);
					$ls_porcar=$this->io_datastore->getValue("impcot",$li_i);
					$ls_moncar=$this->io_datastore->getValue("moncar",$li_i);
					$ls_moncar=((($ls_porcar/100)*$ls_preuni)*$ls_canpro);
					$ls_codalm=$this->io_datastore->getValue("almacen",$li_i);
					$ls_desalm=$this->io_datastore->getValue("desalm",$li_i);
					$ls_cod_pro=$this->io_datastore->getValue("cod_pro",$li_i);
					$ls_nompro=$this->io_datastore->getValue("nompro",$li_i);
					$ls_costo=$this->io_datastore->getValue("cosproart",$li_i);
					//print 'ls_costo-->'.$ls_costo.'--->'.$ls_costo.' '.$ls_costo;
					$ls_existe=$this->uf_select_existencia($ls_codemp,$ls_codpro,$ls_codalm,$ls_codtie,$ls_cod_pro);
					if($ls_canpro>$ls_existe)
					{
					  $ls_hidbanexi="true";
					}
					$ls_monacu=$ls_monacu+$ls_totpro;
					$ls_moncaracu=$ls_moncaracu+$ls_moncar;
					$ls_prepro=number_format($ls_preuni,2, ',', '.');
					$ls_canpro=number_format($ls_canpro,2, ',', '.');
					$ls_totpro=number_format($ls_totpro,2, ',', '.');
					$ls_porcar=number_format($ls_porcar,2, ',', '.');
					//$ls_costo=number_format($ls_costo,2, ',', '.');
					$la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
		$la_objectconcepto[$li_i][7]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";
					//Si la factura es nueva se habilita la opcion de eliminar en edicion

					$la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
					$la_objectconcepto[$li_i][9]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";
					// Si la factura es nueva se habilita la opcion de eliminar en edicion
					if ($ls_estfaccon=="")
					{
					$la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
					}
					else
					{
						$la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
					}
				}// for
				$ls_subtot=number_format($ls_monacu,2, ',', '.');
				$ls_moniva=number_format($ls_moncaracu,2, ',', '.');
				$ls_monto=number_format($ls_monacu+$ls_moncaracu,2, ',', '.');
				$li_filasconcepto=$li_i;
				$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
				$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
				$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
				$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
				$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
				$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
				$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
				$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";
			}
			else
			{
				$lb_valido=false;
				//$this->is_msgc="No hay registros de productos";
			}
		}
		$la_data=array ($la_objectconcepto, $ls_subtot, $ls_moniva,$ls_monto,$li_filasconcepto);
		return $la_data;
}
function uf_select_instpagofac($ls_numfac)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_instpagofac
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	   require_once("../shared/class_folder/class_datastore.php");
		$this->io_datastore=new class_datastore();
	   $ls_cadena="SELECT scb_banco.nomban,scb_banco.codban,sfc_formapago.denforpag,sfc_formapago.metforpag,sfc_instpago.numinst, sfc_instpago.ctaban,sfc_instpago.codforpag,sfc_instpago.monto,sfc_instpago.numfac,sfc_instpago.id_entidad FROM scb_banco,sfc_formapago,sfc_instpago WHERE sfc_instpago.numfac='".$ls_numfac."' AND scb_banco.codban=sfc_instpago.codban AND sfc_instpago.codforpag=sfc_formapago.codforpag;";
		$rs_datauni=$this->io_sql->select($ls_cadena);

		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_instpagofac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$la_producto=$this->io_sql->obtener_datos($rs_datauni);
				$this->io_datastore->data=$la_producto;
				$totrow=$this->io_datastore->getRowCount("numfac");
				for($li_j=1;$li_j<=$totrow;$li_j++)
				{
					$ls_codforpag=$this->io_datastore->getValue("codforpag",$li_j);
					$ls_denforpag=$this->io_datastore->getValue("denforpag",$li_j);
					$ls_numinst=$this->io_datastore->getValue("numinst",$li_j);
					$ls_nombanco=$this->io_datastore->getValue("nomban",$li_j);
					$ls_codban=$this->io_datastore->getValue("codban",$li_j);
					$ls_montoforpag=$this->io_datastore->getValue("monto",$li_j);
					$ls_montoforpag=number_format($ls_montoforpag,2, ',', '.');
					$ls_codent=$this->io_datastore->getValue("id_entidad",$li_j);
					$ls_metforpago=$this->io_datastore->getValue("metforpag",$li_j);
					$ls_ctaban=$this->io_datastore->getValue("ctaban",$li_j);

					$la_objectfpago[$li_j][1]="<input name=txtcodforpag".$li_j." type=text id=txtcodforpag".$li_j." value='".$ls_codforpag."' class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_j." type=hidden id=txtmetforpag".$li_j." value='".$ls_metforpago."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_j." type=hidden id=txtcodent".$li_j."value='".$ls_codent."'class=sin-borde size=15 style= text-align:center readonly>";
					$la_objectfpago[$li_j][2]="<input name=txtdenforpag".$li_j." type=text id=txtdenforpag".$li_j." value='".$ls_denforpag."' class=sin-borde size=45 style= text-align:left readonly>";
					$la_objectfpago[$li_j][3]="<input name=txtnuminst".$li_j." type=text id=txtnuminst".$li_j." value='".$ls_numinst."' class=sin-borde size=20 style= text-align:center readonly>";
					$la_objectfpago[$li_j][4]="<input name=txtnombanco".$li_j." type=text id=txtnombanco".$li_j." value='".$ls_nombanco."' class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_j." type=hidden id=txtcodban".$li_j." value='".$ls_codban."' class=sin-borde size=15 style= text-align:center readonly>";
					$la_objectfpago[$li_j][5]="<input name=txtctabanco".$li_j." type=text id=txtctabanco".$li_j."  value='".$ls_ctaban."' class=sin-borde size=25 style= text-align:left readonly>";
					$la_objectfpago[$li_j][6]="<input name=txtmontoforpag".$li_j." type=text id=txtmontoforpag".$li_j." value='".$ls_montoforpag."' class=sin-borde size=15 style= text-align:center readonly>";

					// Si la factura es nueva se habilita la opcion de eliminar en edicion
		      		 if ($ls_estfaccon=="")
				 	{
						$la_objectfpago[$li_j][7]="<a href=javascript:ue_removerfpago(".$li_j.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
				  	}
					else
				 	{
						$la_objectfpago[$li_j][7]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center>";
				   }
		   	 	 }//for
				$li_filasfpago=$li_j;
				$la_objectfpago[$li_filasfpago][1]="<input name=txtcodforpag".$li_filasfpago." type=text id=txtcodforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtmetforpag".$li_filasfpago." type=hidden id=txtmetforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodent".$li_filasfpago." type=hidden id=txtcodent".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][2]="<input name=txtdenforpag".$li_filasfpago." type=text id=txtdenforpag".$li_filasfpago." class=sin-borde size=45 style= text-align:left readonly>";
				$la_objectfpago[$li_filasfpago][3]="<input name=txtnuminst".$li_filasfpago." type=text id=txtnuminst".$li_filasfpago." class=sin-borde size=20 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][4]="<input name=txtnombanco".$li_filasfpago." type=text id=txtnombanco".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly><input name=txtcodban".$li_filasfpago." type=hidden id=txtcodban".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";
				$la_objectfpago[$li_filasfpago][5]="<input name=txtctabanco".$li_filasfpago." type=text id=txtctabanco".$li_filasfpago." class=sin-borde size=25 style= text-align:left readonly>";
				$la_objectfpago[$li_filasfpago][6]="<input name=txtmontoforpag".$li_filasfpago." type=text id=txtmontoforpag".$li_filasfpago." class=sin-borde size=15 style= text-align:center readonly>";				
				$la_objectfpago[$li_filasfpago][7]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style= text-align:center size=5 readonly>";
			}
			else
			{
				$lb_valido=false;
				//$this->is_msgc="No hay registros de productos";
			}
		}
		$la_data=array ($la_objectfpago,$li_filasfpago);
		return $la_data;
}
function uf_select_profac($ls_numfac)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_profac
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		$io_includeinst = new sigesp_include();
		$io_connect = $io_includeinst->uf_conectar();
		$this->io_sqlints= new class_sql($io_connect);
		$ls_codtie=$_SESSION["ls_codtienda"];
		require_once("../shared/class_folder/class_datastore.php");
		$this->io_data=new class_datastore();
		$ls_cadena="SELECT df.numfac,df.codart,a.denart,um.codunimed,um.denunimed,df.prepro,df.canpro,df.porimp,df.codalm,al.desalm,df.cod_pro,rpc.nompro,df.costo FROM sfc_detfactura df,sim_articulo a,sim_unidadmedida um,sim_almacen al,rpc_proveedor rpc,sim_articuloalmacen aa
WHERE df.codart=a.codart AND df.codemp=a.codemp AND df.codemp=al.codemp AND df.codemp=rpc.codemp AND df.codart=aa.codart AND
df.codtiend=aa.codtiend AND df.codemp=aa.codemp AND df.codalm=aa.codalm AND df.cod_pro=aa.cod_pro AND a.codunimed=um.codunimed AND
a.codemp=al.codemp AND a.codemp=rpc.codemp AND a.codart=aa.codart AND a.codemp=aa.codemp AND al.codemp=rpc.codemp AND
al.codemp=aa.codemp AND al.codalm=aa.codalm AND rpc.cod_pro=aa.cod_pro AND rpc.codemp=aa.codemp AND df.numfac like '".$ls_numfac."'
ORDER BY df.codart";
		//print $ls_cadena;

		$rs_datauni=$this->io_sqlints->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_instpagofac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sqlints->fetch_row($rs_datauni))
			{
				$la_producto=$this->io_sqlints->obtener_datos($rs_datauni);
				$this->io_data->data=$la_producto;
				$totrow=$this->io_data->getRowCount("numfac");
				$ls_subtotA=0;
				$ls_monivaA=0;
				$li_fila=0;
				//print $totrow;
					for($li_i=1;$li_i<=$totrow;$li_i++)
					{

						$ls_codpro=$this->io_data->getValue("codart",$li_i);
		                $ls_denpro=$this->io_data->getValue("denart",$li_i).' '.$this->io_data->getValue("denunimed",$li_i);
						$ls_prepro=$this->io_data->getValue("prepro",$li_i);
						$ls_canpro=$this->io_data->getValue("canpro",$li_i);
						$ls_porcar=$this->io_data->getValue("porimp",$li_i);
						//$ls_moncar=$this->io_data->getValue("moncar",$li_i);
						$ls_totpro=$ls_prepro*$ls_canpro;
						$ls_totcar=((($ls_porcar/100)*$ls_prepro)*$ls_canpro);
						$ls_subtotA=$ls_subtotA+$ls_totpro;
						$ls_monivaA=$ls_monivaA+$ls_totcar;
						$ls_costo=$this->io_data->getValue("costo",$li_i);
						//$ls_costo=number_format($ls_costo,'', ',', '.');
						//print $ls_costo;
						$ls_prepro=number_format($ls_prepro,2, ',', '.');
						$ls_canpro=number_format($ls_canpro,2, ',', '.');
						$ls_moncar=number_format($ls_moncar,2, ',', '.');
						$ls_porcar=number_format($ls_porcar,2, ',', '.');
						$ls_totpro=number_format($ls_totpro,2, ',', '.');
						$ls_codalm=$this->io_data->getValue("codalm",$li_i);
						$ls_desalm=$this->io_data->getValue("desalm",$li_i);
						$ls_cod_pro=$this->io_data->getValue("cod_pro",$li_i);
						$ls_nompro=$this->io_data->getValue("nompro",$li_i);
						$ls_existe=$this->uf_select_existencia($ls_codemp,$ls_codpro,$ls_codalm,$ls_codtie,$ls_cod_pro);
		               $la_objectconcepto[$li_i][1]="<input name=txtcodpro".$li_i." type=text id=txtcodpro".$li_i." value='".$ls_codpro."' class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_i." type=hidden id=txtexiste".$li_i." value='".$ls_existe."'>";
		$la_objectconcepto[$li_i][2]="<input name=txtdenpro".$li_i." type=text id=txtdenpro".$li_i." value='".$ls_denpro."' class=sin-borde size=45 style= text-align:left readonly>";
		$la_objectconcepto[$li_i][3]="<input name=txtcodalm".$li_i." type=text id=txtcodalm".$li_i." value='".$ls_codalm."' class=sin-borde size=11 maxlength=10 style= text-align:left>";
		$la_objectconcepto[$li_i][4]="<input name=txtdesalm".$li_i." type=text id=txtdesalm".$li_i." value='".$ls_desalm."' class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
		$la_objectconcepto[$li_i][5]="<input name=txtnompro".$li_i." type=text id=txtnompro".$li_i." value='".$ls_nompro."' class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_i." type=hidden id=txtcod_pro".$li_i." value='".$ls_cod_pro."' class=sin-borde size=10 style= text-align:right readonly>";
		$la_objectconcepto[$li_i][6]="<input name=txtprepro".$li_i." type=text id=txtprepro".$li_i." value='".$ls_prepro."' class=sin-borde size=10 style= text-align:center onKeyPress=return(currencyFormat(this,'.',',',event))><input name=txtcosto".$li_i." type=hidden id=txtcosto".$li_i." class=sin-borde value='".$ls_costo."' style= text-align:center readonly>";
		$la_objectconcepto[$li_i][7]="<input name=txtporcar".$li_i." type=text id=txtporcar".$li_i." value='".$ls_porcar."' class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_i." type=hidden id=txtmoncar".$li_i." value='".$ls_moncar."' class=sin-borde style= text-align:center readonly>";

                        //Si es nueva, habilita edicion
						if ($ls_estfaccon=="" && $ls_operacion!="ue_guardar")
		                 {
		                    $la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right onKeyPress=return(currencyFormat(this,'.',',',event))>";
		                 }
		                 else
		                 {
		                    $la_objectconcepto[$li_i][8]="<input name=txtcanpro".$li_i." type=text id=txtcanpro".$li_i." value='".$ls_canpro."' class=sin-borde size=10 style= text-align:right readonly>";
		                 }
		                $la_objectconcepto[$li_i][9]="<input name=txttotpro".$li_i." type=text id=txttotpro".$li_i." value='".$ls_totpro."' class=sin-borde size=12 style= text-align:right readonly>";

		                //Si es nueva, habilita edicion
						if ($ls_estfaccon=="")
		                 {
		                    $la_objectconcepto[$li_i][10]="<a href=javascript:ue_removerconcepto(".$li_i.");><img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center></a>";
                         }
		                 else
		                 {
		                   $la_objectconcepto[$li_i][10]="<img src=../shared/imagebank/tools15/eliminar.gif alt=Aceptar width=15 height=15 border=0 style= text-align:center readonly>";
                         }
						 $li_fila++;
					 }//fin del for
					$li_filasconcepto=$li_i;
					$ls_subtot=number_format($ls_subtotA,2, ',', '.');
					$ls_moniva=number_format($ls_monivaA,2, ',', '.');

				   $la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
				$la_objectconcepto[$li_filasconcepto][1]="<input name=txtcodpro".$li_filasconcepto." type=text id=txtcodpro".$li_filasconcepto." class=sin-borde size=21 style= text-align:center readonly><input name=txtexiste".$li_filasconcepto." type=hidden id=txtexiste".$li_filasconcepto.">";
				$la_objectconcepto[$li_filasconcepto][2]="<input name=txtdenpro".$li_filasconcepto." type=text id=txtdenpro".$li_filasconcepto." class=sin-borde size=45 style= text-align:left readonly>";
				$la_objectconcepto[$li_filasconcepto][3]="<input name=txtcodalm".$li_filasconcepto." type=text id=txtcodalm".$li_filasconcepto." class=sin-borde size=11 maxlength=10 style= text-align:left>";
				$la_objectconcepto[$li_filasconcepto][4]="<input name=txtdesalm".$li_filasconcepto." type=text id=txtdesalm".$li_filasconcepto." class=sin-borde size=20 maxlength=255 style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][5]="<input name=txtnompro".$li_filasconcepto." type=text id=txtnompro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcod_pro".$li_filasconcepto." type=hidden id=txtcod_pro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly>";
				$la_objectconcepto[$li_filasconcepto][6]="<input name=txtprepro".$li_filasconcepto." type=text id=txtprepro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right readonly><input name=txtcosto".$li_filasconcepto." type=hidden id=txtcosto".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][7]="<input name=txtporcar".$li_filasconcepto." type=text id=txtporcar".$li_filasconcepto." class=sin-borde size=2 style= text-align:right readonly><input name=txtmoncar".$li_filasconcepto." type=hidden id=txtmoncar".$li_filasconcepto." class=sin-borde style= text-align:center readonly>";
				$la_objectconcepto[$li_filasconcepto][8]="<input name=txtcanpro".$li_filasconcepto." type=text id=txtcanpro".$li_filasconcepto." class=sin-borde size=10 style= text-align:right>";
				$la_objectconcepto[$li_filasconcepto][9]="<input name=txttotpro".$li_filasconcepto." type=text id=txttotpro".$li_filasconcepto." class=sin-borde size=12 style= text-align:right readonly>";
				$la_objectconcepto[$li_filasconcepto][10]="<input name=txtvacio2 type=text id=txtvacio2 class=sin-borde style=text-align:center size=5 readonly>";
			}
			else
			{
				$lb_valido=false;
				//$this->is_msgc="No hay registros de productos";
			}
		}
		$la_data=array ($la_objectconcepto,$ls_subtot,$ls_moniva,$li_filasconcepto);

		return $la_data;
}
function uf_select_estfaccon($ls_codemp,$ls_numfac)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_estfaccon
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// 				- $ls_codemp ( Codigo de la Empresa ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		 $ls_codemp=$this->datoemp["codemp"];
		$ls_codtie=$_SESSION["ls_codtienda"];
		$ls_cadena="SELECT f.estfaccon,f.estfac FROM sfc_factura f WHERE codemp='".$ls_codemp."' AND numfac='".$ls_numfac."';";
		//print $ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_select_instpagofac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$la_producto=$this->io_sql->obtener_datos($rs_datauni);
				$this->io_datastore->data=$la_producto;
				$ls_estfaccon=$this->io_datastore->getValue("estfaccon",1);
				$ls_estfac=$this->io_datastore->getValue("estfac",1);
			}
			else
			{
				$lb_valido=false;
				//$this->is_msgc="No hay registros";
			}
		}
		$la_data=array ($ls_estfaccon,$ls_estfac);
		return $la_data;
}

function uf_verificar_cobrosfaccarta($ls_codemp,$ls_numinstpago,$ls_codcli)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_estfaccon
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// 				- $ls_codemp ( Codigo de la Empresa ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		$ls_cadena="SELECT dt.numcartaorden FROM sfc_dt_cobrocartaorden dt,sfc_cobrocartaorden cc WHERE ".
		" dt.numcob=cc.numcob AND dt.codemp=cc.codemp AND dt.codban=cc.codban AND dt.codtiend=cc.codtiend".
		" AND dt.numcartaorden like '".$ls_numinstpago."' AND dt.codcli like '".$ls_codcli."' AND dt.codemp like '".$ls_codemp."'";
		//print 'carta orden->'.$ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_verificar_cobrosfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				//$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_verificar_cobrosfac($ls_codemp,$ls_numfac,$ls_codcli)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_estfaccon
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// 				- $ls_codemp ( Codigo de la Empresa ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		$ls_cadena="SELECT cc.numcob FROM sfc_cobro_cliente cc,sfc_dt_cobrocliente dt WHERE cc.numcob=dt.numcob ".
		"AND cc.estcob <> 'A' AND cc.codemp=dt.codemp AND cc.codcli=dt.codcli AND cc.codtiend=dt.codtiend AND dt.numfac = '".$ls_numfac."'".
		" AND dt.codcli = '".$ls_codcli."' AND dt.codemp='".$ls_codemp."'";
                
		//print 'factura->'.$ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_verificar_cobrosfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				//$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}

function uf_verificar_devoluciones($ls_codemp,$ls_numfac,$ls_codcli)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_estfaccon
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// 				- $ls_codemp ( Codigo de la Empresa ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		$ls_cadena="SELECT d.coddev FROM sfc_devolucion d,sfc_factura f WHERE ".
		" d.codemp=f.codemp AND d.codtiend=f.codtiend AND d.numfac=f.numfac AND ".
		" d.numfac like '".$ls_numfac."'".
		" AND f.codcli like '".$ls_codcli."' AND d.codemp='".$ls_codemp."' AND d.estdev<>'A'";
		//print 'devolucion->'.$ls_cadena;
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_verificar_cobrosfac ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
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
				//$this->is_msgc="Registro no encontrado";
			}
		}
		return $lb_valido;
}
function uf_buscarcli($ls_codemp,$ls_cedcli)
{
		//////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_profac
		// Parameters:  - $ls_numfac( N�mero de la Factura ).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

		 $ls_cadena="SELECT codcli,razcli,cedcli FROM sfc_cliente WHERE codemp='".$ls_codemp."' AND cedcli='".$ls_cedcli."';";
		$rs_datauni=$this->io_sql->select($ls_cadena);
		if($rs_datauni==false)
		{
			$lb_valido=false;
			$this->is_msgc="Error en uf_buscarcli ".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_datauni))
			{
				$la_producto=$this->io_sql->obtener_datos($rs_datauni);
				$this->io_datastore->data=$la_producto;
				$totrow=$this->io_datastore->getRowCount("cedcli");
				if($totrow!=0)
				{
				  $ls_codcli=$this->io_datastore->getValue("codcli",1);
				  $ls_nomcli=$this->io_datastore->getValue("razcli",1);
				  $ls_cedcli=$this->io_datastore->getValue("cedcli",1);
				}
			}
			else
			{
				$lb_valido=false;
				//$this->is_msgc="No hay registros de Cliente";
			}
		}
		$la_data=array ($ls_codcli,$ls_nomcli,$ls_cedcli);
		return $la_data;
}
function uf_actualizar_estatusfactura_devolucion($ls_codcli,$ls_numfac,$ls_codtie,$la_seguridad)
{
	    //////////////////////////////////////////////////////////////////////////////////////////
		// Function:    - uf_select_nota
		// Parameters:  - $ls_numnot( Codigo de la nota de credito).
		// Descripcion: - Funcion que busca un registro en la bd.
		//////////////////////////////////////////////////////////////////////////////////////////

	    $ls_codemp=$this->datoemp["codemp"];


		$ls_cadena="UPDATE sfc_factura" .
					" SET estfaccon='C' WHERE codemp='".$ls_codemp."' AND codcli='".$ls_codcli."' AND codtiend='".$ls_codtie."'" .
					" AND numfac='".$ls_numfac."';";
					//print $ls_cadena."<br>";
		$li_numrows=$this->io_sql->execute($ls_cadena);
		if(($li_numrows==false)&&($this->io_sql->message!=""))
		{
			$lb_valido=false;
			$this->is_msgc="Error en metodo uf_actualizar_estatusfactura_devolucion".$this->io_funcion->uf_convertirmsg($this->io_sql->message);
			$this->io_sql->rollback();
			print $this->is_msgc;
			print $this->io_funcion->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($li_numrows>0)
			{
				$lb_valido=true;

				/************    SEGURIDAD    **************/
				  $ls_evento="UPDATE";
				  $ls_descripcion ="Actualizó estatus de la Factura ".$ls_numfac." Asociado a la Empresa ".$ls_codemp." de la Agrotienda ".$ls_codtie."  por proceso de facturación";
				   $lb_variable= $this->seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],$la_seguridad["ventanas"],$ls_descripcion);
				/**********************************************/
				//$this->io_sql->commit();
			}
			else
			{
				$this->io_sql->rollback();

			}

		}
		return $lb_valido;
}
}/*FIN DE LA CLASE */
?>

<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("sigesp_siv_c_movimientoinventario.php");
require_once("../shared/class_folder/sigesp_c_reconvertir_monedabsf.php");

class sigesp_siv_c_despacho
{
	var $obj="";
	var $io_sql;
	var $siginc;
	var $con;
	var $ls_codemp;

	function sigesp_siv_c_despacho()
	{
		$in=              new sigesp_include();
		$this->con=       $in->uf_conectar();
		$this->io_sql=    new class_sql($this->con);
		$this->seguridad= new sigesp_c_seguridad();
		$this->fun=       new class_funciones_db($this->con);
		$this->DS=        new class_datastore();
		$this->io_funcion=new class_funciones();
		$this->io_mov=    new sigesp_siv_c_movimientoinventario();
		$this->io_msg=    new class_mensajes();
		$this->ls_gestor=   $_SESSION["ls_gestor"];
		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
	}
	

	function uf_siv_obtener_dt_solicitud($as_codemp,$as_numsol,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_solicitud
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numsol    // numero de la solicitud de ejecución presupuestaria
		//                 $ai_totrows   // total de filas encontradas
		//                 $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description:	Funcion que busca los articulos asociados a una solicitud de ejecucion presupuestaria  en la tabla
		//                	de sep_dt_articulos, e igualmante busca las denominaciones  de los articulos en la tabla siv_articulo
		//				  	para luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/02/2006 								Fecha Última Modificación :08/02/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$li_selmay="";
		$li_seldet="";
		$ls_sql="SELECT sep_dt_articulos.*,".
				"      (SELECT trim(sc_cuenta)".
				"         FROM spg_cuentas".
				"        WHERE spg_cuentas.codemp=sep_dt_articulos.codemp".
				"          AND spg_cuentas.codestpro1=sep_dt_articulos.codestpro1".
				"          AND spg_cuentas.codestpro2=sep_dt_articulos.codestpro2".
				"          AND spg_cuentas.codestpro3=sep_dt_articulos.codestpro3".
				"          AND spg_cuentas.codestpro4=sep_dt_articulos.codestpro4".
				"          AND spg_cuentas.codestpro5=sep_dt_articulos.codestpro5".
				"          AND spg_cuentas.spg_cuenta=sep_dt_articulos.spg_cuenta) as sc_cuentasep".
				"  FROM sep_dt_articulos".
				" WHERE codemp='". $as_codemp ."'".
				"   AND numsol='". $as_numsol ."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_solicitud_I ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_codart=  $row["codart"];
				$li_cansol=  $row["canart"];
				$ls_unidad=  $row["unidad"];
				$ls_ctasep=  $row["sc_cuentasep"];
				$ls_sql= "SELECT siv_articulo.denart,siv_articulo.sc_cuenta,".
						 "       (SELECT unidad FROM siv_unidadmedida ".
						 "         WHERE siv_articulo.codunimed=siv_unidadmedida.codunimed) AS unidad, ".
						 "       (SELECT tipart FROM siv_tipoarticulo ".
						 "         WHERE siv_articulo.codtipart=siv_tipoarticulo.codtipart) AS tipart ".
				         "  FROM siv_articulo".
						 " WHERE codemp='". $as_codemp ."'".
						 "   AND codart='".$ls_codart."'"; 
				$rs_data1=$this->io_sql->select($ls_sql);
				if($rs_data1===false)
				{
					$lb_valido=false;
					$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_solicitud_II ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
				}
				else
				{
					$ai_totrows=$ai_totrows+1;
					if($row=$this->io_sql->fetch_row($rs_data1))
					{
						$ls_denart= $row["denart"];
						$li_unidad= $row["unidad"];
						$ls_clasif=$row["tipart"];
						$ls_ctagas= trim($row["sc_cuenta"]);
						if($ls_unidad=="M")
						{
							$ls_unidad="Mayor";
							$li_canpendes=($li_cansol*$li_unidad);
							$li_selmay="selected";
						}
						else
						{
							$ls_unidad="Detal";
							$li_canpendes=$li_cansol;
							$li_seldet="selected";
						}				

						$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."     type=text     id=txtdenart".$ai_totrows."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
												   "<input name=txtcodart".$ai_totrows."     type=hidden   id=txtcodart".$ai_totrows."    class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>".
												   "<input name=txtctagas".$ai_totrows."     type=hidden   id=txtctagas".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_ctagas."' readonly>".
												   "<input name=txtctasep".$ai_totrows."     type=hidden   id=txtctasep".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_ctasep."' readonly>";
						$ao_object[$ai_totrows][2]="<input name=txtcodalm".$ai_totrows."     type=text     id=txtcodalm".$ai_totrows."    class=sin-borde size=13 maxlength=10 readonly><a href='javascript: ue_catalmacen(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
						$ao_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:60px '><option value=Detal ".$li_seldet.">Detal</option><option value=Mayor ".$li_selmay.">Mayor</option></select></div>".
												   "<input name=hidunidad".$ai_totrows."     type=hidden   id=hidunidad".$ai_totrows."    value='". $li_unidad ."'>".
												   "<input name=txtunidad".$ai_totrows."     type=hidden   id=txtunidad".$ai_totrows."    value='". $ls_unidad ."'>";
						$ao_object[$ai_totrows][4]="<input name=txtcansol".$ai_totrows."     type=text     id=txtcansol".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".number_format ($li_cansol,2,",",".")."' style='text-align:right' readonly>".
												   "<input name=hidexistencia".$ai_totrows." type=hidden   id=hidexistencia".$ai_totrows.">";
						$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."     type=text     id=txtpenart".$ai_totrows."    class=sin-borde size=12 maxlength=12 style='text-align:right' readonly>".
												   "<input name=txthidpenart".$ai_totrows."  type=hidden   id=txthidpenart".$ai_totrows." class=sin-borde size=12 value='".$li_canpendes."'>";
						$ao_object[$ai_totrows][6]="<input name=txtcanart".$ai_totrows."     type=text     id=txtcanart".$ai_totrows."    class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));  onBlur='javascript: ue_montosfactura(".$ai_totrows.");' style='text-align:right'>";
						$ao_object[$ai_totrows][7]="<input name=txtpreuniart".$ai_totrows."  type=text     id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly><input name=hidnumdocori".$ai_totrows." type=hidden id=hidnumdocori".$ai_totrows.">";
						$ao_object[$ai_totrows][8]="<input name=txtmontotart".$ai_totrows."  type=text     id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
					    $ao_object[$ai_totrows][9]="<a href=javascript:uf_dt_activo(".$ai_totrows.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0><input name=hclasi".$ai_totrows." type=hidden id=hclasi".$ai_totrows." class=sin-borde size=15 maxlength=25 value='".$ls_clasif."' readonly></a></a>".			
   						                          " <input type=hidden name=hcodact".$ai_totrows."    id=hcodact".$ai_totrows." class=sin-borde size=15 maxlength=25  readonly>";

					}
				}
			}//while($row=$this->io_sql->fetch_row($li_exec))
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end  function uf_siv_obtener_dt_solicitud

	function uf_siv_obtener_dt_pendiente($as_codemp,$as_numsol,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_pendiente
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numsol    // numero de la solicitud de ejecución presupuestaria
		//                 $ai_totrows   // total de filas encontradas
		//                 $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a una solicitud de ejecucion presupuestaria  en la tabla
		//                 de sep_dt_articulos, e igualmante busca las denominaciones  de los articulos en la tabla siv_articulo
		//				   para luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 04/01/2007 								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$li_selmay="";
		$li_seldet="";
		$ls_sql="SELECT sep_dt_articulos.*,siv_dt_despacho.numorddes,".
				"      (SELECT sc_cuenta".
				"         FROM spg_cuentas".
				"        WHERE spg_cuentas.codemp=sep_dt_articulos.codemp".
				"          AND spg_cuentas.codestpro1=sep_dt_articulos.codestpro1".
				"          AND spg_cuentas.codestpro2=sep_dt_articulos.codestpro2".
				"          AND spg_cuentas.codestpro3=sep_dt_articulos.codestpro3".
				"          AND spg_cuentas.codestpro4=sep_dt_articulos.codestpro4".
				"          AND spg_cuentas.codestpro5=sep_dt_articulos.codestpro5".
				"          AND spg_cuentas.spg_cuenta=sep_dt_articulos.spg_cuenta) as sc_cuentasep,".
				"      (SELECT canpenart".
				"         FROM siv_dt_despacho".
				"        WHERE siv_despacho.codemp=siv_dt_despacho.codemp".
				"          AND siv_despacho.numorddes=siv_dt_despacho.numorddes".
				"          AND sep_dt_articulos.codart=siv_dt_despacho.codart) AS canpenart".
				"  FROM sep_dt_articulos,siv_despacho,siv_dt_despacho".
				" WHERE sep_dt_articulos.codemp='". $as_codemp ."'".
				"   AND sep_dt_articulos.numsol='". $as_numsol ."'".
				"   AND sep_dt_articulos.codemp=siv_despacho.codemp".
				"   AND sep_dt_articulos.numsol=siv_despacho.numsol".
				"   AND siv_despacho.estrevdes=1".
//				"   AND sep_dt_articulos.codart=siv_dt_despacho.codart".
				"   AND siv_despacho.codemp=siv_dt_despacho.codemp".
				"   AND siv_despacho.numorddes=siv_dt_despacho.numorddes".
				" GROUP BY siv_dt_despacho.numorddes,sep_dt_articulos.codart,sep_dt_articulos.codemp,sep_dt_articulos.numsol,sep_dt_articulos.canart,".
				" sep_dt_articulos.unidad,sep_dt_articulos.monpre,sep_dt_articulos.monart,sep_dt_articulos.orden,sep_dt_articulos.codestpro1, ".
				" sep_dt_articulos.codestpro2,sep_dt_articulos.codestpro3,sep_dt_articulos.codestpro4,sep_dt_articulos.codestpro5, ". 
				" sep_dt_articulos.estcla,sep_dt_articulos.spg_cuenta,sep_dt_articulos.monartaux,   ".
				" sep_dt_articulos.estincite,sep_dt_articulos.numdocdes,siv_despacho.codemp,siv_despacho.numorddes,sep_dt_articulos.monpreaux    ".
				" ORDER BY siv_dt_despacho.numorddes DESC";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data==false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_pendiente_I ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			$ai_totrows=0;
			$ls_numorddesaux="";
			while($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_numorddes=$row["numorddes"];
				if(($ls_numorddesaux=="")||($ls_numorddes==$ls_numorddesaux))
				{
					$ls_numorddesaux=$ls_numorddes;
					$ls_codart=  $row["codart"];
					$ls_cansol=  $row["canart"];
					$ls_unidad=  $row["unidad"];
					$ls_ctasep=  $row["sc_cuentasep"];
					$li_canpenart=$row["canpenart"];
					$ls_sql= "SELECT siv_articulo.*,".
							 "       (SELECT unidad FROM siv_unidadmedida ".
							 "         WHERE siv_articulo.codunimed=siv_unidadmedida.codunimed) AS unidad".
							 "  FROM siv_articulo".
							 " WHERE codemp='". $as_codemp ."'".
							 "   AND codart='". $ls_codart ."'";
					$rs_data1=$this->io_sql->select($ls_sql);
					if($rs_data1===false)
					{
						$lb_valido=false;
						$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_pendiente_II ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
					}
					else
					{
						$ai_totrows=$ai_totrows+1;
						if($row=$this->io_sql->fetch_row($rs_data1))
						{
							$ls_denart= $row["denart"];
							$li_unidad= $row["unidad"];
							$ls_ctagas= $row["sc_cuenta"];
							$li_canpendes=$li_canpenart;
							if($ls_unidad=="M")
							{
								$ls_unidad="Mayor";
								$li_canpendes=($li_canpenart/$li_unidad);
								$li_selmay="selected";
							}
							else
							{

								$ls_unidad="Detal";
								$li_seldet="selected";
							}				
							if($li_canpenart=="")
							{
								$li_canpendes=$ls_cansol;
								$li_canpenart=$li_canpendes;
								if($ls_unidad=="Mayor")
								{
									$li_canpenart=($li_canpendes*$li_unidad);
								}
							}
							$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."     type=text     id=txtdenart".$ai_totrows."    class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
													   "<input name=txtcodart".$ai_totrows."     type=hidden   id=txtcodart".$ai_totrows."    class=sin-borde size=15 maxlength=15 value='".$ls_codart."' readonly>".
													   "<input name=txtctagas".$ai_totrows."     type=hidden   id=txtctagas".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_ctagas."' readonly>".
													   "<input name=txtctasep".$ai_totrows."     type=hidden   id=txtctasep".$ai_totrows."    class=sin-borde size=15 maxlength=50 value='".$ls_ctasep."' readonly>";
							$ao_object[$ai_totrows][2]="<input name=txtcodalm".$ai_totrows."     type=text     id=txtcodalm".$ai_totrows."    class=sin-borde size=13 maxlength=10 readonly><a href='javascript: ue_catalmacen(".$ai_totrows.");'><img src='../shared/imagebank/tools15/buscar.gif' alt='Codigo de Articulo' width='18' height='18' border='0'></a>";
							$ao_object[$ai_totrows][3]="<div align='center'><select name=cmbunidad".$ai_totrows." style='width:60px '><option value=Detal ".$li_seldet.">Detal</option><option value=Mayor ".$li_selmay.">Mayor</option></select></div>".
													   "<input name=hidunidad".$ai_totrows."     type=hidden   id=hidunidad".$ai_totrows."    value='". $li_unidad ."'>".
													   "<input name=txtunidad".$ai_totrows."     type=hidden   id=txtunidad".$ai_totrows."    value='". $ls_unidad ."'>";
							$ao_object[$ai_totrows][4]="<input name=txtcansol".$ai_totrows."     type=text     id=txtcansol".$ai_totrows."    class=sin-borde size=12 maxlength=12 value='".number_format ($ls_cansol,2,",",".")."' style='text-align:right' readonly>".
													   "<input name=hidexistencia".$ai_totrows." type=hidden   id=hidexistencia".$ai_totrows.">";
							$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."     type=text     id=txtpenart".$ai_totrows."    class=sin-borde size=12 maxlength=12 style='text-align:right' value='".number_format ($li_canpendes,2,",",".")."' readonly>".
													   "<input name=txthidpenart".$ai_totrows."  type=hidden   id=txthidpenart".$ai_totrows."    class=sin-borde size=12 value='".$li_canpenart."'>";
							$ao_object[$ai_totrows][6]="<input name=txtcanart".$ai_totrows."     type=text     id=txtcanart".$ai_totrows."    class=sin-borde size=12 maxlength=12 onKeyPress=return(ue_formatonumero(this,'.',',',event));  onBlur='javascript: ue_montosfactura(".$ai_totrows.");' style='text-align:right'>";
							$ao_object[$ai_totrows][7]="<input name=txtpreuniart".$ai_totrows."  type=text     id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly><input name=hidnumdocori".$ai_totrows." type=hidden id=hidnumdocori".$ai_totrows.">";
							$ao_object[$ai_totrows][8]="<input name=txtmontotart".$ai_totrows."  type=text     id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 style='text-align:right' readonly>";
						    $ao_object[$ai_totrows][9]="<a href=javascript:uf_dt_activo(".$ai_totrows.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0></a>".			
   						                          " <input type=hidden name=hcodact".$ai_totrows."    id=hcodact".$ai_totrows." class=sin-borde size=15 maxlength=25  readonly>";

						}
					}
				}
				else
				{
					break;
					if($ai_totrows>0)
					{$lb_valido=true;}
				}
			}//while($row=$this->io_sql->fetch_row($li_exec))
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($rs_data);
		return $lb_valido;
	} // end  function uf_siv_obtener_dt_pendiente

	function uf_siv_select_despacho($as_codemp,$as_numorddes)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_select_despacho
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numorddes // numero de la orden de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que exista un maestro de despacho segun el numero de despacho, en la tabla siv_despacho
		//                 de sep_dt_articulos, e igualmante busca las denominaciones  de los articulos en la tabla siv_articulo
		//				   para luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 08/02/2006 								Fecha Última Modificación :08/02/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT * FROM siv_despacho  ".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND numorddes='".$as_numorddes."'";
		$li_exec=$this->io_sql->select($ls_sql);
		if($li_exec===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_select_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($li_exec))
			{
				$lb_valido=true;
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($li_exec);
		}
		return $lb_valido;
	} // end  function uf_siv_select_despacho

	function uf_siv_insert_despacho($as_codemp,&$as_numorddes,$as_numsol,$as_coduniadm,$ad_fecdes,$as_obsdes,$as_codusu,
								    $as_estdes,$as_estrevdes,$as_codunides,$aa_seguridad) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_despacho
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp //codigo de empresa 					$as_numorddes // numero de la orden de despacho
		//                 $as_numsol // nro de la SEP						$as_coduniadm // codigo de unidad administrativa
		//                 $ad_fecdes // fecha del despacho					$as_obsdes    // observacion del despacho
		//                 $as_codusu //usuario que ralizo la recepcion		$as_estdes    // estatus dedespacho: 0--> , 1--> 
		//                 $as_estrevdes // estatus de reverso de despacho:   0-->Despacho Reversado , 1-->Despacho Activo 
		//                 $as_codunides // codigo de unidad a despachar 
		//                 $aa_seguridad // arreglo de registro de seguridad 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta  los  datos  maestros  de  un registro de despacho de almacen y genera  un numero  de
		//                 comprobante consecutivo,  en la tabla siv_recepcion
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
		$io_fun=  new class_funciones_db($this->con);
		$ls_emp="";
		$ls_tabla="siv_despacho";
		$ls_columna="numorddes";
		$as_numorddes=$io_fun->uf_generar_codigo($ls_emp,$as_codemp,$ls_tabla,$ls_columna);
		
		$ls_sql="INSERT INTO siv_despacho (codemp, numorddes, numsol, coduniadm, fecdes, obsdes, codusu, estdes, estrevdes, codunides)".
				"     VALUES ('".$as_codemp."','".$as_numorddes."','".$as_numsol."','".$as_coduniadm."','".$ad_fecdes."',".
				"             '".$as_obsdes."','".$as_codusu."','".$as_estdes."','".$as_estrevdes."','".$as_codunides."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_insert_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
				$lb_valido=true;
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
				$ls_evento="INSERT";
				$ls_descripcion ="Insertó el registro de la orden de Despacho ".$as_numorddes." proveniente de la SEP ".$as_numsol.
								 " Para la Unidad Administrativa ".$as_coduniadm." Asociada a la Empresa ".$as_codemp;
				$ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
												$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
												$aa_seguridad["ventanas"],$ls_descripcion);
				/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}  // end  function uf_siv_insert_despacho

	function uf_siv_insert_dt_despacho($as_codemp,$as_numorddes,$as_codart,$as_numreg,$as_codalm,$as_unidad,$ai_canorisolsep,
									   $ai_canart,$ai_preuniart,$ai_monsubart,$ai_montotart,$ai_orden,$ai_canpenart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_dt_despacho
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp    //codigo de empresa 				$as_numorddes    // numero de la orden de despacho
		//                 $as_codart    // codigo de articulo				$as_numreg       // numero consecutivo de registro
		//                 $as_codalm    // codigo de almacen				$as_unidad       // codigo de unidad M->Mayor D->Detal
		//                 $ai_canart   // cantidad despachada de articulos	$ai_canorisolsep // cantidad de articulos solicitada en la SEP
		//                 $ai_preuniart // precio unitario del articulo	$ai_monsubart    // monto sub-total por articulo
		//                 $ai_montotart // monto total de articulo   	    $ai_canoriart    // codigo de procedencia del documento
		//                 $ai_orden     // orden consecutivo de registro   $ai_canoriart    // codigo de procedencia del documento
		//  			   $as_canpenart // cantidad de articulos que quedan pendientes por entregar
		//  			   $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//                 $aa_seguridad // arreglo de registro de seguridad 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta un detalle de despacho de articulos de almacen asociado a su respectivo
		//                 maestro en la tabla de  siv_dt_despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO siv_dt_despacho(codemp,numorddes,codart,numreg,codalm,unidad,canorisolsep,canart,preuniart,".
		        "                            monsubart,montotart,canpenart,orden)".
				"     VALUES ('".$as_codemp."','".$as_numorddes."','".$as_codart."','".$as_numreg."','".$as_codalm."','".$as_unidad."','".$ai_canorisolsep."',".
				"             '".$ai_canart."','".$ai_preuniart."','".$ai_monsubart."','".$ai_montotart."','".$ai_canpenart."','".$ai_orden."')";
		$li_row=$this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_insert_dt_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	} // end  function uf_siv_insert_dt_despacho

	function uf_siv_insert_dt_scg($as_codemp,$as_codart,$as_codcmp,$ad_feccmp,$as_sccuenta,$as_debhab,$ai_monto,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_dt_scg
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp    //codigo de empresa 					
		//                 $as_codart    // codigo de articulo					
		//                 $as_codcmp    // codigo de comprobante (numero de orden de despacho)			
		//                 $ad_feccmp    // fecha del comprobante
		//                 $as_sccuenta  // cuenta contable asociada
		//                 $as_debhab    // indica si el asiento contable es por el debe o por el haber 
		//                 $ai_monto     // monto del asiento contable
		//                 $aa_seguridad // arreglo de registro de seguridad 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el detalle contable asociado a un despacho de suministros en la tabla siv_dt_scg
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/09/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO siv_dt_scg (codemp,codart,codcmp,feccmp,sc_cuenta,debhab,monto,estint) ".
				"     VALUES ('".$as_codemp."','".$as_codart."','".$as_codcmp."','".$ad_feccmp."','".$as_sccuenta."','".$as_debhab."',".
				"             '".$ai_monto."',0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		   {
			 $this->io_msg->message("CLASE->sigesp_siv_c_despacho.php;MÉTODO->uf_siv_insert_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		   }
		return $lb_valido;
	} // end  function uf_siv_insert_dt_scg

	function uf_siv_insert_dt_scg_int($as_codemp,$as_codart,$as_codcmp,$ad_feccmp,$as_sccuenta,$as_debhab,$ad_mondetscg,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_insert_dt_scg_int
		//         Access: public (sigesp_siv_p_despacho)
		//      Argumento: $as_codemp    //codigo de empresa 					
		//                 $as_codart    // codigo de articulo					
		//                 $as_codcmp    // codigo de comprobante (numero de orden de despacho)			
		//                 $ad_feccmp    // fecha del comprobante
		//                 $as_sccuenta  // cuenta contable asociada
		//                 $as_debhab    // indica si el asiento contable es por el debe o por el haber 
		//                 $ai_monto     // monto del asiento contable
		//                 $aa_seguridad // arreglo de registro de seguridad 
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que inserta el detalle contable asociado a un despacho de suministros en la tabla siv_dt_scg
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 05/09/2006								Fecha Última Modificación :
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="INSERT INTO siv_dt_scg_int (codemp,codart,codcmp,feccmp,sc_cuenta,debhab,monto,estint,estrepasi) ".
				"     VALUES ('".$as_codemp."','".$as_codart."','".$as_codcmp."','".$ad_feccmp."','".$as_sccuenta."','".$as_debhab."',".
				"             '".$ad_mondetscg."',0,0)";
		$li_row=$this->io_sql->execute($ls_sql);
		if ($li_row===false)
		   {
			 $this->io_msg->message("CLASE->sigesp_siv_c_despacho.php;MÉTODO->uf_siv_insert_dt_scg_int; ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			 $lb_valido=false;
		   }
		else
		   {
		     /////////////////////////////////         SEGURIDAD               /////////////////////////////		
			 $ls_evento="INSERT";
		  	 $ls_descripcion = "Insertó Comprobante Contable Inter Compañia Nro. ".$as_codcmp.", Cuenta $as_sccuenta, Operacion $as_debhab, Monto $ad_mondetscg, 
			                    Asociada a la Empresa ".$as_codemp;
			 $ls_variable= $this->seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
			  								$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			 /////////////////////////////////         SEGURIDAD               /////////////////////////////		
		   }
		return $lb_valido;
	} // end  function uf_siv_insert_dt_scg_int


	function uf_select_metodo(&$ls_metodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_metodo
		//         Access: private
		//      Argumento: $ls_metodo    // metodo de inventario
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que metodo de inventario esta siendo utilizado actualmente.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT * FROM siv_config";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_select_metodo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_metodo=$row["metodo"];
			}
			else
			{
				$lb_valido=false;
				$this->io_msg->message("No se ha definido la configuración de inventario");
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_select_metodo
	
	function uf_select_movimiento($ls_metodo,&$rs_metodo,$as_codart,$as_codalm)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_movimiento
		//         Access: private
		//      Argumento: $ls_metodo    // metodo de inventario
		//                 $rs_metodo    // result set de la operacion del select
		//                 $as_codart    // codigo de articulo
		//                 $as_codalm    // codigo de almacén
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los movimientos que no han sido reversados y los ordena segun sea el el metodo 
	    //				   de inventario (en caso de ser FIFO ó LIFO), o saca el promedio si es Costo Promedio Ponderado
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		if($ls_metodo=="FIFO")
		{
			if($this->ls_gestor=="MYSQLT")
			{
				$ls_sql="SELECT * FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						"       (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento".
						"         WHERE opeinv ='REV')".
						" ORDER BY nummov";
			}
			else
			{
				$ls_sql="SELECT * FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND promov || numdocori NOT IN".
						"       (SELECT promov || numdocori FROM siv_dt_movimiento".
						"         WHERE opeinv ='REV')".
						" ORDER BY nummov";
			}
			
			$rs_metodo=$this->io_sql->select($ls_sql);
		}
		if($ls_metodo=="LIFO")
		{
			if($this->ls_gestor=="MYSQLT")
			{
				$ls_sql="SELECT * FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						"       (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento".
						"         WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
			}
			else
			{
				$ls_sql="SELECT * FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND promov || numdocori NOT IN".
						"      (SELECT promov || numdocori FROM siv_dt_movimiento".
						"        WHERE opeinv ='REV')".
						" ORDER BY nummov DESC";
			}
			$rs_metodo=$this->io_sql->select($ls_sql);
		}	
		if($ls_metodo=="CPP")
		{
			if($this->ls_gestor=="MYSQLT")
			{
				$ls_sql="SELECT Avg(cosart) as cosart".
						"  FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND CONCAT(promov,numdocori) NOT IN".
						"       (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento".
						"         WHERE opeinv ='REV')";
			}
			else
			{
				$ls_sql="SELECT Avg(cosart) as cosart".
						"  FROM siv_dt_movimiento".
						" WHERE  codart='". $as_codart ."'".
						"   AND codalm='". $as_codalm ."'".
						"   AND promov || numdocori NOT IN".
						"      (SELECT promov || numdocori FROM siv_dt_movimiento".
						"        WHERE opeinv ='REV')";
			}
			$rs_metodo=$this->io_sql->select($ls_sql);
		}	
		if($rs_metodo===false)
		{
			$this->io_msg->message("CLASE->despacho MÉTODO->uf_select_movimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		return $lb_valido;
	} // end function uf_select_movimiento
																		
	function uf_siv_procesar_dt_despacho($as_codemp,$as_numorddes,$as_codart,$as_codalm,$as_unidad,$ai_canorisolsep,$ai_canart,
										 $ai_preuniart,$ai_monsubart,$ai_montotart,$ai_orden,$as_nummov,$ad_fecdesaux,$as_numsol,
										 $ai_canpenart,$aa_seguridad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_procesar_dt_despacho
		//         Access: private
		//      Argumento: $as_codemp    // codigo de empresa							$as_numorddes // numero de orden de despacho
		//                 $as_codart    // codigo de articulo							$as_codalm    // codigo de almacén								
		//                 $as_unidad    // codigo de unidad M-->Mayor D->Detal		 	$ai_canorisolsep // cantidad de articulos de la SEP
		//                 $ai_canart    // cantidad despachada de articulos			$ai_preuniart    // precio unitario del articulo
		//                 $ai_canoriart // codigo de procedencia del documento			$as_nummov       // numero de movimiento
		//                 $ad_fecdesaux // fecha del despacho							$as_numsol      // numero de la SEP
		//                 $as_numconrec // comprobante (numero concecutivo para hacer unica la recepcion)
		//                 $aa_seguridad // arreglo de registro de seguridad
		//	      Returns: Retorna un Booleano
		//    Description: Función que verifica que metodo de inventario se esta utilizando y además va buscando los precios unitarios 
	    //				   en caso de que no existan suficientes artiulos al mismo precio y procede a llamar al metodo de insert_dt_movimientos
	    //				   y al insert_dt_despacho para ingresarlo en la tabla siv_dt_despacho
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_metodo="";
		$rs_metodo="";
		$lb_valido=$this->uf_select_metodo($ls_metodo);
		if ($lb_valido)
		{
			$lb_valido=$this->uf_select_movimiento($ls_metodo,&$rs_metodo,$as_codart,$as_codalm);
			if($lb_valido)
			{
				if($ls_metodo!="CPP")
				{
					$lb_break=false;
					$li_diferencia=0;
					$li_i=0;
					$li_canart=$ai_canart;
					while(($row=$this->io_sql->fetch_row($rs_metodo))&&(!$lb_break))
					{
						$li_preuniart=$row["cosart"];
						$ls_numdocori=$row["numdocori"];
						$ls_nummov=$row["nummov"];
						if($this->ls_gestor=="MYSQLT")
						{
							$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN candesart ELSE -candesart END) total".
									"  FROM siv_dt_movimiento".
									" WHERE codemp='". $as_codemp ."'".
									"   AND codart='". $as_codart ."'".
									"   AND codalm='". $as_codalm ."'".
									"   AND numdocori='". $ls_numdocori ."'".
									"   AND CONCAT(promov,numdocori) NOT IN".
									"      (SELECT CONCAT(promov,numdocori) FROM siv_dt_movimiento".
									"        WHERE opeinv ='REV')".
									" ORDER BY nummov";
						}
						if($this->ls_gestor=="INFORMIX")
						{
							$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN candesart ELSE -candesart END) AS total".
									"  FROM siv_dt_movimiento".
									" WHERE codemp='". $as_codemp ."'".
									"   AND codart='". $as_codart ."'".
									"   AND codalm='". $as_codalm ."'".
									"   AND numdocori='". $ls_numdocori ."'".
									"   AND promov || numdocori NOT IN".
									"      (SELECT promov || numdocori FROM siv_dt_movimiento".
									"        WHERE opeinv ='REV')".
									" GROUP BY nummov";
						}
						else
						{
							$ls_sql="SELECT SUM(CASE opeinv WHEN 'ENT' THEN candesart ELSE -candesart END) AS total".
									"  FROM siv_dt_movimiento".
									" WHERE codemp='". $as_codemp ."'".
									"   AND codart='". $as_codart ."'".
									"   AND codalm='". $as_codalm ."'".
									"   AND numdocori='". $ls_numdocori ."'".
									"   AND promov || numdocori NOT IN".
									"      (SELECT promov || numdocori FROM siv_dt_movimiento".
									"        WHERE opeinv ='REV')".
									" GROUP BY nummov".
									" ORDER BY nummov";
						}

						$li_exec1=$this->io_sql->select($ls_sql);
						if($row1=$this->io_sql->fetch_row($li_exec1))
						{
							$li_existencia=$row1["total"];
							if ($li_existencia > 0)
							{
								$lb_encontrado=true;
								$li_i=$li_i + 1;

								if ($li_existencia < $li_canart)
								{
									$li_canart= $li_canart-$li_existencia;


									$lb_valido=$this->uf_siv_disminuir_articuloxmovimiento($as_codemp,$as_codart,$as_codalm,$as_nummov,
																							$ls_numdocori,$li_existencia);
/*									if ($lb_valido)
									{
										$ls_opeinv="SAL";
										$ls_promov="DES";
										$ls_codprodoc="SEP";
										$li_candesart="0.00";
										$lb_valido=$this->io_mov->uf_siv_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecdesaux,
																						  	  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																							  $as_numsol,$li_existencia,$li_preuniart,$ls_promov,
																						  	  $as_numorddes,$li_candesart,$ad_fecdesaux,
																							  $aa_seguridad);
	
										if($lb_valido)
										{
											$lb_valido=$this->uf_siv_insert_dt_despacho($as_codemp,$as_numorddes,$as_codart,$li_i,$as_codalm,
																						$as_unidad,$ai_canorisolsep,$ai_canart,$ai_preuniart,
																						$ai_monsubart,$ai_montotart,$ai_orden,$aa_seguridad);
										}
										
									}			
*/															
								}  // fin  if ($li_existencia < $ai_canart)
								elseif($li_existencia >= $li_canart)
								{
									$lb_valido=$this->uf_siv_disminuir_articuloxmovimiento($as_codemp,$as_codart,$as_codalm,$ls_nummov,
																							$ls_numdocori,$li_canart);
/*									if ($lb_valido)
									{
										$ls_opeinv="SAL";
										$ls_promov="DES";
										$ls_codprodoc="SEP";
										$li_candesart="0.00";
										$lb_valido=$this->io_mov->uf_siv_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecdesaux,
																						  	  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																							  $as_numsol,$ai_canart,$li_preuniart,$ls_promov,
																						  	  $as_numorddes,$li_candesart,$ad_fecdesaux,
																							  $aa_seguridad);
										if($lb_valido)
										{
											$lb_valido=$this->uf_siv_insert_dt_despacho($as_codemp,$as_numorddes,$as_codart,$li_i,$as_codalm,
																						$as_unidad,$ai_canorisolsep,$ai_canart,$ai_preuniart,
																						$ai_monsubart,$ai_montotart,$ai_orden,$aa_seguridad);
*/											if($lb_valido)
											{
												$lb_break=true;
											}
									//	}
								//	}
								}
								
								if(!$lb_valido)
								{
									$lb_break=true;
								}

							}  // fin  ($li_existencia > 0)
							
						}  //fin  if($row1=$io_sql->fetch_row($li_exec1))
		
					}// fin  while(($row=$io_sql->fetch_row($rs_metodo))&&(!$lb_break))
					if ($lb_valido)
					{
						$ls_opeinv="SAL";
						$ls_promov="DES";
						$ls_codprodoc="SEP";
						$li_candesart="0.00";
						$lb_valido=$this->io_mov->uf_siv_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecdesaux,
																			  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																			  $as_numsol,$ai_canart,$ai_preuniart,$ls_promov,
																			  $as_numorddes,$li_candesart,$ad_fecdesaux,
																			  $aa_seguridad);

						if($lb_valido)
						{
							$lb_valido=$this->uf_siv_insert_dt_despacho($as_codemp,$as_numorddes,$as_codart,$li_i,$as_codalm,
																		$as_unidad,$ai_canorisolsep,$ai_canart,$ai_preuniart,
																		$ai_monsubart,$ai_montotart,$ai_orden,$ai_canpenart,
																		$aa_seguridad);
						}
						
					}			

					
				}// fin  if($ls_metodo!="CPP")
				else
				{
					if($row=$this->io_sql->fetch_row($rs_metodo))
					{
						$li_preuniart=$row["cosart"];
						$ls_numdocori="";   
						$ls_opeinv="SAL";
						$ls_promov="DES";
						$ls_codprodoc="SEP";
						$li_candesart="0.00";
						$lb_valido=$this->io_mov->uf_siv_insert_dt_movimiento($as_codemp,$as_nummov,$ad_fecdesaux,
																			  $as_codart,$as_codalm,$ls_opeinv,$ls_codprodoc,
																			  $as_numsol,$ai_canart,$li_preuniart,$ls_promov,
																			  $as_numorddes,$li_candesart,$ad_fecdesaux,
																			  $aa_seguridad);

						if($lb_valido)
						{
							$li_i=1;
							$lb_valido=$this->uf_siv_insert_dt_despacho($as_codemp,$as_numorddes,$as_codart,$li_i,$as_codalm,
																		$as_unidad,$ai_canorisolsep,$ai_canart,$ai_preuniart,
																		$ai_monsubart,$ai_montotart,$ai_orden,$ai_canpenart,
																		$aa_seguridad);
					    }
						
					}// fin  if($row=$this->io_sql->fetch_row($rs_metodo))
					
				}// fin  else($ls_metodo!="CPP")
			/*	if($lb_valido)
				{
					$lb_valido=$this->uf_siv_update_sep($as_codemp,$as_numsol);  
				}*/
				
				
			}
			
		}
		return $lb_valido;
	}// end  function uf_siv_procesar_dt_despacho

	function uf_siv_disminuir_articuloxmovimiento($as_codemp,$as_codart,$as_codalm,$as_nummov,$ls_numdocori,$ai_cantidad)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_disminuir_articuloxmovimiento
		//         Access: private
		//      Argumento: $as_codemp       // codigo de empresa
		//                 $as_codart       // codigo de articulo
		//                 $as_codalm       // codigo de almacen
		//                 $ls_numdocori    // numero original de la entrada de suministros a almacén
		//                 $as_nummov       // numero de movimiento
		//                 $as_cantidad     // cantidad de articulos
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que disminuye la cantidad de articulos proveniente de un movimiento en la tabla siv_dt_movimiento
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 09/02/2006 								Fecha Última Modificación :09/02/2006
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $rs_disart=-1;
		 $ld_date= date("Y-m-d");
		 $ls_sql= "UPDATE siv_dt_movimiento".
		 		  "   SET candesart= (candesart - '". $ai_cantidad ."'), ".
		 		  "       fecdesart= '" . $ld_date ."'".
				  " WHERE codemp='" . $as_codemp ."'".
				  "   AND opeinv='ENT'".
				  "   AND codart='" . $as_codart ."'".
				  "   AND codalm='" . $as_codalm ."'".
				  "   AND numdocori='" . $ls_numdocori ."'";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_disminuir_articuloxmovimiento ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
		return $lb_valido;
	}
	
	function uf_siv_update_sep($as_codemp,$as_numsol,$as_estsep) 
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_update_sep
		//         Access: private
		//      Argumento: $as_codemp //codigo de empresa 
		//                 $as_numsol // numero de la solicitud de ejecución presupuestaria
		//                 $as_estsep // estatus en que se va a colocar la SEP
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que actualiza el estatus de la solicitud de ejecución presupuestaria  estsol que indica
		//                 si la SEP fue despachada o no.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 16/02/2006	 								Fecha Última Modificación :16/02/2006	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $lb_valido=true;
		 $ls_sql= "UPDATE sep_solicitud SET  estsol='" . $as_estsep ."'".
				  " WHERE codemp='" . $as_codemp ."' ".
				  "   AND numsol='" . $as_numsol ."' ";
		$li_row = $this->io_sql->execute($ls_sql);
		if($li_row===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_update_sep ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			$lb_valido=true;
		}
	  return $lb_valido;
	} // end function uf_siv_update_sep

	function uf_siv_obtener_dt_despacho($as_codemp,$as_numodrdes,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_despacho
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_numodrdes // numero de orden de despacho
		//                 $ai_totrows   // total de filas encontradas
		//                 $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a un despacho en la tabla siv_dt_despacho, e igualmante busca las
		//                 denominaciones  de los articulos en la tabla siv_articulopara luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/02/2006		 								Fecha Última Modificación :24/02/2006		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$ai_totrows=0;
		$ls_sql= "SELECT siv_dt_despacho.*,siv_articulo.codart,siv_unidadmedida.unidad AS unidades,".
				 "       (SELECT denart FROM siv_articulo ".
				 "         WHERE siv_articulo.codart=siv_dt_despacho.codart) AS denart,".
				 "       (SELECT nomfisalm FROM siv_almacen ".
				 "         WHERE siv_almacen.codalm=siv_dt_despacho.codalm) AS nomfisalm,".
				 "       (SELECT sep_dt_articulos.unidad FROM sep_dt_articulos,siv_despacho".
				 "         WHERE sep_dt_articulos.codemp=siv_despacho.codemp".
				 "           AND sep_dt_articulos.numsol=siv_despacho.numsol".
				 "           AND siv_despacho.codemp=siv_dt_despacho.codemp".
				 "           AND sep_dt_articulos.codemp=siv_dt_despacho.codemp".
				 "           AND siv_despacho.numorddes=siv_dt_despacho.numorddes".
				 "           AND sep_dt_articulos.codart=siv_dt_despacho.codart) AS unisol, ".
				 "        (SELECT tipart FROM siv_tipoarticulo ".
				 "         WHERE siv_articulo.codtipart =siv_tipoarticulo.codtipart) as tipart ".
				 "  FROM siv_dt_despacho,siv_articulo,siv_unidadmedida".
				 " WHERE siv_dt_despacho.codart=siv_articulo.codart".
				 "   AND siv_articulo.codunimed=siv_unidadmedida.codunimed".
				 "   AND siv_dt_despacho.codemp='". $as_codemp ."'".
				 "   AND siv_dt_despacho.numorddes='". $as_numodrdes ."'"; 
		$li_exec1=$this->io_sql->select($ls_sql);
		if($li_exec1===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_despacho ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($li_exec1))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codart=    $row["codart"];
				$ls_codalm=    $row["codalm"];
				$ls_denart=    $row["denart"];
				$ls_unidad=    $row["unidad"];
				$ls_unisol=    $row["unisol"];
				$li_unidad=    $row["unidades"];
				$li_preuniart= $row["preuniart"];
				$li_canart=    $row["canart"];
				$li_cansol=    $row["canorisolsep"];
				$li_montotart= $row["montotart"];
				$li_canpendes= $row["canpenart"];
				$ls_clasif   = $row["tipart"];
				switch ($ls_unidad) 
				{
					case "M":
						$ls_unidadaux="Mayor";
						break;
					case "D":
						$ls_unidadaux="Detal";
						break;
				}
				if($ls_unisol=="M")
				{
						$li_canpendes= ($li_canpendes/$li_unidad);
				}
				$ao_object[$ai_totrows][1]="<input name=txtdenart".$ai_totrows."     type=text     id=txtdenart".$ai_totrows." class=sin-borde size=25 maxlength=50 value='".$ls_denart."' readonly>".
										   "<input name=txtcodart".$ai_totrows."     type=hidden   id=txtcodart".$ai_totrows." class=sin-borde size=15 maxlength=30 value='".$ls_codart."' readonly>";
				$ao_object[$ai_totrows][2]="<input name=txtcodalm".$ai_totrows."     type=text     id=txtcodalm".$ai_totrows." class=sin-borde size=13 maxlength=10 value='". $ls_codalm."' readonly>";
				$ao_object[$ai_totrows][3]="<input name=txtunidad".$ai_totrows."     type=text     id=txtunidad".$ai_totrows." class=sin-borde size=12 maxlength=12 value='". $ls_unidadaux."' style='text-align:center' readonly></div>".
										   "<input name='hidunidad".$ai_totrows."'   type='hidden' id='hidunidad".$ai_totrows."' value='". $li_unidad ."'>";
				$ao_object[$ai_totrows][4]="<input name=txtcansol".$ai_totrows."     type=text     id=txtcansol".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_cansol,2,",",".")."'       style='text-align:right' readonly>".
										   "<input name=hidexistencia".$ai_totrows." type=hidden   id=hidexistencia".$ai_totrows.">";
				$ao_object[$ai_totrows][5]="<input name=txtpenart".$ai_totrows."     type=text     id=txtpenart".$ai_totrows."    class=sin-borde size=12 maxlength=12 style='text-align:right' value='".number_format ($li_canpendes,2,",",".")."' readonly>";
				$ao_object[$ai_totrows][6]="<input name=txtcanart".$ai_totrows."     type=text     id=txtcanart".$ai_totrows." class=sin-borde size=12 maxlength=12 value='".number_format ($li_canart,2,",",".")."'       style='text-align:right' readonly'>";
				$ao_object[$ai_totrows][7]="<input name=txtpreuniart".$ai_totrows."  type=text     id=txtpreuniart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_preuniart,2,",",".")."' style='text-align:right' readonly>".
									       "<input name=hidnumdocori".$ai_totrows."  type=hidden   id=hidnumdocori".$ai_totrows.">";
				$ao_object[$ai_totrows][8]="<input name=txtmontotart".$ai_totrows."  type=text     id=txtmontotart".$ai_totrows." class=sin-borde size=14 maxlength=15 value='".number_format ($li_montotart,2,",",".")."' style='text-align:right' readonly>";
				$ao_object[$ai_totrows][9]="<a href=javascript:uf_dt_activo(".$ai_totrows.");><img src=../shared/imagebank/mas.gif alt=Agregar Seriales width=15 height=15 border=0><input name=hclasi".$ai_totrows." type=hidden id=hclasi".$ai_totrows." class=sin-borde size=15 maxlength=25 value='".$ls_clasif."' readonly></a>".			
			      						   " <input type=hidden name=hcodact".$ai_totrows."    id=hcodact".$ai_totrows." class=sin-borde size=15 maxlength=25  readonly>";
			}
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($li_exec1);
		return $lb_valido;
	} // end  function uf_siv_obtener_dt_despacho

	function uf_siv_obtener_dt_scg($as_codemp,$as_numodrdes,&$ai_totrows,&$ao_object)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_obtener_dt_scg
		//         Access: private
		//      Argumento: $as_codemp    //codigo de empresa 
		//                 $as_numodrdes // numero de orden de despacho
		//                 $ai_totrows   // total de filas encontradas
		//                 $ao_object    // arreglo de objetos para pintar el grid
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que busca los articulos asociados a un despacho en la tabla siv_dt_despacho, e igualmante busca las
		//                 denominaciones  de los articulos en la tabla siv_articulopara luego imprimirlos en el grid de la pagina.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 24/02/2006		 								Fecha Última Modificación :24/02/2006		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 		$lb_valido=true;
		$ai_totrows=0;
		$ls_sql= "SELECT siv_dt_scg.*,siv_articulo.codart,".
				 "       (SELECT denart FROM siv_articulo ".
				 "         WHERE siv_articulo.codart=siv_dt_scg.codart) AS denart".
				 "  FROM siv_dt_scg,siv_articulo".
				 " WHERE siv_dt_scg.codart=siv_articulo.codart".
				 "   AND siv_dt_scg.codemp='". $as_codemp ."'".
				 "   AND siv_dt_scg.codcmp='". $as_numodrdes ."'".
				 " ORDER BY denart,debhab";
		$li_exec=$this->io_sql->select($ls_sql);
		if($li_exec===false)
		{
			$lb_valido=false;
			$this->io_msg->message("CLASE->despacho MÉTODO->uf_siv_obtener_dt_scg ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
		}
		else
		{
			while($row=$this->io_sql->fetch_row($li_exec))
			{
				$ai_totrows=$ai_totrows+1;
				$ls_codart=    $row["codart"];
				$ls_denart=    $row["denart"];
				$ls_sccuenta=  $row["sc_cuenta"];
				$ls_debhab=    $row["debhab"];
				$li_montoc=    $row["monto"];
				
				$ao_object[$ai_totrows][1]="<input  name=txtdenartc".$ai_totrows."  type=text   id=txtdenartc".$ai_totrows."  class=sin-borde size=50  value='".$ls_denart."'   readonly>".
										   "<input  name=txtcodartc".$ai_totrows."  type=hidden id=txtcodartc".$ai_totrows."  class=sin-borde size=30  value='".$ls_codart."'   readonly>";
				$ao_object[$ai_totrows][2]="<input  name=txtsccuenta".$ai_totrows." type=text   id=txtsccuenta".$ai_totrows." class=sin-borde size=30  value='".$ls_sccuenta."' readonly>";
				$ao_object[$ai_totrows][3]="<input  name=txtdebhab".$ai_totrows."   type=text   id=txtdebhab".$ai_totrows."   class=sin-borde size=15  value='".$ls_debhab."'   readonly style='text-align:center'>";
				$ao_object[$ai_totrows][4]="<input  name=txtmonto".$ai_totrows."    type=text   id=txtcansolc".$ai_totrows."  class=sin-borde size=30  value='".number_format ($li_montoc,2,",",".")."' style='text-align:right' readonly>";
			}
		}
		if ($ai_totrows==0)
		{$lb_valido=false;}
		$this->io_sql->free_result($li_exec);
		return $lb_valido;
	} // end  function uf_siv_obtener_dt_scg

	function uf_siv_load_contabilizacion($as_codemp,&$li_value)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_contabilizacion
		//         Access: private
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $li_value    // estatus de contabilizacion de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica que metodo de inventario esta siendo utilizado actualmente.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/01/2007 								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = "SELECT value".
		          "  FROM sigesp_config".
				  " WHERE codemp='".$as_codemp."'".
				  "   AND codsis='SIV'".
				  "   AND seccion='CONFIG'".
				  "   AND entry='CONTA DESPACHO'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_siv_load_contabilizacion ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$li_value=$row["value"];
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end  function uf_siv_load_contabilizacion
//-----------------------------------------------------------------------------------------------------------------------------------
     function uf_cierrecontable()
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_cierrecontable
		//         Access: private
		//      Argumento: $as_codemp   // codigo de empresa
		//                 $li_value    // estatus de contabilizacion de despacho
		//	      Returns: Retorna un Booleano
		//    Description: Funcion que verifica el cierre contable
		//	   Creado Por: Ing. Jennifer Rivero
		// Fecha Creación: 12/09/2008 								Fecha Última Modificación:
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql = " SELECT estciescg FROM sigesp_empresa WHERE codemp='".$this->ls_codemp."'"; 
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Despacho MÉTODO->uf_cierrecontable ERROR->"
			                       .$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$ls_estciescg=$row["estciescg"];
			}
			else
			{
				$ls_estciescg=0;
			}
			$this->io_sql->free_result($rs_data);
		}
		return $ls_estciescg;
	} // end  uf_cierrecontable
//--------------------------------------------------------------------------------------------------------------------------------------
	function uf_siv_load_codigoactivo($as_codart,&$as_codact)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_siv_load_codigoactivo
		//		   Access: public
		//		 Argumens: as_codart  // Codigo de Articulo
		//	  Description: Funcion que obtiene el codigo del activo asociado al articulo.
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/11/2007 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_sql="SELECT codact".
				"  FROM siv_articulo".
				" WHERE codemp='".$this->ls_codemp."'".
				"   AND codart='".$as_codart."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_msg->message("CLASE->Registro_Activo MÉTODO->uf_siv_load_codigoactivo ERROR->".$this->io_funcion->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_codact=$row["codact"];				
			}
			else
			{
				$as_codact="---------------";
			}
			$this->io_sql->free_result($rs_data);
		}
		return $lb_valido;
	} // end function uf_siv_select_movimiento
	//-----------------------------------------------------------------------------------------------------------------------------


}//end  class sigesp_siv_c_recepcion
?>

<?php
class sigesp_snorh_c_metodo_mintra
{
	var $io_mensajes;
	var $io_sno;
	var $io_fecha;
	var $io_metbanco;

	//-----------------------------------------------------------------------------------------------------------------------------------
	function sigesp_snorh_c_metodo_mintra()
	{	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: sigesp_snorh_c_metodo_mintra
		//		   Access: public 
		//	  Description: Constructor de la Clase
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/08/2006 								
		// Modificado Por: 										Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$this->io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_datastore.php");
		$this->DS=new class_datastore();
		require_once("../shared/class_folder/class_mensajes.php");
		$this->io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/sigesp_c_seguridad.php");
		$this->io_seguridad= new sigesp_c_seguridad();
		require_once("../shared/class_folder/class_funciones.php");
		$this->io_funciones=new class_funciones();
		require_once("../shared/class_folder/class_fecha.php");
		$this->io_fecha=new class_fecha();
		require_once("sigesp_sno.php");
		$this->io_sno=new sigesp_sno();
		require_once("sigesp_snorh_c_metodobanco.php");
		$this->io_metbanco=new sigesp_snorh_c_metodobanco();
   		$this->ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$this->ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$this->ls_rifemp=$_SESSION["la_empresa"]["rifemp"];	
	}// end function sigesp_sno_c_metodo_banco
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
    function uf_disgrega_rif(&$as_riflet,&$as_rifnum,&$as_rifdig)
	{ 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_disgrega_rif
		//		   Access: private
		//	    Arguments: as_riflet // Letra del Rif
		//	    		   as_rifnum // Número de Rif
		//	    		   as_rifdig // Digitos del Rif
		//	      Returns: lb_valido 
		//    Description: function que separa letra, numero y digito del rif original
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 31/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_rif=$this->ls_rifemp;
		$as_riflet="J";
		$as_rifnum="XXXXXXXX";
		$as_rifdig="0";
		$li_pos1=strpos($ls_rif, "-");
		if($li_pos1>=1)
		{
			$as_riflet=substr($ls_rif,0,$li_pos1-1);
			$li_pos2=strpos($ls_rif,"-",$li_pos1);
			if($li_pos2>=1)
			{
				$as_rifnum=substr($ls_rif,$li_pos1+1,$li_pos2-$li_pos1-1);
				$as_rifdig=substr($ls_rif,$li_pos2+1);
			}
		}
	}// end function uf_disgrega_rif
//-----------------------------------------------------------------------------------------------------------------------------------
/*
function uf_listado_mintra($as_codnomdes,$as_codnomhas,$as_anocurper,$as_mescurper)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_mintra
		//         Access: public (desde la clase sigesp_snorh_r_prestacionantiguedad)  
		//	    Arguments: as_codnom // código de Nómina
		//	    		   as_anocurper // Año en curso
		//	  			   as_mescurper // Mes en curso		  
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información del  personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/07/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;		
		$li_mes=str_pad($as_mescurper,2,"0",0);
		$ls_sql="SELECT sno_personal.cedper,sno_personal.nomper,sno_personal.apeper, sno_fideiperiodo.sueintper, ".
				"		sno_fideicomiso.fecingfid, sno_fideicomiso.codfid, sno_personal.edocivper, sno_personal.nacper, ".
				"		sno_personal.fecingadmpubper, sno_fideicomiso.cuefid, sno_fideicomiso.capfid, sno_fideicomiso.ubifid, ".
				"		sno_fideiperiodo.bonvacper, sno_fideiperiodo.bonfinper, sno_fideiperiodo.apoper, sno_personal.cuefidper, ".
				"		sno_fideicomiso.ficfid, sno_personal.dirper, sno_personal.telhabper, sno_personal.telmovper, ".
				"       sno_fideicomiso.porintcap, minorguniadm,ofiuniadm,uniuniadm,depuniadm,prouniadm, ".
				"		sno_personal.sexper, sno_personal.fecingper, sno_personal.coreleper, ".
				"       (SELECT SUM(apoper) FROM sno_fideiperiodo ".
				"         WHERE sno_fideiperiodo.codemp = '".$this->ls_codemp."' AND sno_fideiperiodo.codnom = '".$as_codnom."' ".
				"           AND sno_fideiperiodo.anocurper = '".$as_anocurper."' AND sno_fideiperiodo.mescurper = '".$li_mes."') AS montototal ".
				"  FROM sno_personal, sno_fideiperiodo, sno_fideicomiso,sno_personalnomina ".
				" WHERE sno_fideiperiodo.codemp = '".$this->ls_codemp."' ".
				"   AND sno_fideiperiodo.codnom >= '".$as_codnomdes."' ".
				"   AND sno_fideiperiodo.codnom <= '".$as_codnomhas."' ".
				"   AND sno_fideiperiodo.anocurper = '".$as_anocurper."' ".
				"   AND sno_fideiperiodo.mescurper = '".$li_mes."' ".
				"   AND sno_personal.codemp = sno_fideiperiodo.codemp ".
				"	AND sno_personal.codper = sno_fideiperiodo.codper ".
				"   AND sno_personal.codemp = sno_fideicomiso.codemp ".
				"	AND sno_personal.codper = sno_fideicomiso.codper ".
				"   AND sno_personalnomina.codemp= sno_personal.codemp ".
				"   AND sno_personalnomina.codper= sno_personal.codper ".
				"   AND sno_personalnomina.codnom= sno_fideiperiodo.codnom ".
				" ORDER BY sno_personal.cedper ";
		print $ls_sql;
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Metodo Aporte MÉTODO->uf_listado_prestacionantiguedad ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$this->DS->data=$this->io_sql->obtener_datos($rs_data);		
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listado_prestacionantiguedad
*/
//-----------------------------------------------------------------------------------------------------------------------------------
function uf_listado_mintra($as_codconc,$as_codnomdes,$as_codnomhas,$as_ano,$as_perdes,$as_perhas)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_listado_gendisk
		//		   Access: public (desde la clase sigesp_sno_r_aportepatronal)  
		//	    Arguments: aa_codconc // Arreglo de conceptos se desea busca el personal
		//	    		   as_codnomdes // Código Nómina Desde
		//	    		   as_codnomhas // Código Nómina Hasta
		//	    		   as_ano // Año en curso
		//	    		   as_perdes // Período Desde
		//	    		   as_perhas // Período Hasta
		//	      Returns: lb_valido True si se creo el Data stored correctamente ó False si no se creo
		//    Description: función que busca la información de las personas que tienen asociado el concepto	de tipo aporte patronal 
		//				   y se calculó en la nómina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 28/08/2006 								Fecha Última Modificación :  
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_criterio="";
		$ls_orden="";
		if(!empty($as_codnomdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom>='".$as_codnomdes."' ";
		}
		if(!empty($as_codnomhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codnom<='".$as_codnomhas."' ";
		}
		if(!empty($as_ano))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.anocur='".$as_ano."' ";
		}
		if(!empty($as_perdes))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi>='".$as_perdes."' ";
		}
		if(!empty($as_perhas))
		{
			$ls_criterio = $ls_criterio." AND sno_hsalida.codperi<='".$as_perhas."' ";
		}
		$ls_codconc=str_pad($as_codconc,10,"0",0);
		$ls_criterio=$ls_criterio." AND (sno_hsalida.codconc='".$ls_codconc."' ";
		$ls_criterio=$ls_criterio.")";
		$ls_criterio = $ls_criterio." AND sno_hsalida.valsal<>0 ";
		$ls_sql="SELECT DISTINCT sno_hpersonalnomina.codper,sno_personal.cedper, sno_personal.apeper, sno_personal.nomper, sno_hpersonalnomina.sueper, ".
				"       sno_personal.nacper, sno_personal.fecnacper, sno_personal.sexper, sno_hpersonalnomina.fecingper, ".
				"		sno_personal.fecegrper, sno_hpersonalnomina.fecegrper AS fecegrnom, sno_personal.estper, ".
				"		sno_personal.cuecajahoper, sno_personal.edocivper, sno_hpersonalnomina.minorguniadm,sno_hpersonalnomina.ofiuniadm, ".
				"       sno_hpersonalnomina.uniuniadm,sno_hpersonalnomina.depuniadm,sno_hpersonalnomina.prouniadm, sno_asignacioncargo.denasicar, sno_hpersonalnomina.codtipper,".
				"       (SELECT tipnom ".
				"		   FROM sno_hnomina ".
				"   	  WHERE sno_hpersonalnomina.codemp = sno_hnomina.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hnomina.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hnomina.anocurnom ".
				"   		AND sno_hpersonalnomina.codperi = sno_hnomina.peractnom) as tipnom, ".
				"       (SELECT SUM(valsal) ".
				"		   FROM sno_hsalida ".
				"   	  WHERE (sno_hsalida.tipsal='P1' OR sno_hsalida.tipsal='V3' OR sno_hsalida.tipsal='W3' OR sno_hsalida.tipsal='Q1') ".
				$ls_criterio.
				"           AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   		AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   		AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   		AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   		AND sno_hpersonalnomina.codper = sno_hsalida.codper) as personal ".
				"  FROM sno_personal, sno_hpersonalnomina, sno_hsalida, sno_asignacioncargo".
				" WHERE sno_hpersonalnomina.codemp='".$this->ls_codemp."' ".
				"   AND sno_hpersonalnomina.codnom>='".$as_codnomdes."' ".
				"   AND sno_hpersonalnomina.codnom<='".$as_codnomhas."' ".
				"   AND sno_hpersonalnomina.anocur='".$as_ano."' ".
				"   AND sno_hpersonalnomina.codperi>='".$as_perdes."' ".
				"   AND sno_hpersonalnomina.codperi<='".$as_perhas."' ".
				"   AND (sno_hpersonalnomina.staper='1' OR sno_hpersonalnomina.staper='2') ".
				$ls_criterio.
				"   AND sno_hpersonalnomina.codemp = sno_hsalida.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_hsalida.codnom ".
				"   AND sno_hpersonalnomina.anocur = sno_hsalida.anocur ".
				"   AND sno_hpersonalnomina.codperi = sno_hsalida.codperi ".
				"   AND sno_hpersonalnomina.codper = sno_hsalida.codper ".
				"	AND sno_personal.codemp = sno_hpersonalnomina.codemp ".
				"   AND sno_personal.codper = sno_hpersonalnomina.codper ".
				"   AND sno_hpersonalnomina.codemp = sno_asignacioncargo.codemp ".
				"   AND sno_hpersonalnomina.codnom = sno_asignacioncargo.codnom ".
				"   AND sno_hpersonalnomina.codasicar = sno_asignacioncargo.codasicar ".
				" GROUP BY sno_hpersonalnomina.codemp, sno_hpersonalnomina.anocur, sno_hpersonalnomina.codnom, ".
				"		   sno_hpersonalnomina.codperi, sno_hpersonalnomina.codper, sno_personal.cedper, sno_personal.apeper, ".
				"		   sno_personal.nomper, sno_hpersonalnomina.sueper, sno_personal.nacper, sno_personal.fecnacper, ".
				"		   sno_personal.sexper, sno_hpersonalnomina.fecingper, sno_personal.fecegrper, sno_personal.estper, ".
				"		   sno_personal.cuecajahoper, sno_hpersonalnomina.fecegrper,sno_personal.edocivper,sno_hpersonalnomina.minorguniadm, ".
				"          sno_hpersonalnomina.ofiuniadm, sno_hpersonalnomina.uniuniadm,sno_hpersonalnomina.depuniadm,sno_hpersonalnomina.prouniadm,sno_asignacioncargo.denasicar,sno_hpersonalnomina.codtipper ".
				" ORDER BY sno_hpersonalnomina.codper  ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$this->io_mensajes->message("CLASE->Metodo LPH MÉTODO->uf_listado_gendisk ERROR->".$this->io_funciones->uf_convertirmsg($this->io_sql->message));
			$lb_valido=false;
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$datos=$this->io_sql->obtener_datos($rs_data);
				$this->DS->data=$datos;	
				$this->DS->group_by(array('0'=>'codper'),array('0'=>'codper'),'codper');							
			}
			else
			{
				$lb_valido=false;
			}
			$this->io_sql->free_result($rs_data);
		}		
		return $lb_valido;
	}// end function uf_listado_gendisk
	//-----------------------------------------------------------------------------------------------------------------------------------

function uf_metodo_mintra($as_ruta,$as_metodo,$aa_ds_registro,$as_codnomdes,$as_codnomhas,$as_anocur,$as_mes,$ad_fecpro,$aa_seguridad)
{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_fps	
		//	    Arguments: as_ruta   // ruta donde se va aguardar el archivo
		//	    		   as_metodo   // Código del metodo a banco
		//                 aa_ds_fps // arreglo (datastore) datos fps      
		//	    		   as_codnom // Código Nómina 
		//	    		   as_anocurper // Año en curso
		//	    		   as_mescurper // Mes
		//	    		   ad_fecha // Fecha de Procesamiento
		//	    		   as_tiptra // Tipo de Transacción
		//				   aa_seguridad // arreglo de seguridad
		//	      Returns: lb_valido True 
		//	  Description: Funcion que segun el banco, genera un archivo txt a disco para cancelación de Prestación Antiguedad
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/09/2006 								
		// Modificado Por:											Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=false;
	  	switch ($as_metodo)
		{
			case "MINTRA":
				$lb_valido=$this->uf_metodo_mintra_txt($as_ruta,$ad_fecpro,$aa_ds_registro);
				break;

			default:
				$this->io_mensajes->message("El método seleccionado no esta disponible.");
				break;
		}
		if($lb_valido)
		{
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
			$ls_evento="PROCESS";
			$ls_descripcion ="Generó el disco de MINTRA desde la Nómina ".$as_codnomdes."  hasta la nomina ".$as_codnomhas." Año ".$as_anocur." Mes ".$as_mes;
			$lb_valido= $this->io_seguridad->uf_sss_insert_eventos_ventana($aa_seguridad["empresa"],
											$aa_seguridad["sistema"],$ls_evento,$aa_seguridad["logusr"],
											$aa_seguridad["ventanas"],$ls_descripcion);
			/////////////////////////////////         SEGURIDAD               /////////////////////////////		
		}
		return $lb_valido;
	}// end function uf_metodo_fps
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------		
	function uf_metodo_mintra_txt($as_ruta,$ad_fecha,$aa_ds_registro)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_metodo_fps_venezuela
		//		   Access: private 
		//	    Arguments: as_ruta  // ruta 
		//                 ad_fecha // Fecha 
		//                 aa_ds_registro // arreglo (datastore) datos MINTRA   
		//	  Description: genera el archivo txt a disco para  el Metodo MINTRA
		//	   Creado Por: Ing. Carlos Zambrano	
		// Fecha Creación: 15/06/2009 								
		// Modificado Por: 									Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$li_count=$aa_ds_registro->getRowCount("codper");
		if($li_count>0)
		{	
			$ls_nombrearchivo=$as_ruta."/mintra.txt";
			//Chequea si existe el archivo.
			if (file_exists("$ls_nombrearchivo"))
			{
				if(@unlink("$ls_nombrearchivo")===false)//Borrar el archivo de texto existente para crearlo nuevo.
				{
					$lb_valido = false;
				}
				else
				{
					$ls_creararchivo = @fopen("$ls_nombrearchivo","a+");
				}
			}
			else
			{
				$ls_creararchivo = @fopen("$ls_nombrearchivo","a+"); //creamos y abrimos el archivo para escritura
			}
			$ls_encabezado="1ER_NOMBRE;2DO_NOMBRE;1ER_APELLIDO;2DO_APELLIDO;NACIONALIDAD;CEDULA;SEXO;FECHA_NACIMIENTO;CARGO;TIPO_TRABAJADOR;FECHA_INGRESO;ESTADO_EMPLEADO;SALARIO"."\r\n";
			@fwrite($ls_creararchivo,$ls_encabezado);
			for($li_i=1;$li_i<=$li_count;$li_i++)
			{
				$ls_nomper=$aa_ds_registro->data["nomper"][$li_i];
				$li_pos=strpos($ls_nomper," ");
				if($li_pos===false)
				{
					$li_pos=strlen($ls_nomper);
				}
				$ls_primernombre=substr(substr($ls_nomper,0,$li_pos),0,20);
				$ls_primernombre=$this->io_funciones->uf_rellenar_der($ls_primernombre," ",20);
				$ls_segundonombre=substr(substr($ls_nomper,$li_pos+1,strlen($ls_nomper)-$li_pos),0,20);
				$ls_segundonombre=$this->io_funciones->uf_rellenar_der($ls_segundonombre," ",20);
				$ls_apeper=$aa_ds_registro->data["apeper"][$li_i];
				$li_pos=strpos($ls_apeper," ");
				if($li_pos===false)
				{
					$li_pos=strlen($ls_apeper);
				}
				$ls_primerapellido=substr(substr($ls_apeper,0,$li_pos),0,20);
				$ls_primerapellido=$this->io_funciones->uf_rellenar_der($ls_primerapellido," ",20);
				$ls_segundoapellido=substr(substr($ls_apeper,$li_pos+1,strlen($ls_apeper)-$li_pos),0,20);
				$ls_segundoapellido=$this->io_funciones->uf_rellenar_der($ls_segundoapellido," ",20);
				$ls_nacper= $this->io_funciones->uf_trim($aa_ds_registro->data["nacper"][$li_i]); //nacionalidad
				if ($ls_nacper=='V')
				{
					$ls_nacper=1;
				}
				else
				{
					$ls_nacper=2;
				}
				$ls_cedper=$this->io_funciones->uf_trim($aa_ds_registro->data["cedper"][$li_i]); //cedula
				$ls_cedper=str_pad(substr($ls_cedper,0,10),10,"0",0); //cedula
				$ls_sexper=$this->io_funciones->uf_trim($aa_ds_registro->data["sexper"][$li_i]);
				if ($ls_sexper=='M')
				{
					$ls_sexper=1;
				}
				else
				{
					$ls_sexper=2;
				}
				$ld_fecnacper=$this->io_funciones->uf_trim($aa_ds_registro->data["fecnacper"][$li_i]);
				$ld_fecnacper=substr($ld_fecnacper,8,2).substr($ld_fecnacper,5,2).substr($ld_fecnacper,0,4); //DDMMAAAA
				$ls_cargo=$aa_ds_registro->data["denasicar"][$li_i];
				$li_pos=strpos($ls_cargo," ");
				if($li_pos===false)
				{
					$li_pos=strlen($ls_cargo);
				}
				$ls_cargo=substr(substr($ls_cargo,0,$li_pos),0,100);
				$ls_cargo=$this->io_funciones->uf_rellenar_der($ls_cargo," ",100);
				$ls_codtipper= $this->io_funciones->uf_trim($aa_ds_registro->data["codtipper"][$li_i]);
				if ($ls_codtipper!='107'||$ls_codtipper!='207'||$ls_codtipper!='207')
				{
					$ls_codtipper=1;
				}
				else
				{
					$ls_codtipper=2;
				}
				$ld_fecingper=$this->io_funciones->uf_trim($aa_ds_registro->data["fecingper"][$li_i]);
				$ld_fecingper=substr($ld_fecingper,8,2).substr($ld_fecingper,5,2).substr($ld_fecingper,0,4); //DDMMAAAA
				$ls_estatus=$aa_ds_registro->data["estper"][$li_i];
				if ($ls_estatus!=1)
				{
					$ls_estatus=2;
				}
				$ls_sueper=$aa_ds_registro->data["sueper"][$li_i];
				$ls_sueper=str_replace(".","",$ls_sueper);
				
				
				$ls_cadena=$ls_primernombre.";".$ls_segundonombre.";".$ls_primerapellido.";".$ls_segundoapellido.";".$ls_nacper.";".$ls_cedper.";".$ls_sexper.";".$ld_fecnacper.";".$ls_cargo.";".$ls_codtipper.";".$ld_fecingper.";".$ls_estatus.";".$ls_sueper."\r\n";
				if ($ls_creararchivo)  //Chequea que el archivo este abierto				
				{
					if (@fwrite($ls_creararchivo,$ls_cadena)===false)//Escritura
					{
						$this->io_mensajes->message("No se puede escribir el archivo ".$ls_nombrearchivo);
						$lb_valido = false;
					}
				}
				else
				{
					$this->io_mensajes->message("Error al abrir el archivo ".$ls_nombrearchivo);
					$lb_valido = false;
				}
			}
			if ($lb_valido)
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("El archivo ".$ls_nombrearchivo." fue creado.");
			}
			else
			{
				@fclose($ls_creararchivo); //cerramos la conexión y liberamos la memoria
				$this->io_mensajes->message("Ocurrio un error al generar el archivo por favor verifique el diskette.");
			}	
		}
		else
		{
			$this->io_mensajes->message("No hay datos que generar.");
			$lb_valido=false;
		}
		return $lb_valido;
	}// end function uf_metodo_fps_venezuela
	//-----------------------------------------------------------------------------------------------------------------------------------
}
?>
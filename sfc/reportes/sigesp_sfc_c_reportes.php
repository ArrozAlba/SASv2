<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sev_c_reportes_cev
 // Autor:       - Ing. Edgar Pastr�n
 // Descripcion: - Clase que inicializa un archivo pdf con el formato de los reportes
 //                del Comite Estadal de la Vivenda.
 // Fecha:       - 07/06/2006
 //////////////////////////////////////////////////////////////////////////////////////////

include ('../../shared/class_folder/class_pdf.php');

class sigesp_sfc_c_reportes
{
//ATRIBUTOS
  var $io_pdf;
  var $io_funcion;
  var $io_msgc;
  var $io_sql;
  var $datoemp;
  var $io_msg;

//FUNCIONES
///////////////////////////////////////////////////////////////////////////////////////////
// CONSTRUCTOR
///////////////////////////////////////////////////////////////////////////////////////////
  function sigesp_sfc_c_reportes($pag,$ori,$tit)
  {
    $this->io_pdf = new class_pdf($pag,$ori);
    $this->io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
    if ($ori=='landscape')
    {$this->io_pdf->set_margenes(20,20,35,20);}
    else
    {$this->io_pdf->set_margenes(30,30,30,20);}
     $this->colocar_cabecera($tit,$ori);
 }
///////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////
// FUNCION QUE COLOCA LA CABECERA DEL REPORTE
///////////////////////////////////////////////////////////////////////////////////////////
  function colocar_cabecera($titulo,$orientacion)
  {
    $ancho_img_izq = 15;
    //$cabecera = $this->io_pdf->openObject();
	  if ($orientacion=='landscape')
	  {
      $this->io_pdf->add_imagen('imagenes/logo.jpeg',-20,-20,100);
	  }else
	  {
	    $this->io_pdf->add_imagen('imagenes/logo.jpeg',-20,-20,100);
	  }
      $this->io_pdf->add_lineas(5);
  }
///////////////////////////////////////////////////////////////////////////////////////////

function add_espacio()
{
 $this->io_pdf->add_lineas(5);
}
function add_imagen()
{
 $this->io_pdf->add_imagen('imagenes/zulhe.jpg','left',450,25);
}

function add_titulo($aligne,$fila,$letra,$titulo)
{
 /*$this->io_pdf->add_texto('center',45,11,"<b>".$titulo."</b>");*/

 /*(JUSTIFICACION � COORDENADAS(COLUMNA),FILA,TAMA�O LETRA,texto)*/
 $this->io_pdf->add_texto($aligne,$fila,$letra,"<b>".$titulo."</b>");
/* $this->io_pdf->add_texto(4,60,11,"<b>MUNICIPIO: ".$mun."</b>");
 $this->io_pdf->add_texto(100,60,11,"<b>ESTADO: ".$est."</b>");*/
/* $this->io_pdf->add_lineas(10);*/
}


function cuerpo_reporte($as_contenido)
{
 //$datos=array(array('datos'=>$as_contenido));
 $la_opciones = array("color_fondo"    => array(255,255,255),
                     "anchos_col"     => array(170),
					 "lineas"         => 0,
					 "tamano_texto"   => 14,
					 "alineacion_col" => array("full"));
$this->io_pdf->add_tabla('center',array($as_contenido),$la_opciones);

 /*$this->io_pdf->ezTable($datos,"","",array('showHeadings'=>0,'shaded'=>0,'showLines'=>0,'maxWidth'=>460));
 $this->io_pdf->add_lineas(3);*/

}

function pie_pagina($nompre,$nomben,$cedben)
{
 $this->io_pdf->add_texto(3,200,14,$nompre);
 $this->io_pdf->add_texto(20,206,14,"Presidente");
 $this->io_pdf->add_texto(100,200,14,$nomben);
 $this->io_pdf->add_texto(110,206,14,"V.- ".$cedben);
}
///////////////////////////////////////////////////////////////////////////////////////////
// FUNCION QUE RETORNA EL OBJETO PDF
///////////////////////////////////////////////////////////////////////////////////////////
  function get_pdf()
  {
    return $this->io_pdf;
  }
///////////////////////////////////////////////////////////////////////////////////////////

function uf_calcular_montocobrado($ls_numfac)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_calcular_montocobrado
	// Parameters:  - $ls_numfac( Numero de factuta).
	// Descripcion: - Funcion que calcula el monto cobrado de una factura a credito dada.
	//////////////////////////////////////////////////////////////////////////////////////////

	require_once ("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$lb_montocobrado=0;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$ls_cadena="SELECT numnot 
	            FROM   sfc_nota 
				WHERE  codtiend='".$ls_codtie."' 
				AND    nro_documento like '%".$ls_numfac."%' 
				AND    estnota='P' 
				AND    tipnot='CXC';";
	$li_numrows=$this->io_sql->select($ls_cadena);
	if($row=$this->io_sql->fetch_row($li_numrows))
	{
		$ls_cadenacobrado="SELECT sum(moncob) AS total 
		                   FROM   sfc_cobrocartaorden 
						   WHERE  codtiend='".$ls_codtie."' 
						   AND    numcob='".$row["numcob"]."' 
						   AND    estcob='C';";
		$li_cobrar=$this->io_sql->select($ls_cadenacobrado);
		if($row_cob=$this->io_sql->fetch_row($li_cobrar))
		{
			if($row_cob["total"]=="")
			{
				$lb_montocobrado=-1;
			}
			else
			{
				$lb_montocobrado=$row_cob["total"];
			}
		}
		else
		{
			$lb_montocobrado=-1;
		}
	}
	else
	{
		$lb_montocobrado=0;
	}

	return $lb_montocobrado;
}


	function uf_calcular_montocobradofac($ls_numfac,$ls_monto)
{
	//////////////////////////////////////////////////////////////////////////////////////////
	// Function:    - uf_calcular_montocobrado
	// Parameters:  - $ls_numfac( Numero de factuta).
	// Descripcion: - Funcion que calcula el monto cobrado de una factura a credito dada.
	//////////////////////////////////////////////////////////////////////////////////////////

	require_once ("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	$this->io_funcion = new class_funciones();
	$io_include = new sigesp_include();
	$io_connect = $io_include->uf_conectar();
	$this->io_sql= new class_sql($io_connect);
	$this->datoemp=$_SESSION["la_empresa"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$lb_montocobrado=0;
	$ls_codemp=$this->datoemp["codemp"];
	$ls_codtie=$_SESSION["ls_codtienda"];

	$ls_cadena="SELECT numnot 
	            FROM   sfc_nota 
	            WHERE  codtiend='".$ls_codtie."' 
				AND    nro_documento like '%".$ls_numfac."%' 
				AND    estnota='P' 
				AND    tipnot='CXC' 
				AND    monto='".$ls_monto."';";

	$li_numrows=$this->io_sql->select($ls_cadena);
	if($row=$this->io_sql->fetch_row($li_numrows))
	{
		$ls_cadenacobrado="SELECT sum(moncob) AS total 
		                   FROM   sfc_cobrocartaorden 
						   WHERE  codtiend='".$ls_codtie."' 
						   AND    numcob='".$row["numcob"]."' 
						   AND    estcob='C';";

		$li_cobrar=$this->io_sql->select($ls_cadenacobrado);
		if($row_cob=$this->io_sql->fetch_row($li_cobrar)){
			if($row_cob["total"]=="")
			{
				$lb_montocobrado=-1;
			}
			else
			{
				$lb_montocobrado=$row_cob["total"];
			}
		}
		else
		{
			$lb_montocobrado=-1;
		}
	}
	else
	{
		$lb_montocobrado=0;
	}

	return $lb_montocobrado;
}
}
?>

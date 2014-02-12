<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sev_c_reportes_cev
 // Autor:       - Ing. Edgar Pastr�n
 // Descripcion: - Clase que inicializa un archivo pdf con el formato de los reportes
 //                del Comite Estadal de la Vivenda.
 // Fecha:       - 07/06/2006
 //////////////////////////////////////////////////////////////////////////////////////////

include ('../../shared/class_folder/class_pdf.php');

class sigesp_sfc_c_factura_libre
{
//ATRIBUTOS
  var $io_pdf;

//FUNCIONES
///////////////////////////////////////////////////////////////////////////////////////////
// CONSTRUCTOR
///////////////////////////////////////////////////////////////////////////////////////////
  function sigesp_sfc_c_factura_libre($pag,$ori,$tit)
  {
    $this->io_pdf = new class_pdf($pag,$ori);
    $this->io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
    $this->io_pdf->set_margenes(10,10,10,10);

     $this->colocar_cabecera($tit,$ori);
 }
///////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////
// FUNCION QUE COLOCA LA CABECERA DEL REPORTE
///////////////////////////////////////////////////////////////////////////////////////////
  function colocar_cabecera($titulo,$orientacion)
  {
    //$ancho_img_izq = 15;
    //$cabecera = $this->io_pdf->openObject();
	  //$this->io_pdf->add_imagenfac('imagenes/logo_ECISA.jpg',-20,-30,50);
	  $this->io_pdf->add_lineas(5);
  }
///////////////////////////////////////////////////////////////////////////////////////////

function add_espacio()
{
 $this->io_pdf->add_lineas(8);
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
}
?>

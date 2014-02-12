<?php
 //////////////////////////////////////////////////////////////////////////////////////////
 // Clase:       - sev_c_reportes_cev
 // Autor:       - Ing. Edgar Pastrán
 // Descripcion: - Clase que inicializa un archivo pdf con el formato de los reportes
 //                del Comite Estadal de la Vivenda.
 // Fecha:       - 07/06/2006     
 //////////////////////////////////////////////////////////////////////////////////////////

include ('../../../shared/class_folder/class_pdf.php');

class sfv_c_reportes
{
//ATRIBUTOS  
  var $io_pdf;
  
//FUNCIONES
/////////////////////////////////////////////////////////////////////////////////////////// 
// CONSTRUCTOR  
///////////////////////////////////////////////////////////////////////////////////////////    
  function sfv_c_reportes($pag,$ori,$tit)
  {
    $this->io_pdf = new class_pdf($pag,$ori);
    $this->io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
    if ($ori=='landscape')
    {$this->io_pdf->set_margenes(20,45,35,20);}
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
    $ancho_img_izq = 25;
    //$cabecera = $this->io_pdf->openObject();
      $this->io_pdf->add_imagen('../../reportes/imagenes/logo_ECISA.jpg','right',0,$ancho_img_izq);
      $this->io_pdf->add_texto('right',25,7,"EDIFICIO PORFIN CALLE PAEZ C/CSILVA");
	  $this->io_pdf->add_texto('right',28,7,"MEZZANINA Nº 03 SAN CARLOS ESTADO COJEDES");
	  $this->io_pdf->add_texto('right',31,7,"TELEFONO: (0258)-433.16.86");
	  $this->io_pdf->add_texto('left',10,7,"Olga Mairim Nuñez");
	  $this->io_pdf->add_texto('left',13,7,"Abogado");
	  $this->io_pdf->add_texto('left',18,7,"Inpre. No. 96899");
    /*$this->io_pdf->closeObject();
    $this->io_pdf->addObject($cabecera,'all');*/
        
  
    $this->io_pdf->add_linea(0,35,165,35,1);
    $this->io_pdf->add_lineas(5);
  }
///////////////////////////////////////////////////////////////////////////////////////////
function add_espacio()
{
 $this->io_pdf->add_lineas(5);
}

function add_titulo($titulo,$con,$mun,$est)
{
 $this->io_pdf->add_texto('center',45,11,"<b>".$titulo."</b>");
 $this->io_pdf->add_texto(100,55,11,"<b>CONTRATO: ".$con."</b>");
 $this->io_pdf->add_texto(4,60,11,"<b>MUNICIPIO: ".$mun."</b>");
 $this->io_pdf->add_texto(100,60,11,"<b>ESTADO: ".$est."</b>");
 $this->io_pdf->add_lineas(10);
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
<?Php
require_once("../../../shared/class_folder/class_pdf.php");

class sps_reporte_base
{
  
  private $ao_pdf;
  private $aa_color_cabecera_tabla = array(220,220,220);
  private $aa_color_detalle_tabla = array(255,255,255);
  
  public function sps_reporte_base($ps_titulo,$ps_papel,$ps_orirntacion) // Contructor de la clase
  {
    //Instanciamos el documento  //$this->ao_pdf  = new class_pdf('LETTER','portrait');
    $ls_orientacion= "'".$ps_orirntacion."'";
	$ls_papel = "'".$ps_papel."'";
	$this->ao_pdf  = new class_pdf($ps_papel,$ps_orirntacion);
	$this->ao_pdf  = new class_pdf();
    //Configuramos la pagina
	$this->ao_pdf->selectFont('../../../shared/class_folder/ezpdf/fonts/Helvetica.afm');
	$this->ao_pdf->set_margenes(15,15,25,15);
	$this->ao_pdf->numerar_paginas(10);
	//Colocamos el titulo a la pagina y redimensionamos los margenes
    $lo_titulo = $this->ao_pdf->openObject();
	  $this->ao_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
      $this->ao_pdf->add_linea(0,10,$this->ao_pdf->get_ancho_area_trabajo(),10,2);
      $this->ao_pdf->add_texto('center',0,16,"<b><i>".$ps_titulo."</i></b>");
      $this->ao_pdf->add_texto('right',0,10,"<b><i>Fecha: ".date("d/m/Y")."</i></b>");
      $this->ao_pdf->add_texto('right',5,10,"<b><i>Hora: ".date("h:i a   ")."</i></b>");
    $this->ao_pdf->closeObject();
    $this->ao_pdf->addObject($lo_titulo,'all');
    $this->ao_pdf->set_margenes(30,15,25,15);    
    return $this->ao_pdf;
  }
  
  public function getPdf()
  {
    return $this->ao_pdf;
  }
  
  public function getColorCabeceraTabla()
  {
    return $this->aa_color_cabecera_tabla;
  }
  
  public function getColorDetalleTabla()
  {
    return $this->aa_color_detalle_tabla;
  }
}
?>
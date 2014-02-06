/*  JavaScript library
 *  (c) 2006 Ing. Edgar M. Pastrán C.
 *  edgar_pastran@yahoo.es 
 *
 *  Libreria usada para colocar una barra de herramientas generica 
 *  pero configurable en aplicaciones web
/*--------------------------------------------------------------------------*/

  var botones = $F('hidbotonera').toLowerCase();
  
  _scrollAmount=5;
  _scrollDelay=10;
  _menuCloseDelay=500;
  _menuOpenDelay=150;
  _subOffsetTop=0;
  _subOffsetLeft=0;
  
  with(horizStyle=new mm_style())
  {
    bordercolor='#8A867A';
	borderstyle='solid';
	borderwidth=1;
	offbgcolor='#FFFFFF';
	offcolor='#000000';
	onbgcolor='#FFFFEE';
	onborder='1px solid #000080';
	oncolor='#000000';
	padding=5;separatorsize=1;
	separatorpadding=5;
  }
  
  with(milonic=new menuname('mainmenu2'))
  {
    top=70;
	left = ((screen.width - 800)/2) + 18;
	style = horizStyle;
	alwaysvisible = 1;
	orientation='horizontal';
	margin=3;
	followscroll=1;
    var imagenes = '../../../shared/imagebank/tools20/';
    if (botones.indexOf('n', 0) != -1)
    {aI('url=javascript:ue_nuevo();image='+imagenes+'nuevo.gif;title=Nuevo;');}
    if (botones.indexOf('g', 0) != -1)
    {aI('url=javascript:ue_guardar();image='+imagenes+'grabar.gif;title=Grabar;');}
    if (botones.indexOf('b', 0) != -1)
    {aI("url=javascript:ue_buscar();image="+imagenes+"buscar.gif;title=Buscar;");}
    if (botones.indexOf('i', 0) != -1)
    {aI("url=javascript:ue_imprimir();image="+imagenes+"imprimir.gif;title=Imprimir;");}
    if (botones.indexOf('e', 0) != -1)
    {aI("url=javascript:ue_eliminar();image="+imagenes+"eliminar.gif;title=Eliminar;");}
    if (botones.indexOf('c', 0) != -1)
    {aI("url=javascript:ue_cancelar();image="+imagenes+"deshacer.gif;title=Cancelar;");}
    if (botones.indexOf('s', 0) != -1)
    {aI("url=javascript:ue_salir();image="+imagenes+"salir.gif;title=Salir;");}
  }
  
  function ue_salir()
  {location.href = "sigespwindow_blank.php";}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>Formulación- Problemas</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script type="text/javascript" src="../../librerias/js/ext/adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="../../librerias/js/ext/ext-all.js"></script><script type="text/javascript" src="../../librerias/js/menu/menuempresa.js"></script>
  <script type="text/javascript" src="../../librerias/js/general/json2.js"></script>
  <script type="text/javascript" src="../js/sigesp_sfp_empresa.js"></script>
  <script type="text/javascript" language="JavaScript" src="../../librerias/js/general/funciones.js"></script>
  <script type="text/javascript" language="JavaScript" src="../catalogos/catalogoEmp.js"></script>
   <link href="../../otros/css/tablas.css" rel="stylesheet" type="text/css">
  <link href="../../otros/css/ventanas.css" rel="stylesheet" type="text/css">
  <link href="../../otros/css/cabecera.css" rel="stylesheet" type="text/css">
  <link href="../../otros/css/general.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../../librerias/js/ext/resources/css/ext-all.css">
  <link rel="stylesheet" type="text/css" href="../../otros/css/ExtStart.css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" id="norte">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu" id='toolbar'></td>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
	  <td class="Botonera">
		    <img src="../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" id="BtnNuevo" >
		    <img src="../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" id="BtnGrabar">
		    <img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" id="BtnCat">
		    <img src="../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" id="BtnElim">
		     <img src="../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" id="BtnImp">
		    <img src="../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" id="BtnSalir">
		    <img src="../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" id="BtnAyu">
	  </td>
  </tr>
</table>
<div id="centro">
<div class="titulo-pantalla">Definici&oacute;n de Empresa</div>
<form name="form1" method="post" action="" id="form1">

<div id='tabs0'>
</div>
  
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="588" id="tabs1" class="x-hide-display">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <table class="formato-blanco" align="center" border="0" cellpadding="0" cellspacing="5" width="566">
          <tbody>
              <tr class="formato-blanco">
              <td height="19" width="111">&nbsp;</td>
              <td height="22" width="408"><input name="txtempresa" id="txtempresa" value="" type="hidden"> <input name="txtnombrevie" id="txtnombrevie2" type="hidden"></td>
            </tr>
            <tr class="formato-blanco">

              <td height="29">
              <div align="right">C&oacute;digo</div>
              </td>

              <td height="22"><input name="txtcod" id="codemp" value="" size="15" maxlength="15" style="text-align: center;" title="C&oacute;digo" readonly="readonly" type="text"> <input name="codemp" id="codemp" value="0001" type="hidden"> <input name="actualizar" id="actualizar" type="hidden">
              </td>

            </tr>

            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Denominaci&oacute;n</div>
              </td>
              <td height="22"><input name="txtden" id="nombre" value="" size="50" maxlength="100" title="Denominaci&oacute;n" type="text">
			  </td>
            </tr>
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">RIF</div>
              </td>
              <td height="22">
              <input name="txtcod" id="rifemp" value="" size="15" maxlength="15" style="text-align: center;" title="C&oacute;digo"  type="text">

			  </td>
            </tr>	
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">NIT</div>
              </td>
              <td height="22">
             	 <input name="nit" id="nitemp" value="" size="15" maxlength="15" style="text-align: center;" title="C&oacute;digo" type="text">
			  </td>
            </tr>
             
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Forma Juridica</div>
              </td>
              <td height="22">
              	 <input name="Forma Juridica" id="forma_juri" value="" size="30" maxlength="30" style="text-align: center;" title="C&oacute;digo" type="text">

			  </td>
            </tr>	
          
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Año de Inicio</div>
              </td>
              <td height="22">
              	 <input name="Año de Inicio" id="ano_inicio" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
              	 <input  id="emprin"  type="hidden">
              	 

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Sector</div>
              </td>
              <td height="22">
              	 <input name="Sector" id="sector" value="" size="40" maxlength="40" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Base Legal</div>
              </td>
              <td height="22">
              	 <input name="Base Legal" id="base_legal" value="" size="50" maxlength="50" style="text-align: center;"  type="text">
			  </td>
            </tr>
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Consolidadora</div>
              </td>
              <td height="22">
              	 <input name="Consolidadora" id="consolidadora" value="" size="15" maxlength="15" style="text-align: center;"  type="checkbox">
			  </td>
            </tr>
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Empresa Donde Consolida</div>
              </td>
              <td height="22">
              	 <select id='codemp_con'>
              	 	<option value=''>Seleccione Una</option>
              	 </select>
			  </td>
            </tr>						
            						
            						
          </tbody>
        </table>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="588" id="tabs2" class="x-hide-display">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <table class="formato-blanco" align="center" border="0" cellpadding="0" cellspacing="5" width="566">
          <tbody>
              <tr class="formato-blanco">
              <td height="19" width="111">&nbsp;</td>
              <td height="22" width="408"><input name="txtempresa" id="txtempresa" value="" type="hidden"> <input name="txtnombrevie" id="txtnombrevie2" type="hidden"></td>
            </tr>
            <tr class="formato-blanco">

              <td height="29">
              <div align="right">Misión</div>
              </td>

              <td height="22"><textarea name="mision" id="mision" rows="10" cols="55" size="25"  maxlength="15"  title="C&oacute;digo">dddd"\n";</textarea><input name="codemp" id="codemp" value="0001" type="hidden"> 
              </td>
            </tr>
             <tr class="formato-blanco">
              <td height="29">
              <div align="right">Visión</div>
              </td>
              <td height="22"><textarea name="vision" id="vision" rows="10" cols="55" size="25"  maxlength="15"  title="C&oacute;digo"></textarea> <input name="codemp" id="codemp" value="0001" type="hidden"> 
              </td>
          </tbody>
        </table>   
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="588" id="tabs5" class="x-hide-display">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <table class="formato-blanco" align="center" border="0" cellpadding="0" cellspacing="5" width="566">
          <tbody>
              <tr class="formato-blanco">
              <td height="19" width="111">&nbsp;</td>
              <td height="22" width="408"><input name="txtempresa" id="txtempresa" value="" type="hidden"> <input name="txtnombrevie" id="txtnombrevie2" type="hidden"></td>
            </tr>
            <tr class="formato-blanco">

              <td height="29">
              <div align="right">Composición del Patrimonio</div>
              </td>

              <td height="22"><textarea name="mision" id="compat" rows="4" cols="55" size="25"  maxlength="15"  title="C&oacute;digo"></textarea><input name="codemp" id="codemp" value="0001" type="hidden"> 
              </td>

            </tr>
             <tr class="formato-blanco">
              <td height="29">
              <div align="right">Política Presupuestaria</div>
              </td>
              <td height="22"><textarea name="vision" id="politicapre" rows="10" cols="55" size="25"  maxlength="15"  title="C&oacute;digo"></textarea> <input name="codemp" id="codemp" value="0001" type="hidden"> 
              </td>
          </tbody>
        </table>   
        
        
        
       <table align="center" border="0" cellpadding="0" cellspacing="0" width="588" id="tabs3" class="x-hide-display">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
        <table class="formato-blanco" align="center" border="0" cellpadding="0" cellspacing="5" width="566">
          <tbody>
              <tr class="formato-blanco">
              <td height="19" width="111">&nbsp;</td>
              <td height="22" width="408"><input name="txtempresa" id="txtempresa" value="" type="hidden"> <input name="txtnombrevie" id="txtnombrevie2" type="hidden"></td>
            </tr>
            <tr class="formato-blanco">

              <td height="29">
              <div align="right">Estado</div>
              </td>

              <td height="22"><input name="Estado" id="estemp" value="" size="15" maxlength="15" style="text-align: center;" title="C&oacute;digo"  type="text"> <input name="codemp" id="codemp" value="0001" type="hidden"> <input name="actualizar" id="actualizar" type="hidden">
              </td>

            </tr>

            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Ciudad</div>
              </td>
              <td height="22"><input name="Ciudad" id="ciuemp" value="" size="50" maxlength="100" title="ciudad" type="text">
			  </td>
            </tr>
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Dirección</div>
              </td>
              <td height="22">
              <textarea name="direc" id="diremp" rows="10" cols="55" size="25"  maxlength="15" id="direccion"></textarea>
			  </td>
            </tr>	
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
             	 <input name="tel" value="" size="15" maxlength="15" style="text-align: center;" title="tel" type="text" id="telemp">
			  </td>
            </tr>       
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Fax</div>
              </td>
              <td height="22">
              	 <input name="fax" id="faxemp" value="" size="15" maxlength="15" style="text-align: center;" title="fax" type="text">
			  </td>
            </tr>	
          
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Sitio Web</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="website" value="" size="30" maxlength="30" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electronico</div>
              </td>
              <td height="22">
              	 <input name="email" id="email" value="" size="30" maxlength="30" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Zona Postal</div>
              </td>
              <td height="22">
              	 <input name="Zona Postal" id="zonpos" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>
        
<div id="panelaccordion" class="x-hide-display">
		  <table id="datpresi" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Nombre</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="nom_presi" value="" size="30" maxlength="30" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
              	 <input name="email" id="tel_presi" value="" size="20" maxlength="20" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electrónico</div>
              </td>
              <td height="22">
              	 <input name="email_presi" id="email_presi" value="" size="40" maxlength="40" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>

		<table id="datdirplan" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Nombre</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="nom_dirplan" value="" size="40" maxlength="40" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
              	 <input name="email" id="tel_dirplan" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electrónico</div>
              </td>
              <td height="22">
              	 <input name="email_presi" id="email_dirplan" value="" size="30" maxlength="30" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>
        <table id="datdiradmin" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Nombre</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="nom_diradmin" value="" size="40" maxlength="40" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
              	 <input name="email" id="tel_diradmin" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electrónico</div>
              </td>
              <td height="22">
              	 <input name="email_diradmin" id="email_diradmin" value="" size="30" maxlength="30" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>
        
        
        <table id="datdirrh" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Nombre</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="nom_dirrh" value="" size="40" maxlength="40" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
              	 <input name="email" id="tel_dirrh" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electrónico</div>
              </td>
              <td height="22">
              	 <input name="email_presi" id="email_dirrh" value="" size="30" maxlength="30" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>
        
         <table id="datanres" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Nombre</div>
              </td>
              <td height="22">
              	 <input name="Sitio Web" id="nom_respre" value="" size="40" maxlength="40" style="text-align: center;"  type="text">

			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Teléfono</div>
              </td>
              <td height="22">
              	 <input name="email" id="tel_respre" value="" size="15" maxlength="15" style="text-align: center;"  type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Correo Electrónico</div>
              </td>
              <td height="22">
              	 <input name="email_respre" id="email_respre" value="" size="30" maxlength="30" style="text-align: center;"  type="text">
			  </td>
            </tr>
             </tbody>
        </table>
  </div>      
  </td>
      </tr>
        <tr>
        <td>&nbsp;</td>
	 	<table>
		<tbody>
		<tr>
						<!-- container for the existing markup tabs -->
			<!--   	<div id="tabs1" align="center" >
					<div id="caracteristicas" class="x-hide-display">
						<textarea cols="100" rows="10" name="caracteristicas" id="caracteristicas"></textarea>
					</div>
					<div id="causas" class="x-hide-display">
						<textarea cols="100" rows="10" name="causas" id="causas"></textarea>
					</div>	
					<div id="efectos" class="x-hide-display">
						<textarea cols="100" rows="10" name="efectos" id="efectos"></textarea>
					</div>		
						
				</div> -->
		</tr>
		</tbody>
		</table>
      </tr>

    </tbody>
  </table>
  
 <input name="operacion" id="operacion" type="hidden"> 
</form>
<div id="tabs4" class="x-hide-display"></div>

<div id="tabs6" class="x-hide-display">
	    <table id="" width="400">        
             <tr class="formato-blanco">
              <td height="28">
              <div align="right">Presupuesto de Ingreso</div>
              </td>
              <td height="22">
              	 <input name="formpre" id="formspi" value="" size="40" maxlength="40"   type="text">
			  </td>
            </tr>						
            <tr class="formato-blanco">
              <td height="28">
              <div align="right">Presupuesto de Gastos</div>
              </td>
              <td height="22">
              	 <input name="formspi" id="formpre" value="" size="40" maxlength="40"  type="text">
			  </td>
            </tr>
        </table>
</div>


</div>
</body>
</html>
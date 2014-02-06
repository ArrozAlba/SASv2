<script type="text/javascript" language="JavaScript" src="../../librerias/js/general/funciones.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/ext-all.js"></script><script type="text/javascript" src="../../librerias/js/menu/sigesp_mcd_vis_menu.js"></script>
<script type="text/javascript" src="../../librerias/js/general/json2.js"></script>
<script type="text/javascript" src="../js/sigesp_spe_inteprog.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_catubgeo.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_Catprob.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_Catmetas.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_Catesadmin.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_plangastos.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_distmetas.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_catestructuras.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_catplan.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/adapter/locale/ext-lang-es.js"></script>
<link href="../../otros/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../otros/css/general.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../librerias/js/ext/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="../../otros/css/ExtStart.css">
<style>
	.combosEstPlan
	{
			float:left;
			margin-right:5px
	}
	.combosEstPlan2
	{
			float:left;
			margin-right:170px
	}
	.DivContCombos
	{
		width:700px;	
	}
	.DivContCombos div
	{
		float:left;
		margin-right:5px	
	}
	
</style>

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
		    <img src="../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" id="BtnNuevo">
		    <img src="../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" id="grabar">	
		    <img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" id="BtnCat">
		    <img src="../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" id="BtnElim">
		     <img src="../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" id="BtnImp">
		    <img src="../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" id="BtnSalir">
		    <img src="../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" id="BtnAyu">
	  </td>
  </tr>
</table>

<div id="tabs1">
<!--  <img src="../../otros/imagenes/file-add.gif" id="LlamarCatalogosTabs1">
<img src="../../otros/imagenes/file-remove.gif" id="BtnGrabarTabs1">-->
<div id="grid-uniads" class="x-hide-display"></div>
<div id="grid-ubgeos" class="x-hide-display"></div>
<div id="grid-probs" class="x-hide-display"></div>
<div id="grid-vars" class="x-hide-display"></div>
</div>

<div id="ImgSumar">
</div>

<div id="ImgRestar">
</div>

<div id="grid-montos">
</div>



<div id="tabs">
</div>

<div id="tabs2">

<!--  	<img src="../../otros/imagenes/file-add.gif" id="LlamarCatalogosTabs2">
	<img src="../../otros/imagenes/file-remove.gif" id="ResFuente">-->
	<div id="grid-Gastos" class="x-hide-display"></div>
	<div id="grid-ind" class="x-hide-display"></div>	
</div>


<div id="grid-fuentes2"></div>
<div id="grid-fuentes20" style="height:150px"></div>

<div id="DivEstSfp" class="DivContCombos">
	<div id='combo11'></div>
	<div id='combo12'></div>
	<div id='combo13'></div>
	<div id='combo14'></div>
	<div id='combo15'></div>
</div>

<div id="progra"></div>

<div id="sur">esto es el sur </div>
<div id="center1">Esto es el norte</div>
<div id="center2">Esto es el sur</div>


<div id="etiqueta" class="x-hide-display">
</div>

<div id="comboOrgano" class="x-hide-display">
</div>

<div id="DivUb" class="DivContCombos">
	<div id='combo6' class="combosEstPlan2">oo</div>
	<div id='combo7' class="combosEstPlan"></div>
	<div id='combo8' class="combosEstPlan"></div>
	<div id='combo9' class="combosEstPlan"></div>
	<div id='combo10' class="combosEstPlan"></div>
</div>

<div id="PlanSeleccionado">
 <!--  <img src="../../otros/imagenes/file-add.gif" id="SumEstPla">
 <img src="../../otros/imagenes/file-remove.gif" id="ResEstPla">-->
</div>
<div id="EstSeleccionado">
<!--  <img src="../../otros/imagenes/file-add.gif" id="SumEstPre">
<img src="../../otros/imagenes/file-remove.gif" id="ResEstPre"> -->
</div>

<div id='formestproPlan'>
	<table>
	<tr>
		<td id='nivelPlan1' class="NombreNivel2">
		
		</td>
		<td id='valorPlannivel1' class="ValorNivel2">
		</td>
	</tr>
	<tr>
		<td id='nivelPlan2' class="NombreNivel2">
		
		</td>
		<td id='valorPlannivel2' class="ValorNivel2">
		</td>
	</tr>
	<tr>
		<td id='nivelPlan3' class="NombreNivel2">
		
		</td>
		<td id='valorPlannivel3' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelPlan4' class="NombreNivel2">
		</td>
		<td id='valorPlannivel4' class="ValorNivel2">
	
		</td>
	</tr>
		<tr>
		<td id='nivelPlan5' class="NombreNivel2">
		</td>
		<td id='valorPlannivel5' class="ValorNivel2">
	
		</td>
	</tr>

</table>
</div>


<div id='formestproPre'>
	<table>
	<tr>
		<td id='nivelPre1' class="NombreNivel2">
				
		</td>
		<td id='valornivel1' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelPre2' class="NombreNivel2">
		
		</td>
		<td id='valornivel2' class="ValorNivel2">
		</td>
	</tr>
	<tr>
		<td id='nivelPre3' class="NombreNivel2">
		
		</td>
		<td id='valornivel3' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelPre4' class="NombreNivel2">
		</td>
		<td id='valornivel4' class="ValorNivel2">
	
		</td>
	</tr>
		<tr>
		<td id='nivelPre5' class="NombreNivel2">
		</td>
		<td id='valornivel5' class="ValorNivel2">
	
		</td>
	</tr>

</table>
</div>

<div id='formestproAd'>
	<table>
	<tr>
		<td id='nivelAd1' class="NombreNivel2">	
		</td>
		<td id='valorAdnivel1' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelAd2' class="NombreNivel2">
		</td>
		<td id='valorAdnivel2' class="ValorNivel2">
		</td>
	</tr>
	<tr>
		<td id='nivelAd3' class="NombreNivel2">
		
		</td>
		<td id='valorAdnivel3' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelAd4' class="NombreNivel2">
		</td>
		<td id='valorAdnivel4' class="ValorNivel2">
	
		</td>
	</tr>
		<tr>
		<td id='nivelAd5' class="NombreNivel2">
		</td>
		<td id='valorAdnivel5' class="ValorNivel2">
		</td>
	</tr>

</table>
</div>


<div id='formestproUb'>
	<table>
	<tr>
		<td id='nivelUb1' class="NombreNivel2">	
		</td>
		<td id='valorUbnivel1' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelUb2' class="NombreNivel2">
		</td>
		<td id='valorUbnivel2' class="ValorNivel2">
		</td>
	</tr>
	<tr>
		<td id='nivelUb3' class="NombreNivel2">
		
		</td>
		<td id='valorUbnivel3' class="ValorNivel2">
	
		</td>
	</tr>
	<tr>
		<td id='nivelUb4' class="NombreNivel2">
		</td>
		<td id='valorUbnivel4' class="ValorNivel2">
	
		</td>
	</tr>
		<tr>
		<td id='nivelUb5' class="NombreNivel2">
		</td>
		<td id='valorUbnivel5' class="ValorNivel2">
		</td>
	</tr>

</table>
</div>




<div id="tabs7">
</div>
	<div id="gridPlan0" class="x-hide-display"></div>
	<div id="gridPlan1" class="x-hide-display" ></div>
	<div id="gridPlan2" class="x-hide-display"></div>
	<div id="gridPlan3" class="x-hide-display"></div>
	<div id="gridPlan4" class="x-hide-display"></div>
	
	
	<div id="tabs17">
	</div>
	<div id="gridPre0" class="x-hide-display"></div>
	<div id="gridPre1" class="x-hide-display" ></div>
	<div id="gridPre2" class="x-hide-display"></div>
	<div id="gridPre3" class="x-hide-display"></div>
	<div id="gridPre4" class="x-hide-display"></div>

<div id="tabsAd">
</div>
	<div id="gridAd0" class="x-hide-display"></div>
	<div id="gridAd1" class="x-hide-display" ></div>
	<div id="gridAd2" class="x-hide-display"></div>
	<div id="gridAd3" class="x-hide-display"></div>
	<div id="gridAd4" class="x-hide-display"></div>	
	
<div id="tabsUb">
</div>
	<div id="gridUb0" class="x-hide-display"></div>
	<div id="gridUb1" class="x-hide-display" ></div>
	<div id="gridUb2" class="x-hide-display"></div>
	<div id="gridUb3" class="x-hide-display"></div>
	<div id="gridUb4" class="x-hide-display"></div>		
	
	





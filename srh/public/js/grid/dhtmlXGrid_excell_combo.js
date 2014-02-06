/*dhtmlxGrid v.1.4 build 70813 Standard Edition
Copyright Scand LLC http://www.scbr.com
This version of Software is free for using in GPL applications. For commercial use please contact info@scbr.com to obtain license*/
 
function eXcell_combo(cell){try{this.cell = cell;this.grid = this.cell.parentNode.grid;}catch(er){};this.edit = function(){val = this.getValue();this.cell.innerHTML="";dhx_globalImgPath="combo/img/";this.obj = new dhtmlXCombo(this.cell,"combo",this.cell.offsetWidth-2);this.obj.DOMelem.style.border = "0";this.obj.DOMelem.style.height = "18px";switch(this.cell.loadingMode){case "0":
 var selfc=this;this.obj.loadXML(this.cell._url,function(){selfc.obj.setComboValue(val);})
 break 
 case "1":
 this.obj.enableFilteringMode(true,this.cell._url,true,true)
 break
 case "2":
 for(var i = 0;i < options.length;i++){this.obj.addOption(i,options[i].firstChild.data) 
 };break 
 };if(this.cell.loadingMode == "0")this.obj.setComboText("")
 };this.getValue = function(val){return this.cell.innerHTML.toString();};this.setValue = function(val){if (typeof(val)=="object")
 {if (!val.tagName){switch(val.type){case 1:
 this.cell.loadingMode="1";this.cell._url=val.url;break;default:
 this.cell.loadingMode="0";this.cell._url=val.url;break;};val=val.value||"";}else{this.cell.loadingMode = val.getAttribute("xmlcontent")
 if(this.cell.loadingMode == "2"){options=this.grid.xmlLoader.doXPath(".//option",val);val = options[0].firstChild.data
 }else{var childNumber = val.childNodes.length
 
 for(var i = 0;i < childNumber ;i++){if(val.childNodes[i].tagName=="url")this.cell._url=val.childNodes[i].childNodes[0].data
 };for(var i = 0;i < childNumber;i++){if((typeof(val)=="object") && (val.childNodes[i].tagName=="value"))
 val = val.childNodes[i].childNodes[0].data
 };};};};this.setCValue(val);};this.detach = function(){if (!this.obj.getComboText()|| this.obj.getComboText().toString()._dhx_trim()==""){this.setCValue("");}else this.setCValue(this.obj.getComboText(),this.obj.getActualValue())
 this.cell._cval=this.obj.getActualValue();this.obj.closeAll();return true;};};eXcell_combo.prototype = new eXcell;

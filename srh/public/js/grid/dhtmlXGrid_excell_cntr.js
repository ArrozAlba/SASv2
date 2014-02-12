/*dhtmlxGrid v.1.4 build 70813 Standard Edition
Copyright Scand LLC http://www.scbr.com
This version of Software is free for using in GPL applications. For commercial use please contact info@scbr.com to obtain license*/
 

 
 
function eXcell_cntr(cell){this.cell = cell;this.grid = this.cell.parentNode.grid;if ((this.grid.setOnOpenEndHandler)&&(!this.grid._ex_cntr_ready)){this.grid._ex_cntr_ready=true;this.grid.setOnOpenEndHandler(function(id){this.resetCounter(0);});};this.edit = function(){};this.getValue = function(){return this.cell.parentNode.rowIndex;};this.setValue = function(val){this.cell.style.paddingRight = "2px";var cell=this.cell;window.setTimeout(function(){var val=cell.parentNode.rowIndex;if (val<0)val=cell.parentNode.grid.rowsCol._dhx_find(cell.parentNode)+1;cell.innerHTML = val;if (cell.parentNode.grid._fake)cell.parentNode.grid._fake.cells(cell.parentNode.idd,cell._cellIndex).setValue(val);cell=null;},100);};};dhtmlXGridObject.prototype.resetCounter=function(ind){for (var i=0;i<this.rowsCol.length;i++)this.rowsCol[i].cells[ind].innerHTML=i+1;};eXcell_cntr.prototype = new eXcell;

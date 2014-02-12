Source of gridintab.js: 
// vim: sw=4:ts=4:nu:nospell:fdc=4 
/** 
* Grid in an inactive tab example 
* 
* @author Ing. Jozef Sakáloš 
* @copyright (c) 2008, by Ing. Jozef Sakáloš 
* @date 10. April 2008 
* @version $Id: gridintab.js 17 2008-04-24 14:57:16Z jozo $ 
* 
* @license gridintab.js is licensed under the terms of the Open Source 
* LGPL 3.0 license. Commercial use is permitted to the extent that the 
* code/component(s) do NOT become part of another Open Source or Commercially 
* licensed development library or toolkit without explicit permission. 
* 
* License details: http://www.gnu.org/licenses/lgpl.html 
*/ 
  
/*global Ext, Example */ 
  
Ext.ns('Example'); 
  
Ext.BLANK_IMAGE_URL = './ext/resources/images/default/s.gif'; 
  
Example.Grid = Ext.extend(Ext.grid.GridPanel, { 
initComponent:function() { 
Ext.apply(this, { 
store: new Ext.data.SimpleStore({ 
id:0 
,fields:[ 
{name: 'company'} 
,{name: 'price', type: 'float'} 
,{name: 'change', type: 'float'} 
,{name: 'pctChange', type: 'float'} 
,{name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'} 
,{name: 'industry'} 
,{name: 'desc'} 
] 
,data:[ 
['3m Co',71.72,0.02,0.03,'8/1 12:00am', 'Manufacturing'], 
['Alcoa Inc',29.01,0.42,1.47,'9/1 12:00am', 'Manufacturing'], 
['Altria Group Inc',83.81,0.28,0.34,'10/1 12:00am', 'Manufacturing'], 
['American Express Company',52.55,0.01,0.02,'9/1 10:00am', 'Finance'], 
['American International Group, Inc.',64.13,0.31,0.49,'9/1 11:00am', 'Services'], 
['AT&T Inc.',31.61,-0.48,-1.54,'9/1 12:00am', 'Services'], 
['Boeing Co.',75.43,0.53,0.71,'9/1 12:00am', 'Manufacturing'], 
['Caterpillar Inc.',67.27,0.92,1.39,'9/1 12:00am', 'Services'], 
['Citigroup, Inc.',49.37,0.02,0.04,'9/1 12:00am', 'Finance'], 
['E.I. du Pont de Nemours and Company',40.48,0.51,1.28,'9/1 12:00am', 'Manufacturing'], 
['Exxon Mobil Corp',68.1,-0.43,-0.64,'9/1 12:00am', 'Manufacturing'], 
['General Electric Company',34.14,-0.08,-0.23,'9/1 12:00am', 'Manufacturing'], 
['General Motors Corporation',30.27,1.09,3.74,'9/1 12:00am', 'Automotive'], 
['Hewlett-Packard Co.',36.53,-0.03,-0.08,'9/1 12:00am', 'Computer'], 
['Honeywell Intl Inc',38.77,0.05,0.13,'9/1 12:00am', 'Manufacturing'], 
['Intel Corporation',19.88,0.31,1.58,'9/1 12:00am', 'Computer'], 
['International Business Machines',81.41,0.44,0.54,'9/1 12:00am', 'Computer'], 
['Johnson & Johnson',64.72,0.06,0.09,'9/1 12:00am', 'Medical'], 
['JP Morgan & Chase & Co',45.73,0.07,0.15,'9/1 12:00am', 'Finance'], 
['McDonald\'s Corporation',36.76,0.86,2.40,'9/1 12:00am', 'Food'], 
['Merck & Co., Inc.',40.96,0.41,1.01,'9/1 12:00am', 'Medical'], 
['Microsoft Corporation',25.84,0.14,0.54,'9/1 12:00am', 'Computer'], 
['Pfizer Inc',27.96,0.4,1.45,'9/1 12:00am', 'Services', 'Medical'], 
['The Coca-Cola Company',45.07,0.26,0.58,'9/1 12:00am', 'Food'], 
['The Home Depot, Inc.',34.64,0.35,1.02,'9/1 12:00am', 'Retail'], 
['The Procter & Gamble Company',61.91,0.01,0.02,'9/1 12:00am', 'Manufacturing'], 
['United Technologies Corporation',63.26,0.55,0.88,'9/1 12:00am', 'Computer'], 
['Verizon Communications',35.57,0.39,1.11,'9/1 12:00am', 'Services'], 
['Wal-Mart Stores, Inc.',45.45,0.73,1.63,'9/1 12:00am', 'Retail'], 
['Walt Disney Company (The) (Holding Company)',29.89,0.24,0.81,'9/1 12:00am', 'Services'] 
] 
}) 
,columns:[ 
{id:'company',header: "Company", width: 40, sortable: true, dataIndex: 'company'} 
,{header: "Price", width: 20, sortable: true, renderer: Ext.util.Format.usMoney, dataIndex: 'price'} 
,{header: "Change", width: 20, sortable: true, dataIndex: 'change'} 
,{header: "% Change", width: 20, sortable: true, dataIndex: 'pctChange'} 
,{header: "Last Updated", width: 20, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'} 
] 
,viewConfig:{forceFit:true} 
}); // eo apply 
  
// call parent 
Example.Grid.superclass.initComponent.apply(this, arguments); 
} // eo function initComponent 
  
}); 
  
Ext.reg('examplegrid', Example.Grid); 
// application main entry point 
Ext.onReady(function() { 
  
Ext.QuickTips.init(); 
var win = new Ext.Window({ 
renderTo: Ext.getBody() 
,title:Ext.get('page-title').dom.innerHTML 
,width:400 
,height:300 
,plain:true 
,layout:'fit' 
,border:false 
,closable:false 
,items:[{ 
xtype:'tabpanel' 
,defaults:{layout:'fit'} 
,activeItem:0 
,items: [{ 
title:'First Tab (a test of the very long label)' 
,id:'firsttab' 
,iconCls:'icon-ok' 
,bodyStyle:'padding:10px' 
,html:'Click on Grid Tab to instantiate and render the grid.' 
},{ 
title:'Grid Tab' 
,id:'gridtab' 
,xtype:'examplegrid' 
,autoScroll:true 
}] 
}] 
}); 
win.show(); 
  
// code here 
  
}); // eo function onReady 
  
// eof 

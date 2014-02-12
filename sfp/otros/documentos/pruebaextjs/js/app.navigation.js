Ext.ns('app');




app.menu = {
	id:'menu',
	xtype: 'treepanel',
	title:'Módulos',
	iconCls:'module_icon',
	rootVisible: false,
	lines: false,
	singleExpand: false,
	useArrows: true,
	listeners: {
		click : {
			scope  : this,
          	fn     : function( n, e ) {

          		//var sn = this.selModel.selNode || {}; // selNode is null on initial selection
    			if(n.leaf)
    			{
    				//Accedemos a los a atributod del json que usamos para crear el nodo con
	          		if (n.attributes.url)
	          		{
	          			url = n.attributes.url;
	          		}
	          		else {
	          			url = n.id+'/'+n.id+'.html';
	          		}

	          		//Abrimos el nuevo tab
	          		app.tabpanel.addTab(n.id,n.text,url,n.attributes.tabType);
    			}


          	}
		}

	},

	loader: new Ext.tree.TreeLoader({
		dataUrl:'tree.php'
	}),
	root: new Ext.tree.AsyncTreeNode({
		expanded  :true
	})
}

app.help = {
	title:'Ayuda',
    html:'<p>Some Navigation in here.</p>',
    border:false,
    iconCls:'help_icon'
}

app.themeSwap = {
	title:'Themes',
	layout:'fit',
    items: [{
    	xtype: 'themecombo'
    }],
    border:false,
    iconCls:'themes_icon'
}

/********************************************************************************************

app.navigation

 	Tipo : Acordeon
 	Componentes:	app.menu
 					app.help

 *******************************************************************************************/
app.navigation = {
    title: 'Navigation',
	region: 'west',
	id:'navigation',
	split:true,
	width: 300,
	minSize: 175,
	maxSize: 400,
	margins:'3 0 3 3',
	collapsible: true,
	layout:'accordion',
	layoutConfig:{
	    animate:true
	},
	items: [app.menu,app.help,app.themeSwap]
}


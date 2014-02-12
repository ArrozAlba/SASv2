Ext.ns('app');

Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {
     onRender : function(ct, position){
          this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});
     }
});

app.mytabpanel = Ext.extend(Ext.TabPanel, {
	initComponent: function(){
        app.mytabpanel.superclass.initComponent.apply(this, arguments);
    },

    addTab: function (id,title,url,type)
    {
		//alert(url);
		var open = !this.getItem(id);
		if (open)
		{

			switch (type)
			{
				case 'iframe':

					//Creamos un nuevo ifram y cargamos dentro la url
					var newPanel = new Ext.Panel({
				        id : id,
				        title: title,
				        loadScripts: true,
				        autoScroll: true,
				        closable: true,
				        iconCls:id+'_icon',
				        layout:'fit',

				        items: [ new Ext.ux.IFrameComponent({ id: id, url: url, name: id}) ]
			      	});
			     	this.add(newPanel);
			      	this.setActiveTab(newPanel);
				break;
				case 'load':
					//Cargamos la pesta�a por ajax
                	var newPanel = new Ext.Panel({
				        id : id,
						layout: 'fit',
				        title: title,
				        loadScripts: true,

				        closable: true,
				        iconCls:id+'_icon',

						autoLoad: {url: url, scripts: true, scope: this}

			      	});
			     	this.add(newPanel);
			     	this.setActiveTab(newPanel);
				break;
				default:
					alert("Tipo de tab no definido");
				break;
			}
		}
		else {
			//Si ya tenemos la pesta�a creada la seleccionaremos
			this.setActiveTab(id);
		}
    }
});

// register xtype to allow for lazy initialization
Ext.reg('mytabpanel', app.mytabpanel);

app.tabpanel = new app.mytabpanel({
	    id: 'tabs',
		plain: true,  //remove the header border
		activeItem: 0,
		region:'center',
		margins: '3 3 3 0',
		items:[{
			title: 'LimeStudio',
			iconCls:'home_icon',
			html : '<iframe id="frame-welcome" src="http://www.limestudio.es" border="0" width="100%" height="100%" style="border:0" ></iframe>'

		}]
	});
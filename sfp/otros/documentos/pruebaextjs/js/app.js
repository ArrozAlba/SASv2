Ext.BLANK_IMAGE_URL = '../../ext/resources/images/default/s.gif';
Ext.ns('app');




// application main entry point
Ext.onReady(function() {

    Ext.QuickTips.init();

    //Ext.state.Manager.setProvider(new Ext.state.SessionProvider({state: Ext.appState}));

    //Cargando
    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({remove:true});
    }, 550);

     //Theme Combo


	//Generamos el viewport
	var viewport = new Ext.Viewport({
        layout:'border',

		items: [
			{
				xtype: 'box',
				region: 'north',
				applyTo: 'header',
				height:30,
				split:false
			}
			,app.navigation,app.tabpanel
		]
    });




	//app.tabpanel.addTab(2,'Probando','http://www.google.com');

}); // eo function onReady

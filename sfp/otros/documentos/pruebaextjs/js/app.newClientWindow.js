Ext.ns('app');


app.newClientWindow  = Ext.extend(Ext.Window, {
    // Constructor Defaults, can be overridden by user's config object
	id : 'newClientWindow',
    title:'Nuevo Cliente',
    layout:'fit',
    width:600,
    height:400,
    modal: true,
    closeAction:'hide',
    plain: true,
	iconCls: 'client_icon',
	buttons: [
        {
            id: 'newClientWindow_butonSave',
            text: 'Guardar',

            handler: function () {
            	var win= Ext.getCmp('newClientWindow');
				win.saveClient();
            }
		}
        ,{
        	id : 'newClientWindow_butonClose',
            text: 'Cerrar',
        	handler: function () {
            	var win= Ext.getCmp('newClientWindow');
				win.closeWindow();
            }
        }
	]
	,
	newForm: new Ext.FormPanel({
	        id:'newClientWindow_form',
	        labelWidth: 100, // label settings here cascade unless overridden
	        url:'client/save_new_client.php',
			width:600,
    		height:400,
	        border:false,

	        defaultType: 'textfield',

	        items: {
	            xtype:'tabpanel',
	            height:400,
	            activeTab: 0,
	            defaults:{autoHeight:true, bodyStyle:'padding:10px'},
	            items:[{
	                title:'Datos fiscales',
	                layout:'form',
	                defaults: {width: 150},
	                defaultType: 'textfield',
	                items: [

			        	{
			                fieldLabel: 'Razón Social',
			                name: 'cli_razon_social',
			                allowBlank:false,
			                width: 400

			            },{
			                fieldLabel: 'CIF/NIF',
			                name: 'cli_cif_nif',
			                allowBlank:false

			            },{
			                fieldLabel: 'Dirección',
			                name: 'cli_direccion',
			                width: 400

			            },
			            {
			                fieldLabel: 'Localidad',
			                name: 'cli_localidad',
			                allowBlank:false
			            },
			            {
			                fieldLabel: 'CP',
			                name: 'cli_cp',
			                allowBlank:false
			            },
			            {
			                fieldLabel: 'Provincia',
			                name: 'cli_provincia',
			                 allowBlank:false
			            },
			            {
			                fieldLabel: 'Pais',
			                name: 'cli_pais',
			                 allowBlank:false
			            }
	                ]
	            },{
	                title:'Datos de contacto',
	                layout:'form',
	                defaults: {width: 150},
	                defaultType: 'textfield',

	                items: [
	                	{
			                fieldLabel: 'Teléfono',
			                name: 'cli_telefono'
			            },
			            {
			                fieldLabel: 'Email',
			                name: 'cli_email',
			                vtype:'email'
			            },
			            {
			                fieldLabel: 'Web',
			                name: 'cli_web'
			            }
	                ]
	            },
	            {
	                title:'Datos bancarios',
	                layout:'form',
	                defaults: {width: 150},
	                defaultType: 'textfield',

	                items: [
	                	{
			                fieldLabel: 'Cuenta Bancaria',
			                name: 'cli_cuenta_banco',
			                width: 400
			            }
	                ]
	            }
	            ]
	        }
	    })
	,


    closeWindow: function () {
		//this.newForm.getForm().reset();
        this.destroy(true);
    },

    saveClient : function () {

    	this.newForm.getForm().submit({
	    	waitTitle:'Creando registro ...',
			//waitMsg:'Sending data...',
			scope: this,
	        success: function(f, a){
	            if (a.success)
	            {
	            	f.reset();
	            	//Creamos la ventana
	            	 this.destroy(true);
	            	//Recargamos la grid
	            	Ext.example.msg('Mensaje del sistema', 'Nuevo cliente creado');
	            	app.client_store.reload();
	            }
	        },
	        failure: function(){
	                    // código para error
	        }
	    });
    }
    ,
	initComponent : function () {
		//Comprovamos si se le pasa para que no de error
		if (this.record) var record=this.record.data;

        var args = {
            items : [this.newForm]
        }

        Ext.apply(this, args);
        app.newClientWindow.superclass.initComponent.apply(this, arguments);
    }
});

// register xtype to allow for lazy initialization
Ext.reg('newClientWindow', app.newClientWindow );
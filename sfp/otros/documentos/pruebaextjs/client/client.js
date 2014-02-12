
/*******************************************************************************************************
 	Too
 *******************************************************************************************************/


app.client_grid_tb = [

new Ext.Action({
    text: 'Nuevo',
    handler: function(){
		var newClientWindow= Ext.getCmp('newClientWindow');
		if (!newClientWindow)
		{
        	var newClientWindow = new app.newClientWindow();
		}
	    newClientWindow.show(this);
    },
    iconCls: 'new_icon'
})
,
new Ext.Action({
    text: 'Editar',
    handler: function(){
		var selectionModel = app.client_grid.getSelectionModel();
    	//Comprobamos si hay almenos 1 registro seleccionado
    	if (selectionModel.hasSelection())
		{
			var record = selectionModel.getSelected();

		    var editClientWindow = new app.editClientWindow({record: record});
	        editClientWindow.show(this);
		}
		else {
			Ext.MessageBox.alert('Status', 'Debe seleccionar un registro para poder realizar esta acción.');
		}

    },
    iconCls: 'edit_icon'
})
,
new Ext.Action({
    text: 'Eliminar',
    handler: function(){
    	//Obtenemos el sm
    	var selectionModel = app.client_grid.getSelectionModel();
    	//Comprobamos si hay almenos 1 registro seleccionado
    	if (selectionModel.hasSelection())
		{
			Ext.MessageBox.confirm('Confirm', '¿Está seguro que desea eliminar el registro?',
	        	function (btn){

					if (btn=='yes') {

							//get the selected record
							var record = selectionModel.getSelected();
							//get the index of selected record
							var idx = app.client_grid.store.indexOf(record);

							//alert(record.id);


		        			Ext.Ajax.request({
								url: 'client/delete_client.php',
								success: function(){
									//Recargamos la grid
		               				app.client_store.reload();
									Ext.example.msg('Mensaje del sistema', 'Registro borrado');

								},

								//failure: otherFn,
								params: { id: record.id }
							});

							// Global Ajax events can be handled on every request!
							//Ext.Ajax.on('beforerequest', this.showSpinner, this);


						}
	        	}
	        );
		}
		else {
			Ext.MessageBox.alert('Status', 'Debe seleccionar un registro para poder realizar esta acción.');
		}
    },
    iconCls: 'delete_icon'
})
,
new Ext.Action({
    text: 'Imprimir registro',
    handler: function(){
		alert("próximamente");
    },
    iconCls: 'print_icon'
})
,new Ext.Action({
    text: 'Imprimir listado',
    handler: function(){
		alert("próximamente");
    },
    iconCls: 'print_icon'
})
];

/*******************************************************************************************************
 	Creamos el listado de clientes
 *******************************************************************************************************/

// create the Data Store
app.client_store = new Ext.data.Store({
	// load using script tags for cross domain, if the data in on the same domain as
	// this page, an HttpProxy would be better
	proxy: new Ext.data.HttpProxy({
	    url: 'client/clientes.php'
	}),

	// create reader that reads the Topic records
	reader: new Ext.data.JsonReader({
	    root: 'data',
	    totalProperty: 'total',
	    id: 'cli_id',
	    fields: [
	        'cli_razon_social',
	        'cli_cif_nif',
	        'cli_direccion',
	        'cli_localidad',
	        'cli_cp',
	        'cli_provincia',
	        'cli_pais',
	        'cli_telefono',
	        'cli_email',
	        'cli_web',
	        'cli_cuenta_banco'
	    ]
	})

	// turn on remote sorting
	//remoteSort: true
});
app.client_store.setDefaultSort('cli_razon_social');




app.client_cm = new Ext.grid.ColumnModel([{
	id: 'cli_razon_social', // id assigned so we can apply custom css (e.g. .x-grid-col-topic b { color:#333 })
   	header: "Razón Social",
   	dataIndex: 'cli_razon_social',
   	autoExpandColumn: 'cli_razon_social',
   	width: 420
	},
	{
		id:'cli_razon_social',
	   	header: "Razon Social",
	   	dataIndex: 'cli_razon_social',
	   	width: 100
	},
	{
		id:'cli_cif_nif',
	   	header: "CIF/NIF",
	   	dataIndex: 'cli_cif_nif',
	   	width: 100
	},
	{
		id:'cli_direccion',
	  	header: "Dirección",
	   	dataIndex: 'cli_direccion',
	   	width: 300
	},
	{
		id:'cli_localidad',
	   	header: "Localidad",
	   	dataIndex: 'cli_localidad',
	   	width: 100
	},
	{
	   	id:'cli_cp',
	   	header: "CP",
	   	dataIndex: 'cli_cp',
	   	width: 100
	},
	{
		id:'cli_provincia',
	   	header: "Provincia",
	   	dataIndex: 'cli_provincia',
	   	width: 100
	},
	{
		id:'cli_pais',
	   	header: "Pais",
	   	dataIndex: 'cli_pais',
	   	width: 100
	},
	{
		id:'cli_telefono',
	   	header: "Teléfono",
	   	dataIndex: 'cli_telefono',
	   	width: 100,
	   		hidden: true
	},
	{
		id:'cli_email',
	   	header: "Email",
	   	dataIndex: 'cli_email',
	   	width: 200,
	   		hidden: true
	},
	{
		id:'cli_web',
	   	header: "Web",
	   	dataIndex: 'cli_web',
	   	width: 200,
	   	hidden: true
	},
	{
		id:'cli_cuenta_banco',
	   	header: "Cuenta bancaria",
	   	dataIndex: 'cli_cuenta_banco',
	   	width: 200,
	   	hidden: true
	}
]);

// by default columns are sortable
app.client_cm.defaultSortable = true;


app.client_grid = new Ext.grid.GridPanel({
	border:false,
	store: app.client_store,
	cm: app.client_cm,
	loadMask: true,
	viewConfig: {
        forceFit: true
    },
	bbar: new Ext.PagingToolbar({
		pageSize: 25,
		store: app.client_store,
		displayInfo: true,
		displayMsg: 'Mostrando registros {0} - {1} of {2}',
		emptyMsg: "No hay registros"
	}),
	tbar: app.client_grid_tb

});

//app.client_grid.render();




app.client_grid.on('rowdblclick', function(sm, index, record){

	var editClientWindow = new app.editClientWindow({record: record});
    editClientWindow.show(this);
});


	/*
app.client_grid.getSelectionModel().on('rowdblclick', function(sm, rowIdx, r) {

    var editClientWindow = new app.editClientWindow({record: r});
    editClientWindow.show(this);
});

*/




//Cargamos la grid en un panel que añadimos a la pestaña
app.client_tab=Ext.getCmp('client');
app.client_tab.add(new Ext.Panel({
	id:'client_panel_grid',
	border:false,
    layout:'fit',
    items: app.client_grid
}));
app.client_tab.doLayout();

app.client_store.load({params:{start:0, limit:25}});

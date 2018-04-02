<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <!--css-->
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!--css datatable-->
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
		<!-- buttons plugins -->
		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.bootstrap.min.css">

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

		<style type="text/css">
			#app {
				width: 100vw;
				overflow-x: hidden;
			}
			.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus {
			    background-color: #E3E5E6;
			}
			.logout{
				margin-top: 18px;
			}
			.userrol {
				display: none !important;
			}
			ul.navbar-nav > li {
				display: none;
			}
		</style>
		<title>Trayecto Seguro</title>
	</head>
	<body>
		<div id="app">
			<nav class="navbar navbar-default navbar-fixed-top">
				<div class="container">
					<ul class="nav navbar-nav">
						<li id="safety_intelligence"  ><a href="<?php echo base_url('safety_intelligence/'); ?>">Dashboard</a></li> 
						<li id="companies" class="active"><a href="<?php echo base_url('companies/'); ?>">Compañías</a></li> 
						<li id="users"><a href="<?php echo base_url('users/'); ?>">Usuarios</a></li>
						<li id="travels"><a href="<?php echo base_url('travels/'); ?>">Trayectos</a></li>
						<li id="questions"><a href="<?php echo base_url('questions/'); ?>">Preguntas</a></li>

					</ul>
					<span class="pull-right logout"><a href="javascript:logout();" style="text-decoration: none; cursor: pointer;">Cerrar Sesión</a></span>
				</div>	
			</nav>

			<div class="container" style="margin-top:80px;">
				<style type="text/css">
				.icon-action{
					cursor: pointer;
					font-size: 19px;
				}
				.icon-deactivated{
					color: #D9534F;
				}
				</style>
				<div class="row">
					<div class="col-sm-12">
					<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
		                <li class="active">Compañías</li>
					</ol>                                  
				</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					        <thead>
					            <tr>
					                <th>ID</th>
					                <th>Nombre</th>
					                <th>Código</th>
					                <th>Límite de velocidad (km/h)</th>
					                <th>Estado</th>
					                <th>Acciones</th>
					            </tr>
					        </thead>
				    	</table>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12 text-center">
						<button id="btn-create" class="btn btn-success" data-action="create">Crear</button>
					</div>
				</div>
			</div>	
		</div>

		<!-- start modal for create / update-->
		<div class="modal fade" tabindex="-1" id="form-modal" role="dialog" data-backdrop="static">
    		<div class="modal-dialog">
      			<!-- start Modal content-->
      			<div class="modal-content">
        			<div class="modal-header">
          				<h4 class="modal-title" id="title">default</h4>
        			</div>
        			<div class="modal-body">
        				<form id="form-record">
				         	<div class="form-group" id="grp-name">
					            <label for="record-name" class="form-control-label">Nombre</label>
								<input type="text" class="form-control" id="record-name" name="record-name" maxlength="60">
								<span class="help-block" id="validator-name" ></span>
				          	</div>
				          	<div class="form-group" id="grp-code">
					            <label for="record-code" class="form-control-label">Código</label>
								<input type="number" class="form-control" id="record-code" name="record-code">
								<span class="help-block" id="validator-code" ></span>
				          	</div>
				          	<div class="form-group" id="grp-speed-limit">
					            <label for="record-speed-limit" class="form-control-label">Límite de velocidad</label>
								<input type="number" class="form-control" id="record-speed-limit" name="record-speed-limit">
								<span class="help-block" id='validator-speed-limit' ></span>
				          	</div>
				          	<input type="hidden" id="record-id">
				        </form>
        			</div>
        			<div class="modal-footer">
        				<button type="button" id="btn-action" class="btn btn-success" data-action="create">Crear</button>
          				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        			</div>
      			</div>
      			<!-- Modal content-->
    		</div>
  		</div>
  		<!-- end modal for create / update-->

		<!-- start modal for confirm -->
  		<div class="modal fade" tabindex="-1" id="confirm-modal" role="dialog" data-backdrop="static">
    		<div class="modal-dialog">
      			<!-- start Modal content-->
      			<div class="modal-content">
        			<div class="modal-header">
          				<h4 class="modal-title">Confirmación</h4>
        			</div>
        			<!-- This section (div id="modal-body") will be loaded dynamically -->
        			<div class="modal-body">
        				<p>Por favor, presione para efectuar el cambio de estado</p>
        			</div>
        			<div class="modal-footer">
        				<button type="button" id="btn-action-confirm" class="btn btn-warning" data-action="">Aceptar</button>
          				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        			</div>
      			</div>
      			<!-- Modal content-->
    		</div>
  		</div>
		<!-- end modal for confirm -->
		
		<!-- start own script-->
		<script>
			var table;
			$(document).ready(function() {
				// User Roles & Menu
				var user = JSON.parse(sessionStorage.getItem("user"));
				if (user.username == 'superadmin') {
					$("#companies").show();
					$("#users").show();
					$("#questions").show();
					$("#travels").show();
					
				} else if (user.admin) {
					$("#users").show();
					$("#travels").show();
				}
				$("#safety_intelligence").show();
				table = $('#example').DataTable({
		    		"select": true,
			    	"language": {
					    "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
					},
				   "ajax": {
	          			"url": "api/rcompany/list",
	          			"type": "GET"
	        		},
	        		"showRefresh": true,
	            	"sAjaxDataProp" : "response",
		            "columns": [
		            	{ 	
		            		"data": "id" 
		            	},
			            { 	
			            	"data": "name" 
			            },
			            { 
			            	"data": "code"
			        	},
			            { 
			            	"data": "speed_limit"
			        	},
			            { 	
			            	"data": "active",
			            },
			            {
			            	"data": null,
			                "className": "center",
			                "defaultContent": ''
			                	
			                	//+'&nbsp;&nbsp;<i class="glyphicon glyphicon-trash icon-action" data-action="delete" data-id="2" aria-hidden="true"></i>'
			            }
		            ],
		            
		            "columnDefs" : [
	        			{ 	//param active
	        				targets : [4],
	          					render : function (data, type, row) {
	             				return data == '1' ? 'Activo' : 'Inactivo';
	          				}
					    },
					    { 	//icons options
	        				targets : [5],
	          					render : function (data, type, row) {
	          						var iconSwitch = '&nbsp;&nbsp;<i class="glyphicon glyphicon-off icon-action icon-deactivated" data-action="activate" aria-hidden="true"></i>';
	          						if(data.active == 1){
	          							iconSwitch = '&nbsp;&nbsp;<i class="glyphicon glyphicon-off icon-action" data-action="deactivate" aria-hidden="true" style="color : green"></i>';
	          						}
	          					return '<i class="glyphicon glyphicon-edit icon-action" data-action="edit" aria-hidden="true"></i>'+iconSwitch;
	          				}
					    }
					]
			    });
			} );

		//$('#example').find('.dataTables_filter').append('<button class="btn btn-success mr-xs pull-right" type="button">Crear</button>');

			var btnAction = $("#btn-action");
			//lang var text
			var textEdit = "Editar";
			var textDelete = "Eliminar";
			var textActivate = "Activar";
			var textDeActivate = "Desactivar";
			var textCreate = "Crear";

			//To prepare and display modal (edit, activate, deactivate)
			$('#example').on('click', 'i.icon-action', function (e) {
		        e.preventDefault();
		    
		        var action = $(this).attr("data-action");
		 		var row = $(this).closest('tr');
				var id = row.find('td:eq(0)').text();
				var name = row.find('td:eq(1)').text();
				var code = row.find('td:eq(2)').text();
				var speed_limit = row.find('td:eq(3)').text();
				var state = row.find('td:eq(4)').text();
				
				switch (action){
					case "edit":
						//set value to form
						$("#record-id").val(id);
						$("#record-name").val(name);
						$("#record-code").val(code);
						$("#record-speed-limit").val(speed_limit);
						$("#title").text(textEdit);
						btnAction.text(textEdit);
						btnAction.attr("data-action", action);
						btnAction.attr("class", "btn btn-warning");
						$('#form-modal').modal('show');
					break;

					case "activate":
						var btn = $("#btn-action-confirm");
						$("#title").text(textActivate);
						btn.text(textActivate);
						btn.attr("data-id", id);
						btn.attr("data-action", action);
						btn.attr("class", "btn btn-warning");
						$('#confirm-modal').modal('show');
					break;

					case "deactivate":
						var btn = $("#btn-action-confirm");
						$("#title").text(textDeActivate);
						btn.text(textDeActivate);
						btn.attr("data-id", id);
						btn.attr("data-action", action);
						btn.attr("class", "btn btn-danger");
						$('#confirm-modal').modal('show');
					break;
				}
		    } );

			//To prepare and display modal (create)
		    $("#btn-create").click(function(){
				$('#form-record')[0].reset();
				$("#title").text(textCreate);
				btnAction.text(textCreate);
				btnAction.attr("class", "btn btn-success");
				btnAction.attr("data-action", "create");
				$('#form-modal').modal('show');
			});

		    //Event trigger (create / edit)
		    btnAction.click(function(e){
		    	e.preventDefault();
		    	var action = $(this).attr("data-action");
		    	var params = {  
							"name" :  $("#record-name").val(),
							"code" : $("#record-code").val(),
							"speed_limit" : $("#record-speed-limit").val()
						};
				if(action == "edit"){
					params.id = $("#record-id").val();
				}
				processAction(action, params);
		    });

		    //Event trigger (activate / deactivate)
			$("#btn-action-confirm").click(function(e){
		    	e.preventDefault();
		    	var action = $(this).attr("data-action");
		    	var params = {"id" : $(this).attr("data-id") };
				processAction(action, params);
		    });


			//Process actions
			function processAction(action, params){
				var url = "";
				switch (action){

					case "create":
						url = "api/rcompany/add";
					break;

					case "edit":
						url = "api/rcompany/edit";
					break;
					
					case "activate":
						url = "api/rcompany/activate";
					break;

					case "deactivate":
						url="api/rcompany/deactivate";
					break;
				}
				//validator
				var error = false;
				if(action == "create" || action == "edit"){
					if(params.name.length == 0){
						$('#grp-name').addClass('has-error');
						$('#validator-name').html('Este campo es obligatorio.');
						error = true;
					}else{
						$('#grp-name').removeClass('has-error');
						$('#validator-name').html('');
					}

					if(params.code.length == 0){
						$('#grp-code').addClass('has-error');
						$('#validator-code').html('Este campo es obligatorio.');
						error = true;
					}else{
						$('#grp-code').removeClass('has-error');
						$('#validator-code').html('');
					}
					if(params.speed_limit.length == 0){
						$('#grp-speed-limit').addClass('has-error');
						$('#validator-speed-limit').html('Este campo es obligatorio.');
						error = true;
					}else{
						$('#grp-speed-limit').removeClass('has-error');
						$('#validator-speed-limit').html('');
					}
				}
			
				
				//Call to API
				if(!error)
				$.post( url, params)  .done(function() {
					if ($('#form-modal').is(':visible')) {
    					$('#form-modal').modal('hide');
					}
					if ($('#confirm-modal').is(':visible')) {
    					$('#confirm-modal').modal('hide');
					}
					
				    table.ajax.reload( null, false );
				  })
				  .fail(function() {
				    //alert( "error" );
				  })
				  .always(function() {
				    //alert( "finished" );
				  });
			}
		
			function logout() {
				sessionStorage.removeItem("user");
				window.location.href = '<?php echo base_url('login/'); ?>';
			}		
		</script>
		<!-- end own script -->






	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	    <!-- start scripts data tables-->
		<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
		<script src="//cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
		

		<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
	    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.bootstrap.min.js"></script>
		<!-- end scripts data tables-->
	</body>
</html>
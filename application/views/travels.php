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
						<li id="safety_intelligence" ><a href="<?php echo base_url('safety_intelligence/'); ?>">Dashboard</a></li> 
						<li id="companies"><a href="<?php echo base_url('companies/'); ?>">Compañías</a></li> 
						<li id="users"><a href="<?php echo base_url('users/'); ?>">Usuarios</a></li>
						<li id="travels"  class="active"><a href="<?php echo base_url('travels/'); ?>">Trayectos</a></li>
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
		                <li class="active">Trayectos</li>
					</ol>                                  
				</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					        <thead>
					            <tr>
					                <th>ID</th>
					                <th>Usuario</th>
					                <th>Fecha</th>
					                <th>Acciones</th>
					            </tr>
					        </thead>
				    	</table>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-sm-12 text-center">
						<button id="btn-create" class="btn btn-success" data-action="create">Crear</button>
					</div>
				</div>
			</div>	
		</div>
		
		
		<!-- start modal for create / update-->
		<div class="modal fade" tabindex="-1" id="detail-modal" role="dialog" data-backdrop="static">
    		<div class="modal-dialog">
      			<!-- start Modal content-->
      			<div class="modal-content">
        			<div class="modal-header">
          				<h4 class="modal-title" id="title"></h4>
        			</div>
        			<div class="modal-body">
				          	<div class="form-group">
					            <label for="duration" class="form-control-label">Duración</label>
					            <span id="duration"></span>
				          	</div>
				          	<div class="form-group">
					            <label for="distance" class="form-control-label">Distancia</label>
					            <span id="distance"></span>
				          	</div>
				          	<div class="form-group">
					            <label for="average_speed" class="form-control-label">Velocidad promedio</label>
					            <span id="average_speed"></span>
				          	</div>
				          	<div class="form-group">
					            <label for="max_speed" class="form-control-label">Velocidad máxima</label>
					            <span id="max_speed"></span>
				          	</div>
				          	<div class="form-group">
					            <label for="speed_limit" class="form-control-label">Límite de velocidad</label>
					            <span id="speed_limit"></span>
				          	</div>
							<div class="form-group">
					            <label for="speed_violation" class="form-control-label">Sobrepasó de velocidad</label>
					            <span id="speed_violation"></span>
				          	</div>	 
							 <div class="form-group">
					            <label for="usage_phone" class="form-control-label">Uso de teléfono móvil</label>
					            <span id="usage_phone"></span>
				          	</div>
							   
							  
							  <h3>Cuestionario</h3>
							 
							  <div id='answer'></div>
        			</div>
        			<div class="modal-footer">
          				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        			</div>
      			</div>
      			<!-- Modal content-->
    		</div>
  		</div>
  		<!-- end modal for create / update-->
		
		
		<!-- start own script-->
		<script>
			var table;
			$(document).ready(function() {
				// User Roles & Menu
				var user = JSON.parse(sessionStorage.getItem("user"));
				if (user.username == 'superadmin') {
					$("#companies").show();
					$("#users").show();
					$("#travels").show();
					$("#questions").show();
				} else if (user.admin) {
					$("#users").show();
					$("#travels").show();
				}
				$("#safety_intelligence").show();
				endpoint = "api/rtravel/list";
				if (user.company_id) {
					endpoint += "?company_id=" + user.company_id;
				}

				table = $('#example').DataTable({
		    		"select": true,
			    	"language": {
					    "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Spanish.json"
					},
				   "ajax": {
	          			"url": endpoint,
	          			"type": "GET"
	        		},
	        		"showRefresh": true,
	            	"sAjaxDataProp" : "response",
		            "columns": [
		            	{ 	
		            		"data": "id" 
		            	},
			            { 	
			            	"data": "appuser" 
			            },
			            { 
			            	"data": "date"
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
	        				targets : [3],
	          					render : function (data, type, row) {
	             				return '<i title="Descargar log del trayecto" class="glyphicon glyphicon-download-alt icon-action" data-action="download" aria-hidden="true"></i>'
	             				+'&nbsp;&nbsp;<i title="Descargar log Uso del movil" class="glyphicon glyphicon-phone  icon-action" data-action="download-usage" aria-hidden="true" ></i>'
	             				+'&nbsp;&nbsp;<i title="Ver detalles" class="glyphicon glyphicon-eye-open icon-action" data-action="detail" aria-hidden="true" style="color : green"></i>';
	          					}
					    }
					]
			    });
			} );


			//To prepare and display modal (edit, activate, deactivate)
			$('#example').on('click', 'i.icon-action', function (e) {
		        e.preventDefault();

		        var action = $(this).attr("data-action");
		        var row = $(this).closest('tr');
				var id = row.find('td:eq(0)').text();

		        switch (action){
					case 'download':
		    			window.location.href = 'api/rtravel/download_logs?travel_id='+id;
						break;
					case 'download-usage':
		    			window.location.href = 'api/rtravel/download_phoneusagelogs?travel_id='+id;
						break;	
					case 'detail':
						$('#title').empty();
						$.get('api/rtravel/list_by_id?travel_id='+id)
						.done(function(data) {
							$('#title').text('ID: '+164 +' Detalle del Trayecto '+data.response.date);
							$('#duration').text(data.response.duration);
							$('#distance').text(data.response.distance +' Km');
							$('#average_speed').text(data.response.average_speed + ' Km/h');
							$('#max_speed').text(data.response.max_speed + ' Km/h');
							$('#speed_limit').text(data.response.speed_limit + ' Km/h');
							$('#speed_violation').text(data.response.speed_violation);
							$('#usage_phone').text(data.response.usage_phone.usage_phone);
						
						
							$('#answer').empty();

							data.response.answer.forEach(element => {

								var answer = element.value ? " Si" : " No";
								
								$("<div>", {
									'class': 'form-group'
								})
									.append(
										$('<label>', {
											'class': 'form-control-label',
											'text': element.title
										})
									)
									.append(
										$('<span>', {
											'text': answer
										})
									)
									.appendTo('#answer')
							});	

							$('#detail-modal').modal('show');
					  	})
					  	.fail(function(e) {
					    	console.log(e);
					  	})
					  	.always(function() {
					  		//console.log(JSON.stringify(companies));
					    	//alert( "finished" );
						});
						break;
				}
		 		

		    } );

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
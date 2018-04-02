<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <!--css-->
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">



		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
   
  

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
						<li id="safety_intelligence"  class="active"><a href="<?php echo base_url('safety_intelligence/'); ?>">Dashboard</a></li> 
						<li id="companies"><a href="<?php echo base_url('companies/'); ?>">Compañías</a></li> 
						<li id="users"><a href="<?php echo base_url('users/'); ?>">Usuarios</a></li>
						<li id="travels"><a href="<?php echo base_url('travels/'); ?>">Trayectos</a></li>
						<li id="questions"><a href="<?php echo base_url('questions/'); ?>">Preguntas</a></li>
					</ul>
					<span class="pull-right logout"><a href="<?php echo base_url('login/'); ?>" style="text-decoration: none; cursor: pointer;">Cerrar Sesión</a></span>
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
		                <li class="active">Dashboard</li>
					</ol>                                  
				</div>
				</div>
                <div class="row">
                <div class="col-lg-3" id="company">
                    <label>Compañia:</label>
                    <select name="company_id" id="company_id" class="form-control" style="cursor: pointer;width: 250px;">
                    <option value="" selected="">Todas</option>
                    </select>
                 </div>
                </div>
				<div class="row">
					<div class="col-sm-12" id="graph-container">
					
                    <canvas id="canvas"></canvas>
					</div>
				</div>

			</div>	
		</div>

	
		
		<!-- start own script-->
		<script>
		var color = Chart.helpers.color;
	
	
        var companies = [];
		$(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'Authorization':JSON.parse(sessionStorage.getItem("user")).access_token
                }
            });
          
          


            //Load companies
            $.get("api/rcompany/list")
                .done(function(data) {
                    $.each(data.response, function(index, item){
                        companies.push({"id" : item.id, "name" : item.name});
                        $("#company_id").append('<option value="'+item.id+'">'+item.name+'</option>');
                    });
                })
                .fail(function(e) {
                    console.log(e);
                })
                .always(function() {
                    //console.log(JSON.stringify(companies));
                });


				// User Roles & Menu
				var user = JSON.parse(sessionStorage.getItem("user"));
				
				if (user.username == 'superadmin') {
					$("#companies").show();
					$("#users").show();
					$("#questions").show();
                    $("#travels").show();
                    ajax_raking();
                    
				} else if (user.admin) {
					$("#users").show();
                    $("#travels").show();
                    $("#company").hide();
                    ajax_raking(user.company_id);
                } 
                 $("#safety_intelligence").show();
               
               
		
		});
        $("#company_id").on('change', function() {
            var company_id= this.value;
         
            ajax_raking(company_id);
        });		
			function logout() {
				sessionStorage.removeItem("user");
				window.location.href = '<?php echo base_url('login/'); ?>';
            }		
            

            function chart_raking(label,ranking){

                $('#canvas').remove(); // this is my <canvas> element
                $('#graph-container').append('<canvas id="canvas"><canvas>');
                var ctx = document.getElementById('canvas').getContext('2d');

                window.myHorizontalBar = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: label,
                        datasets: [{
                                label: 'Ranking',
                                backgroundColor: color('rgb(54, 162, 235)').alpha(0.5).rgbString(),
                                borderColor: 'rgb(54, 162, 235)',
                                borderWidth: 1,
                                data: ranking
                            }]
                        },
				    options: {
					// Elements options apply to all of the options unless overridden in a dataset
					// In this case, we are setting the border of each horizontal bar to be 2px wide
                        elements: {
                            rectangle: {
                                borderWidth: 2,
                            }
                        },
                        responsive: true,
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Ranking de Conductores'
                        }
                    }
                });   
            }
            function ajax_raking(company_id=''){
                $.get('api/rtravel/safety_intelligence?company_id='+company_id)
                .done(function(data) {
                    var label = [];
                    var ranking = [];
                    data.response.forEach(element => {
                        label.push(element.username);
                        ranking.push(element.ranking);
                    });
                    chart_raking(label,ranking)
                })
                .fail(function(e) {
                    console.log(e);
                })
                .always(function() {
                    //console.log(JSON.stringify(companies));
                    //alert( "finished" );
                });
            }
		</script>
		<!-- end own script -->






	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  
  

	</body>
</html>
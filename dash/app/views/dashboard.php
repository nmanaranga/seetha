<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/main.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/inputs.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/grid.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery-ui-1.8.4_.custom.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.tablescroll.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.autocomplete.css" />
<link href="<?=base_url(); ?>css/superfish.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/menu.css" />
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery-ui-1.8.17.custom.min.js"></script>

<script type='text/javascript' src='<?=base_url()?>js/dashboard.js'></script>



<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<title>Dashboard</title>



</head>
<body>

	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
	<input  id="txtMonth" placeholder="Select Month ..." class="monthYearPicker" />

				 </a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a id="btnClustr" href="#">Cluster Wise</a></li>
				<li><a id="btnSupervisor" href="#">Supervisor Wise</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?=base_url(); ?>/index.php/main/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
			</ul>
		</div>
	</nav>







	<!-- <h2 class="hder_colum">Cluster Wise Sales Target</h2> -->
	<div id="container" >
		<div id="div_cluster">

			<div id="containerx" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

			<table class="table" width="100%" border="0" >
				<thead>



					<tr class="tbl_hder" >
						<td class="tbl_hddat" rowspan="3" valign="middle">Cluster Name</td>
						<td class="tbl_hddat" colspan="4">Current Month</td>
						<td class="tbl_hddat" colspan="4">Cumilative</td>
					</tr>
					<tr class="tbl_hder">
						<td class="tbl_hddat" rowspan="2">Target</td>
						<td class="tbl_hddat" rowspan="2">Archivement</td>
						<td class="tbl_hddat" colspan="2">VAR</td>
						<td class="tbl_hddat" rowspan="2">Target</td>
						<td class="tbl_hddat" rowspan="2">Archivement</td>
						<td class="tbl_hddat" colspan="2">VAR</td>
					</tr>
					<tr class="tbl_hder">
						<td class="tbl_hddat">4T</td>
						<td class="tbl_hddat">%</td>
						<td class="tbl_hddat">4T</td>
						<td class="tbl_hddat">%</td>
					</tr>

				</thead>
				<tbody id="tblbodyx">


				</tbody>
			</table>

		</div>  <!--Cluster end -->


		<!-- <h2 class="hder_colum">Supervisor Wise Sales Target</h2> -->
		<div id="div_supervisor">
			<div id="containery" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

			<table class="table" width="100%" border="0" >
				<thead>



					<tr class="tbl_hdery" >
						<td class="tbl_hddat" rowspan="3" valign="middle">Supervisor Name</td>
						<td class="tbl_hddat" colspan="4">Current Month</td>
						<td class="tbl_hddat" colspan="4">Cumilative</td>
					</tr>
					<tr class="tbl_hdery">
						<td class="tbl_hddat" rowspan="2">Target</td>
						<td class="tbl_hddat" rowspan="2">Archivement</td>
						<td class="tbl_hddat" colspan="2">VAR</td>
						<td class="tbl_hddat" rowspan="2">Target</td>
						<td class="tbl_hddat" rowspan="2">Archivement</td>
						<td class="tbl_hddat" colspan="2">VAR</td>
					</tr>
					<tr class="tbl_hdery">
						<td class="tbl_hddat">4T</td>
						<td class="tbl_hddat">%</td>
						<td class="tbl_hddat">4T</td>
						<td class="tbl_hddat">%</td>
					</tr>

				</thead>
				<tbody id="tblbodyy">


				</tbody>
			</table>
		</div> <!--end supervisor -->


	</div>


</body>
</html>


















<style type="text/css">
	/*@media(max-width: 650px){*/
		#logout, #Main_Menu, .footer_wrapper, .sf-menu{
			display: none;
		}
		/*}*/
		.tbl_hddat{
			padding: 0px !important;
			text-align: center;
		}
		.tbl_hdery{
			background: #5d6d7e    !important;
			padding: 0px;
			color: #fff;
		}
		.tbl_hder{
			background: #979a9a    !important;
			padding: 0px;
			color: #fff;
		}
		table{ 
			font-family: 'Verdana', Arial ;
			font-size: 10px;
		}
		/*table td{ border: 1px solid #2B908F;*/
		}
		tr:nth-child(even) {background: #000;}
		tr:nth-child(odd) {background: #fff}

		.hder_colum{
			margin: 0 auto;
			position: relative;
			z-index: 999;
			color: #f5ebeb;
			text-align: center;
			background: #333;
			font-size: 30px;
		}

		 .table > thead > tr > td{
		 	border :1px solid #ddd !important;
		 }


		


	</style>



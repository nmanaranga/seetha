
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Seetha</title>
	


	<link rel="stylesheet" href="<?=base_url();?>css/dashboard_login.css" />
	<script type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
	<script type='text/javascript' src='<?=base_url()?>js/dashboard_login.js'></script>
</head>
<body>
	<div class="wrapper">
		<div class="container">
			<div class="loginArea">
				<div class="loginHeader">

					<h1><img src="<?php echo base_url(); ?>images/company-logo.png" alt=""  width="180" height="60"/>
						<br>
						<!-- Accounts &amp; Inventory Management System</h1> -->
					</div>
					<div class="companyName">
						<p>Seetha Holdings (Pvt) Limited</p>
					</div>
					<div class="loginInputs">
						<div id="f" class="loader"> </div> 
						<div id="a" class="error"> </div>
						<div id="g_logo" class="logo">
							<form name="form" id="form" class="form-horizontal">
								<div class="input-group">
									<input id="txtUserName" type="text" autocomplete="off"  class="text_field userName"  value="" placeholder="User Name">               
								</div>
								<div class="input-group">
									<input id="txtPassword" autocomplete="off" type="password" class="text_field password" name="password" placeholder="Password">
								</div> 

								<div class="form-group">
									<div class="col-sm-12 controls">
										<button type="button" id="btnLogin" href="#" class="btn btn-success loginBut"> Login</button>                          
									</div>
								</div>
								<div class="forgotPw">
									<!-- <a href="#">Forgot Password?</a> -->
								</div>
							</form> 
						</div>
					</div>
				</div>
				<div class="footer">
					<?php
					$time = time () ;
					$year= date("Y",$time);
					$footer='&copy; 2006-'.$year.' Soft-Master Techonologies (Pvt) Ltd. All rights reserved |
					<a style="color:#FF9966"href="http://www.softmastergroup.com"> www.softmastergroup.com</a> |
					Web development section'; 
					echo $footer;
					?>
				</div>
			</div>
		</body>
		</html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link type="image/png" href="<?=base_url(); ?>img/ico.png" rel="icon">
<title>Inventory System - <?php echo $company; ?></title>
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/main.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/inputs.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/grid.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery-ui-1.8.4_.custom.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.tablescroll.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/jquery.autocomplete.css" />
<link href="<?=base_url(); ?>css/superfish.css" rel="stylesheet" type="text/css">
<!-- <link href="<?=base_url(); ?>css/slider.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>css/menu.css" />

<script type="text/javascript" src="<?=base_url(); ?>js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/slides.min.jquery.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/script.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/autoNumeric1.6.2.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.tablescroll.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/main.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/inputs.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>js/menu.js"></script>

<script>
$(function(){
	$('#slides').slides({
		preload: true,
		generateNextPrev: true
	});
});
</script>

<style type="text/css" media="screen">
.slides_container { width:630px; display:none; margin: auto;}
.slides_control{ height:550px !important; }
.prev{ position: absolute; top: 50px; text-decoration: none;}
.next{position: absolute; top: 50px; right:0; text-decoration: none;}
.slides_container div.slide { width:600px; height:170px; display:block; }
.item { float:left; width:135px;  height:135px; margin:0 10px; background:#efefef; }
.pagination { list-style:none;  margin:0; padding:0; display:none;}
.container{ width:750px; margin:auto; position: relative;}
.pagination .current a { color:red;}
</style>

</head>
<body ondragstart="return false;" ondrop="return false;">

<div id="blocker"></div>
<div id="logout"></div>
</div>
<div class="company_wrapper">
    <div class="company">
		<div id="logo"><!--<img src="<?=base_url(); ?>images/logo.jpg" alt="" >--></div>
		<div class="company_name"><h1><?php echo $company; ?></h1></div>
    </div>
</div>
<div id="slides">
    <div class="container">
		<div class="slides_container">
		    <div class="slide">
			<div id="menu">
			    <ul class="menu">
			    <?php
 				if($this->user_permissions->is_view('s_users')||
 				   $this->user_permissions->is_view('s_module')||
 				   $this->user_permissions->is_view('s_role')||
 				   $this->user_permissions->is_view('s_add_role')||
 				   $this->user_permissions->is_view('s_permission_level_order')||
 				   $this->user_permissions->is_view('u_branch_to_user')){

 				?>
	                <li><li><a href="#" class="parent"><span>PERMISSION</span><img src="<?php echo base_url(); ?>images/system.png" alt="" /></a>
	                    <ul>
	                        <?php if($this->user_permissions->is_view('s_users')){ ?><li><a target="_blank" href="?action=s_users">Users</a></li><?php } ?>
	                        <?php if($this->user_permissions->is_view('s_module')){ ?><li><a target="_blank" href="?action=s_module">Module</a></li><?php } ?>
	                        <?php if($this->user_permissions->is_view('s_role')){ ?><li><a target="_blank" href="?action=s_role">User Role</a></li><?php } ?>
	                        <?php if($this->user_permissions->is_view('s_add_role')){ ?><li><a target="_blank" href="?action=s_add_role">Add Role</a></li><?php } ?>
	                        <?php if($this->user_permissions->is_view('s_permission_level_order')){ ?><li><a target="_blank" href="?action=s_permission_level_order">User Permission Level Order</a></li> <?php } ?> 
	                        <?php if($this->user_permissions->is_view('u_branch_to_user')){ ?><li><a target="_blank" href="?action=u_branch_to_user">Add Branch To User</a></li><?php } ?>
	                    </ul>
	                </li>      
	            <?php } ?>                
			    </ul>
			</div>
		    </div>
		</div>
    </div>
</div>

<div id="copyright" style="display: none;">Copyright &copy; 2012 <a href="http://apycom.com/">Apycom jQuery Menus</a></div>

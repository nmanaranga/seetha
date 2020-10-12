<!DOCTYPE html>

<html lang="en">

    <head>

	<meta charset="utf-8">

	<title><?=strtoupper($company);?> - Distribution System (Login Area)</title>

		

		<link rel="stylesheet" href="<?=base_url();?>css/login.css" />

        <link rel="stylesheet" href="<?=base_url();?>css/jquery-ui-1.8.4.custom.css "/>

        <script type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>

        <script type="text/javascript" src="<?=base_url();?>js/jquery.ui.core.min.js"></script>

        <script type="text/javascript" src="<?=base_url();?>js/jquery-ui-1.8.20.custom.min.js"></script>

        <script type="text/javascript" src="<?=base_url();?>js/login.js"></script>

    

    </head>

    <body>

	<div class="wrapper">

    	<div id="logout"></div>

		<div class="content_wrapper">

        <div class="company_wrapper">

        	<div class="logo"><!--<img src="<?=base_url();?>images/logo.jpg" />--></div>

        	<div id="title"> </div>

            

        </div>

        <div id="slides">

        	<div class="login_area">

            	 <div class="left"><img src="<?=base_url();?>images/login.png" /></div>

           		 <div class="right">

                     <ul><fieldset><legend><div id="f"> </div> <div id="a"> </div></legend>

                        <li>

                         <span> User Name</span>

                        </li>

                        <li>

                        <input type="text" id="txtUserName" title="User Name" value=""/>

                        </li>

                        <li><span> Password</span>

                        </li>

                        <li>

                            <input type="password" autocomplete="off" id="txtPassword" title="Password" value=""/>

                        </li>



                       <!--  <li>

                         <span> Cluster Code</span>

                        </li>

                        <li>

                        <div id="cluster">

                            <select id="cl" style="width:185px;box-shadow:1px 1px 4px #000 inset;"></select>

                        </div>

                          

                        </li>





                        <li>

                         <span> Branch Code</span>

                        </li>

                        <li>

                         <div id="branch">

                            <select style="width:185px;box-shadow:1px 1px 4px #000 inset;"></select>

                         </div>

                         <input type="hidden" autocomplete="off" class="text_field text_field2" id="txtDate" value="<?php  echo date("Y-m-d");?>"  />

                        

                        </li> -->

                        <li><span></span>

                            <button style="width:60px;" id="btnLogin"> Login </button>

                        </li>

                        </fieldset> 

                    </ul>

                </div>

            </div>

        </div>

			<!--<div id="main"> 

				<div class="content">

					

					 

					  

					 

				  </div>

			</div>--><!--main-->

		</div>

		<div class="footer_wrapper">

			<div id="footer">

			 <?php

							$time = time () ;

							$year= date("Y",$time);

							$heading='Soft Master solutions';

							$footer='&copy; 2006-'.$year.' Soft-Master Techonologies (Pvt) Ltd. All rights reserved |

									<a href="http://www.softmastergroup.com"> www.softmastergroup.com</a> |

									Web development section |'; echo $footer;

						?>

			</div>

		</div>

	</div>

    </body>

</html>
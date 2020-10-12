<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title><?=strtoupper($company);?> - Distribution System (Login Area)</title>
        
        <!-- <link rel="stylesheet" href="<?=base_url();?>css/login.css" /> -->
        <link rel="stylesheet" href="<?=base_url();?>css/jquery-ui-1.8.4.custom.css "/>
        <link rel="stylesheet" href="<?=base_url();?>css/bootstrap.min.css "/>
        <link rel="stylesheet" href="<?=base_url();?>css/loginboot.css" />
        <link rel="stylesheet" href="<?=base_url();?>css/metrocss.css" />
        <script type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/jq.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/jquery-ui-1.8.20.custom.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/login.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/loginboot.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/jquery.blueberry.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/metrojs.js"></script>
        <script type="text/javascript">
            $(window).load(function() {
                $('.blueberry').blueberry();
            });
        </script>
        <script type="text/javascript">
         $(window).load(function() {
            $(".flip-list").liveTile();
        });
        </script>
    
    
    </head>
    <body>
        <div class="middlePage">     
            <div class="panel-body">
            <div class="row">
            <div class="col-md-8" style="padding:0;height:550px;">
                <div class="row">
                    <div class="col-md-12 logo" >
                        <?=strtoupper($company);?></br>
                        <span>Automated Recording System</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7" style="padding-right:0px;">
                       <div class="blueberry" style="padding-left:0px;padding-top:15px;">
                        <ul class="slides">
                            <li><img src="<?=base_url();?>img/main/1.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/2.jpg" style="width:380px; height:240px;" /></li>  
                            <li><img src="<?=base_url();?>img/main/3.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/4.jpg" style="width:380px; height:240px;" /></li>  
                            <li><img src="<?=base_url();?>img/main/5.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/6.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/7.jpg" style="width:380px; height:240px;" /></li>  
                            <li><img src="<?=base_url();?>img/main/8.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/9.jpg" style="width:380px; height:240px;" /></li>  
                            <li><img src="<?=base_url();?>img/main/10.jpg" style="width:380px; height:240px;" /></li>
                            <li><img src="<?=base_url();?>img/main/11.jpg" style="width:380px; height:240px;" /></li>
                          </ul>
                        </div>
                    </div>
                    <div class="col-md-5" style="padding-left:0px;">
                        <div class="mango">
                            <div class="list-tile">
                                <ul id="flip1" class="flip-list fourTiles" data-initdelay="500" data-mode="flip-list" data-delay="5000">
                                    <li>
                                        <div class="myClass" style="background-color:#ecf0f1;margin-bottom:0px;">
                                            <h4 class="hh">account</h4>
                                            <h3 class="h">Receivable</h3>
                                        </div>
                                        <div style="background-color:#ecf0f1;text-align:bottom;">
                                            <div class="bot">
                                                <h4 class="hh">safe</h4>
                                                <h3 class="h">Purchases</h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="myClass" style="background-color:#34495e;">
                                            <h4 class="hh" style="color:white;">gift</h4>
                                            <h3 class="h" style="color:white;">Vouchers</h3>
                                        </div>
                                        <div style="background-color:#34495e;">
                                            <div class="bot">
                                                <h4 class="hh" style="color:white;">easy</h4>
                                                <h3 class="h" style="color:white;">Inventory</h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="myClass" style="background-color:#ffa43a;">
                                            <h4 class="hh" style="color:white;">efficiently</h4>
                                            <h3 class="h" style="color:white;">Sales</h3>
                                        </div>
                                        <div style="background-color:#ffa43a;">
                                            <div class="bot">
                                                <h4 class="hh" style="color:white;">easier</h4>
                                                <h3 class="h" style="color:white;">Payments</h3>
                                            </div>
                                        </div>
                                    </li>
                                    <li data-direction="horizontal">
                                        <div class="myClass" style="background-color:#bdc3c7;">
                                            <h4 class="hh">cheque</h4>
                                            <h3 class="h">Printing</h3>
                                        </div>
                                        <div style="background-color:#bdc3c7;">
                                            <div class="bot">
                                                <h4 class="hh">service</h4>
                                                <h3 class="h">Module</h3>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="height:150px;">
                      
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <div class='our_msg'>
                        <div class="blueberry" style="padding-left:20px;padding-top:15px;">
                        <ul class="slides">
                           <!--  <li>Messages System Under Constructions....</li> -->
                            <li>Solution by Softmaster</li>
                    </ul>
                </div>
            </div>
                    </div> 
                </div>
            </div>
            <div class="col-md-4" style="height:550px;background-color: rgba(139,173,255,0.3);">
                <div id="f"> </div> 
                <div id="a"> </div>
                <div id="g_logo"><img src="<?php echo base_url(); ?>images/seetha.png" alt=""  width="130" height="86"/> </div> 
                <div class="spacing"></div>
                <form name="form" id="form" class="form-horizontal">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="txtUserName" type="text" autocomplete="off"  class="form-control"  value="" placeholder="User">                                        
                    </div>
                    <div class="spacing"></div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="txtPassword" autocomplete="off" type="password" class="form-control" name="password" placeholder="Password">
                    </div>  

                    <div class="spacing"></div>  
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                        <div id="cluster">
                          <select id="cl" class='form-control'>
                              <option>-- Select Cluster --</option>
                          </select>
                         </div>
                    </div>  

                    <div class="spacing"></div>  
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                        <div id="branch">
                            <select class='form-control'>
                                <option>-- Select Branch --</option>
                            </select>
                         </div>
                    </div>  

                    
                    <div class="spacing"></div>        
                    <input type="hidden" name='cluster' id='cluster' value='C1' title='C1'/>
                    <input type="hidden" autocomplete="off" class="text_field text_field2" id="txtDate" value="<?php  echo date("Y-m-d");?>"  />                                                      
                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="button" id="btnLogin" href="#" class="btn btn-success pull-right"><i class="glyphicon glyphicon-log-in"></i> Log in</button>                          
                        </div>
                        <div class="smtk"><a href='http://www.softmastergroup.com'><img src="<?=base_url();?>img/soft-master.png" style="width:90px; height:40px" /></a>
                        </div>
                    </div>
                </form>    
            </div>
            </div>
         </div>
         <div style="width:100%;border:0.5px solid #CCC;"></div>
                <div style="text-align:center;"><?php
                $time = time () ;
                $year= date("Y",$time);
                $footer='&copy; 2006-'.$year.' Soft-Master Techonologies (Pvt) Ltd. All rights reserved |
                        <a style="color:#FF9966"href="http://www.softmastergroup.com"> www.softmastergroup.com</a> |
                        Web development section |'; 
                        echo $footer;
                       
                ?></div>
            </div> 
        </div>

</body>
</html>

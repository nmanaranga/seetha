<?php if($this->user_permissions->is_view('u_branch_to_user')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/u_branch_to_user.js"></script>


<h2 style="text-align: center;">Add Branch To User</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/u_branch_to_user" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">User Roll</td>
                <td>
                	<input type="text" class="input_active_num"  name="u_roll" style="width:150px" id="u_roll" />
                    <input type="text" class="hid_value" name="roll_des" id="roll_des" style="width:300px"/>
                </td>
                
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>

            </tr>
            <tr>
                <td>User</td>
                <td>
                    <input type="text" class="input_active_num"  style="width:150px" name="u_user" id="u_user" readonly="readonly" />
                    <input type="text" class="hid_value" name="user_des" id="user_des" style="width:300px"/>
                </td>

                <td style="width: 50px; display:none;">No</td>
                <td>
                    <input type="hidden" class="input_active_num" name="id" id="id" title="<?=$max_no?>"/>
                    <input type="hidden" id="hid" name="hid" title="0" />                    
                </td>

                
            </tr>

            <tr>
                <td style="width: 100px;">Cluster</td>
                <td>
                  
                  <input type="text" class="input_active_num"  style="width:150px" name="u_cluster" id="u_cluster" />
                  <input type="text" class="hid_value" name="cluster_des" id="cluster_des" style="width:300px"/>
                  <input type="button" title="Load branches" id="load_bc" />
                  <input type="hidden" id="hid_tot" name="hid_tot"/>
                  <input type="hidden" id="hid_user" name="hid_user" title="0"/>
                </td>
                <td style="width: 100px;"></td>
                <td style="width: 100px;"></td>
           
            </tr>
            

            

          <tr>
                <td colspan="4" style="text-align: center;">


                     <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 20px;">Cluster</th>
                                <th class="tb_head_th" style="width: 140px;" >Cluster Name</th>
                                <th class="tb_head_th" style="width: 20px;">Branch</th>
                                <th class="tb_head_th" style="width: 120px;" >Branch Name</th>
                                <th class="tb_head_th"  style="width: 60px;">Active</th>
                                <th class="tb_head_th"  style="width: 70px;">Date From</th>
                                <th class="tb_head_th"  style="width: 75px;">Date From</th>
                                
                            </tr>
                        </thead>

                        <tbody id='t_branch'>
                            


                        </tbody>
                       
                        
                    </table> 
                    <div style="text-align: left; padding-top: 7px;">
                       
                    </div>
                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_add('u_branch_to_user')){ ?><input type="button"  id="btnSave" title="Save" /><?php } ?>
                       
                    </div>
                </td>
            </tr>
        </table>
    </form>


     <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='u_branch_to_user' title="u_branch_to_user" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='u_branch_to_user' title="u_branch_to_user" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
                 <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
                 <input type="hidden" name='rep_date' value='' title="" id="rep_date" >
                 <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
        
        </form>

</div>
<?php } ?>
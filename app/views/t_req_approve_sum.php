<?php if($this->user_permissions->is_view('t_req_approve_sum')){ ?>


<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />

<script type='text/javascript' src='<?=base_url()?>js/t_req_approve_sum.js'></script>
     <div id="det_box">
        <img id="close_det_box" src="<?=base_url(); ?>/images/close_button.png"/>
        <?php if($this->user_permissions->is_approve('t_req_approve_sum')){ ?>
            <div id="det_box_inner"><?php if(isset($det_box)){ echo $det_box;} ?></div>
        <?php } ?>
    </div>
<h2>Purchase Request Approve</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
 <form method="post" action="<?=base_url()?>index.php/main/save/t_req_approve_sum" id="form_">
<table style="width:100%;" id="tbl1" border="0">
    <tr>
        <td>Supplier</td>
      <td>
        <input type="text" id="supplier_id" name="supplier_id" title="" style="width:120px" class="input_txt" />
        <input type="text" id="supplier" name="supplier" title="" class="hid_value" style="width:350px" readonly="readonly"/>
        <input type="hidden" id="branch_num" name="branch_num"  class="hid_value" style="width:350px"/>
        <input type="button" id="load_pendings" title="Pending Requations"/>
        <td style="padding-left:11px; width:170px;" align='right'>
            No
            <input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" />
            <input type="hidden" id="hid" name="hid" title="0" />
            <input type="hidden" id="sub_hid" name="sub_hid" title="0" />
        </td>
    </td>
        
        
    </tr>

    <tr>
        <td>Type</td>
      <td>
 

        <?php if($this->utility->get_is_store_in_branch('1')) {
           echo "<select id='type' name='type' style='width:120px;'>
                    <option value='1'>Main Store</option>
                </select>";
        }else{
             echo "<select id='type' name='type' style='width:120px;'>
                    <option value='2'>Direct</option>
                </select>";
        }
        ?>








<!-- 
 

      <select id='type' name='type' style='width:120px;'>
          <option value='1'>Main Store</option>
          <option value='2'>Direct</option>
      </select> -->
        <input type="button" id="load_item_request" title="Load Item Request Note" style="width:160px;" />
            <input type="button" id="load_item_request_duplicate" title="Load Item Request Note" style="width:160px;" />
        
         <td width="180" align='right'>
            Date
            <?php if($this->user_permissions->is_back_date('t_req_approve_sum')){ ?>
            <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="text-align:right;"  />
            <?php }else{ ?> 
            <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="text-align:right;" /> 
            <?php } ?>  

        </td>
    </td>
        
        
    </tr>


    <tr>
    	<td colspan="3">
           <div class="">
    		<table  id="tgrid" style="width:100%">  
					
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 150px;">Code</th>
                                <th class="tb_head_th" style="width: 250px;">Description</th>
                                <th class="tb_head_th" style="width:50px;">Model</th>                            
                               
                                <th class="tb_head_th" style="width: 50px;">Request</th>
                                <th class="tb_head_th" style="width: 50px;">ROQ</th>
                                <th class="tb_head_th" style="width: 50px;">Cur Stock</th>
                                <th class="tb_head_th" style="width: 50px;">App Qty</th>
                                <th class="tb_head_th" style="width: 50px;">Branch</th>

                                   
                             
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='fooo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 150px;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text'  readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 250px;'/></td>";
                                        echo "<td><input type='text' class='' id='1_".$x."' name='1_".$x."' readonly='readonly' style='width : 150px;'/></td>";
                                        
                                        echo "<td><input type='text' class='g_input_amo' id='2_".$x."' name='2_".$x."' readonly='readonly' style='width : 50px;'/></td>";
                                       
                                        echo "<td><input type='text' class='g_input_amo' id='3_".$x."' name='3_".$x."' readonly='readonly' style='width : 50px;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo' id='4_".$x."' name='4_".$x."' readonly='readonly' style='width : 50px;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo' id='5_".$x."' name='5_".$x."' readonly='readonly' style='width : 50px;'/></td>";
                                        echo "<td><input type='button' id='6_".$x."' name='6_".$x."' class='fo br' disabled='disabled' style='margin:0;padding:5px;float:left; height:18px;width:50px;cursor:pointer' title=''  />
                                            <input type='hidden' id='approve_".$x."' name='approve_".$x."'/>
                                            <input type='hidden' id='item_price_".$x."' name='item_price_".$x."'/>
                                        </td>";
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>


			</table>
            </div>
         	
    	</td>
    </tr>

    <tr>
    	<td colspan="3">
    		<input type="button" id="btnExit" title="Exit" />
			<input type="button" id="btnReset" title="Reset" />
			<?php if($this->user_permissions->is_delete('t_req_approve_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
			<?php if($this->user_permissions->is_re_print('t_req_approve_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
			<?php if($this->user_permissions->is_add('t_req_approve_sum')){ ?><input type="button" id="btnSave1" title='Save <F8>' /><?php } ?>
            <input type='hidden' id='app_status' name='approve' title='1' value='1'/>
            <?php if($this->user_permissions->is_approve('t_req_approve_sum')){ ?><input type="button"  id="btnApprove" title="Approve" /><?php } ?>
    	</td>

    </tr>
    <tr>
      <td colspan="6"></td>
    </tr>
    <tr>
      <td colspan="6" style="color:red; font-size:12px;">** For View Item Details, Double Click Item Code</td>
    </tr>
</table><!--tbl1-->
</form>
<div id="load_table"></div>
</div>

<div id="light" class="white_content">
<div style='margin:-10px 10px 5px 5px;padding:5px 0px;'>
    <h3 style='width:938px;font-family:calibri;background:#283d66;color:#fff;text-transform:uppercase;'>Purchase Requisition Details</h3>
    <div id='item_det'></div>
    <hr style="width:100%"/>

    <div style='float:left;margin-right:100px;' id='current_qty_det'>
    </div>
    <div style='float:left;' id='request_qty_det'>
    </div>
    <div style='clear:both'></div>
    <hr style="width:100%"/>
    <hr style="width:100%"/>
    <div id="d_table"></div>
</div>    
    <input type='button' value='close' title='close' id='popclose' style="position:absolute;bottom:5px;right:5px;"/>
</div>
<div id="fade" class="black_overlay"></div>
<?php } ?>
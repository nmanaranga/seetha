<?php if($this->user_permissions->is_view('t_req_sum')){ ?>
<style type="text/css">
  #mframe1 {
    width: 1300px;
    padding: 7px;
    margin: auto;
    z-index: 1;
    margin-top:10px;
    background:#fff;
    border:5px solid #ccc;
  }

</style>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_req_sum.js'></script>
  
    <div id="det_box">
      <img id="close_det_box" src="<?=base_url(); ?>/images/close_button.png"/>
      <?php if($this->user_permissions->is_approve('t_req_sum')){ ?> 
        <div id="det_box_inner"><?php if(isset($det_box)){ echo $det_box;} ?></div>
      <?php } ?>
    </div>
<h2>Purchase Request</h2>
<div class="dframe" id="mframe1">
 <form method="post" action="<?=base_url()?>index.php/main/save/t_req_sum" id="form_">
<table style="width:1300px;" id="tbl1" border="0">
    <tr>
      <!-- <td>Branch</td>
      <td colspan="3">
     	
     	<input type="text" style="font-weight:bold;width:475px;" id="branch" name="branch" title="<?= $branch; ?>"  class="hid_value" readonly="readonly"/></td> -->
     	<td style="width:20px;">Type</td>
      <td colspan="3">
        <select name='type' id='type'>
          <option value='1'>Main Store</option>
          <option value='2'>Direct</option>
        </select>
      </td>
     	<td style="width:20px;">No</td>
     	<td align="right" style="width:150px;">
     		<input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
			<input type="hidden" id="hid" name="hid" title="0" />
		</td>
    </tr>

    <tr>
    	<!--<td>Supplier</td>
     	<td colspan="3"><input type="text" id="supplier_id" name="supplier_id" title="" style="width:120px" class="input_txt" />
     	<input type="text" id="supplier" name="supplier" title="" class="hid_value" style="width:350px" readonly="readonly"/>
      <input type="hidden" id="supplier_tbl" name="supplier_tbl" title="" class="hid_value" style="width:350px" readonly="readonly"/>
      </td>-->
     	<td></td>
      <td colspan="3"></td>
     	<td style="width:20px;">Date</td>
     	<td align="right" style="width:150px; float:right;">
        <?php if($this->user_permissions->is_back_date('t_req_sum')){ ?>
        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="text-align:right;"/>
        <?php }else{ ?> 
        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>"  style="text-align:right;"/> 
        <?php } ?>  
    </td>


    </tr>

<!--      <tr>
        
    	<td colspan="6"><input type="button" title="Load Re-order Levels Items" style="width:200px;"/>
            <input type="checkbox" name="approve" id="approve" style="margin-left:10px;"/>
            <span style="margin-left:10px;">Approve</span></td>
     	
    </tr> -->

    <tr>
    	<td colspan="6">
            
    			<table style="width: 1280px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 90px;">Supplier</th>
                                <th class="tb_head_th" style="width: 150px;">Supplier Name</th>

                                <th class="tb_head_th" style="width: 120px;">Code</th>
                                <th class="tb_head_th" style="width: 150px;">Description</th>
                                <th class="tb_head_th" style="width: 100px;">Model</th>
                                <th class="tb_head_th" style="width: 80px;">Last Price</th>
                                <th class="tb_head_th" style="width: 80px;">Sales Price</th>
                                <th class="tb_head_th" style="width: 50px;">Cur Stock</th>
                                <th class="tb_head_th" style="width: 40px;">ROL</th>
                                <th class="tb_head_th" style="width: 40px;">Week1</th>
                                <th class="tb_head_th" style="width: 40px;">Week2</th>
                                <th class="tb_head_th" style="width: 40px;">Week3</th>
                                <th class="tb_head_th" style="width: 40px;">Week4</th>
                                <th class="tb_head_th" style="width: 60px;">Total</th>
                                <!-- <th class="tb_head_th" style="width: 150px;">Supplier</th> -->
                                <th class="tb_head_th" style="width: 180px;">Comment</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='text' id='supp_".$x."' pic='sup_".$x."' name='supplier_id_".$x."' class='supplier_id' title='' style='width:100%' class='input_txt' /></td>";
                                        echo "<td><input type='text' id='supplier_".$x."' name='supplier' title='' class='hid_value' style='width:100%' readonly='readonly'/></td>";
                                        echo "<input type='hidden' id='supplier_tbl' name='supplier_tbl' title='' class='hid_value' style='width:350px' readonly='readonly'/></td>";

                                        echo "<td style=''><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'  style='width : 100%;' /></td>";
                                        echo "<td  ><input type='text' class='g_input_txt g_col_fixed'  id='n_".$x."' name='n_".$x."' readonly='readonly' maxlength='150' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 g_col_fixed qun' id='1_".$x."' name='1_".$x."' readonly='readonly' style='width : 100%;'/></td>";

                                         echo "<td><input type='text' class='g_input_num2 g_col_fixed ' id='lp_".$x."' name='lp_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                          echo "<td><input type='text' class='g_input_num2 g_col_fixed ' id='sp_".$x."' name='sp_".$x."' readonly='readonly' style='width : 100%;'/></td>";


                                        echo "<td  class='g_col_fixed'>
                                                  <input type='button'  class='qunsb' id='btnb_".$x."' style='margin:0;padding:5px;float:left;height:6px;width:6px;cursor:pointer;display:none;'/>
                                                  <input type='text' class='g_input_num g_col_fixed amo' id='2_".$x."' name='2_".$x."' readonly='readonly' style='width : 66%;'/>
                                              </td>";
                                        echo "<td><input type='text' class='g_input_num g_col_fixed dis' id='3_".$x."' name='3_".$x."' readonly='readonly' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num tf tf".$x."' id='4_".$x."' name='4_".$x."' style='width : 100%;'/>
                                              <input type='hidden' id='roq_".$x."'  name='roq_".$x."'/>
                                        </td>";
                                        echo "<td style=''><input type='text' class='g_input_num tf tf".$x."'  id='5_".$x."' name='5_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num tf tf".$x."' id='6_".$x."' name='6_".$x."' style='width : 100%;'/></td>";
                                        echo "<td style=''><input type='text' class='g_input_num tf tf".$x."' id='7_".$x."' name='7_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_num g_col_fixed st' id='8_".$x."' name='8_".$x."' style='width : 100%;' readonly='readonly'/></td>";
                                        //echo " //<input type='text' class='free_is' id='9_".$x."' name='9_".$x."' style='width : 100%;'/>";
                                        echo "<input type='hidden' name='sup_".$x."' id='sup_".$x."'/>";
                                        echo "<td style=''><input type='text' id='c_".$x."' name='c_".$x."' style='width : 100%;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                  </table>
              
    	</td>
    </tr>
  </table>
  <table>
    <tr>
    	<td >Note</td>
    	<td colspan="5"><input type="text" name="comment" id="comment" style="width:1000px;" class="input_txt" maxlength="255" /></td>
    </tr>

    <tr>
    	<td colspan="6"><input type="button" id="btnExit" title="Exit" />
			<input type="button" id="btnReset" title="Reset" />
			<?php if($this->user_permissions->is_delete('t_req_sum')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
			<?php if($this->user_permissions->is_re_print('t_req_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
		
			<?php if($this->user_permissions->is_add('t_req_sum')){ ?><input type="button"  id="btnSave1" title='Save <F8>' />
      <input type="button"  id="btnSavee" title='Save <F8>' style='display:none;'/><?php } ?>
		  <?php if($this->user_permissions->is_approve('t_req_sum')){ ?><input type="button"  id="btnApprove" title="Approve" value="Approve"/><?php } ?>
      <input type='hidden' id='app_status' name='approve' title='1' value='1'/>
                <?php 
                    if($this->user_permissions->is_print('t_req_sum')){ ?>
                    <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                <?php } ?> 
		</td>
    </tr>
    <tr>
      <td colspan="6"></td>
    </tr>
    <tr>
      <td colspan="6" style="color:red; font-size:12px;">** For View Item Details, Double Click Item Code</td>
    </tr>

</table>

</form>


</div>
<?php } ?>
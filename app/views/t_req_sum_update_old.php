<style type="text/css">
 .wsrol{
width:900px;
height:300px;
overflow:scroll;
 }
</style>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />

<script type='text/javascript' src='<?=base_url()?>js/t_req_sum_update.js'></script>
<h2>Purchase Request Approve</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">

<table style="width:100%;" id="tbl1" border="0">
    <tr>
        <td>Supplier</td>
        <td colspan="3"><input type="text" id="supplier_id" name="supplier_id" title="" style="width:120px" class="input_txt" />
        <input type="text" id="supplier" name="supplier" title="" class="hid_value" style="width:350px" readonly="readonly"/>
        <!-- this  hidden filed   for taking the number of branhes in the js file -->
        <input type="hidden" id="branch_num" name="branch_num"  class="hid_value" style="width:350px"/>
      </td>
    </tr>

    <tr>
        <td>Cluster</td>
      <td colspan="2">
        <input type="text" id="cluster_id" name="cluster_id"  style="width:120px" class="input_txt" title="<?=$cluster?>" readonly="readonly" />
        <input type="text" id="cluster" name="cluster" title="" style="width:350px" class="hid_value" readonly="readonly"/></td>
    </tr>

    

    <tr>
        <td colspan="3"><input type="button" id="load_item_request" title="Load Item Request Note" style="width:150px;" /></td>
        
    </tr>

    <tr>
    	<td colspan="3">
           <div class="wsrol">
    		<table  id="tgrid">
					
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 150px;">Code</th>
                                <th class="tb_head_th" style="width: 250px;">Description</th>
                                <th class="tb_head_th" style="width:150px;">Model</th>
                                
                                <th class="tb_head_th" style="width: 100px;">Total</th>
                               <!--  <th class="tb_head_th" style="width: 100px;">BR1 R</th>
                                <th class="tb_head_th" style="width: 100px;">BR1 ROQ</th>
                                <th class="tb_head_th" style="width: 100px;">BR1 Stck</th>
                                <th class="tb_head_th" style="width: 100px;">BR1 Appr</th> -->

                                <?php for($i=0;$i<$branch;$i++){?>
                                    <th class="tb_head_th" style="width: 100px;">BR <?=$i+1?> R</th>
                                    <th class="tb_head_th" style="width: 100px;">BR<?=$i+1?> ROQ</th>
                                    <th class="tb_head_th" style="width: 100px;">BR<?=$i+1?> Stck</th>
                                    <th class="tb_head_th" style="width: 200px;">BR<?=$i+1?> Appr</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' readonly='readonly' style='width : 150px;' /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 250px;'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' style='width : 150px;'/></td>";
                                        
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' style='width : 100px;'/></td>";
                                        for($i=0;$i<$branch;$i++){
                                        echo "<td><input type='text' class='g_input_amo dis' id='3_".$i.$x."' name='3_".$i.$x."' style='width : 100px;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' id='4_".$i.$x."' name='4_".$i.$x."' style='width : 100px;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo free_is' id='5_".$i.$x."' name='5_".$i.$x."' style='width : 100px;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='6_".$i.$x."' name='6_".$i.$x."' style='width : 100px;'/></td>";
                                        }
                                        
                                        
                                       // echo "<td id='t_".$x."' name='t_".$x."' style='text-align: right;background-color: #f9f9ec; ' class='tf'>&nbsp;</td>";
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
			<input type="button" id="btnReset" title="Cancel" />
			<input type="button" id="btnDelete" title="Delete" />
			<input type="button" id="btnPrint" title="Print" />
			
			<input type="button"  id="btnSave1" title='Save <F8>' />
			
    	
    		<input type="text" class="input_txt" name="r_requet_qty" id="r_requet_qty" title="R_Requet_QTY" style="width:90px;"/>
    		<input type="text" class="input_txt" name="r_roq_reorder_qty" id="r_roq_reorder_qty" title="R_Roq_Reorder_QTY" style="width:90px;"/>
    		<input type="text" class="input_txt" name="current_stck" id="current_stck" title="Stck-Current-Stock" style="width:90px;"/>
    		<input type="text" class="input_txt" name="appr_appro_qty" id="appr_appro_qty" title="Appr_Appro_QTY" style="width:90px;"/>
    	</td>

    </tr>
</table><!--tbl1-->

</div>
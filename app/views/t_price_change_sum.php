<?php if($this->user_permissions->is_view('t_price_change_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_price_change_sum.js"></script>
<style>
#war
{
  text-align:center;
  width:980px;
  background-color:#f2dede;
  margin-left: 80px;
  color: #a94442;
  border-color: #ebccd1;
  border-radius: 4px;
  margin: 30px auto auto;
  padding: 5px;
  font-size: 20px;
}

</style>

<div id='war'>
<p style='margin-left: 960px;position: absolute;margin-top: 4px;'>
<img src='<?=base_url(); ?>/images/close_button.png' id='war_close'>
</p>
<p><b>Price Change In Here Will Cause All the Other Batches Of The Item, To Use The Same Price Going Forward.</b></p>
</div>
<h2 style="text-align: center;">Price Change</h2>
<div class="dframe" id="mframe" style="width:1100px;">
   <form method="post" action="<?=base_url()?>index.php/main/save/t_price_change_sum" id="form_">
      <table style="width: 100%" border="0">
         <tr>
            <td style="width: 100px;"></td>
            <td></td>
            <td style="width: 50px;">No</td>
            <td>
               <input type="text" class="input_active_num" name="id" id="id" style="width: 100%;" title="<?=$max_no?>" />
               <input type="hidden" id="hid" name="hid" title="0" />
            </td>
         </tr>
         <tr>
            <td></td>
            <td></td>
            <td style="width: 100px;">Date</td>
            <?php if($this->user_permissions->is_back_date('t_price_change_sum')){ ?>
            <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            <?php }else{?>
            <td style="width: 100px;"><input type="text" class="input_text" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            <?php } ?>
         </tr>
         <tr>
            <td></td>
            <td></td>
            <td style="width: 100px;">Ref. No</td>
            <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title=" " style="width: 100%;" maxlength="25"/></td>
         </tr>
         <tr>
            <td colspan="4" style="text-align: center;">

               <table style="width: 900px;" id="tgrid" >
                  <thead>
                     <tr>
                        <!-- <th class="tb_head_th" style='width:70px;'>Code</th>
                        <th class="tb_head_th" style='width:150px;'>Description</th>
                        <th class="tb_head_th" style='width:70px;'>Model</th>
                        <th class="tb_head_th" style='width:70px;'>Cost</th>
                        <th class="tb_head_th" style='width:70px;'>Last Price</th>
                        <th class="tb_head_th"  style='width:70px;'>New  Price </th>
                        <th class="tb_head_th"  style='width:70px;'>New Last %</th>
                        <th class="tb_head_th"  style='width:70px;'>Max Price</th>
                        <th class="tb_head_th" style='width:80px;'>New M Price</th>
                        <th class="tb_head_th" style='width:60px;'>New Max %</th> -->

                        <th class="tb_head_th" style='width:120px;'>Code</th>
                        <th class="tb_head_th" style='width:150px;'>Description</th>
                        <th class="tb_head_th" style='width:70px;'>Model</th>
                        <th class="tb_head_th" style='width:70px;'>Cost</th>
                        <th class="tb_head_th" style='width:70px;'>Last Price</th>
                        <th class="tb_head_th"  style='width:70px;'>Max Price</th>
                        <th class="tb_head_th"  style='width:70px;'>Last %</th>
                        <th class="tb_head_th"  style='width:70px;'>Max %</th>

                        <th class="tb_head_th"  style='width:70px;'>New Last </th>
                        <th class="tb_head_th"  style='width:70px;'>New Last %</th>
                        <th class="tb_head_th" style='width:80px;'>New Max</th>
                        <th class="tb_head_th" style='width:69px !importnat;'>New Max %</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        //if will change this counter value of 25. then have to change edit model save function.
                        for($x=0; $x<25; $x++){
                            echo "<tr>";
                                echo "<td style='width:60px;'><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:130px;'><input type='text' readonly='readonly' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:60px;'><input type='text' readonly='readonly' class='g_input_txt' id='1_".$x."' name='1_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:60px;'><input type='text' readonly='readonly' class='g_input_amo qun' id='2_".$x."' name='2_".$x."' style='width:100%;'/></td>";

                                echo "<td style='background-color: #f9f9ec; width:70px;'><input type='text' readonly='readonly' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:60px;'><input type='text' readonly='readonly' class='g_input_amo free_is' id='5_".$x."' name='5_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:60px;'><input type='text' readonly='readonly' class='g_input_amo qun' id='22_".$x."' name='22_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec; width:60px;'><input type='text' readonly='readonly' class='g_input_amo free_is' id='55_".$x."' name='55_".$x."' style='width:100%;'/></td>";

                                echo "<td style='width:70px;'><input type='text' class='g_input_amo ss p_value  dis_pre' id='4_".$x."' name='4_".$x."' style='width:100%;'/></td>";
                                echo "<td style='width:70px; background-color: #f9f9ec;'><input type='text' readonly class='g_input_amo ' id='41_".$x."' name='41_".$x."' style='width:100%;'/></td>";
                                echo "<td style='width:70px;'><input type='text' class='g_input_amo ss precent free_is' id='t_".$x."' name='t_".$x."' style='width:100%;'/></td>";
                                
                                echo "<td style='width:70px; background-color: #f9f9ec;'><input type='text' readonly class='g_input_amo ' id='t1_".$x."' name='t1_".$x."' style='width:100%;'/></td>";
                        
                            echo "</tr>";
                        }
                        ?>
                  </tbody>
               </table>
               <table style="width:100%;" border="0">
                  <tr>
                     <td colspan="8">&nbsp;</td>
                  </tr>
                  <tr style="background-color: transparent;">
                     <td colspan="6" style="padding-left : 10px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_re_print('t_price_change_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                        <?php if($this->user_permissions->is_add('t_price_change_sum')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </form>
<hr style="width:100%" class='hid_line'/>

   <table style="width:100%" border='0'>
      <tr>
        <td colspan='5'><h3 style="width:100%;background:#aaa;color:#fff;">Find Price Change</h3></td>
      </tr>

      <tr>
        <td>Item </td>
        <td style=""><input type="text" id="item_code" class="input_txt" /> </td>
        <td colspan='3'><input type='text' id='item_description' class="hid_value" style="width:350px"/> <input type="text" id="item_model" class="hid_value" /> </td>
      </tr>

      <tr>
        <td>Date From</td>

        <td width='100'><input type="text" id="date_from" class="input_date_down_future" style="width:150px"/></td>
        <td width='75'> Date To</td>
        <td><input type="text" id="date_to" class="input_date_down_future" style="width:150px"/><input type="button" id="search_item" title="Search" /></td>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
      <table style="width: 100%;" id="tgrid2" >
                  <thead>
                     <tr>
                        <th class="tb_head_th" >Trans No</th>
                        <th class="tb_head_th" >Trans Date</th>
                        <th class="tb_head_th" >Operator</th>
                        <th class="tb_head_th" >Cost</th>
                        <th class="tb_head_th" >Last Price</th>
                        <th class="tb_head_th" >New Last Price</th>
                        <th class="tb_head_th" >Max Price</th>
                        <th class="tb_head_th" >New Max Price</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        //if will change this counter value of 25. then have to change edit model save function.
                        for($x=0; $x<25; $x++){
                            echo "<tr>";
                                echo "<td width='70'><input type='text' class='g_input_txt g_col_fixed' id='00_".$x."' name='00_".$x."' /></td>";
                                echo "<td width='70'><input type='text' class='g_input_txt'  id='nn_".$x."' name='nn_".$x."' style='width:100%;'/></td>";
                                echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt'  id='op_".$x."' style='width:100%;'/></td>";
                                echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo g_col_fixed' id='222_".$x."' name='222_".$x."' s/></td>";
                                echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo g_col_fixed' id='33_".$x."' name='33_".$x."' /></td>";
                                echo "<td width='70' ><input type='text' class='g_input_amo g_col_fixed' id='44_".$x."' name='44_".$x."' '/></td>";
                                echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo g_col_fixed' id='555_".$x."' name='555_".$x."' '/></td>";
                                echo "<td width='70' ><input type='text' class='g_input_amo g_col_fixed' id='tt_".$x."' name='tt_".$x."' '/></td>";
                        
                            echo "</tr>";
                        }
                        ?>
                  </tbody>
               </table>
          </tr>     
   </table>

   </form>


      <?php if($this->user_permissions->is_print('t_price_change_sum')){ ?>
        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_price_change_sum' title="t_price_change_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='t_price_change_sum' title="t_price_change_sum" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        
        </form>
        <?php } ?>

</div>
<?php } ?>
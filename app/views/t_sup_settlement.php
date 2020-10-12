<?php if($this->user_permissions->is_view('t_sup_settlement')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_sup_settlement.js"></script>


<h2 style="text-align: center;">Supplier Settlement</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_sup_settlement" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Code</td>
                <td>
                <input type="text" id="supplier" name="supplier"class="input_active" title=""/>
                       <input type="text" class="hid_value" id="supplier_id" title="" readonly="readonly" style="width: 300px;" />
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no;?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td></td>
                <td>
                   
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_sup_settlement')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } else{ ?> 
                        <input type="text" class="input_txt" style="width:100%;" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />  
                    <?php } ?>    
                </td>
            </tr>
          <tr>
                <td colspan="4" style="text-align: center;">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Type</th>
                                <th class="tb_head_th" style="width: 80px;">No</th>
                                <th class="tb_head_th" style="width: 80px;">Date</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                                <th class="tb_head_th" style="width: 80px;">Balance</th> 
                                <th class="tb_head_th" style="width: 80px;">Settle</th>
                                <th class="tb_head_th" >Description</th>  
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<1; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='width:100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text'  class='g_input_txt g_col_fixed'  id='n_".$x."' name='n_".$x."' style='width:100%;background-color: #f9f9ec;' maxlength='150'/></td>";
                                        echo "<td><input type='text'  class='g_input_txt qun g_col_fixed' id='1_".$x."' name='1_".$x."' style='width:100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text'  class='g_input_amo amo g_col_fixed' id='2_".$x."' name='2_".$x."'  style='width:100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text'  class='g_input_amo qun g_col_fixed' id='3_".$x."' name='3_".$x."' style='width:100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo tdr' id='4_".$x."' name='4_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' style='width:100%;' class='' id='5_".$x."' name='5_".$x."' /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
               
            </td>

            </tr>
            <tr>
                <td colspan="4" align="center">
                    <div style="margin:0 auto;width:100%;">
                        <b>Total Dr</b>&nbsp; <input type="text" class="hid_value g_input_amounts" readonly="readonly" id="ttl_dr" name="ttl_dr" style="margin-left:37px;"/>
                    </div>

                </td>
            </tr>

                      <tr>
                <td colspan="4" style="text-align: center;">
                  
                    <table style="width: 875px;" id="tgrid2">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Type</th>
                                <th class="tb_head_th" style="width: 80px;">No</th>
                                <th class="tb_head_th" style="width: 80px;">Date</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                                <th class="tb_head_th" style="width: 80px;">Balance</th> 
                                <th class="tb_head_th" style="width: 80px;">Settle</th>
                                <th class="tb_head_th" >Description</th>  
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<100; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                                  <input type='text' class='g_input_txt g_col_fixed fo'     id='00_".$x."' name='00_".$x."'  style='width:100%;background-color: #f9f9ec;'  /></td>";
                                        echo "<td><input type='text' class='g_input_txt g_col_fixed'        id='nn_".$x."' name='nn_".$x."'  style='width:100%;background-color: #f9f9ec;' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_txt qun g_col_fixed'    id='11_".$x."' name='11_".$x."'  style='width:100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo g_col_fixed'    id='22_".$x."' name='22_".$x."'  style='width:100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo qun g_col_fixed'    id='33_".$x."' name='33_".$x."'  style='width:100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo tcr' id='44_".$x."' name='44_".$x."' /></td>";
                                        echo "<td><input type='text' class='' id='55_".$x."' name='55_".$x."'  /></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
               
            </td>

            </tr>

            <tr style="background:#e6eeff">

            <td colspan="1"></td>
            <td colspan="5">
                <b style="padding-left:96px;">Total</b>
                <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_amount" name="total_amount" style="margin-left:28px; width:85px;background:#e6eeff;" />
                <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_balance" name="total_balance" style="width:85px;background:#e6eeff;" />
                        

            </td>


            </tr>
            <tr>
                <td colspan="4" align="center">
                    <div style="margin:0 auto;width:100%;">
                        <b>Total Cr</b>&nbsp; <input type="text" class="hid_value g_input_amounts" readonly="readonly" id="ttl_cr" name="ttl_cr" style="margin-left:37px;" />
                    </div>

                </td>
            </tr>






            <tr>
                <td colspan="4">
                            <div style="text-align: left; padding-top: 7px;">
                                <input type="button" id="btnExit" title="Exit" />
                                <input type="button" id="btnReset" title="Reset" />
                                <?php if($this->user_permissions->is_delete('t_sup_settlement')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                                <?php if($this->user_permissions->is_re_print('t_sup_settlement')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                              
                                <?php if($this->user_permissions->is_add('t_sup_settlement')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>             
                            </div>
                
                </td>
                
            </tr>

            
        </table>
    </form>
</div>
<?php } ?>

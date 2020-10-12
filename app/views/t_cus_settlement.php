<?php if($this->user_permissions->is_view('t_cus_settlement')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type="text/javascript" src="<?=base_url()?>js/t_cus_settlement.js"></script>
    <h2 style="text-align: center;">Customer Settlement</h2>
    <div class="dframe" id="mframe">
        <form method="post" action="<?=base_url()?>index.php/main/save/t_cus_settlement" id="form_">
            <table style="width: 100%" border="0">
                <tr>
                    <td style="width: 100px;">Code</td>
                    <td>
                        <input type="text" id="customer" name="customer"class="input_active" title=""/>
                        <input type="text" class="hid_value" id="customer_id" title="" readonly="readonly" style="width: 300px;" />
                        &nbsp;&nbsp;<input type="checkbox" id="auto_fill" name="auto_fill"/>Auto Fill
                    </td>
                    <td style="width: 50px;">No</td>
                    <td>
                        <input type="text" class="input_active_num" name="id" id="id" style="width:100%" title="<?=$max_no;?>" />
                        <input type="hidden" id="hid" name="hid" title="0" />
                    </td>

                </tr><tr>
                    <td></td>
                    <td>
                        <td style="width: 100px;">Date</td>
                        <td style="width: 100px;">
                            <?php if($this->user_permissions->is_back_date('t_cus_settlement')){ ?>
                                <input type="text" style='text-align:right;' class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                            <?php } else { ?>  
                                <input type="text" style='text-align:right;' class="" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
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
                                        <input type='text'  class='g_input_txt fo'  id='0_".$x."' name='0_".$x."'  /></td>";
                                        echo "<td><input type='text'  class='g_input_txt  g_col_fixed'     id='n_".$x."'  style='text-align:right;' name='n_".$x."' maxlength='150'/></td>";
                                        echo "<td><input type='text'  class='g_input_num2 g_col_fixed qun' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text'  class='g_input_amo  g_col_fixed amo' id='2_".$x."' name='2_".$x."' /></td>";
                                        echo "<td><input type='text'  class='g_input_num2 g_col_fixed qun' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo tdr' id='4_".$x."' name='4_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text'  class='g_input_txt g_col_fixed' id='5_".$x."' name='5_".$x."' style='width:100%' /></td>";
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
                                <b>Total Dr</b>&nbsp; <input type="text" class="hid_value g_input_amounts" readonly="readonly" id="ttl_dr" name="ttl_dr" style="margin-left:46px;"/>
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
                                    for($x=0; $x<300; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                        <input type='text' class='g_input_txt g_col_fixed foo' id='00_".$x."' name='00_".$x."'  /></td>";
                                        echo "<td><input type='text' class='g_input_txt g_col_fixed' style='text-align:right;' id='nn_".$x."' name='nn_".$x."' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 g_col_fixed qun' id='11_".$x."' name='11_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo g_col_fixed' id='22_".$x."' name='22_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_num2 g_col_fixed qun' id='33_".$x."' name='33_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo tcr' id='44_".$x."' name='44_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_col_fixed' id='55_".$x."' name='55_".$x."' style='width:100%' /></td>";
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
                            <b style="padding-left:162px;">Total</b>
                            <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_amount" name="total_amount" style="margin-left:31px; width:105px; background:#e6eeff;" />
                            <input type="text" class="g_input_amounts" readonly="readonly" title="" id="total_balance" name="total_balance" style="width:105px; background:#e6eeff;" />


                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" align="center">
                            <div style="margin:0 auto;width:100%;">
                                <b>Total Cr</b>&nbsp; <input type="text" class="hid_value g_input_amounts" readonly="readonly" id="ttl_cr" name="ttl_cr" style="margin-left:34px; width:105px;"/>
                            </div>

                            <div style="margin:0 auto;width:100%;">
                                <b>Cr Balance</b>&nbsp; <input type="text" class="hid_value g_input_amounts" readonly="readonly" id="ttl_cr_nt_bal" name="ttl_cr_nt_bal" style="margin-left:13px; width:105px;"/>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div style="text-align: left; padding-top: 7px;">
                                <input type="button" id="btnExit" title="Exit" />
                                <input type="button" id="btnReset" title="Reset" />
                                <?php if($this->user_permissions->is_delete('t_cus_settlement')){ ?><input type="button" id="btnDelete" title="Cancel" /><?php } ?>
                                <?php if($this->user_permissions->is_re_print('t_cus_settlement')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                <?php if($this->user_permissions->is_add('t_cus_settlement')){ ?><input type="button" id="btnSave" title='Save <F8>' />  <?php } ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <?php } ?>
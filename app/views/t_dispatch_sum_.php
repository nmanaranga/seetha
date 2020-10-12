<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_dispatch_sum.js"></script>
<h2 style="text-align: center;">Dispatch Note / Loading /Unloading</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_dispatch_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
            <td style="width: 100px;">From Store</td>
                <td><span id="from_store"><?=$get_from_store?></span>
                       <input type="text" class="hid_value" id="from_store_id" title="" readonly="readonly" style="width: 300px;" />
                    </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>"/>
                    <input type="hidden" id="hid" name="hid" title="0" />
            </td>
            </tr>
            <tr>
            <td style="width: 100px;">To Store</td>
                <td><span id="to_store"><?=$get_to_store?></span>
                       <input type="text" class="hid_value" id="to_store_id" title="" readonly="readonly" style="width: 300px;" />
                    </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
          
            </tr>
            <tr>
            <td style="width: 100px;">Group</td>
                <td><input type="text" id="customer" name="customer"class="input_active" title=""/>
                       <input type="text" class="hid_value" id="customer_id" title="" readonly="readonly" style="width: 300px;" />
                    </td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;" maxlength="25"/></td>
            </tr>
            <tr>
            <td style="width: 100px;">Officer</td>
                <td><input type="text" id="officer" name="officer"class="input_active" title=""/>
                       <input type="text" class="hid_value" id="officer_id" title="" readonly="readonly" style="width: 300px;" />
                    </td>
            </tr>
            <tr>
                <td>Memo</td>
                <td>
                   <input type="text" class="input_txt" name="memo" id="memo" title="" style="width:455px;"/>
                </td></td>
                
            </tr><tr>
                <td colspan="4" style="text-align: center;">
                
                    <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 70px;">Item Code</th>
                                <th colspan="5" class="tb_head_th">Name</th>
                                <th class="tb_head_th" style="width: 70px;">Batch No</th>
                                <th class="tb_head_th" style="width: 70px;">QTY</th>
                               
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt fo' id='n_".$x."' name='n_".$x."' /></td>";
                                        echo "<td colspan='5'><input type='text' class='g_input_txt' id='1_".$x."' name='1_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_txt txt_align' id='2_".$x."' name='2_".$x."' style='width : 100%;'/></td>";
                                        echo "<td><input type='text' class='g_input_txt txt_align' id='3_".$x."' name='3_".$x."' style='width : 100%;'/></td>";

                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
             
                        <table style="width:100%;" border="0">
                            <tr style="background-color: transparent;">
                                
                                <td colspan="6" style="padding-left : 10px;">
                                		<input type="button" id="btnExit" title="Exit" />
										<input type="button" id="btnReset" title="Cancel" />
										<input type="button" id="btnDelete" title="Delete" />
										<input type="button" id="btnPrint" title="Print" />
										<input type="button"  id="btnSave" title='Save <F8>' />
										
                                </td>
                                <td align="left" style="margin-bottom :0px;">Gross</td>
                                <td>
                                	<input type='text' id='total2' readonly="readonly" title="" name='gross' style="background:transparent;border:dotted 1px #ccc;width:120px;text-align:right;" /></td>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td style="padding-right : 7px;">&nbsp;</td>
                                <td colspan="5" style="padding-right : 10px;">&nbsp;</td>
                                <td align="left" >Discount</td>
                                <td><input type='text'  id='discount' readonly="readonly" title="" name='discount' style="background:transparent;border:dotted 1px #ccc;width:120px;text-align:right;" /></td>
                            </tr>
                            <tr style="background-color: transparent;">
                                <td style="padding-right : 7px;">&nbsp;</td>
                                <td colspan="5" style="padding-right : 10px;">&nbsp;</td>
                                <td align="left"> Net Amount</td>
                                <td width="1w0"><input type='text'  id='net_amount' readonly="readonly" title="" name='net_amount' style="background:transparent;border:dotted 1px #ccc;width:120px;text-align:right;" /></td>
                            </tr>
                        
                        
                    </table>
                 
                </td>
            </tr>
        </table>
    </form>
</div>


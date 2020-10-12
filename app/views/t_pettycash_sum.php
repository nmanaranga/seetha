


<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_pettycash_sum.js"></script>

<h2 style="text-align: center;">Petty Cash Voucher</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_pettycash_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Memo</td>
                <td>
                	
                    
                    <input type="text" class="input_txt" title='' readonly="readonly" id="note"  style="width: 450px;">
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?//=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>

            </tr><tr>
                <td>Petty Cash A/C</td>
                <td><select></select><input type="text" class="hid_value" name="jt" id="jt" title="" style="width: 300px;" maxlength="255" /></td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>

            <tr>
                <td>Balance</td>
                <td><input type="text" class="input_txt" name="balance" id="balance"/></td>
                <td style="width: 100px;">Ref. No</td>
                <td style="width: 100px;"><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 100%;"/></td>
           
            </tr>

          <tr>
                <td colspan="4" style="text-align: center;">
                	<div style="height:300;overflow:scroll">
                    <table style="width: 875px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Item Code</th>
                                <th class="tb_head_th">Name</th>
                                <th class="tb_head_th" style="width: 80px;">Amount</th>
                                <th class="tb_head_th" style="width: 80px;">Memo</th>
                              
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'  /></td>";
                                        echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' readonly='readonly' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' /></td>";
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
                </div>


                    <div style="text-align: left; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
                        <input type="button" id="btnDelete" title="Delete" />
                        <input type="button" id="btnPrint" title="Print" />
                        <?php if($this->user_permissions->is_add('018')){ ?>
                        <input type="button"  id="btnSave1" title='Save <F8>' />

                        <?php } ?>
                        <span style="text-align:left;margin-left:200px;">Net Amount</span>
                    <input type="text" class="input_txt" name="net_amount" id="net_amount" style="width:100px;"/>
                    
                    
                    </div>
                
                    
                </td>
            </tr>
        </table>
    </form>
</div>

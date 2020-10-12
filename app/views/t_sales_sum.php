<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_sales_sum.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_sales_sum.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_payment_option.js'></script>

<div id="light" class="white_content2">
<div style="width:940px;height:30px;background:#60152C;padding:5px;">
    <h2>Add Serials</h2>
</div>
<div class="dframe" id="mframe" style="width:940px;margin:0;padding:0;">
<table style="width:950px;" id="tbl1" border="0">
    <tr>
        <td valign="top" class="content" style="width:100%">
            <form id="form1_" method="post" action="<?=base_url()?>index.php/main/save/t_serial_movement" >
                    <table style="width:100%;" id="tbl2" border="0">
                        <tr>
                            <td>Type</td>
                            <td colspan="4"><input type="text" id="type_seri" name="type_seri" class="hid_value" style="width:150px;" value="1" readonly="readonly"/></td>
                        </tr>
                        <tr>
                            <td>No</td>
                            <td colspan="4"><input type="text" id="no" name="no" class="hid_value" style="width:150px;" readonly="readonly" />
                            QTY
                            <input type="text" id="qty" name="qty" class="hid_value" style="width:150px;" readonly="readonly"/></td>
                        </tr>
                        <tr>
                            <td>Item</td>
                            <td colspan="4"><input type="text" id="item_code" name="item_code" style="width:150px;" class="hid_value"/>
                            <input type="text" id="item" name="item" class="hid_value" style="width:355px;"  />     
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td colspan="2" width="240px;">
                                <fieldset >
                                    <legend>Serials</legend>
                                    <div style="height:200px;overflow:scroll;">
                                    <table cellpadding="0" border="0">
                                            <tbody id="set_serial" style=''>
                                                                               
                                            </tbody>
                                    </table>
                                  </div>
                                </fieldset>

                            </td>
                            <td colspan="2" width="360px;">
                                <fieldset>
                                    <legend>Add Serials</legend>
                                    <table style="width:100%" cellpadding="0" border="0">
                                        <tr>
                                            <td colspan="2">
                                                <table>
                                                    <tr>
                                                        <td colspan="2">Last Serial Code : <input type="text" readonly="readonly" class="hid_value" id="last_serial"/></td>
                                                    <tr>
                                                    <tr>
                                                        <td colspan="2"><input type="hidden" readonly="readonly" class="input_txt" title="Serial Get Media"/></td>
                                                    <tr>
                                                    <tr>
                                                        <td colspan="2"><input type="hidden" readonly="readonly" class="input_txt" title="Generate" id="generate"/></td>
                                                    <tr>

                                                    <tr>
                                                        <td colspan="2" style="background:#C0C0C0;padding:5px;border:1px solid #708090;">Generate Serials</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Free Fix</td>
                                                        <td><input type="text" name="free_fix" id="free_fix" class="input_txt" style="width:250px;"/></td>
                                                    </tr>

                                                    <tr>
                                                        <td>Post Fix</td>
                                                        <td><input type="text" name="post_fix" id="pst" class="input_txt" style="width:250px;"/></td>
                                                    </tr>   

                                                    <tr>
                                                        <td>Start NO</td>
                                                        <td><input type="text" name="start_no" id="abc" class="input_txt" style="width:250px;"/></td>
                                                    </tr>   
                                                    
                                                    <tr>
                                                        <td>QTY</td>
                                                        <td><input type="text" name="quantity" id="quantity" class="hid_value" style="width:250px;" readonly="readonly" /></td>
                                                    </tr>   

                                                    <tr>
                                                        <td colspan="2">
                                                            <input type="button" title="Generate" id="gen"/>
                                                            <input type="button" title="Clear" id="clear"/>
                                                            <input type="button" title="Add" id="add"/>
                                                        </td>
                                                    </tr>
                                                        
                                                </table>
                                            </td>
                                            <td>
                                                <div style="height:200px;overflow:scroll;">
                                                <table style="width:100%" cellpadding="0">
                                                <tbody id="set_serial2">
                                                                                               
                                                        </tbody>
                                                </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset> 
                            </td>
                        </tr>
                    
                        <tr>
                            <td style="text-align:right" colspan="5">
                               
                                <input type="button" id="btnExit1" title='Exit' /> 
                                <input type="button" id="btnSave1" title='Save <F8>' />
                                                      
                            </td>
                        </tr>
                    </table><!--tbl2-->
                </form><!--form_-->
            
      </td>
            
    </tr>
</table><!--tbl1-->
</div>

</div>

<div id="fade" class="black_overlay"></div>


<!-- ------------------------------------------------------------------------------------------- -->

<div id="light2" class="white_content">
<div style="width:1010px;height:25px;background:#60152C;padding:5px;"><h2 >Payment Option</h2></div>
<div class="dframe" id="mframe" style="width:960px;">
<table style="width:100%;" id="tbl1" border="1">
    <tr rowspan=2>
        <td valign="top" class="content" style="width:35%">
            <div class="form" id="form">
                <form id="form__" method="post" action="<?=base_url()?>index.php/main/save/t_payment_option" >
                    <table style="width:100%;" id="tbl2">
                        <tr>
                            <td>Cash</td>
                            <td><input type="text" id="cash" class="input_txt g_input_amo" title="" name="cash" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Issue Cheque</td>
                            <td><input type="text" id="cheque_issue" class="input_txt g_input_amo" title="" name="cheque_issue" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Recieve Cheque</td>
                            <td><input type="text" id="cheque_recieve" class="input_txt g_input_amo" title="" name="cheque_recieve" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Credit Card</td>
                            <td><input type="text" id="credit_card" class="input_txt g_input_amo" title="" name="credit_card" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Credit Note</td>
                            <td><input type="text" id="credit_note" class="input_txt g_input_amo" title="" name="credit_note" style="width:150px;text-align:right;" /></td>
                        </tr>

                          <tr>
                            <td>Debit Note</td>
                            <td><input type="text" id="debit_note" class="input_txt g_input_amo" title="" name="debit_note" style="width:150px;text-align:right;" /></td>
                        </tr>

                        
                        <tr>
                            <td>Bank Debit</td>
                            <td><input type="text" id="bank_debit" class="input_txt g_input_amo" title="" name="bank_deposit" style="width:150px;text-align:right;" /></td>
                        </tr>


                        <tr>
                            <td>Discount</td>
                            <td><input type="text" id="discount" class="input_txt g_input_amo" title="" name="discount" style="width:150px;text-align:right;" /></td>
                        </tr>
                        
                        <tr>
                            <td>Advance</td>
                            <td><input type="text" id="advance" class="input_txt g_input_amo" title="" name="advance" style="width:150px;text-align:right;" /></td>
                        </tr>   

                        <tr>
                            <td>Gift Voucher</td>
                            <td><input type="text" id="gv" class="input_txt g_input_amo" title="" name="gv" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Credit</td>
                            <td><input type="text" id="credit" class="input_txt g_input_amo" title="" name="credit" style="width:150px;text-align:right;" /></td>
                        </tr>

                        <tr>
                            <td>Priviledge Card</td>
                            <td><input type="text" id="pc" class="input_txt g_input_amo" title="" name="pc" style="width:150px;text-align:right;" /></td>
                        </tr>


                        
                    </table><!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
      </td>
            <td style="width:65%;" valign="top" class="content" >
                    <div id="tabs" style="margin-bottom:10px;">
                        <ul>
                            <li><a href="#tabs-1">Credit Card</a></li>
                            <li><a href="#tabs-2">Credit Note</a></li>
                            <li><a href="#tabs-3">Debit Note</a></li>
                            <li><a href="#tabs-4">Bank Debit</a></li>
                            <li><a href="#tabs-5">Gift Voucher</a></li>
                            <li><a href="#tabs-6">Advance</a></li>
                            <li><a href="#tabs-7">Issue Cheque</a></li>
                            <li><a href="#tabs-8">Privilege Card</a></li>
                            <li><a href="#tabs-9">Recieve Cheque</a></li>
                            
                            
                        </ul>



                    <div id="tabs-1">
                        <table style="width:100%" cellpadding="0" id="tgrid3">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" style="width:80px;">Type</th>
                                    <th class="tb_head_th" >Number</th>
                                    <th class="tb_head_th" style="width:80px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='type1_".$x."' name='type1_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='no1_".$x."' name='no1_".$x."'/></td>";                
                                        echo "<td><input type='text' class='g_input_amo cc_amount'  id='amount1_".$x."' name='amount1_".$x."'/></td>";                
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                    </div>



                    <div id="tabs-2">
                        <table style="width:100%" cellpadding="0" id="tgrid4">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" >No</th>
                                    <th class="tb_head_th" >Date</th>
                                    <th class="tb_head_th" >Amount</th>
                                    <th class="tb_head_th" >Balance</th>
                                    <th class="tb_head_th" >Settle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                   
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='no2_".$x."' name='no2_".$x."' /></td>";
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;' id='date2_".$x."' name='date2_".$x."'/></td>";                
                                        echo "<td><input type='text' class='g_input_amo cn_amount'  id='amount2_".$x."' name='amount2_".$x."'/></td>";   
                                        echo "<td><input type='text' class='g_input_txt'  id='balance2_".$x."' name='balance2_".$x."'/></td>"; 
                                        echo "<td><input type='text' class='g_input_txt'  id='settle2_".$x."' name='settle2_".$x."'/></td>";                  
                                echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                    </div><!-- tabs-2-->


                    
                    <div id="tabs-3">
                        <table style="width:100%" cellpadding="0" id="tgrid5">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" >No</th>
                                    <th class="tb_head_th" >Date</th>
                                    <th class="tb_head_th" >Amount</th>
                                    <th class="tb_head_th" >Balance</th>
                                    <th class="tb_head_th" >Settle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='no3_".$x."' name='no3_".$x."' /></td>";
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='date3_".$x."' name='date3_".$x."'/></td>";                
                                        echo "<td><input type='text' class='g_input_amo dn_amount'  id='amount3_".$x."' name='amount3_".$x."'/></td>";   
                                        echo "<td><input type='text' class='g_input_txt'  id='balance3_".$x."' name='balance3_".$x."'/></td>"; 
                                        echo "<td><input type='text' class='g_input_txt'  id='settle3_".$x."' name='settle3_".$x."'/></td>";                  
                                echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                    </div><!-- tabs-3-->


                        
                    <div id="tabs-4">
                        <table style="width:100%" cellpadding="0" id="tgrid6">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" >Code</th>
                                    <th class="tb_head_th" >Name</th> 
                                    <th class="tb_head_th" >Amount</th>
                                   
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt bank_deb' id='code4_".$x."' name='code4_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='name4_".$x."' name='name4_".$x."'/></td>";                
                                        echo "<td><input type='text' class='g_input_amo bd_amount'  id='amount4_".$x."' name='amount4_".$x."'/></td>";                
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>
                    </div><!--end tabs- 4-->


                    <div id="tabs-5">
                        <table style="width:100%" cellpadding="0" id="tgrid7">
                            <thead>
                                <tr>
                                    
                                    <th class="tb_head_th" >Number</th>
                                    <th class="tb_head_th" style="width:80px;">Issue Date</th>
                                    <th class="tb_head_th" style="width:80px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='type5_".$x."' name='type5_".$x."' /></td>";
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='no5_".$x."' name='no5_".$x."'/></td>";                
                                        
                                        echo "<td><input type='text' class='g_input_amo gv_amount'  id='amount5_".$x."' name='amount5_".$x."'/></td>";                
                                echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                    </div><!--end tabs-5-->



                    <div id="tabs-6">
                        <table style="width:100%" cellpadding="0" id="tgrid8">
                            <thead>
                                <tr>
                                    
                                    <th class="tb_head_th" >No</th>
                                    <th class="tb_head_th" style="width:80px;">Date</th>
                                    <th class="tb_head_th" style="width:80px;">Amount</th>
                                    <th class="tb_head_th" style="width:80px;">Balance</th>
                                    <th class="tb_head_th" style="width:80px;">Settle</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='no6_".$x."' name='no6_".$x."' /></td>";
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='date6_".$x."' name='date6_".$x."'/></td>";
                                        echo "<td><input type='text' class='g_input_amo a_amount'  id='amount6_".$x."' name='amount6_".$x."'/></td>";                
                                        echo "<td><input type='text' class='g_input_amo'  id='balance6_".$x."' name='balance6_".$x."'/></td>";
                                        echo "<td><input type='text' class='g_input_amo'  id='cdate6_".$x."' name='cdate6_".$x."'/></td>";              
                                                    
                                        
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>
                    </div><!--end tabs-6-->

                    <div id="tabs-7">
                            <table style="width:100%" cellpadding="0" id="tgrid9">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" style="width:80px;">Bank</th>
                                    <th class="tb_head_th" >Description</th>
                                    <th class="tb_head_th" style="width:80px;">Cheque No</th>
                                    <th class="tb_head_th" style="width:80px;">Amount</th>
                                    <th class="tb_head_th" style="width:80px;">CHQ Date</th>
                                    
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='bank7_".$x."' name='bank7_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='des7_".$x."' name='des7_".$x."'/></td>";              
                                        echo "<td><input type='text' class='g_input_txt'  id='chqu7_".$x."' name='chqu7_".$x."'/></td>";
                                        echo "<td><input type='text' class='g_input_amo ci_amount'  id='amount7_".$x."' name='amount7_".$x."'/></td>";                
                                                        
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='cdate7_".$x."' name='cdate7_".$x."'/></td>";              
                                                    
                                        
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                    </div><!--end tabs-7-->





            
        
                    <div id="tabs-8">
                        <table style="width:100%" cellpadding="0" id="tgrid10">
                            <thead>
                                <tr>
                                    <th class="tb_head_th" style="width:80px;">Card No</th>
                                    <th class="tb_head_th" >Available Points</th>
                                    <th class="tb_head_th" style="width:80px;">Redim</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<1; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt' id='type8_".$x."' name='type8_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo' readonly='readonly' id='no8_".$x."' name='no8_".$x."' style='text-align:right;background:#EDEDED;' /></td>";                
                                        
                                        echo "<td><input type='text' class='g_input_amo' id='amount8_".$x."' name='amount8_".$x."' style='text-align:right;background:#EDEDED;'/></td>";                
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                        <div style="margin:0 auto">
                            <input type="button" title="Check" id="chck_cus_card" style="margin:0 auto;widht:250px;height:30px;"/>
                        </div>


                    </div><!--end tabs-8-->




                    <div id="tabs-9">
                        <table style="width:100%" cellpadding="0" id="tgrid10">
                            <thead>
                                <tr>
                                    <th class="tb_head_th">Bank</th>
                                    <th class="tb_head_th">Branch</th>
                                    <th class="tb_head_th">Account No</th>
                                    <th class="tb_head_th">Cheque No</th>
                                    <th class="tb_head_th">Amount</th>
                                    <th class="tb_head_th">CHQ Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                                               
                                    for($x=0; $x<10; $x++){
                                        echo "<tr>";
                                        echo "<td><input type='text' class='g_input_txt bank9' id='bank9_".$x."' name='bank9_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_txt branch9' id='branch9_".$x."' name='branch9_".$x."' /></td>";                
                                        
                                        echo "<td><input type='text' class='g_input_txt' id='acc9_".$x."' name='acc9_".$x."' /></td>";                
                                        echo "<td><input type='text' class='g_input_txt' id='cheque9_".$x."' name='cheque9_".$x."'/></td>";
                                        echo "<td><input type='text' class='g_input_amo cr_amount' id='amount9_".$x."' name='amount9_".$x."'/></td>";                
                                        
                                        echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;' id='date9_".$x."' name='date9_".$x."'/></td>";                
                                        
                                        echo "</tr>";
                                    }
                                                        
                                ?>                                          
                            </tbody>
                        </table>

                       


                    </div><!--end tabs-9-->
                        
                    
                </div><!--end tabs-->


            </td>
    </tr>

    <tr>
                           <td style="text-align:right" colspan="2">
                                <input type="hidden" id="code_" name="code_" title="0" />
                                <input type="button" id="btnExit2" title='Exit' />
                                <input type="button" id="btnSave2" title='Save <F8>' />
                                <input type="button" id="btnReset" title='Reset'>                        
                            </td>
    </tr>
</table><!--tbl1-->
</div>


</div><!-- close payment option -->
<div id="fade2" class="black_overlay"></div>

<!-- ------------------------------------------------------------------------------------------------------- -->

<h2>Sales</h2>

<div class="dframe" id="mframe" style="padding-right:25px;">
<form method="post" action="<?=base_url()?>index.php/main/save/t_sales_sum" id="form_">

        <input type="hidden" name="load_opt" id="load_opt" value="0" />

        <input type="hidden" name="hid_cash" id="hid_cash" />
        <input type="hidden" name="hid_cheque_recieve" id="hid_cheque_recieve" />
        <input type="hidden" name="hid_cheque_issue" id="hid_cheque_issue" />
        <input type="hidden" name="hid_credit_card" id="hid_credit_card" />
        <input type="hidden" name="hid_credit_note" id="hid_credit_note" />
        <input type="hidden" name="hid_debit_note" id="hid_debit_note" />
        <input type="hidden" name="hid_bank_debit" id="hid_bank_debit" />
        <input type="hidden" name="hid_discount" id="hid_discount" />
        <input type="hidden" name="hid_advance" id="hid_advance" />
        <input type="hidden" name="hid_gv" id="hid_gv" />
        <input type="hidden" name="hid_credit" id="hid_credit" />
        <input type="hidden" name="hid_pc" id="hid_pc" />
        <input type="hidden" name="hid_pc_type" id="hid_pc_type" />

         <input type="hidden" name="hid_priv_card" id="hid_priv_card" />


<table style="width:100%;" id="tbl1" border="0">
    <tr>
    	<td width="65">Customer</td>
    	<td width="100"><input type="text" id="customer" class="input_active" title="" name="customer" /></td>
    	<td colspan="3"><input type="text" class="hid_value" id="customer_id"  title="" readonly="readonly" style="width:330px;" /></td>
    	<td width="20">&nbsp;</td>
        <td width="50">No</td>
    	<td width="150"><input type="text" class="input_active_num" name="id" id="id" maxlength="10" title="<?=$max_no?>" style="width:150px;"/>
			<input type="hidden" id="hid" name="hid" title="0" />
		</td>  		
    </tr>

    <tr>
    	<td>Address</td>
    	<td colspan="4"><input type="text" class="hid_value" id="address" title="" readonly="readonly" style="width:485px;"/></td>
    	
    	<td>&nbsp;</td>
    	<td>Date</td>
    	<td><input type="text" class="input_date_down_future" readonly="readonly" style="width:150px;" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
    </tr>

    <tr>
    	<td>Balance</td>
    	<td><input type="text" id="balance" name="balance" class="input_txt" title=""/></td>
    	<td width="50">&nbsp;</td>
    	<td>Category</td>
    	<td><?php echo $sales_category;?></td>
    	<td>&nbsp;</td>
    	<td>Type</td>
    	<td><select name="type" id="type"> 
            <option value="4">Cash</option>
            <option value="5">Credit</option>
            <option value="6">HP</option>
            <option value="7">Internal</option>
        </select></td>

    </tr>

    <tr>
    	<td>SO No</td>
    	<td><input type="text" id="serial_no" name="serial_no" class="input_txt" title="" /></td> 
    	<td>&nbsp;</td>
    	<td>Group</td>
    	<td><?php echo $groups;?></td>
    	<td>&nbsp;</td>
    	<td>Sub No</td>
    	<td><input type="text" id="sub_no" name="sub_no" class="input_active_num" title=""/></td>
    </tr>

    <tr>
    	<td>Store</td>
    	<td><?php echo $stores; ?></td>
    	<td colspan="3"><input type="text"  id="store_id" style="width:330px;" class="hid_value" title="" readonly="readonly"/></td>
    	
    	<td>&nbsp;</td>
    	<td>Ref No</td>
    	<td><input type="text" name="ref_no" id="ref_no" class="input_txt" title="" maxlength="10"/></td>
    </tr>

    <tr>
    	<td colspan="8">
       
    		<table style="width:100%" id="tgrid" border="0" class="tbl">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 50px;">Code</th>
                                <th class="tb_head_th" style='width: 50px;'>Description</th>
                                <th class="tb_head_th" style="width: 50px;">Model</th>
                                <th class="tb_head_th" style="width: 50px;">Batch</th>
                                <th class="tb_head_th" style="width: 50px;">QTY</th>
                                <th class="tb_head_th" style="width: 50px;">FOC</th>
                                <th class="tb_head_th" style="width: 50px;">Price</th>
                                <th class="tb_head_th" style="width: 50px;">Dis %</th>
                                <th class="tb_head_th" style="width: 50px;">Discount</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                                <th class="tb_head_th" style="width: 50px;">Warranty</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                              <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='background-color: #f9f9ec;width:100%;' />
                                              <input type='hidden' name='bqty_'".$x."' id='bqty_".$x." title='0' style='display:block;visibility:hidden;'/>
                                              </td>
                                                <input type='hidden' id='setserial_".$x."' title='0' name='setserial_".$x."' />
                                                <input type='hidden' id='numofserial_".$x."' title='' name='numofserial_".$x."' />
                                                <input type='hidden' id='itemcode_".$x."' title='0' name='itemcode_".$x."' />";

                                        echo "<td style='width:100px;'><input type='text' class='g_input_txt'   id='n_".$x."' name='n_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt model' id='2_".$x."' name='2_".$x."' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_txt batch' id='1_".$x."' name='1_".$x."' style='width : 100%;text-align:right;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num qty qun' id='5_".$x."' name='5_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_num foc' id='4_".$x."' name='4_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo price' id='3_".$x."' name='3_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis_pre' id='6_".$x."' name='6_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo dis' id='7_".$x."' name='7_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                        echo "<td><input type='text' class='g_input_amo amount' id='8_".$x."' name='8_".$x."' style='width : 100%;text-align:right;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_num warranty' id='9_".$x."' name='9_".$x."' style='width : 100%;background-color: #f9f9ec;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                </tbody>
              </table>
            
    	</td>
    </tr>
</table>
<table border="0" style="width:100%;">
    <tr>
    	<td>Memo</td>
    	<td colspan="5"><input type="text" class="input_txt" title="" id="memo" name="memo" style="width:423px;" maxlength="100"/></td>
    	<td align="right">Gross</td>
    	<td width="150"><input type="text" class="g_input_amo" readonly='readonly' id="gross" name="gross" style="border:none;background:transparent;border:1px dotted #ccc;"/></td>
    </tr>

    <tr>
    	<td>Sales Rep</td>
    	<td colspan="5"><input type="text" name="sales_rep" id="sales_rep" style="width:120px;" class="input_active" title=""/>
    	<input type="text" id="sales_rep2" style="width:300px;" class="hid_value" readonly="readonly" title=""/></td>
    	<td rowspan="3" colspan="3">
    		
    		<table style="width:100%" id="tgrid2">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 50px;">Type</th>
                                <th class="tb_head_th">Description</th>
                                <th class="tb_head_th" style="width: 50px;">Rate%</th>
                                <th class="tb_head_th" style="width: 50px;">Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<15; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='hh_".$x."' id='hh_".$x."' title='0' />
                                        <input type='hidden' name='hhh_".$x."' id='hhh_".$x."' title='0' />
                                                <input type='text' class='g_input_txt foo' id='00_".$x."' name='00_".$x."' readonly='readonly' style='width : 100%;background-color: #f9f9ec;' /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='nn_".$x."' name='nn_".$x."' maxlength='150' style='width : 100%;' readonly='readonly'/></td>";
                                        echo "<td><input type='text' class='g_input_amo rate' id='11_".$x."' name='11_".$x."' style='width : 100%; text-align:right; background-color:#f9f9ec;'/></td>";
                                       
                                        echo "<td style='background-color:#f9f9ec;'><input type='text' id='tt_".$x."' name='tt_".$x."' class='g_input_amo aa' style='text-align: right;background-color:#f9f9ec;'/></td>";
                                    echo "</tr>";
                                }
                            ?>
                </tbody>
              </table>
            <input type='hidden' name='additional_amount' id="additional_amount" />
    	</td>
    </tr>
    <tr>
    		<td colspan="6">
    			<fieldset    >
    				<legend>Privilege Card</legend>
    				Privilege Card
    				<input type="text" name="privi_card" class="input_txt" id="privi_card" title="" style="width:100px;"/>
    				Points
    				<input type="text" name="points" class="input_txt" id="points" title="" style="width:100px;"/>
    			</fieldset>
    		</td>
    </tr>

    <tr>
    	<td colspan="6">

    		<input type="checkbox" name="payment_option" id="payment_option"/>Payment Option - 
    		Default get as payment
    	</td>
    </tr>

    <tr>
    	<td colspan="7">
    		<input type="button" id="btnExit" title="Exit" />
			<!--<input type="button" id="btnClearr" title="Clear" />-->
			<input type="button" id="btnResett" title="Reset" />
			<input type="button" id="btnDelete" title="Delete" />
			<input type="button" id="btnPrint" title="Print" />
            <input type="hidden" name="srls" id="srls"/>
			<input type="hidden" name="credit_amount" id="credit_amount"/> 
          
			<input type="button" title="Delivery Note"/>
			<input type="button" title="Payments" id="btnSave"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span style="margin-right:0px;">Net Amount</span>
    	</td>
        
        <td><input type="text" id="net" name="net" class="g_input_amo net" readonly='readonly'/>
        </td>
    </tr>



</table><!--tbl1-->

</form>

        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_sales_sum' title="t_sales_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='sales' title="sales" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        
        </form>



</div>
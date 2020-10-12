    <script type='text/javascript' src='<?=base_url()?>js/t_payment_option.js'></script>
    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/" >
    <input type="hidden" id="form_id" title="<?=base_url()?>index.php/main/save/"/>
    <input type="hidden" id="sp_form_id" title="<?=base_url()?>index.php/main/special_save/"/>
    <div id="light2" class="white_content">
        <div style="width:950px;height:30px;background:#69F;padding:5px;"><h2 >Payment Option</h2></div>
        <div class="dframe" style="width:960px;">
            <table style="width:100%;" id="tbl1" border="1">
                <tr rowspan=2>
                    <td valign="top" class="content" style="width:35%">
                        <div class="form" id="form">
                            <table style="width:100%;" id="tbl2">
                                 <?php if($this->validation->payment_option_validation($type,'cash')) {?>
                                <tr>
                                    <td>Cash</td>
                                    <td><input type="text" id="cash" class="input_txt g_input_amo" title="" name="cash" style="width:150px;text-align:right;" /></td>
                                </tr> 
                                <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'credit')) {?>
                                 <tr>
                                    <td>Credit</td>
                                    <td><input type="text" id="credit" class="input_txt g_input_amo" title="" name="credit" style="width:150px;text-align:right;" />
                                        <input type='radio' name='ci' style='cursor:pointer' value='c' title='c' class='ci' id='cic'/>
                                    </td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'chq_issue')) {?>
                                <tr>
                                    <td>Issue Cheque</td>
                                    <td><input type="text" id="cheque_issue" class="hid_value g_input_amo" title="" name="cheque_issue" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'chq_receive')) {?>
                                <tr>
                                    <td>Recieve Cheque</td>
                                    <td><input type="text" id="cheque_recieve" class="hid_value g_input_amo" title="" name="cheque_recieve" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'credit_card')) {?>
                                <tr>
                                    <td>Credit Card</td>
                                    <td><input type="text" id="credit_card" class="hid_value g_input_amo" title="" name="credit_card" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'crn')) {?>
                                <tr>
                                    <td>Credit Note</td>
                                    <td><input type="text" id="credit_note" class="hid_value g_input_amo" title="" name="credit_note" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'drn')) {?>
                                <tr>
                                    <td>Debit Note</td>
                                    <td><input type="text" id="debit_note" class="hid_value g_input_amo" title="" name="debit_note" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'bank_deposit')) {?>
                                <!-- <tr>
                                    <td>Bank Debit</td>
                                    <td><input type="text" id="bank_debit" class="hid_value g_input_amo" title="" name="bank_deposit" style="width:150px;text-align:right;" /></td>
                                </tr> -->
                                 <?php } ?>
                                 
                                  <?php if($this->validation->payment_option_validation($type,'discount')) {?>
                                <tr>
                                    <td>Discount</td>
                                    <td><input type="text" id="discount" class="input_txt g_input_amo" title="" name="discount" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                 <?php if($this->validation->payment_option_validation($type,'advance')) {?>
                                <!-- <tr>
                                    <td>Advance</td>
                                    <td><input type="text" id="advance" class="hid_value g_input_amo" title="" name="advance" style="width:150px;text-align:right;" /></td>
                                </tr>  --> 
                                 <?php } ?>

                                <?php if($this->validation->payment_option_validation($type,'gift_voucher')) {?>
                                <tr>
                                    <td>Gift Voucher</td>
                                    <td><input type="text" id="gv" class="hid_value g_input_amo" title="" name="gv" style="width:150px;text-align:right;" /></td>
                                </tr> 
                                <?php } ?>

                                <?php if($this->validation->payment_option_validation($type,'privilege_card')) {?>
                                <tr>
                                    <td>Priviledge Card</td>
                                    <td><input type="text" id="pc" class="input_txt g_input_amo" title="" name="pc" style="width:150px;text-align:right;" /></td>
                                </tr>
                                 <?php } ?>

                                <?php if($this->validation->payment_option_validation($type,'installment')) {?>
                                <tr>
                                    <td>Installment</td>
                                    <td><input type="text" id="installment" class="input_txt g_input_amo" title="" name="installment" style="width:150px;text-align:right;" />
                                        <input type='radio' style='cursor:pointer' name='ci' value='i' title='i' class='ci' id='ici'/>  
                                        <input type='hidden' name='total_interest_amount' id='total_interest_amount' value='0'/>  
                                          
                                    </td>
                                </tr>
                                <?php } ?>
                            </table><!--tbl2-->
                        </div><!--form-->
                    </td>
                    <td style="width:65%;" valign="top" class="content" >
                        <div id="tabs" style="margin-bottom:10px;">
                            <ul>
                                <?php if($this->validation->payment_option_validation($type,'credit_card')) {?><li><a href="#tabs-1">Credit Card</a></li> <?php } ?>
                                <?php if($this->validation->payment_option_validation($type,'crn')) {?><li><a href="#tabs-2" id="credit_tab">Credit Note</a></li> <?php } ?>
                                <?php if($this->validation->payment_option_validation($type,'drn')) {?><li><a href="#tabs-3" id="debit_tab">Debit Note</a></li> <?php } ?>
                                <!-- <?php if($this->validation->payment_option_validation($type,'bank_deposit')) {?><li><a href="#tabs-4">Bank Debit</a></li> <?php } ?> -->
                                <?php if($this->validation->payment_option_validation($type,'gift_voucher')) {?><li><a href="#tabs-5">Gift Voucher</a></li> <?php } ?>
                                <!-- <?php if($this->validation->payment_option_validation($type,'advance')) {?><li><a href="#tabs-6">Advance</a></li> <?php } ?> -->
                                <?php if($this->validation->payment_option_validation($type,'chq_issue')) {?><li><a href="#tabs-7">Issue Cheque</a></li> <?php } ?>
                                <?php if($this->validation->payment_option_validation($type,'privilege_card')) {?><li><a href="#tabs-8">Privilege Card</a></li> <?php } ?>
                                <?php if($this->validation->payment_option_validation($type,'chq_receive')) {?><li><a href="#tabs-9">Recieve Cheque</a></li> <?php } ?>
                                <?php if($this->validation->payment_option_validation($type,'installment')) {?><li><a href="#tabs-10">Installment</a></li> <?php } ?>
                            </ul>

                             <?php if($this->validation->payment_option_validation($type,'credit_card')) {?>
                            <div id="tabs-1">
                                <table style="width:100%" cellpadding="0" id="tgrid3">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width:80px;">Type</th>
                                            <th class="tb_head_th" style="width:80px;">Amount</th>
                                            <th class="tb_head_th" style="width:80px;">Number</th>
                                            <th class="tb_head_th" style="width:80px;">Bank</th>
                                            <th class="tb_head_th" style="width:80px;">Bank Name</th>
                                            <th class="tb_head_th" style="width:80px;">Month</th>
                                            <th class="tb_head_th" style="width:80px;">Rate%</th>
                                            <th class="tb_head_th" style="width:80px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            for($x=0; $x<10; $x++){
                                                echo "<tr>";
                                                echo "<td><input type='text' class='g_input_txt type1' id='type1_".$x."' name='type1_".$x."'  /></td>";
                                                echo "<td><input type='text' class='g_input_amo cc_amount'  id='amount1_".$x."' name='amount1_".$x."'/></td>";                
                                                echo "<td><input type='text' style='border: medium none;width: 100%;' maxlength='30' class='' id='no1_".$x."' name='no1_".$x."'/></td>"; 
                                                echo "<td>
                                                            <input type='text' class='g_input_txt bank1' id='bank1_".$x."' name='bank1_".$x."'/>
                                                            <input type='hidden' id='acc1_".$x."' name='acc1_".$x."'/>
                                                            <input type='hidden' id='merchant1_".$x."' name='merchant1_".$x."'/>
                                                      </td>"; 
                                                echo "<td><input type='text' class='g_input_txt g_col_fixed bank11' id='1bank1_".$x."' name='1bank1_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_num g_col_fixed' id='month1_".$x."' name='month1_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_amo g_col_fixed' id='rate1_".$x."' name='rate1_".$x."'/></td>";
                                                echo "<td><input type='text' class='g_input_amo g_col_fixed' id='amount_rate1_".$x."' name='amount_rate1_".$x."'/></td>";                
                                                echo "</tr>";
                                            }
                                        ?>                                          
                                    </tbody>
                                </table>
                            </div>
                             <?php } ?>

                            <?php if($this->validation->payment_option_validation($type,'crn')) {?>
                            <div id="tabs-2">
                                <table style="width:100%" cellpadding="0" id="tgrid4">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width:40px;">No</th>
                                            <th class="tb_head_th" style="width:125px;">Type</th>
                                            <th class="tb_head_th" style="width:125px;">Description</th>
                                            <th class="tb_head_th" style="width:100px;">Amount</th>
                                            <th class="tb_head_th" style="width:100px;">Balance</th>
                                            <th class="tb_head_th" style="width:100px;">Settle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                   
                                            for($x=0; $x<10; $x++){
                                                echo "<tr>";
                                                echo "<td><input type='hidden' name='cl_".$x."' id='cl_".$x."'/>
                                                          <input type='hidden' name='bc_".$x."' id='bc_".$x."'/>
                                                     <input type='text' class='g_input_txt' id='no2_".$x."' name='no2_".$x."' /></td>";
                                                echo "<td><input type='text' class='g_input_txt' id='type_".$x."' name='type_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_txt' id='des_".$x."' name='des_".$x."'/><input type='hidden' name='t_code_".$x."' id='t_code_".$x."'/></td>";               
                                                echo "<td><input type='text' class='g_input_amo cn_amount'  id='amount2_".$x."' name='amount2_".$x."'/></td>";   
                                                echo "<td><input type='text' class='g_input_amo'  id='balance2_".$x."' name='balance2_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_amo cn_settle'  id='settle2_".$x."' name='settle2_".$x."'/></td>";                  
                                                echo "</tr>";
                                            }
                                            echo "<tr style=background:#f2f2f2;>";
                                            echo "<td></td>";
                                            echo "<td></td>";
                                            echo "<td><b>Total</b></td>";
                                            echo "<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total' name='total'/></td>";
                                            echo "<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_bal' name='total_bal'/></td>";
                                            echo "<td></td>";

                                            echo "</tr>";
                                        ?>                                          
                                    </tbody>
                                </table>
                            </div><!-- tabs-2-->
                             <?php } ?>

                            <?php if($this->validation->payment_option_validation($type,'drn')) {?>
                            <div id="tabs-3">
                                <table style="width:100%" cellpadding="0" id="tgrid5">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width:40px;">No</th>
                                            <th class="tb_head_th" style="width:125px;">Type</th>
                                            <th class="tb_head_th" style="width:125px;">Description</th>
                                            <th class="tb_head_th" style="width:100px;">Amount</th>
                                            <th class="tb_head_th" style="width:100px;">Balance</th>
                                            <th class="tb_head_th" style="width:100px;">Settle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            for($x=0; $x<10; $x++){
                                                echo "<tr>";
                                                echo "<td>
                                                        <input type='hidden' name='cl3_".$x."' id='cl3_".$x."'/>
                                                        <input type='hidden' name='bc3_".$x."' id='bc3_".$x."'/>
                                                        <input type='text' class='g_input_txt' id='no3_".$x."' name='no3_".$x."' /></td>";
                                                echo "<td><input type='text' class='g_input_txt' id='type3_".$x."' name='type3_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_txt' id='des3_".$x."' name='des3_".$x."'/><input type='hidden' name='t_code3_".$x."' id='t_code3_".$x."'/></td>";              
                                                echo "<td><input type='text' class='g_input_amo dn_amount'  id='amount3_".$x."' name='amount3_".$x."'/></td>";   
                                                echo "<td><input type='text' class='g_input_amo'  id='balance3_".$x."' name='balance3_".$x."'/></td>"; 
                                                echo "<td><input type='text' class='g_input_amo dn_settle'  id='settle3_".$x."' name='settle3_".$x."'/></td>";                  
                                                echo "</tr>";
                                            }

                                            echo"<tr style=background:#f2f2f2;>";
                                            echo"<td></td>";
                                            echo"<td></td>";
                                            echo"<td><b>Total</b></td>";
                                            echo"<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_d' name='total_d' /></td>";
                                            echo"<td><input type='text' class='g_input_amo' style='background-color:#f2f2f2; font-weight:bold' id='total_d_bal' name='total_d_bal'/></td>";
                                            echo "<td></td>";
                                            echo"</tr>";
                                        ?>                                          
                                    </tbody>
                                </table>
                            </div><!-- tabs-3-->
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'bank_deposit')) {?>
                            <!-- <div id="tabs-4">
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
                            </div> --><!--end tabs- 4-->
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'gift_voucher')) {?>
                            <div id="tabs-5">
                                    <table style="width:100%" cellpadding="0" id="tgrid7">
                                        <thead>
                                            <tr>
                                                <th class="tb_head_th" style="width:80px;">Serial</th>
                                                <th class="tb_head_th" >Description</th>
                                                <th class="tb_head_th" style="width:80px;">Issue Date</th>
                                                <th class="tb_head_th" style="width:80px;">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                for($x=0; $x<10; $x++){
                                                    echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt gft' id='type5_".$x."' name='type5_".$x."' /></td>";
                                                    echo "<td>
                                                            <input type='text' class='g_input_txt gft' id='des5_".$x."' name='des5_".$x."' />
                                                            <input type='hidden' id='gcode5_".$x."' name='gcode5_".$x."' />
                                                          </td>";
                                                    echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='no5_".$x."' name='no5_".$x."'/></td>";                
                                                    echo "<td><input type='text' readonly='readonly' class='g_input_amo gv_amount'  id='amount5_".$x."' name='amount5_".$x."'/></td>";                
                                                    echo "</tr>";
                                                }
                                            ?>                                          
                                        </tbody>
                                    </table>
                            </div><!--end tabs-5-->
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'advance')) {?>
                            <!-- <div id="tabs-6">
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
                                                echo "<td><input type='text' class='g_input_amo a_settle'  id='cdate6_".$x."' name='cdate6_".$x."'/></td>";              
                                                echo "</tr>";
                                            }
                                        ?>                                          
                                    </tbody>
                                </table>
                            </div> --><!--end tabs-6-->
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'chq_issue')) {?>
                            <div id="tabs-7">
                                        <table style="width:100%" cellpadding="0" id="tgrid9">
                                        <thead>
                                            <tr>
                                                <th class="tb_head_th" style="width:80px;">Bank Acc</th>
                                                <th class="tb_head_th" >Bank Name</th>
                                                <th class="tb_head_th" style="width:80px;">Cheque No</th>
                                                <th class="tb_head_th" style="width:80px;">Amount</th>
                                                <th class="tb_head_th" style="width:80px;">CHQ Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                for($x=0; $x<10; $x++){
                                                    echo "<tr>";
                                                    echo "<td><input type='text' class='g_input_txt bank7' id='bank7_".$x."' name='bank7_".$x."' /></td>";
                                                    echo "<td><input type='text' class='g_input_txt'  id='des7_".$x."' name='des7_".$x."'/></td>";              
                                                    echo "<td><input type='text' class='g_input_num'  id='chqu7_".$x."' name='chqu7_".$x."'/></td>";
                                                    echo "<td><input type='text' class='g_input_amo ci_amount'  id='amount7_".$x."' name='amount7_".$x."'/></td>";                
                                                    echo "<td><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;'  id='cdate7_".$x."' name='cdate7_".$x."'/></td>";              
                                                    echo "</tr>";
                                                }
                                            ?>                                          
                                        </tbody>
                                    </table>
                            </div><!--end tabs-7-->
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'privilege_card')) {?>
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
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'chq_receive')) {?>

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
                             <?php } ?>

                             <?php if($this->validation->payment_option_validation($type,'installment')) {?>
                            <div id="tabs-10">
                                <table style="width:100%" border='1' cellpadding="0" id="installment_grid">
                                    <tbody>
                                        <tr><td>Total Amount</td><td align='right'><input type='text' class='g_input_amo' id='ttl_amount' name='ttl_amount' value="" title="" style='width:100px;' /></td></tr>
                                        <tr><td>Down Payment</td><td align='right'><input type='text' class='g_input_amo' id='down_payment' name='down_payment'  title="" style='width:100px;'/></td> </tr>             
                                        <tr><td>Rate Per Month</td><td align='right'><input type='text' class='g_input_amo' id='rate_per_month' name='rate_per_month' title="" style='width:100px;'/></td> </tr>               
                                        <tr><td>Period By Day</td><td align='right'><input type='text' class='g_input_num' id='period' name='period' style='width:100px;' title="" /></td></tr>
                                        <!-- <tr><td>End Date</td><td align='right'><input type='text' class='input_date_down_future'  style='border:none;height:17px;font-weight:normal;' id='end_date' name='end_date' style='width:100px;'/></td></tr>                -->
                                        <tr><td>No Of Installment</td><td align='right'><input type='text' class='g_input_num ' id='num_of_installment' name='num_of_installment' title="" style='width:100px;'/></td></tr>
                                        <tr><td colspan='2' align='right'><input type='button' value='Calculate' title='Calculate' id='installment_calc'/></td></tr>
                                    </tbody>
                                </table>
                               <table style="width:200px" border='0' cellpadding="0" id="installment_det">
                               </table>
                            </div><!--end tabs-10-->
                             <?php } ?>
                        </div><!--end tabs-->
                    </td>
                </tr>
                <tr>
                  <td style="text-align:right" colspan="2">
                        <span style="margin-right:35%;font-weight:bold">
                        Net Amount 
                        <input type="text" class='hid_value g_input_amo' id="opt_net_value" style='width:150px;'/>
                        Balance
                        <input type="text" class='hid_value g_input_amo' id="opt_balance" style='width:150px;'/>
                        </span>
                        <input type="hidden" id="code_" name="code_" title="0" />
                        <input type="button" id="btnExit2" title='Exit' />
                        <input type="button" id="btnSave2" title='OK' />
                      <!--   <input type="button" id="btnReset" title='Reset'>   -->
                        <input type="hidden" id="hidDue">  
                    </td>
                </tr>
            </table><!--tbl1-->
        </div>
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
            <input type="hidden" name="hid_installment" id="hid_installment" />
            <input type="hidden" name="hid_pc_type" id="hid_pc_type" />
            <input type="hidden" name="hid_priv_card" id="hid_priv_card" />
            <input type="hidden" name="hid_ins_period_by_days" id="hid_ins_period_by_days" />
            <input type="hidden" name="hid_ins_down_payment" id="hid_ins_down_payment" />
            <input type="hidden" name="hid_ins_rate_per_month" id="hid_ins_rate_per_month" />
            <input type="hidden" name="hid_num_of_installment" id="hid_num_of_installment" />
            <input type="hidden" name="install_pay" id="install_pay" />

    </div><!-- close payment option -->
    <div id="fade2" class="black_overlay"></div>
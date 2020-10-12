<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_quotation_sum.js"></script>

<h2 style="text-align: center;">Quotation</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_quotation_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Item</td>
                <td><input type="text" id="item" name="item"class="input_active" title=""/>
                       <input type="text" class="hid_value" id="item_id" title="" readonly="readonly" style="width: 300px;" />
                    </td>
                
                <!--
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            	</tr>
            	-->

            <tr>
                <td>From Date</td>
                    <td colspan="2">
                    <input type="text" id="fdate" name="fdate" class="input_date_down_future" title="<?php echo date('Y-m-d')?>"/>
                      
					To Date
                    <input type="text" id="tdate" name="tdate" class="input_date_down_future" title="<?php echo date('Y-m-d')?>"/>
                    </td>
            </tr>


            <tr>
                <td>Branch</td>
                    <td><?php echo $accNos; ?>
                        <input type="text" class="hid_value" title='' id="acc_id"  maxlength="255" style="width: 270px;">   
                    </td>
            </tr>


            <tr>
                <td>Store</td>
                    <td><?php echo $accNos; ?>
                        <input type="text" class="hid_value" title='' id="acc_id"  maxlength="255" style="width: 270px;">   
                    </td>
            </tr>


            <tr>
                <td colspan="4" style="text-align: center;">
                
                   
                		<table style="width: 100%;">
                		<thead>
                		<tr>
                			    <th class="tb_head_th" width="70">OPB</th>
                                <th class="tb_head_th" >Purchase</th>
                                <th class="tb_head_th" >Sales</th>
                                <th class="tb_head_th" >clas</th>
                		</tr>
                		</thead>

                		<tbody>
								<tr>
                            	   <td width='70' style='background-color: #f9f9ec;'><input type='hidden' name='h_1' id='h_1' title='0'/>
                                   <input type='text' class='g_input_txt fo' id='0_1' name='0_1'/></td>
                                   <td><input type='text' class='g_input_txt'  id='n_1' name='n_1' maxlength='150' style='width:100%; background-color: #f9f9ec;'/></td>;
                                   <td width='70'><input type='text' style='background-color: #f9f9ec;' class='g_input_txt' id='1_1' name='1_1'/></td>;
                                   <td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_num qun' id='2_1' name='2_1'/></td>;
                                </tr>
                        </tbody>
						</table>





                    <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" width="70">Date</th>
                                <th class="tb_head_th" >Type</th>
                                <th class="tb_head_th" >No</th>
                                <th class="tb_head_th" >In</th>
                                <th class="tb_head_th" >Out</th>
                                <th class="tb_head_th" >Balance</th>
                                                              
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td width='70' style='background-color: #f9f9ec;><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' /></td>";
                                        echo "<td ><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' style='width:100%; background-color: #f9f9ec;'/></td>";
                                        echo "<td width='70'><input type='text' style='background-color: #f9f9ec;' class='g_input_txt' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_num qun' id='2_".$x."' name='2_".$x."' s/></td>";
                                        echo "<td width='70' style='background-color: #f9f9ec;'><input type='text' class='g_input_amo dis' id='3_".$x."' name='3_".$x."' /></td>";
                                        echo "<td width='70'id='t_".$x."' name='t_".$x."' class='tf' style='text-align:right;'>&nbsp;</td>";

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
										<input type="button" id="btnReset" title="Cancel" />
										<input type="button" id="btnDelete" title="Delete" />
										<input type="button" id="btnPrint" title="Print" />
										<input type="button"  id="btnSave" title="Save" />
										
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






 </form>

        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='t_quotation_sum' title="t_quotation_sum" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='type' value='t_quotation_sum' title="t_quotation_sum" >
                 <input type="hidden" name='header' value='false' title="false" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
                 <input type="hidden" name='dt' value='' title="" id="dt" >
                 <input type="hidden" name='cus_id' value='' title="" id="cus_id" >
                 <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >
        
        </form>

















    
</div>


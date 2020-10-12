
<script type='text/javascript' src='<?=base_url()?>js/m_guarantor.js'></script>
<h2>Guarantor</h2>
<div>
<table width="100%" border="0">
    <tr>
        <td valign="top" class="content" style="width: 640px;">
            <div class="form" id="form">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_guarantor" >
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">General</a></li>
                        <li><a href="#tabs-2">Black List</a></li>
                        
                    </ul>
                    <div id="tabs-1">
                        <fieldset>
                            <legend>Personal Details</legend>
                            <table border="0" style="width:100%;">
                            
                            <tr>
                                <td>Code</td>
                                <td><input type="text" class="input_txt" title='' id="code" name='code' maxlength="10" style="width:100px;text-transform:uppercase;"></td>
								
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td colspan="3"><input type="text" class="input_txt" title=' ' id="name" name="name"  style="width:350px;" maxlength="100"/></td>
                            </tr><tr>
                                <td>Full Name</td>
                                <td colspan="3"><input type="text" class="input_txt" title=' ' id="full_name" name="full_name"  style="width:350px;" maxlength="100"/></td>
                            </tr><tr>
                                <td>Address 01</td>
                                <td colspan="3"><input type="text" class="input_txt" id="address1" title="" style="width: 200px;" name="address1"  maxlength="100"/></td>                       
                            </tr><tr>
                                <td>Address 02</td>
                                <td colspan="3"><input type="text" class="input_txt" id="address2" title="" style="width: 200px;"  name="address2" maxlength="100"/></td>                          
                            </tr><tr>
                                <td>Address 03</td>
                                <td colspan="3"><input type="text" class="input_txt" id="address3" title="" style="width: 200px;"  name="address3" maxlength="100"/></td>                      
                            </tr>
							
							
							<tr>
                                <td>TP No</td>
                                <td>
                                    <input type="text" class="input_txt" id="tp" title="" style="width: 120px;" maxlength="15" name="tp" />
								</td>
								<td>Mobile Number
								
                                    <input type="text" class="input_txt" id="mobile_number" title="" style="width: 120px;" maxlength="15" name="mobile"  />
                                    
                                </td>                        
                            </tr>
                            <tr>
                                <td>Fax No</td>
                                <td>
                                    <input type="text" class="input_txt" id="fax" title="" style="width: 120px;" maxlength="15" name="fax" />
                                </td>
                                <td></td>                        
                            </tr>
                             <tr>
                                <td>Email</td>
                                <td colspan="3"><input type ="text" class="input_txt" id="email" title="" style="width:200px" name="email"  maxlength="50"/></td>
                            </tr>
                            <tr>
                                <td>Relationship</td>
                                <td colspan="3"><input type="text" class="input_txt" title=' ' id="relation" name="relation"  style="width:350px;" maxlength="100"/></td>
                            </tr>
                            
                          	
							 <tr>
                                <td>Area </td>
								<td colspan="3"> <?php echo $area; ?>
									<input type="text" class="hid_value" id="area_id" title="" maxlength="255" style="width:280px;"/>
                                </td>
                            </tr>

                        </table>
                        </fieldset>

                        <fieldset style="margin-top:10px;">
                            <legend>Employment</legend>
                            <table border="0" style="width:100%;">
                                <tr>
                                    <td>Occupation</td>
                                    <td colspan="3"><input type="text" class="input_txt" title=' ' id="occupation" name="occupation"  style="width:350px;" maxlength="100"/></td>
                                </tr>

                                <tr>
                                    <td>Office Tel No</td>
                                    <td>
                                        <input type="text" class="input_txt" id="office_tp" title="" style="width: 120px;" maxlength="15" name="office_tp" />
                                    </td>
                                                          
                                </tr>

                                <tr>
                                <td>Office Fax No</td>
                                <td>
                                    <input type="text" class="input_txt" id="office_fax" title="" style="width: 120px;" maxlength="15" name="office_fax" />
                                </td>
                                                 
                                 </tr>

                                <tr>
                                    <td>EPF No</td>
                                    <td>
                                        <input type="text" class="input_txt" id="epf" title="" style="width: 120px;" maxlength="15" name="epf" />
                                    </td>
                                                       
                                </tr>

                                <tr>
                                    <td>Salary</td>
                                    <td>
                                        <input type="text" class="input_active_num" id="salary" title="" style="width: 120px;" maxlength="15" name="salary" />
                                    </td>
                                               
                                </tr>

                            </table>
                        </fieldset>

                    </div>
                    <div id="tabs-2">
                            <table style="width: 100%;" border="0" >
									
										
									<tr>
										<td colspan="3">
											<fieldset>
												<legend>Black List Details</legend>
												<table width="100%" border="0">
    												<tr>
        												<td align="left" style="width:100px;">Black List</td>
                                                        <td><input type="checkbox" name="is_black_list" title="1" id="is_black_list"/></td>
    												</tr>
                                                    <tr>
                                                        <td>Date</td>
                                                        <td><input type="text" class="input_date_down_old" title="<?php echo date("Y-m-d"); ?>" id="date" name="date" /></td>  
                                                    </tr>
                                                   
                                                    <tr>
                                                        <td>Reason</td>
                                                        <td><textarea class="input_txt" name="reason" id="reason" name="reason" title=""></textarea></td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td>Officer</td>
                                                        <td><input type="text" class="input_txt" name="officer" id="officer" title="" /></td>
                                                        
                                                    </tr>
                                                </table>
											</fieldset>	
										</td>
									
									</tr>
									
										
                             </table>
                    </div>
                    
                    
                <div style="text-align: right; padding: 7px;">
                    <input type="hidden" id="code_" name="code_" />
                    <input type="button" id="btnExit" title="Exit" />
                    <input type="button" id="btnPrint" title='Print' value="Print" />
                    <input type="button" id="btnSave" title='Save <F8>' />
                    
                    <input type="button" id="btnReset" title='Reset'>
                </div>
                </div>
                </form>
            </div>
        </td>
        <td class="content" valign="top" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
            <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
            </table> 
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>

<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='m_customer' title="m_customer" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >  
                 <input type="hidden" name='code_find' value='' title="" id="code_find" >                
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name='dt' value='' title="" id="dt" > 
        
</form>
</div>

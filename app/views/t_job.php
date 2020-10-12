<?php if($this->user_permissions->is_view('t_job')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type="text/javascript" src="<?=base_url()?>js/t_job.js"></script>

    <h2 style="text-align: center;">Service Job</h2>
    <div class="dframe" id="mframe" style="width:750px;">

        <form method="post" action="<?=base_url()?>index.php/main/save/t_job" id="form_">
            <table style="width:100%;"  border="0" cellpadding="1">
                <tr>
                    <td colspan="3" rowspan="4">
                        <fieldset id="f_set1">
                            <legend><b>Invoice</b></legend>
                            <table border="0" style="width:100%;">
                                <tr>
                                    <td style="width:100px;">Inv Type</td>
                                    <td style="width:150px;">
                                        <select id="inv_type" name="inv_type" style="width:150px;">
                                            <option>---</option>
                                            <option value="1">CASH SALES</option>
                                            <option value="2">CREDIT SALES</option>
                                        </select>
                                        <input type="hidden" class="input_text" style="width:150px" name="inv_type_h" id="inv_type_h" title="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Inv No</td>
                                    <td><input type="text" class="input_txt" style="width:150px" name="inv_no" id="inv_no" title="" /></td>
                                </tr>
                                <tr>
                                    <td>Inv Date</td>
                                    <td><input type="text" class="input_txt" readonly="readonly" style="width:150px" name="inv_date" id="inv_date" title="" /></td>
                                </tr>
                            </table>
                        </fieldset> 
                    </td>
                    <td colspan="3" rowspan="4">
                        <fieldset>
                            <legend><B>Type</B></legend>
                            <table border="0" style="width:100%;">
                                <tr>
                                    <td style="width:100px;">Internal</td>
                                    <td style="width:150px; height:25px;"><input type="radio" class='types' value="0" title="0" name="type" id="in" checked></td>
                                </tr>
                                <tr>
                                    <td>External</td>
                                    <td style="height:25px;"><input type="radio" class='types' value="1" title="1" name="type" id="ex"></td>
                                </tr>
                                <tr>
                                    <td style="height:25px;">&nbsp;</td>
                                    <td>&nbsp;</td> 
                                </tr>
                            </table> 
                        </fieldset>
                    </td>
                    <td style="height:25px; width:100px;">No</td>
                    <td>
                        <input type="text" class="input_active_num" style="width:100%" name="id" id="id" title="<?=$max_no?>"  />
                        <input type="hidden" id="hid" name="hid" title="0" />
                    </td>
                </tr>
                <tr>
                   <td style="height:25px;">Ref No</td>
                   <td><input type="text" class="input_active_num" name="ref_no" style="width:100%" id="ref_no" title="" /></td>
               </tr>
               <tr> 
                <td >Date</td>
                <td><?php if($this->user_permissions->is_back_date('t_job')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                <?php } else { ?>
                    <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?> </td>
                </tr>
                <tr>
                   <td>CRN No</td>
                   <td><input type="text" class="input_active_num" name="crn_no" id="crn_no" title="<?=$crn_no?>" style="width:100px;"/>
                       <input type="hidden" class="input_active_num" name="crn_no_hid" id="crn_no_hid" value='' style="width:150px;"/></td>
                   </tr>
                   <tr>
              <!--   <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td> -->
            </tr>
            <tr>
                <td style="width:120px;">Customer</td>
                <td style="width:150px;"><input type="text" class="input_txt" name="cus_id" style="width:100%" id="cus_id" title="" readonly="readonly"/></td>
                <td colspan="4"><input type="text" class="hid_value" name="customer" style="width:300px" id="customer" title="" /></td> <td><input type="button" id="customer_create" name="customer_create" title="..." disabled></td>
               <!--  <td></td>
                <td></td> -->
            </tr>
            <tr>
                <td style="width:120px;">Address</td>
                <td colspan="5" ><input type="text" class="hid_value" name="address" style="width:455px;" id="address" title="" readonly="readonly" /></td>
               <!--  <td>&nbsp;</td>
                <td>&nbsp;</td> -->
            </tr>
            <tr>
                <td colspan="8">
                    <fieldset>
                        <legend><b>Item</b></legend>
                        <table border="0" style="width:100%;">
                            <tr>
                                <td style="width:110px;">Item</td>
                                <td colspan="5" style="width:150px;"><input type="text" class="input_txt" name="item_id" style="width:149px" id="item_id" title="" readonly="readonly"/>
                                    <input type="text" class="hid_value" name="item" style="width:334px;" id="item" title="" />
                                    <input type="text" class="input_txt" name="item_des" style="width:450px;display:none;" id="item_des" title=""  /></td>
                                    <td>&nbsp;</td>
                                    
                                </tr>
                                <tr>
                                    <td>Brand</td>
                                    <td colspan="5"><input type="text" class="input_txt" name="brand_id" style="width:149px" id="brand_id" title="" readonly="readonly"/>
                                        <input type="text" class="hid_value" name="brand" style="width:334px;" id="brand" title="" />
                                        <input type="text" class="input_txt" name="brand_des" style="width:450px;display:none;" id="brand_des" title=""  /></td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Model</td>
                                        <td style="width:150px;"><input type="text" class="input_txt" name="model" style="width:100%;text-transform:uppercase;" id="model" title=""/></td>
                                        <td colspan="4">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Serial No</td>
                                        <td style="width:150px;"><input type="text" class="input_txt" name="serial" style="width:100%" id="serial" title="" /></td>
                                        <td colspan="4">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td align="right"><input type="checkbox" class="input_txt" name="gur_crd" id="gur_crd" style="width:5px;" /></td>
                                        <td align="left">Guranteed Card</td>
                                        <td colspan="4">&nbsp;</td>
                                        
                                        <td>&nbsp;</td> 
                                    </tr>
                                    <tr>
                                        <td>Guranteed Card No</td>
                                        <td><input type="text" class="hid_value" name="gur_no" style="width:100%" id="gur_no" title="" /></td>
                                        
                                        <td>Start Date</td><td><input type="text" class="hid_value" name="start_date" style="width:100%;" id="start_date" title="" /></td>
                                        <td>End Date</td><td><input type="text" class="hid_value" name="end_date" style="width:100%;"  id="end_date" title="" /> </td>
                                        
                                        <td colspan="4">&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Fault</td>
                                        <td colspan="5"><textarea style="width:100%; height:80px;resize: none;" name="fault" id="fault" class="input_txt"></textarea></td>
                                        <td style="width:100px;">&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Parts Collected</td>
                                        <td colspan="5"><textarea style="width:100%; height:80px;resize: none;" name="parts" id="parts" class="input_txt"></textarea></td>
                                        <td style="width:100px;">&nbsp;</td>
                                        
                                    </tr>
                                    <tr>
                                        <td>Advance Amount</td>
                                        <td><input type="text" class=" input_txt g_input_amo " name="advance" style="width:100%;text-align:right;" id="advance" title="0" /></td>
                                        <td colspan="4">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <fieldset>
                                <legend><b>Supplier</b></legend>
                                <table border="0" style="width:100%;">
                                    <tr>
                                        <td style="width:110px;">Supplier</td>
                                        <td style="width:150px;"><input type="text" class="input_txt" name="sup_id" style="width:100%" id="sup_id" title="" readonly="readonly"/></td>
                                        <td colspan="4"><input type="text" class="hid_value" name="supplier" style="width:334px" id="supplier" title="" /></td>
                                        <td style="width:100px;">&nbsp;</td>
                                        
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <div style="text-align: left; padding-top: 7px;">
                                <input type="button" id="btnExit" title="Exit" />
                                <input type="button" id="btnReset" title="Reset" />
                                <?php if($this->user_permissions->is_delete('t_job')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
                                <?php if($this->user_permissions->is_re_print('t_job')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
                                <?php if($this->user_permissions->is_add('t_job')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>                                                     
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
            <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

                <input type="hidden" name='by' value='t_job' title='t_job' class="report">
                <input type="hidden" name='orientation' value="L" title="L">
                <input type="hidden" name='header' value='false' title='false'>
                <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                <input type="hidden" name='dt' value='' title="" id="dt" >
                <input type="hidden" name='type' value='service' title="service" >
                <input type="hidden" name='page' value='A5' title="A5" >
                <input type="hidden" name='org_print' value='' title="" id="org_print">
                <input type="hidden" name='qno' value='' title="" id="qno" >

            </form>
        </div>
    <?php } ?>

<?php if($this->user_permissions->is_view('t_loyalty_card_request')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type='text/javascript' src='<?=base_url()?>js/t_loyalty_card_request.js'></script>

    <h2>Loyalty Card Request</h2>
    <div class="dframe" id="mframe" style="width:700px;">

        <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_loyalty_card_request" >
            <table style="width:100%;" id="tbl1" border="0">
                <tr>
                    <td style="width:100px;">No</td>
                    <td style="width:100px;"><input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" style="width:100%"/>
                        <input type="hidden" class="input_active_num" name="hid" id="hid" title="0" style="width:100%"/></td>
                    <td>&nbsp;</td>
                </tr> 
                <tr>
                    <td style="width:100px;">Date</td>
                    <td style="width:100px;">
                        <?php if($this->user_permissions->is_back_date('t_loyalty_card_request')){ ?>
                            <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" style="width:100%;text-align: right;" title="<?=date('Y-m-d')?>" />
                        <?php } else { ?>
                            <input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" style="width:100%" title="<?=date('Y-m-d')?>" />
                        <?php } ?>  
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:100px;">Customer Code</td>
                    <td style="width:100px;"><input type="text" class="input_txt" id="customer_id" name="customer_id" title="" style="width: 100%;" /></td>
                    <td width="200"><input type="text" name="customer_des" class="hid_value" id="customer_des" style="width:100%;" maxlength="10"/></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td colspan="2"><input type="text" name="address" class="hid_value" id="address" style="width:100%;" /></td>
                </tr>

                <tr>
                    <td>TP</td>
                    <td><input type="text" class="hid_value" id="tp" name="tp" style="width:100%;" maxlength="15"/></td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td colspan="2"><input type="text" name="email" class="hid_value" id="email" style="width:100%" /></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td>Comments</td>
                    <td colspan="2"><input type="text" class="input_txt" id="note" name="note" title="" style="width: 100%;" /></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>

            </table>



        </td>

    </tr>
    <tr>
        <td style="text-align:left" colspan="3">
            <input type="hidden" id="code_" name="code_" title="0" />
            <input type="button" id="btnExit" title='Exit' />
            <input type="button" id="btnReset" title='Reset'>
            <?php if($this->user_permissions->is_delete('t_loyalty_card_request')){ ?><input type="button" id="btnDelete" title='Delete' /><?php } ?>
            <?php if($this->user_permissions->is_add('t_loyalty_card_request')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>

        </td>
    </tr>
</table><!--tbl2-->
</form><!--form_-->


<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

    <input type="hidden" name='by' value='t_loyalty_card_request' title="t_loyalty_card_request" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='type' value='19' title="19" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
    <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
    <input type="hidden" name="sales_type" id="sales_type" value="" title="" >
    <input type="hidden" name='dt' value='' title="" id="dt" >
    <input type="hidden" name='card_no1'  id="card_no1" >
    <input type="hidden" name='salesp_id' value='' title="" id="salesp_id" >

</form>
</div>
<?php } ?>

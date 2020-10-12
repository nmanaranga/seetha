<?php if($this->user_permissions->is_view('return_reason')){ ?>
    <script type='text/javascript' src='<?=base_url()?>js/return_reason.js'></script>
    <h2>Reason Setup</h2>

    <table width="100%">
        <tr>
            <td valign="top" class="content" style="width: 500px;">
                <div class="form" id="form" style="width: 500px;">
                    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/return_reason" >
                        <table>

                            <tr>
                                <td width='59'>Type</td>
                                <td width='145'>
                                    <input type="radio" class="rt" name="r_type" id="purchase_ret" title="1"  value="1" checked="true" />Purchase Return
                                </td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="sales_ret" title="2" value="2"/>Sales Return
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="cheque_ret" title="3"  value="3"/>Cheque Return
                                </td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="bank" title="4"  value="4"/>Bank Chargers
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="sup_event" title="5"  value="5"/>Supplier Event
                                </td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="cus_event" title="6"  value="6"/>Customer Event
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="radio" class="rt" name="r_type" id="card_cat" title="7"  value="7"/>Card Category
                                </td>
                            </tr>
                        </table>    
                        <table>
                            <tr>
                                <td>Code</td>
                                <td>
                                    <input type="text" class="hid_value" readonly name="code" id="code" title="<?=$max_no ?>" style="width: 150px; text-transform:uppercase;"maxlength="4">
                                    <input type="hidden" name="hid_max" id="hid_max">
                                </td>

                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><input type="text" class="input_txt" name="description" title='' id="des" style="width: 350px;" maxlength="50"/></td>
                            </tr>


                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <input type="hidden" id="code_" name="code_" value=""/>
                                    <input type="button" id="btnExit" title='Exit' />
                                    <?php if($this->user_permissions->is_add('return_reason')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>


                                    <input type="button" id="btnReset" title='Reset'>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>
            <td valign="top" class="content" style="width: 600px;">
                <div class="form" id="form" style="width:600px;" >
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
    <?php } ?>
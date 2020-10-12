<?php if($this->user_permissions->is_view('t_repair_dash_bord')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/t_repair_dash_bord.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2>Repair Dash Board</h2>
<div class="dframe" id="mframe" style=" margin-top:10px; " >

    <form method="post" action="<?=base_url()?>index.php/main/save/t_repair_dash_bord" id="form_">
        <table style="width: 100%" border="0">
            <tr>
              <td rowspan="3" colspan="2">
                <table>
                        <tr>
                            <td>No</td>
                            <td><input type="text" id="no" name="no" title="" class="input_txt"/></td>
                        </tr>

                        <tr>
                            <td>Date</td>
                            <td><input type="text" id="date" name="date" title="" class="input_date_down_future"/></td>
                        </tr>

                       <tr>
                            <td>Ref No</td>
                            <td><input type="text" id="ref_no" name="ref_no" title=" " class="input_txt"/></td>
                        </tr>
                </table>
              </td>
             
              <td>&nbsp;</td>
              <td rowspan="3" colspan="2">
                <fieldset>
                    <legend>Invoice</legend>
                    <table>
                        <tr>
                            <td>INV Type</td>
                            <td><select></select></td>
                        </tr>

                        <tr>
                            <td>INV No</td>
                            <td><input type="text" id="inv_no" name="inv_no" title=" " class="input_txt"/></td>
                        </tr>

                         <tr>
                            <td>INV Date</td>
                            <td><input type="text" id="inv_date" name="inv_date" title="" class="input_date_down_future"/></td>
                        </tr>
                    </table>
                </fieldset>
              </td>
              
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>


        <table style="width: 100%" border="0">
            <tr>
                <td>Customer</td>
               <td colspan="5"><select></select>
                <input type="text" id="customer" name="customer" title="" style="width:350px;" class="hid_value"/></td>
            </tr>
            <tr>
                <td>Address</td>
                <td colspan="6"><input type="text" id="address" name="address" title="" style="width:505px;" class="input_txt"/></td>
            </tr>

            <tr>
                <td colspan="7">
                    <fieldset><legend>Item</legend>
                        <table>
                            <tr>
                                <td>Item</td>
                                <td><select></select></td>
                                <td colspan="5"><input type="text" id="item" name="item" title="" style="width:350px;" class="hid_value"/></td>
                            </tr>
                            <tr>
                                <td>Brand</td>
                                <td><select></select></td>
                                <td colspan="5"><input type="text" id="brand" name="brand" title="" style="width:350px;" class="hid_value"/></td>
                            </tr>
                            <tr>
                                <td>Model</td>
                                <td><input type="text" id="model" name="model" title="" class="input_txt"/></td>
                                <td colspan="5">
                                    <span>Serial No</span>
                                    <input type="text" id="serial" name="serial" title="" style="width:150px;" class="input_txt"/>
                                    <input type="checkbox" id="chck_serial" name="chck_serial"/>
                                    <span>Guranteed Card<span>
                                    <span style="margin-left:15px;">Guranteed Card No</span>
                                    <input type="text" id="gcn" name="gcn" title="" style="width:150px;" class="input_txt"/>

                                </td>
                            </tr>   

                            <tr>
                                <td colspan="7">&nbsp;</td>
                            </tr>

                             <tr>
                                <td colspan="7">&nbsp;</td>
                            </tr>

                            <tr>
                                <td>Fault</td>
                                <td colspan="6"><input type="text" id="fault" name="fault" title="" style="width:450px;" class="input_txt"/></td>
                            </tr>
                        </table>


                    </fieldset>

                </td>
            </tr>

            <tr>
                <td colspan="6">
                    <fieldset>
                        <legend>Supplier to Supplier</legend>
                        <table border="0">
                                <tr>
                                    <td>Brand</td>
                                    <td><select></select></td>
                                    <td colspan="3"><input type="text" id="brand" name="brand" style="width:450px;" class="hid_value"/></td>

                                </tr>

                                <tr>
                                <td>No</td>
                                <td><input type="text" id="sup_no" name="sup_no" style="width:150px;" class="input_txt"/></td>
                                <td colspan="2">Date
                                    <input type="text" id="sup_date" name="sup_date" class="input_date_down_future"/>
                                </td>
                                
                                </tr>


                        </table>
                    </fieldset>

                </td>

            </tr>



            <tr>
                <td colspan="6">
                    <fieldset>
                        <legend>Reject</legend>
                        <table border="0">
                                <tr>
                                    <td>Reason</td>
                                   
                                    <td colspan="4"><input type="text" id="reason" name="reason" style="width:450px;" class="input_txt" title=""/></td>

                                </tr>

                                <tr>
                                <td>No</td>
                                <td><input type="text" id="reject_no" name="reject_no" style="width:150px;" class="input_txt"/></td>
                                <td colspan="2">Date
                                    <input type="text" id="reject_date" name="reject_date" class="input_date_down_future"/>
                                </td>
                                
                                </tr>


                        </table>
                    </fieldset>

                </td>

            </tr>

              <tr>
                <td colspan="6">
                    <fieldset>
                        <legend>Recieve From Supplier</legend>
                        <table border="0">
                                <tr>
                                    <td>NO</td>
                                    <td><input type="text" id="rec_supplier" name="rec_supplier" style="width:150px;" class="input_txt" title=""/></td>
                                    <td>Date</td>
                                    <td><input type="text" id="rec_supplier_date" name="rec_supplier_date" class="input_date_down_future"/></td>
                                    <td>Amount</td>
                                    <td><input type="text" id="rec_amount" name="rec_amount" style="width:150px;" class="input_txt" title=""/></td>
                                </tr>
                        </table>
                    </fieldset>

                </td>

            </tr>

            <tr>
                <td colspan="6">
                    <fieldset>
                        <legend>Issue to Customer</legend>
                        <table border="0">
                                <tr>
                                    <td>NO</td>
                                    <td><input type="text" id="issue_cus" name="issue_cus" style="width:150px;" class="input_txt" title=""/></td>
                                    <td>Date</td>
                                    <td><input type="text" id="issue_cus_date" name="issue_cus_date" class="input_date_down_future"/></td>
                                    <td>Amount</td>
                                    <td><input type="text" id="rec_amount" name="rec_amount" style="width:150px;" class="input_txt" title=""/></td>
                                </tr>
                        </table>
                    </fieldset>

                </td>

            </tr>

            <tr>
                <td colspan="6">
                    <input type="button" id="btnExit" title="Exit"/>
                    <input type="button" id="btnReset" title="Reset"/>
                </td>
            </tr>



        </table>
    </form>

</div>
<?php } ?>
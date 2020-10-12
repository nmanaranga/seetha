    <script type='text/javascript' src='<?=base_url()?>js/g_t_serial_out.js'></script>
    <!-- <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/" > -->
    <input type="hidden" id="form_id" title="<?=base_url()?>index.php/main/save/"/>
   
<div id="light" class="white_content2">
<div style="width:940px;height:30px;background:#69F;padding:5px;">
    <h2>Add Serials</h2>
</div>
<div class="dframe" style="width:940px;margin:0;padding:0;">
<table style="width:950px;" id="tbl1" border="0">
    <tr>
        <td valign="top" class="content" style="width:100%">
            <!-- <form id="form1_" method="post" action="<?=base_url()?>index.php/main/save/t_serial_movement" > -->
                    <table style="width:100%;" id="tbl2" border="0">
                        <tr>
                            <td>Type</td>
                            <td colspan="4"><input type="text" id="type_seri" name="type_seri" class="hid_value" style="width:150px;" value="1" readonly="readonly"/>
                            No  
                            <input type="text" id="no" name="no" class="hid_value" style="width:150px;" readonly="readonly" />  
                            </td>
                        </tr>
                        
                        <tr>
                            <td>Item</td>
                            <td colspan="4"><input type="text" id="item_code" name="item_code" style="width:150px;" class="hid_value"/>
                            <input type="text" id="item" name="item" class="hid_value" style="width:355px; margin-left:17px;"  />     
                            </td>
                        </tr>
                        <tr>
                            <td>QTY</td>
                            <td>
                                <input type="text" id="qty" name="qty" class="hid_value" style="width:150px;" readonly="readonly"/>
                            </td>

                        </tr>
                        <tr><td colspan='5'>
                            <!-- <hr style='width:100%;height:5px;background:#ccc;border:#ccc;'/> -->
                        </td></tr>

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

                                <div id="serch_pop44" style="height:200px;overflow:scroll;">
                                    <input type="text" id="pop_search44" title=""  style="width: 100%;" /><br />
                                    <div id="sr44"></div>
                                    <div style="text-align: right; padding-top: 7px;"></div>
                                </div>
                              
                            </td>
                        </tr>
                    
                        <tr>
                            <td style="text-align:right" colspan="5">
                               
                                <input type="button" id="btnExit1" title='Exit' /> 
                                <input type="button" id="btnSave1" title='Save <F8>' />
                                                      
                            </td>
                        </tr>
                    </table>
                <!-- </form> -->
            
      </td>
            
    </tr>
</table>
</div>

</div>
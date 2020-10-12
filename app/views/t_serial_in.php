    <script type='text/javascript' src='<?=base_url()?>js/t_serial_in.js'></script>
    <!-- <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/" > -->
    <input type="hidden" id="form_id" title="<?=base_url()?>index.php/main/save/"/>
<div id="light" class="white_content3">

<div style="width:650px;height:30px;background:#69f;padding:5px;">

<h2>Add Serials</h2>

</div>
<div class="dframe" style="width:645px;margin:0;padding:0;">
<table style="width:645px;" id="tbl1" border="0">
    <tr>
        <td valign="top" class="content" style="width:100%">
            
                <!--<form id="form1_" method="post" action="<?=base_url()?>index.php/main/save/t_serial_movement" >-->
                    <table style="width:100%;" id="tbl2" border="0">
                        <tr>
                            <td>Type</td>
                            <td colspan="4"><input type="text" id="type" name="type" class="hid_value" style="width:150px;" value="1" readonly="readonly"/></td>
                            
                        </tr>

                        <tr>
                            <td>No</td>
                            <td colspan="4"><input type="text" id="no" name="no" class="hid_value" style="width:150px;" readonly="readonly" />
                           
                            QTY
                            <input type="text" id="qty" name="qty" class="hid_value qtys" style="width:150px;" readonly="readonly"/></td>
                        </tr>
                        
                        <tr>
                            <td>Item</td>
                            <td colspan="4"><input type="text" id="item_code" name="item_code" style="width:150px;" class="hid_value"/>
                            <input type="text" id="item" name="item" class="hid_value" style="width:355px;"  />     
                            </td>
                            
                        </tr>


                        <tr>
                        </tr>
                    </table>
<br>
                    <div id="tabs" style="width:625px;">
                            <ul>
                                <li><a href="#tabs-1" >Serial</a></li>
                                <li><a href="#tabs-2" >Serial Genarate</a></li>      
                            </ul>
                   

                    <div id="tabs-1">

                         <!-- <td colspan="2" "> -->
                         <div style="width:600px;">
                                <fieldset >
                                    <legend>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Serials &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                        Other No 1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                        Other No 2
                                    </legend>
                                    <div style="height:200px;overflow:scroll;">
                                    <table cellpadding="0" border="0">
                                            <tbody id="set_serial" style=''>
                                                                               
                                            </tbody>
                                    </table>
                                  </div>
                               
                                </fieldset>
                            </div>
                             <table cellpadding="0" border="0">
                                <tr>
                                <td style="text-align:left; padding-left:495px;" colspan="5">
                               
                                <input type="button" class="btnExit1" id="btnExit1" title='Exit' value='Exit' />
                                <input type="button" id="btnSave1" title='Save <F8>' value='Save'/>
                                                      
                                </td>
                                </tr>
                                </table>

                    </div>

                    <div id="tabs-2">


                        <div style="width:600px;">
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
                                                            <input type="button" value="Generate" title="Generate" id="gen"/>
                                                            <input type="button" value="Clear" title="Clear" id="clear"/>
                                                            <input type="button" value="Add" title="Add" id="add"/>
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
                            </div>




                    </div>

 </div>



                            <!-- <td>&nbsp;</td>
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
                                                            <input type="button" value="Generate" title="Generate" id="gen"/>
                                                            <input type="button" value="Clear" title="Clear" id="clear"/>
                                                            <input type="button" value="Add" title="Add" id="add"/>
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
                            </td> -->
                        </tr>
                    
                       <!--  <tr>
                            <td style="text-align:left; padding-left:537px;" colspan="5">
                               
                                <input type="button" class="btnExit1" id="btnExit1" title='Exit' value='Exit' />
                                <input type="button" id="btnSave1" title='Save <F8>' value='Save'/>
                                                      
                            </td>
                        </tr> -->
                    </table><!--tbl2-->
                <!--</form>form_-->
            
      </td>
            
    </tr>
</table><!--tbl1-->
</div>

</div>
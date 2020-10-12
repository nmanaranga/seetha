<?php if($this->user_permissions->is_view('m_branch')&&
$this->utility->is_developer()){ ?>
    <script type='text/javascript' src='<?=base_url()?>js/m_branch.js'></script>
    <h2>Branches</h2>

    <table width="100%">
        <tr>
            <td valign="top" class="content" style="width: 600px;">
                <div class="form" id="form" style="width: 700px;">
                    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_branch" >
                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1">General</a></li>
                                <li><a href="#tabs-2">Other</a></li>
                                <li><a href="#tabs-3" >Sales</a></li>
                                <li style="display:none;"><a href="#tabs-4">Opening HP</a></li>    
                                <li><a href="#tabs-5" >Customer Settings</a></li>                    
                            </ul>

                            <div id="tabs-1"> <!-- Tab 1-->
                                <fieldset>
                                    <legend>General</legend>
                                    <table border="0" style="width:100%;">
                                        <tr>
                                            <td>Cluster</td>
                                            <td><?php echo $cluster; ?>
                                            <input type="text" class="hid_value" title='' id="cluster_id"  maxlength="255" style="width: 270px;">   
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Code</td>
                                        <td><input type="text" class="input_txt" title='' id="bc" style="text-transform:uppercase;" name="bc" maxlength="3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>
                                            <input type="text" class="input_txt" title='' id="name" name="name"  style="width:375px;" maxlength="255"/></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td><input type="text" class="input_txt" id="address" title="" name="address" style="width: 375px;"  maxlength="255"/></td>                       
                                        </tr>
                                        <tr>
                                            <td>TP </td>
                                            <td >
                                                <span>
                                                    <input type="text" class="input_txt" id="tp" title="" style="width:120px;" maxlength="15" name="tp" />
                                                </span>
                                                <span style="margin-left:110px;">   
                                                    Fax

                                                    <input type="text" class="input_txt" id="fax" title="" style="width:120px;" maxlength="15" name="fax" />
                                                </span>
                                            </td>                        
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><input type="text" class="input_txt" title='' id="email" name="email"  style="width:375px;"/></td>
                                        </tr>

                                    </table>
                                </fieldset>
                            </div>

                            <div id="tabs-2"> <!-- Tab 2-->

                                <table border="0" style="width:100%;">

                                   <tr>
                                    <td width="166">Account</td>
                                    <td>
                                        <input type="text" class="input_txt ld" title="" id="current_acc" name="current_acc" data='r_unit'/>                  
                                        <input type="text" class="hid_value" title='' id="acc_dtls"  name="acc_dtls" maxlength="255" style="width: 194px;">   
                                    </td>
                                </tr>
                            </table>
                            <fieldset>
                               <table border="0" style="width:100%;">
                                   <legend>Cash Sales</legend>

                                   <tr>
                                    <td>Customer Sale Limit</td>
                                    <td><input type="text" name="cash_customer_limit" id="cash_customer_limit" class="input_active g_input_amo" /></td>
                                </tr>

                                <tr>
                                    <td>Default Customer</td>
                                    <td>
                                        <input type="text" name="def_cash_customer" id="def_cash_customer" class="input_txt"/>
                                        <input type="text" name="def_customer_des" id="def_customer_des" class="hid_value" style="width:194px;"/>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <br/>
                        <fieldset>
                           <table border="0" style="width:100%;">
                               <legend>Default Sales Value</legend>
                               <tr>
                                <td style="width:100px;">Multi Stores</td>
                                <td width="20"> <input type='checkbox' name='multi_store' id='multi_store'  title='1' /></td>
                                
                            </tr>  
                            <tr>
                                <td width="130">Default Store</td>
                                <td> </td>
                                <td>
                                    <input type='text' name='def_store' id='def_store' class='input_txt'/>
                                    <input type='text' id='desc_def_store' class='hid_value' maxlength="180" style="width: 199px;"/>
                                    <input type="button" id="def_store_create" title="..." value="...">     
                                </td>

                            </tr>
                            <tr> 
                                <td>Purchase Store</td>
                                <td> </td>
                                <td >
                                    <!-- <input type='checkbox' style="visibility:hidden;"/> -->
                                    <input type='text' name='pur_store' id='pur_store' class='input_txt'/>
                                    <input type='text' id='desc_pur_store' class='hid_value' maxlength="180" style="width: 199px;"/>
                                </td>
                            </tr>
                            <tr>       
                                <td>Sales Store</td>
                                <td> </td>
                                <td >
                                    <!-- <input type='checkbox' style="visibility:hidden;"/> -->
                                    <input type='text' name='sales_store' id='sales_store' class='input_txt'/>
                                    <input type='text' id='desc_sales_store' class='hid_value' maxlength="180" style="width: 199px;"/>
                                </td>
                            </tr>
                            <tr>       
                                <td>Sales Order Store</td>
                                <td> </td>
                                <td >
                                    <!-- <input type='checkbox' style="visibility:hidden;"/> -->
                                    <input type='text' name='sales_order_store' id='sales_order_store' class='input_txt'/>
                                </td>
                            </tr>
                            <tr>   
                               <td colspan="4`"><hr/></td>
                           </tr> 
                           <tr>
                            <td>Sales Category</td>
                            <td> <input type='checkbox' name='is_sales_cat' id='is_sales_cat' title='1'/> </td>
                            <td>
                                <input type="text" class="input_txt ld" title="" id="def_sales_category" name="def_sales_category" data='r_unit'/>                
                                <input type="text" class="hid_value" title='' id="category_id"  maxlength="180" style="width: 199px;">   
                                <input type="button" id="sales_cat_create" title="..." value="...">
                            </td>
                        </tr>
                        <tr>
                            <td>Group</td>
                            <td><input type='checkbox' name='is_sales_group' id='is_sales_group' title='1'/> </td>
                            <td>                              
                                <input type="text" class="input_txt ld" title="" id="def_sales_group" name="def_sales_group" data='r_unit'/>                
                                <input type="text" class="hid_value" title='' id="group_dtls"  maxlength="180" style="width: 199px;">   
                                <input type="button" id="sales_group_create" title="..." value="...">
                            </td>
                        </tr>
                        <tr>
                            <td>Opening Balance Date</td>
                            <td> </td>
                            <td>
                               <input type="text"  class="input_date_down_future" id="open_bal_date" name="open_bal_date" title="<?=date('Y-m-d')?>" style="width: 150px;" />
                           </td>
                       </tr>
                       <tr>
                        <td>Cash In Hand Float</td>
                        <td> </td>
                        <td>
                           <input type="text"  class="input_active g_input_amo" id="cash_float" name="cash_float" title="0.00" style="width: 150px;" />
                       </td>
                   </tr>
               </table>
           </fieldset>            
       </div>
       <div id="tabs-3">
        <table>
            <tr>
                <td>Use Sales Officer</td>
                <td>
                    <input type='checkbox' name='is_sales_man' id='is_sales_man' title='1' />
                    <input type='text' name='sales_man' id='sales_man' class='input_txt'/>
                    <input type='text' id='desc_sales_man' name='desc_sales_man' class='hid_value' style='width:300px;'/>
                </td>
                <tr>    

                    <tr>
                        <td>Use Collection Off</td>
                        <td>
                            <input type='checkbox' name='is_collection_off' id='is_collection_off' title='1' />
                            <input type='text' name='collection_off' id='collection_off' class='input_txt'/>
                            <input type='text' id='desc_collection_off' name='desc_collection_off' class='hid_value' style='width:300px;'/>
                        </td>
                        <tr>   
                            <tr>
                                <td>Use Driver</td>
                                <td>
                                    <input type='checkbox' name='is_driver' id='is_driver' title='1' />
                                    <input type='text' name='driver' id='driver' class='input_txt'/>
                                    <input type='text' id='desc_driver' name='desc_driver' class='hid_value' style='width:300px;'/>
                                </td>
                                <tr>    

                                    <tr>
                                        <td>Use Helper</td>
                                        <td>
                                            <input type='checkbox' name='is_helper' id='is_helper' title='1' />
                                            <input type='text' name='helper' id='helper' class='input_txt'/>
                                            <input type='text' id='desc_helper' name='desc_helper' class='hid_value' style='width:300px;'/>
                                        </td>
                                        <tr>   
                                        </table>
                                    </div>
                                    <div id="tabs-4"> <!-- Tab 4-->
                                     <table>
                                        <tr>
                                            <td>
                                                <fieldset>
                                                    <legend>Opening HP</legend>
                                                    <table width="100%" border = "0">
                                                        <tr>
                                                            <td>Opening HP </td>
                                                            <td><input type='checkbox' name='def_use_opening_hp' id='def_use_opening_hp' value="1" /></td>
                                                        </tr>

                                                        <tr>
                                                            <td>No. of Opening HP  Account</td>
                                                            <td><input type='text' name='no_of_opening_hp' id='no_of_opening_hp' class='input_txt' style="text-align:right;" /></td>
                                                        </tr>
                                                    </table>
                                                </fieldset>
                                                <td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div id="tabs-5">
                                         <table border='0'>
                                            <tr>
                                              <td>Default Category </td>
                                              <td><input type='text'  name='show_cat' id='show_cat' class='input_txt'></td>                                    
                                              <td><input type='text' name='desc_cat' id='desc_cat' class='hid_value' style="width:300px"></td>
                                          </tr>
                                          <tr>
                                           <td>Default Town </td>
                                           <td><input type='text' name='show_town' id='show_town' class='input_txt'></td>
                                           <td><input type='text' name='desc_town' id='desc_town' class='hid_value'style="width:300px"></td>
                                       </tr>
                                       <tr>
                                           <td>Default Area</td>
                                           <td><input type='text'name='show_area' id='show_area' class='input_txt'></td>                                    
                                           <td><input type='text' name='desc_area' id='desc_area' class='hid_value' style="width:300px"></td>
                                       </tr> 
                                       <tr>
                                           <td>Default Route</td>
                                           <td><input type='text' name='show_route' id='show_route' class='input_txt'></td>                                    
                                           <td><input type='text' name='desc_route' id='desc_route' class='hid_value' style="width:300px"></td>
                                       </tr> 
                                       <tr>
                                           <td>Default Nationality</td>    
                                           <td><input type='text' name='show_nation' id='show_nation' class='input_txt'></td>                                    
                                           <td><input type='text' name='desc_nation' id='desc_nation' class='hid_value' style="width:300px"></td>
                                       </tr> 
                                       <tr>
                                           <td>Default Status</td>    
                                           <td><input type='text' name='show_status' id='show_status' class='input_txt'></td>                                    
                                           <td><input type='text' name='desc_status' id='desc_status' class='hid_value' style="width:300px"></td>
                                       </tr> 
                                   </table>
                               </div>
                           </div>
                           <table>                    
                            <tr>
                                <td colspan="2" style="text-align: right;">
                                    <input type="button" id="btnExit" title="Exit" />
                                    <input type="hidden" id="code_" name="code_" />
                                    <?php if($this->user_permissions->is_add('m_branch')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                                    <input type="button" id="btnReset" title='Reset'>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>
            <td class="content" valign="top">
                <div class="form">
           <!-- <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
        </table> -->
        <div id="grid_body"><?=$table_data;?></div>
    </div>
</td>
</tr>
</table>
<?php } ?>



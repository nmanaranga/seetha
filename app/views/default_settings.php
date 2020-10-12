<?php if($this->user_permissions->is_view('default_settings')&&
        $this->utility->is_developer()){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type='text/javascript' src='<?= base_url() ?>js/default_settings.js'></script>

<h2>Default Settings</h2>
<div class="dframe" id="mframe" style="padding-right:25px; width:1190px;">
    <form id="form_" method="post" action="<?= base_url() ?>index.php/main/save/default_settings" >
        <div id="tabs" >
            <ul>
                <li><a href="#tabs-1" >Stocks</a></li>
                <li><a href="#tabs-2" >Accounts</a></li>
                <li><a href="#tabs-3" >Sales</a></li>
                <li><a href="#tabs-4" >Module</a></li>
                <li><a href="#tabs-5" >PO Active Days</a></li>
                <li><a href="#tabs-6" >Print</a></li>
            </ul>
            <div id="tabs-1">
                <table border="0">
                    <tr>
                        <td style="width:85px;"></td>
                        <td style="width:42px;"></td>
                        <td style="width:130px;"></td>
                        <td style="width:150px;"></td>
                        <td style="width:90px;"></td>
                        <td style="width:140px;"></td>
                        <td style="width:150px;"></td>
                        <td style="width:150px;"></td>
                        <td style="width:200px;"></td>
                       
                        
                    </tr>

                    <tr>
                        <td>Sub Item</td>
                        <td><input type='checkbox' name='sub_item' id='sub_item' title='1'/></td>
                        <td>Auto Department</td>
                        <td><input type='checkbox' name='auto_dep_id' id='auto_dep_id' title='1'/>
                                        <input type='text' name='auto_dep' id='auto_dep' title=''  style="width:100px;" /></td>
                        
                        <td>Auto Color</td>
                        <td><input type='checkbox' name='auto_clr_id' id='auto_clr_id' title='' />
                            <input type='text' name='auto_clr' id='auto_clr' title='' style="width:100px;"/></td> 
                        
                          <td>Auto Nationality</td>
                          <td><input type='checkbox' name='auto_national_id' id='auto_national_id' title='' />
                            <input type='text' name='auto_national' id='auto_national' title='' style="width:100px;"/></td>
                          
                    </tr>                
                    <tr>
                        <td>Serial No </td>
                        <td><input type='checkbox' name='serial_no' id='serial_no'  title='1'/></td>
                         
                         <td>Auto Main Category</td>
                        <td><input type='checkbox' name='auto_main_cat_id' id='auto_main_cat_id' title=''/>
                            <input type='text' name='auto_main_cat' id='auto_main_cat' title=''style="width:100px;"/></td>
                         
                         <td>Auto Area</td>
                        <td><input type='checkbox' name='auto_area_id' id='auto_area_id' title='' />
                            <input type='text' name='auto_area' id='auto_area' title='' style="width:100px;"/></td> 
                       
                         <td>Auto Customer Category</td>
                        <td><input type='checkbox' name='auto_c_category_id' id='auto_c_category_id' title='' />
                            <input type='text' name='auto_c_category' id='auto_c_category' title='' style="width:100px;"/></td>   
                        
                     
                        
                    </tr>
                   <tr>
                        <td>Item Batch</td>
                        <td><input type='checkbox' name='item_batch' id='item_batch'  title='1'/></td>
                         <td>Auto Sub Category</td> 
                        <td><input type='checkbox' name='auto_sub_cat_id' id='auto_sub_cat_id' title='' />
                            <input type='text' name='auto_sub_cat' id='auto_sub_cat' title='' style="width:100px;"/></td>
                        
                        <td>Auto Route</td>
                        <td><input type='checkbox' name='auto_route_id' id='auto_route_id' title='' />
                            <input type='text' name='auto_route' id='auto_route' title='' style="width:100px;"/></td> 
                        
                        <td>Auto Customer Type</td>
                        <td><input type='checkbox' name='auto_c_type_id' id='auto_c_type_id' title='' />
                            <input type='text' name='auto_c_type' id='auto_c_type' title='' style="width:100px;"/></td> 
                         
                        
                    </tr>
                    <tr>
                        <td>Additional Item</td>
                        <td><input type='checkbox' name='add_item' id='add_item'  title='1'/></td>
                        <td>Auto Unit</td>
                        <td><input type='checkbox' name='auto_unit_id' id='auto_unit_id' title='1'/>
                                        <input type='text' name='auto_unit' id='auto_unit' title='' style="width:100px;"/></td>
                         <td>Auto Town</td>
                        <td><input type='checkbox' name='auto_town_id' id='auto_town_id' title='' />
                            <input type='text' name='auto_town' id='auto_town' title='' style="width:100px;"/></td>   
                        

                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td>Auto Brand</td>
                        <td><input type='checkbox' name='auto_brand_id' id='auto_brand_id' title='' />
                            <input type='text' name='auto_brand' id='auto_brand' title='' style="width:100px;"/></td>
                         
                         <td>Auto Supplier</td>
                        <td><input type='checkbox' name='auto_supplier_id' id='auto_supplier_id' title='' />
                            <input type='text' name='auto_supplier' id='auto_supplier' title='' style="width:100px;"/></td> 
                         
                    </tr>
                     <tr>   
                        <td colspan='8' width="100%"><hr/></td> 
                    </tr>
                </table>

                <table border="0" width="100%">
                  
                  
                     

                    <tr>  
                        <td style="width:118px;" >Auto Item Id</td>
                        <td style="width:1500px;"><input type='checkbox' name='auto_item_id' id='auto_item_id' title='' />
                            <input type='text' name='auto_item' id='auto_item' title='' style="width:100px;"/></td>
                        

                       <td></td>
                    
                    <tr>
                    <tr>
                        <td colspan="2"><b>Generate Item Code By</b></td>
                    <tr> 
                    <tr>
                        <td colspan="2"><input type="radio" style="display: inline-block;" name="gen_itemcode_by_department" id="gen_itemcode_by_department" value="1"  class="gen_ic">
                       Department ID</td>

                    <tr>    
                    <tr>
                         <td colspan="2"><input type="radio" style="display: inline-block;" name="gen_itemcode_by_maincat" id="gen_itemcode_by_maincat" value="1" class="gen_ic">
                         Main Category ID</td>
                    <tr>  
                    <tr>   
                        <td colspan="2"><input type="radio" style="" name="gen_itemcode_by_subcat" id="gen_itemcode_by_subcat" value="1" class="gen_ic">
                        Sub Category ID</td>
                    </tr> 
                    <tr> 
                        <td colspan="2"><input type="radio" style="display: inline-block;" name="gen_supplier_by_auto_id" id="gen_supplier_by_auto_id" value="1" class="gen_ic">
                        Supplier ID</td>
                        
                    <tr>    
                    <tr> 
                        <td colspan="2"> <input type="radio" style="display: inline-block;" name="gen_itemcode_by_standard" id="gen_itemcode_by_standard" value="1" class="gen_ic">
                        Standard</td>
                    <tr>   
                    <tr> 
                        <td colspan="2"> <input type="radio" style="display: inline-block;" name="gen_itemcode_by_normal" id="gen_itemcode_by_normal" value="1" class="gen_ic">
                        Normal</td>
                    </tr>  
                </table>

            </div>

            <div id="tabs-2">
                <table>
                    <tr>
                        <td>Is Sales Discount in Sepearate Acc</td>
                        <td><input type='checkbox' name='sep_sales_dis' id='sep_sales_dis' title='1'/></td>
                    <tr>
                    <tr>
                        <td>Is Sales Returns in Sepearate Acc</td>
                        <td><input type='checkbox' name='sep_sales_ret' id='sep_sales_ret' title='1'/></td>
                    <tr>   
                    <tr>
                        <td>Is Multi Cheques In Voucher</td>
                        <td><input type='checkbox' name='is_m_chq' id='is_m_chq' title='1'/></td>
                    <tr>   
                </table>        
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
            
            <div id="tabs-4">
                <table>
                    <tr>
                    <td>Seettu Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_seettu' id='use_seettu' title='1'/></td>
                </tr> 

                <tr>
                    <td>HP Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_hp' id='use_hp' title='1'/></td>
                </tr> 

                 <tr>
                    <td>Service Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_service' id='use_service' title='1'/></td>
                </tr>

                <tr>
                    <td>Cheque Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_cheque' id='use_cheque' title='1'/></td>
                </tr> 

                <tr>
                    <td>Gift Voucher Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_gift' id='use_gift' title='1'/></td>
                </tr>

                <tr>
                    <td>Privilage Card Module</td>
                    <td style="width:50px;"><input type='checkbox' name='use_privilage' id='use_privilage' title='1'/></td>
                </tr> 
                </table>
                       
            </div>

            <div id="tabs-5">
               
            <table border="1">
                

                <tr>
                    <td>PO Number</td>
                    <td></td>
                </tr> 
                <tr>
                    <td></td>
                    <td></td>
                </tr> 
                <tr>
                    <td></td>
                    <td></td>
                </tr> 

            </table>
            </div>

            <div id="tabs-6">

                 <fieldset>
                    <legend >Print</legend>
                      <table border="0">
                        <tr>
                            <td><input type="checkbox" name="print_cur_time" id="print_cur_time"></td>
                            <td>Print Current Time</td>
                            
                        </tr> 
                        <tr>
                            <td><input type="checkbox" name="print_sav_time" id="print_sav_time"></td>
                            <td>Print Save Time</td>
                        </tr> 
                        <tr>
                            <td>Heading Align</td>
                            <td>
                                <select id="type" name="type">
                                    <option value="0">---</option>
                                    <option value="L" title="Left">Left</option>
                                    <option value="C" title="Center">Center</option>
                                    <option value="R" title="R">Right</option>
                                </select>
                            </td>
                        </tr> 

                      </table>
                </fieldset>
          
            </div>
        </div>
        
       

        <input type="button" id="btnExit" title="Exit" />
        <input type="button" id="btnResett" title="Reset" />
        <?php if($this->user_permissions->is_add('default_settings')){ ?><input type="button" title='Save <F8>' id="btnSave"/><?php } ?>


    </form>
</div>
<?php } ?>
<?php if($this->user_permissions->is_view('m_customer')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_customer_status.js'></script>
<script type="text/javascript" src="js/jscolor/jscolor.js"></script>

<h2>Customers Status</h2>
<div>
<table width="100%" border="0">
    <tr>

        <td valign="top" class="content" style="width: 640px;">
            <div class="form" id="form" >
    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_customer_status" >
                  <table border="0" style="width:100%;">
                            <tr>
                            <td>Code</td>
            <td><input type="text" class="input_active_hid"  title='' id="code" name='code' maxlength="10" style="width:150px; border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;">
                <input type="hidden" id="code_" name="code_"></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><input type="text" class="input_txt" id="txtDesc" name="txtDesc" maxlength="50" style="width:350px; border: 1px solid #003399; padding:3px: font-weight: bold; font-size: 12px;font-family:Arial;"></td>
                            </tr>
                            <tr>
                                <td>Color</td>
                                <td><input type="text" class="color" placeholder="click here" style="width:150px; border: 1px solid #003399;padding: 3px;" id="color" name ="color"></td>
                                </tr>
                                    <!-- <span class="colorpicker">
                                    <span class="bgbox"></span>
                                    <span class="hexbox"></span>
                                    <span class="clear"></span>
                                    <span class="colorbox">
                                <b class="selected" style="background:#A9BAD4" title="Light Blue"></b>
                                <b id ="#A1A4B3" style="background:#A1A4B3" title="Stone Blue"></b>
                                <b style="background:#A49381" title="Sand"></b>
                                <b style="background:#626878" title="Charcoal"></b>
                                <b style="background:#2E436E" title="Navy Blue"></b>    
                                <b style="background:#2E436E" title="Navy Blue"></b>   
                            </span>    
                        </span>
                        <td/><td>
                                 <select id='color' name='color' style="width:150px">
                                    <option label="Select color">--Select color--</option>
                                    <option class="redColor" value="Red">Red</option>
                                    <option value="Blue">Blue</option>
                                    <option value="Gold">Gold</option>
                                    <option class="greenColor" value="Green">Green</option>
                                    <option value="Yellow">Yellow</option>
                                    </select>
                                    <input type="text" id="put_color" name="put_color" style="width:30px; border: 1px solid #003399; padding:3px;" class="" readonly="readonly" > </td>

                            </tr>-->
                        </table>
                    
                <div aign="center"style="text-align: right; padding:7px;">
                <input type="button" id="btnExit" title="Exit" />
                <input type="button" id="btnSave" title='Save <F8>' />                    
                    <input type="button" id="btnReset" title='Reset'>
                </div>
                </div>
                </form>
            </div>
        </td>
        <td class="content" valign="top" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
                <table align="center">
                    <tr>
                <td><label>Search</label></td>
              <td> <input type="text" id="search" name="search" class="input_txt" style="width:300px; marging-bottom:100px;"/></td> 
                    </tr>
                </table>
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>


</div>
<?php } ?>
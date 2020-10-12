<?php if($this->user_permissions->is_view('m_vehicle_setup')){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type='text/javascript' src='<?= base_url() ?>js/m_vehicle_setup.js'></script>
<h2>Vehicle Setup</h2>
<div>
    <table style="width:100%;" id="tbl1">
        <tr>
            <td valign="top" class="content" style="width:500px;">
                <div class="form" id="form">
                    <form id="form_" method="post" action="<?= base_url() ?>index.php/main/save/m_vehicle_setup" >
                        <table style="width:450px;" id="tbl2">
                            <tr>
                                <td>Cluster</td>
                                <td colspan="2"><input type="text" id="cl" name="cl" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="cluster_name" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr>
                            <tr>
                                <td>Branch</td>
                                <td colspan="2"><input type="text" id="bc" name="bc" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="branch_name" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr>
                            <tr>
                                <td>Vehicle No</td>
                                <td colspan="2"><input type="text" id="code" name="code" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td colspan="2"><input class="input_txt" type="text" id="description" name="description" title="" name="heading" maxlength="50" style="width:353px;" /></td>
                            </tr>
                            <tr>
                                <td>Driver</td>
                                <td colspan="2"><input type="text" id="driver" name="driver" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="driver_name" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr> 
                            <tr>
                                <td>Stores</td>
                                <td colspan="2"><input type="text" id="stores" name="stores" style="width:100px; text-transform: uppercase;" title="" class="input_txt upper" maxlength="10"/>
                                <input type="text" id="stores_name" style="width:250px;"  readonly="readonly" class="hid_value"/></td>
                            </tr>                           
                            <tr>
                                <td>&nbsp;</td>
                                <td style="text-align:right" colspan="2">
                                    <input type="hidden" id="code_" name="code_" title="0" />
                                    <input type="button" id="btnExit" title='Exit' />
                                    <input type="button" id="btnReset" title='Reset'>  
                                    <?php if($this->user_permissions->is_add('m_vehicle_setup')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>

            <td valign="top" class="content">
                <div class="form" >
                    <table>
                        <tr>
                            <td style="padding-right:64px;"><label>Search</label></td>
                            <td><input type="text" class="input_txt" title='' id="type_search" name="srch" style="width:230px; marging-left:20px;">
                            </td>
                        </tr>
                    </table>
                    <div id="grid_body"><?= $table_data; ?></div>
                </div>
            </td>

        </tr>
    </table>
</div>
<?php } ?>




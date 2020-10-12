<?php if($this->user_permissions->is_view('r_journal_type')){ ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/t_forms.css" />
<script type='text/javascript' src='<?= base_url() ?>js/r_journal_type.js'></script>
<h2>Paybale / Recievalble / Journal Type </h2>

<table style="width:100%;" id="tbl1">
    <tr>
        <td width="50%" valign="top" class="content" style="width:55%">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?= base_url() ?>index.php/main/save/r_journal_type" >
                    <table style="width:100%;" id="tbl2">
                        <tr>
                            <td>Code</td>
                            <td><input type="text" id="code" name="code" class="input_txt upper" style="width:150px; text-transform: uppercase;" maxlength="20" />
							  Type
							    <select name="payble_type" id="payble_type">
                                    <option value="0">---</option>
                                    <option value="1">Payble</option>
                                    <option value="2">Journal</option>
                                    <option value="3">Receivable</option>
                          </select>						    </td></tr>

                        <tr>
                            <td>Description</td>
                            <td><input type="text" id="description" name="description" class="input_txt" style="width:485px;" maxlength="50" /></td>
                        </tr>

                        <tr>
                            <td>Payble A/C</td>
                            <td><input type="text" class="input_txt" id="saccount" title="" style="width: 150px;" />
                                <input type="hidden" name="account" id="account" title="0" />
                                <input type="text" class="input_txt" title='' readonly="readonly" id="account_des"  style="width: 332px;"/></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table style="width:100%" id="tgrid">
                                    <thead>
                                        <tr>
                                            <th class="tb_head_th" style="width:150px">Code</th>
                                            <th class="tb_head_th" style="width:200px" >Account Name</th>
                                            <th class="tb_head_th" style="width:70px">Dr</th>
                                            <th class="tb_head_th" style="width:70px">Cr</th>
                                            <th class="tb_head_th" style="width:100px">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for ($x = 0; $x < 25; $x++) {
                                            echo "<tr>";
                                            echo "<td><input style='background-color: #f9f9ec;width:100%;' type='text' class='g_input_txt fo' id='0_" . $x . "'/>
                                                 <input style='background-color: #f9f9ec;width:100%;' type='hidden' title='0' class='g_input_txt fo' id='h_" . $x . "' name='h_" . $x . "' /></td></td>";
                                            echo "<td style='background-color: #E6E6E6;' ><input type='text' class='g_input_txt' style='width:100%' readonly='readonly'  id='n_" . $x . "' /></td>";
                                            echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_amo' style='width:100%' id='1_" . $x . "' name='1_" . $x . "'/></td>";
                                            echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_amo' style='width:100%' id='2_" . $x . "' name='2_" . $x . "'/></td>";
                                            echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt' style='width:100%' id='3_" . $x . "' name='3_" . $x . "'/></td>";
                                            echo "</tr>";
                                        }
                                        ?>											
                                    </tbody>
                                </table>							</td>
                        </tr>


                        <tr>
                            <td style="text-align:right" colspan="2">
                                <input type="hidden" id="code_" name="code_" title="0" />
                                <input type="button" id="btnExit" title='Exit' />
                                <input type="button" id="btnReset" title='Reset'>	
                                <?php if($this->user_permissions->is_add('r_journal_type')){ ?><input type="button" id="btnSave" title='Save <F8>' /> <?php } ?>
                            </td>
                        </tr>
                    </table>
                    <!--tbl2-->
                </form><!--form_-->
            </div><!--form-->
        </td>
        <td width="50%" valign="top" class="content" style="width:65%;">
            <div class="form">
                <table>
                <tr>
                <td style="padding-right:64px;"><label>Search</label></td>
                <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
                </tr>
                </table>
                <div id="grid_body"><?= $table_data; ?></div>
            </div>
        </td>
    </tr>
</table><!--tbl1-->
<?php } ?>

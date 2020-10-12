
<?php if($this->user_permissions->is_view('chq_account_setup')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/chq_account_setup.js'></script>
<h2>Account Setup</h2>
<div>
<table width="100%" border="0">
    <tr>
        <td valign="top" class="content" style="width: 45px;">
            <div class="form" id="form">
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/chq_account_setup" >
                <table>

                    <tr>
                        <td>Code</td>
                        <td>
                            <input type="text" class="input_txt" id="id" name="id" style="width:150px;">
                        </td>
                    </tr>

                    <tr>
                        <td>Name</td>
                        <td>
                            <input type="text" class="input_txt" id="name" name="name" style="width:450px;">
                        </td>
                    </tr>

                    <tr>
                        <td>A/C No:</td>
                        <td><input type="text" class="input_txt" id="code" name="code" style="align:right;width:150px;">
                        <input type="text" class="hid_value" id="des" name="des" style="align:right;width:300px;">
                        <input type="hidden" class="input_txt" id="code_" name="code_" value="0"></td>
                    </tr>

                    <tr>
                        <td>Print Scheme</td>
                        <td><input type="text" class="input_txt" id="scheme_code" name="scheme_code" style="width:150px;">
                            <input type="text" class="hid_value" id="scheme_des" name="scheme_des" style="width:300px;"/></td>
                    </tr>

                    <tr>
                        <td>Stamp</td>
                        <td><input type="text" class="input_txt" title='' id="stamp_1" name="stamp_1" style="width:450px;"/></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="text" class="input_txt" title='' id="stamp_2" name="stamp_2" style="width:450px;"/></td>
                    </tr>
                    </table>
                    <div style="border:#000000 solid 2px; background-color:#F2F2F2;">
                        <table style="padding-left:30px;"> 
                            <tr>
                                <td>
                                    <b><h3 style="color:#000000;">Tip</h3></b>
                                </td><br/>
                            </tr>

                            <tr>
                                <td width="100"> A/C No.</td>
                                <td width="25">  > </td>
                                <td id="Acc_id"> Enter Bank Account No. Here</td>
                            </tr>
                           <!--<tr>
                                <td>Last Chq. No.</td>
                                <td> > </td>
                                <td> Enter Last Printed Cheque No. Here</td>
                            </tr>-->
                            <tr>
                                <td> Print Scheme</td>
                                <td> > </td>
                                <td id="print_s_code"> Select a Cheque Scheme for Printing a Cheque in This Account</td>
                            </tr>
                            <tr>
                                <td> Stamp</td>
                                <td> > </td>
                                <td id="stamp_01">If You Want to Print Your Stamp Enter Stamp Details</td>
                            </tr>
                            <tr>
                                 <td> </td>
                                 <td> </td>
                                <td id="stamp_02"> Example:-</td>
                            </tr>
                            <tr>
                                 <td> </td>
                                 <td> </td>
                                <td style="padding-left:50px; font-weight:bold;" id="stamp_03">
                                   
                                    Seetha Group Of Company
                                    
                                </td>
                            </tr>
                            <tr>
                                 <td> </td>
                                 <td> </td>
                                <td style="padding-left:50px;" id="stamp_05"><br/><br/>
                                     - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                                </td>
                            </tr>
                            <tr>
                                 <td> </td>
                                 <td> </td>
                                <td style="padding-left:90px;" id="stamp_04">
                                    Managing Derector
                                </td>
                            </tr>
                    </table>
                    <br/><br/>
                    </div>
                    
                <table>

                    <tr>
                        <td colspan="2" style="text-align:right">
                            <input type="button" id="btnExit" title="Exit" />
                            <?php if($this->user_permissions->is_add('chq_account_setup')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td class="content" valign="top" style="width: 450px;">
            <div class="form" id="form" style="width: 450px;">
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

<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
                 <input type="hidden" name='by' value='m_customer' title="m_customer" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='pdf_no' id="pdf_no" value='' title="" >
                 <input type="hidden" name='orientation' value='P' title="P" >
                 <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
                 <input type="hidden" name='qno' value='' title="" id="qno" >  
                 <input type="hidden" name='code_find' value='' title="" id="code_find" >                
                 <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
                 <input type="hidden" name='dt' value='' title="" id="dt" > 
        
</form>
</div>
<?php } ?>
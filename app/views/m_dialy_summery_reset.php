<?php if($this->user_permissions->is_view('m_dialy_summery_reset')){ ?>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
    <script type="text/javascript" src="<?=base_url()?>js/m_dialy_summery_reset.js"></script>
    <h2 style="text-align: center;">Daily Summery Reset</h2>
    <div class="dframe" id="mframe">
        <form method="post" action="<?=base_url()?>index.php/main/save/m_dialy_summery_reset" id="form_">
            <table style="width: 100%" border="0" cellpadding="5">
                <tr><td style="width: 50px;"></td>
                    <td style="width: 150px;"></td>
                    <td ></td>
                    <td style="width: 100px;">No</td>
                    <td style="width: 100px;">
                        <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                        <input type="hidden" id="hid" name="hid" title="0" />                </td>
                    </tr>

                    <tr><td></td>
                        <td></td>
                        <td></td>
                        <td style="width: 100px;">Date</td>
                        <td style="width: 100px;">
                            <?php if($this->user_permissions->is_back_date('m_dialy_summery_reset')){ ?>
                                <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                            <?php } else { ?>
                                <input type="text" class="input_txt" readonly="readonly" style="width:100%; text-align:right;" name="date" id="date" title="<?=date('Y-m-d')?>" />
                            <?php } ?> 
                        </td>
                    </tr>

                    <tr><td></td>
                        <td>Reset As At</td>
                        <td><input type="text" class="input_date_down_future" readonly="readonly" name="rdate" id="rdate" title="<?=date('Y-m-d')?>" style="width:30%; text-align:right;"/>
                            <?php if($this->user_permissions->is_add('m_dialy_summery_reset')){ ?><input type="button" id="btnSave" title='Save' style="width:30%;/>
                            </td>
                            <td style="width: 100px;"></td>
                            <td style="width: 100px;"></td>
                        </tr>
                        
                        <tr><td></td>
                            <td>History Report</td>
                            <td><input type="text" class="input_date_down_future" readonly="readonly" name="pdate" id="pdate" title="<?=date('Y-m-d')?>" style="width:30%; text-align:right;"/>
                                <?php if($this->user_permissions->is_re_print('m_dialy_summery_reset')){ ?><input type="button" id="btnPrint" title='Print'style="width:30%; /> <?php } ?>
                                </td>
                                <td style="width: 100px;"></td>
                                <td style="width: 100px;"></td>
                            </tr>
                            <tr>
                            <?php } ?>
                            
                        </table>
                        <?php 
                        if($this->user_permissions->is_print('m_dialy_summery_reset')){ ?>
                            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
                        <?php } ?> 
                    </form>


                    <form method="post" action="<?=base_url()?>index.php/reports/generate/m_dialy_summery_reset" id="print_pdf" target="_blank"> 
                        <input type="hidden" name='by' value='m_dialy_summery_reset' title="m_dialy_summery_reset" class="report">
                        <input type="hidden" name='page' value='A4half' title="A4half" >
                        <input type="hidden" name='orientation' value='P' title="p" >
                        <input type="hidden" name='type' value='0' title="0" >
                        <input type="hidden" name='header' value='false' title="false" >
                        <input type="hidden" name='qno' value='' title=5 id="qno" >
                        <input type="hidden" name='pd' title="<?= date('Y-m-d') ?>" id="pd" >
                        

                    </form>

                </div>

                <?php } ?>
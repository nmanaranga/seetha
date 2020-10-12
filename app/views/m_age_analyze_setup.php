<?php if($this->user_permissions->is_view('016')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    .posting_div{
        background-color: #FFF;
        border: 1px dotted #CCC;
        padding: 7px;
        padding-buttom: 0px;
    }
    
    .heading {
        background-color: #aee8c;
        margin: 5px;
        border: 2px solid #FFF;
        box-shadow: 0px 0px 5px #AAA;
        text-align: left;
        padding: 7px;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/m_age_analyze_setup.js"></script>

<h2 style="text-align: center;">Age Analyze Setup</h2>
<div class="dframe" id="mframe" style="text-align: center;">
    <?php if(isset($_GET['key'])){ echo "<span style='color: blue;' >".base64_decode($_GET['key'])."</span>"; } ?>
    <form action="index.php/main/save/m_age_analyze_setup" method="post" >
        <table style="width: 100%" id="tgrid">
            <tr>
                <th class="tb_head_th" >Description</th>
                <th class="tb_head_th" >Range</th>
                <th class="tb_head_th"  style='width : 150px'>Type</th>
            </tr>
            <?php
            error_reporting(0);
                for($x=0; $x<10; $x++){
                    
                    //print_r($table_data);exit;
                    
                    
                    if(!isset($table_data[$x]->description))
                        {
                        $table_data[$x]->description = "";
                        }
                    if(!isset($table_data[$x]->range)){
                        $table_data[$x]->range = ""; 
                        }
                    
                    if(isset($table_data[$x]->type)){
                        if($table_data[$x]->type == 1){
                            $t1 = "checked='checked'"; $t2 = "";
                        }elseif($table_data[$x]->type == 2){
                            $t2 = "checked='checked'"; $t1 = "";
                        }
                    }else{
                        $t1 = "";
                        $t2 = "";
                    }
                    
                    echo "<tr>";
                        echo "<td><input type='text' name='description".$x."' class='g_input_txt' id='description".$x."' style='width : 100%' title='".$table_data[$x]->description."'/></td>";
                        echo "<td><input type='text' name='range".$x."' class='g_input_num' id='range".$x."' style='width : 100%' title='".$table_data[$x]->range."'/></td>";
                        //echo "<td><input type='text' name='max".$x."' class='g_input_num' id='max".$x."' style='width : 100%' title='".$table_data[$x]->max."'/></td>";
                        echo "<td style='text-align: center;'><input type='radio' name='type".$x."' title='1' ".$t1." /> Day <input type='radio' name='type".$x."' title='2' ".$t2." /> Week </td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <div style="text-align: right; padding: 7px;">
                        <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="btnReset" title="Reset" />
            <?php if($this->user_permissions->is_view('016')){ ?>
            <input type="submit" id="btnSave" title='Save <F8>' />
            <?php } ?>
        </div>
    </form>
</div>
<?php } ?>
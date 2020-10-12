<?php if($this->user_permissions->is_view('008')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/m_customer_import.js"></script>

<h2 style="text-align: center;">Import Customers</h2>
<div class="dframe" id="mframe" style="text-align: center;">
    <span style="color: red; font-size: 14px; font-weight: bold;">
        <?php if(isset($_GET['error'])){ echo base64_decode($_GET['error']); } ?>
    </span>
    <form method="post" action="index.php/main/load_data/m_customer_import/upload" enctype="multipart/form-data" id="upload">
        <input type="file" name="userfile" id="up" /><!--<input type="submit" title="Upload" />-->
    </form>
    <form method="post" action="index.php/main/save/m_customer_import" id="imp">
        <div style=" overflow: auto; height: 430px;">
            <?=$table_data?>
        </div>
        <div style="text-align: right; margin-top: 14px;">
            <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="btnReset" title="Cancel" />
            <?php if($this->user_permissions->is_view('008')){ ?>
            <input type="button"  id="btnSave" title='Save <F8>' />
            <?php } ?>
        </div>
    </form>
</div>
<?php } ?>
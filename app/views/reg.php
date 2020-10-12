<style type="text/css">
    img {
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    function set_lock(id){
        $.post("/index.php/main/load_data/reg/lock", {
            id : id
        }, function(r){
            location.reload();
        }, "text");
    }
    
    function set_unlock(id){
        $.post("/index.php/main/load_data/reg/unlock", {
            id : id
        }, function(r){
            location.reload();
        }, "text");
    }
</script>
<h2>Coumputer Register</h2>
<div style="padding: 7px; margin: 14px;" id="form" class="form">
<?php
    echo $table;
?>
</div>
<?php
//session_start();
require_once("pr_bakup.php");
$b = new PR_Backup();

if($_POST['action'] == 'get_tables'){
    $b->get_tb();
}elseif($_POST['action'] == 'get_query'){
    $b->table = $_POST['tb'];
    
    $b->count = 5000;
    $b->limit = $_POST['limit'];
    
    $content = $b->bakup();
    
    if($_POST['limit'] <= $b->rec_count() && $content["res"] != ""){
        $res["change"] = false;
        $res["finish"] = false;
    }else{
        $res["change"] = true;
        $res["finish"] = true;
    }
    
    $bac = "bac/".$_POST['f'].".sql";

    if($_POST['start'] == "false"){
        empty_folder(); 
        $fp = fopen($bac, 'w+');
        
        $comment = <<<COM
/*

SQLyog Ultimate v8.55 
MySQL - 5.5.16 : Database - kff_20120106

*********************************************************************

*/



/*!40101 SET NAMES utf8 */;



/*!40101 SET SQL_MODE=''*/;



/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
COM;
        fwrite($fp, $comment);
        fclose($fp);
    }
    
    
    //print_r($content);
    $fp = fopen($bac, 'a') or die("can't open file");
    fwrite($fp, utf8_encode($content['res']));
    fclose($fp);
    
    $res['start'] = true;
    
    echo json_encode($res);
}

function empty_folder(){
    $mydir = "bac"; 
    $d = dir($mydir); 
    while($entry = $d->read()) { 
        if ($entry!= "." && $entry!= "..") { 
            unlink("bac/".$entry); 
        } 
    } 
    $d->close(); 
}
?>
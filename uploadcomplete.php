<?php
header('X-Frame-Options: SAMEORIGIN'); 
header('Content-type: text/html; charset=utf-8');
?>
<script type="text/javascript">
<?php 
$folderId = filter_var($_REQUEST["folderId"], FILTER_VALIDATE_INT);
$version = filter_var($_REQUEST["version"], FILTER_VALIDATE_INT);
$result = filter_var ( trim ( $_REQUEST["result"] ) , FILTER_SANITIZE_STRING );
?>

window.parent.onFileUploadComplete(<?php echo $folderId?>, <?php echo $version?>, <?php echo '"'.$result.'"'?>);
</script>
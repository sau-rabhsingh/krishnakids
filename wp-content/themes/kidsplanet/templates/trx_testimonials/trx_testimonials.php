<?php
// Autoload layouts in this folder
$name = pathinfo(__FILE__);
themerex_autoload_folder( 'templates/'.trim($name['filename']) );
?>
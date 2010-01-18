<?php 

  $_ROOT_DIRECTORY = dirname(__FILE__) . "/";
  
  foreach (glob($_ROOT_DIRECTORY . "includes/*.php") as $filename) {
     if ($filename !== ($_ROOT_DIRECTORY . "includes/core.php")) {
       require_once($filename);
     }
  }

  # create a new backend
  $b = new Backend();
  
  
  print_r ($b->list_files());
  
?>
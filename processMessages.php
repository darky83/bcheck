<?php

//Default includes for auto class lookup.
include 'includes/AutoLoader.php';
include 'bl/helpers/logging/LoggerManager.php';

//Start processing the mailbox. 
ProcessBackupMailBox::process();

?>
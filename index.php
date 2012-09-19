<?php
/* bcheck index.php
 * 
 */

//pop3 110
//imap 143
//$box = new MailBox("192.168.30.100", "143", "INBOX", "user", "pass");
//Print all as test.
//$box->printMailBoxSettings();

//$box->readMessages();

//echo var_dump(get_class_vars('BackupAttempt')); 
function startTimer() {
	return date('Y-m-d H:i:s m');
}

include_once 'bl/helpers/logging/LoggerManager.php';
include_once 'includes/AutoLoader.php';

ProcessBackupMailBox::Process();

/*
$start = startTimer();
for($i = 0; $i < 5000; $i++) {
	$b = new BackupValidator();
	$b->failureRegex = "failure";
	$b->successRegex = "success";
	$b->backupType = "test";
}

$stop = startTimer();

echo "Started without save on $start and stopped $stop <br/>";

$start = startTimer(); 

for($i = 0; $i < 5000;$i++) {
	$c = new BackupValidator(5);
}
$stop = startTimer();

echo "Started with retrieval on $start and stopped $stop";
*/


/*
$startRegular =  startTimer();

$t = new Test();
for($i = 0; $i < 500000; $i++) {
	$t->value = $i;
}

$stopRegular = startTimer();


$startRefl = startTimer();

$test = 'value';

$t = new Test();
$reflClass = new ReflectionClass('Test');
for($i = 0;$i < 500000; $i++) {
	$t->{'value'} = $i;
		
}

$stopRefl = startTimer();

echo "regular started on $startRegular and stopped on $stopRegular";
echo "<br/> reflection started on $startRefl and stopped on $stopRefl";
*/


?>
<?php
include("../lggr.php");
$log = new lggr(dirname(__FILE__)."/logs/");

$log->logWarning("This is an example of a warning log.");
$log->file("email_notices")->logEmergency("This is an emergency line in a different file.");
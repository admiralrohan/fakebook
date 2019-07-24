<?php
// require_once(dirname(__FILE__) . "/../env.php");
require_once(__DIR__ . "/../env.php");

try {
    $db = new mysqli($db_host, $db_username, $db_password, $db_name);
    $db->set_charset("utf8");
} catch (Exception $e) {
    print "An exception occured. Message: {$e->getMessage()}";
    // print "The system is busy please try again later.";
} catch (Error $e) {
    print "An error occured. Message: {$e->getMessage()}";
    // print "The system is busy please try again later.";
}

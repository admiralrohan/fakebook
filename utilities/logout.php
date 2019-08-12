<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// print_r($_SESSION);
// if (! isset($_SESSION['user_id'])) {
// echo session_id()." 1st";
// header("Location: ../index.php?from=logout&success=false");
// exit();
// } else {
// echo session_id()." 2nd";
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    // echo session_id()." 3nd";
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

if (session_status() == PHP_SESSION_ACTIVE) {
    // echo " 4th";
    session_destroy();
    header("Location: ../index.php?from=logout&success=true");
    exit();
}
// }

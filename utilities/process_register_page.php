<?php
$errors = array();

$fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
if (empty($fname)) {
    $errors[] = "You forgot to enter your first name.";
}

$lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
if (empty($lname)) {
    $errors[] = "You forgot to enter your last name.";
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (empty($email) || (! filter_var($email, FILTER_VALIDATE_EMAIL))) {
    $errors[] = "You forgot to enter your email address or the email format is incorrect.";
}

$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
if (empty($password)) {
    $errors[] = "You forgot to enter the password.";
} else {
    if ($password !== $cpassword) {
        $errors[] = "Your two passwords did not match.";
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
}

if (empty($errors)) {
    try {
        $query = "INSERT into users (user_id, fname, lname, email, psword, registered_on) ";
        $query .= "VALUES (NULL, ?, ?, ?, ?, NOW())";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('ssss', $fname, $lname, $email, $hashed_password);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {
            header('Location: index.php?from=register');
        } else {
            $errors[] = $db->error;
        }

        $db->close();
    } catch (Exception $e) {
        echo "Exception: " . $e;
    } catch (Error $e) {
        echo "Error: " . $e;
    }
}

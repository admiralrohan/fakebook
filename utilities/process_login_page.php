<?php
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
if (empty($email) || (!filter_var($email, FILTER_VALIDATE_EMAIL))) {
    $errors[] = "You forgot to enter your email address or not in correct format.";
}

$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
if (empty($password)) {
    $errors[] = "You forgot to enter the password.";
}

if (empty($errors)) {
    try {
        $query = "SELECT user_id, fname, lname, email, psword from users where email = ?";

        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $fname, $lname, $email_db, $password_db);
        $stmt->fetch();

        if (password_verify($password, $password_db)) {
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fname'] = $fname;
            $_SESSION['fullname'] = "{$fname} {$lname}";
            $_SESSION['email'] = $email_db;

            $url = "timeline.php";
            header('Location: ' . $url);
        } else {
            $errors[] = "Wrong email or password entered.";
        }

        $db->close();
    } catch (Exception $e) {
        echo "Exception: " . $e;
    } catch (Error $e) {
        echo "Error: " . $e;
    }
}

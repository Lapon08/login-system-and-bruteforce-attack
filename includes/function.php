<?php 
    function dbConnect(){
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "bruteforce";

        try {
            $db = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user,$db_pass);
            return $db;
        } catch (PDOException $e) {
            die( $e->getMessage());
        }
    }
    function loginUser($username,$password,$db){
        if (!empty($username) || !empty($password)) {
            $sql = "SELECT * FROM user WHERE username=:username";
            $stmt = $db->prepare($sql);
            $params = array(":username" => $username);
            $stmt-> execute($params);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $verify = password_verify($password,$user['password']);
                if ($verify) {
                    session_start();
                    $_SESSION['username'] = $user['username'];
                    header("Location: index.php");
                }else {
                    $error = "Wrong username or password";
                    return $error;
                }
            }else {
                $error = "Wrong username or password";
                return $error;
            }
        }else {
            $error = "enter your username and password";
            return $error;
        }

    }
    function registerUser($username,$password,$db){
        if (!empty($username) || !empty($password)) {
            $sql = "SELECT * FROM user WHERE username=:username ";
            $stmt = $db->prepare($sql);
            $params =array(
                ":username" => $username
            );
            $stmt->execute($params);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO user (`username`, `password`) VALUES (?,?)";
                $stmt = $db->prepare($sql);
                $saved = $stmt->execute([$username,$password]);
                if($saved) header("Location: login.php");
            }else{
                $error = "username has been used";
                return $error;
            }

        }else{
            $error = "Username / Password can not be empty";
            return $error;
        }
    }

    function checkSession(){
        session_start();
        if (empty($_SESSION['username'])) {
            header("Location: login.php");
        }
    }
    function logoutUser(){
        session_start();
        session_destroy();
        unset($_SESSION['username']);
        header("Location: login.php");
    }

?>
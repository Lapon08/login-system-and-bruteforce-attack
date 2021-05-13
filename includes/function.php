<?php 
    function dbConnect(){
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "bruteforce";

        try {
            $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8",$db_user,$db_pass);
            return $db;
        } catch (PDOException $e) {
            die( $e->getMessage());
        }
    }
    function loginUser($username,$password,$response,$token,$db){
        $secret_key = "6LfsE9MaAAAAAGwftHy5aNRg-R3BLx4Z-sho9TKw";
        // Disini kita akan melakukan komunkasi dengan google recpatcha
        // dengan mengirimkan scret key dan hasil dari response recaptcha nya
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
        $response = json_decode($verify);
        if($response->success){
            if (!empty($_SESSION['token'])) {
                if (!empty($username) || !empty($password) ) {
                    if ($token == $_SESSION['token']) {
                        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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
                        $error = "Invalid Token" . $token ." == " . $_SESSION['token'];
                        return $error;
                    }
                }else {
                    $error = "enter your username and password";
                    return $error;
                }
            }else {
                $error = "Invalid Token2";
                return $error;
            }
        }else {
            $error = "Invalid Captcha";
            return $error;
        }
    }
    function registerUser($username,$password,$db){
        if (!empty($username) || !empty($password)) {
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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
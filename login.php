<?php 
    session_start();
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }
    
    include('includes/function.php');
    $db = dbConnect();
    if (isset($_POST['submit'])) {
        $login = loginUser($_POST['username'],$_POST['password'],$_POST["g-recaptcha-response"],$_POST['token'],$db);
    }
    include("view/header.html");
?>


<body>
    <div class="container d-flex justify-content-center">
        <div class="col-lg-5 border m-5">
        <h3 class="text-center">Login</h3>
        <?php 
            if (isset($login)) {
                echo "<p style='color:red' class='text-center'>$login</p>";
            }
        ?>
            <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control">      
            </div>
            <div class="d-flex justify-content-center">
                <div class="g-recaptcha" data-sitekey="6LfsE9MaAAAAAIw9akG-evDmvAdNp2-JX8EaNidQ"></div>
            </div>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" required/>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" name="submit" class="btn btn-primary mb-2" >Login</button>   
            </div> 
            </form>
        </div>
    </div>
</body>

<?php 
    include("view/footer.html");
?>
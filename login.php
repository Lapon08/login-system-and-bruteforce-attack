<?php 
    include('includes/function.php');
    $db = dbConnect();
    if (isset($_POST['submit'])) {
        $login = loginUser($_POST['username'],$_POST['password'],$db);
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
            <button type="submit" name="submit" class="btn btn-primary mb-2" >Login</button>    
            </form>
        </div>
    </div>
</body>

<?php 
    include("view/footer.html");
?>
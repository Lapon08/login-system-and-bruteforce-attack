<?php 
    include('includes/function.php');
    checkSession();
    $db = dbConnect();
    include("view/header.html");
?>
<body>
    <h1>Hello <?php echo $_SESSION['username'] ?></h1>
    <a href="logout.php">Logout</a>
</body>
</html>
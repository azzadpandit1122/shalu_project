<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="centered-form" method="POST">
        <h2>Welcoeme to my School</h2>
        
        <label for="name">Name:</label>
        <input type="text" name="name">

        <label for="email"> Email</label>
        <input type="email" name="email">
        
        <label for="pass">Password:</label>
        <input type="password" name="pass">
        
        <input type="submit" name="sb">Submit</input>
    </form>


    <?php
    $con = mysqli_connect('localhost','root','','shalu');

    if(isset($_POST['sb'])){
        if($con){
            echo("Conncted");
        }else{
            echo("DisConncted");
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pass'];

        $query = "INSERT INTO `db` (`name`, `email`, `password`) VALUES ('$name', '$email', '$password')";
        $excute=mysqli_query($con,$query);
    }

    ?>
</body>
</html>    
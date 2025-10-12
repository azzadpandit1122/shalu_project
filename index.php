<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="centered-form" method="POST" >

        <label for="pass">Abouts Us:</label>
        <input type="text" name="aboutus">

        <label for="name">Name:</label>
        <input type="text" name="name">

        <label for="email"> Email</label>
        <input type="email" name="email">
        
        <label for="pass">Password:</label>
        <input type="password" name="pass">

        <label for="local address">local address:</label>
        <input type="local address" name="local_address">

        <label for="home address">home address:</label>
        <input type="home address" name="home_address">

        <label for="10th year">10th year:</label>
        <input type="10th year" name="10th_year">

        <label for="diploma year">diploma year:</label>
        <input type="diploma year" name="diploma_yaer">

        <label for="BSC year">BSC year:</label>
        <input type="BSC year" name="BSC_year">

        <label for="father name">father name:</label>
        <input type="father name" name="father_name">

        <label for="moher name">mother name:</label>
        <input type="mother name" name="mother_name">
    
        <input type="submit" name="sb"></input>
    </form>


    <form action="" class="centered-form" method="post">
    <input type="email" name="email_1" id="">
    <input type="submit" name="read"></input>
    </form>


    <?php
    $con = mysqli_connect('localhost','root','','shalu');

   




    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM db";
    $result = $con->query($sql);

    print_r("Read - R<br>");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
             print_r( "Name : " . $row["name"] .
                   "Email :".$row["email"] .
                   "Password :" .$row["password"] .
                   "About use :" .$row["about_use"].
                   "Father :" .$row["Father"].
                   "Mather :" .$row["Mather"].
                   "Local address :" .$row["local_address"].
                   "Home address :" .$row["home_address"].
                   "10th year  :" .$row["10th_year"].
                   "diploma year :" .$row["diploma_year"].
                   "Bsc year :" .$row["BSC_year"].
                    "<br>" );
        }
    } else {
        echo "0 results";
    }


    if(isset($_POST['sb'])){
       
        $aboutus = $_POST['aboutus'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $local_address = $_POST['local_address'];
        $home_address = $_POST['home_address'];
        $_10th_year = $_POST['10th_year'];
        $diploma_yaer = $_POST['diploma_yaer'];
        $BSC_year = $_POST['BSC_year'];
        $father = $_POST['father_name' ];
        $mother = $_POST['mother_name'];

        $query = "INSERT INTO `db` (`name`, `email`, `password`, `about_use`, `Father`, `Mather`, `local_address`, `home_address`, `10th_year`, `diploma_year`, `BSC_year`) 
        VALUES 
        ('$name', '$email', '$password', '$aboutus', '$father', '$mother', '$local_address', '$home_address', '$_10th_year', '$diploma_yaer', '$BSC_year')";


        $excute=mysqli_query($con,$query);
        
    }

    ?>
</body>
</html>
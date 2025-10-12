<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record by Email</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }
        .centered-form {
            width: 50%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 95%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"], button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 18px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover, button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<!-- Search form -->
<form method="post" action="" class="centered-form">
    <h3>Search Record by Email</h3>
    <input type="email" name="email_1" placeholder="Enter email" required>
    <input type="submit" name="read" value="Fetch Data">
</form>

<?php
// Step 1: Database Connection
$con = mysqli_connect('localhost','root','','shalu');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$record = null;
$showSaveButton = false;

// Step 2: Fetch data by email
if (isset($_POST['read'])) {
    $email = $_POST['email_1'];

    // ✅ Security fix: Use real_escape_string to prevent SQL injection
    $email = mysqli_real_escape_string($con, $email);

    $query = "SELECT * FROM db WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
        $showSaveButton = true;
    } else {
        echo "<p style='color:red;text-align:center;'>No record found for email: $email</p>";
    }
}

// Step 3: Update record
if (isset($_POST['update'])) {
    $old_email = $_POST['old_email'];  // original email (hidden)
    $new_email = $_POST['email'];      // new email entered
    $name = $_POST['name'];
    $password = $_POST['password'];

    // ✅ Escape all values
    $old_email = mysqli_real_escape_string($con, $old_email);
    $new_email = mysqli_real_escape_string($con, $new_email);
    $name = mysqli_real_escape_string($con, $name);
    $password = mysqli_real_escape_string($con, $password);

    // ✅ Update query including email change
    $updateQuery = "
        UPDATE db 
        SET name='$name', password='$password', email='$new_email' 
        WHERE email='$old_email'
    ";

    if (mysqli_query($con, $updateQuery)) {
        echo "<p style='color:green;text-align:center;'>✅ Record updated successfully!<br>Old Email: $old_email → New Email: $new_email</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Error updating record: " . mysqli_error($con) . "</p>";
    }
}


?>

<?php if ($record): ?>
    <form method="post" action="">
    <h3>Edit Record</h3>
    <!-- hidden original email -->
    <input type="hidden" name="old_email" value="<?php echo htmlspecialchars($record['email']); ?>">

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($record['email']); ?>"><br><br>

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($record['name']); ?>"><br><br>

    <label>Password:</label><br>
    <input type="text" name="password" value="<?php echo htmlspecialchars($record['password']); ?>"><br><br>

    <input type="submit" name="update" value="Save Changes">
    </form>


    <script>
        // Save original values to detect changes
        const originalData = {
            name: "<?php echo addslashes($record['name']); ?>",
            email: "<?php echo addslashes($record['email']); ?>",
            password: "<?php echo addslashes($record['password']); ?>",
            phone: "<?php echo addslashes($record['phone']); ?>"
        };

        function confirmUpdate() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const phone = document.getElementById('phone').value.trim();

            // Check if any field changed
            if (name !== originalData.name || email !== originalData.email || password !== originalData.password || phone !== originalData.phone) {
                return confirm("You have made some changes.\nDo you want to save them?");
            } else {
                alert("No changes detected. Nothing to save.");
                return false;
            }
        }
    </script>
<?php endif; ?>

</body>
</html>

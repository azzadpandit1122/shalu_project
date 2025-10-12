<?php
include 'config.php';

// Step 1: Handle Form Submission
if (isset($_POST['add_book'])) {
    $book_name = mysqli_real_escape_string($con, $_POST['book_name']);
    $book_year = mysqli_real_escape_string($con, $_POST['book_year']);
    $book_auther = mysqli_real_escape_string($con, $_POST['book_auther']);
    $book_price = mysqli_real_escape_string($con, $_POST['book_price']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    $is_for_sales = isset($_POST['is_for_sales']) ? 1 : 0;

    $insertQuery = "INSERT INTO books (book_name, book_year, book_auther, book_price, type, from_date, to_date, is_for_sales)
                    VALUES ('$book_name', '$book_year', '$book_auther', '$book_price', '$type', '$from_date', '$to_date', '$is_for_sales')";

    if (mysqli_query($con, $insertQuery)) {
        echo "<p style='color:green;text-align:center;'>✅ Book '$book_name' added successfully!</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Error adding book: " . mysqli_error($con) . "</p>";
    }
}

// Step 2: Fetch all books
$books = mysqli_query($con, "SELECT id, book_name, book_auther, book_price FROM books ORDER BY id DESC");
?>

<!-- Step 3: Page Layout -->
<div style="display:flex; max-width:1000px; margin:auto; gap:20px;">

    <!-- Left Column: List of Books -->
    <div style="flex:1; border:1px solid #ccc; padding:20px; border-radius:10px; max-height:600px; overflow-y:auto;">
        <h3 style="text-align:center;">📚 All Books</h3>
        <ul style="list-style:none; padding:0;">
            <?php while($book = mysqli_fetch_assoc($books)): ?>
                <li style="border-bottom:1px solid #eee; padding:10px 0;">
                    <strong><?php echo htmlspecialchars($book['id'].":"); ?></strong>
                    <strong><?php echo htmlspecialchars($book['book_name']); ?></strong><br>
                    Author: <?php echo htmlspecialchars($book['book_auther']); ?><br>
                    Price: ₹<?php echo htmlspecialchars($book['book_price']); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Right Column: Add Book Form -->
    <div style="flex:1;">
        <form method="post" action="" style="padding:20px;border:1px solid #ccc;border-radius:10px;">
            <h2 style="text-align:center;">📚 Add New Book</h2>
            
            <label>Book Name:</label><br>
            <input type="text" name="book_name" required style="width:100%;margin-bottom:10px;"><br>

            <label>Book Year:</label><br>
            <input type="number" name="book_year" min="1900" max="2099" required style="width:100%;margin-bottom:10px;"><br>

            <label>Author Name:</label><br>
            <input type="text" name="book_auther" required style="width:100%;margin-bottom:10px;"><br>

            <label>Book Price (₹):</label><br>
            <input type="number" step="0.01" name="book_price" required style="width:100%;margin-bottom:10px;"><br>

            <label>Type:</label><br>
            <input type="text" name="type" placeholder="e.g., Fiction, Education" style="width:100%;margin-bottom:10px;"><br>

            <label>From Date:</label><br>
            <input type="date" name="from_date" style="width:100%;margin-bottom:10px;"><br>

            <label>To Date:</label><br>
            <input type="date" name="to_date" style="width:100%;margin-bottom:10px;"><br>

            <label>
                <input type="checkbox" name="is_for_sales" value="1"> Available for Sale
            </label><br><br>

            <input type="submit" name="add_book" value="Add Book" style="background-color:#28a745;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;width:100%;">
        </form>
    </div>

</div>

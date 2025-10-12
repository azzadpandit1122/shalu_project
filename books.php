<?php
include 'config.php'; // handling database conntion with phpmyadmin

// Step 1: Handle Form Submission for Add/Update
if (isset($_POST['add_book'])) {
    $book_name = mysqli_real_escape_string($con, $_POST['book_name']);
    $book_year = mysqli_real_escape_string($con, $_POST['book_year']);
    $book_auther = mysqli_real_escape_string($con, $_POST['book_auther']);
    $book_price = mysqli_real_escape_string($con, $_POST['book_price']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    $is_for_sales = isset($_POST['is_for_sales']) ? 1 : 0;

    // Update if book_id is set, else insert
    if (!empty($_POST['book_id'])) {
        $book_id = intval($_POST['book_id']);
        $updateQuery = "UPDATE books SET 
            book_name='$book_name',
            book_year='$book_year',
            book_auther='$book_auther',
            book_price='$book_price',
            type='$type',
            from_date='$from_date',
            to_date='$to_date',
            is_for_sales='$is_for_sales'
            WHERE id='$book_id'";
        if (mysqli_query($con, $updateQuery)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
            exit();
        } else {
            echo "<p style='color:red;text-align:center;'>❌ Error updating book: " . mysqli_error($con) . "</p>";
        }
    } else {
        // Insert new book
        $insertQuery = "INSERT INTO books (book_name, book_year, book_auther, book_price, type, from_date, to_date, is_for_sales)
                        VALUES ('$book_name', '$book_year', '$book_auther', '$book_price', '$type', '$from_date', '$to_date', '$is_for_sales')";
        if (mysqli_query($con, $insertQuery)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?added=1");
            exit();
        } else {
            echo "<p style='color:red;text-align:center;'>❌ Error adding book: " . mysqli_error($con) . "</p>";
        }
    }
}

// Step 2: If edit_id is set, fetch book
$edit_book = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = mysqli_query($con, "SELECT * FROM books WHERE id='$edit_id'");
    $edit_book = mysqli_fetch_assoc($res);
}

// Step 3: Fetch all books
$books = mysqli_query($con, "SELECT * FROM books ORDER BY id DESC");

// Optional: messages
if (isset($_GET['added'])) echo "<p style='color:green;text-align:center;'>✅ Book added successfully!</p>";
if (isset($_GET['updated'])) echo "<p style='color:green;text-align:center;'>✅ Book updated successfully!</p>";
?>

<div style="display:flex; max-width:1000px; margin:auto; gap:20px;">

    <!-- Left Column: List of Books -->
    <div style="flex:1; border:1px solid #ccc; padding:20px; border-radius:10px; max-height:600px; overflow-y:auto;">
        <h3 style="text-align:center;">📚 All Books (Click to Edit)</h3>
        <ul style="list-style:none; padding:0;">
            <?php while($book = mysqli_fetch_assoc($books)): ?>
                <li style="border-bottom:1px solid #eee; padding:10px 0;">
                    <a href="?edit_id=<?php echo $book['id']; ?>" style="text-decoration:none; color:black; display:block;">
                        <strong><?php echo htmlspecialchars($book['book_name']); ?></strong><br>
                        Author: <?php echo htmlspecialchars($book['book_auther']); ?><br>
                        Price: ₹<?php echo htmlspecialchars($book['book_price']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Right Column: Add/Edit Book Form -->
    <div style="flex:1;">
        <form method="post" action="" style="padding:20px;border:1px solid #ccc;border-radius:10px;" 
            onsubmit="return confirm('Do you want to save the changes?');">
            <h2 style="text-align:center;"><?php echo $edit_book ? "✏️ Edit Book" : "📚 Add New Book"; ?></h2>
            
            <input type="hidden" name="book_id" value="<?php echo $edit_book['id'] ?? ''; ?>">

            <label>Book Name:</label><br>
            <input type="text" name="book_name" value="<?php echo $edit_book['book_name'] ?? ''; ?>" required style="width:100%;margin-bottom:10px;"><br>

            <label>Book Year:</label><br>
            <input type="date" name="book_year" value="<?php echo $edit_book['book_year'] ?? ''; ?>" min="1900" max="2099" required style="width:100%;margin-bottom:10px;"><br>

            <label>Author Name:</label><br>
            <input type="text" name="book_auther" value="<?php echo $edit_book['book_auther'] ?? ''; ?>" required style="width:100%;margin-bottom:10px;"><br>

            <label>Book Price (₹):</label><br>
            <input type="number" step="0.01" name="book_price" value="<?php echo $edit_book['book_price'] ?? ''; ?>" required style="width:100%;margin-bottom:10px;"><br>

            <label>Type:</label><br>
            <input type="text" name="type" value="<?php echo $edit_book['type'] ?? ''; ?>" placeholder="e.g., Fiction, Education" style="width:100%;margin-bottom:10px;"><br>

            <label>From Date:</label><br>
            <input type="date" name="from_date" value="<?php echo $edit_book['from_date'] ?? ''; ?>" style="width:100%;margin-bottom:10px;"><br>

            <label>To Date:</label><br>
            <input type="date" name="to_date" value="<?php echo $edit_book['to_date'] ?? ''; ?>" style="width:100%;margin-bottom:10px;"><br>

            <label>
                <input type="checkbox" name="is_for_sales" value="1" <?php echo (isset($edit_book['is_for_sales']) && $edit_book['is_for_sales']==1) ? 'checked' : ''; ?>> Available for Sale
            </label><br><br>

            <div style="display:flex; gap:4%;">
            <!-- Save Button -->
            <input type="submit" name="add_book" value="Save Book" 
                style="background-color:#28a745;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer; flex:1;">

            <?php if ($edit_book): ?>
                <!-- Cancel Button -->
                <button type="button" onclick="window.location.href='<?= $_SERVER['PHP_SELF'] ?>'" 
                        style="background-color:#dc3545;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer; flex:1;">
                    Cancel
                </button>
                <?php endif; ?>
            </div>

        </form>
    </div>

</div>

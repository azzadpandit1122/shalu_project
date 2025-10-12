<?php
include 'config.php'; // DB connection

// --- Messages to show after actions ---
$messages = [];

// --- Handle Add/Update Book ---
if (isset($_POST['add_book'])) {
    $book_name = mysqli_real_escape_string($con, $_POST['book_name']);
    $book_year = mysqli_real_escape_string($con, $_POST['book_year']);
    $book_auther = mysqli_real_escape_string($con, $_POST['book_auther']);
    $book_price = mysqli_real_escape_string($con, $_POST['book_price']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
    $is_for_sales = isset($_POST['is_for_sales']) ? 1 : 0;

    if (!empty($_POST['book_id'])) {
        // Update existing book
        $book_id = intval($_POST['book_id']);
        $sql = "UPDATE books SET 
                    book_name='$book_name',
                    book_year='$book_year',
                    book_auther='$book_auther',
                    book_price='$book_price',
                    type='$type',
                    from_date='$from_date',
                    to_date='$to_date',
                    is_for_sales='$is_for_sales'
                WHERE id='$book_id'";
        if (mysqli_query($con, $sql)) {
            $messages[] = "✅ Book updated successfully!";
        } else {
            $messages[] = "❌ Error updating book: ".mysqli_error($con);
        }
    } else {
        // Insert new book
        $sql = "INSERT INTO books (book_name, book_year, book_auther, book_price, type, from_date, to_date, is_for_sales)
                VALUES ('$book_name','$book_year','$book_auther','$book_price','$type','$from_date','$to_date','$is_for_sales')";
        if (mysqli_query($con, $sql)) {
            $messages[] = "✅ Book added successfully!";
        } else {
            $messages[] = "❌ Error adding book: ".mysqli_error($con);
        }
    }
}

// --- Handle Delete ---
if (isset($_POST['delete_book']) && !empty($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $sql = "DELETE FROM books WHERE id='$book_id'";
    if (mysqli_query($con, $sql)) {
        $messages[] = "✅ Book deleted successfully!";
    } else {
        $messages[] = "❌ Error deleting book: ".mysqli_error($con);
    }
}

// --- Fetch Book to Edit ---
$edit_book = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res = mysqli_query($con, "SELECT * FROM books WHERE id='$edit_id'");
    $edit_book = mysqli_fetch_assoc($res);
}

// --- Handle Search ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$books_query = "SELECT * FROM books";
if ($search !== '') {
    $books_query .= " WHERE book_name LIKE '%$search%'";
}
$books_query .= " ORDER BY id DESC";
$books = mysqli_query($con, $books_query);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Book Management</title>
</head>
<body style="font-family:Arial, sans-serif;">

<!-- --- Messages --- -->
<?php if(!empty($messages)): ?>
    <?php foreach($messages as $msg): ?>
        <p style="color:green; text-align:center;"><?php echo $msg; ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<div style="display:flex; max-width:1000px; margin:auto; gap:20px;">

    <!-- === Left Column: Book List + Search === -->
    <div style="flex:1; border:1px solid #ccc; padding:20px; border-radius:10px; max-height:600px; overflow-y:auto;">
        <h3 style="text-align:center;">📚 All Books</h3>

        <!-- Search Form -->
        <form method="get" action="" style="margin-bottom:10px;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search by book name..." 
                   style="width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </form>

        <!-- Book List -->
        <ul style="list-style:none; padding:0;">
            <?php if(mysqli_num_rows($books) > 0): ?>
                <?php while($book = mysqli_fetch_assoc($books)): ?>
                    <li style="border-bottom:1px solid #eee; padding:10px 0;">
                        <a href="?edit_id=<?php echo $book['id']; ?>" style="text-decoration:none; color:black; display:block;">
                            <strong><?php echo htmlspecialchars($book['book_name']); ?></strong><br>
                            Author: <?php echo htmlspecialchars($book['book_auther']); ?><br>
                            Price: ₹<?php echo htmlspecialchars($book['book_price']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li style="padding:10px; color:gray;">No books found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- === Right Column: Add/Edit Book Form === -->
    <div style="flex:1;">
        <form method="post" action="" style="padding:20px; border:1px solid #ccc; border-radius:10px;" 
              onsubmit="return confirm('Do you want to save the changes?');">
            <h2 style="text-align:center;"><?php echo $edit_book ? "✏️ Edit Book" : "📚 Add New Book"; ?></h2>
            
            <input type="hidden" name="book_id" value="<?php echo $edit_book['id'] ?? ''; ?>">

            <label>Book Name:</label><br>
            <input type="text" name="book_name" value="<?php echo $edit_book['book_name'] ?? ''; ?>" required style="width:100%; margin-bottom:10px;"><br>

            <label>Book Year:</label><br>
            <input type="date" name="book_year" value="<?php echo $edit_book['book_year'] ?? ''; ?>" min="1900" max="2099" required style="width:100%; margin-bottom:10px;"><br>

            <label>Author Name:</label><br>
            <input type="text" name="book_auther" value="<?php echo $edit_book['book_auther'] ?? ''; ?>" required style="width:100%; margin-bottom:10px;"><br>

            <label>Book Price (₹):</label><br>
            <input type="number" step="0.01" name="book_price" value="<?php echo $edit_book['book_price'] ?? ''; ?>" required style="width:100%; margin-bottom:10px;"><br>

            <label>Type:</label><br>
            <input type="text" name="type" value="<?php echo $edit_book['type'] ?? ''; ?>" placeholder="e.g., Fiction, Education" style="width:100%; margin-bottom:10px;"><br>

            <label>From Date:</label><br>
            <input type="date" name="from_date" value="<?php echo $edit_book['from_date'] ?? ''; ?>" style="width:100%; margin-bottom:10px;"><br>

            <label>To Date:</label><br>
            <input type="date" name="to_date" value="<?php echo $edit_book['to_date'] ?? ''; ?>" style="width:100%; margin-bottom:10px;"><br>

            <label>
                <input type="checkbox" name="is_for_sales" value="1" <?php echo (isset($edit_book['is_for_sales']) && $edit_book['is_for_sales']==1) ? 'checked' : ''; ?>> Available for Sale
            </label><br><br>

            <div style="display:flex; gap:4%;">
                <input type="submit" name="add_book" value="Save Book" 
                       style="background-color:#28a745; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; flex:1;">
                <?php if($edit_book): ?>
                    <button type="button" onclick="window.location.href='<?= $_SERVER['PHP_SELF'] ?>'" 
                            style="background-color:#dc3545; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; flex:1;">
                        Cancel
                    </button>
                    <button type="submit" name="delete_book" onclick="return confirm('Are you sure you want to delete this book?');"
                            style="background-color:#ff4d4f; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; flex:1;">
                        Delete
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

</div>
</body>
</html>

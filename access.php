<?php
include 'includes/dbconnect.php';

$sql = "SELECT * FROM resources";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Resources - BASO Course Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Available Lecture Resources</h2>
        <div class="list-group">
            <?php while($row = $result->fetch_assoc()): ?>
            <a href="<?php echo $row['file_path']; ?>" class="list-group-item list-group-item-action">
                <h5 class="mb-1"><?php echo $row['title']; ?></h5>
                <p class="mb-1"><?php echo $row['description']; ?></p>
                <small>Uploaded on: <?php echo $row['uploaded_at']; ?></small>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="assets/js/scripts.js"></script>
</body>
</html>

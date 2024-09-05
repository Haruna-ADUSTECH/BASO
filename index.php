<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'baso_course_repository';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $course_name = $_POST['course_name'];
    $lecturer_name = $_POST['lecturer_name'];

    // File upload logic
    $target_dir = "uploads/";
    $file_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = array('pdf', 'doc', 'docx', 'ppt', 'pptx');

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Insert file info into database
            $stmt = $conn->prepare("INSERT INTO courses (course_name, lecturer_name, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $course_name, $lecturer_name, $target_file);
            $stmt->execute();
            echo "<script>alert('The file $file_name has been uploaded successfully.');</script>";
        } else {
            echo "<script>alert('Error uploading file.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type.');</script>";
    }
}

// Fetch courses
$courses = $conn->query("SELECT * FROM courses ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BASO Online Course Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BASO Course Repository</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero py-5 text-center bg-primary text-white">
        <div class="container">
            <h1>Welcome to BASO Online Course Repository</h1>
            <p>Access and upload course materials easily.</p>
        </div>
    </section>

    <!-- Upload Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Upload Course Materials</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" required>
            </div>
            <div class="mb-3">
                <label for="lecturer_name" class="form-label">Lecturer Name</label>
                <input type="text" class="form-control" id="lecturer_name" name="lecturer_name" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Upload File</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </section>

    <!-- Display Courses Section -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Available Courses</h2>
        <div class="row">
            <?php if ($courses->num_rows > 0): ?>
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['course_name']) ?></h5>
                                <p class="card-text">Lecturer: <?= htmlspecialchars($row['lecturer_name']) ?></p>
                                <a href="<?= htmlspecialchars($row['file_path']) ?>" class="btn btn-primary" download>Download Materials</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No courses available yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 BASO Online Course Repository | All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

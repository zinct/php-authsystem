<?php
// Mulai session
session_start();

// Cek apakah session user ada
if (!isset($_SESSION['user'])) {
    // Jika tidak ada session user, redirect ke halaman login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="text-center">
            <h1 class="mb-3">Selamat Datang, <?php echo $_SESSION['user']; ?>!</h1>
            <p class="lead">Anda berhasil masuk ke halaman utama.</p>
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        </div>
    </div>
</body>

</html>
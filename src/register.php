<?php

require 'koneksi.php';

session_start();

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Kata sandi dan konfirmasi kata sandi tidak cocok!';
    } else {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Query gagal: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = 'Email sudah terdaftar!';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);

            if (!$insertStmt) {
                die("Query gagal: " . $conn->error);
            }

            $insertStmt->bind_param("sss", $name, $email, $hashedPassword);
            $insertStmt->execute();

            $_SESSION['user'] = $name;
            header('Location: home.php');
            exit();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Registrasi</h3>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3 text-start">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="name" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi Anda" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Masukkan ulang kata sandi Anda" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <div class="text-center mt-3">
                    <p class="mb-0">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
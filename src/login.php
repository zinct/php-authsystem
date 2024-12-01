<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
}


if (isset($_COOKIE['remember_me_email']) && isset($_COOKIE['remember_me_password'])) {
    $email = $_COOKIE['remember_me_email'];
    $password = $_COOKIE['remember_me_password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['name'];
            header('Location: home.php');
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['name'];

            if (isset($_POST['rememberMe'])) {
                setcookie('remember_me_email', $email, time() + (86400 * 30), "/"); // 30 hari
                setcookie('remember_me_password', $password, time() + (86400 * 30), "/"); // 30 hari
            }

            header('Location: home.php');
            exit();
        } else {
            $_SESSION['error'] = 'Email atau password salah!';
        }
    } else {
        $_SESSION['error'] = 'Email atau password salah!';
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Login</h3>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger text-center">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi Anda" required>
                </div>
                <div class="mb-3 form-check text-start">
                    <input type="checkbox" class="form-check-input" name="rememberMe">
                    <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <div class="text-center mt-3">
                    <p class="mb-1"><a href="forgot-password.php" class="text-decoration-none">Lupa Password?</a></p>
                    <p class="mb-0">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar di sini</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
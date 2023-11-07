<?php
$registrationStatus = ''; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

        // Validasi input (pastikan tidak kosong)
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $registrationStatus = '<div class="alert alert-danger" role="alert">Username, password, dan konfirmasi password harus diisi.</div>';
        } elseif ($password !== $confirmPassword) { // Validasi konfirmasi password
            $registrationStatus = '<div class="alert alert-danger" role="alert">Konfirmasi password tidak sesuai. Silakan coba lagi.</div>';
        } else {
            // Koneksi ke database
            $databaseHost = 'localhost';
            $databaseName = 'poliklinik';
            $databaseUsername = 'root';
            $databasePassword = '';

            $mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);

            if (!$mysqli) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Sanitasi input untuk mencegah SQL injection
            $username = mysqli_real_escape_string($mysqli, $username);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Query untuk memeriksa apakah username sudah ada
            $checkUsernameQuery = "SELECT * FROM user WHERE username = '$username'";
            $result = mysqli_query($mysqli, $checkUsernameQuery);

            if (mysqli_num_rows($result) > 0) {
                // Jika username sudah ada, tampilkan pesan kesalahan
                $registrationStatus = '<div class="alert alert-danger" role="alert">Username sudah digunakan. Silakan pilih username lain.</div>';
            } else {
                // Jika username belum ada dan konfirmasi password sesuai, lanjutkan dengan penyisipan data
                $sql = "INSERT INTO user (username, password) VALUES ('$username', '$hashedPassword')";

                if (mysqli_query($mysqli, $sql)) {
                    // Pesan pendaftaran sukses
                    $registrationStatus = '<div class="alert alert-success" role="alert">Registration successful</div>';

                    // Mengarahkan ke index.php setelah 3 detik
                    echo '<script>setTimeout(function() { window.location.href = "login.php"; }, 3000);</script>';
                } else {
                    // Pesan pendaftaran gagal
                    $registrationStatus = '<div class="alert alert-danger" role="alert">Registration failed. Please try again.</div>';
                }
            }

            // Tutup koneksi database
            mysqli_close($mysqli);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class a="navbar-brand" href="#">
                Sistem Informasi Poliklinik
            </a>
        </nav>
    </div>

    <div class="container">
        <h2>User Registration</h2>
        <?php echo $registrationStatus; // Menampilkan notifikasi registrasi ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxy3F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>

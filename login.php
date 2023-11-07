<?php
$loginStatus = ''; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

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

        // Query untuk mengambil data pengguna berdasarkan username
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = mysqli_query($mysqli, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                // Set cookies dengan status login dan username
                setcookie("status_login", "true", time() + 3600, "/");
                setcookie("username", $username, time() + 3600, "/");

                $updateSql = "UPDATE user SET sts_login = true WHERE username = '$username'";

                if (mysqli_query($mysqli, $updateSql)) {
                    // Redirect ke halaman dashboard atau halaman yang sesuai
                    header("Location: index.php");
                    exit();
                } else {
                    $loginStatus = '<div class="alert alert-danger" role="alert">Gagal memperbarui status login.</div>';
                }
            } else {
                $loginStatus = '<div class="alert alert-danger" role="alert">Password salah. Silakan coba lagi.</div>';
            }
        } else {
            $loginStatus = '<div class="alert alert-danger" role="alert">Username tidak ditemukan. Silakan coba lagi.</div>';
        }

        // Tutup koneksi database
        mysqli_close($mysqli);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php echo $loginStatus; // Menampilkan notifikasi login 
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for "password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
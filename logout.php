<?php
// Menghapus cookie "status_login" dan "username"
setcookie("status_login", "", time() - 3600, "/");
setcookie("username", "", time() - 3600, "/");

// Redirect ke halaman lain atau berikan pesan logout sukses
header("Location: index.php"); // Anda dapat mengarahkan ke halaman lain jika diperlukan
exit();
?>

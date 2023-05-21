<?php
        // oturumu başlat
        session_start();

        if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== false) {
                header('location: login.php');
                exit;
        }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
        <meta charset="UTF-8">
        <title>Hoşgeldin Kullanıcı</title>
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
        <style>
        .wrapper{
                width: 500px;
                padding: 20px;
        }
        .wrapper h2 {text-align: center}
        .wrapper form .form-group span {color: red;}
        </style>
</head>
<body>
        <main>
		<nav class="navbar navbar-expand-lg bg-body-tertiary">
			  <div class="container-fluid">
			    <a class="navbar-brand" href="#">Rol: Kullanıcı (<?php echo $_SESSION['username']; ?>)</a>
			    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			      <span class="navbar-toggler-icon"></span>
			    </button>
			  </div>
		</nav>

                <section class="container wrapper">
                        <div class="page-header">
                                <h2 class="display-5">hoşgeldiniz <?php echo $_SESSION['username']; ?></h2>
                        </div>
			<br><br><br><br><br>
                        <a href="password_reset.php" class="btn btn-block btn-outline-warning">Şifre Sıfırlama</a>
                        <a href="logout.php" class="btn btn-block btn-outline-danger">Çıkış</a>
                </section>
        </main>
</body>
</html>

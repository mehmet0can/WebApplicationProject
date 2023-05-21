<?php
// oturumu başlat
session_start();
 
// Kullanıcının giriş yapıp yapmadığını kontrol edin, değilse giriş sayfasına yönlendir
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: login.php');
    exit;
}
 
// konfigrasyon dosyası
require_once 'config/config.php';
 
// Boş degerleri doğrula
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';
 
// Form gönderildiğinde form verilerinin işlenmesi (get ile)
// düzenlendi (post ile)
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
    // yeni şifrenin geçerliliğini dogrula
    if(empty(trim($_POST['new_password']))){
        $new_password_err = 'Lütfen yeni şifrenizi giriniz.';     
    } elseif(strlen(trim($_POST['new_password'])) < 6){
        $new_password_err = 'Şifre en az 6 karakterden oluşmalıdır.';
    } else{
        $new_password = trim($_POST['new_password']);
    }
    
    // şifreyi onayla
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = 'Lütfen şifreyi onaylayın.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = 'Şifre eşleşmedi';
        }
    }
        
    // Veritabanını güncellemeden önce giriş hatalarını kontrol edin
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Bir güncelleme bildirimi hazırlayın
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        
        if($stmt = $mysql_db->prepare($sql)){
            // parametreleri ayarla
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Değişkenleri hazırlanan ifadeye parametre olarak bağlayın
            $stmt->bind_param("si", $param_password, $param_id);
            
            
            // Hazırlanan ifadeyi yürütme girişimi
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Birşeyler ters gitti tekrar deneyiniz.";
            }

            // durumu kapat
            $stmt->close();
        }

        // bağlantıyı kapat
        $mysql_db->close();
    }
}
?>
 
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre sıfırlama</title>
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/cosmo/bootstrap.min.css" rel="stylesheet" integrity="sha384-qdQEsAI45WFCO5QwXBelBe1rR9Nwiss4rGEqiszC+9olH1ScrLrMQr1KmDR964uZ" crossorigin="anonymous">
    <style type="text/css">
        .wrapper{ 
            width: 500px; 
            padding: 20px; 
        }
        .wrapper h2 {text-align: center}
        .wrapper form .form-group span {color: red;}
    </style>
</head>
<body>
    <main class="container wrapper">
        <section>
            <h2>Şifre sıfırlama</h2>
            <p>Parolanızı sıfırlamak için lütfen bu formu doldurun.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <label>Yeni şifre</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Şifre onaylama</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-block btn-primary" value="gönder">
                    <a class="btn btn-block btn-link bg-light" href="welcome.php">iptal et</a>
                </div>
            </form>
        </section>
    </main>    
</body>

</html>
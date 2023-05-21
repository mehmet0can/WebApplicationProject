// users isimli tablo oluşturmak için kullanılan sql create table komutu,
// bu komutta id, username ve password alanları oluşturularak veriler bu alana kaydedilecektir.
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

// Admin kullanıcısını oluşturmak için kullanılacak olan insert komutu
INSERT INTO users ('id', 'username', 'password') VALUES (1000, 'Admin', 'Admin123');

// Manager kullanıcısını oluşturmak için kullanılacak olan insert komutu
INSERT INTO users ('id', 'username', 'password') VALUES (1001, 'Manager', 'Manager123');

// User kullanıcısını oluşturmak için kullanılacak olan insert komutu
INSERT INTO users ('id', 'username', 'password') VALUES (1002, 'User', 'User123');

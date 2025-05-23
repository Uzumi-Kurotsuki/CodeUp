<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_or_email) || empty($password)) {
        $errors[] = "Username/Email dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :ue OR email = :ue LIMIT 1");
        $stmt->execute(['ue' => $username_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Username/Email atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/jpeg" href="photos/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style/style_login.css" />
  <title>CodeUP</title>
</head>
<body>
  <div id="bintang-container"></div>

  <div class="hero">
  <!-- Navbar -->
  <nav>
    <div class="nav-left">
      <a href="index.php">Beranda</a>
      <a href="#tentang-kami">Tentang Kami</a>
      <a href="#Kontak">Kontak</a>
    </div>
    <div class="nav-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-logo" title="<?= htmlspecialchars($_SESSION['username']) ?>" onclick="window.location.href='profile.php'"></div>
        <form method="POST" action="logout.php" style="display:inline;">
          <button type="submit" class="btn-logout">Logout</button>
        </form>
        <button id="toggle-music" style="background: none; border: none; cursor: pointer;">
          <img id="music-icon" src="photos/play-icon.png" alt="Play Music" style="width: 40px; height: 40px;">
        </button>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </nav>
  <audio id="background-music" loop>
    <source src="Sound/The Deli - Day in the life - Mr Microverse.mp3" type="audio/mpeg">
    Browser Anda tidak mendukung pemutar audio.
  </audio>
  <script>
    const music = document.getElementById('background-music');
    const toggleBtn = document.getElementById('toggle-music');
    const musicIcon = document.getElementById('music-icon');
    let isPlaying = false;

    if (toggleBtn && music && musicIcon) {
      toggleBtn.addEventListener('click', () => {
        if (isPlaying) {
          music.pause();
          musicIcon.src = 'photos/play-icon.png';
        } else {
          music.play().catch(error => {
            console.error('Error playing audio:', error);
          });
          musicIcon.src = 'photos/pause-icon.png';
        }
        isPlaying = !isPlaying;
      });
    }
  </script>

    <h1 id="typewriter"></h1>
    <div class="info">
      <img src="photos/komputer.png" alt="komputer" />
      <div class="bubble">
        <strong>Masuk untuk</strong>
        mengakses akun Anda :)
      </div>
    </div>
    <div class="card">
      <?php if (!empty($errors)): ?>
        <ul class="error" style="color: #ff6b6b; text-align: left; margin-bottom: 1em;">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <input type="text" name="username_or_email" placeholder="Username atau Email" required value="<?= htmlspecialchars($_POST['username_or_email'] ?? '') ?>" />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit"><h4>Masuk</h4></button>
      </form>
      <div class="Reg">
        <a href="register.php">Belum punya akun? Daftar di sini</a>
      </div>
    </div>
  </div>

  <script>
    // Star blinking animation
    window.onload = () => {
      const container = document.getElementById('bintang-container');
      for (let i = 0; i < 150; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        const size = Math.random() * 5 + 1;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        star.style.top = `${Math.random() * 100}%`;
        star.style.left = `${Math.random() * 100}%`;
        star.style.opacity = Math.random();
        star.style.animation = `blink ${Math.random() * 2 + 1}s infinite`;
        container.appendChild(star);
      }
    };

    // Typing animation
    const text = "CodeUP";
    const target = document.getElementById("typewriter");
    let index = 0;

    function type() {
      if (index < text.length) {
        target.innerHTML += text.charAt(index);
        index++;
        setTimeout(type, 150);
      }
    }

    type();
  </script>
</body>
</html>

<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/jpeg" href="photos/logo.jpg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <title>CodeUp</title>
  <link rel="stylesheet" href="style/style_index.css" />
</head>
<body>
  <audio id="bg-music" loop style="display:none;">
    <source src="sound/The Deli - Day in the life - Mr Microverse.mp3" type="audio/mpeg">
  </audio>
  <nav>
    <div class="nav-left">
      <a href="index.php">Beranda</a>
      <a href="#" id="course-link">Kursus</a>
      <a href="#tentang-kami">Tentang Kami</a>
      <a href="#Kontak">Kontak</a>
    </div>
    <div class="nav-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <?php
          // Fetch user profile picture from database
          $stmt = $pdo->prepare("SELECT active_profile_pic FROM users WHERE id = ?");
          $stmt->execute([$_SESSION['user_id']]);
          $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

          // Fetch photo name from shop_items for active_profile_pic
          $profilePic = 'photos/default-avatar.gif';
          if ($userProfile && $userProfile['active_profile_pic']) {
              $photoStmt = $pdo->prepare("SELECT name FROM shop_items WHERE id = ?");
              $photoStmt->execute([$userProfile['active_profile_pic']]);
              $photo = $photoStmt->fetch(PDO::FETCH_ASSOC);
              if ($photo) {
                  $profilePic = 'photos/' . $photo['name'];
              }
          }
        ?>
        <div class="profile-logo" title="<?= htmlspecialchars($_SESSION['username']) ?>" onclick="window.location.href='profile.php'" style="background-image: url('<?= htmlspecialchars($profilePic) ?>');"></div>
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

    // Add event listener to course link to require login
    const courseLink = document.getElementById('course-link');
    if (courseLink) {
      courseLink.addEventListener('click', (e) => {
        e.preventDefault();
        const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
        if (isLoggedIn) {
          window.location.href = 'course.php';
        } else {
          // Show the card box effect modal for login prompt
          const cardBoxEffect = document.getElementById('card-box-effect');
          const overlay = document.getElementById('overlay');
          if (cardBoxEffect && overlay) {
            cardBoxEffect.style.display = 'block';
            overlay.style.display = 'block';
          }
        }
      });
    }
  </script>
  <style>
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #111;
      color: white;
      padding: 10px 20px;
      font-family: 'Press Start 2P', cursive;
      border-bottom: 2px solid #00f;
    }

    .nav-left a,
    .nav-right a {
      color: #fff;
      text-decoration: none;
      margin-right: 20px;
      font-size: 14px;
    }

    .nav-left a:hover,
    .nav-right a:hover {
      color: #0ff;
    }

    .nav-left {
      display: flex;
      gap: 15px;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .btn-logout {
      font-family: 'Press Start 2P', cursive;
      font-size: 12px;
      padding: 5px 10px;
      background: red;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .profile-logo {
      width: 40px;
      height: 40px;
      background-image: url('photos/default-avatar.gif');
      background-size: cover;
      background-position: center;
      border-radius: 50%;
      cursor: pointer;
    }
  </style>
  <section id="beranda">
  <div class="hero">
    <h1>LEVEL UP<br>WITH CODEUP</h1>
    <p class="subtext">The Journey To Becoming a Better Programmer ✨</p>
    <button class="btn-start" id="get-started-btn">Get Started</button>
  </div>
  </section>
  <!-- Card box effect container -->
<div id="overlay" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.7); z-index: 10999;"></div>
<div id="card-box-effect" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 360px; background-color: #003366; border: 8px solid #FFD700; border-radius: 12px; box-shadow: 0 0 20px 5px #FFD700; padding: 20px; z-index: 11000; text-align: center; color: #fff;">
  <img src="photos/0426-ezgif.com-video-to-gif-converter.gif" alt="Effect GIF" style="max-width: 100%; border-radius: 12px; margin-bottom: 15px;" />
  <h2>Welcome to CodeUp!</h2>
  <p>Start your coding journey with us. Please login or register to access courses.</p>
  <button id="sign-in-btn" style="background-color: #FFD700; color: #000; border: none; padding: 10px 20px; font-size: 0.9em; cursor: pointer; border-radius: 8px; box-shadow: 2px 2px #444; margin-right: 10px; transition: background-color 0.3s ease;">Sign In</button>
  <button id="close-card-btn" style="background-color: #FFD700; color: #000; border: none; padding: 10px 20px; font-size: 0.9em; cursor: pointer; border-radius: 8px; box-shadow: 2px 2px #444; transition: background-color 0.3s ease;">Close</button>
</div>

  <!-- TENTANG KAMI -->
  <section id="tentang-kami" class="courses-container" style="position: relative; overflow: hidden; background-color: #0b1a3d; border: 4px solid #000000; border-radius: 12px; box-shadow: 0 0 10px 3px rgba(0,0,0,0.8);">
    <div id="bintang-container"></div>

    <!-- Tambahan Parallax -->
    <div class="parallax-container">
      <img src="photos/—Pngtree—three-dimensional 3d moon planet universe_13249268(1).png" class="parallax moon" alt="Moon">
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud1" alt="Cloud 1">
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud2" alt="Cloud 2">
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud3" id="cloud3" alt="Cloud 3">
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud4" id="cloud4" alt="Cloud 4">
      <!-- Cloud kiri bawah -->
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud5" id="cloud5" alt="Cloud 5">

      <!-- Cloud kanan bawah -->
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud6" id="cloud6" alt="Cloud 6">

      <!-- Cloud tengah bawah -->
      <img src="photos/—Pngtree—3d cloud_7360117.png" class="parallax cloud7" id="cloud7" alt="Cloud 7">

    </div>
        <!-- Tambahkan di dalam <section id="tentang-kami"> -->

    <div style="position: relative; z-index: 2; width: 100%; display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; padding-top: 100px; padding-bottom: 120px;">
      <div class="course-card">
        <h2>Misi Kami</h2>
        <img src="https://via.placeholder.com/400x240?text=Misi+Kami" alt="">
        <p>Membuat pembelajaran coding jadi menyenangkan dan mudah dimengerti untuk semua kalangan.</p>
        <div class="level">Beginner Friendly</div>
      </div>

      <div class="course-card">
        <h2>Tim Kami</h2>
        <img src="https://via.placeholder.com/400x240?text=Tim+Kami" alt="">
        <p>Terdiri dari developer, desainer, dan pengajar berpengalaman di bidang teknologi dan edukasi.</p>
        <div class="level">Experienced Team</div>
      </div>

      <div class="course-card">
        <h2>Visi Kami</h2>
        <img src="https://via.placeholder.com/400x240?text=Visi+Kami" alt="">
        <p>Menjadi platform edukasi teknologi nomor satu dengan pendekatan gamifikasi.</p>
        <div class="level">Future Ready</div>
      </div>
    </div>
  </section>

  <!-- KONTAK KAMI -->
   <section id="Kontak">
  <footer style="background-color: #000c1f; padding: 60px 20px 30px; color: #fff; font-size: 0.75em;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; max-width: 1200px; margin: auto;">
      <div style="flex: 1 1 200px; margin: 20px;">
        <h2 style="color: #86a72c; margin-bottom: 15px;">Practice</h2>
        <p><a href="#" style="color: #7FDBFF; text-decoration: none;">Challenges</a></p>
        <p><a href="#" style="color: #7FDBFF; text-decoration: none;">Projects</a></p>
      </div>

      <div style="flex: 1 1 200px; margin: 20px;">
        <h2 style="color: #86a72c; margin-bottom: 15px;">Learn</h2>
        <p><a href="#" style="color: #7FDBFF; text-decoration: none;">HTML</a></p>
        <p><a href="#" style="color: #7FDBFF; text-decoration: none;">Python</a></p>
        <p><a href="#" style="color: #7FDBFF; text-decoration: none;">JavaScript</a></p>
      </div>

      <div style="flex: 1 1 200px; margin: 20px;">
        <h2 style="color: #86a72c; margin-bottom: 15px;">Kontak Kami</h2>
        <p>Email: <a href="mailto:support@codeup.com" style="color: #7FDBFF; text-decoration: none;">support@codeup.com</a></p>
        <p>Telepon: <a href="tel:+628123456789" style="color: #7FDBFF; text-decoration: none;">+62 812 3456 789</a></p>
        <p>Alamat: <span style="color: #7FDBFF;">Jl. Pixel Art No.88, Jakarta</span></p>
      </div>
    </div>
    <div style="text-align: center; margin-top: 40px; font-size: 0.7em; color: #7FDBFF;">
      Made with ❤️ in Indonesia | © 2025 CodeUp
    </div>
  </footer>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const audio = document.getElementById('bg-music');
      const startButton = document.querySelector('.btn-start');
      const toggleMusicButton = document.getElementById('toggle-music');
      const musicIcon = document.getElementById('music-icon');
      let isPlaying = false;
      toggleMusicButton.addEventListener('click', function() {
        if (isPlaying) {
          audio.pause();
          musicIcon.src = 'photos/play-icon.png';
        } else {
          audio.play().catch(error => {
            console.log('Audio play prevented:', error);
          });
          musicIcon.src = 'photos/pause-icon.png';
        }
        isPlaying = !isPlaying;
      });
      audio.addEventListener('play', function() {
        isPlaying = true;
        musicIcon.src = 'photos/pause-icon.png';
      });
      audio.addEventListener('pause', function() {
        isPlaying = false;
        musicIcon.src = 'photos/play-icon.png';
      });

      // Script untuk bintang kelap kelip
      const container = document.getElementById('bintang-container');
      for (let i = 0; i < 100; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';
        star.style.width = Math.random() * 3 + 2 + 'px';
        star.style.height = star.style.width;
        star.style.position = 'absolute';
        star.style.backgroundColor = '#fff';
        star.style.borderRadius = '50%';
        star.style.opacity = Math.random();
        star.style.animation = `blink ${Math.random() * 2 + 1}s infinite`;
        container.appendChild(star);
      }
    });

    // Script buat awan & bulan parallax
    const moon = document.getElementById('moon');
    const cloud1 = document.getElementById('cloud1');
    const cloud2 = document.getElementById('cloud2');
    const cloud3 = document.getElementById('cloud3');
    const cloud4 = document.getElementById('cloud4');
    const cloud5 = document.getElementById('cloud5');
    const cloud6 = document.getElementById('cloud6');
    const cloud7 = document.getElementById('cloud7');

    document.addEventListener('scroll', function() {
      let value = window.scrollY;
      
      // Moon turun pelan
      moon.style.transform = `translate(-50%, ${value * 0.2}px)`;

      // Cloud 1 - kiri atas
      cloud1.style.left = (5 + value * 0.05) + '%';
      cloud1.style.top = (120 - value * 0.2) + 'px';
      cloud1.style.opacity = 1 - value / 600;

      // Cloud 2 - kanan atas
      cloud2.style.right = (5 + value * 0.05) + '%';
      cloud2.style.top = (170 - value * 0.2) + 'px';
      cloud2.style.opacity = 1 - value / 600;

      // Cloud 3 - kiri bawah
      cloud3.style.left = (15 + value * 0.04) + '%';
      cloud3.style.top = (60 - value * 0.15) + 'px';
      cloud3.style.opacity = 1 - value / 600;

      // Cloud 4 - kanan bawah
      cloud4.style.right = (15 + value * 0.04) + '%';
      cloud4.style.top = (220 - value * 0.15) + 'px';
      cloud4.style.opacity = 1 - value / 600;

      // Cloud 5 - kiri bawah baru
      cloud5.style.left = (10 + value * 0.03) + '%';
      cloud5.style.bottom = (30 + value * 0.1) + 'px';
      cloud5.style.opacity = 1 - value / 600;

      // Cloud 6 - kanan bawah baru
      cloud6.style.right = (10 + value * 0.03) + '%';
      cloud6.style.bottom = (30 + value * 0.1) + 'px';
      cloud6.style.opacity = 1 - value / 600;

      // Cloud 7 - tengah bawah baru
      cloud7.style.left = `calc(50% - 50px)`; // posisi tengah (kira2)
      cloud7.style.bottom = (20 + value * 0.12) + 'px';
      cloud7.style.opacity = 1 - value / 600;
    });

  document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    const getStartedBtn = document.getElementById('get-started-btn');
    const cardBoxEffect = document.getElementById('card-box-effect');
    const overlay = document.getElementById('overlay');
    const closeCardBtn = document.getElementById('close-card-btn');
    const signInBtn = document.getElementById('sign-in-btn');

    getStartedBtn.addEventListener('click', function(event) {
      if (!isLoggedIn) {
        event.preventDefault();
        cardBoxEffect.style.display = 'block';
        overlay.style.display = 'block';
      } else {
        window.location.href = 'course.php';
      }
    });

    closeCardBtn.addEventListener('click', function() {
      cardBoxEffect.style.display = 'none';
      overlay.style.display = 'none';
    });

    overlay.addEventListener('click', function() {
      cardBoxEffect.style.display = 'none';
      overlay.style.display = 'none';
    });

    signInBtn.addEventListener('click', function() {
      window.location.href = 'login.php';
    });
  });

  </script>
</body>
</html>

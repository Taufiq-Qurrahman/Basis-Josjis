<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Tehkota</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
</head>
<body>

    <div class="login-container">
        <h2>Login Tehkota</h2>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('auth/proses_login') ?>" method="POST" id="loginForm">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" id="btn-login">Masuk Sistem</button>
        </form>
    </div>

    <script src="<?= base_url('assets/js/auth.js') ?>"></script>
</body>
</html>
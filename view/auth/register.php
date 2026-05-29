<?php 
// File: view/auth/register.php


$errors = $errors ?? [];
$old = $old ?? [];


// 1. Include your existing top header template if your app uses one
//include BASE_PATH . '/view/inc/header.php';
?>

<style>
:root {
    --primary-color: #2196F3;
    --primary-hover: #1e88e5;
    --error-color: #e53935;
    --text-color: #333333;
    --input-border: #cccccc;
    --bg-light: #f9f9f9;
}

.register-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
    padding: 40px 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.register-card {
    background: #ffffff;
    width: 100%;
    max-width: 450px;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.register-title {
    margin: 0 0 24px 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--text-color);
    text-align: center;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #555555;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    font-size: 15px;
    border: 1px solid var(--input-border);
    border-radius: 6px;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15);
}

.form-control.is-invalid {
    border-color: var(--error-color);
    background-color: #fff8f8;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
}

.error-feedback {
    color: var(--error-color);
    margin: 6px 0 0 0;
    font-size: 13px;
    font-weight: 500;
}

.btn-submit {
    width: 100%;
    padding: 14px;
    margin-top: 10px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-submit:hover {
    background: var(--primary-hover);
}
</style>

<div class="section page-wrapper register-wrapper">
    <div class="register-card">

        <h2 class="register-title">Create Account</h2>

        <form action="<?= BASE_URL ?>/Public/index.php?page=register" method="POST">

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"
                    class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="John Doe">
                <?php if (!empty($errors['name'])): ?>
                <p class="error-feedback"><?= $errors['name'] ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    class="form-control <?= !empty($errors['email'] ?? null) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="you@example.com">
                <?php 
                if (!empty($errors['email'])): ?>
                <p class="error-feedback"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" placeholder="••••••••">
                <?php if (!empty($errors['password'])): ?>
                <p class="error-feedback"><?= $errors['password'] ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="form-control <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                    placeholder="••••••••">
                <?php if (!empty($errors['confirm_password'])): ?>
                <p class="error-feedback"><?= $errors['confirm_password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-submit">
                Register
            </button>
        </form>

    </div>
</div>

<?php 
// 2. Include your existing footer template
//include BASE_PATH . '/view/inc/footer.php'; 
?>
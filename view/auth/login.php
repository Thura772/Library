<?php require BASE_PATH . '/view/Layout/header.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);
?>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }

    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
    }

    .login-card {
        width: 380px;
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .login-card h1 {
        margin-bottom: 25px;
        font-size: 26px;
        color: #333;
    }

    .input-group {
        margin-bottom: 18px;
        text-align: left;
    }

    .input-group input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        font-size: 14px;
        box-sizing: border-box;
    }

    .input-group input:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
    }

    .error {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #4a90e2, #357abd);
        border: none;
        color: white;
        font-size: 15px;
        border-radius: 8px;
        cursor: pointer;
    }

    button:hover {
        transform: translateY(-1px);
    }

    .extra-text {
        margin-top: 15px;
        font-size: 13px;
    }

    .extra-text a {
        color: #4a90e2;
        text-decoration: none;
    }
</style>

<div class="form-container">

    <div class="login-card">

        <h1>Welcome Back</h1>

        <form method="POST" action="<?= BASE_URL ?>/Public/index.php?page=login">

            <!-- EMAIL -->
            <div class="input-group">

                <input type="email"
                    name="email"
                    placeholder="Email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>">

                <?php if (!empty($errors['email'])): ?>
                    <div class="error">
                        <?= htmlspecialchars($errors['email']) ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- PASSWORD -->
            <div class="input-group">

                <input type="password"
                    name="password"
                    placeholder="Password">

                <?php if (!empty($errors['password'])): ?>
                    <div class="error">
                        <?= htmlspecialchars($errors['password']) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['general'])): ?>
                    <div class="error">
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

            </div>

            <button type="submit">
                Login
            </button>

        </form>

        <div class="extra-text">
            Don't have an account?
            <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                Register
            </a>
        </div>

    </div>

</div>

<?php require BASE_PATH . '/view/Layout/footer.php'; ?>
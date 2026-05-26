<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Media Library') ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/Public/css/style.css">
</head>

<body>

    <div class="page-container">
        <div class="content">

            <header class="header">
                <div class="wrapper">


                    <!-- LOGO (HOME / RANDOM) -->
                    <h1 class="logo">
                        <a href="<?= BASE_URL ?>/Public/index.php?page=random">
                            <img src="<?= BASE_URL ?>/Public/img/Brand-title.png" alt="Media Library">
                        </a>
                    </h1>

                    <!-- NAVIGATION -->
                    <ul class="nav">

                        <!-- BOOKS -->
                        <li class="<?= ($section === 'books') ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=books">
                                <img src="<?= BASE_URL ?>/Public/img/book.png"> Books
                            </a>
                        </li>

                        <!-- MOVIES -->
                        <li class="<?= ($section === 'movies') ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=movies">
                                <img src="<?= BASE_URL ?>/Public/img/movie.png"> Movies
                            </a>
                        </li>

                        <!-- MUSIC -->
                        <li class="<?= ($section === 'music') ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=catalog&cat=music">
                                <img src="<?= BASE_URL ?>/Public/img/music.png"> Music
                            </a>
                        </li>

                        <!-- SUGGEST -->


                        <!-- REGISTER -->


                        <!-- SUGGEST -->
                        <li class="<?= ($page ?? '') === 'suggest' ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=suggest">
                                <img src="<?= BASE_URL ?>/Public/img/suggestion.png"> Suggest
                            </a>
                        </li>

                        <?php if (!empty($_SESSION['user'])): ?>

                        <!-- USER NAME -->
                        <li>
                            <a href="javascript:void(0);">

                                <?= htmlspecialchars($_SESSION['user']['name']); ?>
                            </a>
                        </li>

                        <!-- LOGOUT -->
                        <li>
                            <a href="<?= BASE_URL ?>/Public/index.php?page=logout">

                                Logout
                            </a>
                        </li>

                        <?php else: ?>

                        <!-- LOGIN -->
                        <li class="<?= ($page ?? '') === 'login' ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=login">
                                Login
                            </a>
                        </li>

                        <!-- REGISTER -->
                        <li class="<?= ($page ?? '') === 'register' ? 'on' : '' ?>">
                            <a href="<?= BASE_URL ?>/Public/index.php?page=register">
                                Register
                            </a>
                        </li>

                        <?php endif; ?>

                    </ul>

                </div>
            </header>

            <!-- SEARCH BAR -->
            <?php if (empty($hideSearch)): ?>
            <div class="search">
                <div class="wrapper">
                    <form method="get" action="<?= BASE_URL ?>/Public/index.php">

                        <input type="hidden" name="page" value="catalog">

                        <?php if (!empty($section)): ?>
                        <input type="hidden" name="cat" value="<?= htmlspecialchars($section) ?>">
                        <?php endif; ?>

                        <label for="s">Search:</label>
                        <input type="text" name="s" id="s">
                        <input type="submit" value="Go">

                    </form>
                </div>
            </div>
            <?php endif; ?>

            <main id="content">
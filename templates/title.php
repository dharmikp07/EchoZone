<div style="floatL right" ;>
    <?php if (isLoggedIn()): ?>
        Hello
        <?php echo htmlEscape(getAuthUser()) ?>.
        <a href="logout.php">Log out</a>
    <?php else: ?>
        <a href="login.php">Log in</a>
    <?php endif ?>
</div>
<a href="index.php">
    <h1>Echo Zone Blog</h1>
</a>
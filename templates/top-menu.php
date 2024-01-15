<div class="top-menu">
    <div class="menu-options">
        <?php if (isLoggedIn()): ?>
            <div class="btn btn-outline-secondary say-hello">
                Hello
                <?php echo htmlEscape(getAuthUser()) ?>
            </div>
            <div class="btn btn-primary">
                <a href="index.php">Home</a>
            </div>

            <div class="btn btn-primary">
                <a href="list-posts.php">All posts</a>
            </div>

            <div class="btn btn-primary">
                <a href="edit-post.php">New post</a>
            </div>

            <div class="btn btn-danger">
                <a href="logout.php">Log out</a>
            </div>

        <?php else: ?>
            <div class="btn btn-primary">
                <a href="login.php">Log in</a>
            </div>
        <?php endif ?>
    </div>
</div>
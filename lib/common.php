<?php
/**
 * Gets the root path of the project
 * 
 * @return string   
 */
function getRootPath()
{
    return realpath(__DIR__ . '/..');
}

/**
 * Gets the full path for the database file
 * 
 * @return string
 */
function getDatabasePath()
{
    return getRootPath() . '/data/data.sqlite';
}

/**
 * Gets the DSN for the SQLite connection
 * 
 * @return string
 */
function getDsn()
{
    return 'sqlite:' . getDatabasePath();
}

/**
 * Gets the PDO object for database access
 * 
 * @return \PDO
 */
function getPDO()
{
    return new PDO(getDsn());
}

/**
 * Escape HTML so it is safe to output
 * 
 * @param string $html
 * @return string
 */
function htmlEscape($html)
{
    return htmlspecialchars($html, ENT_HTML5, 'UTF-8');
}

function convertSqlDate($sqlDate)
{
    /* @var $date DateTime */
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $sqlDate);

    if ($date === false) {
        return "Invalid Date";
    }
    return $date->format('d M Y, H:m:s');
}

/**
 * Returns the number of comments for the specified post
 * 
 * @param integer $postId
 * @return integer
 */
function countCommentsForPost($postId)
{
    $pdo = getPDO();
    $sql = "
        SELECT
            COUNT(*) AS c
        FROM
            comment
        WHERE 
            post_id = :post_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('post_id' => $postId, )
    );

    return (int) $stmt->fetchColumn();
}

function getSqlDateForNow()
{
    return date('Y-m-d H:i:s');
}

/**
 * Converts unsafe text to safe, paragraphed, HTML
 * 
 * @param string $text
 * @return string
 */
function convertNewLinesToParagraph($text)
{
    $escaped = htmlEscape($text);
    return '<p>' . str_replace("\n", "</p><p>", $escaped) . '</p>';
}

function redirectAndExit($script)
{
    // Get the domain-relative URL (e.g. /blog/whatever.php or /whatever.php) and work
    // out the folder (e.g. /blog/ or /).
    $relativeUrl = $_SERVER['PHP_SELF'];
    $urlFolder = substr($relativeUrl, 0, strrpos($relativeUrl, '/') + 1);

    // Redirect to the full URL (http://myhost/blog/script.php)
    $host = $_SERVER['HTTP_HOST'];
    $fullUrl = 'http://' . $host . $urlFolder . $script;
    header('Location: ' . $fullUrl);
    exit();
}

/**
 * Returns all the comments for the specified post
 * 
 * @param integer $postId
 * 
 */
function getCommentsForPost($postId)
{
    $pdo = getPDO();
    $sql = "
        SELECT
            id, name, text, created_at, website
        FROM
            comment
        WHERE 
            post_id = :post_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array("post_id" => $postId));

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Try to Log in
 * 
 * @param PDO $pdo
 * @param string $username
 * @param string $password
 * @return bool $success
 */
function tryLogin(PDO $pdo, $username, $password)
{
    $sql = "
        SELECT
            password
        FROM
            user
        WHERE
            username = :username
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array('username' => $username)
    );

    // Get the hash from this row, and use the third-party hashing library to check it
    $hash = $stmt->fetchColumn();
    $success = password_verify($password, $hash);

    return $success;
}

/**
 * Logs the user in
 * 
 * For safety, we ask PHP to generate the cookie, so if a user logs onto a site that a cracker
 * has prepared for him (e.g. on a public PC) the cracker's copy of the cookie ID will be useless.
 * 
 * @param string $username
 */
function login($username)
{
    // Regenerate the session ID to prevent session fixation attacks
    session_regenerate_id();

    // Store the username in the session to indicate that the user is logged in
    $_SESSION['logged_in_username'] = $username;
}

/**
 * Logs the user out
 */
function logout()
{
    unset($_SESSION['logged_in_username']);
}

function getAuthUser()
{
    return isLoggedIn() ? $_SESSION['logged_in_username'] : null;
}

function isLoggedIn()
{
    return isset($_SESSION['logged_in_username']);
}
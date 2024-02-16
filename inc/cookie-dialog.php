<?php
// cookie logic just for display, the website doesnt actually collect cookies
// this will just be a hollow prompt with no functionality
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// session_unset();

if (isset($_POST['cookie_accepted'])) {
    $_SESSION['cookie_accepted'] = $_POST['cookie_accepted'] === 'true';
}

// Check if the user has already accepted the cookie
if (isset($_SESSION['cookie_accepted']) && $_SESSION['cookie_accepted']) {
    // Cookie has been accepted do something
} else {
    // Cookie has not yet been interacted, show the dialog
?>

    <div id="cookie-dialog">
        <p>This website uses cookies. By using this website, you agree to our use of <a href="cookie-policy.php">cookies</a>.</p>
        <div class="cookie-buttons">
            <button onclick="acceptCookie()">Accept</button>
            <button onclick="acceptNecessaryCookie()">Accept Necessary</button>
            <button onclick="disagreeCookie()">Disagree</button>
        </div>
    </div>

    <script>
        function disagreeCookie() {
            // ?php $_SESSION['cookie_accepted'] = false; ?>
            setSessionCookie(false);
            document.getElementById('cookie-dialog').style.display = 'none';
            // window.location.href = 'cookie_disagree.php';
        }

        function acceptCookie() {
            // ?php $_SESSION['cookie_accepted'] = true; ?>
            setSessionCookie(true);
            document.getElementById('cookie-dialog').style.display = 'none';
        }

        function acceptNecessaryCookie() {
            // ?php $_SESSION['cookie_accepted'] = true; ?>
            setSessionCookie(true);
            document.getElementById('cookie-dialog').style.display = 'none';
        }

        function setSessionCookie(accepted) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'inc/cookie-dialog.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Session cookie updated successfully');
                } else {
                    console.log('Failed to update session cookie');
                }
            };
            xhr.send('cookie_accepted=' + accepted);
        }
    </script>

    <link rel="stylesheet" href="assets/css/cookie-dialog.css">
<?php
}
?>
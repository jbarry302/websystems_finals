<script src="https://kit.fontawesome.com/a38ed59c98.js" crossorigin="anonymous"></script>

<?php
require_once('config/db.php');
require_once('config/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$qs = "
    SELECT
        ni.id AS nav_item_id,
        ni.readable_name,
        ni.technical_name,
        ni.url,
        ni.hidden,
        GROUP_CONCAT(p.code SEPARATOR ',') AS permissions
    FROM
        nav_items AS ni
        LEFT JOIN nav_item_permissions AS nip ON ni.id = nip.nav_item_id
        LEFT JOIN permissions AS p ON nip.permission_id = p.id
    GROUP BY
        ni.id;
";


$q = mysqli_query($conn, $qs);
$navItems = mysqli_num_rows($q) > 0 ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];

?>

<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">
            <img src="assets/imgs/329079.svg" height="45" width="45" />
            AcademiaHub
        </a>
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="menu">
            <hr>
            <?php foreach ($navItems as $item) : ?>
                <?php
                $baseUrlName = basename(parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH));
                $isActive = strcmp($baseUrlName, $item['url']) === 0;
                if ($item['hidden'] == 'true') {
                    continue;
                } 
                if (isset($item['permissions'])) {
                    $permissions = explode(',', $item['permissions']);
                    $allowed = [];
                    foreach ($permissions as $permission) {
                        if($permission == 0 && isset($_SESSION['email'])){
                            $allowed[] = true;
                        } else if ($permission == 1 && isset($_SESSION['email']) && $_SESSION['role'] === 'student') {
                            $allowed[] = true;
                        } else if ($permission == 2 && isset($_SESSION['email']) && $_SESSION['role'] === 'admin') {
                            $allowed[] = true;
                        } else {
                            $allowed[] = false;
                        }
                    }
                    if (in_array(false, $allowed)) {
                        continue;
                    }
                } ?>
                <li class="nav-item<?= $isActive ? ' active' : ' ' ?>"><a href="<?= $item['url']; ?>"><?= $item['readable_name']; ?></a></li>
                <hr>
            <?php endforeach; ?>

            <li>
                <?php if (isset($_SESSION['email'])) : ?>
                    <div class="user-card">
                        <div class="profile-image" onclick="toggleUserMenu()">
                            <img src="https://via.placeholder.com/40" alt="User Profile">
                        </div>
                        <div class="user-menu-small">
                            <span class="email"><?php echo $_SESSION['email']; ?></span>
                            <a href="edit-profile.php" class="special-btn edit-profile-btn">Edit Profile</a>
                            <a href="logout.php" class="special-btn logout-btn">Logout</a>
                            <a href="account-deletion.php" class="special-btn  delete-account-btn">Delete Account</a>
                        </div>
                        <div id="user-menu" class="user-menu">
                            <span class="email"><?php echo $_SESSION['email']; ?></span>
                            <a href="edit-profile.php" class="special-btn edit-profile-btn">Edit Profile</a>
                            <a href="logout.php" class="special-btn logout-btn">Logout</a>
                            <a href="account-deletion.php" class="special-btn delete-account-btn">Delete Account</a>
                        </div>
                    </div>
                <?php else : ?>
                    <a href="login.php">Sign in<i class="fa-solid fa-arrow-right fa-shake" style="margin-left: 5px;"></i></a>
                <?php endif; ?>
            </li>

        </ul>
    </div>
</nav>

<link rel="stylesheet" href="assets/css/header.css">
<script src="script.js"></script>
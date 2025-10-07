<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Site</title>
  <link rel="stylesheet" href="style.css">  

<div class="navbar">  
     <div class="nav-left"></div>

    <div class="nav-center">
        <a href="index.php">Home</a>
        <a href="list_programs.php">List Programs</a>
        <?php if (empty($_SESSION['user_id'])): ?>
            <a href="login.php">Manage Programs</a>
        <?php else: ?>
            <a href="user_requests.php"
               class="<?= basename($_SERVER['PHP_SELF']) == 'manage_program.php' ? 'active' : '' ?>">
               Manage Programs
            </a>
        <?php endif; ?>
    </div>

    <div class="user-menu">
        <?php if (empty($_SESSION['user_id'])): ?>
            <!-- Not logged in: clicking goes to login -->
            <div class="user-circle" onclick="window.location.href='login.php'">U</div>
        <?php else: ?>
            <!-- Logged in: show first letter in circle + username next to it -->
            <div class="user-info" onclick="toggleMenu()">
                <div class="user-circle">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        <?php endif; ?>

        <div class="dropdown" id="userDropdown">
            <a href="request_status.php">Request Status</a>
            
            <a href="#" onclick="openLogoutModal()">Logout</a>
            <a href="change_password.php">Change Password</a>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to logout?</h3>
        <div class="modal-buttons">
            <button onclick="confirmLogout()">Yes, Logout</button>
            <button onclick="closeLogoutModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
    
.navbar {
    background-color: #2c3e50;
    padding: 12px 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.nav-center {
    flex: 1;
    display: flex;
    justify-content: center;
}
.nav-center a {
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    font-weight: 500;
}


.user-menu {
    position:relative;
}


.user-circle {
    width: 40px;
    height: 40px;
    background: #527ad0ff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
}


.user-info {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}
.user-name {
    color: white;
    font-weight: 500;
    font-size: 14px;
}

.dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 50px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    min-width: 160px;
}
.dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
}
.dropdown a:hover {
    background: #f0f0f0;
}
.show { display: block !important; }

.modal {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}
.modal-content {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    max-width: 350px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.modal-content h3 {
    margin-bottom: 20px;
    color: #333;
}
.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}
.modal-buttons button {
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}
.modal-buttons button:first-child {
    background: #2563eb;
    color: white;
}
.modal-buttons button:first-child:hover {
    background: #1e4ed8;
}
.modal-buttons button:last-child {
    background: #ccc;
    color: #333;
}
.modal-buttons button:last-child:hover {
    background: #b3b3b3;
}
</style>

<script>
function toggleMenu() {
    document.getElementById("userDropdown").classList.toggle("show");
}
window.onclick = function(event) {
    if (!event.target.closest(".user-menu")) {
        document.getElementById("userDropdown").classList.remove("show");
    }
}

function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
    document.getElementById("userDropdown").classList.remove("show"); // close dropdown
}
function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}
function confirmLogout() {
    window.location.href = "logout.php"; // redirect to logout page
}
</script>

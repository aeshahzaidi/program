
<nav class="navbar">


<div class="nav-left">
  <div class="admin-dropdown">
    <span class="logo dropbtn" onclick="toggleAdminDropdown()">Admin Panel &#9662;</span>
    <div class="dropdown" id="adminDropdown">
      <a href="program_requests.php" class="<?= basename($_SERVER['PHP_SELF']) == 'user_requests.php' ? 'active' : '' ?>">List of Requests</a>
      <a href="change_password.php">Change Password</a>
      <a href="#" onclick="openLogoutModal()">Logout</a>
    </div>
  </div>
</div>

    
  </div>

  <div class="nav-center">
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
    <a href="manage_program.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_program.php' ? 'active' : '' ?>">Manage Programs</a>
    <a href="list_programs.php" class="<?= basename($_SERVER['PHP_SELF']) == 'list_programs.php' ? 'active' : '' ?>">List Programs</a>
  </div>

  
    
</nav>


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
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #ffffff;
  padding: 0 30px;
  height: 60px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  font-family: 'Segoe UI', sans-serif;
}


.admin-dropdown {
  position: relative;
  display: inline-block;
}

.admin-dropdown .dropbtn {
  cursor: pointer;
  font-weight: 700;
  font-size: 1.1rem;
  color: #a6bae7ff;
  user-select: none;
}

.admin-dropdown .dropdown {
  display: none;
  position: absolute;
  top: 45px;
  left: -10%;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  overflow: hidden;
  min-width: 220px;
  z-index: 10;
}







.nav-left .logo {
  font-weight: 800;
  font-size: 1.2rem;
  color: #2563eb;
}


.nav-center {
  display: flex;
  gap: 15px;
}
.nav-center a {
  color: #333;
  padding: 8px 12px;
  text-decoration: none;
  font-weight: 500;
  border-radius: 4px;
  transition: background 0.2s, color 0.2s;
}
.nav-center a:hover {
  background: #f2f6ff;
  color: #2563eb;
}
.nav-center a.active {
  background: #2b55b0ff;
  color: white;
}


.user-menu {
  position: relative;
}



.dropdown {
  display: none;
  position: absolute;
  right: 0;
  top: 80px;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  overflow: hidden;
  min-width: 160px;
  z-index: 10;
}
.dropdown a {
  display: block;
  padding: 10px 15px;
  color: #333;
  text-decoration: none;
  font-size: 0.9rem;
  transition: background 0.2s;
}
.dropdown a:hover {
  background: #f2f6ff;
  color: #2563eb;
}
.admin-dropdown:hover .dropdown {
  display: block;
}
.show {
  display: block !important;
}


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
  width: 90%;
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

function toggleAdminDropdown() {
  document.getElementById("adminDropdown").classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.closest(".admin-dropdown")) {
    document.getElementById("adminDropdown").classList.remove("show");
  }
}
function openLogoutModal() {
  document.getElementById("logoutModal").style.display = "flex";
  document.getElementById("userDropdown").classList.remove("show");
}
function closeLogoutModal() {
  document.getElementById("logoutModal").style.display = "none";
}
function confirmLogout() {
  window.location.href = "logout.php"; // or your logout route
}
</script>

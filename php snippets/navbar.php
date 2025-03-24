<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav>
        <div class="navbar">
            <div class="nav-logo">
                <a href="../php/index.php">
                    <img src="../images/logo.svg" alt="Health Family Clinic Logo" width="100%" heights="100%" class="nav-logo-img">
                </a>
            </div>
            <ul class="display-flex">
                <li><a href="../php/index.php">Home</a></li>
                <li><a href="../php/ourdoctors.php">Our Doctors</a></li>
                <li><a href="../php/appointment-selection.php">Book an Appointment</a></li>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <!-- User is logged in -->
                    <li class="profile-item display-flex gap-4" style="width: fit-content;" onclick="toggleDropdown()">
                        <img src="../images/icon-profile.svg">
                        <p><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        <img src="../images/icon-arrow-down.svg">

                    </li>
        

                    <!-- <li><a href="../php snippets/logout.php">Log out</a></li> -->
                <?php else: ?>
                    <!-- User is not logged in -->
                    <li><a href="../php/login.php">Login</a></li>
                    <li><a href="../php/signup.php"><button class="btn-blue-sm">Sign Up</button></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Dropdown menu -->
    <ul id="profile-dropdown" class="dropdown-menu">
        <li class="display-flex .align-items-center gap-4">
            <img src="../images/icon-calendar.svg">
            <a href="../php/appointments.php">Appointments</a>
        </li>
        <li class="display-flex .align-items-center gap-4" >
            <img src="../images/icon-settings.svg">
            <a href="../php/settings.php">Settings</a>
        </li>
        <li class="display-flex .align-items-center gap-4">
            <img src="../images/icon-signout.svg">
            <a href="../php snippets/logout.php">Log out</a>
        </li>
    </ul>

    </nav>


</header>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById("profile-dropdown");
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Optional: Close dropdown if clicked outside
    document.addEventListener("click", function(event) {
        const profileItem = document.querySelector(".profile-item");
        const dropdown = document.getElementById("profile-dropdown");

        if (!profileItem.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });
</script>
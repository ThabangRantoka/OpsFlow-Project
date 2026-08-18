<?php
require_once("../config/DataBase.php");


$notificationCount = 0;


if (isset($_SESSION["user_id"])) {


    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM notifications
        WHERE user_id = ?
        AND is_read = 0
    ");


    if ($stmt) {


        $stmt->bind_param("i", $_SESSION["user_id"]);

        $stmt->execute();


        $result = $stmt->get_result();


        if ($result && ($row = $result->fetch_assoc())) {

            $notificationCount = (int)$row["total"];

        }


        $stmt->close();

    }

}

?>

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<header class="topbar">


    <!-- GLOBAL SEARCH -->

    <form class="global-search"
          action="../Search/index.php"
          method="GET">


        <i class="fa-solid fa-magnifying-glass">
</i>


        <input
            type="text"
            id="searchBox"
            name="q"
            placeholder="Search employees, projects, departments..."
            autocomplete="off"
            required
        >


        <div id="live-search-results">
</div>


    </form>



    <!-- TOP RIGHT ACTIONS -->

    <div class="topbar-actions">


        <!-- LIGHT / DARK MODE -->

        <button
            type="button"
            class="theme-toggle"
            id="themeToggle"
            aria-label="Switch to dark mode"
            title="Switch to dark mode"
        >

            <i class="fa-solid fa-moon" id="themeIcon">
</i>

        </button>



        <!-- NOTIFICATIONS -->

        <a
            class="notification-link"
            href="../Notifications/index.php"
            aria-label="Notifications"
            title="Notifications"
        >


            <i class="fa-regular fa-bell">
</i>


            
<?php if ($notificationCount > 0) {
 
?>

                <span class="notification-count">

                    
<?php echo $notificationCount;
 
?>
                </span>


            
<?php }
 
?>

        </a>



        <!-- USER MENU -->

        <div class="user-menu">


            <button
                class="user-menu-trigger"
                type="button"
                id="userMenuTrigger"
            >


                <span class="mini-avatar">

                    
<?php
                    echo strtoupper(
                        substr(
                            $_SESSION["fullname"] ?? "U",
                            0,
                            2
                        )
                    );

                    
?>
                </span>


                <span class="top-user-name">

                    
<?php
                    echo htmlspecialchars(
                        $_SESSION["fullname"] ?? "User"
                    );

                    
?>
                </span>


                <i class="fa-solid fa-chevron-down">
</i>


            </button>



            <div
                class="user-dropdown"
                id="userDropdown"
            >


                <div class="dropdown-email">

                    
<?php
                    echo htmlspecialchars(
                        $_SESSION["email"] ?? ""
                    );

                    
?>
                </div>


                <a href="../Profile/index.php">

                    <i class="fa-regular fa-user">
</i>
                    My profile
                </a>


                <a href="../Auth/logout.php">

                    <i class="fa-solid fa-arrow-right-from-bracket">
</i>
                    Sign out
                </a>


            </div>


        </div>


    </div>


</header>



<script>
(function () {

    /* =========================================================
       USER DROPDOWN
       ========================================================= */

    const trigger = document.getElementById("userMenuTrigger");
    const dropdown = document.getElementById("userDropdown");

    if (trigger && dropdown) {

        trigger.addEventListener("click", function (e) {

            e.stopPropagation();

            dropdown.classList.toggle("show");

        });

        document.addEventListener("click", function () {

            dropdown.classList.remove("show");

        });

    }


    /* =========================================================
       LIGHT / DARK MODE
       ========================================================= */

    const themeToggle = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");

    const root = document.documentElement;


    function applyTheme(theme) {

        root.setAttribute("data-theme", theme);

        if (theme === "dark") {

            if (themeIcon) {

                themeIcon.classList.remove("fa-moon");
                themeIcon.classList.add("fa-sun");

            }

            if (themeToggle) {

                themeToggle.setAttribute(
                    "aria-label",
                    "Switch to light mode"
                );

                themeToggle.setAttribute(
                    "title",
                    "Switch to light mode"
                );

            }

        } else {

            if (themeIcon) {

                themeIcon.classList.remove("fa-sun");
                themeIcon.classList.add("fa-moon");

            }

            if (themeToggle) {

                themeToggle.setAttribute(
                    "aria-label",
                    "Switch to dark mode"
                );

                themeToggle.setAttribute(
                    "title",
                    "Switch to dark mode"
                );

            }

        }

    }


    /* =========================================================
       LOAD SAVED THEME
       ========================================================= */

    const savedTheme =
        localStorage.getItem("opsflow-theme");

    if (savedTheme === "dark") {

        applyTheme("dark");

    } else {

        applyTheme("light");

    }


    /* =========================================================
       THEME TOGGLE
       ========================================================= */

    if (themeToggle) {

        themeToggle.addEventListener("click", function () {

            const currentTheme =
                root.getAttribute("data-theme") || "light";

            const newTheme =
                currentTheme === "dark"
                    ? "light"
                    : "dark";

            localStorage.setItem(
                "opsflow-theme",
                newTheme
            );

            applyTheme(newTheme);

        });

    }


    /* =========================================================
       GLOBAL LIVE SEARCH
       ========================================================= */

    const searchBox =
        document.getElementById("searchBox");

    const results =
        document.getElementById("live-search-results");


    if (searchBox && results) {

        searchBox.addEventListener(
            "keyup",
            function () {

                const q =
                    this.value.trim();


                if (q.length < 2) {

                    results.innerHTML = "";
                    results.style.display = "none";

                    return;

                }


                fetch(
                    "../Search/search_ajax.php?q=" +
                    encodeURIComponent(q)
                )

                .then(function (response) {

                    return response.text();

                })

                .then(function (data) {

                    results.innerHTML = data;
                    results.style.display = "block";

                })

                .catch(function () {

                    results.innerHTML = "";
                    results.style.display = "none";

                });

            }
        );


        document.addEventListener(
            "click",
            function (e) {

                if (
                    !searchBox.contains(e.target) &&
                    !results.contains(e.target)
                ) {

                    results.style.display = "none";

                }

            }
        );

    }

})();
</script>
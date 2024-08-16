<?php include "../php/configs/functions.php"; ?>
<script>
    document.querySelector("body").insertAdjacentHTML(
        "beforeend",
        `
    <div class="loader_box">
        <img src='../assets/img/f.gif' ;/>
    </div>
    `
    );
    window.addEventListener("load", function() {
        const loader = document.querySelector(".loader_box");
        if (loader) {
            loader.remove();
        }
    });
</script>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow ">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>



    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto ">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>

                <!-- Counter - Alerts -->
                <?php
                // Fetch the total count of unread notifications from the database
                $total_noty_result = mysqli_query($con, "SELECT COUNT(*) AS `total_notification` FROM `notification` WHERE `status` = 'unread'");
                $total_noty = mysqli_fetch_assoc($total_noty_result);

                // Get the number of unread notifications, default to 0 if not set
                $total_notification = isset($total_noty['total_notification']) ? (int)$total_noty['total_notification'] : 0;
                ?>

                <?php if ($total_notification > 0) : ?>
                    <span class="badge badge-danger badge-counter"><?= $total_notification ?></span>
                <?php endif; ?> </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <?php $noti_query = mysqli_query($con, "SELECT * FROM `notification`  WHERE `status` = 'unread' ORDER BY `notification`.`id` DESC LIMIT 10");
                if (mysqli_num_rows($noti_query) > 0) {
                    while ($noty = mysqli_fetch_array($noti_query)) { ?>
                        <a class="dropdown-item d-flex align-items-center" href="<?= $noty['url'] ?>">
                            <div>
                                <div class="small text-gray-500"><?= $noty['date'] ?></div>
                                <span class="font-weight-bold"><?= $noty['message'] ?></span>
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <a class="dropdown-item d-flex align-items-center" href="notifications.php">
                        <div>
                            <div class="small text-gray-500"></div>
                            <span class="font-weight-bold">No new Notifications</span>
                        </div>
                    </a>
                <?php }
                ?>
                <a class="dropdown-item text-center small text-gray-500" href="notifications.php">Show All Alerts</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-comments"></i>
                <?php
                $query = "SELECT COUNT(DISTINCT user_id) AS total_users_chatted 
          FROM `messages` 
          WHERE type = 'user' AND `read`='unread'";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $total_users_chatted = isset($row['total_users_chatted']) ? (int)$row['total_users_chatted'] : 0;

                    // Check if there are unread messages and display the badge only if the count is greater than 0
                    if ($total_users_chatted > 0) {
                        echo '<span class="badge badge-danger badge-counter">' . $total_users_chatted . '</span>';
                    }
                }
                ?>

            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <?php
                $query = "SELECT time, user_id,status, COUNT(*) as message_count FROM messages WHERE type = 'user' AND `read`='unread' GROUP BY user_id ORDER BY MAX(time) DESC LIMIT 8";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    mysqli_stmt_bind_result($stmt, $time, $user_id, $status, $message_count);

                    while (mysqli_stmt_fetch($stmt)) {
                        $user_data1 = getUserById($user_id);
                ?>
                        <a class="dropdown-item d-flex align-items-center" href="messages.php?user_id=<?= $user_id ?>">
                            <div class="font-weight-bold">
                                <div class="text-truncate"><?= $user_data1['username'] ?> </div>
                                <div class="small text-gray-500"> <?= $message_count ?> New Messages · <?= $time ?> </div>
                            </div>
                        </a>
                    <?php }
                } else { ?>
                    <a class="dropdown-item d-flex align-items-center" href="">
                        <div class="font-weight-bold">
                            <div class="text-truncate">NO NEW MESSAGES </div>

                        </div>
                    </a>
                <?php } ?>

                <a class="dropdown-item text-center small text-gray-500" href="chat.php">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                $admin_profile = mysqli_query($con, "SELECT `data2`,`data3` FROM `settings` WHERE `id` = 4");
                $a_profile = mysqli_fetch_array($admin_profile);
                ?>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $a_profile['data2'] ?></span>
                <img class="img-profile rounded-circle" src="../assets/img/profile/<?= $a_profile['data3'] ?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="settings.php#profile">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="settings.php">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="../index.php">
                    <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
                    Go To Website
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item bg-danger text-light" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>
</nav>
<!--Navegation Bar-->
<!-- F3703A Orange color-->
<nav class="navbar">
    <div class="logo">
        <a href="index.php"><img src="images/internshop_logo.png" alt="logo"></a>
    </div>
        <ul>
            <?php
                if(isset($_SESSION['eid'])){
                    // Employer menu bar.
                    echo('
                        <li><a href="employer_dashboard.php">Dashboard</a></li>
                        <li><a href="employer_internship_form.php">Post internship</a></li>
                        <li class="sub-menu-2">
                            <a class="sub-menu-a" href="">
                                <div class="profile-icon"><img class="mini-icon" onerror="this.src=\'uploads/com/blank.png\'" src='.$_SESSION['dp_path'].'></div>&blacktriangledown;
                            </a>
                            <ul class="sub-menu-under-ul">
                                <li><a href="company_profile.php">Profile</a></li>
                                <li><a href="employer_change_password.php">Change Password</a></li>
                                <li><a href="employer_change_email.php">Change Email</a></li>
                                <li><a href="employer_delete_account.php">Delete Account</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    ');
                } else if(isset($_SESSION['sid'])){
                    // Student Menu bar.
                    echo('
                        <li><a href="internships.php">Internships&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a class="sub-menu-a" href="">Location &blacktriangleright;</a>
                                    <ul>
                                        <li><a href="internships.php?location=Remote">Work from home</a></li>
                                        <li><a href="internships.php?location=Delhi">Internship in Delhi</a></li>
                                        <li><a href="internships.php?location=Banglore">Internship in Banglore</a></li>
                                        <li><a href="internships.php?location=Mumbai">Internship in Mumbai</a></li>
                                        <li><a href="internships.php?location=Chennai">Internship in Chennai</a></li>
                                        <li><a href="internships.php?location=Kolkata">Internship in Kolkata</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu-1"><a href="">Category &blacktriangleright;</a>
                                    <ul>');

                                        // Fetch categories dynamically from database
                                        $cat_sql = "SELECT category_name FROM categories WHERE status='active' AND is_deleted=0 ORDER BY category_name ASC";
                                        $cat_result = mysqli_query($conn, $cat_sql);
                                        if($cat_result && mysqli_num_rows($cat_result) > 0){
                                            while($row = mysqli_fetch_assoc($cat_result)){
                                                $category = htmlspecialchars($row['category_name'], ENT_QUOTES);
                                                $category_url = urlencode($row['category_name']);
                                                echo "<li><a href='internships.php?category={$category_url}'>{$category}</a></li>";
                                            }
                                        } else {
                                            echo "<li><a href='#'>No Categories Found</a></li>";
                                        }

                    echo('
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="student_dashboard.php">Dashboard</a></li>
                        <li><a href="student_resume.php">Resume</a></li>
                        <li><a href="bookmarks.php">Bookmarks</a></li>
                        <li class="sub-menu-2">
                            <a class="sub-menu-a" href="">
                                <div class="profile-icon"><img class="mini-icon" onerror="this.src=\'uploads/dp/blank.jpg\'" src='.$_SESSION['dp_path'].'></div>&blacktriangledown;
                            </a>
                            <ul class="sub-menu-under-ul">
                                <li><a href="student_profile.php">Profile</a></li>
                                <li><a href="student_change_password.php">Change Password</a></li>
                                <li><a href="student_change_email.php">Change Email</a></li>
                                <li><a href="student_delete_account.php">Delete Account</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    ');
                } else {
                    // Guest menu bar
                    echo('
                        <li><a href="internships.php">Internships&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a class="sub-menu-a" href="">Location &blacktriangleright;</a>
                                    <ul>
                                        <li><a href="internships.php?location=Remote">Work from home</a></li>
                                        <li><a href="internships.php?location=Delhi">Internship in Delhi</a></li>
                                        <li><a href="internships.php?location=Banglore">Internship in Banglore</a></li>
                                        <li><a href="internships.php?location=Mumbai">Internship in Mumbai</a></li>
                                        <li><a href="internships.php?location=Chennai">Internship in Chennai</a></li>
                                        <li><a href="internships.php?location=Kolkata">Internship in Kolkata</a></li>
                                    </ul>
                                </li>
                                <li class="sub-menu-1"><a href="">Category &blacktriangleright;</a>
                                    <ul>');

                                        // Fetch categories dynamically from database
                                        $cat_sql = "SELECT category_name FROM categories WHERE status='active' AND is_deleted=0 ORDER BY category_name ASC";
                                        $cat_result = mysqli_query($conn, $cat_sql);
                                        if($cat_result && mysqli_num_rows($cat_result) > 0){
                                            while($row = mysqli_fetch_assoc($cat_result)){
                                                $category = htmlspecialchars($row['category_name'], ENT_QUOTES);
                                                $category_url = urlencode($row['category_name']);
                                                echo "<li><a href='internships.php?category={$category_url}'>{$category}</a></li>";
                                            }
                                        } else {
                                            echo "<li><a href='#'>No Categories Found</a></li>";
                                        }

                    echo('
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="">Register&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a href="student_register.php">As a Student</a></li>
                                <li class="sub-menu-1"><a href="employer_register.php">As an Company</a></li>
                            </ul>
                        </li>
                        <li><a href="">Login&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a href="student_login.php">As a Student</a></li>
                                <li class="sub-menu-1"><a href="employer_login.php">As an Company</a></li>
                                 <li class="sub-menu-1"><a href="admin_login.php">As an Admin</a></li>
                            </ul>
                        </li>
                        ');
                    }
            ?> 
        </ul>
</nav>
<div id="notify" style="visibility: hidden; opacity: 0;"></div>

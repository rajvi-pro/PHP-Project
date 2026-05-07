<!--Navegation Bar-->
<!-- F3703A Orange color-->
<nav classf="navbar">
    <div class="logo">
        <a href="index.php"><img src="images/internshop_logo.png" alt="logo"></a>
    </div>
        <ul>
            <?php
            if (isset($_SESSION['adminid'])) {
            // Admin menu bar
            echo('
            <li><a href="admin_dashboard.php">Dashboard</a></li>
           <li class="sub-menu-2">
                            <a class="sub-menu-a" href="">
                                <div class="profile-icon"><img class="mini-icon" onerror="this.src=\'uploads/com/blank.png\'" src='.$_SESSION['dp_path'].'></div>&blacktriangledown;
                            </a>
                <ul class="sub-menu-under-ul">
                    <li><a href="admin_change_password.php">Change Password</a></li>
                    <li><a href="admin_change_email.php">Change Email</a></li>
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </li>
        ');
            }


            else if(isset($_SESSION['eid'])){
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
                }else if(isset($_SESSION['sid'])){
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
                                    <ul>
                                        <li>
                                            <a href="internships.php?category=Information Technology">Information Technology</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Business & Management">Business Management</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Humanities">Humanities</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Science & Technology">Science & Technology</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Law">Law</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Architecture">Architecture</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="student_dashboard.php">Dashboard</a></li>
                        <li><a href="student_resume.php">Resume</a></li>
                        <li><a href="bookmarks.php">Bookmarks</a></li>
                        <!-- <img class="bookmark-icon-img" src="images/icons/bookmark.png"> -->
                        <li class="sub-menu-2">
                            <a class="sub-menu-a" href="">
                                <div class="profile-icon"><img class="mini-icon" onerror="this.src=\'uploads/dp/blank.jpg\'" src='.$_SESSION['dp_path'].'></div>&blacktriangledown;
                            </a>
                            <ul class="sub-menu-under-ul">
                                <!--<div class="menu-name-email">
                                    <div>'.$_SESSION['name'].'</div>
                                    <div>'.$_SESSION['email'].'</div>
                                </div>-->
                                
                                <li><a href="student_profile.php">Profile</a></li>
                                <li><a href="student_change_password.php">Change Password</a></li>
                                <li><a href="student_change_email.php">Change Email</a></li>
                                <li><a href="student_delete_account.php">Delete Account</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    ');
                }else{
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
                                    <ul>
                                        <li>
                                            <a href="internships.php?category=Information Technology">Information Technology</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Business Management">Business Management</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Humanities">Humanities</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Science & Technology">Science & Technology</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Law">Law</a>
                                        </li>
                                        <li>
                                            <a href="internships.php?category=Architecture">Architecture</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="">Register&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a href="student_register.php">As a student</a></li>
                                <li class="sub-menu-1"><a href="/internshop/employer_register.php">As an employer</a></li>
                            </ul>
                        </li>
                        <li><a href="">Login&blacktriangledown;</a>
                            <ul>
                                <li class="sub-menu-1"><a href="student_login.php">As a student</a></li>
                                <li class="sub-menu-1"><a href="employer_login.php">As an employer</a></li>
                            </ul>
                        </li>
                        ');
                    }
            ?> 
        </ul>
</nav>
<div id="notify" style="visibility: hidden; opacity: 0;"></div>
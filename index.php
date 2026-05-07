<?php
    session_start();
    include("conn.php");
    
    if(isset($_SESSION['eid'])){
        header('Location: employer_dashboard.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Internshop</title>
    <link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <style>
        /* --- WELCOME SCREEN STYLING --- */
        #welcome-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #b3e5fc, #e1f5fe);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.6s ease;
            overflow: hidden;
        }

        #welcome-screen h1 {
            font-size: 3rem;
            color: #004aad;
            font-family: 'Poppins', sans-serif;
            text-shadow: 0 0 15px rgba(0,0,0,0.1);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        #welcome-screen p {
            color: #003b82;
            font-size: 1.2rem;
            margin-top: 10px;
            font-family: 'Open Sans', sans-serif;
            animation: fadeIn 2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* GLASS EFFECT BUTTON */
        #enter-btn {
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            color: #004aad;
            font-weight: 600;
            padding: 14px 40px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1.1rem;
            margin-top: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.1s ease;
        }

        #enter-btn:hover {
            background: rgba(255, 255, 255, 0.45);
            transform: scale(1.07);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        /* Hide site content until Enter is clicked */
        header, main, footer {
            display: none;
        }

        body.loaded header,
        body.loaded main,
        body.loaded footer {
            display: block;
        }
    </style>
</head>
<body>

    <!-- WELCOME SCREEN -->
    <div id="welcome-screen">
        <h1>Welcome to <span style="color:#007bff;">Internshop</span></h1>
        <p>Your gateway to amazing internships ✨</p>
        <button id="enter-btn">Enter Site</button>
    </div>
    <script>
       const enterBtn = document.getElementById('enter-btn');
        const welcomeScreen = document.getElementById('welcome-screen');

        enterBtn.addEventListener('click', () => {
            // Instantly hide welcome screen without waiting seconds
            welcomeScreen.style.opacity = '0';
            welcomeScreen.style.pointerEvents = 'none';
            setTimeout(() => {
                welcomeScreen.style.display = 'none';
                document.body.classList.add('loaded');
            }, 300); // Quick smooth fade
        });
    </script> 

    <!-- HEADER -->
    <header>
        <?php include("nav.php"); ?>
    </header>

    <!-- MAIN -->
    <main>
        <div class="index-search-bar">
            <form action="internships.php" method="GET">
                <input class="search" type="text" name="keyword" placeholder="What are you looking for? e.g Design, Mumbai, Infosys">
                <input class="search-btn" type="submit" value="&#128269;">                    
            </form>
        </div>

        <div class="slider">
            <div class="imgslider"></div>
        </div>

        <div class="content">
            <h2>Internships</h2>
            <p>Apply to 10,000+ internships for free</p>
            <div class="view-all-int">
                <a href="internships.php">View all internships<b>&ShortRightArrow;</b></a>
            </div>
        </div>

        <!-- POPULAR CITIES -->
        <div class="img-div">
            <h2 class="img-title">Popular cities</h2>
            <div class="images">
                <div class="box"><a href="internships.php?location=Remote"><img src="images/1wfh.png"><p>Work from home</p></a></div>
                <div class="box"><a href="internships.php?location=Delhi"><img src="images/2delhi.png"><p>Delhi/NCR</p></a></div>
                <div class="box"><a href="internships.php?location=Bangalore"><img src="images/3banglor.png"><p>Bangalore</p></a></div>
                <div class="box"><a href="internships.php?location=Mumbai"><img src="images/4mumbai.png"><p>Mumbai</p></a></div>
                <div class="box"><a href="internships.php?location=Chennai"><img src="images/5chennai.png"><p>Chennai</p></a></div>
                <div class="box"><a href="internships.php?location=Kolkata"><img src="images/6kolkata.png"><p>Kolkata</p></a></div>
            </div>
        </div>

        <!-- POPULAR CATEGORIES -->
        <div class="img-div">
            <h2 class="img-title">Popular categories</h2>
            <div class="images">
                <div class="box"><a href="internships.php?category=Information%20Technology"><img class="cards" src="images/cat1.png"><p>Information Technology</p></a></div>
                <div class="box"><a href="internships.php?category=Business%20Management"><img src="images/cat2.png"><p>Business Management</p></a></div>
                <div class="box"><a href="internships.php?category=Humanities"><img src="images/cat3.png"><p>Humanities</p></a></div>
                <div class="box"><a href="internships.php?category=Science%20&%20Technology"><img src="images/cat4.png"><p>Science & Technology</p></a></div>
                <div class="box"><a href="internships.php?category=Law"><img src="images/cat5.png"><p>Law</p></a></div>
                <div class="box"><a href="internships.php?category=Architecture"><img src="images/cat6.png"><p>Architecture</p></a></div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <?php include("footer.php"); ?>
    </footer>

    <script>
       /*const enterBtn = document.getElementById('enter-btn');
        const welcomeScreen = document.getElementById('welcome-screen');

        enterBtn.addEventListener('click', () => {
            // Instantly hide welcome screen without waiting seconds
            welcomeScreen.style.opacity = '0';
            welcomeScreen.style.pointerEvents = 'none';
            setTimeout(() => {
                welcomeScreen.style.display = 'none';
                document.body.classList.add('loaded');
            }, 300); // Quick smooth fade
        });*/
    </script> 

    <script src="script/script.js"></script>
</body>
</html>

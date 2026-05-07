<?php
    session_start();
    include("conn.php");
    
    if (!isset($_SESSION['eid'])) {
        header("Location: index.php");
    }


?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Internshop | Student Applications</title>
		<link rel="icon" type="image/x-icon" href="images/icons/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/student_dashboard_style.css">
    <link rel="stylesheet" type="text/css" href="css/bookmarks_style.css">
    <script type="text/javascript" src="script/errorMessage.js"></script>
</head>
<body>
	<header>
		<?php include("nav.php"); ?>
	</header>
</body>
</html>
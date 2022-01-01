<?php session_start();
    // require_once 'functions.inc.php';
    require_once(__DIR__ .'/../api/config/core.php');
    require_once(__DIR__ .'/../api/config/database.php');
    require_once(__DIR__ .'/../api/objects/client.php');
    require_once(__DIR__ .'/../api/objects/plan.php');
    require_once(__DIR__ .'/../api/objects/priority.php');
    require_once(__DIR__ .'/../api/objects/notification.php');

    if(!isset($_SESSION['_id'])) {
        header("Location: ./auth/login");
    }else if(isset($_SESSION['client_id'])) {
        // get database connection
        $database = new Database();
        $db = $database->connect();
        // prepare client object
        $client = new Client($db); 
        $plan = new Plan($db); 
        $priority = new Priority($db); 
        $notification = new Notification($db); 

        $planStmt = $plan->countPlans($_SESSION['client_id']);
        $priorityStmt = $priority->countPriorities($_SESSION['client_id']);
        $planStmt2 = $plan->countCompletedPlans($_SESSION['client_id']);
        $planStmt3 = $plan->countOngoingPlans($_SESSION['client_id']);

        // $keyd = '4324';
        // $e = $plan->countPrioritiesForEachPlan($_SESSION['client_id'],$keyd);
        // var_dump($e);
        // if (empty($e)) {
        //     $z = 0;
        //     echo "Less than Zero with ".$z;
        // }else{
        //     $z = count($e);
        //     echo "More than Zero with ".$z;
        // }


        if (empty($planStmt)) {
            $planNum = 0;
        }else{
            $planNum = count($planStmt);
        }
        if (empty($planStmt2)) {
            $countCompletedPlans = 0;
        }else{
            $countCompletedPlans = count($planStmt2);
        }
        if (empty($planStmt3)) {
            $countOngoingPlans = 0;
        }else{
            $countOngoingPlans = count($planStmt3);
        }
        if (empty($priorityStmt)) {
            $priorityNum = 0;
        }else{
            $priorityNum = count($priorityStmt);
        }
    
        $_id = ucfirst($_SESSION['_id']);
        $client_id = ucfirst($_SESSION['client_id']);

        $stmt = $client->readOneClient($_id);
        
        $firstname = ucfirst($client->firstname);
        $lastname = ucfirst($client->lastname);
        $fullname = $firstname ." ".$lastname;
        $email = ucfirst($client->email);
        $address = strtolower($client->address);
        $gender = strtolower($client->gender);
        $dob = strtolower($client->dob);
        $country = strtolower($client->country);
        $state = ucwords($client->state); 
        $contact_number = $client->contact_number;
        $isVerified = $client->isVerified;
        $profile_img = $client->profile_img; 
        if ($isVerified === '1') {
            $isVerified = "Verified";
        }
        if ($isVerified === '0') {
            $isVerified = "Unverified";
        } 
    }
    // session_destroy();
    function maskPhoneNumber($number){
        $mask_number =  str_repeat("*", strlen($number)-4) . substr($number, -4);  
        return $mask_number;
    }

?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?= $home_url; ?>">
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Five Planner App</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript"> 
        // Function to capitalize letters
        function capitalize(string) {
            return string.charAt(0).toUpperCase();
        }

        // function to capitalize each word
        function capitalizeFirstLetters(str){
            return str.toLowerCase().replace(/^\w|\s\w/g, function (letter) {
                return letter.toUpperCase();
            })
        }

        function goToURL(subUrl) {
            // var subUrl = 'about';
            var url = "<?= $home_url;?>/"+subUrl;
            var cc = subUrl;
            location.href = url;
            // alert(url);
        }

        $(window).on('load', function() {
            var pathname = window.location.pathname;
            pathname = pathname.substring(pathname.lastIndexOf("/")+1);
            // alert(pathname);
            if(pathname === '.' || pathname === "/" || pathname === "" || pathname === "dashboard") {
                $('#titleBar').text(capitalizeFirstLetters(pathname));
            }else if(pathname === "profile"){
                $('#titleBar').text(capitalizeFirstLetters(pathname));
            }else if(pathname === "progress"){
                $('#titleBar').text(capitalizeFirstLetters(pathname));
            }
        });

        const diffDaysLeft = (date, otherDate) => Math.ceil(Math.abs(date - otherDate) / (1000 * 60 * 60 * 24));
        const diffDaysUsed = (date, otherDate) => Math.ceil(Math.abs(date - otherDate) / (1000 * 60 * 60 * 24));

        function generate() {
            let id = () => {
                return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
            }
           // document.getElementById("uniqueID").innerHTML = id();
           var idRg = id();
           return idRg;
        }

    </script>
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<?php require_once 'partials/sideBar.inc.php'; ?>
    </div>
    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <p class="navbar-brand titleBar" id="titleBar" href="dashboard"></p>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-bell"></i>
                                    <p class="notification">5</p>
                                    <p>Notifications</p>
                                    <b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                              </ul>
                        </li> -->
                        <li>
                            <a href="../api/auth/logout.php">
                                <i class="ti-shift-right"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>

        
<?php ob_start();
    session_start();
    require_once(__DIR__ .'/../api/config/core.php');
    if(isset($_SESSION['user'])) {
        header("Location: ../dashboard");
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"> -->
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?= $app_name; ?> - Dashboard Login</title>
  <!-- <link rel="stylesheet" href="css/style.css"> -->
  <link rel="icon" type="image/png" sizes="96x96" href="../assets/img/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
  <link rel = "stylesheet" href = "https://storage.googleapis.com/code.getmdl.io/1.3.0/material.deep_purple-blue.min.css">
    <script src = "https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
    <link rel = "stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <style>
    body {
        background-color: #F3EBF6;
        font-family: 'Ubuntu', sans-serif;
        box-sizing: border-box;
    }
    
    .main {
        background-color: #FFFFFF;
        width: 350px;
        height: 400px;
        margin: 1em auto;
        border-radius: 1.5em;
        box-shadow: 0px 11px 35px 2px rgba(0, 0, 0, 0.14);

        /*position: fixed;*/
        /*top: 50%;*/
        /*left: 35%;*/
        /*transform: translate(0, -50%);*/
    }
    
    .sign {
        padding-top: 20px;
        color: #8C55AA;
        font-family: 'Ubuntu', sans-serif;
        font-weight: bold;
        font-size: 23px;
    }

    .signPill {
        padding-top: 5px;
        color: #000;
        font-family: cursive;
        font-weight: bold;
        font-size: 16px;
    }
    .signPillPa {
        padding-top: 1px;
        color: #000;
        font-family: cursive;
        font-weight: bold;
        font-size: 14px;
        font-style: italic;
    }
    
    .un {
    width: 76%;
    color: rgb(38, 50, 56);
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 1px;
    background: rgba(136, 126, 126, 0.04);
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    outline: none;
    box-sizing: border-box;
    border: 2px solid rgba(0, 0, 0, 0.02);
    margin-bottom: 50px;
    margin-left: 46px;
    text-align: center;
    margin-bottom: 27px;
    font-family: 'Ubuntu', sans-serif;
    }
    
    form.form1 {
        padding-top: 10px;
    }
    
    .pass {
            width: 76%;
    color: rgb(38, 50, 56);
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 1px;
    background: rgba(136, 126, 126, 0.04);
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    outline: none;
    box-sizing: border-box;
    border: 2px solid rgba(0, 0, 0, 0.02);
    margin-bottom: 50px;
    margin-left: 46px;
    text-align: center;
    margin-bottom: 27px;
    font-family: 'Ubuntu', sans-serif;
    }
    
   
    .un:focus, .pass:focus {
        border: 2px solid rgba(0, 0, 0, 0.18) !important;
        
    }
    
    .submit {
      cursor: pointer;
        border-radius: 5em;
        color: #fff;
        background: linear-gradient(to right, #9C27B0, #E040FB);
        border: 0;
        padding-left: 40px;
        padding-right: 40px;
        padding-bottom: 10px;
        padding-top: 10px;
        font-family: 'Ubuntu', sans-serif;
        margin-left: 35%;
        font-size: 13px;
        box-shadow: 0 0 20px 1px rgba(0, 0, 0, 0.04);
    }
    
    .forgot {
        text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
        color: #E1BEE7;
        padding-top: 15px;
    }
    
    a {
        text-shadow: 0px 0px 3px rgba(117, 117, 117, 0.12);
        color: #E1BEE7;
        text-decoration: none
    }
    
    @media (max-width: 600px) {
        .main {
            border-radius: 0px;
        }
    }   

    </style>
</head>

<body>
  <div class="main">
    <p class="sign" align="center">Sign In</p>
    <p class="signPill" align="center">
        <b style="color: #2452ae;">F.</b>
        <b style="color: #cfc200;">I.</b>
        <b style="color: #089842;">V.</b>
        <b style="color: #ec3b3b;">E</b> Planner</p>
    <p class="signPillPa" align="center">People who have five year plans achieve more.</p>
    <form id="login_form" class="form1">
      <input class="un " type="text" align="center" name="email" id="email" placeholder="Enter email">
      <input class="pass" type="password" align="center" name="password" id="password" placeholder="Enter password">
      <button class="submit" align="center" id="submit" name="submit" type="submit">Log In</button>
      <p class="forgot" align="center"><a href="register">Sign Up</a> </p>
    </form>
    <script>
        // trigger when login form is submitted
        $(document).on('submit', '#login_form', function(e){
            e.preventDefault();
            var email = $('#email').val();
            var password = $('#password').val();
            if (email == "" || password == "") {
                // $('#errorAlert').slideDown(300).delay(5000).slideUp(300).html("Form inputs cannot be empty.");
                toastr.error("Form inputs cannot be empty.");
            }else{
                
                // function to make form values to json format
                $.fn.serializeObject = function(){
                    var o = {};
                    var a = this.serializeArray();
                    $.each(a, function() {
                        if (o[this.name] !== undefined) {
                            if (!o[this.name].push) {
                                o[this.name] = [o[this.name]];
                            }
                            o[this.name].push(this.value || '');
                        } else {
                            o[this.name] = this.value || '';
                        }
                    });
                    return o;
                };
                
                // get form data
                var login_form = $(this);
                var form_data = JSON.stringify(login_form.serializeObject());
                // console.log(form_data);
                // submit form data to api
                $.ajax({
                    url: "../api/auth/client-login.php",
                    type : "POST",
                    contentType : 'application/json',
                    data : form_data,
                    beforeSend: function(){
                        setTimeout(function () {
                            $('#submit').text('Loading...');
                            // $('#submit').html('Loading...');
                        }, 100); 
                    },
                    success : function(result){
                        // console.log(result);
                        setTimeout(function() {
                            if(result.status == "Done"){
                                // console.log(result.status);
                                window.location.href = "../dashboard";
                                $('#submit').text('Log In');
                            }else{
                                toastr.error("Login failed. Email or password is incorrect.");
                                $('#submit').text('Log In');
                            }
                        }, 4000);
                    },
                    error: function(result){
                        setTimeout(function() {
                            if(result.status === "Not done"){
                                // alert('Not Done.');
                                // console.log(result.status);
                            }
                            // on error, tell the user login has failed & empty the input boxes
                            toastr.error("Login failed. Email or password is incorrect.");
                            login_form.find('input').val('');
                            $('#submit').text('Log In');
                        }, 4000);
                    }
                });
             
                return false;
            }
        });
    </script>  
    </div>
     
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/animate.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    <script src="{{asset('front/js/jquery.min.js')}}"></script>
    <link type="text/css" href="{{asset('front/css/font-awesome.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('front/css/style.css')}}" rel="stylesheet">
</head>
<body>


<section class="logmain">
    <div class="logbg">
        <div class="logimg">
            <img src="{{asset('front/images/regbg.jpg')}}"/>

            <div class="imgtext text1">
                <p>Need more information about the work within Dash GTS? <br>Take a look in our FAQ</p>
                <p>Need Help? Contact Support</p>
                <p>
                    Copyright © 2023 Dash GTS All Rights Reserved. <br>Privacy Statement
                </p>
            </div>
        </div>
        <div class="logtext">
            <div class="bg-white">
                <h3>Create your <span class="blueclolor">account</span></h3>
                <div class="logform">
                    <div>
                        <label>Email Address</label>
                        <input type="email" value="" placeholder="Email Address" class="inputtext"/>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" value="" placeholder="Password" class="inputtext"/>
                    </div>
                    <div class="d-flex space">
                        <div>
                            <label>First Name</label>
                            <input type="text" value="" placeholder="First Name" class="inputtext"/>
                        </div>
                        <div>
                            <label>Last Name</label>
                            <input type="text" value="" placeholder="Last Name" class="inputtext"/>
                        </div>
                    </div>
                    <div>
                        <label>Country of Residence</label>
                        <select class="dropdd">
                            <option>Select</option>
                            <option>Select</option>
                        </select>
                    </div>
                    <div class="forgot">
                        <label><input type="checkbox" name=""> I agree to Dash GTS’s <a href="#">Terms of service</a>
                            and <a href="#">Privacy Policy</a></label>
                    </div>
                    <div>
                        <button class="btn bluebg">Create Account</button>
                    </div>

                    <div class="text">
                        Have an account? <a href="#">Sign In</a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>


</body>
</html>

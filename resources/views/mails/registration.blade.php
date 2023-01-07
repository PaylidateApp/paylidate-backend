<head>
    <!-- Styles -->
    <style>
    html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .row {
                width: 100%;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 32px;
            }

            .links > a {
                color: #FFF;
                padding: 0 8px;
                font-size: 12px;
                /* font-weight: 600; */
                /* letter-spacing: .1rem; */
                text-decoration: none;
                /* text-transform: uppercase; */
            }

            .text-white {
                color: #FFF;
            }

            .back-pad {
                background-color: #283f4c; padding: 10px 10px 10px 10px;
            }

            </style>

        </head>
    <body>
    <div class="flex-center back-pad">
        <div class="title content row">
            <b class="text-white content">Paylidate</b>
        </div>
    </div>

    <p style="font-size: 18px"><b>Hello {{$user['name']}}</b></p>
    <p style="font-size: 20px"><b>Welcome to Paylidate.</b></p>
    <p style="font-size: 20px"><b>We are excited to have you on paylidate.</b></p>
    <p>
        For any questions, please send a reply to this mail or paylidate@gmail.com, we are always happy to help out.
        Meanwhile, click this
        <a href={{ $verifyEmailLink }}>link</a> or visit
        <a href={{ $verifyEmailLink }}>{{ $verifyEmailLink }}</a> to verify your account
    </p>
    <p>
        Once again, welcome to Paylidate - where you can do 'commerce without barriers'.
    </p>

    <p><b>Thanks</b></p>
    <p><b>Lawrence from Paylidate</b></p>

    <div class="flex-center back-pad">
        <div class="content links row">
            <a href="https://www.paylidate.com/login">Login</a>|
            <a href="https://www.paylidate.com/about">About Us</a>|
            <a href="https://www.paylidate.com/contact">Contact Us</a>
        </div>
    </div>

    <p class="content" style="font-size: 8px;">Copyright (c) 2021 Paylidate.</p>

    </body>

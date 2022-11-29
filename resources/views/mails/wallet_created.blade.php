<head>
    <!-- Styles -->
    <style>
        html,
        body {
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

        .links>a {
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
            background-color: #283f4c;
            padding: 10px 10px 10px 10px;
        }
    </style>

</head>

<body>
    <div class="flex-center back-pad">
        <div class="title content row">
            <b class="text-white content">Paylidate</b>
        </div>
    </div>

    <p style="font-size: 18px"><b>Hello {{$user}}</b></p>
    <p>A bonus of #1,000< has been added to your Paylidate Wallet.</p><br />
            <p>Use PAYLIDATE to protect your transaction and get more bonuses for yourself and anyone you transact with.
            </p><br />


            <p>Your Escrow agent</p>
            <p><b>Paylidate</b></p>

            <div class="flex-center back-pad">
                <div class="content links row">
                    <a href="https://www.paylidate.com/login">Login</a>|
                    <a href="https://www.paylidate.com/about">About Us</a>|
                    <a href="https://www.paylidate.com/contact">Contact Us</a>
                </div>
            </div>

            <p class="content" style="font-size: 8px;">Copyright (c) 2021 Paylidate.</p>

</body>

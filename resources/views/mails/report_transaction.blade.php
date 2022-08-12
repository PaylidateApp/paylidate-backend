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

    <p style="font-size: 18px"><b>Hello {{$user_name}}</b></p>
    @if ($type == 'report')
        <p style="font-size: 15px"><b>The transaction with Transaction Ref {{ transation_ref }} was reported by the buyer for {{$report}}</b></p>
        <br>
        @if ($user_name == 'Admin' || $user_name == 'Lawrence')
        
        <p style="font-size: 12px; color:red"><b>NOTE: Your have just 24 hours to raise a dispute on this transaction else the trasaction will be cancelled and the buyer will get a refund</b></p>

        @else

        <p style="font-size: 12px; color:red"><b>NOTE: The seller has just 24 hours to raise a dispute on this transaction else the trasaction will be cancelled and the buyer will get a refund</b></p>

        @endif

    @else
        <p style="font-size: 15px"><b>The transaction with Transaction Ref {{ transation_ref }} that was reported by the buyer for "{{$report}}" hase been resolve and transaction process can continue</b></p>
        <br>
       
    @endif

    
    <p>
    Click or visit <a href="{{ url('https://www.paylidate.com/escrow-transaction/'.$transation_ref)}}" >https://www.paylidate.com/escrow-transaction/{{$transation_ref}}</a> to view transaction
    </p>

    <p><b>Thanks</b></p>
    <div class="flex-center back-pad">
        <div class="content links row">
            <a href="https://www.paylidate.com/login">Login</a>|
            <a href="https://www.paylidate.com/about">About Us</a>|
            <a href="https://www.paylidate.com/contact">Contact Us</a>
        </div>
    </div>

    <p class="content" style="font-size: 8px;">Copyright (c) 2021 Paylidate.</p>

    </body>

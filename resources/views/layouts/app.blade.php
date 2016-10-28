<!DOCTYPE html>
<html @yield('html_attrs')>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>PreciseFreight - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    @yield('css')

    <!-- Styles -->
<!--    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">

    <style>

        textarea {
            font-family: sans-serif;
        }

        body {
            line-height: 1.6;
            color: #3a3a3a;
            margin: 0;
        }

        h1 {
            font-family: sans-serif;
            font-size: 2.2em;
            text-align: center;

            /*margin: 0 0;*/
        }

        h2 {
            font-size: 1.5em;
            margin: 0.83em 0;
        }

        html {overflow-y: scroll; overflow-x: hidden;}
        pre {
            margin: 1em 0;
        }
        /*body {font-family: 'Open Sans', sans-serif; background:#f0eeed; color: #676767;}*/
        .header:after {content:"";height:0;display:block;visibility:hidden;clear:both;}
        .header {border-bottom: 3px solid #db7a78;}
        .header .brand { color: White; float: left; padding:0 16px 0 0;}
        .header .nav {float: right; padding:0 75px 0 0}
        .header .nav li {display: inline-block; margin: 0; list-style: none;}
        .header .nav li a {
            color: white;
            font-size: 1.2em;
            text-decoration: none;
            /*display: block;*/
            line-height: 95px;
            padding: 10px 0 0;
            margin: 0 0 0 50px;
            width: 100px;
            text-align: left;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .nav li.on a {text-decoration:underline;}
        .header .nav li a:hover, .header .nav li a:active {opacity: 0.7;}
        .brand {
            position: absolute;
            left: 10%;
            margin-left: -50px;
            display: block;
        }
        .header {
            margin: 0 auto;
            background: #3acec2;
            display: block;
        }
        .section.page h1 {
            font-size: 24px;
            text-align: center;
            line-height: 1.6;
            font-weight: bold;
        }
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
        .section.page p {width: 475px; margin-left: auto; margin-right: auto;}
        .section.page .media-details h1 .price {color: #0000A0; padding-right: 10px; font-size: 34px;}
        .section.catalog h2 {
            font-size: 24px;
            text-align: center;
            line-height: 1.6;
            font-weight: normal;
            padding-top: 20px;
        }
        form input[type="submit"] {
            width: 475px;
            text-align: center;
            border: 0;
            background: #DC143C;
            color: #FFF;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            font-size: 16px;
            padding: 14px 0 16px;
            font-family: 'Open Sans', sans-serif;
        }
        select {
            width: 50%;
            text-align: left;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            font-family: 'Open Sans', sans-serif;
        }

    </style>

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>

@yield('js')
    <script>
        $(document).ready(function () {
            $('#division').change(function () {
                var division = $(this).val();
                //console.log(division);
                $.getJSON("/api/runnumbers/" + $("#division").val(),
                    function (data) {
                        console.log(data);
                        var $el = $('#beginrunnumber');
                        $el.empty(); // remove old options
                        $el.append($("<option></option>")
                            .attr("value", '').text('Choose Beginning Runnumber'));
                        $.each(data, function(value, key) {
                            $el.append($("<option></option> value=")
                                .attr("runnumber", value.runnumber).text(key.runnumber));
                        });
                    });
                $.getJSON("/api/divisions/" + $("#division").val(),
                    function (data) {
                        console.log(data);
                        var $el = $('#date');
                        $el.empty(); // remove old options
                        $el.append($("<option></option>")
                            .attr("value", '').text('Choose Import Date'));
                        $.each(data, function(value, key) {
                            $el.append($("<option></option> value=")
                                .attr("date", value.importDate).text(key.importDate));
                        });
                    });
            });
        });

    </script>
</head>


<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="header">

            <!-- Branding Image -->
            <h1 class="brand"< href="/">Precise Freight Audit</a></h1>

            <ul class="nav">
                <li class="home>"><a href="/">Home</a></li>
                <li class="run"><a href="/run"> Run</a></li>
                <li class="delete"><a href="/delete">Delete</a></li>
                <li class="division"><a href="/reports">Reports</a></li>
                <li class="zeroPaid"><a href="/zeroPaid">Zero Paid</a></li>
            </ul>
        </div>
    </div>

</nav>

@yield('content')

</body>
</html>

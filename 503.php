<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>AVTO DELI - 503</title>
    <script src="./js/jquery-1.11.1.min.js"></script>
    <script src="./js/jquery-ui.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" media="screen" rel="stylesheet"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" media="screen" rel="stylesheet"/>
    <style>
        * {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, abbr, address, cite, code, del, dfn, em, img, ins, kbd, q, samp, small, strong, sub, sup, var, b, i, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, caption, article, aside, canvas, details, figcaption, figure, footer, header, hgroup, menu, nav, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            vertical-align: baseline;
            background: transparent
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
            display: block
        }

        html {
            font-size: 16px;
            line-height: 24px;
            width: 100%;
            height: 100%;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            overflow-y: scroll;
            overflow-x: hidden
        }

        img {
            vertical-align: middle;
            max-width: 100%;
            height: auto;
            border: 0;
            -ms-interpolation-mode: bicubic
        }

        body {
            min-height: 100%;
            -webkit-font-smoothing: subpixel-antialiased
        }

        .clearfix {
            clear: both;
            zoom: 1
        }

        .clearfix:before, .clearfix:after {
            content: & quot;
            \0020 & quot;;
            display: block;
            height: 0;
            visibility: hidden
        }

        .clearfix:after {
            clear: both
        }

    </style>
    <style>
        body.background.error-page-wrapper, .background.error-page-wrapper.preview {
            font-family: 'Open Sans', sans-serif;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .background.error-page-wrapper .content-container {
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, .2);
            padding: 50px;
            background-color: rgba(255, 255, 255, .9);
            width: 100%;
            max-width: 525px;
            position: absolute;
            left: 50%;
            margin-left: -262px;
        }

        .background.error-page-wrapper .content-container.in {
            left: 0px;
            opacity: 1;
        }

        .background.error-page-wrapper .head-line {
            transition: color .2s linear;
            font-size: 48px;
            line-height: 60px;
            letter-spacing: -1px;
            margin-bottom: 5px;
            color: #ccc;
        }

        .background.error-page-wrapper .subheader {
            transition: color .2s linear;
            font-size: 36px;
            line-height: 46px;
            color: #333;
        }

        .background.error-page-wrapper .hr {
            height: 1px;
            background-color: #ddd;
            width: 60%;
            max-width: 250px;
            margin: 35px auto;
        }

        .background.error-page-wrapper .context {
            transition: color .2s linear;
            font-size: 18px;
            line-height: 27px;
            color: #bbb;
        }

        .background.error-page-wrapper .context p {
            margin: 0;
        }

        .background.error-page-wrapper .context p:nth-child(n+2) {
            margin-top: 16px;
        }

        .background.error-page-wrapper .buttons-container {
            margin-top: 35px;
            overflow: hidden;
        }

        .background.error-page-wrapper .buttons-container a {
            transition: text-indent .2s ease-out, color .2s linear, background-color .2s linear;
            text-indent: 0px;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: none;
            color: #fff;
            background-color: #2ecc71;
            padding: 12px 0 13px;
            text-align: center;
            display: inline-block;
            overflow: hidden;
            position: relative;
            width: 45%;
        }

        .background.error-page-wrapper .buttons-container .fa {
            transition: left .2s ease-out;
            position: absolute;
            left: -50px;
        }

        .background.error-page-wrapper .buttons-container a:hover {
            text-indent: 15px;
        }

        .background.error-page-wrapper .buttons-container a:nth-child(1) {
            float: left;
        }

        .background.error-page-wrapper .buttons-container a:nth-child(2) {
            float: right;
        }

        .background.error-page-wrapper .buttons-container .fa-home {
            font-size: 18px;
            top: 15px;
        }

        .background.error-page-wrapper .buttons-container a:hover .fa-home {
            left: 10px;
        }

        .background.error-page-wrapper .buttons-container .fa-warning {
            font-size: 16px;
            top: 17px;
        }

        .background.error-page-wrapper .buttons-container a:hover .fa-warning {
            left: 5px;
        }

        .background.error-page-wrapper .buttons-container .fa-power-off {
            font-size: 16px;
            top: 17px;
        }

        .background.error-page-wrapper .buttons-container a:hover .fa-power-off {
            left: 9px;
        }

        .background.error-page-wrapper .buttons-container.single-button {
            text-align: center;
        }

        .background.error-page-wrapper .buttons-container.single-button a {
            float: none !important;
        }

        @media screen and (max-width: 555px) {
            .background.error-page-wrapper {
                padding: 30px 5%;
            }

            .background.error-page-wrapper .content-container {
                padding: 37px;
                position: static;
                left: 0;
                margin-left: 0;
            }

            .background.error-page-wrapper .head-line {
                font-size: 36px;
            }

            .background.error-page-wrapper .subheader {
                font-size: 27px;
                line-height: 37px;
            }

            .background.error-page-wrapper .hr {
                margin: 30px auto;
                width: 215px;
            }

            .background.error-page-wrapper .buttons-container .fa {
                display: none;
            }

            .background.error-page-wrapper .buttons-container a:hover {
                text-indent: 0px;
            }
        }

        @media screen and (max-width: 450px) {
            .background.error-page-wrapper {
                padding: 30px;
            }

            .background.error-page-wrapper .head-line {
                font-size: 32px;
            }

            .background.error-page-wrapper .hr {
                margin: 25px auto;
                width: 180px;
            }

            .background.error-page-wrapper .context {
                font-size: 15px;
                line-height: 22px;
            }

            .background.error-page-wrapper .context p:nth-child(n+2) {
                margin-top: 10px;
            }

            .background.error-page-wrapper .buttons-container {
                margin-top: 29px;
            }

            .background.error-page-wrapper .buttons-container a {
                float: none !important;
                width: 65%;
                margin: 0 auto;
                font-size: 13px;
                padding: 9px 0;
            }

            .background.error-page-wrapper .buttons-container a:nth-child(2) {
                margin-top: 12px;
            }
        }
    </style>
    <style>


        .background-image {
            background-color: #FFFFFF;
            background-image: url(./img/ford.jpg) !important;
        }

        .primary-text-color {
            color: #494949 !important;
        }

        .secondary-text-color {
            color: #AAAAAA !important;
        }

        .sign-text-color {
            color: #FFBA00 !important;
        }

        .sign-frame-color {
            color: #343C3F;
        }

        .pane {
            background-color: #FFFFFF !important;
        }

        .border-button {
            color: rgba(216, 83, 79, 1) !important;
            border-color: rgba(216, 83, 79, 1) !important;
        }

        .button {
            background-color: rgba(216, 83, 79, 1) !important;
            color: #FFFFFF !important;
        }

        .shadow {
            box-shadow: 0 0 60px #000000;
        }

    </style>
</head>
<body class="background error-page-wrapper background-color background-image">

<div class="content-container shadow">
    <div class="head-line secondary-text-color">
        503
    </div>
    <div class="subheader primary-text-color">
        Izgleda da ima naš strežnik težave!
    </div>
    <div class="hr"></div>
    <div class="context secondary-text-color">
        <p>
            Pojdite na prejšnjo stran in poskusite znova.
            Če mislite, da gre za napako prijavite težavo.
        </p>


    </div>
    <div class="buttons-container single-button">
        <a class="button" href="mailto:avtodeli@zigastrgar.com" target="_blank"><span class="fa fa-warning"></span>
            Prijavi težavo</a>
    </div>
</div>

<script>
    function ErrorPage(e, t, n) {
        this.$container = $(e), this.$contentContainer = this.$container.find(n == "sign" ? ".sign-container" : ".content-container"), this.pageType = t, this.templateName = n
    }
    ErrorPage.prototype.centerContent = function () {
        var e = this.$container.outerHeight(), t = this.$contentContainer.outerHeight(), n = (e - t) / 2, r = this.templateName == "sign" ? -100 : 0;
        this.$contentContainer.css("top", n + r)
    }, ErrorPage.prototype.initialize = function () {
        var e = this;
        this.centerContent(), this.$container.on("resize", function (t) {
            t.preventDefault(), t.stopPropagation(), e.centerContent()
        }), this.templateName == "plain" && window.setTimeout(function () {
            e.$contentContainer.addClass("in")
        }, 500), this.templateName == "sign" && $(".sign-container").animate({textIndent: 0}, {
            step: function (e) {
                $(this).css({transform: "rotate(" + e + "deg)", "transform-origin": "top center"})
            }, duration: 1e3, easing: "easeOutBounce"
        })
    }, ErrorPage.prototype.createTimeRangeTag = function (e, t) {
        return "<time utime=" + e + ' simple_format="MMM DD, YYYY HH:mm">' + e + "</time> - <time utime=" + t + ' simple_format="MMM DD, YYYY HH:mm">' + t + "</time>."
    }, ErrorPage.prototype.handleStatusFetchSuccess = function (e, t) {
        if (e == "503")$("#replace-with-fetched-data").html(t.status.description); else if (!t.scheduled_maintenances.length)$("#replace-with-fetched-data").html("<em>(there are no active scheduled maintenances)</em>"); else {
            var n = t.scheduled_maintenances[0];
            $("#replace-with-fetched-data").html(this.createTimeRangeTag(n.scheduled_for, n.scheduled_until)), $.fn.localizeTime()
        }
    }, ErrorPage.prototype.handleStatusFetchFail = function (e) {
        $("#replace-with-fetched-data").html("<em>(enter a valid StatusPage.io url)</em>")
    }, ErrorPage.prototype.fetchStatus = function (e, t) {
        if (!e || !t || t == "404")return;
        var n = "", r = this;
        t == "503" ? n = e + "/api/v2/status.json" : n = e + "/api/v2/scheduled-maintenances/active.json", $.ajax({
            type: "GET",
            url: n
        }).success(function (e, n) {
            r.handleStatusFetchSuccess(t, e)
        }).fail(function (e, n) {
            r.handleStatusFetchFail(t)
        })
    };
    var ep = new ErrorPage('body', "503", "background");
    ep.initialize();

    // hack to make sure content stays centered >_<
    $(window).on('resize', function () {
        $('body').trigger('resize')
    });

</script>


</body>
</html>

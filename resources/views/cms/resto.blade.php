<!DOCTYPE html>
<html>
<head>
</head>
<!-- <body onload="setCookie()"> -->
<body onload="sendCookie()">
    <!-- <h2>QRaved Start</h2> -->

    <iframe style="display:none;" src="https://sqh.zappar.io/7777972784004926192/1.0.23a/getlocalstorage.html" id="ifr"></iframe>

    <script type="text/javascript">
        function sendCookie() {
            var currUrl = window.location.href;
            var parsedUrl = currUrl.split("?res=");
            var postMsg = parsedUrl[1]; // this is just example
            postCrossDomainMessage(postMsg);
        }

        function postCrossDomainMessage(msg) {
            var win = document.getElementById('ifr').contentWindow;
            win.postMessage(msg, "https://sqh.zappar.io/7777972784004926192/1.0.23a/");

            window.location.replace("https://sqh.zappar.io/7777972784004926192/1.0.23a/");
        }
    </script>
</body>
</html>

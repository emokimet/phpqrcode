<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Swish Installation</title>
    <script>
        function checkSwishApp() {
            // Attempt to open the Swish app using its URL scheme
            var swishUrl = "swish://";
            var fallbackUrl = "https://www.getswish.se"; // URL for Swish download page

            var start = Date.now();
            var timeout;

            // Try to redirect to the Swish app
            window.location = swishUrl;

            // Set a timeout to check if the app opened
            timeout = setTimeout(function() {
                // Redirect to the fallback URL if the app does not open
                window.location = fallbackUrl; // Redirect to Swish download page
            }, 2000); // Adjust timeout as necessary

            window.onblur = function() {
                // If the user leaves the page, the app likely opened
                clearTimeout(timeout);
                alert("Swish app is installed and opened.");
            };
        }
    </script>
</head>
<body onload="checkSwishApp()">
    <h1>Checking for Swish App...</h1>
</body>
</html>
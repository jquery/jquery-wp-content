$(document).ready(function() {
    // Tracks clicks of the plugin download button
    $("a.download").click(function(event) {
        // Query parameter distinguishes click tracking request from normal page load
        var clickTrackerURL = window.location + "?d=1";
        var downloadURL = $(this).attr("href");
        
        event.preventDefault();

        $.ajax({
            type: "HEAD",
            url: clickTrackerURL
        }).done(function() {
            window.location = downloadURL;
        });
    });
});
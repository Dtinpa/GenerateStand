$(document).ready(function() {
    $(".endpointListBullet").click(function (e) {
        e.preventDefault();
        $("#endpointDesc").empty();
        var endpointName = $(this).attr('class').split(/\s+/)[1];
        
        $.ajax({
            url: 'api.php',
            type: 'GET',
            data: {
                action: "getEndpoint",
                page: endpointName
            },
            dataType: "html",
            success: function(result) {
                $("#endpointDesc").html(result);
            }
        });
    });

    $(".getAPIKey").click(function (e) {
        e.preventDefault();

        var email = $("#emailBox").val();
        $.ajax({
            url: 'api.php',
            type: 'GET',
            data: {
                action: "generateKey",
                email: email
            },
            dataType: "html",
            success: function(result) {
                $("#endpointDesc").html(result);
            }
        });
    });

});

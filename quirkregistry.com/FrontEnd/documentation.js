$(document).ready(function() {
    $(".endpointButton").click(function (e) {
        e.preventDefault();
        $("#endpointDesc").empty();
        var endpointName = $(this).attr('class').split(/\s+/)[2];
        
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

        var userValidation = ((tok = $('#validate').attr('data-t')) ? tok : "");
        var email = $("#emailBox").val();
        $.ajax({
            url: 'api.php',
            type: 'GET',
            data: {
                action: "generateKey",
                email: email,
                token: userValidation
            },
            dataType: "html",
            success: function(result) {
                $("#endpointDesc").html(result);
            }
        });
    });

});

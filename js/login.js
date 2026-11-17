
$(document).ready(function () {

    $("#loginForm").submit(function (event) {

        event.preventDefault();

        $.ajax({

            url: "php/login.php",
            type: "POST",
            dataType: "json",
            data: $(this).serialize(),

            success: function (response) {

                $("#message").html(response.message);

                if (response.status === "success") {
                window.location.href = "profile.html";
                }

            },

                error: function (xhr, status, error) {
                    $("#message").html(
                    "HTTP Status: " + xhr.status +
                    "<br>Error: " + error +
                    "<br>Response: " + xhr.responseText
        );
    }

        });

    });

});

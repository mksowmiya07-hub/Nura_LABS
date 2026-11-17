
$(document).ready(function () {

    // Get logged-in user's profile
    $.ajax({

        url: "php/profile.php",
        type: "GET",
        dataType: "json",

        success: function (response) {

            if (response.status === "success") {

                $("#fullname").text(response.user.full_name);
                $("#email").text(response.user.email);
                $("#created_at").text(response.user.created_at);

            } else {

                $("#message").text(response.message);

                window.location.href = "login.html";
            }

        },

        error: function () {
            $("#message").text("Unable to load profile!");
        }

    });

});

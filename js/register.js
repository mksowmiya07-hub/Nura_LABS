$(document).ready(function () {

    $("#registerForm").submit(function (event) {

        event.preventDefault();

        $.ajax({

            url: "php/register.php",
            type: "POST",
            data: $(this).serialize(),

            success: function (response) {

                $("#message").html(response.message);

                if (response.status === "success") {
                    $("#registerForm")[0].reset();
                }

            },

            error: function () {
                $("#message").html("Something went wrong!");
            }

        });

    });

});
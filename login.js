$(document).ready(function () {
  // console.log("Document is ready");

  $("#loginForm").submit(function (event) {
    event.preventDefault(); // Prevent the default form submission (page refresh)
    // console.log("Form submitted");

    // Clear previous messages
    $("#loginMessage").html("");

    // Get the form fields
    const email = $("input[name='email']").val().trim();
    const password = $("input[name='password']").val().trim();

    // Validate inputs
    if (email === "" || password === "") {
      // console.log("Email or password is empty");
      $("#loginMessage").html(
        "<p class='error'>Please fill in both email and password.</p>"
      );
      return; // Don't submit the form if validation fails
    }

    // Disable button to prevent multiple clicks
    const $button = $(this).find("button[type='submit']");
    $button.prop("disabled", true).text("Logging in...");

    // Log form data
    console.log("Form data:", { email, password });

    // Form data
    const formData = { email, password };

    $.ajax({
      url: "login.php", // Ensure this path is correct
      type: "POST",
      data: formData,
      dataType: "json", // Expecting a JSON response
      success: function (response) {
        console.log("AJAX Response:", response); // Log the response to check
        if (response.status === "success") {
          // Redirect to the appropriate page
          console.log("Redirecting to:", response.redirect);
          if (response.redirect) {
            window.location.href = response.redirect; // Redirect to the appropriate page
          } else {
            $("#loginMessage").html(
              "<p class='error'>Redirect URL not found.</p>"
            );
          }
        } else {
          console.log("Login failed:", response.message); // Log failure message
          $("#loginMessage").html(`<p class='error'>${response.message}</p>`);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", status, error);
        console.log("XHR Response:", xhr.responseText); // Log the detailed response error
        $("#loginMessage").html(
          `<p class='error'>An error occurred: ${xhr.responseText}. Please try again.</p>`
        );
      },
      complete: function () {
        // Re-enable button after request completes (either success or error)
        $button.prop("disabled", false).text("Login");
      },
    });
  });
});

//Toggle between log in and sign up
$(".toggleBox").click(function() {
    $("#logInbox").toggle();
    $("#signUpbox").toggle();
});

//POST of text to be sent to database
$('#texta').on('input', function() {
    $.ajax({
        type: "POST",
        url: "updatedb.php",
        data: {
            content: $("#texta").val()
        }
    })
});

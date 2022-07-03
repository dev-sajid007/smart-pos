
// Get the modal
let modal = $("#myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
// var img = document.getElementById("myImg");

let modalImg = $("#img01");
// var captionText = document.getElementById("caption");

$('.full-screen-image').click(function () {
    // console.log($(this).attr('src'))
    $("#myModal").css("display" , "block");
    $("#img01").attr('src', $(this).attr('src'))
    // captionText.innerHTML = (this).alt;
})


// Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
$('#img01').click(function () {
    $("#myModal").css("display", "none");
})

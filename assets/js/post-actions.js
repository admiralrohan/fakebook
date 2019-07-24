function checkWidth() {
  $("#container").removeClass();
  $("#container").addClass("mx-auto my-3");

  if ($(window).width() < 640) {
    $("#container").addClass("w-100");
  } else if ($(window).width() > 641 && $(window).width() < 960) {
    $("#container").addClass("w-75");
  } else if ($(window).width() > 961) {
    $("#container").addClass("w-50");
  }
}

$(document).ready(function() {
  // checkWidth();
  // console.log($("#container").attr("class"));

  // $(window).resize(function() {
  //   checkWidth();
  //   console.log($("#container").attr("class"));
  // });

  $(".like-count").on("click", function(event) {
    event.preventDefault();

    // console.log($(this).attr("class"));

    if ($(this).data("likes")) {
      // console.log($(this).data("likes"));
      // $('#post-liked-by-modal').modal({
      //     show: true
      // });
    }
  });

  $(".share-post").on("click", function(event) {
    event.preventDefault();

    $("#share-post-modal").modal({
      show: true
    });
    $("#post-content").focus();
  });

  $(".no-of-comments").on("click", function(event) {
    event.preventDefault();
  });

  $("#comment-link").on("click", function(event) {
    event.preventDefault();
    $("#comment-input").focus();
  });

  $("#comment-input").keypress(function(e) {
    if (e.which == 13) {
      $("form#comment-form").submit();
      return false;
    }
  });

  $('[data-toggle="tooltip"]').tooltip();
});

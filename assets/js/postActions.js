(function($) {
  // Focus input at the end of the text
  $.fn.focusTextToEnd = function() {
    let initialVal = this.val();
    return this.focus()
      .val("")
      .val(initialVal);
  };
})(jQuery);

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
  $("textarea").on("keyup", function() {
    this.style.height = "1px";
    this.style.height = 12 + this.scrollHeight + "px";
  });

  $(".like-post").on("click", likePostActions);
  $(".post-like-count").on("click", postLikeCountActions);
  $(".share-post").on("click", sharePostActions);
  $("#share-post-submit").on("click", sharePostSubmit);

  $(".edit-post").on("click", editPostActions);
  $(".delete-post").on("click", deletePostActions);

  $(".comment-count").on("click", function(event) {
    event.preventDefault();
  });

  $(".comment-post").on("click", function(event) {
    event.preventDefault();
    $(this)
      .closest(".card")
      .find(".comment-input")
      .focus();
  });

  $(".comment-input").on("keydown", postNewCommentActions);

  $(".like-comment").on("click", likeCommentActions);
  $(".comment-like-count").on("click", commentLikeCountActions);
  $(".edit-comment").on("click", editCommentActions);
  $(".delete-comment").on("click", deleteCommentActions);
});

function goToIndexPage() {
  window.location.href = "index.php?from=none";
}

function likePostActions(event) {
  event.preventDefault();

  let id = $(this)
    .closest(".card")
    .data("id");
  let isPostLikedByUser = $(this).data("isLiked");
  let url = isPostLikedByUser ? "ajax/dislike_post.php" : "ajax/like_post.php";

  $.ajax({
    type: "POST",
    url,
    data: { id },
    dataType: "json",
    success: function(res) {
      if (res.success) {
        $.ajax({
          type: "POST",
          url: "ajax/ajax_post_liked_by_users.php",
          data: { id },
          dataType: "json",
          success: function(res) {
            $.ajax({
              type: "POST",
              url: "ajax/update_like_count_post.php",
              data: { users: res.users },
              dataType: "json",
              success: function(res) {
                if (res.success) {
                  $(`#post-${id} .post-like-count`).replaceWith(res.content);
                  $(`#post-${id} .post-like-count`).on(
                    "click",
                    postLikeCountActions
                  );
                  $('[data-toggle="tooltip"]').tooltip();
                }
              }
            });
          }
        });

        $.ajax({
          type: "POST",
          url: "ajax/ajax_is_post_liked_by_user.php",
          data: { id },
          dataType: "json",
          success: function(res) {
            $.ajax({
              type: "POST",
              url: "ajax/update_like_button_post.php",
              data: { isLiked: res.isLiked },
              dataType: "json",
              success: function(res) {
                if (res.success) {
                  $(`#post-${id} .like-post`).replaceWith(res.content);
                  $(`#post-${id} .like-post`).on("click", likePostActions);
                }
              }
            });
          }
        });
      } else {
        if ("errorCode" in res) {
          if (res.errorCode === 0) {
            goToIndexPage();
          }
        }
      }
    }
  });
}

function postLikeCountActions(event) {
  event.preventDefault();

  if ($(this).data("likes")) {
    let likedUsersString = $(this)
      .data("originalTitle")
      .split("<br>");
    likedUsersString.pop();

    const likedUsersJq = [];
    likedUsersString.forEach(user => {
      likedUsersJq.push($($.parseHTML(user)));
    });

    const likedUsers = [];
    likedUsersJq.forEach(user => {
      likedUsers.push({
        id: user.data("id"),
        name: user.html(),
        friendshipStatus: user.data("status")
      });
    });

    // Update the modal content with new data
    $("#post-liked-by-modal .modal-header").load(
      "ajax/print_modal_liked_users_header.php",
      { likedUsers }
    );
    $("#post-liked-by-modal .modal-body").load(
      "ajax/print_modal_liked_users_body.php",
      { likedUsers }
    );

    // Show modal
    $("#post-liked-by-modal").modal({
      show: true
    });
  }
}

function sharePostActions(event) {
  event.preventDefault();

  let id = $(this)
    .closest(".card")
    .data("id");

  $("#share-post-modal .modal-body").load(
    "ajax/print_modal_share_post_body.php",
    { id }
  );

  $("#share-post-modal").modal({
    show: true
  });

  $("#share-post-modal").on("shown.bs.modal", function(event) {
    $("#post-id").val(id); // Used to store which post to share in a hidden input field
    $("#post-content").focus();
  });
}

function sharePostSubmit() {
  const id = $("#post-id").val();
  const content = $("#post-content").val();

  $.ajax({
    type: "POST",
    url: "ajax/share_post.php",
    data: { id, content },
    dataType: "json",
    success: function(res) {
      $("#share-post-modal").modal("hide");

      if (res.success) {
        const alertHTMLString = `
          <div class="alert alert-primary fade show" role="alert">
            <span>
              Your post has been shared. View <a href="${res.newPostUrl}" class="alert-link">your new post</a>.
            </span>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;

        const $alert = $($.parseHTML(alertHTMLString));
        // TODO: Scroll to the top of the alert box after inserting, scrolltop with animation
        $alert
          .insertBefore($(`#post-${id}`))
          .find("button")
          .delay(4000)
          .queue(function() {
            $(this).trigger("click");
          });
      } else {
        if ("errorCode" in res) {
          if (res.errorCode === 0) {
            goToIndexPage();
          } else {
          }
        }
      }
    }
  });
}

function editPostActions(event) {
  event.preventDefault();

  const $post = $(this).closest(".card");
  const $postHeader = $post.find(".card-header");
  const $postBody = $post.find(".card-body");

  let postContent = $postBody
    .find(".card-text")
    .eq(0)
    .html()
    .split("<br>")
    .join("");
  console.log(postContent);

  $postBody.find(".post-content").hide();
  $postHeader.find(".post-crud-options").fadeOut();

  const editPostString = `
    <div class="form-group">
      <textarea type="text" class="form-control form-control-sm edit-post-input overflow-hidden" rows="3" placeholder="Write something here..." name="post_content">${postContent}</textarea>
      <div class="mt-1">
        <span class="edit-post-guide">Press Esc to </span>
        <a href="#" class="edit-post-input-cancel">cancel</a>
      </div>
    </div>`;

  const $editPost = $($.parseHTML(editPostString))
    .hide()
    .prependTo($postBody)
    .fadeIn(1000);

  const $editPostInput = $editPost.find(".edit-post-input").focusTextToEnd();

  // You can check the content's height by setting to 1px and then reading the scrollHeight property
  $editPostInput.on("keyup", function() {
    this.style.height = "1px";
    this.style.height = 12 + this.scrollHeight + "px";
  });

  function editPostCloseActions() {
    $editPost.remove();
    $postBody.find(".post-content").fadeIn();
    $postHeader.find(".post-crud-options").fadeIn();
  }

  $editPostInput.keydown(function(e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      const id = $(this)
        .closest(".card")
        .data("id");
      const content = $(this).val();

      if (content === postContent) {
        editPostCloseActions();
      } else {
        $.ajax({
          type: "POST",
          url: "ajax/update_post.php",
          data: { id, content },
          dataType: "json",
          success: function(res) {
            $postBody
              .find(".post-content .card-text")
              .eq(0)
              .html(res.content);

            editPostCloseActions();
          }
        });
      }
    } else if (e.key === "Escape") {
      editPostCloseActions();
    }
  });

  $editPost.find(".edit-post-input-cancel").on("click", function(event) {
    event.preventDefault();
    editPostCloseActions();
  });

  // Show or hide the guide for cancel button
  $editPostInput.on("focusin", function() {
    $editPost.find(".edit-post-guide").show();
  });

  $editPostInput.on("focusout", function() {
    $editPost.find(".edit-post-guide").hide();
  });
}

function deletePostActions(event) {
  event.preventDefault();

  const id = $(this)
    .closest(".card")
    .data("id");

  const alertHTMLString = `
    <div class="alert alert-danger fade show" role="alert">
      <span>
        Are you sure you want to delete this post?
        <a href="#" class="alert-link delete-post-yes">Yes</a>&nbsp;or
        <a href="#" class="alert-link delete-post-no">No</a>
      </span>

      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>`;

  const $alert = $($.parseHTML(alertHTMLString));
  $alert
    .insertBefore($(`#post-${id}`))
    .find("button")
    .delay(5000)
    .queue(function() {
      $(this).trigger("click");
    });

  $alert.find(".delete-post-yes").on("click", function(event) {
    event.preventDefault();
    $(this)
      .closest(".alert")
      .fadeOut(function() {
        $(this).remove();
      });

    $.ajax({
      type: "POST",
      url: "ajax/delete_post.php",
      data: { id },
      dataType: "json",
      success: function(res) {
        if (res.success) {
          $(`#post-${id}`).fadeOut(3000);

          const alertHTMLString = `
          <div class="alert alert-primary fade show" role="alert">
            <span>
              Your post has been deleted.
            </span>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;

          const $alert = $($.parseHTML(alertHTMLString));
          // TODO: Scroll to the top of the alert box after inserting, scrolltop with animation
          $alert
            .insertBefore($(`#post-${id}`))
            .find("button")
            .delay(1500)
            .queue(function() {
              $(this).trigger("click");

              $(`#post-${id}`).remove();
              if (window.location.pathname.includes("post.php")) {
                window.location.replace("profile.php");
              }
            });
        } else {
          if ("errorCode" in res) {
            if (res.errorCode === 0) {
              goToIndexPage();
            } else {
            }
          }
        }
      }
    });
  });

  $alert.find(".delete-post-no").on("click", function(event) {
    event.preventDefault();
    $alert.find("button").trigger("click");
  });
}

function likeCommentActions(event) {
  event.preventDefault();

  const id = $(this)
    .closest(".comment")
    .data("id");

  let isCommentLikedByUser = $(this).data("isLiked");
  let url = isCommentLikedByUser
    ? "ajax/dislike_comment.php"
    : "ajax/like_comment.php";

  $.ajax({
    type: "POST",
    url,
    data: { id },
    dataType: "json",
    success: function(res) {
      if (res.success) {
        $.ajax({
          type: "POST",
          url: "ajax/ajax_comment_liked_by_users.php",
          data: { id },
          dataType: "json",
          success: function(res) {
            $.ajax({
              type: "POST",
              url: "ajax/update_like_count_comment.php",
              data: { users: res.users },
              dataType: "json",
              success: function(res) {
                if (res.success) {
                  $(`#comment-${id} .comment-like-count`).replaceWith(
                    res.content
                  );
                  $(`#comment-${id} .comment-like-count`).on(
                    "click",
                    postLikeCountActions
                  );
                  $('[data-toggle="tooltip"]').tooltip();
                }
              }
            });
          }
        });

        $.ajax({
          type: "POST",
          url: "ajax/ajax_is_comment_liked_by_user.php",
          data: { id },
          dataType: "json",
          success: function(res) {
            $.ajax({
              type: "POST",
              url: "ajax/update_like_button_comment.php",
              data: { isLiked: res.isLiked },
              dataType: "json",
              success: function(res) {
                if (res.success) {
                  $(`#comment-${id} .like-comment`).replaceWith(res.content);
                  $(`#comment-${id} .like-comment`).on(
                    "click",
                    likeCommentActions
                  );
                }
              }
            });
          }
        });
      } else {
        if ("errorCode" in res) {
          if (res.errorCode === 0) {
            goToIndexPage();
          }
        }
      }
    }
  });
}

function commentLikeCountActions() {
  let likedUsersString = $(this)
    .data("originalTitle")
    .split("<br>");
  likedUsersString.pop();

  const likedUsersJq = [];
  likedUsersString.forEach(user => {
    likedUsersJq.push($($.parseHTML(user)));
  });

  const likedUsers = [];
  likedUsersJq.forEach(user => {
    likedUsers.push({
      id: user.data("id"),
      name: user.html(),
      friendshipStatus: user.data("status")
    });
  });

  // Update the modal content with new data, reusing same modal for post liked users
  $("#post-liked-by-modal .modal-header").load(
    "ajax/print_modal_liked_users_header.php",
    { likedUsers }
  );
  $("#post-liked-by-modal .modal-body").load(
    "ajax/print_modal_liked_users_body.php",
    { likedUsers }
  );

  // Show modal
  $("#post-liked-by-modal").modal({
    show: true
  });
}

function editCommentActions(event) {
  event.preventDefault();

  const $comment = $(this).closest(".comment");
  let commentContent = $comment.find(".comment-content").html();

  $comment.find(".comment-owner").hide();
  $comment.find(".comment-content").hide();
  $comment.find(".comment-actions").hide();

  const editCommentString = `
    <div class="form-group">
      <textarea type="text" class="form-control form-control-sm edit-comment-input overflow-hidden" rows="1" placeholder="Write a comment..." name="comment">${commentContent}</textarea>
      <div class="mt-1">
        <span class="edit-comment-guide">Press Esc to </span>
        <a href="#" class="edit-comment-input-cancel">cancel</a>
      </div>
    </div>`;

  const $editComment = $($.parseHTML(editCommentString))
    .hide()
    .prependTo($comment)
    .fadeIn(1000);

  const $editCommentInput = $editComment
    .find(".edit-comment-input")
    .focusTextToEnd();

  // You can check the content's height by setting to 1px and then reading the scrollHeight property
  $editCommentInput.on("keyup", function() {
    this.style.height = "1px";
    this.style.height = 12 + this.scrollHeight + "px";
  });

  $editCommentInput.keydown(function(e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      const id = $(this)
        .closest(".comment")
        .data("id");
      const content = $(this).val();

      $.ajax({
        type: "POST",
        url: "ajax/update_comment.php",
        data: { id, content },
        dataType: "json",
        success: function(res) {
          $comment.find(".comment-content").html(res.content);

          $editComment.remove();
          $comment.find(".comment-owner").fadeIn();
          $comment.find(".comment-content").fadeIn();
          $comment.find(".comment-actions").fadeIn();
        }
      });
    } else if (e.key === "Escape") {
      $editComment.remove();
      $comment.find(".comment-owner").fadeIn();
      $comment.find(".comment-content").fadeIn();
      $comment.find(".comment-actions").fadeIn();
    }
  });

  $editComment.find(".edit-comment-input-cancel").on("click", function(event) {
    event.preventDefault();

    $editComment.remove();
    $comment.find(".comment-owner").fadeIn();
    $comment.find(".comment-content").fadeIn();
    $comment.find(".comment-actions").fadeIn();
  });

  // Show or hide the guide for cancel button
  $editCommentInput.on("focusin", function() {
    $editComment.find(".edit-comment-guide").show();
  });

  $editCommentInput.on("focusout", function() {
    $editComment.find(".edit-comment-guide").hide();
  });
}

function deleteCommentActions(event) {
  event.preventDefault();

  const id = $(this)
    .closest(".comment")
    .data("id");

  const alertHTMLString = `
    <div class="alert alert-danger fade show" role="alert">
      <span>
        Are you sure you want to delete this post?
        <a href="#" class="alert-link delete-comment-yes">Yes</a>&nbsp;or
        <a href="#" class="alert-link delete-comment-no">No</a>
      </span>

      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>`;

  const $alert = $($.parseHTML(alertHTMLString));
  $alert
    .insertBefore($(`#comment-${id}`))
    .find("button")
    .delay(5000)
    .queue(function() {
      $(this).trigger("click");
    });

  $alert.find(".delete-comment-yes").on("click", function(event) {
    event.preventDefault();
    $(this)
      .closest(".alert")
      .fadeOut(function() {
        $(this).remove();
      });

    $.ajax({
      type: "POST",
      url: "ajax/delete_comment.php",
      data: { id },
      dataType: "json",
      success: function(res) {
        if (res.success) {
          $(`#comment-${id}`).fadeOut(3000);

          const alertHTMLString = `
          <div class="alert alert-primary fade show" role="alert">
            <span>
              Your comment has been deleted.
            </span>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;

          const $alert = $($.parseHTML(alertHTMLString));
          // TODO: Scroll to the top of the alert box after inserting, scrolltop with animation
          $alert
            .insertBefore($(`#comment-${id}`))
            .find("button")
            .delay(1500)
            .queue(function() {
              $(this).trigger("click");

              // To delete the <hr>
              $(`#comment-${id}`)
                .next()
                .remove();
              $(`#comment-${id}`).remove();
            });
        } else {
          if ("errorCode" in res) {
            if (res.errorCode === 0) {
              goToIndexPage();
            } else {
            }
          }
        }
      }
    });
  });

  $alert.find(".delete-comment-no").on("click", function(event) {
    event.preventDefault();
    $alert.find("button").trigger("click");
  });
}

function postNewCommentActions(e) {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();

    const postId = $(this)
      .closest(".card")
      .data("id");
    const $commentInput = $(this);
    const content = $commentInput.val();

    $.ajax({
      type: "POST",
      url: "ajax/post_comment.php",
      data: { id: postId, content },
      dataType: "json",
      success: function(res) {
        const id = res.commentId;
        $commentInput.val("");

        $.ajax({
          type: "POST",
          url: "ajax/ajax_load_comment_without_like.php",
          data: { id },
          dataType: "json",
          success: function(res) {
            if (res.success) {
              const $comment = $($.parseHTML(res.commentBody));
              $comment.find(".like-comment").on("click", likeCommentActions);
              $comment.find(".edit-comment").on("click", editCommentActions);
              $comment
                .find(".delete-comment")
                .on("click", deleteCommentActions);

              $comment
                .hide()
                .prependTo(`#post-${postId} .comments`)
                .fadeIn(1000);
            }
          }
        });
      }
    });
  } else if (e.key === "Escape") {
    $(this).val("");
    $(this).blur();
  }
}

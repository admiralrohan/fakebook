<div class="card p-3 mb-3">
    <form method="POST" action="timeline.php">
        <div class="form-group">
            <label for="post_content" class="font-weight-bold">Create Post</label>
            <?php include("./includes/show_success.php"); ?>
            <?php include("./includes/show_errors.php"); ?>

            <textarea class="form-control overflow-hidden" id="post_content" rows="3" name="post_content" placeholder="Write something here..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-sm mb-2">Create New Post</button>
    </form>
</div>
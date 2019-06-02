<?php
// Add Friend
$q = "SELECT request_id from friend_requests where (request_from = {$own_id} && request_to = {$profile_id}) && (request_status = 'pending' || request_status = 'accepted') UNION
SELECT request_id from friend_requests where (request_from = {$profile_id} && request_to = {$own_id}) && (request_status = 'pending' || request_status = 'accepted')";
$result = $db->query($q);

if ($result->num_rows == 0 && $profile_id != $own_id) {
?>
<span>
    <a href="utilities/add_friend.php?id=<?= $profile_id ?>" class="btn btn-sm btn-primary">Add Friend <i class="fas fa-user-plus"></i></a>
</span>
<?php } ?>

<?php
// Friend
$q = "SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'accepted'";
$result = $db->query($q);

if ($result->num_rows == 1) {
?>
<span>
    <a class="btn btn-sm btn-primary dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Friend <i class="fas fa-user-friends"></i>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="utilities/unfriend.php?id=<?= $profile_id ?>">Unfriend</a>
    </div>
</span>
<?php } ?>

<?php
// Friend request sent
$q = "SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'pending'";
$result = $db->query($q);

if ($result->num_rows) {
?>
<span>
    <a class="btn btn-sm btn-primary dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Friend Request Sent <i class="fas fa-user-friends"></i>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="utilities/cancel_request.php?id=<?= $profile_id ?>">Cancel Request</a>
    </div>
</span>
<?php } ?>

<?php
// Respond to friend request
$q = "SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'pending'";
$result = $db->query($q);

if ($result->num_rows) {
?>
<span>
    <a class="btn btn-sm btn-primary dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Respond to Friend Request <i class="fas fa-user-friends"></i>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="utilities/confirm_request.php?id=<?= $profile_id ?>">Confirm</a>
        <a class="dropdown-item" href="utilities/delete_request.php?id=<?= $profile_id ?>">Delete Request</a>
    </div>
</span>
<?php } ?>
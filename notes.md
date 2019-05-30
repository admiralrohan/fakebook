# Timeline
post_content => post_content of form
post_owner => $_SESSION["user_id"]

# Profile

# Posts_by_user
Sends back posts by a particular user id

# Profile?id=2
if ($_GET["user_id"]) == $_SESSION["user_id"])
show create post option, edit and delete post option
else if ("Id not found in users table")
else
# Send friend request

# Add friends
Friend_requests => request_id, request_from, requested_to, requested_on, request_status (pending, accepted, rejected)
23, Niladri (user_id => 22), Rohan (user_id => 2), 27.05.19 2pm, pending

# Reject sent friends request

# Fetch pending friend requests
# Fetch friend list
Select user_id, user_name from users where user_id IN
((Select * from friend_requests where request_from = me && request_status = accepted) UNION
(Select * from friend_requests where request_to = me && request_status = accepted))

# Fetch posts timeline
Sends back posts by id In (Friend List)


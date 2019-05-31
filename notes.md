# File structure
assets => all css, image and js files
classes => class structures
includes => all view files which are not used as standalone view file but to be added into other view files
utilities => doesn't have any view associated with it but do some action between views
other files in the root => main view files

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


# Friends
Friend_requests => request_id, request_from, requested_to, requested_on, request_status (pending, accepted, rejected)
23, Niladri (user_id => 22), Rohan (user_id => 2), 27.05.19 2pm, pending

# Add friend
request_from => $_SESSION["user_id"]
request_to => $_GET["id"]
request_status => pending

Use trigger to privent sending friend request to myself.
Trigger to check whether there is already sent friend request to the same user or not.

# Confirm incoming friend request
request_from => $_GET["id"]
request_to => $_SESSION["user_id"]
request_status => pending

# Delete incoming friend Request
request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $_GET["id"]
request_status => accepted

# Cancel sent friend request
request_from => $_SESSION["user_id"]
request_to => $_GET["id"]
request_status => accepted

# Unfriend already accepted friend
request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $_GET["id"]
request_status => accepted

# Received friend requests => confirm, delete request
Select user_id, user_name from users where user_id IN
(Select request_from from friend_requests where request_to = me && request_status = pending)

# Sent friends requests => unfriend
Select user_id, user_name from users where user_id IN
(Select request_to from friend_requests where request_from = me && request_status = pending)

# Fetch friend list => unfriend
Select user_id, user_name from users where user_id IN
((Select request_to from friend_requests where request_from = me && request_status = accepted) UNION
(Select request_from from friend_requests where request_to = me && request_status = accepted))


# Fetch posts timeline
Sends back posts by id In (Friend List)

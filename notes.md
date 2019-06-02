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

# 5 actions can be made to a profile
Add friend => no record
Unfriend => accepted
Cancel Request => pending
Confirm => pending
Delete Request => pending

# Add friend
request_from => $_SESSION["user_id"]
request_to => $_GET["id"]
request_status => pending (Make)

Use trigger to privent sending friend request to myself.
Trigger to check whether there is already sent friend request to the same user or not.

First check if the user_id exists or not
Check if he is already a friend / there already a request from that person / already a request sent to that person

SELECT request_id from friend_requests where request_from = {$own_id} && request_to = {$friend_id} where request_status = 'pending' || request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} where request_status = 'pending' || request_status = 'accepted'

# Confirm incoming friend request
request_from => $_GET["id"]
request_to => $_SESSION["user_id"]
request_status => accepted

Check if there any incoming friend request from that person (The request can't be sent maliciously because of those extra checks in Add Friend procedure)
Doesn't matter whether the user_id exists or not because we are not inserting new entry into the DB, just updating existing entry if exists

SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'accepted' && request_on = NOW() where request_id = (from previous query)

# Delete incoming friend Request
request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $_GET["id"]
request_status => rejected

Check if there any incoming friend request from that person (The request can't be sent maliciously because of those extra checks in Add Friend procedure)
Doesn't matter whether the user_id exists or not because we are not inserting new entry into the DB, just updating existing entry if exists

SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'rejected' && request_on = NOW() where request_id = (from previous query)

# Cancel sent friend request
request_from => $_SESSION["user_id"]
request_to => $_GET["id"]
request_status => rejected

SELECT request_id from friend_requests where request_to = {$friend_id} && request_from = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'rejected' && request_on = NOW() where request_id = (from previous query)

# Unfriend already accepted friend
request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $_GET["id"]
request_status => rejected

Request can be sent from both sides in this case.

SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} && request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_to = {$friend_id} && request_from = {$own_id} && request_status = 'accepted'

UPDATE friend_requests SET request_status = 'rejected' && request_on = NOW() where request_id = (from previous query)


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


# Fetch posts for timeline
Sends back posts by id In (Friend List)

Select post_id, post_content, post_owner as post_owner_id, CONCAT(fname, ' ', lname) as post_owner_name from posts inner join users where post_owner in
((Select request_to from friend_requests where request_from = me && request_status = accepted) UNION
(Select request_from from friend_requests where request_to = me && request_status = accepted))

# Get friendship status for a profile
4 options in profile page:

Add friend
If he is not friend already / if no request has been sent already from either side

SELECT request_id from friend_requests where (request_from = {$own_id} && request_to = {$profile_id}) && (request_status = 'pending' || request_status = 'accepted') UNION
SELECT request_id from friend_requests where (request_from = {$profile_id} && request_to = {$own_id}) && (request_status = 'pending' || request_status = 'accepted')
If no result returned

Friend
SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'accepted'

Friend request sent
SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'pending'

Respond to friend request
SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'pending'

# Mutual Friend


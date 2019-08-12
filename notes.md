# File structure

assets => all css, image and js files
classes => class structures
includes => all view files which are not used as standalone view file but to be added into other view files
utilities => doesn't have any view associated with it but do some action between views
other files in the root => main view files

fetch files in /includes are PHP functions while fetch files in /utilities are AJAX calls

# Timeline

post_content => post_content of form
post_owner => \$\_SESSION["user_id"]

# Profile

# Posts_by_user

Sends back posts by a particular user id

# Profile?id=2

if ($_GET["user_id"]) == $\_SESSION["user_id"])
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
request_to => $\_GET["id"]
request_status => pending (Make)

Use trigger to privent sending friend request to myself.
Trigger to check whether there is already sent friend request to the same user or not.

First check if the user_id exists or not
Check if he is already a friend / there already a request from that person / already a request sent to that person

SELECT request_id from friend_requests where request_from = {$own_id} && request_to = {$friend_id} where request_status = 'pending' || request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} where request_status = 'pending' || request_status = 'accepted'

# Confirm incoming friend request

request_from => $_GET["id"]
request_to => $\_SESSION["user_id"]
request_status => accepted

Check if there any incoming friend request from that person (The request can't be sent maliciously because of those extra checks in Add Friend procedure)
Doesn't matter whether the user_id exists or not because we are not inserting new entry into the DB, just updating existing entry if exists

SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'accepted' && request_on = NOW() where request_id = (from previous query)

# Delete incoming friend Request

request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $\_GET["id"]
request_status => rejected

Check if there any incoming friend request from that person (The request can't be sent maliciously because of those extra checks in Add Friend procedure)
Doesn't matter whether the user_id exists or not because we are not inserting new entry into the DB, just updating existing entry if exists

SELECT request_id from friend_requests where request_from = {$friend_id} && request_to = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'rejected' && request_on = NOW() where request_id = (from previous query)

# Cancel sent friend request

request_from => $_SESSION["user_id"]
request_to => $\_GET["id"]
request_status => rejected

SELECT request_id from friend_requests where request_to = {$friend_id} && request_from = {$own_id} && request_status = 'pending'
UPDATE friend_requests SET request_status = 'rejected' && request_on = NOW() where request_id = (from previous query)

# Unfriend already accepted friend

request_from/request_to => $_SESSION["user_id"]
request_to/request_from => $\_GET["id"]
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

Add friend = 1
If he is not friend already / if no request has been sent already from either side

SELECT request_id from friend_requests where (request_from = {$own_id} && request_to = {$profile_id}) && (request_status = 'pending' || request_status = 'accepted') UNION
SELECT request_id from friend_requests where (request_from = {$profile_id} && request_to = {$own_id}) && (request_status = 'pending' || request_status = 'accepted')
If no result returned

Friend = 2
SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'accepted' UNION
SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'accepted'

Friend request sent = 3
SELECT request_id from friend_requests where request_to = {$profile_id} && request_from = {$own_id} && request_status = 'pending'

Respond to friend request = 4
SELECT request_id from friend_requests where request_from = {$profile_id} && request_to = {$own_id} && request_status = 'pending'

# Mutual Friend

Must sent an user_id
My friend list INTERSECT My friend's friend list

SELECT value FROM table_a
INTERSECT
SELECT value FROM table_b

(1, 2, 4, 5) ^ (2, 3, 4, 6) = (2, 3, 4)
Select (1, 2, 4, 5) ^ (2, 3, 4, 6)

SELECT value FROM table_a
WHERE (value) IN
(SELECT value FROM table_b);

SELECT user_id, CONCAT(fname, ' ', lname) as user_name from users where user_id IN
(SELECT request_from from friend_requests where request_to = {$profile_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$profile_id} && request_status = 'accepted') INTERSECT
(SELECT request_from from friend_requests where request_to = {$friend_id} && request_status = 'accepted' UNION
SELECT request_to from friend_requests where request_from = {$friend_id} && request_status = 'accepted')

Make seperate file to show friendship status in Profile Page, which will be reused in Mutual friend page.

2 => 13, 14
13 => 2
14 => 2
So mutual friend of 13 and 14 is 2.

# Search profiles (id is must)

# Likes in post

# Comments in post

# Messages

message.php?id=13 => message from Rohan to John
INSERT into messages (msg_id, msg_content, msg_from, msg_to, msgd_on) VALUES (NULL, '$content', $own_id, \$friend_id, NOW())

class Message {
msg_id, msg_content, msg_from_id, msg_from_name, msg_to_id, msg_to_name, msgd_on
}

# Fetch conversations between two users

Msgs sent from me to him + Msgs sent from him to me => sort them ASC

SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$own_id} && msg_to = {$friend_id} UNION SELECT msg_id, msg_content, msg_from, msg_to, msgd_on from messages where msg_from = {$friend_id} && msg_to = {$own_id}

# Next agenda: 20.06.19

Like a post
Fetch post liked by users
Unlike a post (only for session_id)

Comment on a post
Fetch comments for a post
Delete existing comment
Can only delete own comment

# comments

comment_id, comment_content, post_id (posts), comment_owner (users), commented_on

SELECT comment_id, comment_content, comment_owner as comment_owner_id, CONCAT(fname, ' ', lname) as comment_owner_name commented_on from comments as c INNER JOIN users as u ON c.comment_owner = u.user_id where post_id = 4

# Inbox

Need to find all messages where \$profile_id is associated
Create new associate array User => Message

# Next tasks 30.06.19

Share Posts DONE
Edit Post (Own)
Delete Post (Own)
Edit Comment (Own)
Delete Comment (Own)
Copying single post page functionalities into timeline and individual profile pages (i.e like count, modal)
Inbox
All users (Find friends)
Add edit and delete button for own posts DONE
Disable edit and delete button for comments of other users
Disable edit and delete button for posts of other users DONE
Like comment

shared_posts => will refer to another post
shared_post_id, shared_post_content, original_post (id => foreign key), shared_post_owner, post_shared_on
If someone else shared a shared post, then that post will refer to the original post always

add original_post column in posts table, which will be null for original posts

Suppose user 2 shared post 4 of user 13 => post id 45 => original post id 4
user 14 shared post 45 => post id 47 => original post id 4

share a post logic =>
suppose I want to share post 4 => is post 4 shared post => no, so original post id = 4 => new post id 45
suppose I want to share post 45 => is post 45 shared post => yes, so original post id = original post id of post 45 = 4

# Next ideas

Group
Page
Tag friends to post
Tag friends in comment
Add Feelings to post
Share on your timeline (Default + completed) / friend's timeline / group / page you manage / share in private message
Notifications
Post privacy settings

# Database trigger for Create Post

if is_shared_post = false, post_content can't be null

New post created splash message

# Adding ajax for post operations

Each card represents a post, on which we will call each operation without refreshing the page or redirecting anywhere
Timeline and profile page has N posts, individual post pages have 1 post

# Post operations

Like post
Comment on post
Share post
Edit post
Delete post (with a modal)
Comment like, edit, delete

Each post have "post-4" id
comment-17 id
and id value as data property

When a action button is clicked, we will track the parent post/comment div and it's id from data attribute
Then send ajax call to the server with that id
it will return updated content after making the operation
and we will replace the element wrt the id

.like-post
.comment-post
.share-post
.like-count
.comment-count
.see-full-story

.like-comment
.edit-comment
.delete-comment

.post
.comment

posts can be fetched by particular id, posts associated with someone's profile id, someone's timeline
make post array containing one post => post.php?id=4

load_post($post, $own_id)
load_comment($comment, $post, \$own_id)

<div class="card-text my-2"><?= nl2br(mb_substr($post->content, 0, 1000)) . "<br><br><a href='post.php?id={$post->id}'>See Full Story</a>" ?></div>

.vertical-center => mx-auto

# Like post clicked

Fetch post id from card data value

let titleLikes = document
.getElementById(`post-${id}`)
.getElementsByClassName("like-count")[0].dataset.originalTitle;

# Open modal when .like-count clicked

when .like-count clicked
load data for the corresponding post in the modal
open modal

Add created_at and updated_at columns into comments and posts table

<?php
function addActiveClass($page_name) {
  if(basename($_SERVER["PHP_SELF"]) == $page_name) {
    echo "active";
  }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand font-weight-bold logo" href="index.php">Fakebook</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?= addActiveClass("profile.php") ?>">
        <a class="nav-link" href="profile.php">Profile</span></a>
      </li>
      <li class="nav-item dropdown <?= addActiveClass("received_friend_requests.php") ?>">
        <a class="nav-link dropdown-toggle" href="received_friend_requests.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Friends
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="received_friend_requests.php">Received Friend Requests</a>
          <a class="dropdown-item" href="sent_friend_requests.php">Sent Friend Requests</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="friend_list.php">Friend List</a>
        </div>
      </li>
      <li class="nav-item <?= addActiveClass("inbox.php") ?>">
        <a class="nav-link" href="inbox.php">Inbox</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./utilities/logout.php">Logout</a>
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
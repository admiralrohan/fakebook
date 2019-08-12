-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2019 at 03:09 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fakebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(14) UNSIGNED NOT NULL,
  `comment_content` mediumtext CHARACTER SET utf8 NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `comment_owner` int(6) UNSIGNED NOT NULL,
  `commented_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_content`, `post_id`, `comment_owner`, `commented_on`) VALUES
(3, 'DFB Pokal winner', 25, 2, '2019-06-25 23:08:49'),
(4, 'First comment posted again', 25, 2, '2019-06-25 23:10:47'),
(5, 'Hello', 25, 13, '2019-06-27 04:21:18'),
(6, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 25, 13, '2019-06-27 04:49:58'),
(8, 'Cool post', 43, 2, '2019-07-01 03:23:20'),
(9, 'Cool post', 7, 13, '2019-07-05 02:38:33'),
(10, 'Cool post', 4, 13, '2019-07-05 08:02:54'),
(11, 'Hi there', 47, 2, '2019-07-24 11:24:47'),
(12, 'Test comment', 5, 2, '2019-08-03 02:55:30'),
(13, 'First comment', 26, 2, '2019-08-03 03:38:25'),
(14, 'Ho', 86, 2, '2019-08-09 15:01:53'),
(15, 'Hi there', 67, 2, '2019-08-12 10:05:14'),
(23, 'Hi', 25, 2, '2019-08-12 14:08:43'),
(24, 'Hellooo', 25, 2, '2019-08-12 14:09:40'),
(25, 'Hellooo', 25, 2, '2019-08-12 14:11:07'),
(28, 'Hello', 25, 2, '2019-08-12 14:20:52'),
(29, 'Extra', 25, 2, '2019-08-12 14:22:17'),
(31, 'Hello', 46, 2, '2019-08-12 14:25:30'),
(32, 'Hi', 45, 2, '2019-08-12 14:25:41'),
(33, 'Hi', 24, 2, '2019-08-12 14:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `request_id` int(10) UNSIGNED NOT NULL,
  `request_from` int(6) UNSIGNED NOT NULL,
  `request_to` int(6) UNSIGNED NOT NULL,
  `request_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_status` enum('pending','accepted','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`request_id`, `request_from`, `request_to`, `request_on`, `request_status`) VALUES
(21, 2, 14, '2019-06-02 13:39:22', 'rejected'),
(22, 13, 2, '2019-06-02 11:04:33', 'rejected'),
(25, 13, 2, '2019-06-02 11:07:18', 'rejected'),
(26, 2, 13, '2019-06-02 11:21:27', 'rejected'),
(27, 2, 13, '2019-06-02 13:26:31', 'rejected'),
(28, 13, 2, '2019-06-02 13:38:23', 'rejected'),
(29, 2, 13, '2019-06-02 13:38:58', 'rejected'),
(30, 13, 2, '2019-06-02 13:40:04', 'rejected'),
(31, 13, 2, '2019-06-02 13:40:22', 'accepted'),
(32, 14, 2, '2019-06-02 13:41:09', 'accepted'),
(33, 13, 14, '2019-06-24 07:10:00', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(10) NOT NULL,
  `msg_content` mediumtext NOT NULL,
  `msg_from` int(6) UNSIGNED DEFAULT NULL,
  `msg_to` int(6) UNSIGNED DEFAULT NULL,
  `msgd_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `msg_content`, `msg_from`, `msg_to`, `msgd_on`) VALUES
(1, 'Hi', 2, 13, '2019-06-03 01:47:50'),
(2, 'Hello john', 2, 13, '2019-06-03 01:51:02'),
(3, 'oi', 2, 13, '2019-06-03 01:55:05'),
(4, 'oi', 2, 13, '2019-06-03 01:55:35'),
(5, 'de', 2, 13, '2019-06-03 01:59:33'),
(6, 'de', 2, 13, '2019-06-03 02:01:17'),
(7, 'Hello', 2, 13, '2019-06-16 00:02:33'),
(8, 'Hello', 2, 13, '2019-06-16 00:02:36'),
(9, 'Df', 2, 13, '2019-06-16 00:08:49'),
(10, 'Dfg', 2, 13, '2019-06-16 00:09:26'),
(11, 'd', 2, 13, '2019-06-16 00:09:40'),
(12, 'Hi', 14, 2, '2019-06-27 01:15:50'),
(13, 'Next message', 14, 2, '2019-06-27 01:38:24'),
(14, 'Hi', 2, 13, '2019-08-12 14:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `post_content` mediumtext COLLATE utf8mb4_unicode_ci,
  `original_post` int(10) UNSIGNED DEFAULT NULL,
  `post_owner` int(6) UNSIGNED DEFAULT NULL,
  `posted_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_shared_post` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_content`, `original_post`, `post_owner`, `posted_on`, `is_shared_post`) VALUES
(1, 'My first post.', NULL, 2, '2019-05-25 14:38:36', 0),
(2, 'Thank you Rohan', NULL, 2, '2019-05-25 14:38:59', 0),
(3, 'Hello this is John doe', NULL, 13, '2019-05-28 20:02:50', 0),
(4, 'Hello john doe', NULL, 13, '2019-05-28 20:02:57', 0),
(5, 'Hello from Jane Doe', NULL, 14, '2019-05-29 17:00:39', 0),
(6, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', NULL, 2, '2019-05-29 17:01:46', 0),
(7, 'Zombie ipsum reversus ab viral inferno, nam rick grimes malum cerebro. De carne lumbering animata corpora quaeritis. Summus brains sit​​, morbo vel maleficia? De apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium.', NULL, 2, '2019-05-29 17:18:54', 0),
(8, 'His values - and his record - affirm what is best in us. And in all these ways, they helped make this country more decent and more just. In my first book, Dreams From My Father, I described the experience of my first service at Trinity: In the white community, the path to a more perfect union means acknowledging that what ails the African-American community does not just exist in the minds of black people; that the legacy of discrimination - and current incidents of discrimination, while less overt than in the past - are real and must be addressed. In the face of that young student who sleeps just three hours before working the night shift, I think about my mom, who raised my sister and me on her own while she worked and earned her degree; who once turned to food stamps but was still able to send us to the best schools in the country with the help of student loans and scholarships. I realize that I am not the likeliest candidate for this office.\r\n\r\nBecause it&#39;s not who we are. And we cannot ignore the very real concerns of Americans who are not worried about illegal immigration because they are racist or xenophobic, but because they fear it will result in lower wages when they&#39;re already struggling to raise their families. This time we want to talk about how the lines in the Emergency Room are filled with whites and blacks and Hispanics who do not have health care; who don&#39;t have the power on their own to overcome the special interests in Washington, but who can take them on if we do it together. At the same time, Israelis must acknowledge that just as Israel&#39;s right to exist cannot be denied, neither can Palestine&#39;s. And just as it devastates Palestinian families, the continuing humanitarian crisis in Gaza does not serve Israel&#39;s security; neither does the continuing lack of opportunity in the West Bank. For instance, in the United States, rules on charitable giving have made it harder for Muslims to fulfill their religious obligation.\r\n\r\nHe grew up herding goats, went to school in a tin-roof shack. I&#39;m talking about something more substantial. It was because of these newfound understandings that I was finally able to walk down the aisle of Trinity one day and affirm my Christian faith. Much has been made of the fact that an African-American with the name Barack Hussein Obama could be elected President. 9/11 was an enormous trauma to our country. Threatening Israel with destruction - or repeating vile stereotypes about Jews - is deeply wrong, and only serves to evoke in the minds of Israelis this most painful of memories while preventing the peace that the people of this region deserve.\r\n\r\nIt wasn&#39;t until after college, when I went to Chicago to work as a community organizer for a group of Christian churches, that I confronted my own spiritual dilemma. We need to heed the biblical call to care for &#34;the least of these&#34; and lift the poor out of despair. This is a problem that&#39;s brought together churches and synagogues and mosques and people of all faiths as part of a grassroots movement. We will, however, relentlessly confront violent extremists who pose a grave threat to our security.\r\n\r\nThat we can participate in the political process without fear of retribution, and that our votes will be counted at least, most of the time. But we do need to remind ourselves that so many of the disparities that exist in the African-American community today can be directly traced to inequalities passed on from an earlier generation that suffered under the brutal legacy of slavery and Jim Crow. Now let there be no doubt. And just as it devastates Palestinian families, the continuing humanitarian crisis in Gaza does not serve Israel&#39;s security; neither does the continuing lack of opportunity in the West Bank. And it is no coincidence that countries where women are well-educated are far more likely to be prosperous.\r\n\r\nThank you.', NULL, 2, '2019-05-29 17:19:29', 0),
(9, 'Hello it&#39;s sunday hurray', NULL, 2, '2019-06-02 22:22:18', 0),
(16, 'Hello world', NULL, 2, '2019-06-02 22:40:32', 0),
(17, 'New post by john', NULL, 13, '2019-06-21 13:26:43', 0),
(18, 'Hi john', NULL, 13, '2019-06-21 13:26:54', 0),
(19, 'John', 4, 13, '2019-06-21 13:27:22', 1),
(20, 'Er john', NULL, 13, '2019-06-21 13:27:51', 0),
(22, 'Hello', NULL, 13, '2019-06-21 13:31:00', 0),
(24, 'Hj', NULL, 13, '2019-06-21 13:33:00', 0),
(25, 'Jane here', NULL, 14, '2019-06-21 13:34:00', 0),
(26, 'He', NULL, 13, '2019-06-21 13:39:48', 0),
(42, 'Dummy shared post content', 4, 2, '2019-07-01 03:17:30', 1),
(43, 'Dummy shared post content', 20, 2, '2019-07-01 03:18:09', 1),
(44, 'Dummy shared post content', 20, 2, '2019-07-01 03:23:25', 1),
(45, 'Dummy shared post content', NULL, 13, '2019-07-01 04:10:58', 1),
(46, 'Dummy shared post content', NULL, 13, '2019-07-01 04:32:00', 1),
(47, 'Say hi to me', 4, 13, '2019-07-01 05:49:26', 1),
(67, 'New post', NULL, 13, '2019-07-01 22:16:45', 0),
(81, '', 16, 2, '2019-08-09 12:25:15', 1),
(82, '', 16, 2, '2019-08-09 12:29:44', 1),
(83, 'Hello', 25, 2, '2019-08-09 14:59:54', 1),
(84, 'New share', 25, 2, '2019-08-09 15:00:33', 1),
(85, 'Another one', 25, 2, '2019-08-09 15:01:22', 1),
(86, 'Df', 25, 2, '2019-08-09 15:01:31', 1),
(87, '', NULL, 2, '2019-08-09 15:05:44', 1),
(88, '', NULL, 2, '2019-08-09 15:05:53', 1);

--
-- Triggers `posts`
--
DELIMITER $$
CREATE TRIGGER `posts_before_insert` BEFORE INSERT ON `posts` FOR EACH ROW IF (NEW.post_content IS NULL) && (NEW.is_shared_post = 0)
THEN
   SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot insert empty post';
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `post_liked_by_users`
--

CREATE TABLE `post_liked_by_users` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `post_liked_by` int(6) UNSIGNED NOT NULL,
  `post_liked_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_liked_by_users`
--

INSERT INTO `post_liked_by_users` (`post_id`, `post_liked_by`, `post_liked_on`) VALUES
(1, 2, '2019-08-06 03:08:05'),
(4, 2, '2019-06-26 04:07:28'),
(4, 13, '2019-07-06 04:23:56'),
(4, 14, '2019-06-20 05:09:50'),
(5, 2, '2019-08-03 02:59:15'),
(7, 13, '2019-07-05 02:32:23'),
(16, 2, '2019-08-06 04:17:27'),
(17, 2, '2019-08-06 04:02:21'),
(18, 2, '2019-08-12 14:28:54'),
(19, 2, '2019-08-06 03:07:55'),
(22, 2, '2019-08-06 02:58:42'),
(22, 13, '2019-06-24 01:50:30'),
(22, 14, '2019-06-24 01:44:23'),
(24, 2, '2019-08-06 03:58:46'),
(24, 13, '2019-07-06 05:31:39'),
(25, 2, '2019-08-09 14:59:40'),
(25, 13, '2019-07-02 03:01:39'),
(26, 2, '2019-08-06 03:11:13'),
(43, 2, '2019-08-03 04:10:20'),
(44, 2, '2019-08-06 04:02:38'),
(45, 2, '2019-08-06 02:40:00'),
(46, 2, '2019-08-06 03:12:45'),
(47, 2, '2019-08-06 04:27:00'),
(67, 2, '2019-08-12 10:05:36'),
(86, 2, '2019-08-09 15:01:46'),
(87, 2, '2019-08-12 15:01:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(6) UNSIGNED NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `psword` varchar(255) NOT NULL,
  `registered_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `psword`, `registered_on`) VALUES
(2, 'Rohan', 'Gayen', 'rohangayen@gmail.com', '$2y$10$/zoZNoaU7qUHK66Cn63oeO18ysPCDeCYkH/.5b/S2ImZjraJ7ih0u', '2019-05-19 03:44:10'),
(13, 'John', 'Doe', 'johndoe@gmail.com', '$2y$10$jW0t8dLGX94b37tRhSaY4.zj2.RcgocE3L3DxdaZqBWr8yrBcvWUa', '2019-05-19 04:53:59'),
(14, 'Jane', 'Doe', 'janedoe@gmail.com', '$2y$10$UdJe9gptqvqHZwTcjPzsmuSB.lXI8E4d091dz4pojobcMzdreHFN.', '2019-05-19 05:12:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `fk_users_comments` (`comment_owner`),
  ADD KEY `fk_posts_comments` (`post_id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_users_friend_request_1` (`request_from`),
  ADD KEY `fk_users_friend_request_2` (`request_to`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `fk_users_message_1` (`msg_from`),
  ADD KEY `fk_users_message_2` (`msg_to`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_users_posts` (`post_owner`),
  ADD KEY `fk_original_post` (`original_post`);

--
-- Indexes for table `post_liked_by_users`
--
ALTER TABLE `post_liked_by_users`
  ADD PRIMARY KEY (`post_id`,`post_liked_by`),
  ADD KEY `fk_users_likes` (`post_liked_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(14) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `request_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_posts_comments` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_comments` FOREIGN KEY (`comment_owner`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `fk_users_friend_request_1` FOREIGN KEY (`request_from`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_friend_request_2` FOREIGN KEY (`request_to`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_users_message_1` FOREIGN KEY (`msg_from`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_message_2` FOREIGN KEY (`msg_to`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_original_post` FOREIGN KEY (`original_post`) REFERENCES `posts` (`post_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_posts` FOREIGN KEY (`post_owner`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `post_liked_by_users`
--
ALTER TABLE `post_liked_by_users`
  ADD CONSTRAINT `fk_posts_likes` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_likes` FOREIGN KEY (`post_liked_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2024 at 12:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game_express`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'News'),
(2, 'Reviews'),
(5, 'Videos');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_username` varchar(255) NOT NULL,
  `comment_created_date` datetime NOT NULL,
  `comment_parent_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_content`, `comment_username`, `comment_created_date`, `comment_parent_id`, `author_id`, `post_id`) VALUES
(1, '123', '123', '2024-04-03 08:28:56', NULL, NULL, 19),
(2, 'Test.... test...', 'Sunny Baker', '2024-04-03 08:32:14', NULL, 4, 19),
(3, 'Test again....', 'Sunny Baker', '2024-04-03 08:42:11', NULL, 4, 19),
(4, '123123', 'Sunny Baker', '2024-04-03 08:43:01', NULL, 4, 19),
(5, '5', 'Sunny Baker', '2024-04-03 08:43:10', NULL, 4, 19),
(6, '65466', 'Sunny Baker', '2024-04-03 08:43:15', NULL, 4, 19),
(8, 'use &lt;b&gt;strong&lt;/b&gt;', 'Sunny Baker', '2024-04-05 04:31:34', NULL, 4, 19),
(10, '&lt;script&gt;alert(&quot;b&quot;)&lt;/script&gt;', 'Sunny Baker', '2024-04-05 04:32:32', NULL, 4, 19),
(11, 'sub reply', 'Sunny Baker', '2024-04-05 06:05:26', 10, 4, 19),
(14, 'Im gonna move far away from here, and I need the money!', 'K Boss', '2024-04-05 07:57:24', NULL, NULL, 19),
(15, 'IDK what you are talking', 'Confuse N', '2024-04-05 08:06:00', 14, NULL, 19),
(17, 'test test', 'Administrator System', '2024-04-05 18:07:07', 14, 1, 19),
(18, 'sfsfsf', 'Steven', '2024-04-05 18:07:38', NULL, NULL, 19),
(19, 'asdfsf', 'Administrator System', '2024-04-05 18:10:25', NULL, 1, 23),
(20, 'dsfdsf', 'sf', '2024-04-05 18:10:36', NULL, 1, 23),
(21, 'asf', 'Administrator System', '2024-04-05 18:19:08', NULL, 1, 23),
(23, 'awesome', 'Steven', '2024-04-05 18:29:10', NULL, NULL, 9),
(24, 'Yep!', 'Man', '2024-04-05 18:29:19', 23, NULL, 9),
(25, 'test test', 'Test', '2024-04-05 18:29:27', 24, NULL, 9),
(26, '&lt;script&gt;alert(&quot;Hey!&quot;)&lt;/script&gt;', 'Javascript', '2024-04-05 18:29:48', 25, NULL, 9),
(27, 'SSS', 'SSS', '2024-04-05 18:31:35', 26, NULL, 9),
(28, 'f', 'f', '2024-04-05 18:32:43', NULL, NULL, 9);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  `game_description` text DEFAULT NULL,
  `game_release_date` datetime DEFAULT NULL,
  `game_developer` varchar(255) DEFAULT NULL,
  `steam_url` varchar(255) DEFAULT NULL,
  `game_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_categories`
--

CREATE TABLE `game_categories` (
  `game_category_id` int(11) NOT NULL,
  `game_category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_image` varchar(255) DEFAULT NULL,
  `post_thumbnail` varchar(255) DEFAULT NULL,
  `post_content` text DEFAULT NULL,
  `post_created_date` datetime NOT NULL,
  `post_modified_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_image`, `post_thumbnail`, `post_content`, `post_created_date`, `post_modified_date`, `author_id`, `category_id`, `game_id`) VALUES
(9, 'The Elder Scrolls 6 release date coming way sooner than we&#039;d expected', 'uploads/202403310546151339.png', 'uploads/202403310546151339_thumbnail.png', '<p><strong>Source: <a href=\"https://www.gamingbible.com/news/the-elder-scrolls-6-release-date-coming-soon-711839-20240327\">https://www.gamingbible.com/news/the-elder-scrolls-6-release-date-coming-soon-711839-20240327</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.gamingbible.com/author/sam-cawley\">Sam Cawley</a></strong></p>\r\n<p>&nbsp;</p>\r\n<p>A recent development update for The Elder Scrolls VI has fans hoping it&rsquo;ll be released sooner than expected.</p>\r\n<p>Fans have been waiting for news on The Elder Scrolls VI for a while now, ever since the initial teaser trailer for the game dropped over five years ago.</p>\r\n<p>Now Bethesda has finally broken its silence on the game as it celebrates the 30th anniversary of the Elder Scrolls series, confirming development is running smoothly and playable builds of the RPG do currently exist.</p>\r\n<p>Speaking about the game in a recent tweet, Bethesda said: \"Even now, returning to Tamriel and playing early builds has us filled with the same joy, excitement, and promise of adventure.\"</p>\r\n<p>This is a huge update for the game after so many years of quiet, as it&rsquo;s believed the project was put on the backburner to focus all efforts on the newest IP Starfield, which still has a few content updates and DLCs planned for the foreseeable future.</p>\r\n<p>Following this development update, fans are hopeful the game&rsquo;s release could be fast tracked to sometime next year, or at the very least 2025.</p>\r\n<p>Neither Bethesda or Xbox has said anything about a release date, nor a vague release window, so it\'s really anyone&rsquo;s guess when the companies are planning to launch the game, as well as what consoles it&rsquo;ll be for.</p>\r\n<p>If it does somehow release in 2025, it&rsquo;ll be facing harsh competition from GTA VI, though to be fair a recent update from Rockstar Games suggests the next Grand Theft Auto might be getting pushed back to 2026.</p>\r\n<p>We&rsquo;re still none the wiser as to when The Elder Scrolls VI will finally materialise on our consoles and PCs, but this positive update from Bethesda definitely fills us with hope it&rsquo;ll be sooner rather than later.</p>', '2024-03-31 04:53:19', '2024-04-02 22:14:31', 4, 1, NULL),
(10, 'April 12 is Going to Be a Big Day for Diablo 3 Fans', 'uploads/202403310605265676.png', 'uploads/202403310605265676_thumbnail.png', '<p><strong>Source: <a href=\"https://gamerant.com/diablo-3-season-31-update-patch-notes-april-2024/\">https://gamerant.com/diablo-3-season-31-update-patch-notes-april-2024/</a></strong></p>\r\n<p><strong>Author: <a href=\"https://gamerant.com/author/dane-angelo-enerio/\">DANE ENERIO</a></strong></p>\r\n<p>April 12 is going to be a big day for players of Diablo 3, as the critically acclaimed hack and slash is set to receive a new update on that day. Blizzard Entertainment\'s third mainline entry in its beloved series of action RPGs was released over a decade ago, but the title still continues to receive new content and tweaks even after the launch of its successor, Diablo 4, last year.</p>\r\n<p>Diablo 3 was greeted with mostly positive reviews when it dropped in May 2012, with many critics claiming that the game improved upon the already competent and satisfying gameplay systems of the titles that came before it. Since its release, Blizzard Entertainment\'s third mainline Diablo entry has received a major expansion in the form of Reaper of Souls, and it has already been succeeded by Diablo 4, which was released in June 2023. Despite its age, Diablo 3 still regularly sees updates, with the next one set to arrive in April.</p>\r\n<p>Blizzard Entertainment announced that Diablo 3\'s Season 31: Season of the Forbidden Archives will drop on April 12, 2024. This season will mostly see the return of previously released items and cosmetics, but it will also introduce two new rewards - the Valor Portrait Frame and Angelic Treasure Goblin pet - for those who complete the entirety of the upcoming season\'s journey. In addition, Season 31 will make several minor tweaks as part of Patch 2.7.7A for Diablo 3, which is considered to be one of the best story-driven co-op games in recent memory.</p>\r\n<p>The previous Season 30: The Lords of Hell that was released in January brought much more substantial changes to the game, including rebalances for Diablo 3\'s seven classes. Season 30 also permanently added the Rites of Sanctuary and Visions of Enmity themes to the game.</p>\r\n<p><img src=\"uploads/202403310611243685.png\" alt=\"\" width=\"814\" height=\"510\"></p>\r\n<p><img src=\"uploads/202403310611371692.png\" alt=\"\" width=\"804\" height=\"486\"></p>\r\n<p>While Blizzard Entertainment has already released Diablo 4, its predecessor remains a popular game even now. Diablo 3 saw more than 3 million active players this March, according to data provided by analytics tracker ActivePlayer.io.</p>\r\n<p>Blizzard Entertainment previously revealed that Diablo 3 managed to attract 65 million players by the time it celebrated its 10th anniversary in 2022. Diablo 4 also became a hit, with the game reaching 12 million players just a few months after it was released. Blizzard Entertainment\'s latest release in the Diablo franchise corrected many of its predecessor\'s perceived faults, including Diablo 4&rsquo;s return to the series&rsquo; dark and dreary art style.</p>\r\n<h2>Diablo 3 April 12, 2024 Patch Notes</h2>\r\n<ul>\r\n<li>KR Age Restriction\r\n<ul>\r\n<li>Updated Logos for KR Age Restriction from 18 years of age to 19 years of age.</li>\r\n</ul>\r\n</li>\r\n<li>Extra progress orb drops from the Soulshard Stain of Sin now works for Challenge Rifts.</li>\r\n<li>Extra progress orb drops from the Soulshard Stain of Sin and Altar of Rites node Reaper now only drops from monsters inside Nephalem Rifts, Greater Nephalem Rifts, and Challenge Rifts.</li>\r\n</ul>', '2024-03-31 06:05:26', '2024-04-02 22:14:12', 5, 1, NULL),
(11, 'Bloodborne: Return to Yharnam is something you really don&#039;t wanna miss', 'uploads/202403310614291650.png', 'uploads/202403310614291650_thumbnail.png', '<p><strong>Source: <a href=\"https://www.gamingbible.com/news/platform/playstation/bloodborne-return-to-yharnam-unmissable-837162-20240327\">https://www.gamingbible.com/news/platform/playstation/bloodborne-return-to-yharnam-unmissable-837162-20240327</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.gamingbible.com/author/emma-flint\">Emma Flint</a></strong></p>\r\n<p>Being a Bloodborne fan isn&rsquo;t always easy. True, we have a fantastic game to always return to, but being a fan of this FromSoftware title hasn&rsquo;t been without its trials.</p>\r\n<p>Thankfully, alongside the news that Bloodborne is finally getting a remake (apparently), there comes the &lsquo;Return to Yharnam&rsquo;. This event urges fans new and old to partake in battle once more as the scope of the title&rsquo;s PvP is tested.</p>\r\n<p>The community event started on 24 March, and will run until 7 April. The two-week window is designed to reinvigorate the dwindling multiplayer fandom that&rsquo;s inevitably come from the game&rsquo;s natural online decline.</p>\r\n<p>As players traverse through Bloodborne&rsquo;s gothic, hellish landscape, they need to meet certain requirements to continue, one of which being to defeat hostile hunters controlled by other players. If you fail to meet these requirements in each location, you won&rsquo;t be part of this unmissable event for very long.</p>\r\n<p>I&rsquo;ll admit, as someone who doesn&rsquo;t often frequent multiplayer games, this wouldn&rsquo;t initially be one for me. But seeing as how an official announcement of that illustrious remake hasn&rsquo;t come from FromSoftware yet, this is one of the few ways I can return to the game and find new ways to cry with frustration and rage.</p>\r\n<p>If you intend to join in with the festivities, of which weeping over dying for the hundredth time will be mandatory, the clock is ticking; the event is already underway, so it&rsquo;s time to get creating that new character if you want to join.</p>\r\n<p>&lsquo;Return to Yharnam&rsquo; isn&rsquo;t the only Soulslike news we have to be happy about &ndash; there&rsquo;s also plenty of Elden Ring releases to snap up this year too. Not ignoring the fact that Shadow of the Erdtree is just around the corner, which fills us with dread and excitement in equal measure.</p>\r\n<p>The call to arms is ringing out once more, will you answer it and venture into Bloodborne once more?</p>', '2024-03-31 06:14:29', '2024-04-02 22:14:07', 4, 1, NULL),
(12, 'Palworld Fan Shares Clever Way to Make a Ton of Money Fast', 'uploads/202403310618096217.png', 'uploads/202403310618096217_thumbnail.png', '<p><strong>Source: <a href=\"https://gamerant.com/palworld-get-money-fast-high-quality-cloth/\">https://gamerant.com/palworld-get-money-fast-high-quality-cloth/</a></strong></p>\r\n<p><strong>Author: <a href=\"https://gamerant.com/author/shawn-wilken/\">SHAWN WILKEN</a></strong></p>\r\n<p>One Palworld player has recently discovered a clever way to make money in quick succession. Coins are essential for maintaining life in Palworld, from buying necessary crafting items that are otherwise hard to farm to finding a unique Pal with favorable traits and stats from the black market.</p>\r\n<p>Released in January 2024, developer Pocketpair\'s hit game saw monumental success in its early access release, selling over one million copies in its first eight hours. Initially dubbed \"Pokemon with guns\" for its many resemblances to certain Pokemon breeds, Palworld continued its success in sales, reaching 15 million on Steam and another 10 million players on Xbox.</p>\r\n<p>One of Palworld\'s fans ecently shared their unique method to farm coins quickly, utilizing the abilities of one specific Pal. The player shared a few images, highlighting which Pal they were using, the material they were farming, and a comical image of what life on the ranch looks like with multiple of these Pals hard at work. At first glance, the ranch set-up appears to show a few of each Pal roaming around, routinely dropping one item. From the player\'s notes, this item is not only quick to drop in conjunction with the Pal\'s work suitability, they sell for a high price in volume.</p>\r\n<p>In terms of selling items to generate a healthy flow of coins in Palworld, players routinely seek out the best method to fatten their wallets. Coins are necessary when building a base set for weapon construction and military aspirations. This player chooses to use several Sibelyx, which come with a level 1 Farming work suitability, and drop High Quality Cloth, a valuable item. Farming this particular item is easy when numerous Sibelyx roam around the ranch, and the Artisan passive skill boosts their work speed by 50%. With a dozen Sibelyx working hard at the ranch, players can stockpile High Quality Cloth quickly while exploring the world to take on various side missions.</p>\r\n<p><img src=\"uploads/202403310617159571.png\" alt=\"\" width=\"382\" height=\"439\"></p>\r\n<p>Players should remain cautious of the idea of hoarding high-priced items, as one Palworld player learned the hard way, losing out on millions in Nail sales due to the 0.1.5.0 patch. Luckily, High Quality Cloth sells for a modest 40 coins, which seems minimal in single sales but adds up once players sell them in bulk. For example, one stack of 9999 High Quality Cloth sells for 399,960 coins, marking up the final sale price.</p>\r\n<p>It is worth noting that High Quality Cloth is essential for building items, so Palworld players should stock this item in abundance if they wish to sell some for profit. Palworld continues to achieve great success, and with future patches, it\'s never known whether items like High Quality Cloth will see a reduction in sales prices. It\'s best to sell fast and store those coins safely rather than lose out on potential future gains.</p>', '2024-03-31 06:18:09', '2024-04-02 22:13:57', 5, 1, NULL),
(13, 'Reports Of GTA 6 Being Delayed To 2026 Are &quot;Overblown&quot;', 'uploads/202403310620237029.png', 'uploads/202403310620237029_thumbnail.png', '<p><strong>Source: <a href=\"https://www.thegamer.com/grand-theft-auto-gta-6-delay-reports-are-overblown-behind-schedule-normal-release-date-rumour/\">https://www.thegamer.com/grand-theft-auto-gta-6-delay-reports-are-overblown-behind-schedule-normal-release-date-rumour/</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.thegamer.com/author/george-f/\">GEORGE FOSTER</a></strong></p>\r\n<p>A reliable gaming insider has claimed that last week\'s reports of GTA 6 potentially being delayed into 2026 are \"overblown\" and that there\'s no indication of anything major having changed over at Rockstar Games.</p>\r\n<p>Considering how long we\'ve all been waiting for the next Grand Theft Auto (and how long it\'s been since Rockstar\'s last release, Red Dead Redemption 2), it doesn\'t come as a surprise to learn that the one thing everyone wants to know about the game is when we\'re going to get to play it.</p>\r\n<p>That made the reveal at the end of its reveal trailer that it\'s aiming for 2025 sting a bit for a lot of fans expecting to see it this year, but things got a little more painful last week when it was reported that GTA 6 was falling behind schedule and could slip out of its internal release date of Early 2025 and end up launching later in the year. Even worse, there was reportedly the possibility that it could be delayed into 2026.</p>\r\n<p>The report quickly did the rounds on the internet and got everyone worked up about the possibility of waiting another year for GTA 6, but it seems that there\'s not all that much to worry about just yet. Bloomberg reporter and reliable gaming insider Jason Schreier recently commented on the claims in a weekly roundup, and noted that the reaction to the report has been \"overblown\".</p>\r\n<p>After commenting on the impact the report had on Take-Two\'s stocks, Schreier noted that games are always behind schedule while in development and could get delayed at any point, something that\'s doubly true for GTA 6. He also points out that there\'s a lot of time between now and the end of 2025 that, even if production was going perfectly, it\'d be impossible to say definitively if it\'ll be ready by then.</p>\r\n<p>Schreier also notes that there\'s a possibility that Grand Theft Auto VI will slip out of 2025\", but pointed out that\'s the case for every game in development and that there\'s \"no indication that anything significant has changed\" for GTA 6 right now. For one final bout of confidence when it comes to GTA 6 being on track, Schreier asked several developers at Rockstar what they thought of the rumours and was \"met with shrugs\"</p>', '2024-03-31 06:20:24', '2024-04-02 22:13:45', 4, 1, NULL),
(14, 'Red Dead Redemption 2 free update lets you boost the game&#039;s graphics', 'uploads/202403310623259942.png', 'uploads/202403310623259942_thumbnail.png', '<p><strong>Source: <a href=\"https://www.gamingbible.com/news/red-dead-redemption-2-free-graphics-update-687935-20240327\">https://www.gamingbible.com/news/red-dead-redemption-2-free-graphics-update-687935-20240327</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.gamingbible.com/author/sam-cawley\">Sam Cawley</a></strong></p>\r\n<p>Red Dead Redemption 2 has been unexpectedly updated with new graphic enhancement options, specifically HDR10+.</p>\r\n<p>Despite all of its success and often being referred to as one of the greatest games ever made, it&rsquo;s criminal that Red Dead Redemption 2 hasn&rsquo;t received a next-gen update yet.</p>\r\n<p>It features such an impressive open world packed with detail, and a story mode you could play over and over again without getting bored, yet even if you own an Xbox Series X/S or PlayStation 5, you&rsquo;re still restricted to a last-gen version of the game.</p>\r\n<p>That being said there&rsquo;s still hope of a next-gen version of the game, after all a Red Dead Redemption 3 feels like a good decade away at this point and recent developments suggest GTA VI could be delayed to 2026.</p>\r\n<p>Additionally, Red Dead Redemption 2 received an update the other day seemingly out of the blue, and one of the standout parts of the patch notes was the addition of HDR10+ support.</p>\r\n<p>Provided you&rsquo;ve got a TV or monitor that supports it, you can now enjoy a wide range of graphics and colour enhancements, making the world of Red Dead Redemption 2 slightly more immersive than it already was.</p>\r\n<p>Granted, it&rsquo;s not the long-awaited next-gen update fans were hoping for, but it&rsquo;s certainly a start and possibly a precursor to a much larger technical update.</p>\r\n<p>If anything, it&rsquo;s at least another reason to replay the game, though I&rsquo;m sure there aren&rsquo;t many of you who wouldn&rsquo;t play through the game again for less.</p>\r\n<p>Whatever the future holds for Red Dead Redemption 2, there&rsquo;s nothing that can possibly dethrone it as one of the best games ever made, which means GTA VI&rsquo;s launch could very well be the most important day in Rockstar Games\' history.</p>', '2024-03-31 06:23:25', '2024-04-02 22:13:38', 4, 1, NULL),
(15, 'BioShock 4 teaser has us feeling hopeful for the sequel', 'uploads/202403310730486112.png', 'uploads/202403310730486112_thumbnail.png', '<p><strong>Source: <a href=\"https://www.gamingbible.com/news/platform/bioshock-4-teaser-has-us-feeling-hopeful-095285-20240328\">https://www.gamingbible.com/news/platform/bioshock-4-teaser-has-us-feeling-hopeful-095285-20240328</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.gamingbible.com/author/sam-cawley\">Sam Cawley</a></strong></p>\r\n<p>Ladies and gentlemen, BioShock 4 might not be dead after all following a teaser from 2K games.</p>\r\n<p>It&rsquo;s absolutely criminal that we haven&rsquo;t had a new BioShock game since 2013, which isn&rsquo;t a shot fired at the developers by any means, as it&rsquo;s believed development on BioShock 4 has been a nightmare over the last few years.</p>\r\n<p>While BioShock Infinite was well-received, with fans still praising its fantastic story to this day, many agreed it felt like more of the same and didn&rsquo;t quite innovate the gameplay like many would have expected.</p>\r\n<p>Perhaps that&rsquo;s why the next game, presumably BioShock 4, has been reportedly stuck in development hell for quite some time now.</p>\r\n<p>Fear not though BioShock fans as there is hope on the horizon following a very small teaser from developer 2K games on Twitter.</p>\r\n<p>The developer recently posted a tweet to celebrate the 11th anniversary of BioShock Infinite (my oh my how time flies) which obviously prompted questions from fans regarding another game in the series.</p>\r\n<p>When asked if a new BioShock game is still in the works, 2K responded with an emoji of chef which can only mean it&rsquo;s cooking something good.</p>\r\n<p><img src=\"uploads/202403310625542323.png\" alt=\"\" width=\"773\" height=\"431\"></p>\r\n<p>This seemingly disproves the theory that the game is facing cancellation after being stuck in development hell, as it&rsquo;s said the story has been re-written several times by this point.</p>\r\n<p>Rumours also state it&rsquo;ll be the first open world title in the series, as well as the first to take place in a location not under the sea or up in the air. It&rsquo;s said the plot will be set in a colony somewhere in Antarctica.</p>\r\n<p>Whatever 2K games is cooking up, I&rsquo;m sure many will just be glad that another game is on the way, fingers-crossed it&rsquo;s another banger.</p>', '2024-03-31 06:25:56', '2024-04-02 22:13:32', 4, 1, NULL),
(16, 'Fallout meets Starfield in your next free PC RPG', 'uploads/202403310628145702.png', 'uploads/202403310628145702_thumbnail.png', '<p><strong>Source: <a href=\"https://www.gamingbible.com/news/platform/fallout-meets-starfield-in-free-pc-rpg-104379-20240328\">https://www.gamingbible.com/news/platform/fallout-meets-starfield-in-free-pc-rpg-104379-20240328</a></strong></p>\r\n<p><strong>Author: <a href=\"https://www.gamingbible.com/author/sam-cawley\">Sam Cawley</a></strong></p>\r\n<p>Don&rsquo;t walk, run to the Epic Games store right now as its next free game is an RPG that&rsquo;s essentially Fallout with a Starfield twist.</p>\r\n<p>Epic Games store is a fantastic platform to snag some freebies, arguably better than Steam since it&rsquo;s more likely to give away AAA games free-of-charge.</p>\r\n<p>It&rsquo;s said that last year the Epic Games store gave away $10,000 worth of free software, and it&rsquo;s latest offering is one you won&rsquo;t want to miss.</p>\r\n<p>It&rsquo;s none other than The Outer Worlds, specifically the Spacer&rsquo;s Choice Edition of the game which includes all of the game&rsquo;s DLC.</p>\r\n<p>Made by Obsidian Entertainment, the developer that brought you Fallout: New Vegas, The Outer Worlds is basically the company&rsquo;s version of a Fallout game but in space, which is also how people generally describe Starfield.</p>\r\n<p>While it differs from Bethesda&rsquo;s RPG is a number of ways the formula remains pretty much the same, as it&rsquo;s a shooter with loads of side content, plenty of fun weapons to play with and an assortment of perks to make your character stronger.</p>\r\n<p>It&rsquo;ll soon be joined by Avowed, another Obsidian Entertainment game that&rsquo;s being compared to The Elder Scrolls V: Skyrim.</p>\r\n<p>The Outer Worlds: Spacer&rsquo;s Choice Edition will be given away for free starting on 4 April, and it&rsquo;ll remain free until 11th April which gives you about a week to add it to your library.</p>\r\n<p>If you&rsquo;re a fan of RPGs and/or sci-fi games it&rsquo;s well-worth a go and while it falls short of Bethesda\'s Fallout games in a few ways it has enough redeeming qualities to help it stand on its own.</p>\r\n<p>Plus it&rsquo;s another Epic Games freebie, so what&rsquo;s the point in looking a gift horse in the mouth?</p>', '2024-03-31 06:28:14', '2024-04-02 22:13:26', 5, 1, NULL),
(17, 'One Piece: Pirate Warriors 4 Legend Dawn DLC Adds Roger, Rayleigh, &amp; Garp!', 'uploads/202403310632266003.jpg', 'uploads/202403310632266003_thumbnail.jpg', '<p><span style=\"font-size: 16px;\"><strong>Source: <a href=\"https://steamdeckhq.com/news/one-piece-pirate-warriors-4-legend-dawn-dlc-out/\">https://steamdeckhq.com/news/one-piece-pirate-warriors-4-legend-dawn-dlc-out/</a></strong></span></p>\r\n<p><span style=\"font-size: 16px;\"><strong>Author: <a href=\"https://steamdeckhq.com/author/oliverstogden/\">Oliver Stogden</a></strong></span></p>\r\n<p><span style=\"font-size: 16px;\">If you haven\'t yet, follow us on X (Twitter) to know when we post new articles, and join our Reddit or Subscribe to us on YouTube to check out our content and interact with our awesome community. Thank you for supporting us!</span></p>\r\n<p><span style=\"font-size: 16px;\">The Legend Dawn DLC pack for One Piece: Pirate Warriors 4 which adds Roger, Rayleigh, and Garp as playable characters is available now!</span></p>\r\n<p><span style=\"font-size: 16px;\">The Legend Dawn DLC pack adds Gol D. Roger, Silvers Rayleigh, and Monkey D. Garp to the character roster. Each of them comes with new, unique move sets to take out the enemy. You can purchase the character pack separately or if you own Character Pass 2, it\'s already included.</span></p>\r\n<p><span style=\"font-size: 16px;\">Here\'s the trailer for the Legend Dawn DLC Pack:</span></p>\r\n<p><iframe src=\"https://www.youtube.com/embed/KuH4UWLr6Q4\" width=\"560\" height=\"314\" allowfullscreen=\"allowfullscreen\"></iframe></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 16px;\">In addition to the Character Pack DLC, One Piece: Pirate Warriors 4 is also getting the Additional Episode 3 DLC, titled &ldquo;Path to the King of the Pirates&rdquo; &amp; &ldquo;Soul Map 3&rdquo;, provides new single-player content. You can get this content as part of the Additional Episode Pack, or you can purchase it separately. As a bonus for Character Pass 2 owners, the Onigashima Battle Law costume will also be available.&nbsp;</span></p>\r\n<p><span style=\"font-size: 16px;\">One Piece: Pirate Warriors 4 is available now on Steam for $39.99. It holds a Steam Deck Verified rating according to Valve\'s testing, so should run just fine on the Steam Deck.</span></p>\r\n<p><span style=\"font-size: 16px;\">If you enjoyed this article, check out the rest of the content on SteamDeckHQ! We have a wide variety of game reviews and news that will help your gaming experience. Whether you\'re looking for news, tips and tutorials, game settings and reviews, or just want to stay up-to-date on the latest trends, we\'ve got your back!</span></p>', '2024-03-31 06:31:22', '2024-04-02 22:13:12', 5, 1, NULL),
(18, 'The Sims 4 Is Giving Away a Free DLC Pack', 'uploads/202403310637287637.png', 'uploads/202403310637287637_thumbnail.png', '<p><span style=\"font-size: 16px;\"><strong>Source: <a href=\"https://gamerant.com/sims-4-free-backyard-stuff-dlc-pack-sale-promo/\">https://gamerant.com/sims-4-free-backyard-stuff-dlc-pack-sale-promo/</a></strong></span></p>\r\n<p><span style=\"font-size: 16px;\"><strong>Author: <a href=\"https://gamerant.com/author/dominik-bosnjak/\">DOMINIK BO&Scaron;NJAK</a></strong></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 16px;\">EA is currently giving away the Backyard Stuff DLC for The Sims 4. The limited-time promo adds to the growing list of The Sims 4 content packs that have been treated to 100% discounts in recent times.</span></p>\r\n<p><span style=\"font-size: 16px;\">Since the game\'s 2014 debut, EA and Maxis have released 15 The Sims 4 expansions and over 60 smaller DLC packs. Most of those add-ons regularly go on sale, although it\'s much rarer to see any being given away for free. But that\'s precisely the kind of offer that has now been extended to the Backyard Stuff pack.</span></p>\r\n<p><span style=\"font-size: 16px;\">The newly debuted 100% discount on this DLC is available across all platforms. PC players have a particularly wide array of options to choose from, as Backyard Stuff can be claimed via Steam, Epic Games Store, the EA App, or a combination thereof. Meanwhile, Xbox and PlayStation gamers can grab the DLC via their console\'s storefronts. Although EA did not specify how long the promotion would last, the pack\'s Microsoft Store page indicates that its 100% discount expires on April 13. A month-long window of opportunity to claim this add-on for free matches the length of other recent promos that saw The Sims 4 give away some of its DLC.</span></p>\r\n<h2><span style=\"font-size: 16px;\">Where To Claim The Sims 4 Backyard Stuff DLC for Free</span></h2>\r\n<ul>\r\n<li style=\"font-size: 16px;\"><span style=\"font-size: 16px;\"><a href=\"https://www.ea.com/games/the-sims/the-sims-4/store/addons/the-sims-4-backyard-stuff\">EA App</a></span></li>\r\n<li style=\"font-size: 16px;\"><span style=\"font-size: 16px;\"><a href=\"https://store.epicgames.com/en-US/p/the-sims-4--backyard-stuff\">Epic Games Store</a></span></li>\r\n<li style=\"font-size: 16px;\"><span style=\"font-size: 16px;\"><a href=\"https://store.playstation.com/en-us/product/UP0006-CUSA09209_00-TS4BACKYARDSTUFF\">PlayStation Store</a></span></li>\r\n<li style=\"font-size: 16px;\"><span style=\"font-size: 16px;\"><a href=\"https://redirect.viglink.com/?key=d325da4c00e59f5c25e7b4b7cc6a9b26&amp;cuid=UUgrUeUpU2158574&amp;u=https%3A%2F%2Fstore.steampowered.com%2Fapp%2F1235760%2FThe_Sims_4_Backyard_Stuff%2F\">Steam</a></span></li>\r\n<li style=\"font-size: 16px;\"><span style=\"font-size: 16px;\"><a href=\"https://redirect.viglink.com/?key=d325da4c00e59f5c25e7b4b7cc6a9b26&amp;cuid=UUgrUeUpU2158574&amp;u=https%3A%2F%2Fwww.xbox.com%2Fen-US%2Fgames%2Fstore%2Fthe-sims-4-backyard-stuff%2Fc2wg3rnsgfv7\">Xbox Store</a></span></li>\r\n</ul>\r\n<h2><br><span style=\"font-size: 16px;\">What\'s Included in The Sims 4 Backyard Stuff Pack</span></h2>\r\n<p><span style=\"font-size: 16px;\"><img src=\"uploads/202403310636445575.png\" alt=\"\" width=\"504\" height=\"446\"></span></p>\r\n<p><span style=\"font-size: 16px;\">While Backyard Stuff is regularly priced at $9.99, thus being among the more affordable add-ons for the game, it\'s also one of the better The Sims 4 content packs in terms of its bang-to-buck ratio. Specifically, this DLC comes with 40 Build Mode furniture items and 25 cosmetics usable in the Create a Sim menu. Water slides, drink trays, a sun rug, and multiple floral arrangements are part of the package, as are a variety of outdoor tables, chairs, and quirky garden decorations.</span></p>\r\n<p><span style=\"font-size: 16px;\">The included clothing and hairstyles mostly lean on the casual side, keeping in line with the laid-back garden party theme of the DLC. Since Backyard Stuff was released in July 2016, it has never dropped below $4.99 on PC and $6.99 on consoles up until right now.</span></p>\r\n<p><span style=\"font-size: 16px;\">Given EA\'s recent track record, it is plausible that the company might opt to give away yet another one of the game\'s add-ons come spring, as it has lately been running such promos on a quarterly basis. In the meantime, Maxis isn\'t done churning out content for its hit game, with the latest The Sims 4 DLC just hitting the digital store shelves in late February, having arrived in the form of the Crystal Creations Stuff Pack.</span></p>', '2024-03-31 06:37:28', '2024-04-02 21:53:57', 4, 1, NULL),
(19, 'Roguelite City-Builder &#039;Against the Storm&#039; Breezes Past 1 Million Sold on Steam!', 'uploads/202403310640458299.jpg', 'uploads/202403310640458299_thumbnail.jpg', '<p><span style=\"font-size: 16px;\"><strong>Source: <a href=\"https://steamdeckhq.com/news/against-the-storm-passes-1m-sales/\">https://steamdeckhq.com/news/against-the-storm-passes-1m-sales/</a></strong></span></p>\r\n<p><span style=\"font-size: 16px;\"><strong>Author: <a href=\"https://steamdeckhq.com/author/oliverstogden/\">Oliver Stogden</a></strong></span></p>\r\n<p><span style=\"font-size: 16px;\">After releasing into early access in November 2022, and then getting its full release recently in December 2023, Against the Storm has passed one million units sold on Steam! Against the Storm holds a Metacritic score of 91 as well as a 95% User Rating on Steam, so it\'s well-deserving of its commercial success.</span></p>\r\n<p><span style=\"font-size: 16px;\">It\'s far from over for Against the Storm, however, as the team is working through the roadmap that they\'ve set out for the game, just recently releasing the 1.2 patch which added new content and QoL improvements.</span></p>\r\n<p><span style=\"font-size: 16px;\"><img src=\"uploads/202403310640026064.jpg\" alt=\"\" width=\"730\" height=\"730\"></span></p>\r\n<p><span style=\"font-size: 16px;\">The team is now working on the free update known as Patch 1.3, as well as a DLC expansion, both of which are expected to launch later this year.</span></p>\r\n<p><span style=\"font-size: 16px;\">Eremite (developers of Against the Storm) Co-Founder, Lukasz Korzanowski says that he believes Against the Storm\'s success has been down to the development team listening to and respecting player feedback as they developed the game, and that it\'s reassuring to see how small teams like their own can find success with their games.</span></p>\r\n<p><span style=\"font-size: 16px;\">You can purchase Against the Storm on Steam for $29.99. There\'s also a demo available if you want to try out the game for free first. The game is rated as Verified on the Steam Deck by Valve\'s testing. It holds a Platinum rating on ProtonDB, however, users report that some text may be difficult to read on the Steam Deck.</span></p>\r\n<p><span style=\"font-size: 16px;\">Doesn\'t it warm your heart to see smaller indie studios getting success like this? Let us know your thoughts about the game in the comments below!</span></p>\r\n<p><span style=\"font-size: 16px;\">If you enjoyed this article, check out the rest of the content on SteamDeckHQ! We have a wide variety of game reviews and news that will help your gaming experience. Whether you\'re looking for news, tips and tutorials, game settings and reviews, or just want to stay up-to-date on the latest trends, we\'ve got you covered!</span></p>', '2024-03-31 06:40:45', '2024-04-02 22:12:58', 5, 1, NULL),
(20, 'Sonic Superstars Review - Reaching for Stars', 'uploads/202404020844306302.png', 'uploads/202404020844306302_thumbnail.png', '<p><strong><span style=\"font-size: 16px;\">Source: <a href=\"https://www.gamespot.com/reviews/sonic-superstars-review-reaching-for-stars/1900-6418139/\">https://www.gamespot.com/reviews/sonic-superstars-review-reaching-for-stars/1900-6418139/</a></span></strong></p>\r\n<p><strong><span style=\"font-size: 16px;\">Author: <a href=\"https://www.gamespot.com/profile/zero-chan/\">Heidi Kemps</a></span></strong></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 16px;\">Sonic as a franchise is notoriously inconsistent, offering up some amazing highs, some truly dire lows, and a fair few games that are forgettably mediocre. But then there\'s that one weird category several Sonics fall into: games that are obviously, undeniably flawed in some way, but still have that something that makes a Sonic game feel special. Maybe not everyone can feel what makes them great, but for others, those qualities will overshine all of the negatives. Sonic Superstars is one such game, one with glaring flaws that I happen to like a lot.</span></p>\r\n<p><span style=\"font-size: 16px;\">We all know how it goes by now: Sonic\'s arch-nemesis Dr. Eggman is up to no good, and he\'s got his eye on a new locale whose resources he can exploit for evil schemes. It\'s up to Sonic and his BFFs, Tails, Knuckles, and Amy, to stop the Doctor, recover the Chaos Emeralds, battle the returning but obscure fan-favorite villain Fang the Sniper, and meet a brand-new buddy to save the day. Accomplishing this task involves zipping through 11 themed side-scrolling zones while collecting rings, bonking Badniks, and dealing with each area\'s distinct gimmicks and threats. No melodramatic story scenes, RPG or sim elements, or awkward romances here: It\'s pure, classic Sonic platforming.</span></p>\r\n<p><span style=\"font-size: 16px;\">That doesn\'t mean it\'s entirely devoid of new ideas, however. All of the zones are brand-new: Familiar elements from previous games like gimmicks and enemies might return, but every stage is an original. Collecting a Chaos Emerald now grants a special Emerald Power players can put to use at almost any time, provided they have the energy for it. And--perhaps the biggest new twist of all is that Sonic Superstars now supports four-person couch co-op multiplayer. Sure, somebody could pick up the player 2 pad and flail around as Tails in Sonic 2 and 3, but that was extremely limited; what we have here in Superstars is a unique kind of controlled chaos (pun intended).</span></p>\r\n<p><span style=\"font-size: 16px;\">The new Zones in Sonic Superstars cover some familiar Sonic archetypes--a lush island with lots of loops, a bouncy carnival-type area, a factory-type location--along with new stage concepts, some of which appears to be based on unused concepts dating all the way back to the original Sonic the Hedgehog. At their best, these zones are filled with memorable, creative elements that make you eager to dive back in and replay them to explore further--the voxel-graphic, electricity-zipping, transformation-heavy Cyber Station feels destined to be an all-time favorite among longtime Sonic fans. At their worst, the level gimmicks make the zones a miserable slog--Press Factory Act 2 takes the underwater-timer element and applies it to the whole stage, leaving you having to constantly press switches throughout to reset a countdown to instant death.</span></p>\r\n<p><span style=\"font-size: 16px;\">To navigate the levels, you\'ll choose one of the four heroes to play as. This choice matters quite a bit as abilities vary wildly between the cast: Sonic has his Drop Dash from Sonic Mania, Tails has timed flight, Knuckles can glide and scale walls, and relative newcomer Amy brings in a double-jump with a much bigger hit radius and a flailing hammer run. While most levels can be played using any character, a small handful are specially designed for a certain character. They\'re all fun, but with the extra navigation and attack versatility the other characters offer, it\'s weird that Sonic feels outclassed in his own game.</span></p>\r\n<p><span style=\"font-size: 16px;\">But even though our blue buddy lacks the verticality of his companions, Emerald Powers are available to help bridge the gap a little. Each Emerald collected grants a permanent new power, ranging from the extremely situational (Aqua lets you navigate water easily, which requires being near/in water; Vision reveals hidden platforms and goodies if they\'re in the area) to the incredibly useful (Ivy creates a vine made of vertical platforms; Bullet sends you flying long distances; Avatar summons a screen-filling wave of clones to collect items and damage enemies). Combining character abilities with the more utilitarian Emerald powers gives a wide range of movement options, helping to alleviate that common Sonic frustration of seeing a collectible goodie or alternate route but not being able to vault over to it.</span></p>\r\n<p><span style=\"font-size: 16px;\">Getting those Emeralds, however, is a rather awkward experience. You enter a special stage through giant rings you find in the levels, which teleport you to a 3D environment where you need to catch the floating, moving Chaos Emerald by flinging yourself with a tether beam from various floating grapple-points. With no ground and few things in the environment besides a mess of orbs, rings, and oddly-shaped speed points, there\'s little good distance reference, making it difficult to ascertain how close you are to getting the Emerald--or, sometimes, what you can and can\'t tether to in your current position. A good chunk of the time when I got the Emerald, I just felt like I got lucky.</span></p>\r\n<p><span style=\"font-size: 16px;\">But the occasional annoyance of the Special Stages is nothing compared to some of Sonic Superstars\' boss battles. It\'s not so much that the bosses are poorly designed--they have some very fun and challenging attack patterns and weaknesses. The problem is that, for many of them, the opportunity to deal damage only comes after you spend a very long time dodging and moving, and once you do damage them (or, heaven forbid, miss the window) there\'s a long, long wait before the next opportunity to hit them again. Sometimes there are ways to get around this--Amy\'s huge damage radius and the Avatar skill can cheat out hits--but even those can\'t get rid of a lengthy period of dodging fire blasts on a floating platform before the boss reappears.</span></p>\r\n<p><span style=\"font-size: 16px;\">And then there\'s a general lack of rewards. Like Sonic Origins\' Anniversary mode, Sonic Superstars has done away with finite lives. Instead of collecting rings and looking for 1UP monitors, you\'ll be collecting medals tucked away in odd places and in bonus stages. Those medals can be exchanged for parts to customize your avatar in online multiplayer... and not much else, meaning that there\'s not much incentive to get them if you aren\'t going to play online. This can dampen the desire to explore levels as a result. Several of the stages are worth replaying because they\'re fun to frolic around in, but a fair few are not, and the charm of the spinning, slow-moving Sonic 1-inspired bonus stages starts to wear thin pretty early on.</span></p>\r\n<p><span style=\"font-size: 16px;\">As for the much-hyped co-op multiplayer, how much fun you\'ll have is likely dependent on how and with who you play. Instead of split-screen gameplay, all players share the same screen space, and those who lag behind eventually get zipped forward to rejoin the leaders, similar to how Tails would rejoin you in Sonic 2 and 3. What usually happens is someone in the group falls into a \"leader\" role that everyone else acts as backup for. If you\'re, for example, parents who grew up playing Sonic and want to share a classic Sonic experience with your young, less-skilled kids, this format works great. If you\'ve got a roomful of Sonic veterans--the type who argue about the correct pronunciation of Hydrocity--and they all have different ideas about where to go and what to prioritize, it can be a mess.</span></p>\r\n<p><span style=\"font-size: 16px;\">Online multiplayer is also underwhelming, consisting of a small group of players fighting it out in very simplistic minigames, such as collecting items, dodging enemy attacks, and blasting each other with energy shots. They\'re overly basic at best and confusingly chaotic at worst, feeling and looking like gameplay you\'d see in a suspicious mobile game ad on social media.</span></p>\r\n<p><span style=\"font-size: 16px;\">It sounds like I\'ve rattled off a lot of problems, but there\'s plenty more I can praise, too. Beating the main story mode opens up a new mode where you play as Trip, the new spiky reptilian friend, in specially modified versions of the stages that let you use her wall-rolling abilities to their fullest. Finish that, and you get to play the big, ultimate final boss encounter with Super Sonic that\'s simultaneously way too long and really cool. The visual design is excellent, with many colorful environments and little graphical touches, like enemies donning ghost costumes for a haunted house area. Sonic Mania composer Tee Lopes returns to give the soundtrack a delightful kick, offering up some excellent tracks alongside the others like Sonic soundtrack veteran Jun Senoue. The second-to-last zone features a riff on an all-time Sega arcade classic that will go over most folks\' heads but fill old fans with absolute delight.</span></p>\r\n<p><span style=\"font-size: 16px;\">Sonic Superstars is far from the best Sonic, and its flaws are both copious and obvious. Despite this, there\'s still that base Sonic high-speed platforming joy at its core, and those ultra-cool moments when it really sticks the landing with a funky new idea, unique surprise, or charming throwback to outshine the ways in which it trips over itself. After all, when you reach for the stars, sometimes you\'ll overextend--but it makes those moments when you do seize glittering glory all the sweeter.</span></p>', '2024-04-02 08:44:30', '2024-04-02 22:12:40', 4, 2, NULL),
(23, 'Test file name', 'uploads/202404051729121757.jpg', 'uploads/202404051729121757_thumbnail.jpg', '<p>sdf</p>', '2024-04-05 17:28:52', '2024-04-05 17:29:13', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_description`) VALUES
(1, 'Administrator', 'Administrators have full control over the CMS and are responsible for managing the entire website.'),
(2, 'Author', 'Authors are individuals who create and edit articles, but they cannot edit the articles posted by others.'),
(3, 'User', 'Users are registered accounts on the website and may engage in various activities such as commenting and searching.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `email`, `role_id`) VALUES
(1, 'admin', '$2y$10$vfzhA5tC4yEmTsV1OxQJAerfhHkmcylNENTJVOlDFdhv4xbmD8nf.', 'Administrator', 'System', 'admin@game-express.com', 1),
(4, 'sunny', '$2y$10$Sn2mCEN8iWg7/wy7OVeTUe3tVIPLHicr6djN1Ljytv4aJe0V/OOoi', 'Sunny', 'Baker', 'sunnybaker@gmail.com', 2),
(5, 'blue', '$2y$10$rP3dUrgb/9l8iw8VS77pPuIVx74tyAm3Ov1Osa6wFjfvs8KX3/IaC', 'Blue', 'Panda', 'bluepanda@gmail.com', 2),
(8, 'ninja', '$2y$10$WFuFpgIisCeuZax2TAUD.eI3flvJjirlZhDpraZlD1.1FPhV31ZAW', 'Ninja', 'Raven', 'ninjaraven@gmail.com', 3),
(9, 'happy', '$2y$10$HHGDzqmTtVKAMbRglwhTdOQ47T.2GKEhEc8mjylgxViVCWh5ONB7u', 'Happy', 'Jumper', 'happyjumper@gmail.com', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `Comments_FK_author_id` (`author_id`),
  ADD KEY `Comments_FK_parent_id` (`comment_parent_id`),
  ADD KEY `Comments_FK_post_id` (`post_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `Games_FK_game_category_id` (`game_category_id`);

--
-- Indexes for table `game_categories`
--
ALTER TABLE `game_categories`
  ADD PRIMARY KEY (`game_category_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `Posts_FK_category_id` (`category_id`),
  ADD KEY `Posts_FK_game_id` (`game_id`),
  ADD KEY `Posts_FK_author_id` (`author_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `Roles_FK_role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_categories`
--
ALTER TABLE `game_categories`
  MODIFY `game_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Comments_FK_author_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `Comments_FK_parent_id` FOREIGN KEY (`comment_parent_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Comments_FK_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `Games_FK_game_category_id` FOREIGN KEY (`game_category_id`) REFERENCES `game_categories` (`game_category_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `Posts_FK_author_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Posts_FK_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `Posts_FK_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `Roles_FK_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

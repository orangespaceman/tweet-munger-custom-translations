<?php 
	require_once('TweetMunger.php');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Tweet Munger</title>	 
	</head>
	<body>
		<h1>Test</h1>
		<?php
		
			$tweetMunger = new TweetMunger(array(
				'debugMode' => true,
				'originalTwitterAccount' => 'xxx',
				'mungedTwitterAccount' => 'yyy',
				'userAgentAccount' => 'xxx@yyy.com',
                'newTweetCount' => 10,
                'ignoreRetweets' => true,
                'translations' => 'pirate',
				'twitterConsumerKey' => 'x',
				'twitterConsumerSecret' => 'x',
				'twitterConsumerOauthToken' => 'x',
				'twitterConsumerOauthSecret' => 'x'
			));
		?>
	</body>
</html>
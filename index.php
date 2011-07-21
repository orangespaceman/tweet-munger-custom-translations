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
				'originalTwitterAccount' => 'stephenfry',
				'mungedTwitterAccount' => 'stephenfrirate',
				'userAgentAccount' => 'thatsfixedit@hotmail.com',
                'newTweetCount' => 10,
                'ignoreRetweets' => true,
                'translations' => 'pirate',
				'twitterConsumerKey' => 'mhPdDlAavrMtomrrEQ5Q',
				'twitterConsumerSecret' => 'HWVpqDu3eeIl524w8bY929bt0aW7XiQmqIvI42lQxk',
				'twitterConsumerOauthToken' => '339560278-yqCzPb7ywO4kIZ87IlOM10CZzDLe9lV6qnhOPn42',
				'twitterConsumerOauthSecret' => 'DefshQsfi4NrQ3j3fhHB9UN2bUGyv7xOMSPA7IIgSg'
			));
		?>
	</body>
</html>
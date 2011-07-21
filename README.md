# TweetMunger

Translate _(mung)_ tweets from a specific user account through a custom dictionary, then re-tweet from a new account.


## Set up

Setting up a new TweetMunger account will require a few steps:

  * Create a new [Twitter](http://twitter.com/) account 
  * [Register a new app](https://dev.twitter.com/) with the twitter account - take a note of the four different keys listed below, and make sure the app has read and write access.  (Give it read and write access before creating your access tokens so they share this access, to check see [here](https://twitter.com/settings/applications))
  * Pick a custom translation (or create your own)


## Init

Set up a script on a (php-enabled) web server that calls the TweetMunger class.  The following is one example of how you could do it:

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
            <h1>Tweet Munger</h1>
            <?php

                $tweetMunger = new TweetMunger(array(
                    'debugMode' => false,
                    'originalTwitterAccount' => 'xxx',
                    'mungedTwitterAccount' => 'yyy',
                    'userAgentAccount' => 'xxx@yyy.com',
                    'newTweetCount' => 10,
                    'ignoreRetweets' => true,
                    'translations' => 'pirate',
                    'twitterConsumerKey' => 'www',
                    'twitterConsumerSecret' => 'xxx',
                    'twitterConsumerOauthToken' => 'yyy',
                    'twitterConsumerOauthSecret' => 'zzz'
                ));
            ?>
        </body>
    </html>

### Init options explained

  * *debugMode*: Set to true to output content only in the browser, false to post to twitter
  * *originalTwitterAccount*: The Twitter account we're copying from 
  * *mungedTwitterAccount*: The Twitter account we're posting to
  * *userAgentAccount*: The email account used by TwitterSearch class to tell Twitter who's calling
  * *newTweetCount*: How many new tweets to translate each time
  * *ignoreRetweets*: Retweets can sometimes confuse Twitter Munger due to inconsistent IDs so ignore by default
  * *translations*: The custom translation library name to translate to
[here](http://code.google.com/apis/language/translate/v2/getting_started.html))
  * *twitterConsumerKey*, *twitterConsumerSecret*, *twitterConsumerOauthToken* and *twitterConsumerOauthSecret*: Twitter Authorisation tokens -  [Register a new app](https://dev.twitter.com/) for these
  
  
## Automate

Set up a cron job on your server to call the above script every xx minutes/hours.  The following is one example:

    # call script four times an hour
    0,15,30,45 * * * * curl http://xxx.yy.zz/tweetmunger/ >/dev/null 2>&1
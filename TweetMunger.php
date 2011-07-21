<?php 
/*
 * Class to mung tweets - 
 * detect new tweets from a specified account
 * translate with a custom dictionary
 * post to new twitter account
 *
 * @author Pete G
 */

require_once('Twitteroauth.php');
require_once('TwitterSearch.php');
require_once('Translations.php');


/**
 * Tweet Munger Class
 */
class TweetMunger {
	
    /**
     * The Twitter Search object
     *
     * @var object
     */
    protected $twitterSearch;

    /**
     * Set to true to output content only in the browser, false to post to twitter.
     *
     * @var bool
     */
    protected $debugMode = false;
    
    /**
     * The Twitter account we're copying from 
     *
     * @var string
     */
    protected $originalTwitterAccount;
    
    /**
     * The Twitter account we're posting to
     * 
     * @var string
     */
    protected $mungedTwitterAccount;

    /**
     * The email account used by TwitterSearch class to tell Twitter who's calling
     *
     * @var string
     */
    protected $userAgentAccount;

    /**
     * How many new tweets to translate each time
     * Good to set a limit in case the account gets heavy use
     * 
     * @var int
     */
    protected $newTweetCount = 10;

    /**
     * Retweets can sometimes confuse Twitter Munger due to inconsistent IDs so ignore by default
     *
     * @var bool
     */
    protected $ignoreRetweets = true;

    /**
     * Twitter Authorisation tokens
     * Register a new app at https://dev.twitter.com/
     *
     * @var string
     */
    protected $twitterConsumerKey;
    
    /**
     * Twitter Authorisation tokens
     *
     * @var string
     */
    protected $twitterConsumerSecret;
    
    /**
     * Twitter Authorisation tokens
     *
     * @var string
     */
    protected $twitterConsumerOauthToken;
    
    /**
     * Twitter Authorisation tokens
     *
     * @var string
     */
    protected $twitterConsumerOauthSecret;
    
    /**
     * Translation type
     *
     * @var string
     */
     protected $translations = "pirate";
     
     
     /**
      * Translator class
      *
      * @var obj
      */
      protected $translator;
	
	
    /**
     * Constructor.
     * Save/overwrite any default settings passed through during instantiation.  
     * (Not the best way to do it, but...)
     * 
     * @var array
     * @return void
     */
    public function __construct($options) {
        
        // save passed values
        foreach ($options as $option => $value) {
            if (property_exists($this, $option)) {
                $this->$option = $value;
            }
        }
        
        // prep the selected translations
        require_once('translations/'.$this->translations.'.php');
        $translatorClass = ucFirst($this->translations)."Translations";
        $this->translator = new $translatorClass;		
		
		// debug
		if ($this->debugMode) {
			$this->debug('<p><em>(Debug mode on, not posting to twitter)</em></p>');
		}
		
        // get the latest tweet from the munged account
        $this->twitterSearch = new TwitterSearch();
        $this->twitterSearch->user_agent = 'phptwittersearch:'.$this->userAgentAccount;
        $latestMungedTweetId = $this->getLatestMungedTweetId();

        // check if there have been any new tweets since this
        $tweets = $this->getLatestTweets($latestMungedTweetId);
        $tweets = array_reverse($tweets);
        
        // loop through all new tweets
        foreach ($tweets as $key => $tweet) {

            // mung text
            $text = $this->mungText($tweet->text, $tweet->id_str);
            
            // condition : if a translation is found, post to twitter
            if (!empty($text)) {
                $this->tweet($text);
            }
        }
	}
	
	
    /**
     * Get the Twitter ID of the latest translated tweet
     * 
     * @return string
     */
    private function getLatestMungedTweetId() {
        $this->twitterSearch->from($this->mungedTwitterAccount);
        $lastMungedTweet = $this->twitterSearch->rpp(1)->results();
        $latestMungedTweetId = @$lastMungedTweet[0]->id_str;
        $this->debug('<p>$latestMungedTweetId: '.$latestMungedTweetId.'</p>');
        return $latestMungedTweetId;
    }
    
    
    /**
     * Get all new tweets since the last munged tweet 
     * 
     * @var string
     * @return array
     */
    private function getLatestTweets($latestMungedTweetId) {
        $this->twitterSearch->from($this->originalTwitterAccount);
        $this->twitterSearch->since($latestMungedTweetId);
        $results = $this->twitterSearch->rpp($this->newTweetCount)->results();
        $this->debug('<p>New Tweet count: '.count($results).'</p>');
        $this->debug('<hr />');
        return $results;
    }
    
    
    /**
     * Translate a tweet
     * 
     * @var string $text 
     * @var int $id
     * @return string
     */
	private function mungText($text, $id) {

        $this->debug('<p>Original Tweet (ID - '.$id.'): ' . $text . '</p>');
        
        // condition : ignore retweet?
        if ($this->ignoreRetweets && strpos($text, "RT") === 0) {
            $this->debug("<p>Retweet found, ignoring...</p>");
            $this->debug('<hr />');
            return false;
        }
        
        // remove content twitter automatically turns into hashtags and user ids - so as not to annoy people!
        $text = strip_tags(trim($text));
        $text = str_replace('@', '_', $text);
        $text = str_replace('#', '_', $text);

        // use the translator class to translate
		$text = $this->translator->translate($text);
				
		// ensure new text length is <= 140 characters
        if (strlen($text) > 140) {
            $text = substr($text, 0, 137) . "...";
            $this->debug('<p>Text is too long, truncating...</p>');
        }

		$this->debug('<p>Translation: ' . $text . '</p>');
        
        // return the newly translated text
        return $text;
	}
	
	
    /**
      * Tweet the new text (if not in debug mode)
      * 
      * @var text
      * @return void
      */
     private function tweet($text) {
         $tweet = new TwitterOAuth($this->twitterConsumerKey, $this->twitterConsumerSecret, $this->twitterConsumerOauthToken, $this->twitterConsumerOauthSecret);
         if (!$this->debugMode) {
             $post = $tweet->post('statuses/update', array('status' => $text));
         } 
         $this->debug('<p>tweeting: ' . $text . '</p>');
         if (!$this->debugMode) {
             $this->debug("<pre style='font-size:9px;'>");
             $this->debug(print_r($post));
             $this->debug("</pre>");
         }
         $this->debug('<hr />');
     }
	
	
	/**
	 *
	 */
	private function debug($text) {
//		if ($this->debugMode) {
			echo $text;
//		}
	}
}

<?php
/*
 * Abstract Translations class
 * describes layout of all translations, and provides a few global methods
 */
abstract class Translations {

     /**
      * If there are any shouts, how often should they appear?
      * Higher = less frequent
      *
      * @var int
      */
     public $shoutFrequency = 5; 
    
    /**
     * Potential endings of sentences 
     *
     * @var array
     */
    abstract $shouts = array();
     
    /**
     * Translations!
     *
     * @var array
     */
    abstract $translations = array();

    /**
	 * Translate!
	 * Over-writable if necessary
     * 
     * @var string $text
     * @return string
	 */
	public function translate($text) {

    	foreach($this->translations as $search => $replace) {
    		$text = preg_replace("/\b$search\b/i", $replace, $text);
    	}

    	// condition : sometimes add in a funny end to a sentence
    	if (count($this->shouts > 0)) {
        	if (1 == rand(1,$this->shoutFrequency)) {
        		shuffle($this->shouts);			
        		$text = preg_replace("/\. /", $this->shouts[0], $text);
        	}
        }
        
        $this->debug('<p>Munging: ' . $text . '</p>');
        
        // condition : does the translation class contain any additional munging techniques?
        if (method_exists($this, "additionalMunging")) {
            $text = $this->additionalMunging($text);
        }

		return $text;
    }
}
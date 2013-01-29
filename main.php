<?php 

/**
Plugin Name: nLinks
Plugin URI: www.MarketingBurst.com
Description: Automatically create links for keywords found within posts. Set natural parameters to boost up the value of links within individual posts and for the blog as a whole.
Author: Spaxton1
Version: 2.0.1
Author URI: www.MarketingBurst.com
*/

define("aLinks_FILE", __FILE__);
define("aLinks_DIR", dirname(__FILE__));
define("aLinks_URL", plugins_url('/', __FILE__));

include aLinks_DIR . '/classes/class.custom-post-type.php';
aLinks_CustomPostTypes::init();

include aLinks_DIR . '/classes/class.keyphrase-parse.php';
aLinks_keyphraseParser::init();

include aLinks_DIR . '/classes/class.link-builder.php';


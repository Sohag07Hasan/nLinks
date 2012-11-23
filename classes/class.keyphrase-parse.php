<?php 
/*
 * Parsing the keyphrases with the associate link
 * */

class aLinks_keyphraseParser{
	
	//const REGEXP_PARSE = '#(?!((<h.*?)(<.*?)|(<a.*?)))\b(%s)\b(?!([^>]*?</h[1-6])|(([^<>]*?)>)|([^>]*?</a>))#i';
	const REGEXP_PARSE = '#(?!((<h.*?)(<.*?)|(<a.*?)))\b(%s)\b(?!([^>]*?</h[1-6])|(([^<>]*?)>)|([^>]*?</a>)|(\shttp))#i';
	const global_options_key = "aLinks_global_options";
	
	/*
	 * some static variables
	 * */
	static $keyPhrases = array();
	static $keyPhrase;
	static $key;
	static $options;
	static $total_parsed = 0;
	static $single_parsed = 0;
	static $probability = 0;
	static $used_hrefs = array();
	static $phrase_found = 0;
	static $probability_array = array();
	static $max_links = 0;
	static $single_probability = 1;
	static $probability_index = 0;
	static $max_links_per_post = 1;
	
	static $total_parsed_per_post = 0;
	
	
	
	static function init(){
		add_filter('the_content', array(get_class(), 'parse_keyPhrase'));
		register_activation_hook(aLinks_FILE, array(get_class(), 'activate_the_plugin'));
	}
	
	static function parse_keyPhrase($content){
		self::$single_parsed = 0;
		self::$total_parsed_per_post = 0;
		
		$keyPhrases = self::get_keyPhrases();					
		
		if(!empty($keyPhrases)) :
			
			$global_settings = self::get_global_options();
			
			//var_dump($global_settings);
			
			
			$max_links = $global_settings['max_link_p_post'];
			$max_links_per_post = $global_settings['max_link_p_post_bal'];
			
			if(!empty($max_links_per_post)){
				self::$max_links_per_post = $max_links_per_post;
			}
			
			$max_links_sitewise = $global_settings['max_links'];
			$randomize = $global_settings['randomize'];
			self::$probability = $global_settings['raw_url_percentage'];
			self::$max_links = $max_links;
			self::set_probability();		
			
			//var_dump(self::$probability);
			
			
			if(!empty($randomize)){
				$keyPhrases = self::shuffle_keyphrases($keyPhrases);
			}
			
			
			$is_unlimited = false;
			$is_random = false;
			
			
			if($max_links == -1){
				$is_unlimited = true;
			}
			
			if($max_links_sitewise == -1){
				$max_links_sitewise = 500;
			}
			
			//var_dump($max_links);
			//die();
						
			foreach($keyPhrases as $key => $Phrases){
				$link_replaced = 0;
				
				self::$key = $key;				
				$expression = self::get_regexExpression();								
				$phrase_found = preg_match_all($expression, $content, $matches);
				self::$phrase_found = $phrase_found;				
				
				$total_links = ($is_unlimited) ? 100 : $max_links;
				
				
				
				if($phrase_found){					
					
					self::set_options($Phrases[0]);					
					foreach($Phrases as $pno => $phrase){
						if(self::$total_parsed < $max_links_sitewise) :
																	
							self::$keyPhrase = $phrase;
														
							if(isset(self::$probability_array[self::$probability_index])){
								self::$single_probability = self::$probability_array[self::$probability_index];
							}
							else{
								self::$single_probability = 1;
							}
							
							$content = self::link_add_with_content($content, $link, $expression, $total_links);
							//self::$single_parsed = 0;	
							
						endif;			
					}
				}				
				
				self::$single_parsed = 0;
			}		
		endif;

		self::$used_hrefs = array();
		self::$probability_index = 0;
		self::$probability_array = array();
		return $content;
	}
	
	
	/*
	 * main functionality goes here
	 * */
	static function link_add_with_content($content, $link='', $regex, $total_links = 2){
		
		if(self::$total_parsed_per_post == self::$max_links_per_post){
			return $content;
		}
		
		if(self::$single_parsed == $total_links){			
			return $content;
		} 
		
	//	var_dump(self::$single_parsed);
		
		$link = self::get_associate_link();		
		
		if(!$link){
			return $content;
		}
		
		//$phrase_found = preg_match_all($expression, $content, $matches);
		
		$new_content = preg_split($regex, $content);						
		if(count($new_content) > 2){					
			//$index = count($new_content) - 2;
			$rand = 1;
			$new_content[$rand] .= $link . $new_content[$rand+1];			
			unset($new_content[$rand+1]);
			$content = implode(self::$key, $new_content);
			
			self::$probability_index ++;			
			self::$single_parsed ++;	
			self::$total_parsed ++;	
			self::$total_parsed_per_post ++;
				
			return self::link_add_with_content(implode(self::$key, $new_content), $link, $regex, $total_links);
		}
		else{
			self::$single_parsed ++;
			self::$probability_index ++;
			self::$total_parsed ++;	
			self::$total_parsed_per_post ++;
			return preg_replace($regex, $link, $content, 1);
		}
		
	}
	
	
	
	/*
	 * ruturn the associate links using linksbuilder class
	 * */
	static function get_associate_link(){
		$link_builder = new aLinks_linksbuilder();
		$link_builder->set_ingredents(self::send_ingredents());		
		$link = $link_builder->get_prepared_url();
		return $link;
	}
	
	
	/*
	 * set the probabilty
	 * 
	 * */
	static function set_probability(){		
		$probability = self::$probability;		
		if($probability == 1){
			for($i=1; $i<=40; $i++){
				if(fmod($i, 4)){
					self::$probability_array[] = 1;
				}
				else{
					self::$probability_array[] = 0;
				}
			}					
		}
		
		if($probability == 2){
			for($i=1; $i<=40; $i++){
				if(fmod($i, 2)){
					self::$probability_array[] = 1;
				}
				else{
					self::$probability_array[] = 0;
				}
			}	
		}
			
		
	}
	
	
	
	/*
	 * send necessary parameters to the link builder
	 * */
	static function send_ingredents(){
		$ingredents = array(
			'keyphrase' => self::$keyPhrase->post_title,
			'href' => get_post_meta(self::$keyPhrase->ID, aLinks_CustomPostTypes::metakey_link, true),
			'title' => self::$keyPhrase->post_content,
			'settings' => self::$options,
			'exchange' => get_post_meta(self::$keyPhrase->ID, aLinks_CustomPostTypes::metakey_exchange, true),
			'probability' => self::$probability											
		);
		
		//var_dump($ingredents);
		//die();
		
		return $ingredents;
	}
	
	
	
	
	/*
	 * replacement check 
	 * */
	 static function keyword_replacement_check(){
		
	 }
	
	
	
	//return the global options
	static function get_global_options(){
		$settings = aLinks_CustomPostTypes::get_global_options();
		return $settings;
	}
	
	//get edited content
	static function get_edited_content($content, $expression, $link){
		$contents = preg_split($expression, $content);
		$contents[0] = $contents[0] . $link;
		
		return implode(self::$keyPhrase->post_title, $contents);
	}
	
	//shuffle the keyphrases
	static function shuffle_keyphrases($keyphrases){
		foreach($keyphrases as $key => $keyphrase){
			shuffle($keyphrases[$key]);
		}
		
		return $keyphrases;
	}
	
	
	/*
	 * returnt eh keyphrases
	 * */
	static function get_keyPhrases(){
		if(empty(self::$keyPhrases)) :
			global $wpdb;
			$post_type = self::get_postType();
			$sql = "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type = '$post_type' AND post_status = 'publish'";
			$keyPhrases = $wpdb->get_results($sql);
			if($keyPhrases){
				foreach($keyPhrases as $phrase){				
					self::$keyPhrases[strtolower($phrase->post_title)][] = $phrase;
				}
			}
		endif;
				
		return self::$keyPhrases;
	}
	
	
	/*
	 * return the posttype
	 * */
	static function get_postType(){
		return aLinks_CustomPostTypes::posttype;
	}
	
	
	/*
	 * return the regular expression
	 * */
	static function get_regexExpression(){		
		return sprintf(self::REGEXP_PARSE, self::$key);
	}
	
	
	
	/*
	 * set options
	 * */
	static function set_options($phrase){
		$options = get_post_meta($phrase->ID, aLinks_CustomPostTypes::metakey_option, true);
		$randomness = get_post_meta($phrase->ID, aLinks_CustomPostTypes::metakey_randomness, true);
		
		self::$options = array(
			'options' => $options,
			'randomness' => $randomness
		);
	}
	
	
	/*
	 * activate the plugin
	 * */
	static function activate_the_plugin(){
		$new_options = array(
				'max_link_p_post' => 2,
				'randomize' => "Y",				
				'max_links' => "-1",
				'raw_url_percentage' => 2
		);
			update_option(self::global_options_key, $new_options);
	}
}

<?php
/*
 * builds links
 * */

class aLinks_linksbuilder{
	private  $link = '';
	private  $link_ingredents = array();
	private  $title = '';
	private  $href = '';
	private  $keyPhrase = '';
	private  $is_amazon = false;
	private  $randomness;
	private  $option;
	private  $exchange;
	private  $probability;
	private  $used_hrefs = array();
	
	
	public function set_ingredents($ingredents){
		$this->link_ingredents = $ingredents;
		$this->set_title();
		$this->set_href();
		$this->set_keyPhrase();
		$this->set_option();
		$this->set_randomness();
		$this->set_exchange();
		$this->set_probability();
	}
	
	public function get_prepared_url(){
		$link = $this->keyPhrase;	
		
		if(in_array($this->href, aLinks_keyphraseParser::$used_hrefs)){
			return false;
		}
		else{
			aLinks_keyphraseParser::$used_hrefs[] = $this->href;
		}
		
		//var_dump(aLinks_keyphraseParser::$used_hrefs);
		
		$raw_probability = $this->get_row_url_probability();
		switch($this->option){
			case "1" :
				if($raw_probability == 1){
					//$link = $this->keyPhrase . ' ' . "<a href='$this->href'>$this->href</a>";
					$link = '<a href="' . $this->href . '"> ' . $this->keyPhrase . '</a>';
				}
				else{
					//$link = '<a href="' . $this->href . '"> ' . $this->keyPhrase . '</a>';
					$link = $this->keyPhrase . ' ' . "<a href='$this->href'>$this->href</a>";
				}
				break;
				
			case "3" :
				if($raw_probability == 1){
					$link = $this->exchange . ' ' . "<a href='$this->href'>$this->href</a>";
				}
				else{
					$link = '<a href="' . $this->href . '"> ' . $this->exchange . '</a>';
				}
				break;			
							
		}
		
		return $link;
	}
	
	
	private function set_title(){
		$this->title = $this->link_ingredents['title'];
	}
	
	private function set_href(){
		$this->href = $this->link_ingredents['href'];
	}
	
	private function set_keyPhrase(){
		$this->keyPhrase = $this->link_ingredents['keyphrase'];
	}	
	
	private function set_option(){
		$this->option = $this->link_ingredents['settings']['options'];
	}
	
	private function set_randomness(){
		$this->randomness = $this->link_ingredents['settings']['randomness'];
	}
	
	private function set_exchange(){
		$this->exchange = $this->link_ingredents['exchange'];
	}
	
	private function set_probability(){
		$this->probability = $this->link_ingredents['probability'];
	}
	
	
	/*
	 * sanitize the probability
	 * */
	private function get_row_url_probability(){
		
		//var_dump($this->probability);
		//exit;
		
		if($this->probability == 1){
			$array = array(1, 1, 0, 1);
			return $array[rand(0, 3)];
		}		
		elseif($this->probability == 2){
			$array = array(1, 0, 1, 0);
			return $array[rand(0, 3)];
		}
		else{
			return 0;
		}
		
	}
}
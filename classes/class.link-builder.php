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
		//var_dump($raw_probability . ' ' . $this->option . ' ' . $this->href . ' ' . $this->keyPhrase);
		switch($this->option){
			case "1" :
				if($raw_probability == 1){					
					$link = '<a title="'.$this->title.'" href="' . $this->href . '"> ' . $this->keyPhrase . '</a>';
				}
				else{					
					$link = $this->keyPhrase . ' ' . "<a title='$this->title' href='$this->href'>$this->href</a>";
				}
				break;
				
			case "3" :
				if($raw_probability == 1){
					$link = '<a title="'.$this->title.'" href="' . $this->href . '"> ' . $this->exchange . '</a>';
					
				}
				else{
					$link = $this->exchange . ' ' . "<a title='$this->title' href='$this->href'>$this->href</a>";
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
		//var_dump($this->probability);
	}
	
	
	/*
	 * sanitize the probability
	 * */
	private function get_row_url_probability(){
		return aLinks_keyphraseParser::$single_probability;	
	}
}
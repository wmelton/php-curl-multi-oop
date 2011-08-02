<?php
/**
 * @TODO Jāizveido iespēja rezultātu uzreiz saglabāt failā uz fopen handle
 * Pieprasījumu klase
 * @author Mārtiņš Balodis
 */
class Curl
{
	/**	
	 * Curl Handle
	 * @var 
	 */
	private $ch;
	
	/**
	 * 
	 */
	public function __construct()
	{
		// Inicializē Curl handleri
		$this->ch = curl_init();
		
		// Rezultāts tiek atgriezts nevis izvadīts
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		// Saglabā sūtāmos headerus
		curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
//		# TRUE to include the header in the output. 
//		curl_setopt($this->ch, CURLOPT_HEADER, true);
		// No kādas pārlūkprogrammas it kā atvērta lapa
		curl_setopt($this->ch, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.9");
		// Seko redirektiem
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		// Redirektu limits
		curl_setopt($this->ch, CURLOPT_MAXREDIRS, 4);
		// The contents of the "Accept-Encoding: " header. This enables decoding of the response. Supported encodings are "identity", "deflate", and "gzip". If an empty string, "", is set, a header containing all supported encoding types is sent. 
		curl_setopt($this->ch, CURLOPT_ENCODING, "");
//		// The name of a file to save all internal cookies to when the connection closes.
//		$cookie_jar = str_replace('\\','/',dirname(__FILE__).'/cookies.class.curl.txt');
//		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_jar);
	}
	
	/**
	 *
	 * @param integer $option Curl constant
	 * @see http://php.net/manual/en/function.curl-setopt.php
	 * @param mixed $value 
	 */
	public function setopt($option,$value) {
		
		curl_setopt ($this->ch, $option, $value);
		
	}
	
	/**
	 * Uzstāda datu sūtīšanas metodi
	 * @param string $method 
	 */
	public function set_method($method)
	{
		switch($method)
		{
			case 'POST' : curl_setopt ($this->ch, CURLOPT_POST, true); break;
			case 'GET' : curl_setopt ($this->ch, CURLOPT_HTTPGET, TRUE); break;
			default : throw new Exception('Nederīga metode',1);
		}
	}
	
	/**
	 * Parametri, kas jāpadod kā POST dati
	 * @param string $post_string 
	 */
	public function set_post_string($post_string)
	{
		curl_setopt ($this->ch, CURLOPT_POSTFIELDS, $post_string);
	}
	
	/**
	 * Uzstāda linku, kuru vajadzēs atvērt
	 * @param string $url 
	 */
	public function set_url($url)
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);
	}
	
	/**
	 * Veic pieprasījumu
	 * @param string $url 
	 * @param string $set_method 
	 */
	public function exec($url=false,$set_method = false)
	{
		// Uzstāda url, ja tāds ir padots
		if($url!==false)
		{
			$this->set_url($url);
		}
		
		// Uzstāda izpildes metodi, ja tāda ir padota
		if($set_method!==false)
		{
			$this->set_method($set_method);
		}
		
		// Veic pieprasījumu
		$result = curl_exec($this->ch);
		
		return $result;
	}
	
	/**
	 * Atgriež headerus kādi tika nosūtīti uz serveri
	 * @return string 
	 */
	public function get_headers_sent()
	{
		return curl_getinfo($this->ch,CURLINFO_HEADER_OUT);
	}
	
	/**
	 * 
	 */
	public function get_headers_received()
	{
		
	}
	
	public function get_handle()
	{
		return $this->ch;
	}
}
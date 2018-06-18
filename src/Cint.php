<?php

namespace Opinodo\CintLibrary;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Cint
{
	private $url;
	private $api_key;
	private $api_secret;
	
	private $client;
	private $response;
	private $errors;
	
	function __construct() {

		$this->url = Config::get('cint.sandbox') ? Config::get('cint.url_sandbox') : Config::get('cint.url_live');
		$this->url = trim( $this->url, '/' ) . '/';
		
		$this->client = new Client();
	}
	
	public function check() 
	{
		$this->getPanel();
		
		if ( $this->getStatusCode() == 200 )
		{
			return true;
		} else
		{
			Log::error('Cint auth false: ' . $this->url );
			return false;		
		}
	}
	
	public function getStatusCode()
	{
		return $this->response->getStatusCode();
	}
	
	public function getReason()
	{
		return $this->response->getReasonPhrase();
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function setAuth( Array $option ) 
	{
        if(Config::get('cint.sandbox')) {
            $this->api_key		= Config::get('cint.sandbox_key');
            $this->api_secret	= Config::get('cint.sandbox_secret');

        }
        else {
            $this->api_key		= $option['api_key'];
            $this->api_secret	= $option['api_secret'];
        }
	}
	
	public function getPanel()
	{
		$response = $this->request( 'GET', 'panels/' . $this->api_key );
		
		return $response ? $response['panel'] : FALSE;
	}

	public function getEvents( $data = array() )
	{
		$response = $this->request( 'GET', 'panels/' . $this->api_key . '/events', $data );

		return $response ? $response : FALSE;
	}

	public function createPanelist( $data )
	{
		$response = $this->request( 'POST', 'panels/' . $this->api_key . '/panelists', $data );
		
		return $response ? $response['panelist'] : FALSE;
	}
	
	public function getPanelist( $panelist_id )
	{
		$response = $this->request( 'GET', 'panels/' . $this->api_key . '/panelists/' . $panelist_id );
		
		return $response ? $response['panelist'] : FALSE;
	}

    public function patchPanelist( $panelist_id, $data )
    {
        $response = $this->request( 'PATCH', 'panels/' . $this->api_key . '/panelists/' . $panelist_id, $data );

        return $response ? TRUE : FALSE;
    }

    public function getVariables( $panelist_id )
    {
        $response = $this->request( 'GET', 'panels/' . $this->api_key . '/panelists/' . $panelist_id . '/variables');

        return $response;
    }
	
	public function getRespondentQuotas()
	{
		$response = $this->request( 'GET', 'panels/' . $this->api_key . '/respondent_quotas' );
		
		return $response ? $response['respondent_quotas'] : FALSE;
	}
	
	public function createCandidateRespondent( $panelist_id, $data )
	{
		$response = $this->request( 'POST', 'panels/' . $this->api_key . '/panelists/' . $panelist_id . '/candidate_respondents', $data );
		
		return $response ? $response : FALSE;
	}

    public function unsubscribePanelist( $data )
    {
        $response = $this->request( 'DELETE', 'panels/' . $this->api_key . '/panelists/'.$data );

        return $response ? $response : FALSE;
    }

	private function request( $method, $source, $data = null )
	{
		$method		= strtolower($method);
		$url		= $this->url . $source;

		$options	= [
			'auth' => [$this->api_key, $this->api_secret, 'basic'],
			'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
		];
		
		if ( !empty( $data ) ) {
			if ( in_array( $method, array('post', 'put') ) ) {
				$options['body'] = json_encode($data);
			} else {
				$options['query'] = $data;
			}
		}
		
		try {
            $this->response = $this->client->{$method}( $url, $options);
		} catch (RequestException $e) {

			if ($e->hasResponse()) {

				$this->response =  $e->getResponse();
				$this->errors = json_decode( $this->response->getBody(), true );
			}
			
			if ( empty($this->errors) ) {
				$this->errors = [ 'system' => 'Whoops, looks like something went wrong.' ];
			}

			if(!isset($this->errors)) {
                Log::info('Cint debug: '. $method .' url: ' . $url . ' options: ' . print_r(json_decode( $this->response, true ), true));
            }

			Log::error('Cint response: ', $this->errors );

			return FALSE;
		}

		$response = $this->response->getBody();

		return json_decode( $response, true );
	}
	

}
<?php

namespace There4\FogBugz;

/**
 * Simple Curl wrapper to encapsulate any special settings
 *
 * @author Craig Davis <craig@there4development.com>
 */
class Curl
{
    /**
     * Our Curl connection reference
     * @var resource
     */
    private $_ch;

    /**
     * last response
     * @var string
     */
    public $response;

    /**
     * Constructor inits our curl
     *
     * @return void
     */
    public function __construct()
    {
        // Let's be nice and let them know we are out here
        $agent
            = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0; "
            . "There4 FogBugz API http://git.io/6uZNKQ)";

        $this->_ch = curl_init();

        // set the agent, forwarding, and turn off ssl checking
        curl_setopt_array(
            $this->_ch,
            array(
                CURLOPT_USERAGENT      => $agent,
                CURLOPT_VERBOSE        => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_SSL_VERIFYPEER => false
            )
        );
    }

    /**
     * Fetch a url
     *
     * @param string $url path to fetch
     * @param array $params post parameters
     *
     * @return void
     */
    public function fetch($url, array $params = array())
    {
        // Set the url and parameters
        curl_setopt_array(
            $this->_ch,
            array(
                CURLOPT_URL        => $url,
                CURLOPT_POST       => true,
                CURLOPT_POSTFIELDS => $params
            )
        );

        // Execute the curl call
        $this->response = curl_exec($this->_ch);

        // Check for errors and throw an exception if something happened
        if (curl_errno($this->_ch)) {
            throw new CurlError(curl_error($this->_ch), curl_errno($this->_ch));
        }

        return $this->response;
    }

    /**
     * Destructor closes the curl instance
     *
     * @return void
     */
    public function __destruct()
    {
        curl_close($this->_ch);
    }
}

/* End of file Curl.php */

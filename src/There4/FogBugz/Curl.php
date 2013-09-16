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
   * Our curl connection reference
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
            CURLOPT_VERBOSE        => FALSE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_AUTOREFERER    => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE
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
    // set the url and parameters
    curl_setopt($this->_ch, CURLOPT_URL, $url);
    curl_setopt($this->_ch, CURLOPT_POST, true);
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $params);

    // execute the curl call
    $this->response = curl_exec($this->_ch);

    // check for errors and throw an exception if something happened
    if (curl_errno($this->_ch)) {
        throw new CurlError(
            curl_error($this->_ch),
            curl_errno($this->_ch)
        );
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

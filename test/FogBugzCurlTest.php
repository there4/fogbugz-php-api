<?php
require_once '_autoloader.php';
use There4\FogBugz;

class FogBugzCurlTest extends PHPUnit_Framework_TestCase
{
  protected $curl;

  protected function setUp()
  {
    $this->curl = new FogBugz\Curl();
  }

  public function testServerHasRequirements()
  {
    if (extension_loaded('curl')) {
      $this->assertTrue(TRUE);

      return;
    }
    $this->fail('Curl extension is not available');
  }

  public function testCurlCanFetchExample()
  {
    $this->assertInternalType(
        'string',
        $this->curl->fetch('http://www.example.com')
    );
  }

  /**
   * @expectedException There4\FogBugz\CurlError
   */
  public function testCurlThrowsExceptionOnHttpError()
  {
    $this->curl->fetch('http://404.example.com');
  }

}

/* End of file FogBugzCurlTest.php */
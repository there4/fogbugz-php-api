<?php
 
class FogBugzCurlTest extends PHPUnit_Framework_TestCase {

  protected $curl;
  
  protected function setUp() {
    require_once __DIR__ . '/../lib/api.php';
    $this->curl = new FogBugzCurl();
  }

  public function testServerHasRequirements() {
    
    if (extension_loaded('curl')) {
      $this->assertTrue(TRUE);
      return;
    }
    $this->fail('Curl extension is not available');
  }
  
  public function testCurlCanFetchExample() {
    $this->assertInternalType(
        'string',
        $this->curl->fetch('http://www.example.com')
    );
  }
  
  /**
   * @expectedException FogBugzCurlError
   */
  public function testCurlThrowsExceptionOnHttpError() {
    $this->curl->fetch('http://404.example.com');
  }

}
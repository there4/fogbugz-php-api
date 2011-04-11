<?php
 
class FogBugzAPITest extends PHPUnit_Framework_TestCase {

  protected $user = "user@example.com";
  protected $pass = "pAssWoRd";
  protected $url  = "http://example.com";

  protected function setUp() {
    require_once __DIR__ . '/../lib/api.php';
  }
    
  public function testCanParseToken() {

      $fogbugz = new FogBugz($this->user, $this->pass, $this->url);
      
      // swap out our connection object
      $fogbugz->curl = $this->getMock('FogBugzCurl');
      
      // set the xml we would expect to see on a login
      $fogbugz->curl
          ->expects($this->any())
          ->method('fetch')
          ->will($this->returnValue(
              file_get_contents(__DIR__ . '/data/login_expected.xml')
          ));
      
      // this will fetch the data above and parse the token
      $fogbugz->logon();
      
      // confirm we read the token correctly
      $this->assertEquals(
        'sdodkc5adoq244f1ef51d9dje1eu05',
        $fogbugz->token,
        "Unable to correctly parse token"
      );
  }

  public function testCanCatchError() {
      $fogbugz = new FogBugz($this->user, $this->pass, $this->url);
      $fogbugz->curl = $this->getMock('FogBugzCurl');
      $fogbugz->curl
          ->expects($this->any())
          ->method('fetch')
          ->will($this->returnValue(
              file_get_contents(__DIR__ . '/data/error.xml')
          ));
      
      // we simulate a situation where we are not logged in
      // this should throw an exception if it parses the
      // xml properly
      try {
        $fogbugz->startWork(array("ixBug" => 213));
      }
      catch (FogBugzAPIError $expected) {
        $this->assertEquals(
          3,
          $expected->getCode(),
          "Error code was not processed correctly"
        );
        
        $this->assertEquals(
          "Not logged on",
          $expected->getMessage(),
          "Error message was not processed correctly"
        );
        return;
      }    
      
      $this->fail("An exception was not raised");
  }

}

?>
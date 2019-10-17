<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use There4\FogBugz;
use There4\FogBugz\Curl;

class FogBugzAPITest extends TestCase
{
    protected $user = "user@example.com";
    protected $pass = "pAssWoRd";
    protected $url  = "http://example.com";

    protected function setUp()
    {
    }

    protected function getMockWithData($filename)
    {
        $fullpath = realpath(__DIR__ . '/data/' . $filename);

        if (!is_readable($fullpath)) {
            throw new Exception("Invalid filename in mock data: $filename");
        }

        $xml  = file_get_contents($fullpath);
        $curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['fetch'])
            ->getMock();

      // Set the xml we would expect to see on a login
        $curl
            ->expects($this->any())
            ->method('fetch')
            ->will($this->returnValue($xml));

        return $curl;
    }

    public function testCanParseToken()
    {
        $fogbugz = new FogBugz\Api($this->user, $this->pass, $this->url);

        $fogbugz->curl = $this->getMockWithData('login_expected.xml');

        // This will fetch the data above and parse the token
        $fogbugz->logon();

        // Confirm we read the token correctly
        $this->assertEquals(
            'sdodkc5adoq244f1ef51d9dje1eu05',
            $fogbugz->token,
            "Unable to correctly parse token"
        );
    }

    public function testCanCatchError()
    {
        $fogbugz = new FogBugz\Api($this->user, $this->pass, $this->url);

        $fogbugz->curl = $this->getMockWithData('error.xml');

        // We simulate a situation where we are not logged in.
        // This should throw an exception if it parses the xml properly.
        try {
            $fogbugz->startWork(array("ixBug" => 213));
        } catch (FogBugz\ApiError $expected) {
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

  /**
   * @expectedException There4\FogBugz\ApiLogonError
   */
    public function testInvalidLogonThrowsException()
    {
        $fogbugz = new FogBugz\Api($this->user, $this->pass, $this->url);

        $fogbugz->curl = $this->getMockWithData('error_1.xml');

        // This will fetch the data above and parse the token
        $fogbugz->logon();
    }

  /**
   * @expectedException There4\FogBugz\ApiError
   */
    public function testInvalidRequestHandlesException()
    {
        $fogbugz = new FogBugz\Api($this->user, $this->pass, $this->url);

        $fogbugz->curl = $this->getMockWithData('error.xml');

        // This will fetch the data above and parse the token
        $fogbugz->startWork(array(
            'ixBug' => 23442
        ));
    }

  /**
   * @expectedException There4\FogBugz\ApiError
   */
    public function testRequestHandlesCurlException()
    {
        $fogbugz = new FogBugz\Api($this->user, $this->pass, $this->url);

        $fogbugz->curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['fetch'])
            ->getMock();

        // set the xml we would expect to see on a login
        $fogbugz->curl
            ->expects($this->any())
            ->method('fetch')
            ->will($this->throwException(new FogBugz\CurlError('Unit testing mock', 42)));

        // This will fetch the data above and parse the token
        $fogbugz->startWork(array(
            'ixBug' => 23442
        ));
    }
}

/* End of file FogBugzTest.php */

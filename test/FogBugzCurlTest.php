<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use There4\FogBugz;

class FogBugzCurlTest extends TestCase
{
    protected $curl;

    protected function setUp()
    {
        $this->curl = new FogBugz\Curl();
    }

    public function testServerHasRequirements()
    {
        if (extension_loaded('curl')) {
            $this->assertTrue(true);

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

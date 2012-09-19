[![Build Status](https://secure.travis-ci.org/there4/fogbugz-php-api.png?branch=master)](http://travis-ci.org/there4/fogbugz-php-api)

# FogBugz PHP API

See the api documentation at [XML API Documentation](http://fogbugz.stackexchange.com/fogbugz-xml-api)

This is a small API used to wrap up the FogBugz API into an easy to call
object. It allows access to all of the API commands exposed by FogBugz,
and returns a SimpleXMLElement object for any commands that return data.

## FogBugz CLI

This project was written in support for a FogBugz command line client.
Check out my repo at [there4/fogbugz-php-cli](https://github.com/there4/fogbugz-php-cli)
for a working command line tool for FogBugz. You can notate cases,
track time working, and review histories. Try it, you'll like it.

## Sample Code

    use There4\FogBugz;
    $fogbugz = new FogBugz\Api(
        'username@example.com',
        'password',
        'http://example.fogbugz.com'
    );
    $fogbugz->logon();
    $fogbugz->startWork(array(
      'ixBug' => 23442
    ));


## Magic Methods

The API uses __call() to make a method for each api endpoint in the FogBugz API.
For example, to call the stopWork api endpoint, simple call a method on the
fogbugz object $fogbugz->stopWork(). If you want to call the api with specific
parameters, supply those to the function as an associative array, as in the
sample above.

## Return Format

Remember that the api methods return SimpleXMLElement objects. See the sample.php
file for an example of this.


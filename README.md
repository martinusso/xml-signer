## xml-signer

[![Build Status](https://travis-ci.org/martinusso/xml-signer.svg?branch=master)](https://travis-ci.org/martinusso/xml-signer)
[![Build Status](https://scrutinizer-ci.com/g/martinusso/xml-signer/badges/build.png?b=master)](https://scrutinizer-ci.com/g/martinusso/xml-signer/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/martinusso/xml-signer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/martinusso/xml-signer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/martinusso/xml-signer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/martinusso/xml-signer/?branch=master)

XML Signer allows you to sign XML documents using X.509 digital certificates.


### Installation

You can install the xml-signer in 2 different ways:

Install it via Composer

`$ composer require martinusso/xml-signer`

Or, clone the Git repository (`https://github.com/martinusso/xml-signer`).


### How to use it

#### Instantiating a certificate object with PFX file

```
$password = '.pfx password here!';
$pfx = file_get_contents('path/to/certificate.pfx');
return Certificate::readPfx($pfx, $password);
```

### Contribute

Please refer to [CONTRIBUTING.md](https://github.com/martinusso/xml-signer/blob/master/CONTRIBUTING.md) for information on how to contribute to XmlSigner

### License

This library is released under the [MIT license](https://github.com/martinusso/xml-signer/blob/master/LICENSE).

## Contributing

 - Coding standard for the project is [PSR-2](http://www.php-fig.org/psr/psr-2/)
 - Any contribution must provide tests for additional introduced conditions

## Workflow

 - Fork the project.
 - Make your bug fix or feature addition.
 - Add tests for it. No test, no :beers:
 - Send a pull request.

### Installation

To install the project and run the tests, you need to clone it first:

```
$ git clone git@github.com:martinusso/xml-signer.git
```

You will then need to run a composer installation:

```
$ cd xml-signer
$ curl -s https://getcomposer.org/installer | php
$ php composer.phar update
```

### Testing

```
$ composer test
```

or 


```
$ composer test-cover
```

RethinkDB Translator
=================

This PHP library providing a simple way to translate the result of `danielmewes/php-rql` library.

[![Latest Stable Version](https://poser.pugx.org/th3mouk/rethinkdb-translator/v/stable)](https://packagist.org/packages/th3mouk/rethinkdb-translator)
[![Latest Unstable Version](https://poser.pugx.org/th3mouk/rethinkdb-translator/v/unstable)](https://packagist.org/packages/th3mouk/rethinkdb-translator)
[![Total Downloads](https://poser.pugx.org/th3mouk/rethinkdb-translator/downloads)](https://packagist.org/packages/th3mouk/rethinkdb-translator)
[![License](https://poser.pugx.org/th3mouk/rethinkdb-translator/license)](https://packagist.org/packages/th3mouk/rethinkdb-translator)

[![Build Status](https://travis-ci.org/Th3Mouk/rethinkdb-translator.svg?branch=master)](https://travis-ci.org/Th3Mouk/rethinkdb-translator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Th3Mouk/rethinkdb-translator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Th3Mouk/rethinkdb-translator/?branch=master)

## Installation

`composer require th3mouk/rethinkdb-translator`

## Usage

It exists three transformations:

- `Translate::cursorToAssociativeArray`
- `Translate::arrayObjectToAssociativeArray`
- `Translate::arrayOfArrayObjectToAssociativeArray`

## Options

You can pass an array of options to each of the available methods, here are the available options :

| Option           | Description                                                     | Value   | Default |
|------------------|-----------------------------------------------------------------|---------|---------|
| dateTimeToString | Should the dateTime instances be converted to strings (ISO8601) | boolean | true    |

An example :

```php
$options = ['dateTimeToString' => false];

// raw $data from RethinkDB
Translate::cursorToAssociativeArray($data, $options);
```

## Please

Feel free to improve this library.

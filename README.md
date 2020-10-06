# PHP Data Extractor

## Why

Everytime that I had to parse a file like a CNAB, EDI or any CSV file, the process became very similar and always returning an array that I could not ever trust on what kind of index they will bring back. Some times breaking the application because some data did not come.
So, I decided to write this library that works with objects like models for extracted data. The way the data will be extracted is wrote on models attributes docblock, and this will grant to developers an auto-complete on models attributes with a proper description on how the extraction was made, and optionaly, a descrition of the meaning of this data. What I really missed on arrays.

## Instalation

Execute `composer require fmezini/data-extractor`

Or add `fmezini/data-extractor: "*"` to required section of your composer.json file.

## Usage

The Data Extractor works above models attributes. A model can be any class with public attributes that have the `@stractable` tag on its docblock.

At version 1, Data extractor brings 3 types of data getters, being: substring, array and regex.

Each type of value getter require its own docblok tags that must be a subtag from `@extractable` tag.

### Substring

The substring value getter works just like substr PHP function where,

+ `{@start}` is the initial position, based on 0 index.
+ `{@length}` is the length of the desired text

```php
/**
 * @extractable
 *   {@start integer}
 *   {@length integer}
 */
```

### Array

The index is a simple array index tha must be extracted (say, from an CSV file). Both numeric or associative index.

```php
/**
 * @extractable
 *   {@index mixed}
 */
```

### Regex

Regex uses patterns to match the desired data, where:

+ `{@pattern}` must be any valid pattern tha must match the desired data
+ `{@index}` is a numeric index of the matched data from pattern

```php
/**
 * @extractable
 *   {@pattern string}
 *   {@index integer}
 */
```

### The Model

Write a class with public attributes with docblock description to guide the Extractor

```php

class MyModel
{
    /**
     * @extractable
     *    {@start 0}
     *    {@length 10}
     * @otherTag from prop 1
     */
    public $prop1;

    /**
     * @extractable
     *    {@start 10}
     *    {@length 11}
     * @otherTag from prop 2
     */
    public $prop2;
}
```

### Getting the extractor

The extractor can be directly instantiated or created via factory

#### Directly

```php
use Fmezini\DataExtractor\Extractor;
use Fmezini\DataExtractor\DocBlockParser;
use Fmezini\DataExtractor\ValueGetters\ArrayValueGetter;
use Fmezini\DataExtractor\ValueGetters\RegexValueGetter;
use Fmezini\DataExtractor\ValueGetters\SubstringValueGetter;

//Array extractor
$extractor = new Extractor(
    DocBlockParser::createInstance(),
    new ArrayValueGetter()
);

//Regex extractor
$extractor = new Extractor(
    DocBlockParser::createInstance(),
    new RegexValueGetter()
);

//Substring extractor
$extractor = new Extractor(
    DocBlockParser::createInstance(),
    new SubstringValueGetter()
);
```

#### Via Factory

```php
use Fmezini\DataExtractor\ExtractorFactory;

$factory = new ExtractorFactory();

//Array extractor
$extractor = $factory->createArrayExtractor();

//Regex extractor
$extractor = $factory->createRegexExtractor();

//Substring extractor
$extractor = $factory->createSubstringExtractor();
```

### Extracting the data from source

The extraction process can use the model namespace or an instance;

```php
//By namespace
$extractedModel = $extractor->extract(MyModel::class, 'First dataSecond Data');

//By instance
$extractedModel = $extractor->extract(new MyModel(), 'First dataSecond Data');

echo $extractedModel->prop1; //will give 'First data'
echo $extractedModel->prop2; //will give 'Second data'
```

Optionaly a callback can be provided on third paramenter to touch the model after the extraction

```php
$extractedModel = $extractor->extract(
    MyModel::class,
    'First dataSecond Data',
    function ($model, $propertiesSchema) {
        foreach ($propertiesSchema as $property => $schema) {
            $model->{$property} .= $schema['otherTag'];
        }
    }
);

echo $extractedModel->prop1; //will give 'First data from prop 1'
echo $extractedModel->prop2; //will give 'Second data from prop 2'
```

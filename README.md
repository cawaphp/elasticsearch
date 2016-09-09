# Сáша ElasticSearch
-----

## Warning
Be aware that this package is still in heavy developpement.
Some breaking change will occure. Thank's for your comprehension.

## Features
* Simple override of [official ElasticSearch client](https://github.com/elastic/elasticsearch-php) in order to have profiling enable
* Simple QueryBuilder 
* Add a configuration variable `type` & `index` to force index globally 
* For browsing result, you can use [JMESPath](https://github.com/jmespath/jmespath.php)

## Basic Usage

```php
$search->add("aggregations/city", new QueryBuilder([
    "terms" => [
        "field" => "city",
        "size" => 0,
    ],
]));

$search->add("aggregations/article/children/type", "article");

$search->add("query/bool/must[]", [
    "terms" => [
        "city" => ['london', 'paris'],
    ],
]);
```

### License

Cawa is licensed under the GPL v3 License - see the `LICENSE` file for details

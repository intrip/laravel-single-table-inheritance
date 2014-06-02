#Laravel-Single-Table-Inheritance

Mapping domain model into relational database is hard.For this reason there are many ways to do that. Single table inheritance is one of them. the stengths of this approach are:
 - Is simple
 - Moving column between hierarchy doesn't require db changes
But there are some weakness in that approach:
 - There is no metadata to define which attribute belongs to which subtype: looking table directly is a bit weird
 - The table will quiclky become a bottleneck if you create many hierarchies
 - You waste some space with empty columns(depending on dbms compression of nulls)

Laravel-Single-Table-Inheritance is a package that offer a simple and basic model to handle single table inheritance with eloquent ORM. Be aware this pattern come from Martin Fowler(Patterns of Enterprise Application Architecture). 
For more information follow this link: <a href="http://martinfowler.com/eaaCatalog/index.html" target="_blank">Single Table Inheritance</a>. 
Also check my post about <a href="http://www.jacopobeschi.com/post/php-table-inheritance" target="_blank">Single table inheritance</a>.

- **Author**: Jacopo Beschi
- **Version**: 1.0.0

[![Build Status](https://travis-ci.org/intrip/laravel-single-table-inheritance.png)](https://travis-ci.org/intrip/laravel-single-table-inheritance)

## Requirements

- PHP >= 5.3.7
- Composer
- Laravel framework 4.0.* or superior

##Installation with Composer

To install Laravel-Single-Table-Inheritance with Composer, add this line to your composer.json file in the `require field:

```json
"jacopo/laravel-single-table-inheritance": "1.0.0"
```
Congratulations! You succesfully installed the package.

##Usage

To use the package you need to extend `Jacopo\LaravelSingleTableInheritance\Models\Model` on your model class. Then you need to change the following attributes of your model:

- protected $table_type
- protected static $table_type_field
- protected $my_attributes

The field `$table_type` contains the string that will be saved in the `$table_type_field` column to distinguish the current model to the other models saved in the table. The field `$my_attributes` is an array that contains all the attributes that belongs to the current model(except the eloquent attributes created_at updated_at and deleted_at which are auto setted). For a better understanding on how to setup your model you should definatelly look in the <a href="https://github.com/intrip/laravel-single-table-inheritance/tree/master/examples">examples</a> folder.

Keep in mind that each model can fetch the data only from the rows that belongs to his type even if all data is stored and the same table! You can only set and get attributes related to the current model and his parents. If you want to fetch all the data inside the table you should create custom queries.

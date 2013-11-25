#Laravel-Single-Table-Inheritance

Laravel-Single-Table-Inheritance is a package that offer a model to handle single table inheritance with eloquent ORM. This pattern come from Martin Fowler(Patterns of Enterprise Application Architecture). For more information follow this link: <a href="http://martinfowler.com/eaaCatalog/index.html" target="_blank">Single Table Inheritance</a>.

- **Author**: Jacopo Beschi
- **Version**: 0.1.0

[![Build Status](https://travis-ci.org/intrip/laravel-single-table-inheritance.png)](https://travis-ci.org/intrip/laravel-single-table-inheritance)

## Requirements

- PHP >= 5.3.7
- Composer
- Laravel framework 4.0.*

##Installation with Composer

To install the package with Composer, add this line to your composer.json file in the `require field:

```json
"jacopo/laravel-single-table-inheritance": "dev-master"
```

Then open `app/config/app.php` and add the following line in the `providers` array:

```php
'providers' => array(
    'Jacopo\LaravelSingleTableInheritance\LaravelSingleTableInheritanceServiceProvider',
)
```

Congratulations! You succesfully installed the package.

##Usage

To use the package you need to extend `Jacopo\LaravelSingleTableInheritance\Models\Model` on your model class. Then you need to change the following attributes of your model:

- protected $table_type
- protected static $table_type_field
- protected $my_attributes

The field `$table_type` contains the string that will be saved in the `$table_type_field` column to distinguish the current model to the other models saved in the table. The field `$my_attributes` is an array that contains all the attributes that belongs to the current model(except the eloquent attributes created_at updated_at and deleted_at which are auto setted). To undestand better how to setup your model you can look in the <a href="https://github.com/intrip/laravel-single-table-inheritance/tree/master/examples">examples</a> folder.

Keep in mind that each model can fetch the data only from the lines that belongs to his type. Also you can only set and get attributes related to the current model(`$my_attributes`). If you want to fetch all the data inside the table you should create custom queries.

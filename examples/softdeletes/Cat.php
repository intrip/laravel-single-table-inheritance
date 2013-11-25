<?php

use Jacopo\LaravelSingleTableInheritance\Models\Model;

class Cat extends Model
{
	protected $table_type = 'Cat';
	protected static $table_type_field = 'type';
	protected $my_attributes = array("name","food","toy","bedding");
	protected $softDelete = true;
}
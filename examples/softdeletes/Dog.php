<?php

use Jacopo\LaravelSingleTableInheritance\Models\Model;

class Dog extends Model
{
	protected $table_type = 'Dog';
	protected static $table_type_field = 'type';
	protected $my_attributes = array("name","food","toy","collar");
	protected $softDelete = true;
}
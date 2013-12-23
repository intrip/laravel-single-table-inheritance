<?php

use Jacopo\LaravelSingleTableInheritance\Models\Model;

class Animal extends Model
{
	/**
	 * The name to save in the type field
	 * @var string
	 */
	protected $table_type = 'Animal';
	/**
	 * The name of the colum that holds table_type
	 * @var string
	 */
	protected static $table_type_field = 'type';
	/**
	 * The list of attributes that belongs to the class
	 * @var array
	 */
	protected $my_attributes = array("id", "name");
	/**
	 * Standard eloquent softDelete attribute
	 * @var boolean
	 */
	protected $softDelete = true;
}
<?php

class Dog extends Animal
{
	/**
	 * Here the table type is Dog
	 */
	protected $table_type = 'Dog';
	/**
	 * Dog has also "collar" attribute
	 * @var array
	 */
	protected $my_attributes = array("collar");
}
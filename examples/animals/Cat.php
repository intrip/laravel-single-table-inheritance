<?php

class Cat extends Animal
{
	/**
	 * the table name
	 * @var string
	 */
	protected $table = 'animal';
	/**
	 * Here the table type is Cat
	 * @var string
	 */
	protected $table_type = 'Cat';
	/**
	 * Cat has also the attribute: "food"
	 * @var array
	 */
	protected static $my_attributes = array("food");
}

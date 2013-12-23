<?php

class Cat extends Animal
{
	/**
	 * Here the table type is Cat
	 * @var string
	 */
	protected $table_type = 'Cat';
	/**
	 * Cat has also the attribute: "food"
	 * @var array
	 */
	protected $my_attributes = array("food");
}
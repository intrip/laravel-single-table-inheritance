<?php namespace Jacopo\LaravelSingleTableInheritance\Models;
/**
 *
 * Eloquent Model for Single table inheritance
 * this design comes from Martin fowler, this is just and implementation with laravel ORM
 *
 * Weakness of this implementation:
 * 	- As new objects are introduced you must change the table attributes
 * 	- There is not metadata to define which attribute belongs to wich subtype
 * 	- You cant set any good safe defaults: all to null o empty strings
 *
 * Works well when you have few subtypes and few specific attributes
 *
 * @author  Jacopo Beschi beschi.jacopo@gmail.com
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException;

class Model extends Eloquent
{
	/**
	 * The table type to save in $table_type_field
	 * @var String
	 */
	protected $table_type;
	/**
	 * The table type field
	 * @var String
	 */
	protected static $table_type_field = 'table_type';
	/**
	 * The attributes in the table related to the given type
	 * @var array
	 */
	protected $my_attributes = array();
	/**
	 * Eloquent base attributes
	 * @var array
	 */
	protected $eloquent_attributes = array();
	
	public function __construct( array $attributes = array() )
	{
		$this->prepareTableTypeField();
		$this->prepareEloquentAttributes();

		return parent::__construct( $attributes );
	}

	protected function prepareTableTypeField()
	{
		// if table type is not given use the class name
		$this->table_type = ( $this->table_type ) ? $this->table_type : get_class($this);
		// set table type field attribute
		$this->attributes[static::$table_type_field] = $this->table_type;
	}

	protected function prepareEloquentAttributes()
	{
		$deleted_at = static::DELETED_AT;
		$created_at = static::CREATED_AT;
		$updated_at = static::UPDATED_AT;

		$this->eloquent_attributes = array($deleted_at, $created_at, $updated_at);
	}

	/**
	 * Update newQuery to fetch data of the right type
	 *
	 * @param  boolean $getOnlyMyType
	 * @param  bool  $excludeDeleted
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function newQuery($excludeDeleted = true)
  	{
	    $builder = parent::newQuery($excludeDeleted);
	    $builder->where(static::$table_type_field,'=', $this->table_type);

	    return $builder;
  	}

  	/**
  	 * Protect table_type_field from being changed.
  	 * Protect also set of attributes keys not in $my_attributes and $eloquent_attributes array
  	 *
  	 * @param string $key
  	 * @param mixed $value
  	 * @throws Jacopo\LaravelTableInheritance\Exceptions\InvalidAttributeException
  	 */
  	public function setAttribute($key, $value)
  	{
  		if( ($key == static::$table_type_field) || ( ! $this->isInMyAttributes($key) ))
  			throw new InvalidAttributeException;

  		return parent::setAttribute($key, $value);
  	}

  	/**
  	 * Dont let get data attributes key not in $my_attributes and $eloquent_attributes array
  	 *
  	 * @param  String $key
  	 * @return mixed
  	 * @throws  Jacopo\LaravelTableInheritance\Exceptions\InvalidAttributeException
  	 */
  	public function getAttribute($key)
  	{
  		if( ! $this->isInMyAttributes($key) )
  			throw new InvalidAttributeException;

  		return parent::getAttribute($key);
  	}

  	/**
  	 * Check if the key is in $my_attributes or in $eloquent_attributes array fields
  	 *
  	 * @param  string $key
  	 * @return  boolean
  	 */
  	protected function isInMyAttributes($key)
  	{
  		$valid_attributes = array_merge($this->my_attributes, $this->eloquent_attributes);

        return in_array($key, $valid_attributes);
  	}

	protected function getTableType()
	{
		return $this->table_type;
	}
	
	protected function getTableTypeField()
	{
		return $this->table_type_field;
	}
}

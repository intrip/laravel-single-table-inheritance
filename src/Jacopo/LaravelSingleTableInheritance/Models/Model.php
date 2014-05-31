<?php namespace Jacopo\LaravelSingleTableInheritance\Models;
/**
 * Eloquent Model for Single table inheritance
 * this design comes from Martin fowler(Pattern of enterprise application architecture), this is just and implementation with Eloquent (laravel ORM)
 *
 * Strenghts of this implementation: 
 * 	- Is simple
 * 	- Moving column between hierarchy doesn't require database changes
 * 
 * Weakness of this implementation:
 * 	- There is no metadata to define which attribute belongs to which subtype: looking table directly is a bit weird
 * 	- The table will quiclky become a bottleneck if you create many hierarchies
 * 	- You waste some space with empty columns(depending on dbms compression of null values)
 *
 * Works well when you have few subtypes and few specific attributes and with active record implementation (Eloquent)
 *
 * @author  Jacopo Beschi jacopoo@jacopobeschi.com
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException;

abstract class Model extends Eloquent
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
	protected static $my_attributes = array();
	/**
	 * All the attributes associated to the given class, containing also parent attributes
	 * @var array
	 */
	protected static $all_attributes;
	/**
	 * Eloquent base attributes
	 * @var array
	 */
	protected static $eloquent_attributes = array();
	
	public function __construct( array $attributes = array() )
	{
		$this->prepareTableTypeField();
		$this->prepareEloquentAttributes();
		$this->prepareAllAttributes();
		
		return parent::__construct( $attributes );
	}

	/**
	 * Fill the array containing all the attributes that belongs to the class
	 * @return void
	 */
	protected function prepareAllAttributes()
	{
		static::$all_attributes = static::$my_attributes;
		$parent = get_class($this);
		while( ($parent = get_parent_class($parent) ) != 'Jacopo\LaravelSingleTableInheritance\Models\Model')
		{
			static::$all_attributes = array_merge(static::$all_attributes, $parent::$my_attributes);
		}
	}

	/**
	 * Check if the category is at top of the inheritance tree
	 * @return boolean 
	 */
	public function isRootCat()
	{
		return ( get_parent_class($this) == 'Jacopo\LaravelSingleTableInheritance\Models\Model' ) ;
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

		static::$eloquent_attributes = array($deleted_at, $created_at, $updated_at);
	}

    /**
     * Validate if a given key is a relation of the model (available as collection)
     *
     * @param $key
     * @return bool
     */
    protected function checkForRelationsAttributes($key)
    {
        try
        {
            $key = $this->$key();
        }
        catch(\BadMethodCallException $e)
        {
            return false;
        }

        return is_subclass_of($key, 'Illuminate\Database\Eloquent\Relations\Relation');
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
  	 * Protect also set of attributes keys not in $all_attributes and $eloquent_attributes array
  	 *
  	 * @param string $key
  	 * @param mixed $value
  	 * @throws Jacopo\LaravelTableInheritance\Exceptions\InvalidAttributeException
  	 */
  	public function setAttribute($key, $value)
  	{
  		if( ($key == static::$table_type_field) || ( ! $this->isInAllAttributes($key) ))
  			throw new InvalidAttributeException;

  		return parent::setAttribute($key, $value);
  	}

  	/**
  	 * Dont let get data attributes key not in $all_attributes and $eloquent_attributes array
  	 *
  	 * @param  String $key
  	 * @return mixed
  	 * @throws  \Jacopo\LaravelTableInheritance\Exceptions\InvalidAttributeException
  	 */
  	public function getAttribute($key)
  	{
  		if( ! $this->isInAllAttributes($key) )
  			throw new InvalidAttributeException;

  		return parent::getAttribute($key);
  	}

  	/**
  	 * Check if the key is in $all_attributes or in $eloquent_attributes array fields
  	 *
  	 * @param  string $key
  	 * @return  boolean
  	 */
  	protected function isInAllAttributes($key)
  	{
  		$valid_attributes = array_merge($this->getAllAttributes(), $this->getEloquentAttributes());
        return in_array($key, $valid_attributes) || $this->checkForRelationsAttributes($key);
  	}

    /**
     * Lazy loading all_attributes
     * @return array
     */
    protected function getAllAttributes()
    {
        if( empty(static::$all_attributes)) $this->prepareAllAttributes();

        return static::$all_attributes;
    }

    /**
     * Lazy loading eloquent_attributes
     * @return array
     */
    protected function getEloquentAttributes()
    {
        if( empty(static::$eloquent_attributes)) $this->prepareEloquentAttributes();

        return static::$eloquent_attributes;
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

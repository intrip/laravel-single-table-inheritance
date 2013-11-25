<?php

/**
 * InheritanceSingleTableTest
 *
 */
use Mockery as m;
use Jacopo\LaravelSingleTableInheritance\Models\Model;
use Carbon\Carbon;

class TestModel extends \PHPUnit_Framework_TestCase
{
	protected $model;

	public function setUp()
	{
		$this->model = new SingleModelStub();
	}

	public function tearDown()
	{
		m::close();
	}

	public function testCanCreateModel()
	{
		$this->assertInstanceOf('SingleModelStub', $this->model);
	}

	public function testNewQueryNoDeleteYesMyAttributes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(false, true);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $builder);
		$wheres = $builder->getQuery()->wheres;
		$expected_where = array(
				"type"=> "Basic",
				"column" => "type",
				"operator" => "=",
				"value" => "single_model",
				"boolean" => "and"
			);
		
		$this->assertEquals($expected_where,$wheres[0]);
	}

	public function testNewQueryYesDeleteYesMyAttributes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(true, true);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $builder);
		$wheres = $builder->getQuery()->wheres;
		$expected_where = array(
				"type"=> "Basic",
				"column" => "type",
				"operator" => "=",
				"value" => "single_model",
				"boolean" => "and"
			);
		$this->assertEquals($expected_where,$wheres[1]);
		$expected_where = array(
				"type"=> "Null",
				"column" => "stub.deleted_at",
				"boolean" => "and"
			);
		
		$this->assertEquals($expected_where,$wheres[0]);
	}

	public function testNewQueryYesDeleteNoMyAttributes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(true, false);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $builder);
		$wheres = $builder->getQuery()->wheres;
		$expected_where = array(
				"type"=> "Null",
				"column" => "stub.deleted_at",
				"boolean" => "and"
			);
		
		$this->assertEquals($expected_where,$wheres[0]);
	}

	public function testNewQueryNoDeleteNoMyAttributes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(false, false);
		$this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $builder);
		$wheres = $builder->getQuery()->wheres;
	
		$this->assertNull($wheres[0]);
	}

	/**
	 * @expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
	 */
	public function testSetAttributeThrowsInvalidAttributeException()
	{
		$this->model->not_working = "error";
	}

	/**
	 * @expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
	 */
	public function testGetAttributeThrowsInvalidAttributeException()
	{
		$this->model->not_working;
	}

	public function testGetSetAttributeWorks()
	{
		$this->model->working = "works";
		$this->assertEquals("works", $this->model->working);

		$model = new SingleModelStub(array(
				"working"=>"works",
			));
		$this->assertEquals("works", $model->working);
	}

	public function testGetAttributesWorksWithEloquentAttributes()
	{
		$this->model->created_at = null;
		$this->assertEquals(null, $this->model->created_at);
	}

}

class SingleModelStub extends  Model
{
	protected $table = 'stub';

	protected $table_type = "single_model";

	protected static $table_type_field = "type";

	protected $softDelete = true;

	protected static $unguarded = true;

	protected $my_attributes = array("working");

}

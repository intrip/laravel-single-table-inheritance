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
		$this->model = new SingleModelRootStub();
	}

	public function tearDown()
	{
		m::close();
	}

	public function testNewQueryNoSoftDeletes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelRootStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(false);
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

	public function testNewQuerySoftDeletes()
	{
		$conn = m::mock('Illuminate\Database\Connection');
		$grammar = m::mock('Illuminate\Database\Query\Grammars\Grammar');
		$processor = m::mock('Illuminate\Database\Query\Processors\Processor');
		$conn->shouldReceive('getQueryGrammar')->once()->andReturn($grammar);
		$conn->shouldReceive('getPostProcessor')->once()->andReturn($processor);
		SingleModelRootStub::setConnectionResolver($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
		$resolver->shouldReceive('connection')->andReturn($conn);
		$builder = $this->model->newQuery(true);
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

		$model = new SingleModelRootStub(array(
				"working"=>"works",
			));
		$this->assertEquals("works", $model->working);

		$model_child = new SingleModelChildStub(array(
				"working"=>"works",
				"working_child" => "works child",
			));
		$this->assertEquals("works", $model_child->working);
		$this->assertEquals("works child", $model_child->working_child);
	}

	public function testGetAttributesWorksWithEloquentAttributes()
	{
		$this->model->created_at = null;
		$this->assertEquals(null, $this->model->created_at);

		$model_child = new SingleModelChildStub;
		$model_child->created_at = null;
		$this->assertNull($model_child->created_at);
	}

	public function testIsRootCatRoot()
	{
		$is_root = $this->model->isRootCat();
		$this->assertTrue($is_root);
	}

	public function testIsRootCatNotRoot()
	{
		$model = new SingleModelChildStub;
		$is_root = $model->isRootCat();
		$this->assertFalse($is_root);
	}

    /**
     * @test
     **/
    public function itCanSetAndGetRelationsAttributes()
    {
        $this->model->valid_relation;
    }
    
    /**
     * @test
     * @expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
     **/
    public function itCheckForWrongRelationsAttributes()
    {
        $this->model->fake_relation;
    }
}

class SingleModelRootStub extends  Model
{
	protected $table = 'stub';

	protected $table_type = "single_model";

	protected static $table_type_field = "type";

	protected $softDelete = true;

	protected static $unguarded = true;

	protected static $my_attributes = array("working");

    public function valid_relation()
    {
        return $this->mockRelationGetResults();
    }

    /**
     * @return m\MockInterface
     */
    private function mockRelationGetResults()
    {
        return m::mock('Illuminate\Database\Eloquent\Relations\HasMany')->shouldReceive('getResults')->getMock();
    }

    public function fake_relation()
    {
        return '';
    }

}

class SingleModelChildStub extends SingleModelRootStub
{
	protected $table = 'stub';

	protected $table_type = "single_model_child";

	protected static $table_type_field = "type";

	protected $softDelete = true;

	protected static $unguarded = true;

	protected static $my_attributes = array("working_child");

}
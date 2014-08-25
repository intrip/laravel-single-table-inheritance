<?php namespace Jacopo\LaravelSingleTableInheritance\Tests;

/**
 * InheritanceSingleTableTest

 */
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelChildStub;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelRootStub;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelRootStubSoftDelete;
use Mockery as m;
use Carbon\Carbon;

class TestModel extends TestCase {
  protected $model_root;
  protected $model_root_soft_delete;
  protected $model_child;

  public function setUp() {
    parent::setUp();
    $this->model_root = new SingleModelRootStub();
    $this->model_root_soft_delete = new SingleModelRootStubSoftDelete();
    $this->model_child = new SingleModelChildStub();
  }

  public function tearDown() {
    m::close();
  }

  /**
   * @test
   */
  public function delete_WithSoftDelete() {
    $model_created = $this->model_root_soft_delete->create(array ("working" => "works"));

    $model_created->delete();

    $this->assertEquals($this->model_root_soft_delete->withTrashed()->first()->id, $model_created->id);
  }

  /**
   * @test
   */
  public function delete() {
    $model_created = $this->model_root->create(array ("working" => "works"));

    $model_created->delete();

    $this->assertNull($this->model_root->first());
  }

  /**
   * @expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   */
  public function testSetAttributeThrowsInvalidAttributeException() {
    $this->model_root->not_working = "error";
  }

  /**
   * @expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   */
  public function testGetAttributeThrowsInvalidAttributeException() {
    $this->model_root->not_working;
  }

  public function testGetSetAttributeWorks() {
    //@todo make this test, finish the rest, clean the production code in a better way
    // setup the new doc, retag repush e fix github issue
    //    $working_value = "works";
    //    $working_child_value = "works child";
    //
    //    $this->assertEquals($working_value, $this->model->working);
    //
    //    $model_child = $this->model_child->create(array (
    //                                                          "working"       => $working_value,
    //                                                          "working_child" => $working_child_value,
    //                                                  ));
    //    $this->assertEquals($working_value, $model_child->working);
    //    $this->assertEquals($working_child_value, $model_child->working_child);
  }

  public function testGetAttributesWorksWithEloquentAttributes() {
    //		$this->model->created_at = null;
    //		$this->assertEquals(null, $this->model->created_at);
    //
    //		$model_child = new SingleModelChildStub;
    //		$model_child->created_at = null;
    //		$this->assertNull($model_child->created_at);
  }

  public function testIsRootCatRoot() {
    //		$is_root = $this->model->isRootCat();
    //		$this->assertTrue($is_root);
  }

  public function testIsRootCatNotRoot() {
    //		$model = new SingleModelChildStub;
    //		$is_root = $model->isRootCat();
    //		$this->assertFalse($is_root);
  }

  /**
   * @test
   **/
  public function itCanSetAndGetRelationsAttributes() {
//    $this->model_root->valid_relation;
  }

  /**
   * @test
   * @ expectedException Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   **/
  public function itCheckForWrongRelationsAttributes() {
    //        $this->model->fake_relation;
  }
}

<?php namespace Jacopo\LaravelSingleTableInheritance\Tests;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelChildStub;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelRootStub;
use Jacopo\LaravelSingleTableInheritance\Tests\Stubs\SingleModelRootStubSoftDelete;
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
   * @test
   * @expectedException \Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   */
  public function setAttributeThrowsInvalidAttributeException() {
    $this->model_root->not_working = "error";
  }

  /**
   * @test
   * @expectedException \Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   */
  public function getAttributeThrowsInvalidAttributeException() {
    $this->model_root->not_working;
  }

  /**
   * @test
   */
  public function getSetAttributeWorks() {
    $working_value = "works";
    $working_child_value = "works child";

    $model_root = $this->model_child->create(array (
                                                     "working" => $working_value,
                                             ));

    $this->assertEquals($working_value, $model_root->working);

    $model_child = $this->model_child->create(array (
                                                      "working"       => $working_value,
                                                      "working_child" => $working_child_value,
                                              ));
    $this->assertEquals($working_value, $model_child->working);
    $this->assertEquals($working_child_value, $model_child->working_child);
  }

  /**
   * @test
   */
  public function getAttributesWorksWithEloquentAttributes() {
    $this->model_root->created_at = null;
    $this->assertEquals(null, $this->model_root->created_at);
  }

  /**
   * @test
   */
  public function canCheckIfIsRootCatRoot() {
    $is_root = $this->model_root->isRootCat();
    $this->assertTrue($is_root);

    $is_root = $this->model_child->isRootCat();
    $this->assertFalse($is_root);
  }

  /**
   * @test
   **/
  public function itCanSetAndGetRelationsAttributes() {
    $this->model_root->valid_relation;
  }

  /**
   * @test
   * @expectedException \Jacopo\LaravelSingleTableInheritance\Exceptions\InvalidAttributeException
   **/
  public function itCheckForWrongRelationsAttributes() {
    $this->model_root->fake_relation;
  }
}

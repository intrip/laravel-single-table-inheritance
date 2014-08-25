<?php  namespace Jacopo\LaravelSingleTableInheritance\Tests\Stubs;

use Jacopo\LaravelSingleTableInheritance\Models\Model;

class SingleModelRootStub extends Model{

  protected $table = 'stub';

  protected $table_type = "single_model";

  protected static $table_type_field = "type";

  protected static $unguarded = true;

  protected static $my_attributes = array ("working","id");

  public function valid_relation() {
    return $this->mockRelationGetResults();
  }

  /**
   * @return m\MockInterface
   */
  private function mockRelationGetResults() {
    return m::mock('Illuminate\Database\Eloquent\Relations\HasMany')->shouldReceive('getResults')->getMock();
  }

  public function fake_relation() {
    return '';
  }
}
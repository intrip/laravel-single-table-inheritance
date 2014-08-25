<?php namespace Jacopo\LaravelSingleTableInheritance\Tests\Stubs;

class SingleModelChildStub extends SingleModelRootStub
{
  protected $table = 'stub';

  protected $table_type = "single_model_child";

  protected static $table_type_field = "type";

  protected $softDelete = true;

  protected static $unguarded = true;

  protected static $my_attributes = array("working_child");
}

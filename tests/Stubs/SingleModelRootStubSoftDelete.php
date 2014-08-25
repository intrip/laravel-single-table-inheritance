<?php  namespace Jacopo\LaravelSingleTableInheritance\Tests\Stubs;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class SingleModelRootStubSoftDelete extends SingleModelRootStub {
  use SoftDeletingTrait;
} 
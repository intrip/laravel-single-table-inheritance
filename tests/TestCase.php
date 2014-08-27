<?php namespace Jacopo\LaravelSingleTableInheritance\Tests;

use \Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Test TestCase
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class TestCase extends OrchestraTestCase {

  /**
   * Setup the test environment.
   */
  public function setUp() {
    parent::setUp();

    // create an artisan object for calling migrations
    $artisan = $this->app->make('artisan');

    // migrations only for testing purpose
    $artisan->call('migrate', [
            '--database' => 'testbench',
            '--path'     => '../src/migrations/tests',
    ]);
  }

  /**
   * Define environment setup.
   *
   * @param  Illuminate\Foundation\Application $app
   * @return void
   */
  protected function getEnvironmentSetUp($app) {
    // reset base path to point to our package's src directory
    $app['path.base'] = __DIR__ . '/../src';

    $app['config']->set('database.default', 'testbench');
    $app['config']->set('database.connections.testbench', array (
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
    ));
  }

  /**
   * Get package providers.  At a minimum this is the package being tested, but also
   * would include packages upon which our package depends, e.g. Cartalyst/Sentry
   * In a normal app environment these would be added to the 'providers' array in
   * the config/app.php file.
   *
   * @return array
   */
  protected function getPackageProviders() {
    return array ();
  }

  /**
   * Get package aliases.  In a normal app environment these would be added to
   * the 'aliases' array in the config/app.php file.  If your package exposes an
   * aliased facade, you should add the alias here, along with aliases for
   * facades upon which your package depends, e.g. Cartalyst/Sentry
   *
   * @return array
   */
  protected function getPackageAliases() {
    return array ();
  }

  /**
   * @test
   **/
  public function dummy() {
  }
}
 
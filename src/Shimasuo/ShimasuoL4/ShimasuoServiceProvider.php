<?php namespace Shimasuo\ShimasuoL4;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class ShimasuoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
	
	public function boot(){
		$this->package('Shimasuo/ShimasuoL4');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['shimasuo'] = $this->app->share(function($app)
		{			
			return new \Shimasuo\Shimasuo(
					Config::get('ShimasuoL4::shimasuo.config'),
					function($data){
						if(!is_array($data)){
							$data = [$data, []];
						}
						if(count($data) == 1){
							$data[]=[];
						}

						if($data[0] instanceof \Closure){
							$data = [
								new \Illuminate\Support\SerializableClosure($data[0]),
								'__invoke',
								$data[1]
							];
						}

						$serialize = Config::get('ShimasuoL4::shimasuo.serialize');
						if($serialize == null){
							return serialize($data);
						}
						return $serialize($data);
					},
					Config::get('ShimasuoL4::shimasuo.unserialize')
			);
		});

		$this->app['shimasuo.run'] = $this->app->share(function($app)
		{
			return new ShimasuoCommand($app['shimasuo']);
		});

		$this->commands('shimasuo.run');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['shimasuo', 'shimasuo.run'];
	}

}

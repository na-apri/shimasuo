<?php namespace Shimasuo\ShimasuoL4;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;

class ShimasuoCommand extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'shimasuo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'shimasuo run';

	/**
	 *
	 * @var \Shimasuo\Shimasuo
	 */
	protected $shimasuo;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(\Shimasuo\Shimasuo $shimasuo)
	{
		$this->shimasuo = $shimasuo;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		switch ($this->argument('mode')){

			case 'stop':
				$this->shimasuo->end();				
				break;
			
			case 'exec':
				$this->shimasuo->run(
					null,
					Config::get('ShimasuoL4::shimasuo.minChildProcess'),
					Config::get('ShimasuoL4::shimasuo.maxChildProcess'),
					Config::get('ShimasuoL4::shimasuo.threshold')
				);				
				break;

			case 'start':
			default :
				chdir(App::make('path.base'));
				exec("nohup php artisan shimasuo exec > /dev/null &");
				break;
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			['mode', InputArgument::REQUIRED, 'mode {start|stop}'],
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}
	
}


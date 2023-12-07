<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ActionButtonApiController;

class UpdateButtons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:buttons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */ 
    public function handle()     
    { 
       // ini_set('memory_limit','-1');     
		// app()->call('App\Http\Controllers\TestController@index');
		//app()->call('App\Http\Controllers\TagController@index');
		//app()->call('App\Http\Controllers\ListController@getList');   
		app()->call('App\Http\Controllers\ActionButtonApiController@UpdateButtonSummary'); 
	}
}

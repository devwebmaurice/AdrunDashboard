<?php

namespace App\Console\Commands\Adrun;

use Illuminate\Console\Command;

use App\Models\Adrun\AdrunWebsiteModel;
use App\Models\Adtech\AdtechWebsiteModel;


class Editeur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:editeurSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Editeur';

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
     * @return mixed
     */
    public function handle()
    {

        echo "---SYNC START---";
        flush();
       
        $ADRUN_editeurs     = AdrunWebsiteModel::getInstance()->getEditeurList();
        $ADTECH_editeurs    = AdtechWebsiteModel::getInstance()->getADTECHWebsiteIDList()->return;
        
        
        
        
        die('editeur');
    }
}

<?php

namespace App\Console\Commands\Adrun;

use Illuminate\Console\Command;
use App\Models\Adrun\AdrunCampaignModel;
use Carbon\Carbon;
use DateTime;
use App\Models\Adtech\AdtechReportModel;
use App\Models\SingleBilanModel;
use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunReportModel;

class CampaignReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Reports';

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
        echo 'test';
    }
}

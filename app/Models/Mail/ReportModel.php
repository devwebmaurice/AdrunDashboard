<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Carbon\Carbon;

class ReportModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        $this->emails     = \Config::get('adrun.emails');
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function sendDailyEndOfCampaignReport($datas = NULL)
    {
        
        if(!empty($datas)):
            
            foreach ($this->emails as $nom => $email):
            
                $datacc = array('datas' => $datas,'name' => $nom );
        
                $this->nom   = $nom;
                $this->email = $email;
        
                Mail::send('email.report.daily-end-campaign', $datacc, function($message) {

                    $date = Carbon::now()->subDays(2)->endOfDay(); 

                    $message->to($this->email, $this->nom  )->subject('BILAN DE CAMPAGNE DU [ '.$date->format('d/m/Y').' ]');
                    $message->from('dashboard.adrun@gmail.com','ADRUN INTELLIGENCE DASHBOARD ');


                });
            
            endforeach;
            
         endif;   
        
    }
    
    public function sendReminderDashboard($datas = NULL)
    {
        
        $this->nom   = $datas['name'];
        $this->email = $datas['surname'];
        
        $datacc = array('datas' => 'test','name' => $this->nom );
        
        Mail::send('email.report.dashboard-sync', $datacc, function($message) {

            $date = Carbon::now(); 

            $message->to($this->email, $this->nom  )->subject('DASHBOARD SYNC [ '.$date->format('Y-m-d H:i:s').' ]');
            $message->from('dashboard.adrun@gmail.com','DASHBOARD ADRUN');


        });
        
        
        
    }
    
    
    
}

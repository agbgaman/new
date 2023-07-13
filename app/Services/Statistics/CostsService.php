<?php

namespace App\Services\Statistics;

use DB;

class CostsService 
{
    private $year;
    private $month;

    public function __construct(int $year = null, int $month = null) 
    {
        $this->year = $year;
        $this->month = $month;
    }

    # Costs for Voiceover Studio

    public function getCostPerText($id)
    {   
        $cost = DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.id', $id)
                    ->select(DB::raw('(voiceover_results.characters * vendors.cost) as data'))              
                    ->get();  

        return $cost;
    }


    public function getVoiceoverSpending()
    {
        $spending = DB::table('voiceover_results')
                ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                ->whereYear('voiceover_results.created_at', $this->year)
                ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'), DB::raw("MONTH(voiceover_results.created_at) month"))
                ->groupBy('month')
                ->orderBy('month')
                ->get(); 
        
        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($spending as $row) {	
            $month = $row->month;
            $data[$month] = number_format((float)$row->data, 2, '.', '');            	   
        }
        
        return $data;
    }


    public function getTotalCostForTextCurrentYear()
    {   
        $data = DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->whereYear('voiceover_results.created_at', $this->year)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();  

        $cost = get_object_vars($data[0]);
             
        return $cost['data'];

       
    }


    public function getTotalCostForTextCurrentMonth()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->whereMonth('voiceover_results.created_at', $this->month)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalCostForTextPastMonth()
    {   
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->whereMonth('voiceover_results.created_at', $pastMonth)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);       

        return $cost['data'];
    }


    public function getTotalAWSCostCurrentMonth()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'aws')
                    ->whereMonth('voiceover_results.created_at', $this->month)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalAWSCostCurrentYear()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'aws')
                    ->whereYear('voiceover_results.created_at', $this->year)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalAzureCostCurrentMonth()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'azure')
                    ->whereMonth('voiceover_results.created_at', $this->month)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalAzureCostCurrentYear()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'azure')
                    ->whereYear('voiceover_results.created_at', $this->year)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalGCPCostCurrentMonth()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'gcp')
                    ->whereMonth('voiceover_results.created_at', $this->month)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalGCPCostCurrentYear()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'gcp')
                    ->whereYear('voiceover_results.created_at', $this->year)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalIBMCostCurrentMonth()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'ibm')
                    ->whereMonth('voiceover_results.created_at', $this->month)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalIBMCostCurrentYear()
    {   
        $data= DB::table('voiceover_results')
                    ->join('vendors', 'voiceover_results.vendor_id', '=', 'vendors.vendor_id')
                    ->where('voiceover_results.vendor', 'ibm')
                    ->whereYear('voiceover_results.created_at', $this->year)
                    ->select(DB::raw('sum(voiceover_results.characters * vendors.cost) as data'))
                    ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }

    # Costs for Transcribe Studio

    public function getCostPerSecond($id)
    {   
        $cost = DB::table('transcribe_results')
                    ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                    ->where('transcribe_results.id', $id)
                    ->select(DB::raw('(transcribe_results.length * vendors.cost) as data'))              
                    ->get();  

        return $cost;
    }


    public function getTranscribeSpending()
    {
        $spending = DB::table('transcribe_results')
                ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                ->whereYear('transcribe_results.created_at', $this->year)
                ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'), DB::raw("MONTH(transcribe_results.created_at) month"))
                ->groupBy('month')
                ->orderBy('month')
                ->get(); 
        
        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($spending as $row) {	
            $month = $row->month;
            $data[$month] = number_format((float)$row->data, 2);            	   
        }
        
        return $data;
    }


    public function getTotalCostForSecondsCurrentYear()
    {   
        $data = DB::table('transcribe_results')
                    ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                    ->whereYear('transcribe_results.created_at', $this->year)
                    ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                    ->get();  

        $cost = get_object_vars($data[0]);
             
        return $cost['data'];

       
    }


    public function getTotalCostForSecondsCurrentMonth()
    {   
        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->whereMonth('transcribe_results.created_at', $this->month)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalCostForSecondsPastMonth()
    {   
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->whereMonth('transcribe_results.created_at', $pastMonth)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);       

        return $cost['data'];
    }


    public function getTotalAWSTranscribeCostCurrentMonth()
    {   
        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->where('transcribe_results.vendor', 'aws')
                        ->whereMonth('transcribe_results.created_at', $this->month)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalAWSTranscribeCostCurrentYear()
    {   
        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->where('transcribe_results.vendor', 'aws')
                        ->whereYear('transcribe_results.created_at', $this->year)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalGCPTranscribeCostCurrentMonth()
    {   
        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->where('transcribe_results.vendor', 'gcp')
                        ->whereMonth('transcribe_results.created_at', $this->month)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalGCPTranscribeCostCurrentYear()
    {   
        $data= DB::table('transcribe_results')
                        ->join('vendors', 'transcribe_results.vendor', '=', 'vendors.vendor_id')
                        ->where('transcribe_results.vendor', 'gcp')
                        ->whereYear('transcribe_results.created_at', $this->year)
                        ->select(DB::raw('sum(transcribe_results.length * vendors.cost) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }
}
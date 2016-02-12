<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Crew\Unsplash\Photo as uPhoto;
use Crew\Unsplash\HttpClient;
use Carbon\Carbon;
use App\Photo;

class PhotoPromptController extends Controller
{
    protected function generate($year, $month) {
        HttpClient::init([
   		    'applicationId' => env('IMG_ID'),
    		'secret'        => env('IMG_SECRET'),
	    ]);

        $date = Carbon::now();
        $date->year = $year;
        $date->month = $month;
        $date->startOfMonth();
        
        $end = $date->copy()->endOfMonth();
        
        while($date->lte($end)) {
            $random = uPhoto::random();
            $link = $random->links['html'];
            $image = $random->urls['thumb'];
            
            Photo::create([
                'date' => $date->toDateString(),
                'link' => $link,
                'image' => $image,
            ]);
            $date->addDays(1);
        }
        
        return redirect('view/'.$year.'/'.$month);
    }
    
    public function getMonth($year = null, $month = null)
    {
        if($year == null) {
            $year = date('Y');
        }
        
        if($month == null) {
            $month = date('m');
        }
        
        $title = date('F Y',mktime(0,0,0,$month,1, $year));
        
        $date = Carbon::now();
        $date->year = $year;
        $date->month = $month;
        $date->startOfMonth();
        
        $end = $date->copy()->endOfMonth();
        $next = $date->copy()->addMonths(1);
        $prev = $date->copy()->subMonths(1);
        
        
        $photos = Photo::whereBetween('date',[$date->toDateString(), $end->toDateString()])
            ->get();
            
        if($photos->isEmpty()) {
            $this->generate($year, $month);
        }
            
        $start = $date->dayOfWeek;
        $calendar = [];
        $j = 0;
        for($i = 0; $i < $start; $i++) {
            $calendar[$j][$i] = null;
        }
        

        foreach($photos as $photo) {
            $temp = new Carbon($photo->date);

            if($temp->dayOfWeek == 0) {
                if($j != 0) {
                    $j++;
                }
            }
            
            $calendar[$j][$temp->dayOfWeek] = $photo;
                
            if($temp->dayOfWeek == 6) {
                $j++;
            }
        }
        
        \View::share('next', $next);
        \View::share('prev',$prev);
        \View::share('title',$title);
        \View::share('photos',$calendar);
        return \View::make('photos.month');
    }
}

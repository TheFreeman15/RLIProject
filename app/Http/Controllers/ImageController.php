<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Symfony\Component\Console\Output\ConsoleOutput;
use Intervention\Image\Image as Img;

class ImageController extends Controller
{
    private $output;
    private $olid;
    public function __construct() {
        $this->output = new ConsoleOutput();

        $this->olid = new TesseractOCR();
        $this->olid->lang('eng')
                   ->psm(7);
    }

    protected function thicken(Img $img) {
        $black_arr = array();
        for($i = 0; $i < $img->width(); $i++) {
            for($j = 0; $j < $img->height(); $j++) {
                $colorA = $img->pickColor($i, $j, 'hex');
                if($colorA == '#000000') {
                    array_push($black_arr, array($i, $j));
                }
            }
        }

        $s = count($black_arr);
        for($k = 0; $k < $s; $k++) {
            $img->pixel('#000000', $black_arr[$k][0]-1, $black_arr[$k][1]-1);
            $img->pixel('#000000', $black_arr[$k][0], $black_arr[$k][1]-1);
            $img->pixel('#000000', $black_arr[$k][0]+1, $black_arr[$k][1]-1);
            $img->pixel('#000000', $black_arr[$k][0]-1, $black_arr[$k][1]);
            $img->pixel('#000000', $black_arr[$k][0]+1, $black_arr[$k][1]);
            $img->pixel('#000000', $black_arr[$k][0]-1, $black_arr[$k][1]+1);
            $img->pixel('#000000', $black_arr[$k][0], $black_arr[$k][1]+1);
            $img->pixel('#000000', $black_arr[$k][0]+1, $black_arr[$k][1]+1);
        }
        return 0;
    }
    protected function two_tone(Img $img) {
        $thres = 86;
        $white = 0;
        $black = 0;
        for($i = 0; $i < $img->width(); $i++) {
            for($j = 0; $j < $img->height(); $j++) {
                $colorA = $img->pickColor($i, $j);
                if($colorA[0] > $thres && $colorA[1] > $thres && $colorA[2] > $thres) {
                //if($color != 4287993237) {
                    $img->pixel('#000000', $i, $j);
                    $black++;
                }
                else {
                    $img->pixel('#ffffff', $i, $j);
                    $white++;
                }
            }
        }
    
        if($black > $white)
            $img->invert();

        //$this->thicken($img);
        return $img;
    }

    protected function race_prep(String $src) {    
        //Name
        $img = Image::make($src)
                    ->resize(1920, 1080)
                    ->crop(1000, 90, 90, 215);

        $this->two_tone($img);
        $img->save('img/race_results/Name.png');
    
        //Standings
        $img = Image::make($src)
             ->resize(1920, 1080)
             ->crop(1290, 570, 530, 360)
             ->save('img/race_results/Standings.png');
    
        $img = Image::make('img/race_results/Standings.png')
             ->crop(150, 33, 150, 7)
             ->save('img/race_results/SD.png');
    
        //$img->crop(1, 10, 5, 9);
        //$img->save('img/race_results/SDI.png');
        //    $this->two_tone($img);
        //  $img->save('img/race_results/SDI.png');
    
    
        $row_width = 40.2142;
        //Position
        for($i = 0; $i < 14; $i++) {
            $pos = Image::make('img/race_results/Standings.png');
            $pos->crop(50, 33, 10, 7 + (int)($i * $row_width));
            $this->two_tone($pos);
            $pos->save('img/race_results/pos_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/pos_' . ($i + 1) . '.png<info>');
        }
    
        //Driver
        for($i = 0; $i < 14; $i++) {
            $driver = Image::make('img/race_results/Standings.png');
            $driver->crop(150, 33, 150, 7 + (int)($i * $row_width));
            $this->two_tone($driver);
            $driver->save('img/race_results/driver_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/driver_' . ($i + 1) . '.png<info>');
        }
    
        //Team
        for($i = 0; $i < 14; $i++) {
            $team = Image::make('img/race_results/Standings.png');
            $team->crop(240, 33, 555, 7 + (int)($i * $row_width));
            $this->two_tone($team);
            $team->save('img/race_results/team_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/team_' . ($i + 1) . '.png<info>');
        }
    
        //Grid
        for($i = 0; $i < 14; $i++) {
            $grid = Image::make('img/race_results/Standings.png');
            $grid->crop(50, 33, 821, 7 + (int)($i * $row_width));
            $this->two_tone($grid);
            $grid->save('img/race_results/grid_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/grid_' . ($i + 1) . '.png<info>');
        }
    
        //Stops
        for($i = 0; $i < 14; $i++) {
            $stops = Image::make('img/race_results/Standings.png');
            $stops->crop(50, 33, 910, 7 + (int)($i * $row_width));
            $this->two_tone($stops);
            $stops->save('img/race_results/stops_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/stops_' . ($i + 1) . '.png<info>');
        }
    
        //Fastest Lap
        for($i = 0; $i < 14; $i++) {
            $best = Image::make('img/race_results/Standings.png');
            $best->crop(150, 33, 1000, 7 + (int)($i * $row_width));
            $this->two_tone($best);
            $best->save('img/race_results/best_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/best_' . ($i + 1) . '.png<info>');
        }
    
        //Finishing Time
        for($i = 0; $i < 14; $i++) {
            $time = Image::make('img/race_results/Standings.png');
            $time->crop(140, 33, 1140, 7 + (int)($i * $row_width));
            $this->two_tone($time);
            $time->save('img/race_results/time_' . ($i + 1) . '.png');
            $this->output->writeln('<info>img/race_results/time_' . ($i + 1) . '.png<info>');
        }
    
        return 0;
    }   
    
    public function raceprep() {
        //$this->race_prep('img/RRMexico.png');
    
        /*$this->olid->image('img/race_results/Name.png');
        $tr = $this->olid->run();
    
        $this->output->writeln("<info>" . $tr . "</info>");*/
    

        $results = array();
        for($i = 12; $i < 14; $i++) {
            $row = array();
            $this->output->writeln("<info>Driver " . ($i + 1) . " : " . "</info>");
    
            $this->olid->image('img/race_results/pos_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["pos"] = (int)$tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/driver_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["driver"] = $tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/team_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["team"] = $tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/grid_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["grid"] = (int)$tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/stops_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["stops"] = (int)$tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/best_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["best"] = $tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
    
            $this->olid->image('img/race_results/time_' . ($i + 1) . '.png');
            $tr = $this->olid->run();
            $row["time"] = $tr;
    
            $this->output->writeln("<info>" . $tr . "</info>");
            array_push($results, $row);
        }
    
        $this->olid->image('img/race_results/Standings.png');
        return response()->json($results); //$this->olid->response('png');
    }

    public function race_name() {
        //$this->race_prep('img/RRMexico.png');
    
        /*$this->olid->image('img/race_results/Name.png');
        $tr = $this->olid->run();

        //Replace Series of '\n' with a single '$'
        $tri = preg_replace('/\n+/', '$', $tr);
        $arr = explode("$", $tri);*/

        $arr['name'] = "12";
        $arr['nn'] = "23";
        $arrr = array();
        array_push($arrr, $arr);
        return response()->json($arrr);
    }
}

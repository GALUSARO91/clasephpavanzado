<?php

namespace App\Controllers;

use App\Models\{Job, Project};

class IndexController extends BaseController {
   
    public function indexAction() {

       /* $clousure = function(){
            echo 'Hola mundo';
        };
        $clousure();
        die;*/

        $jobs = Job::all();

        /*$jobs = array_filter($jobs->toArray(),function($job){
            return $job['months']>= 5;
        });*/

       /* $project1 = new Project('Project 1', 'Description 1');
        $projects = [
            $project1
        ];*/

        $name = 'Luis Rodriguez';
        $limitMonths = 5;

        $trabajo_filtro = function(array $job) use($limitMonths){
            return $job['months']>= $limitMonths;
        };

        /*$filterClosure = function (array $job) use ($limitMonths) {
            return $job['months'] > $limitMonths;
        };*/

        //$jobs = array_filter($jobs->toArray(), $trabajo_filtro);

        return $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs
        ]);

        
    }
}
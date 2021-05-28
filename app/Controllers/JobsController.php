<?php
namespace App\Controllers;

use App\Models\Job;
use App\Services\JobService;
use Zend\Diactoros\ServerRequest;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;

class JobsController extends BaseController {

    private $jobService;

    public function __construct(JobService $jobService)
    {
        parent::__construct();
        $this->jobService = $jobService;
    }


    public function indexAction(){
        $jobs = Job::withTrashed()->get();
        return $this->renderHTML('jobs/index.twig',compact('jobs'));
    }

    public function deleteAction(ServerRequest $request){
       
      /*   $params = $request->getQueryParams();
       
        $job = Job::where('id',intval($params['id']));
        $job->delete();
        return new RedirectResponse('/personal/jobs');
 */
    

            $this->jobService->deleteJob(intval($request->getAttribute('id')));
            return new RedirectResponse('/personal/jobs');
        
    }


    public function getAddJobAction($request) {
        $responseMessage = null;

        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobValidator = v::key('title', v::stringType()->notEmpty())
                  ->key('description', v::stringType()->notEmpty());

                //var_dump(is_dir('C:\xampp\htdocs\personal\public\uploads\\'));

                //die;

            try {
                $jobValidator->assert($postData);
                $postData = $request->getParsedBody();

                $files = $request->getUploadedFiles();
                $logo = $files['logo'];

                if($logo->getError() == UPLOAD_ERR_OK) {
                    $fileName = $logo->getClientFilename();
                    $logo->moveTo('C:\xampp\htdocs\personal\public\uploads\\'.$fileName);
            }
               // $title = $postData['title'];
                //$description = $postData['description'];
                $job = new Job();
                //var_dump($job);
                //die;
                $job->title =  $postData['title'];
                $job->description = $postData['description'];
                $job->months = $postData['months'];
                $job->logo = $fileName;
                $job->save();
                $responseMessage = 'Saved';
            } catch (\Exception $e) {
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addJob.twig', [
            'responseMessage' =>$responseMessage
        ]);
    }
};
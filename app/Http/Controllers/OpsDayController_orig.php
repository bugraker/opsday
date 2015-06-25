<?php namespace App\Http\Controllers;

use App\OpsDay;
use App\Http\Requests;
//use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;

/**
 * Display Ops Day.
 *
 * by: george.simcox@lyteworx.com
 *
 **/

class OpsDayController extends Controller {

    private $model;

    /**
     * Define object
     */
    public function __construct()
    {
        $this->model = new OpsDay();
    }
    public function showDayTest()
    {
        var_dump($_REQUEST);
    }
    /**
     * Show the OP Day for the current day.
     *
     * all date formats: yyyy-mm-dd
     *
     * GET modifications
     *  -start = OP start day to use
     *  -target = used to define what day to find the OP day for.  Used for a day other than the current day.
     *
     * Example:  http://url?start=2015-06-01&target=2015-07-22
     *
     * Note:  current day, or target day must follow the op start day
     *
     */
    public function showDay()
    {
        if (!empty($_ENV['OP_TZ'])) {
            $tz = $_ENV['OP_TZ']; // set to OP_TZ environmental variable.
        } else {
            $tz = 'UTC'; // set to UTC for mil use.
        }
        date_default_timezone_set($tz);

        $time = date("Y-m-d"); // init start day to current date as a default. can be overridden by "start" parameter or going to /start url

        // see if start parameter is present
        if (!empty($_REQUEST['start'])
            && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/',$_REQUEST['start'])) {
            // start parameter found
            $time = strtotime($_REQUEST['start']);

            // set cookie
            $this->model->setCookie($time);
        }

        // check to see if a cookie has been set
        elseif (!empty($this->model->getCookie())) {
            $time = strtotime($this->model->getCookie()); // set start day to what is in the cookie
        }

        // ok, no op start date available.  go ask the user
        else {
            // we are going to need to pass on the current and target date.
            if (!empty($_REQUEST['target'])) {
                $opsday = ['target' => $_REQUEST['target'], 'today' => $time];
            } else {
                $opsday = ['target' => $time, 'today' => $time];
            }
            return view('opsday.calendar', $opsday);
        }

        // Start day is defined

        // see if target day has been defined.
        if (!empty($_REQUEST['target'])) {
            // user has specifued the target day
            $target = strtotime($_REQUEST['target']);

            $opday = $this->model->getOpsDay($time, $target);
        }

        // not defined, so use current day
        else {
            // no target day defined
            $opday = $this->model->getOpsDay($time);
        }

        $output = json_decode($opday, true);

        return view('opsday.show', $output);
/*
        if (empty($output['message'])) {
            echo $output['day'];
        } else {
            var_dump($output);
        }
*/
    }

    /**
     * Show the ADO table
     *
     */
    public function showDayAPI()
    {
        // see if start parameter is present
        if (!empty($_REQUEST['start'])) {
            $time = strtotime($_REQUEST['start']);

            // set cookie
            $this->model->setCookie($time);
        }

        // check cookie if available
        elseif (!empty($this->model->getCookie())) {
            $time = strtotime($this->model->getCookie());
        }

        else {
            // ok, assume start is beginning of the current year
            $time = strtotime(date("Y")."-01-01");
        }

        // target day
        if (!empty($_REQUEST['target'])) {

            $target = strtotime($_REQUEST['target']);
            $opday = $this->model->getOpsDay($time, $target);
        } else {

            $opday = $this->model->getOpsDay($time);
        }

        echo $opday;
    }

    /**
     * Wipe cookie so the user will be asked to redefine the op start day
     *
     * @return mixed
     */
    public function reStart()
    {
        $this->model->unsetCookie();

        return \Redirect::to('/');
    }

}

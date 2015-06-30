<?php namespace App\Http\Controllers;

use App\OpsDay;
use App\Http\Requests;

/**
 * Display Ops Day.
 *
 * by: geo.simcox@gmail.com
 *
 * OPSDAY
 *
 *The MIT License (MIT)
 *
 *Copyright (c) 2015 George Patton Simcox, email: geo.simcox@gmail.com
 *
 *Permission is hereby granted, free of charge, to any person obtaining a copy
 *of this software and associated documentation files (the "Software"), to deal
 *in the Software without restriction, including without limitation the rights
 *to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *copies of the Software, and to permit persons to whom the Software is
 *furnished to do so, subject to the following conditions:
 *
 *The above copyright notice and this permission notice shall be included in
 *all copies or substantial portions of the Software.
 *
 *THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *THE SOFTWARE.
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

        /** Get COOKIE **/
        $cookie = $this->model->getCookie();

        if (empty($cookie)) {
            // what? No cookie found!
            $cookie = [
                'start' => null,
                'target' => null,
                'op' => "OPSDAY",
                'display' => null,
                'set' => null
            ];
        }

        /** Get TARGET **/
        if (!empty($_REQUEST['target'])) {
            // user has specified the target day
            $cookie['target'] = $_REQUEST['target'];
        //} elseif (empty($cookie['target'])) {
        } else {
            $cookie['target'] = date("Y-m-d");
        }

        /** Get START **/
        if (!empty($_REQUEST['start'])
            && preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/',$_REQUEST['start'])) {
            // start parameter found
            $cookie['start'] = $_REQUEST['start'];
        }

        /** Get OP **/
        if (!empty($_REQUEST['op'])){
            $cookie['op'] = $_REQUEST['op'];
        } elseif (isset($_REQUEST['op'])) {
            $cookie['op'] = $_REQUEST['op'];
        }

        /** Get DISPLAY **/
        if (!empty($_REQUEST['display'])) {
            $cookie['display'] = $_REQUEST['display'];
        }

        /** Get SET **/
        if (!empty($_REQUEST['set'])){
            $cookie['set'] = true;
        }

        // set cookie
        $this->model->setCookie($cookie);

        //check to see if cookie has start day
        if (!empty($cookie['start']) && !empty($cookie['target']) && !empty($cookie['set'])) {
            // get figure op day
            $result = $this->model->getOpsDay('web', strtotime($cookie['start']), strtotime($cookie['target']));

        }
        // ok, no op start date available.  go ask the user
        else {

            $this->model->setCookie($cookie);

            if (empty($cookie['start']) || empty($cookie['target']) || empty($cookie['set'])) {
                $opsday = [
                    'start' => $cookie['start'],
                    'target' => $cookie['target'],
                    'op' => (!empty($cookie['op']) ? $cookie['op'] : null),
                    'display' => (!empty($cookie['display']) ? $cookie['display'] : 'opsday')
                ];
                
                return view('opsday.calendar', $opsday);
            }
        }

        /** OUTPUT **/
        $output = json_decode($result, true);

        // add operation name
        $output['op'] = $cookie['op'];

        // mod rollover
        if (!empty($output['rollover']) && $output['rollover'] > 0) {
            $output['rollover'] = str_repeat('+', $output['rollover']);
        } elseif (!empty($output['rollover']) && $output['rollover'] < 0) {
            $output['rollover'] = str_repeat('-', abs($output['rollover'] + 1));
        }

        // add display
        if (!empty($cookie['display'])) {
            $output['display'] = $cookie['display'];
        } else {
            $output['display'] = "opsday";
        }

        return view('opsday.show', $output);
    }

    /**
     * Show the ADO table (not finished)
     *
     */
    public function showDayAPI()
    {
        /** INIT **/
        // set up default return
        $api_output = [
            "status" => "400",
            "message" => "Error Processing Request",
            "data" => ""
        ];

        /** Get START **/
        if (!empty($_REQUEST['start'])) {
            $start = $_REQUEST['start'];
        }

        /** Get TARGET **/
        if (!empty($_REQUEST['target'])) {
            $target = $_REQUEST['target'];
        } else  {
            $target = date("Y-m-d"); // date is assumed to be the current date.
        }

        if (!empty($start) && !empty($target)) {
            $opday_json = $this->model->getOpsDay('api', strtotime($start), strtotime($target));

            $opday_data = json_decode($opday_json, true);

            if (empty($opday_data['rollover'])) {
                unset($opday_data['rollover']); // dump empty rollover from data
            } elseif ($opday_data['rollover'] < 0) {
                $opday_data['rollover']++;
            }

            if (empty($opday_data['message'])) {
                unset($opday_data['message']); // dump empty message from data
                $api_output['status'] = "200";
                $api_output['message'] = "Success";
            } else {
                // move message to main api return
                $api_output['message'] = $opday_data['message'];
                unset($opday_data['message']);
            }

            $api_output['data'] = $opday_data; // add opday return to as api data return.

        } else {
            unset($api_output['data']);
        }

        header('Content-Type: application/json');
        echo json_encode($api_output);

    }

    /**
     * reset cookie so the user will be asked to redefine the op start day
     * Also, reset start day to the current day
     *
     * @return mixed
     */
    public function reset()
    {
        $cookie = $this->model->getCookie();

        $cookie['set'] = null;
        $cookie['display'] = 'opsday';
        $cookie['start'] = date("Y-m-d");

        $this->model->setCookie($cookie);

        return \Redirect::to('/');
    }

    /**
     * reset cookie so the user will be asked to redefine the op start day
     *
     * @return mixed
     */
    public function calendar()
    {
        $cookie = $this->model->getCookie();

        $cookie['set'] = null;

        $this->model->setCookie($cookie);

        return \Redirect::to('/');
    }

}

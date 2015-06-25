<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Ops Day Methods
 *
 * by: geo.simcox@gmail.com
 *
 * **
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

class OpsDay extends Model {

    private $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Set Cookie
     *
     * @param null $content
     * @return bool
     */
    public function setCookie($content=null)
    {
        if (!empty($content)) {
            setcookie('opsday', json_encode($content)); // 86400 = 1 day
        }

        return true;
    }

    /**
     * Unset cookie parameter
     *
     * @param null $parameter
     * @return bool
     */
    public function unsetCookie($parameter=null)
    {
        if (!empty($_COOKIE['opsday'])) {
            $cookie = json_decode($_COOKIE['opsday'], true);

        } else {

            $cookie = [
                'start' => null,
                'target' => null,
                'op' => null,
                'set' => null,
            ];
        }

        if (!empty($parameter)
            && !empty($cookie[$parameter])) {
            $cookie[$parameter] = null;
        }

        $this->setCookie($cookie);

        return true;
    }

    /**
     * Get Cookie
     *
     * @return mixed|null
     */
    public function getCookie()
    {
        $cookie = null;
        if (!empty($_COOKIE['opsday'])) {
            $cookie = json_decode($_COOKIE['opsday'], true);
        }
        return($cookie);
    }

    /**
     * getOpsDay
     *
     * determine the op day, diff between dates, and op day rollover modifier.
     *
     * @param null $start_day
     * @param null $get_day
     * @return string
     */
    public function getOpsDay($type=null, $start_day=null, $get_day=null)
    {
        $output = [
            'day' => null,
            'date' => null,
            'start' => null,
            'message' => null,
        ];

        if (empty($type)) {
            return(json_encode($output));
        }

        if (empty($get_day)) {
            // no start date defined.
            $current_day = date("Y-m-d");
            $get_day = strtotime($current_day); // set today

        } elseif (!is_int($get_day)) {

            if ($type == 'api') {
                $output['message'] = 'Invalid or malformed target';
            } else {
                $output['message'] = 'Invalid or malformed OP Day.';
            }
        }

        if (empty($output['message'])
            && !empty($start_day)
            && is_int($start_day)) {

            $output['start'] = date("Y-m-d", $start_day); // start day

            $output['date'] = date("Y-m-d", $get_day); // get date

            if (empty($output['message'])) {

                $time_diff = $get_day - $start_day;

                    $days_since = abs((integer)floor($time_diff / 86400) % (26 * 26)); // number of days between start and target date

                    $major = (integer)floor($days_since / 26);
                    $minor = (integer)floor($days_since % 26);

                    $output['day'] = substr($this->seed, $major, 1) . substr($this->seed, $minor, 1); // concatenate major and minor 'digits'
                    $output['since'] = $time_diff / 86400;
                    $output['rollover'] = floor($time_diff / 86400 / (26 * 26));

                    if (empty($output['rollover'])) {
                        $output['rollover'] = null;
                    }
            }
        } else {
            // mal-formed start day provided
            if ($type == 'api') {
                $output['message'] = 'Error determining start';
            } else {
                $output['message'] = 'Error determining OP Start.';
            }
        }
        // convert output to json and return
        return(json_encode($output));
    }

}

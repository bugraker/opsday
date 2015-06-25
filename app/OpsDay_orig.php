<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Ops Day Methods
 *
 * author: george.simcox@lyteworx.com
 *
 * All rights reserved
 *
 **/

class OpsDay extends Model {

    private $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function setCookie($date=null)
    {
        if (!empty($date)) {
            setcookie('opsday', date("Y-m-d", $date)); // 86400 = 1 day
        }
    }

    public function unsetCookie()
    {
        setcookie('opsday', '');
    }

    public function getCookie()
    {
        $cookie = null;
        if (!empty($_COOKIE['opsday'])) {
            $cookie = $_COOKIE['opsday'];
        }
        return($cookie);
    }

    /**
     * getOpsDay
     *
     * determine the op day
     *
     * @param null $start_day
     * @param null $get_day
     * @return string
     */
    public function getOpsDay($start_day=null, $get_day=null)
    {
        // init return
        $output = [
            'day' => null,
            'date' => null,
            'start' => null,
            'message' => null,
        ];

        if (empty($get_day)) {
            // no start date defined.
            $current_day = date("Y-m-d");
            $get_day = strtotime($current_day); // set today

        } elseif (!is_int($get_day)) {

            $output['message'] = 'Invalid or Malformed OP Date.';
        }

        if (empty($output['message'])
            && !empty($start_day)
            && is_int($start_day)) {

            $output['start'] = date("Y-m-d", $start_day); // start day

            $output['date'] = date("Y-m-d", $get_day); // get date

            if (empty($output['message'])) {

                $time_diff = $get_day - $start_day;

                if ($time_diff >= 0) {

                    $days_since = (integer)floor($time_diff / 86400) % (26 * 26); // number of days between start and target date

                    $major = (integer)floor($days_since / 26);
                    $minor = (integer)floor($days_since % 26);

                    $output['day'] = substr($this->seed, $major, 1) . substr($this->seed, $minor, 1); // concatenate major and minor 'digits'

                } else {
                    // future start date
                    $output['message'] = 'OP Start Date MUST precede the OP Date.';
                }
            }
        } else {
            // mal-formed start day provided
            $output['message'] = 'Error determining OP Start date.';
        }
        // convert output to json and return
        return(json_encode($output));
    }

}

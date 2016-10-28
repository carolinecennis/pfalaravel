<?php
/**
 * Created by IntelliJ IDEA.
 * User: cennis
 * Date: 8/7/16
 * Time: 2:56 PM
 */

namespace App\Services;

class MultiCurlService
{

    public $division;

    public function __construct($division)
    {
        $this->division = $division;
    }

    public function callCurlMulti($results)
    {
        $urls = array();

        foreach ($results as $result) {
            $data = array(
                'url' => 'http://pfa-internal.com/updateSum',
                'post' => array(
                    'key' => $result->ids,
                    'value' => $result->checkSum,
                    'division' => $this->division,
                )
            );
            array_push($urls, $data);
        }

        $this->multiCurl($urls);

    }

    public function multiCurl($data)
    {

        // array of curl handles
        $curly = array();
        // data to be returned
        $result = array();

        // multi handle
        $mh = curl_multi_init();

        // loop through $data and create curl handles
        // then add them to the multi-handle
        foreach ($data as $id => $d) {
            $curly[$id] = curl_init();

            $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
            curl_setopt($curly[$id], CURLOPT_URL, $url);
            curl_setopt($curly[$id], CURLOPT_HEADER, 0);
            curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

            // post?
            if (is_array($d)) {
                if (!empty($d['post'])) {
                    curl_setopt($curly[$id], CURLOPT_POST, 1);
                    curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
                }
            }

            curl_multi_add_handle($mh, $curly[$id]);
        }

        // execute the handles
        $running = null;
        do {
            do {
                $mrc = curl_multi_exec($mh, $running);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            // this fixes the multi select from returning -1 forever
            usleep(30);
        } while(curl_multi_select($mh) === -1);

        while ($running && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $running);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

            // get content and remove handles
            foreach ($curly as $id => $c) {
                $result[$id] = curl_multi_getcontent($c);
                curl_multi_remove_handle($mh, $c);
            }

            // all done
            curl_multi_close($mh);

            return $result;
        }


    function rolling_curl($urls, $custom_options = null)
    {
        // make sure the rolling window isn't greater than the # of urls
        $rolling_window = 50;
        $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;

        $master = curl_multi_init();
        $curl_arr = array();
        // add additional curl options here
        $std_options = array(CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HEADER => false,
            CURLOPT_MAXREDIRS => 5);

        $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;
        // start the first batch of requests
        for ($i = 0; $i < $rolling_window; $i++) {
            $ch = curl_init();
            $options[CURLOPT_URL] = $urls[$i]['url'];
            //  $options[CURLOPT_HEADER] = 0;
            //  $options[CURLOPT_RETURNTRANSFER] = 1;

            // post?
            if (is_array($urls[$i])) {
                if (!empty($urls[$i]['post'])) {
                    $options[CURLOPT_POST] = 1;
                    $options[CURLOPT_POSTFIELDS] = http_build_query($urls[$i]['post']);
                }

                curl_setopt_array($ch, $options);


                curl_multi_add_handle($master, $ch);
            }
        }
        do {
            while (($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM) ;
            if ($execrun != CURLM_OK)
                break;

            // a request was just completed -- find out which one
            while ($done = curl_multi_info_read($master)) {
                $info = curl_getinfo($done['handle']);
                if ($info['http_code'] == 200) {

                    $output = curl_multi_getcontent($done['handle']);
                    // request successful.  process output using the callback function.
                 //   $callback($output);
                    // start a new request (it's important to do this before removing the old one)
                    $ch = curl_init();
                    $var = $i++;
                    $options[CURLOPT_URL] = $urls[$var]['url'];;  // increment i

                    // post?
                    if (is_array($urls[$var])) {
                        if (!empty($urls[$var]['post'])) {
                            $options[CURLOPT_POST] = 1;
                            $options[CURLOPT_POSTFIELDS] = http_build_query($urls[$var]['post']);
                        }

                        curl_setopt_array($ch, $options);
                        curl_multi_add_handle($master, $ch);
                        // remove the curl handle that just completed
                        curl_multi_remove_handle($master, $done['handle']);
                    }
                } else {
                    //request failed
                }
            }
        } while ($running);

        curl_multi_close($master);
        return true;
    }
}


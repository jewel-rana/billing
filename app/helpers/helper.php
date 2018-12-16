<?php

    use App\Option;

    function getOption($key, $default = null)
    {
        $option = Option::where('label', $key)->first();

        return (!empty($option) & is_object($option)) ? $option->content : $default;
    }

    /**
     * Return nav-here if current path begins with this path.
     *
     * @param string $path
     * @return string
     */
    function isActive($path)
    {
        return Request::is($path) ? 'active' : '';
    }

//create nice slug
    function niceSlug($string, $separator = '-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and', "'" => '');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    function notifyMe($userId, $message, $type = 'pending')
    {
        $notice = new \App\Notification;
        $notice->user_id = $userId;
        $notice->message = $message;
        $notice -> status = 0;
        $notice->type = $type;
        $notice->save();

        return ($notice->id) ? true : false;
    }

    function getChildCatForDropdown($id)
    {
        $query = \App\Category::where('parent', $id)->pluck('name', 'id');

        return ($query) ? $query : array();
    }


    function words($value, $words = 100, $end = '...')
    {
        return \Illuminate\Support\Str::words($value, $words, $end);
    }


    /**
     * @param $timestamp
     * @return string
     */
    function get_time_ago($timestamp)
    {

        //date_default_timezone_set("Asia/dhaka");
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;

        $minutes = round($seconds / 60); // value 60 is seconds
        $hours = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        $days = round($seconds / 86400); //86400 = 24 * 60 * 60;
        $weeks = round($seconds / 604800); // 7*24*60*60;
        $months = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        $years = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60

        if ($seconds <= 60) {

            return "Just Now";

        } else if ($minutes <= 60) {

            if ($minutes == 1) {

                return "one minute ago";

            } else {

                return "$minutes minutes ago";

            }

        } else if ($hours <= 24) {

            if ($hours == 1) {

                return "an hour ago";

            } else {

                return "$hours hrs ago";

            }

        } else if ($days <= 7) {

            if ($days == 1) {

                return "yesterday";

            } else {

                return "$days days ago";

            }

        } else if ($weeks <= 4.3) {

            if ($weeks == 1) {

                return "a week ago";

            } else {

                return "$weeks weeks ago";

            }

        } else if ($months <= 12) {

            if ($months == 1) {

                return "a month ago";

            } else {

                return "$months months ago";

            }

        } else {

            if ($years == 1) {

                return "one year ago";

            } else {

                return "$years years ago";

            }
        }
    }

    function reviewAverage($reviews)
    {
        $reviewArr[] = 0;

        if ($reviews->count() > 0) :

            foreach ($reviews as $review) :
                $reviewArr[] = $review->rating . ", ";
            endforeach;

        endif;

        return floor(array_sum($reviewArr));

    }

    /**
     * @param $string
     * @param $repl
     * @param $limit
     * @return string
     */
    function addDotsToLongText($string, $repl, $limit)
    {
        if (strlen($string) > $limit) {
            return substr($string, 0, $limit) . $repl;
        } else {
            return $string;
        }
    }


    function getCategoryNameById($id)
    {
        if (isset($id)) {

            $parent = \App\Category::where('parent', null)
                ->where('id', $id)->first();
            if (!empty($parent)) {
                return $parent->name;
            } else {
                $child = App\Category::where('id', $id)->first();
                //dd($child);
                return $child->name;
            }

        }

    }

    function get_options($array, $parent = "", $indent = "")
    {
        $return = array();
        foreach ($array as $key => $val) {
            if ($val["parent"] == $parent) {
                $return[] = $indent . $val["id"];
                $return = array_merge($return, get_options($array, $val["id"], $indent));
            }
        }
        return $return;
    }

    function partitionArray( $arr, $p = 3 ) {
        //check array is an array
        if( is_array( $arr ) ) :

            //count the given array
            $listlen = count( $arr );

            //floor pertition
            $partlen = floor( $listlen / $p );
            $partrem = $listlen % $p;
            $partition = array();
            $mark = 0;

            //loop through array
            for ( $px = 0; $px < $p; $px++ ) {
                $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
                $partition[$px] = array_slice( $arr, $mark, $incr );
                $mark += $incr;
            }
            return $partition;

        else :
            return $arr;
        endif;
    }


    ///for chat feature increase
    function formatUrlsInText($text)
    {
        // The Regular Expression filter
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        // Check if there is a url in the text
        if (preg_match($reg_exUrl, $text, $url)) {

            // make the urls hyper links
            return preg_replace($reg_exUrl, "<a href=" . $url[0] . " title=" . $url[0] . " target='_blank'>.$url[0].</a> ", $text);

        } else {

            return $text;
        }
    }

    function check_base64_image($data)
    {

        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type))
        {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                return true;
            }

            $data = base64_decode($data);

            if ($data === false) {
                return false;
            }
        }
        return false;
    }


    function checkMessage($data)
    {
        //youtube video
        if (strpos($data, 'youtube') > 0) {
            $data = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<iframe width=\"200\" height=\"100\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $data);

        } elseif (strpos($data, 'youtu') > 0) {
            $data = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", "<iframe width=\"200\" height=\"100\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $data);

        }

//        //base64
        else if (check_base64_image($data))
        {
            $data ='<a href="'.$data.'" target="_blank"><img src="'.$data.'" style="overflow:auto; height:100px;width:170px;"></a>';
        }
        //normal text
        else
        {
            $data = formatUrlsInText($data);
        }

        return html_entity_decode($data);
    }


    function escapePhpString($target) {
        $replacements = array(
            "'" => '"',
            "\\" => '\\\\',
            "\r\n" => "\\r\\n",
            "\n" => "\\n"
        );
        return strtr($target, $replacements);
    }

//return currency sign
    function getCurrencySignByName($str)
    {
        if($str=='euro')
        {
            return '€';
        }
        elseif($str == 'afn')
        {
            return '؋';
        }
        else{
            return '$';
        }
    }

//add http to url
    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
<?php

class utils
{
    static public function getDateFormat($date)
    {
        $dateSlice = explode("-", $date);
        $string = "";
        if ($dateSlice[1] == "01") {
            $string .= "January";
        }
        else if ($dateSlice[1] == "02") {
            $string .= "February";
        }
        else if ($dateSlice[1] == "03") {
            $string .= "March";
        }
        else if ($dateSlice[1] == "04") {
            $string .= "April";
        }
        else if ($dateSlice[1] == "05") {
            $string .= "May";
        }
        else if ($dateSlice[1] == "06") {
            $string .= "June";
        }
        else if ($dateSlice[1] == "07") {
            $string .= "July";
        }
        else if ($dateSlice[1] == "08") {
            $string .= "August";
        }
        else if ($dateSlice[1] == "09") {
            $string .= "September";
        }
        else if ($dateSlice[1] == "10") {
            $string .= "October";
        }
        else if ($dateSlice[1] == "11") {
            $string .= "November";
        }
        else if ($dateSlice[1] == "12") {
            $string .= "December";
        }

        return $string." ".$dateSlice[2].", ".$dateSlice[0];
    }

    static public function test_input($data)
    {
        if (isset($data)) {
            $data = trim($data);//Remove whitespaces from both sides of a string
            $data = stripslashes($data);//Remove the backslash
            $data = htmlspecialchars($data);//Convert the predefined characters "<" (less than) and ">" (greater than) to HTML entities
            return $data;
        }
    }
}
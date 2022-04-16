<?php

function static_count()
{
    static $count = 0;
    return ++$count;
}

function read_dateTime($dateTime)
{
    return date("F j, Y H:i:s", strtotime($dateTime));
}

function read_date($date)
{
    return date("F j, Y", strtotime($date));
}

function read_time($time)
{
    return date("H:i:s", strtotime($time));
}

function dateNow()
{
    return date("Y-m-d H:i:s");
}

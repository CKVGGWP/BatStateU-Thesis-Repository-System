<?php

function static_count()
{
    static $count = 0;
    return ++$count;
}

function read_dateTime($dateTime)
{
    return date("F j, Y g:i:a", strtotime($dateTime));
}

function read_date($date)
{
    return date("F j, Y", strtotime($date));
}

function read_time($time)
{
    return date("g:i:a", strtotime($time));
}

function dateTimeNow()
{
    return date("Y-m-d H:i:s");
}

function dateNow()
{
    return date("Y-m-d");
}

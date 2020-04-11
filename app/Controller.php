<?php
namespace App;

class Controller
{
    public function homepage()
    {
        return 'This is a Homepage';
    }

    public function somepage($first, $second)
    {
        return "First: '{$first}' <br>Second: '{$second}'";
    }
}

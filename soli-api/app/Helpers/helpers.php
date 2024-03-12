<?php

use App\Helpers\NumberHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\FileHelper;

function numberhelper(): NumberHelper
{
    return new NumberHelper();
}

function reshelper(): ResponseHelper
{
    return new ResponseHelper();
}

function filehelper(): FileHelper
{
    return new FileHelper();
}

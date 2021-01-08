<?php

namespace app\modules\wallet\commands;

use Yii;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;


class BaseController extends Controller
{
    protected function print($val)
    {
        try {
            echo strval($val) . "\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    protected function arrayToString(array $arr)
    {
        $str = '';
        foreach($arr as $k => $v) {
            $str .= $k . ': ' . $v . ', ';
        }
        return $str;
    }

    public function printError($name='Error', $message = null, $code = 0, $status = 500, \Exception $previous = null)
    {
        Console::error(
            Console::ansiFormat(' [!] ' . $name . ' at ' . date('d.m.Y h:i:s') . ' - Error! ', 
            [Console::BG_RED, Console::FG_GREY, Console::BOLD] ));

        Console::error(
            Console::ansiFormat('   Code: ' . $code . ' ', 
            [Console::FG_YELLOW] ));

        Console::error(
            Console::ansiFormat('   Message: ' . $message .' ',
            [Console::FG_YELLOW] ));
        
        Console::error();
    }

    public function printSuccess($name='Success', $message = null, $code = 0, $status = 200, \Exception $previous = null)
    {
        Console::output(
            Console::ansiFormat(' [*] ' . $name . ' at ' . date('d.m.Y h:i:s') . ' - Success! ', 
            [Console::FG_GREEN, Console::BOLD] ));

        Console::output(
            Console::ansiFormat('  Result message: ',
            [Console::FG_CYAN] ));

        Console::output(
            Console::ansiFormat('    ' . preg_replace("/\n/", "\n    ", $message) .' ',
            [Console::FG_CYAN] ));

        Console::output();
    }

}

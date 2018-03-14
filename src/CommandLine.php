<?php

namespace Gukasov;

class CommandLine
{
    /**
     * Gets input value for given option.
     *
     * @param string $option
     *
     * @return mixed
     */
    public static function getInput(string $option)
    {
        $input = getopt("$option:", []);

        if (isset($input[$option])) {
            return $input[$option];
        }

        return false;
    }
}
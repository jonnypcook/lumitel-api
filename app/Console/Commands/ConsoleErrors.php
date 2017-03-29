<?php

namespace App\Console\Commands;

/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 29/03/2017
 * Time: 08:10
 */
trait ConsoleErrors
{
    /**
     * display errors and exit
     * @param $errors
     */
    public function errorAndExit($errors) {
        $this->comment('Command failed with the following errors:');
        foreach ((array)$errors as $error) {
            if (is_array($error)) {
                foreach ($error as $item) {
                    $this->error($item);
                }

            } else {
                $this->error($error);
            }
        }

        exit;
    }
}
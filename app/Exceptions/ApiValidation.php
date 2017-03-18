<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 10:45
 */

namespace App\Exceptions;

use Validator;

trait ApiValidation
{
    /**
     * @param array $configuration
     * @param $data
     * @return bool
     */
    function runValidator(array $configuration, $data) {
        $validator = $this->createValidator($configuration, $data);
        $validator->validate();

        return true;
    }

    /**
     * @param array $configuration
     * @param $data
     * @return mixed
     */
    function createValidator(array $configuration, $data) {
        return Validator::make($data, $configuration);
    }


}
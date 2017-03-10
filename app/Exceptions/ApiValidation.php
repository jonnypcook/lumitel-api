<?php
/**
 * Created by PhpStorm.
 * User: jonathancook
 * Date: 10/03/2017
 * Time: 10:45
 */

namespace App\Exceptions;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait ApiValidation
{
    /**
     * @param array $configuration
     * @param $data
     * @return bool
     */
    function runValidator(array $configuration, $data) {
        $validator = $this->createValidator($configuration, $data);
        if ($validator->fails()) {
            throw new BadRequestHttpException($this->formatValidatorErrorMessage($validator->errors()->messages()));
        }

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

    /**
     * format our validation error message
     * @param $errors
     * @return mixed
     */
    function formatValidatorErrorMessage($errors) {
        return array_reduce($errors, function ($errorMessage = '', $error) {
            $errorMessage .= ' ' . implode(' ', $error);
            return ltrim($errorMessage, ' ');
        });
    }
}
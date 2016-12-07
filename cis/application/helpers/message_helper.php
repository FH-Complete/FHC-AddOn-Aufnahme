<?php

/**
 * Success
 *
 * @return  array
 */
function success($retval, $code = null, $msg_indx_prefix = 'fhc_')
{
	$success = new stdClass();
	$success->error = EXIT_SUCCESS;
	$success->fhcCode = $code;
	if (!is_null($code)) $success->msg = lang($msg_indx_prefix . $code);
	$success->retval = $retval;
	
	return $success;
}

/**
 * Error
 *
 * @return  array
 */
function error($retval = '', $code = null, $msg_indx_prefix = 'fhc_')
{
	$error = new stdClass();
	$error->error = EXIT_ERROR;
	$error->fhcCode = $code;
	if (!is_null($code)) $error->msg = lang($msg_indx_prefix . $code);
	$error->retval = $retval;
	
	return $error;
}

/**
 * Checks if the result represents a success
 */
function isSuccess($result)
{
	if (is_object($result) && isset($result->error) && $result->error == EXIT_SUCCESS)
	{
		return true;
	}
	
	return false;
}

/**
 * Checks if the result represents a success and also if it contains data from DB
 */
function hasData($result)
{
	if (isSuccess($result) && isset($result->retval) &&
		is_array($result->retval) && count($result->retval) > 0)
	{
		return true;
	}
	
	return false;
}

/**
 * Checks if the result represents an error
 * Wrapper function of isSuccess, more readable code
 */
function isError($result)
{
	return !isSuccess($result);
}
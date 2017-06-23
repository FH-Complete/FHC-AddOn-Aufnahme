<?php
/**
 * Created by PhpStorm.
 * User: puraner
 * Date: 22.06.2017
 * Time: 16:11
 */


function parseUdfData($udf_config, $udfs, $data, $target, $lang)
{
    if(is_array($udf_config) && $udf_config["active"] == true)
    {
        //$udfs = $this->getData("udfs");

        if(isset($udfs) && is_array($udfs))
        {
            $errormsg = "";

            foreach($udfs as $udf)
            {
                if(isset($data[$udf->name]))
                {
                    if($udf->type == 'checkbox')
                    {
                        $target->{$udf->name} = true;
                    }
                    else
                    {
                        $validation = $udf->validation;
                        $isValid = true;

                        if(isset($validation->{'max-value'}))
                        {
                            if($data[$udf->name] > $validation->{'max-value'})
                            {
                                $isValid = false;
                                $errormsg .= $udf->name." ".$lang->line("aufnahme/greaterThanMaxValue").'<br>';
                            }
                        }

                        if(isset($validation->{'min-value'}))
                        {
                            if($data[$udf->name] < $validation->{'min-value'})
                            {
                                $isValid = false;
                                $errormsg .= $udf->name." ".$lang->line("aufnahme/lessThanMinValue").'<br>';
                            }
                        }

                        if (isset($validation->regex) && is_array($validation->regex))
                        {
                            foreach($validation->regex as $regexIndx => $regex)
                            {
                                if ($regex->language == 'php')
                                {
                                    if (preg_match($regex->expression, $data[$udf->name]) != 1)
                                    {
                                        $isValid = false;
                                        $errormsg .= $udf->name." ".$lang->line("aufnahme/notValid").'<br>';
                                        break;
                                    }
                                }
                            }
                        }

                        if($isValid)
                        {
                            $target->{$udf->name} = $data[$udf->name];
                        }
                    }
                }
                elseif((in_array($udf->name, $udf_config["udfs"])) && ($udf->type == 'checkbox'))
                {
                    $target->{$udf->name} = false;
                }
            }
        }
    }

    if($errormsg == "")
    {
        return $target;
    }

    return $errormsg;
}
<?php
/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 4/8/2016
 * Time: 11:09 AM
 */
class xx
{
    public function get_element_template($parameters)
    {
        if (!isset($this->$parameters['type'])) {
            $result['msg'] = 'etiqueta no válida ' . $parameters['type'];
        }
        $template = $parameters['type'] . 'Template';
        if (property_exists('fratrisTemplates', $parameters['type'])) {
            $html = $this->$parameters['type'];
            $html = $this->replace_data($html, $parameters);
            $result['html'] = $html;
            $result['type'] = $parameters['type'];
            $result['msg'] = 'false';
            $template = false;
        }
        if (($template) && (property_exists('fratrisTemplates', $template))) {
            $html = $this->master($parameters);
            $result['html'] = $html;
            $result['type'] = $parameters['type'];
            $result['msg'] = 'false';
        }

        return $result;
    }


}



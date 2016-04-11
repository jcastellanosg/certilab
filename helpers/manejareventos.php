<?php

/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/5/2015
 * Time: 2:19 PM
 */
JLoader::registerNamespace('Monolog',JPATH_ADMINISTRATOR . '/components/com_certilab//helpers/validator');

//JLoader::registerNamespace('Monolog', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/validator');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\ChromePHPHandler;


class ManejarEventos
{


    public static function HandlerLog($data)
    {
        // Create the logger
        $logger = new Logger('my_logger');
        // Now add some handlers
        $logger->pushHandler(new StreamHandler(__DIR__ . '/fratris_facturacion.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $logger->pushHandler(new ChromePHPHandler());
        // You can now use your logger
        $logger->addInfo('My logger is now ready');
        $logger->addWarning('Test warning message');
        $logger->addError('Test error message');
        $logger->addInfo('Test info message');
        $logger->addDebug('Test debug message');

    }

    public static function mostrarEnPantalla($data)
    {
        $msg = $data['clase'].": ".$data['message'];
        $type = $data['type'];
        JFactory::getApplication()->enqueueMessage(JText::_($msg),$type);
    }

    public static function enviarColaDeProcesos($data)
    {
    }

    public static function enviarNotificacion($data)
    {
    }

    public static function enviarLogFile($data)
    {
    }

    public static function enviarLogCentralizado($data)
    {

    }

    public static function getEventoByCode($evento)
    {
        /*
         * De acuerdo al aevento determinar acciones a ejecutar
         */
        return $evento;
    }

    function _killMessage($error)
    {
        $app = JFactory::getApplication();
        $appReflection = new ReflectionClass(get_class($app));
        $_messageQueue = $appReflection->getProperty('_messageQueue');
        $_messageQueue->setAccessible(true);
        $messages = $_messageQueue->getValue($app);
        foreach ($messages as $key => $message) {
            if ($message['message'] == $error) {

                unset($messages[$key]);
            }
        }
        $_messageQueue->setValue($app, $messages);
    }

    public static function  recoverMessage()
    {
        $resultado = Array();
        $app = JFactory::getApplication();
        $appReflection = new ReflectionClass(get_class($app));
        $_messageQueue = $appReflection->getProperty('_messageQueue');
        $_messageQueue->setAccessible(true);
        $messages = $_messageQueue->getValue($app);
        foreach ($messages as $key => $message) {
            $resultado[] = "key: " . $key + "- message: " . implode("%",$message);
            unset($messages[$key]);
            }
        $_messageQueue->setValue($app, $messages);
        return implode(" & ",$resultado);
    }

    public static function manejarEvento($data)
    {
        $evento = $data['evento'];
        if (!isset($evento)){
            $evento = 1;
        }
        switch ($evento) {
            case 1 :
                self::mostrarEnPantalla($data);
                break;
            case 2 :
                self::enviarColaDeProcesos($data);
                break;
            case 3 :
                self::enviarNotificacion($data);
                break;
            case 4 :
                self::enviarLogFile($data);
                break;
            default  :
                self::enviarLogCentralizado($data);

        }
    }

}
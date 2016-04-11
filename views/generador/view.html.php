<?php
defined('_JEXEC') or die();



class CertilabViewGenerador extends JViewLegacy
{

    protected $datosgenerales = array();

    public function display($tpl = null)
    {
        $this->generateController();
        $this->generateModel();
        $this->generateView();
        $this->generateTemplate();
        $modelo = $this->getModel();
        $this->datosgenerales = $modelo->getDataToDataTable();
        parent::display($tpl);


    }


    public function generateController()
    {
        error_reporting(E_ALL);
        $templates = new FratrisTemplatesComponent;
        $pagename = 'generador\controllers\controller.php';

        $modelo = $this->getModel();

        $newFileName = $pagename;
        $parameters = $modelo->getParametersController();
        $parameters['$'] = "$";

        echo "<pre>";
        print_r($parameters);
        echo "</pre>";

        $newFileContent = $templates->get_element_template($parameters);

        echo "<pre>";
        print_r($newFileContent);
        echo "</pre>";
        if (file_put_contents($newFileName, $newFileContent) != false) {
            echo "File created (" . basename($newFileName) . ")";
        } else {
            echo "Cannot create file (" . basename($newFileName) . ")";
        }
    }


    public function generateModel()
    {
        error_reporting(E_ALL);
        $templates = new FratrisTemplatesComponent;
        $pagename = 'generador\models\modelo.php';

        $modelo = $this->getModel();

        $newFileName = $pagename;
        $parameters = $modelo->getParametersModelo();
        $parameters['$'] = "$";
  /*      echo "<pre>";
        print_r($parameters);
        echo "</pre>";*/
        $newFileContent = $templates->get_element_template($parameters);
        if(file_put_contents($newFileName,$newFileContent)!=false){
            echo "File created (".basename($newFileName).")";
        }else{
            echo "Cannot create file (".basename($newFileName).")";
        }

    }

    public function generateView()
    {
        error_reporting(E_ALL);
        $templates = new FratrisTemplatesComponent;
        $pagename = 'generador\views\view.html.php';

        $modelo = $this->getModel();

        $newFileName = $pagename;
        $parameters = $modelo->getParametersView();
        $parameters['$'] = "$";
        /*      echo "<pre>";
              print_r($parameters);
              echo "</pre>";*/
        $newFileContent = $templates->get_element_template($parameters);
        if(file_put_contents($newFileName,$newFileContent)!=false){
            echo "File created (".basename($newFileName).")";
        }else{
            echo "Cannot create file (".basename($newFileName).")";
        }

    }

    public function generateTemplate()
    {
        error_reporting(E_ALL);
        $templates = new FratrisTemplatesComponent;
        $pagename = 'generador\views\default.php';

        $modelo = $this->getModel();

        $newFileName = $pagename;
        $parameters = $modelo->getParametersTemplate();

        $parameters['$'] = "$";
        $parameters['element'] = "table";
        $parameters['table'] = $templates->get_element_template($parameters);
        $parameters['element'] = "template";

        $newFileContent = $templates->get_element_template($parameters);
        if (file_put_contents($newFileName, $newFileContent) != false) {
            echo "File created (" . basename($newFileName) . ")";
        } else {
            echo "Cannot create file (" . basename($newFileName) . ")";
        }
    }

}

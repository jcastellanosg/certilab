<?php
defined('_JEXEC') or die('Restricted access');


class FratrisTemplatesComponent
{

    private $controller = <<<CONTROLLER
<?php

defined('_JEXEC') or die('Restricted access');

class #COMPONENT#Controller#VIEW# extends JControllerAdmin
{
    public function getlist()
    {
        //JSession::checkToken('get') or die('Invalid Token');

        #$#jinput = JFactory::getApplication()->input;
        #$#draw = #$#jinput->get('draw', 1, 'INT');
        #$#modelo = #$#this->getModel();
        #$#items = #$#modelo->getItems();

        #$#resultado = array(
            "draw" => #$#draw,
            "recordsTotal" => 100,
            "recordsFiltered" => 50,
            "data" => #$#items,
        );

        echo json_encode(#$#resultado);
        jexit();
    }
}


CONTROLLER;

    private $modelo = <<<MODELO
<?php
            defined('_JEXEC') or die('Restricted access');

            class #COMPONENT#Model#VIEW# extends JModelList
            {
                  private #$#querytotal;
                  private #$#parametros;
                  private #$#campos = #CAMPOS#;
                  private #$#header = #HEADER#;
                  private #$#queryselect = <<<QUERY
                      #QUERYSELECT#;
QUERY;


                  public function __construct(#$#config = array())
                  {
                      #$#jinput = JFactory::getApplication()->input;
                      #$#this->parametros['arraydecondiciones'] = #$#jinput->get('arraydecondiciones', [], 'ARRAY');;
                      #$#this->parametros['arrayorder'] = #$#jinput->get('order', null, 'ARRAY');
                      #$#this->parametros['vista'] = 'Test';
                      parent::__construct(#$#config);
                  }

                  public function getListQuery()
                  {
                      #$#db = JFactory::getDbo();
                      #$#query = #$#db->getQuery(true);
                      #$#paginacion = #$#this->setPaginacion();
                      #$#querysring = #$#this->getQueryComands(). " LIMIT {#$#paginacion['start']} ,{#$#paginacion['limit']}";
                      #$#query->setQuery(#$#querysring);
                      return #$#query;
                  }

                  public function setPaginacion()
                  {
                      #$#jinput = JFactory::getApplication()->input;
                      #$#start = #$#jinput->get('start', '0', 'INT');
                      #$#limit = #$#jinput->get('length', '0', 'INT');
                      return ["start"=>#$#start,"limit"=>#$#limit];
                  }

                  public function getQueryComands()
                  {
                      #$#queryselect =  #$#this->queryselect;
                      #$#orden = #$#this->getQueryOrder();
                      if (!empty(#$#orden)) {
                            #$#queryselect .= " ORDER BY {#$#orden}";
                      }
                      return #$#queryselect;
                  }

                  private function satinizarValue(#$#value)
                  {
                      #$#db = JFactory::getDbo();
                      return #$#db->quote(#$#value);
                  }


                  public function getQueryWhere()
                  {
                      #$#condicion = #CONDITIONFROMTABLE#;
                      #$#condicionfi = #$#this->getQueryWhereFromInput();
                      #$#condiciones = array_merge(#$#condicion, #$#condicionfi);
                      return implode(' AND ', #$#condiciones);
                  }

                  public function getQueryWhereFromInput()
                  {
                    #$#condiciones = #$#this->parametros['arraydecondiciones'];
                    #$#condiciones = array_map(array(#$#this, 'doWhereCondition'), #$#condiciones);
                    return array_column(array_filter(#$#condiciones), 'condition');
                  }

                  function doWhereCondition(#$#row)
                  {
                    #$#validaciones = new FratrisValidaciones;
                    #$#resultado = false;
                    #$#campos = #$#this->campos;
                    #$#campo = #$#row['field'];
                    if (array_key_exists(#$#campo, #$#campos)) {
                        #$#valor = #$#validaciones->getValue(#$#row['operator'], #$#row['value'], #$#campos[#$#campo]['type']);
                        if (#$#valor) {
                            #$#resultado = "{#$#campo} {#$#valor}";
                        }
                    }
                    return #$#resultado;
                   }

                  public function getQueryOrder()
                  {
                        #$#columns = [];
                        #$#campos = #$#this->campos;
                        #$#totalcampos = count(#$#campos);
                        #$#arrayorder = #$#this->parametros['arrayorder'];
                        foreach (#$#arrayorder as #$#field) {
                            #$#index = (int)#$#field['column'];
                            if (#$#index < #$#totalcampos) {
                                #$#direction = #$#field['dir'] == 'asc' ? 'ASC' : 'DESC';
                                #$#columns[] = "{#$#campos[#$#index]}  {#$#direction}";
                            }
                        }
                        return implode(",", #$#columns);
                  }
    }
MODELO;

    private $view = <<<VIEW
<?php
            defined('_JEXEC') or die('Restricted access');

            class #COMPONENT#View#VIEW# extends JViewLegacy
            {
                    public function display(#$#tpl = null)
                    {
                        #$#modelo = #$#this->getModel();
                        parent::display(#$#tpl);
                    }
            }

VIEW;

    private $template = <<<TPL

#FORMA#

#TABLE#

#SCRIPT#

TPL;

    private $table = <<<TABLE
    <table id="#VIEW#Table" class="table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                #HEADER#
            </tr>
        </thead>
        <tfoot>
            <tr>
                #FOOTER#
            </tr>
        </tfoot>
    </table>

<script>
    #$#(document).ready(function() {
        #$#('##VIEW#Table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                 "url": "http://localhost/centrek/administrator/index.php?option=com_certilab&task=generador.getList&menu=507",
                "type": "POST",
                "data" :  function ( d ) {
                    if (fr_datatables.data.length > 0) {
                        d.arraydecondiciones = fr_datatables.data
                    }
                }
            },
            "columns": #COLUMNS#,
            "language": {
        }
        } );
    } );

</script>

TABLE;

    private $xmlcomponente = <<<XMLCOMPONENTE
<?xml version="1.0" encoding="UTF-8"?>
<extension type="component" version="3.0" method="upgrade">
    <name>#COMPONENTE#</name>
    <license>#LICENCIA#</license>
    <author>#AUTOR#</author>
    <authorEmail>#EMAIL#</authorEmail>
    <authorUrl>#AUTORURL#</authorUrl>
    <creationDate>#FECHA#</creationDate>
    <copyright>#COPYRIGHT#</copyright>
    <version>#VERSION#</version>
    <description>#DESCRIPCION#</description>



    <install>
        <sql>
            <file driver="mysql"
                  charset="utf8">sql/install.mysql.utf8.sql</file>
        </sql>
    </install>

    <uninstall>
        <sql>
            <file driver="mysql"
                  charset="utf8">sql/uninstall.mysql.utf8.sql</file>
        </sql>
    </uninstall>

    <languages folder="site">
         <language tag="en-GB">language/en-GB.com_#COMPONENTE#.ini</language>
 	     <language tag="es-ES">language/es-ES.com_#COMPONENTE#.ini</language>
    </languages>

    <languages folder="admin">
         <language tag="en-GB">language/en-GB.com_#COMPONENTE#.ini</language>
 	     <language tag="es-ES">language/es-GB.com_#COMPONENTE#.ini</language>
    </languages>

    <media folder="media" destination="com_certilab">
        <folder>js</folder>
        <folder>css</folder>
        <folder>images</folder>
    </media>


    <files folder="site">
            <folder>controllers</folder>
            <folder>models</folder>
            <folder>tables</folder>
            <folder>views</folder>
            <folder>layouts</folder>
            <folder>helpers</folder>
            <folder>sql</folder>
            <filename>access.xml</filename>
            <filename>config.xml</filename>
            <filename>controller.php</filename>
            <filename>index.html</filename>
            <filename>#COMPONENTE#.php</filename>
    </files>

    <administration>
        <menu  link="option=com_facturacion">#COMPONENTEMENU#</menu>
        <files folder="admin">
            <folder>controllers</folder>
            <folder>models</folder>
            <folder>tables</folder>
            <folder>views</folder>
            <folder>layouts</folder>
            <folder>helpers</folder>
            <folder>sql</folder>
            <filename>access.xml</filename>
            <filename>config.xml</filename>
            <filename>controller.php</filename>
            <filename>index.html</filename>
            <filename>#COMPONENTE#.php</filename>
        </files>
    </administration>
</extension>

XMLCOMPONENTE;


    private function master($parameters)
    {
        $content = array_map([$this, 'get_element_template'], $parameters['content']);
        return implode('', $content);
    }

    private function add_pads($paramKey)
    {
        return '#' . strtoupper($paramKey) . '#';
    }

    private function replace_data($html, $parameters)
    {
        $keys = array_keys($parameters);
        $keys = array_map([$this, 'add_pads'], $keys);
        $html = str_replace($keys, array_values($parameters), $html);
        return preg_replace('/\#\w+\#/', '', $html);
    }

    public function get_element_template($parameters)
    {
        $result = false;
        if (isset($this->$parameters['element'])) {
            $parameters['content'] = gettype($parameters['content']) === 'array' ? $this->master($parameters['content']) : $parameters['content'];
            $result = $this->$parameters['element'];
            $result = $this->replace_data($result, $parameters);
        }
        return $result;
    }

}





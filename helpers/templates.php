<?php


class HelperTemplates
{
    static private $templatescampos = array();
    static private $formadecreacionnomodal;
    static private $formadeedicionnomodal;
    static private $formadebusqueda;
    static private $formadecreacion;
    static private $formadeedicion;
    static private $helpblock;


    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function getformato($formato, $campos)
    {
        $template = self::$templatescampos[$formato];
        foreach($campos as $key => $value) {
            $template = str_replace($key, $value, $template);
        }
        return $template;
    }


    public function gettemplate($formato, $id, $descripcion, $texto = "", $opciones = "", $id2 = "")
    {
        $template = "";
        if (array_key_exists($formato, self::$templatescampos)) {
            $template = self::$templatescampos[$formato];
            $template = str_replace('#DESCRIPCION#', $descripcion, $template);
            $textoayuda = empty($texto) ? "" : str_replace("#TEXTODEAYUDA#", $texto, self::$helpblock);
            $template = str_replace('#TEXTODEAYUDA#', $textoayuda, $template);
            if (!empty($opciones)) {
                $opciones = self::adecuaropciones($formato, $opciones);
                $template = str_replace('#OPCIONES#', $opciones, $template);
            }
            $template = str_replace('#ID#', $id, $template);
        }
        return $template;
    }

    public function getformadebusqueda($formato, $camposdebusqueda)
    {
        $template = self::$formadebusqueda;
        $template = str_replace('  #CAMPPOSDEBUSQUEDA#', $camposdebusqueda, $template);
        return $template;
    }

    public function getformadecreacion($formato, $camposdecreacion)
    {
        $template = $formato == "formadecreacion" ? self::$formadecreacion : self::$formadecreacionnomodal;
        $template = str_replace('  #CAMPPOSDECREACION#', $camposdecreacion, $template);
        return $template;
    }

    public function getformadeedicion($formato, $camposdecreacion)
    {
        $template = $formato == "formadeedicion" ? self::$formadeedicion : self::$formadeedicionnomodal;
        $template = str_replace('  #CAMPPOSDECREACION#', $camposdecreacion, $template);
        return $template;
    }


    public function getformadefacturacion($formato, $camposdecreacion,$id)
    {
        $template = self::$templatescampos['factura'];
        $template = str_replace('#CAMPPOSDECREACION#', $camposdecreacion, $template);
        $template = str_replace('#TIPO#', $id, $template);
        return $template;
    }



    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function __construct()
    {

        self::$helpblock = "<p class='help-block'>
                               <i class='fa fa-comments'></i>   #TEXTODEAYUDA#
                            </p>";

        $templatescampos['tabulador'] = <<<TABULADOR
   <div #DATA# style="display:none">
          <form role="form"  class='form-horizontal' #FORMA#>
             <div class="form-body">
                    <ul class="nav nav-tabs">
                        #TITULOS#
                    </ul>
                    <div class="tab-content">
                        #CONTENIDO#
                    </div>
             </div>
             <div class="form-actions">
			      <button type="button" class="btn btn-warning" #BTCANCELAR#>Cancelar</button>
			      <button type="button" class="btn btn-success"  #BTCREAR# >#TIPO# Ok</button>

	         </div>
          </form>
   </div>
TABULADOR;

        $templatescampos['files'] = <<<FILES
        <div class="alert alert-success margin-bottom-10"  >
        <i class="fa fa-warning"></i> Por favor seleccione los documentos
        <input type="hidden" id="#ID#" #DATA#>
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                                            </div>
                                            <div id="tab_images_uploader_container_#ID#" class="text-align-reverse margin-bottom-10">
                                                <a id="tab_images_uploader_pickfiles_#ID#" href="javascript:;" class="btn yellow">
                                                    <i class="fa fa-plus"></i> Selecccionar Documentos y/o Imagenes
                                                 </a>
                                                <a id="tab_images_uploader_uploadfiles_#ID#" href="javascript:;" class="btn green" style="display:none;">
                                                    <i class="fa fa-share"></i> Subir Archivos
                                                </a>
                                            </div>
                                            <div class="row">
                                                <div id="tab_images_uploader_filelist_#ID#" class="col-md-6 col-sm-12">
                                                </div>
                                            </div>

FILES;

        /*
        $templatescampos['contenidotabulador'] = <<<CONTENIDOTABULADOR
<div class="tab-pane" id="#ID#"  >
    <div class="form-body">
    #DESCRIPCION#
    </div>
</div>
CONTENIDOTABULADOR;
*/

        $templatescampos['countrylist'] = <<<COUNTRYLIST
<div class='form-group' id='grupo_#ID#'  >
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-4'>
        <select name='' id='#ID#' class='form-control select2' #DATA#>
            <option value='AF'>Afghanistan</option>
            <option value='AL'>Albania</option>
            <option value='DZ'>Algeria</option>
            <option value='AS'>American Samoa</option>
            <option value='AD'>Andorra</option>
            <option value='AO'>Angola</option>
            <option value='AI'>Anguilla</option>
            <option value='AQ'>Antarctica</option>
            <option value='AR'>Argentina</option>
            <option value='AM'>Armenia</option>
            <option value='AW'>Aruba</option>
            <option value='AU'>Australia</option>
            <option value='AT'>Austria</option>
            <option value='AZ'>Azerbaijan</option>
            <option value='BS'>Bahamas</option>
            <option value='BH'>Bahrain</option>
            <option value='BD'>Bangladesh</option>
            <option value='BB'>Barbados</option>
            <option value='BY'>Belarus</option>
            <option value='BE'>Belgium</option>
            <option value='BZ'>Belize</option>
            <option value='BJ'>Benin</option>
            <option value='BM'>Bermuda</option>
            <option value='BT'>Bhutan</option>
            <option value='BO'>Bolivia</option>
            <option value='BA'>Bosnia and Herzegowina</option>
            <option value='BW'>Botswana</option>
            <option value='BV'>Bouvet Island</option>
            <option value='BR'>Brazil</option>
            <option value='IO'>British Indian Ocean Territory</option>
            <option value='BN'>Brunei Darussalam</option>
            <option value='BG'>Bulgaria</option>
            <option value='BF'>Burkina Faso</option>
            <option value='BI'>Burundi</option>
            <option value='KH'>Cambodia</option>
            <option value='CM'>Cameroon</option>
            <option value='CA'>Canada</option>
            <option value='CV'>Cape Verde</option>
            <option value='KY'>Cayman Islands</option>
            <option value='CF'>Central African Republic</option>
            <option value='TD'>Chad</option>
            <option value='CL'>Chile</option>
            <option value='CN'>China</option>
            <option value='CX'>Christmas Island</option>
            <option value='CC'>Cocos (Keeling) Islands</option>
            <option value='CO'>Colombia</option>
            <option value='KM'>Comoros</option>
            <option value='CG'>Congo</option>
            <option value='CD'>Congo, the Democratic Republic of the</option>
            <option value='CK'>Cook Islands</option>
            <option value='CR'>Costa Rica</option>
            <option value='CI'>Cote dIvoire</option>
            <option value='HR'>Croatia (Hrvatska)</option>
            <option value='CU'>Cuba</option>
            <option value='CY'>Cyprus</option>
            <option value='CZ'>Czech Republic</option>
            <option value='DK'>Denmark</option>
            <option value='DJ'>Djibouti</option>
            <option value='DM'>Dominica</option>
            <option value='DO'>Dominican Republic</option>
            <option value='EC'>Ecuador</option>
            <option value='EG'>Egypt</option>
            <option value='SV'>El Salvador</option>
            <option value='GQ'>Equatorial Guinea</option>
            <option value='ER'>Eritrea</option>
            <option value='EE'>Estonia</option>
            <option value='ET'>Ethiopia</option>
            <option value='FK'>Falkland Islands (Malvinas)</option>
            <option value='FO'>Faroe Islands</option>
            <option value='FJ'>Fiji</option>
            <option value='FI'>Finland</option>
            <option value='FR'>France</option>
            <option value='GF'>French Guiana</option>
            <option value='PF'>French Polynesia</option>
            <option value='TF'>French Southern Territories</option>
            <option value='GA'>Gabon</option>
            <option value='GM'>Gambia</option>
            <option value='GE'>Georgia</option>
            <option value='DE'>Germany</option>
            <option value='GH'>Ghana</option>
            <option value='GI'>Gibraltar</option>
            <option value='GR'>Greece</option>
            <option value='GL'>Greenland</option>
            <option value='GD'>Grenada</option>
            <option value='GP'>Guadeloupe</option>
            <option value='GU'>Guam</option>
            <option value='GT'>Guatemala</option>
            <option value='GN'>Guinea</option>
            <option value='GW'>Guinea-Bissau</option>
            <option value='GY'>Guyana</option>
            <option value='HT'>Haiti</option>
            <option value='HM'>Heard and Mc Donald Islands</option>
            <option value='VA'>Holy See (Vatican City State)</option>
            <option value='HN'>Honduras</option>
            <option value='HK'>Hong Kong</option>
            <option value='HU'>Hungary</option>
            <option value='IS'>Iceland</option>
            <option value='IN'>India</option>
            <option value='ID'>Indonesia</option>
            <option value='IR'>Iran (Islamic Republic of)</option>
            <option value='IQ'>Iraq</option>
            <option value='IE'>Ireland</option>
            <option value='IL'>Israel</option>
            <option value='IT'>Italy</option>
            <option value='JM'>Jamaica</option>
            <option value='JP'>Japan</option>
            <option value='JO'>Jordan</option>
            <option value='KZ'>Kazakhstan</option>
            <option value='KE'>Kenya</option>
            <option value='KI'>Kiribati</option>
            <option value='KP'>Korea, Democratic Peoples Republic of</option>
            <option value='KR'>Korea, Republic of</option>
            <option value='KW'>Kuwait</option>
            <option value='KG'>Kyrgyzstan</option>
            <option value='LA'>Lao Peoples Democratic Republic</option>
            <option value='LV'>Latvia</option>
            <option value='LB'>Lebanon</option>
            <option value='LS'>Lesotho</option>
            <option value='LR'>Liberia</option>
            <option value='LY'>Libyan Arab Jamahiriya</option>
            <option value='LI'>Liechtenstein</option>
            <option value='LT'>Lithuania</option>
            <option value='LU'>Luxembourg</option>
            <option value='MO'>Macau</option>
            <option value='MK'>Macedonia, The Former Yugoslav Republic of</option>
            <option value='MG'>Madagascar</option>
            <option value='MW'>Malawi</option>
            <option value='MY'>Malaysia</option>
            <option value='MV'>Maldives</option>
            <option value='ML'>Mali</option>
            <option value='MT'>Malta</option>
            <option value='MH'>Marshall Islands</option>
            <option value='MQ'>Martinique</option>
            <option value='MR'>Mauritania</option>
            <option value='MU'>Mauritius</option>
            <option value='YT'>Mayotte</option>
            <option value='MX'>Mexico</option>
            <option value='FM'>Micronesia, Federated States of</option>
            <option value='MD'>Moldova, Republic of</option>
            <option value='MC'>Monaco</option>
            <option value='MN'>Mongolia</option>
            <option value='MS'>Montserrat</option>
            <option value='MA'>Morocco</option>
            <option value='MZ'>Mozambique</option>
            <option value='MM'>Myanmar</option>
            <option value='NA'>Namibia</option>
            <option value='NR'>Nauru</option>
            <option value='NP'>Nepal</option>
            <option value='NL'>Netherlands</option>
            <option value='AN'>Netherlands Antilles</option>
            <option value='NC'>New Caledonia</option>
            <option value='NZ'>New Zealand</option>
            <option value='NI'>Nicaragua</option>
            <option value='NE'>Niger</option>
            <option value='NG'>Nigeria</option>
            <option value='NU'>Niue</option>
            <option value='NF'>Norfolk Island</option>
            <option value='MP'>Northern Mariana Islands</option>
            <option value='NO'>Norway</option>
            <option value='OM'>Oman</option>
            <option value='PK'>Pakistan</option>
            <option value='PW'>Palau</option>
            <option value='PA'>Panama</option>
            <option value='PG'>Papua New Guinea</option>
            <option value='PY'>Paraguay</option>
            <option value='PE'>Peru</option>
            <option value='PH'>Philippines</option>
            <option value='PN'>Pitcairn</option>
            <option value='PL'>Poland</option>
            <option value='PT'>Portugal</option>
            <option value='PR'>Puerto Rico</option>
            <option value='QA'>Qatar</option>
            <option value='RE'>Reunion</option>
            <option value='RO'>Romania</option>
            <option value='RU'>Russian Federation</option>
            <option value='RW'>Rwanda</option>
            <option value='KN'>Saint Kitts and Nevis</option>
            <option value='LC'>Saint LUCIA</option>
            <option value='VC'>Saint Vincent and the Grenadines</option>
            <option value='WS'>Samoa</option>
            <option value='SM'>San Marino</option>
            <option value='ST'>Sao Tome and Principe</option>
            <option value='SA'>Saudi Arabia</option>
            <option value='SN'>Senegal</option>
            <option value='SC'>Seychelles</option>
            <option value='SL'>Sierra Leone</option>
            <option value='SG'>Singapore</option>
            <option value='SK'>Slovakia (Slovak Republic)</option>
            <option value='SI'>Slovenia</option>
            <option value='SB'>Solomon Islands</option>
            <option value='SO'>Somalia</option>
            <option value='ZA'>South Africa</option>
            <option value='GS'>South Georgia and the South Sandwich Islands</option>
            <option value='ES'>Spain</option>
            <option value='LK'>Sri Lanka</option>
            <option value='SH'>St. Helena</option>
            <option value='PM'>St. Pierre and Miquelon</option>
            <option value='SD'>Sudan</option>
            <option value='SR'>Suriname</option>
            <option value='SJ'>Svalbard and Jan Mayen Islands</option>
            <option value='SZ'>Swaziland</option>
            <option value='SE'>Sweden</option>
            <option value='CH'>Switzerland</option>
            <option value='SY'>Syrian Arab Republic</option>
            <option value='TW'>Taiwan, Province of China</option>
            <option value='TJ'>Tajikistan</option>
            <option value='TZ'>Tanzania, United Republic of</option>
            <option value='TH'>Thailand</option>
            <option value='TG'>Togo</option>
            <option value='TK'>Tokelau</option>
            <option value='TO'>Tonga</option>
            <option value='TT'>Trinidad and Tobago</option>
            <option value='TN'>Tunisia</option>
            <option value='TR'>Turkey</option>
            <option value='TM'>Turkmenistan</option>
            <option value='TC'>Turks and Caicos Islands</option>
            <option value='TV'>Tuvalu</option>
            <option value='UG'>Uganda</option>
            <option value='UA'>Ukraine</option>
            <option value='AE'>United Arab Emirates</option>
            <option value='GB'>United Kingdom</option>
            <option value='US'>United States</option>
            <option value='UM'>United States Minor Outlying Islands</option>
            <option value='UY'>Uruguay</option>
            <option value='UZ'>Uzbekistan</option>
            <option value='VU'>Vanuatu</option>
            <option value='VE'>Venezuela</option>
            <option value='VN'>Viet Nam</option>
            <option value='VG'>Virgin Islands (British)</option>
            <option value='VI'>Virgin Islands (U.S.)</option>
            <option value='WF'>Wallis and Futuna Islands</option>
            <option value='EH'>Western Sahara</option>
            <option value='YE'>Yemen</option>
            <option value='ZM'>Zambia</option>
            <option value='ZW'>Zimbabwe</option>
        </select>
        #TEXTODEAYUDA#

    </div>
</div>
COUNTRYLIST;

        $templatescampos['multiselectagrupado'] = <<<MULTISELECTAGRUPADO
<div class='form-group last'  >
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-9'>
        <select multiple='multiple' class='multi-select' id='#ID#' name='my_multi_select2[]' #DATA#>
            <optgroup label='NFC EAST'>
                <option>Dallas Cowboys</option>
                <option>New York Giants</option>
                <option>Philadelphia Eagles</option>
                <option>Washington Redskins</option>
            </optgroup>
            <optgroup label='NFC NORTH'>
                <option>Chicago Bears</option>
                <option>Detroit Lions</option>
                <option>Green Bay Packers</option>
                <option>Minnesota Vikings</option>
            </optgroup>
            <optgroup label='NFC SOUTH'>
                <option>Atlanta Falcons</option>
                <option>Carolina Panthers</option>
                <option>New Orleans Saints</option>
                <option>Tampa Bay Buccaneers</option>
            </optgroup>
            <optgroup label='NFC WEST'>
                <option>Arizona Cardinals</option>
                <option>St. Louis Rams</option>
                <option>San Francisco 49ers</option>
                <option>Seattle Seahawks</option>
            </optgroup>
        </select>
    </div>
</div>
MULTISELECTAGRUPADO;

        $formadebusqueda = <<<FORMADEBUSQUEDA
   <div id='buscar_modal' class='modal fade' role='dialog' aria-hidden='true'  #DATA#>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
                <h4 class='modal-title'>Buscar</h4>
            </div>
            <div class='modal-body form'>
                <form action='#' class='form-horizontal form-row-seperated' id="form_buscar">
                   #CAMPPOSDEBUSQUEDA#
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal' id="bt_form_buscar_limpiar">Inicializar</button>
                <button type='button' class='btn btn-primary' id="bt_form_buscar_cancelar"><i class='fa fa-check' ></i>Cancelar</button>
                <button type='button' class='btn btn-primary' id="bt_form_buscar_buscar"><i class='fa fa-check' ></i>Buscar</button>
            </div>
        </div>
    </div>
</div>
FORMADEBUSQUEDA;

        $formadecreacion = <<<FORMADECREACION
   <div id='myModal_crear' class='modal fade' role='dialog' aria-hidden='true'  #DATA#>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
                <h4 class='modal-title' id="tituloformacreacion" >Crear Nuevo</h4>
            </div>
            <div class='modal-body form'>
             <form action='#' class='form-horizontal form-row-seperated' id="form_crear">
                   #CAMPPOSDECREACION#
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-warning'  id="bt_form_crear_limpiar"><i class='fa fa-refresh' ></i>Limpiar</button>
                <button type='button' class='btn btn-warning' data-dismiss='modal' id="bt_form_crear_cancelar"><i class='fa fa-times' ></i>Cancelar</button>
                <button type='button' class='btn btn-success' id="bt_form_crear_crear"><i class='fa fa-check' ></i>Crear Nuevo</button>
            </div>
        </div>
    </div>
</div>
FORMADECREACION;

        $formadecreacionnomodal = <<<FORMADECREACIONNOMODAL
<!-- BEGIN PAGE CONTENT-->
<div class="row" id="myNoModal_crear" style="display: none">
<div class="col-md-12">
<form class="form-horizontal form-row-seperated" action="#" id="form_crear">
<div class="portlet">
<div class="portlet-title">
    <div class="caption">
        <i class="fa fa-shopping-cart"></i>Productos
    </div>
    <div class="actions btn-set">
        <button type="button" name="back" class="btn default" id="bt_form_crear_regresar"><i class="fa fa-angle-left" ></i> Regresar</button>
        <button type="button" class="btn default" id="bt_form_crear_limpiar"><i class="fa fa-reply" ></i> Limpiar</button>
        <button type='button' class='btn btn-success' id="bt_form_crear_crear"><i class='fa fa-edit' ></i>Guardar Nuevo</button>
        <div class="btn-group">
            <a class="btn yellow dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fa fa-share"></i> Mas <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="#">
                        Duplicar </a>
                </li>
                <li>
                    <a href="#">
                        Borrar</a>
                </li>
                <li class="divider">
                </li>
                <li>
                    <a href="#">
                        Imprimir </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="portlet-body">
<div class="tabbable">
<ul class="nav nav-tabs">
 #TITULOTABULADOR#
</ul>
<div class="tab-content no-space">


    #TABULADOR#


</div>
</div>
</div>
</div>
</form>
</div>
</div>
<!-- END PAGE CONTENT--
FORMADECREACIONNOMODAL;

        $formadeedicion = <<<FORMADEEDICION
   <div id='myModal_editar' class='modal fade' role='dialog' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
                <h4 class='modal-title' id="tituloformacreacion" >Editar</h4>
            </div>
            <div class='modal-body form'>
                <form action='#' class='form-horizontal form-row-seperated' id="form_edicion">
                   #CAMPPOSDECREACION#
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-warning' data-dismiss='modal' id="bt_form_editar_cancelar"><i class='fa fa-times' ></i>Cancelar</button>
                <button type='button' class='btn btn-success' id="bt_form_editar_crear"><i class='fa fa-edit' ></i>Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
FORMADEEDICION;

        $formadeedicionnomodal = <<<FORMADEEDICIONNOMODAL
<!-- BEGIN PAGE CONTENT-->
<div class="row" id="myNoModal_editar" style="display: none">
<div class="col-md-12">
<form class="form-horizontal form-row-seperated" action="#" id="form_edicion">
<div class="portlet">
<div class="portlet-title">
    <div class="caption">
        <i class="fa fa-shopping-cart"></i>Productos
    </div>
    <div class="actions btn-set">
        <button type="button" name="back" class="btn default" id="bt_form_editar_regresar"><i class="fa fa-angle-left" ></i> Regresar</button>
        <button type='button' class='btn btn-success' id="bt_form_editar_crear"><i class='fa fa-edit' ></i>Guardar Cambios</button>

        <div class="btn-group">
            <a class="btn yellow dropdown-toggle" href="#" data-toggle="dropdown">
                <i class="fa fa-share"></i> Mas <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu pull-right">
                <li>
                    <a href="#">
                        Duplicar </a>
                </li>
                <li>
                    <a href="#">
                        Borrar</a>
                </li>
                <li class="divider">
                </li>
                <li>
                    <a href="#">
                        Imprimir </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="portlet-body">
<div class="tabbable">
<ul class="nav nav-tabs">
 #TITULOTABULADOR#
</ul>
<div class="tab-content no-space">


    #TABULADOR#


</div>
</div>
</div>
</div>
</form>
</div>
</div>
<!-- END PAGE CONTENT-->
FORMADEEDICIONNOMODAL;

        /**
         * Datatable
         */
        $templatescampos['datatable'] = <<<DATATABLE
			<table class="table table-bordered table-hover" id='datatable_#ID#'  #DATA#>
											<thead>
											<tr role="row" class="heading" id='tr_#ID#'>

											</tr>
											</thead>

											</table>
DATATABLE;


        /**
         * Campos para forma de busqueda texto
         */
        $templatescampos['selectinputtext'] = <<<SELECTTEXT
      <div class='form-group'  >
           <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
           <div class="col-md-6">
										<div class="input-group" #DATA#>
											<div class="input-group-btn">
												<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-angle-down"></i></button>
												<ul class="dropdown-menu">
													<li>
														<a href="#">
														Contener </a>
													</li>
													<li>
														<a href="#">
														Igual a </a>
													</li>
													<li>
														<a href="#">
														Ser Mayor que </a>
													</li>
													<li>
														<a href="#">
														Ser Menor que </a>
													</li>
													<li>
														<a href="#">
														Empezar Por </a>
													</li>

												</ul>
											</div>
											<!-- /btn-group -->
											<input type="text" class="form-control">
										</div>
										  #TEXTODEAYUDA#
										<!-- /input-group -->
									</div>
       </div>


SELECTTEXT;


        /**
         * Campos para forma de busqueda numeric
         */
        $templatescampos['selectinputnumerico'] = <<<SELECTNUMERICO

           <div class='form-group'  >
           <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
           <div class="col-md-6">
										<div class="input-group" #DATA#>
											<div class="input-group-btn">
												<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fa fa-angle-down"></i></button>
                                                    <ul class='dropdown-menu' role='menu' >
                                                        <li>
                                                            <a href='#' >
                                                                Igual a
                                                             </a>
                                                            </li>
                        <li>
                            <a href='#'>
                                Ser Mayor que
                            </a>
                        </li>
                        <li>
                            <a href='#'>
                                Ser Menor que
                            </a>
                        </li>
                    </ul>
											</div>
											<!-- /btn-group -->
											<input type="text" class="form-control">
										</div>
										  #TEXTODEAYUDA#
										<!-- /input-group -->
									</div>
       </div>


SELECTNUMERICO;

        /**
         * Seleccionar un rango de fechas
         */
        $templatescampos['rangofechasavanzado'] = <<<RANGOFECHASAVANZADO
<div class='form-group' id='grupo_#ID#'  #DATA#>
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-8'>
        <div id='#ID#' class='btn default'>
            <i class='fa fa-calendar'></i>
            &nbsp; <span #DATA#></span>
            <b class='fa fa-angle-down'></b>
        </div>
        #TEXTODEAYUDA#
    </div>
</div>
RANGOFECHASAVANZADO;

        /**
         * Seleccionar un rango de fechas
         */
        $templatescampos['rangofechasavanzadofuturo'] = <<<RANGOFECHASAVANZADOFUTURO
<div class='form-group' id='grupo_#ID#' #DATA#>
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-8'>
        <div id='#ID#' class='btn default'>
            <i class='fa fa-calendar'></i>
            &nbsp; <span id='rangofechas_#ID#' ></span>
            <b class='fa fa-angle-down'></b>
        </div>
        #TEXTODEAYUDA#
    </div>
</div>
RANGOFECHASAVANZADOFUTURO;

        /**
         * Seleccionar una fecha y hora
         */
        $templatescampos['datetime'] = <<<DATETIME
<div class='form-group' id='grupo_#ID#'  #DATA#>
		<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
		<div class="col-md-6">
			<div id='#ID#'class="input-group date form_datetime"  data-date="2015-10-12" data-date-format="yyyy-mm-dd">
			   <input class="form-control form-control-inline input-medium date-picker" size="16" type="text" value="">
			</div>
			 #TEXTODEAYUDA#
		              <!-- /input-group -->
		</div>
</div>
DATETIME;

        /**
         * Seleccionar hora min seg
         */
        $templatescampos['timepicker'] = <<<TIMEPICKER
	<div class='form-group' id='grupo_#ID#'  #DATA#>
										<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" class="form-control timepicker", id="#ID#" #DATA#>
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
												</span>
											</div>
											#TEXTODEAYUDA#
										</div>
									</div>
TIMEPICKER;

        /**
         * Multiselect grafico
         */
        $templatescampos['multiselect'] = <<<MULTISELECT
    <div class='form-group' id='grupo_#ID#'  >
        <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
        <div class='col-md-9'>
            <select multiple='multiple' class='multi-select' id='#ID#' name='#ID#[]' #DATA#>

            </select>
             #TEXTODEAYUDA#
        </div>
    </div>

MULTISELECT;

        /**
         * Textarea
         */
        $templatescampos['textarea'] = <<<TEXAREA
<div class='form-group' id='grupo_#ID#'  >
    <label class='control-label col-md-3'><strong>#DESCRIPCION#</strong></label>
    <div class="col-md-6">
        <textarea class='form-control' rows='3' id='#ID#' #DATA#></textarea>
        #TEXTODEAYUDA#
     </div>
</div>
TEXAREA;

        /**
         * Email
         */
        $templatescampos['email'] = <<<EMAIL
<div class='form-group' id='grupo_#ID#'  >
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-6'>
          <div class='input-group'>
             <span class='input-group-addon'>
                  <i class='fa fa-envelope'></i>
             </span>
             <input id=#ID# type='text' class='form-control' placeholder='Email Address' #DATA#>
          </div>
          #TEXTODEAYUDA#
    </div>
</div>
EMAIL;

        /**
         * Password
         */
        $templatescampos['password'] = <<<PASSWORD
<div class='form-group' id='grupo_#ID#' >
    <label class='control-label col-md-3' for='#ID#'><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-6'>
        <div class='input-group'>
            <input type='password' class='form-control' id='#ID#' placeholder='Password' #DATA#>
            <span class='input-group-addon'>
                <i class='fa fa-user'></i>
            </span>
        </div>
        #TEXTODEAYUDA#
    </div>
</div>
PASSWORD;

        /**
         * Radio
         */
        $templatescampos['radio'] = <<<RADIO
<div class='form-group' id='grupo_#ID#'  >
    <label class='control-label col-md-3'><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-6'>
        <div class='radio-list'>
            #OPCIONES#
        </div>
         #TEXTODEAYUDA#
    </div>
</div>
RADIO;

        /**
         * Estatica
         */
        $templatescampos['estatica'] = <<<ESTATICA
<div class='form-group' id='grupo_#ID#'  >
     <label  class='control-label col-md-3'><strong>#DESCRIPCION#</strong></label>
     <div class='col-md-6'>
            <strong>
                <p class='form-control-static' id='#ID#' #DATA# >

                </p>
            </strong>
            #TEXTODEAYUDA#
     </div>
</div>
ESTATICA;

        $templatescampos['thumbnail'] = <<<THUMBNAIL
<div class='form-group' id='grupo_#ID#'  >
     <label  class='control-label col-md-3'><strong>#DESCRIPCION#</strong></label>
     <div class='col-md-6'>
            <strong>
                <p class='form-control-static' id='#ID#' #DATA#>

                </p>
            </strong>
            #TEXTODEAYUDA#
     </div>
</div>
THUMBNAIL;

        /**
         * Oculto
         */
        $templatescampos['oculto'] = <<<OCULTO
<div class='form-group' id='grupo_#ID#' style="display:none"  >
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
     <div class='col-md-6'>
        <input id='#ID#' type='text' class='form-control' #DATA#>
         #TEXTODEAYUDA#
     </div>
</div>
OCULTO;

        /**
         * Deshabilitado
         */
        $templatescampos['disabled'] = <<<DISABLED
<div class='form-group' id='grupo_#ID#'  >
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
     <div class='col-md-6'>
        <input id='#ID#' type='text' class='form-control' placeholder='Disabled' disabled #DATA#>
         #TEXTODEAYUDA#
     </div>
</div>
DISABLED;

        $templatescampos['checkbox'] = <<<CHECKBOX
<div class='form-group' id='grupo_#ID#'  #DATA#>
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-6'>
    <div class='checkbox-list'>
        #OPCIONES#
    </div>
    #TEXTODEAYUDA#
    </div>
</div>
CHECKBOX;

        $templatescampos['checkboxinline'] = <<<CHECKBOXINLINE
<div class='form-group' id='grupo_#ID#'  #DATA#>
    <label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-8'>
    <div class="checkbox-list">
        #OPCIONES#
    </div>
    </div>
</div>
CHECKBOXINLINE;

        $templatescampos['select'] = <<<SELECT
        <div class='form-group' id='grupo_#ID#' >
										<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
										<div class="col-md-6">
											<select id='#ID#' class="form-control input-medium select2me" data-placeholder="Seleccione #DESCRIPCION#" #DATA#>
											</select>
											#TEXTODEAYUDA#
										</div>
									</div>
SELECT;

        $templatescampos['selecthorizontal'] = <<<SELECT
        <div class="col-md-6" id="grupo_#ID#"  >
														<div class="form-group" >
															<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
															<div class="col-md-9">
																<select id='#ID#' class="form-control input-medium select2me" data-placeholder="Seleccione #DESCRIPCION#" #DATA#>
											                    </select>
																<span class="help-block">
																#TEXTODEAYUDA# </span>
															</div>
														</div>
													</div>

SELECT;

        $templatescampos['selectAll'] = <<<SELECT
        <div class='form-group' id='grupo_#ID#'>
										<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
										<div class="col-md-6">
											<select id='#ID#' class="form-control input-medium select2me" data-placeholder="Seleccione #DESCRIPCION#" #DATA#>
											</select>
											#TEXTODEAYUDA#
										</div>
									</div>
SELECT;

        /**
         * Password
         */
        $templatescampos['input'] = <<<INPUT
<div class='form-group' id='grupo_#ID#'  >
    <label class='control-label col-md-3' for='#ID#'><strong>#DESCRIPCION#</strong></label>
    <div class='col-md-6'>
        <div class='input-group input-medium'>
            <input  class='form-control' id='#ID#' placeholder='Escriba #DESCRIPCION#' #DATA#>
            <span class='input-group-addon'>
                <i class='fa fa-user'></i>
            </span>
        </div>
        #TEXTODEAYUDA#
    </div>
</div>
INPUT;

        $templatescampos['switchbox'] = <<<SWITCHBOX
        <div class='form-group' id='grupo_#ID#'  >
										<label class="control-label col-md-3"><strong>#DESCRIPCION#</strong></label>
										<div class="col-md-9">
											<input type="checkbox" id='#ID#' class="make-switch" data-size="normal" checked data-off-text="Inactivo" data-on-text="Activo" #DATA#>
                                 		#TEXTODEAYUDA#
                                 		</div>
		</div>
SWITCHBOX;

        $templatescampos['factura'] = <<<FACTURA
<div id="#TIPO#invoice" class="invoice " style="display: none" #DATA#>
        <div class="row invoice-logo visible-print ">
           <div style="height: 20px;">
           </div>
            <div class="col-xs-4 invoice-logo-space">
                <img id="#TIPO#_logoempresa" height="150" width="150" class="img-responsive" alt="" src='../media/com_certilab/assets/global/img/centrek.png'   />
            </div>
            <div class="col-xs-5">
                <p id='#TIPO#tipodocumento'>
                </p>
            </div>
            <div class="col-xs-3">
                <table>
                    <tr><td>Codigo:</td><td id="#TIPO#_forcod" ></td></tr>
                    <tr><td>Version:</td><td id="#TIPO#_forver" ></td></tr>
                    <tr><td>Vigencia:</td><td id="#TIPO#_forvig" ></td></tr>
                </table>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class='row visible-print'>
                <div class="visible-print datosl" style="display:none;">
                    <table id="#TIPO#_imprimir" class="table">
                        <tr >
                            <td id="nrocotizacion">

                            </td>
                        </tr>
                        <tr>
                            <td >
                                <br />
                                <p>Señores</p>
                                <p>#CLIENTE#</p>
                                <p></p>
                                <p>#REFERENCIA#</p>
                                <br />
                                <p id=#TIPO#_Saludo>Cordial Saludo.</p>
                                <p id='#TIPO#_TextoEncabezado'>
                                    Atendiendo su solicitud, nos permitimos presentar la cotización de servicios y/o ensayos para las muestras de
                                    su interés, así:
                                </p>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <div class="row">

            <form action='#' role="form" class="form-horizontal hidden-print" id='#TIPO#forma'>
                        #FORMAENCABEZADOS#
             </form>
        </div>
        <div class="row">
            <div class="actions btn-set">
                <button type="button" name="back" class="btn default hidden-print" #BTCANCELAR#><i class="fa fa-angle-left"></i> Regresar</button>
                <button type="button" class="btn default hidden-print" #BTCREAR#><i class="fa fa-reply"></i> Nuevo Item</button>
            </div>
        </div>
        <div style="height: 20px;">
        </div>
        <div class="row" id="#TIPO#gifspinner" style="display: none;" >
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
            </div>
           <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
               <div><img class='img-responsive img-center' src="../media/com_certilab/assets/admin/layout/img/loading-spinner-default.gif" id="loading-indicator"  /></div>
           </div>
        </div>
        <div class="row" id="#TIPO#tabladedatos">
           <div class="col-xs-12">
               <table class="table table-striped table-hover" id="#TIPO#_dtsecundaria">
                    <thead>
                        <tr id='#TIPO#thdetalle'>
                            #HEADERTABLA#
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="well visible-print" id="#TIPO#_dataempresa">
                <strong>Tiempo de entrega de resultados</strong><br>
                <strong id='#TIPO#diashabiles'></strong> días hábiles a partir de la recepción de las muestras.<br>
                <strong>Forma de pago.</strong>
                <ul>
                <li id="#TIPO#imprimircondicionesdepago">100 % pago anticipado .</li>
                <li>La compañía contratante debe cancelar el IVA (16%).</li>
                <li> Favor no hacer Retención en la Fuente, CENTREK es una entidad sin ánimo delucro.</li>
                <li>Favor realizar el respectivo pago a nombre de CENTREK, en: BANCOLOMBIA cuenta corriente No 304701854-45.</li>
                <ul>

    </div>
            </div>
            <div class="col-xs-4 invoice-block">
                <ul class="list-unstyled amounts" id="#TIPO#_totales"></ul>
                <br />
                <p id = "#TIPO#firma"><p>
                <br />
                <a class="btn btn-lg blue hidden-print margin-bottom-5" data-facturacion-accion="imprimir" id="bt_#TIPO#_imprimir">
                    Imprimir <i class="fa fa-print"></i>
                </a>
                <a class="btn btn-lg green hidden-print margin-bottom-5 " #GUARDAR# id="#TIPO#_documento">
                    #ACCION# #DOCUMENTO# <i class="fa fa-check"></i>
                </a>

            </div>
        </div>
    </div>


    </table
    <table>



FACTURA;


/*
        $templatescampos['factura'] = <<<FACTURA
<div class="invoice "  style="display: none" #DATA#   >
<div class="row invoice-logo visible-print">
    <div class="col-xs-6 invoice-logo-space">
        <img id="#TIPO#_logoempresa"  height="150" width="150" class="img-responsive" alt=""/>
    </div>
    <div class="col-xs-6">
        <p>
         <h3>
            #TIPO# #DOCUMENTO#
         </h3>   <span class="muted" id="#TIPO#_texto">

                        </span>
        </p>
    </div>
</div>
<hr>

<div class='row'>
 <div class="visible-print" style="display:none;">
     <table id="#TIPO#_imprimir"  class="table" >
          <tr>
            <td class="datosr">Empresa :</td> <td class="datosl" ><strong>#EMPRESA#</strong></td>
            <td class="datosr">Cliente :</td>  <td class="datosl"><strong>#CLIENTE#</strong></td>
        </tr>
        <tr>
            <td class="datosr">Obra :</td> <td class="datosl"><strong>#OBRA#</strong></td>
            <td class="datosr">Localidad :</td> <td class="datos"><strong>#LOCALIDAD#</strong></td>
       </tr>
       <tr>
            <td></td><td class="datosr" >Destino: </td>
            <td class="datosl"><strong>#DESTINO#</strong></td><td></td>
       </tr>
  </table>
</div>
</div>
<div class="row"  >

   <form action='#' role="form" class="form-horizontal hidden-print" >
        #FORMAENCABEZADOS#
   </form>
</div>
<div class="row">
   <div class="actions btn-set">
        <button type="button" name="back" class="btn default hidden-print" #BTCANCELAR#><i class="fa fa-angle-left" ></i> Regresar</button>
        <button type="button" class="btn default hidden-print" #BTCREAR# ><i class="fa fa-reply" ></i> Nuevo Item</button>
    </div>
</div>
<div style="height: 20px;">
</div>
<div class="row">
    <div class="col-xs-12">
        <table class="table table-striped table-hover" id="#TIPO#_dtsecundaria">
            <thead>
            <tr id=thdetalle>
                #HEADERTABLA#
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-xs-4">
        <div class="well" id="#TIPO#_dataempresa">
        </div>
    </div>
    <div class="col-xs-8 invoice-block">
        <ul class="list-unstyled amounts" id="#TIPO#_totales">
           </ul>
        <br/>
        <a class="btn btn-lg blue hidden-print margin-bottom-5"  data-facturacion-accion="imprimir">
            Imprimir <i class="fa fa-print"></i>
        </a>
        <a class="btn btn-lg green hidden-print margin-bottom-5 " #GUARDAR# id="#TIPO#_documento">
            #ACCION# #DOCUMENTO# <i class="fa fa-check"></i>
        </a>

    </div>
</div>
</div>
FACTURA;
*/
        $templatescampos['modal']  = <<<FORMAMODAL
   <div id='#TIPO#_modal' class='modal fade' role='dialog' aria-hidden='true' #DATA#>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true' #BTCANCELAR#></button>
                <h4 class='modal-title' id="tituloformacreacion" >#TIPO# #DOCUMENTO#</h4>
            </div>
             <form action='#' class='form-horizontal form-row-seperated' #FORMA#>
                    <div class='modal-body form'>
                        #FORMACAMPOSMODAL#
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-warning' data-dismiss='modal' #BTCANCELAR#><i #BTCANCELAR# class='fa fa-times' ></i>Cancelar</button>
                        <button type='button' class='btn btn-success' #BTCREAR#><i #BTCREAR# class='fa fa-check' ></i>#ACCION# #DOCUMENTO# Ok</button>
                    </div>
              </form>
        </div>
    </div>
</div>
FORMAMODAL;

        self::$formadecreacionnomodal = $formadecreacionnomodal;
        self::$formadeedicionnomodal = $formadeedicionnomodal;
        self::$templatescampos = $templatescampos;
        self::$formadebusqueda = $formadebusqueda;
        self::$formadecreacion = $formadecreacion;
        self::$formadeedicion = $formadeedicion;

    }

    // Funciones usadas para recuperar valores (Armar el WHERE) en el modelo listar

    public function getValueSelectinputtext($campo, $valores)
    {
        $resultado = '';
        $db = JFactory::getDbo();
        switch (trim($valores[2])) {
            case "Contener" :
                $resultado = "{$campo} LIKE '%" . $db->escape($valores[1], true) . "%'";
                break;
            case "Ser Mayor que" :
                $resultado = "{$campo} > " . $db->quote($valores[1]);
                break;
            case  "Ser Menor que" :
                $resultado = "{$campo} < " . $db->quote($valores[1]);
                break;
            case  "Igual a" :
                $resultado = "{$campo} = " . $db->quote($valores[1]);
                break;
            case "Empezar Por"  :
                $resultado = "{$campo} LIKE '" . $db->escape($valores[1], true) . "%'";
                break;
        }
        return $resultado;
    }

    public function getValueSelectinputnumerico($campo, $valores)
    {
        $resultado = '';
        $db = JFactory::getDbo();
        switch (trim($valores[2])) {
            case "Ser Mayor que" :
                $resultado = "{$campo} > " . (float)($valores[1]);
                break;
            case  "Ser Menor que" :
                $resultado = "{$campo} < " . (float)($valores[1]);
                break;
            case  "Igual a" :
                $resultado = "{$campo} = " . (float)($valores[1]);
                break;
        }
        return $resultado;
    }

    public function getValueSwitchbox($campo, $valores)
    {
        return "{$campo} = " . (int)($valores[1]);
    }

    public function getValueCheckboxinline($campo, $valores)
    {
        $db = JFactory::getDbo();
        $resultado = array();
        $valoreschkbox = explode(",", $valores[1]);
        foreach ($valoreschkbox as $valor) {
            $resultado[] = "{$campo} LIKE '%" . $db->escape($valor, true) . "%'";
        }
        switch (trim($valores[2])) {
            case "AND" :
                $resultado = implode(' AND ', $resultado);
                break;
            case  "OR" :
                $resultado = implode(' OR ', $resultado);
                break;
        }
        return $resultado;
    }

    public function getValueRangofechasavanzadofuturo($campo, $valores)
    {
        $db = JFactory::getDbo();
        $resultado = "";
        $fecha = explode("-", $valores[1]);
        $campofinal = str_replace('inicial', 'final', $campo);
        $fechai = $campo . " <= " . $db->quote(str_replace('/', '-', $fecha[0] . ' 00:00:00'), true);
        $fechaf = $campofinal . " >= " . $db->quote(str_replace('/', '-', $fecha[1] . ' 00:00:00'), true);
        return $fechai . ' AND ' . $fechaf;
    }

    private function adecuaropciones($formato, $opciones)
    {
        $listaopciones = array();
        switch ($formato) {
            case "checkbox" :
                $opciones = explode(",", $opciones);
                foreach ($opciones as $opcion) {
                    $opcion = trim($opcion);
                    $listaopciones[] = "<label>
                                                            <input type='checkbox' name='#ID#[]' value='{$opcion}'>
                                                            {$opcion}
                                                        </label>";
                }
                break;
            case "checkboxinline" :
                $opciones = explode(",", $opciones);
                $i = 0;
                foreach ($opciones as $opcion) {
                    $opcion = trim($opcion);
                    $listaopciones[] = "<label class='checkbox-inline'>
                                                <input type='checkbox' name='#ID#[]' value='{$opcion}'>
                                                {$opcion}
                                            </label>";
                    $i++;
                }
                break;
            case "radio" :
                $opciones = explode(",", $opciones);
                $i = 0;
                foreach ($opciones as $opcion) {
                    $opcion = trim($opcion);
                    $listaopciones[] = "<label>
                                            <input type='radio' name='#ID#' value='{$opcion}' checked>
                                            {$opcion}
                                       </label>";
                    $i++;
                }
                break;
        }
        return implode("\n\t\t", $listaopciones);
    }


}


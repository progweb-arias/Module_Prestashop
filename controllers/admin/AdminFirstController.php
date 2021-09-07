<?php

use OrbitaDigital\OdFirst\Resources;

class AdminFirstController extends ModuleAdminController
{
    public $iconCheck;
    public $iconClose;
    public function __construct()
    {
        $this->iconCheck = 'check';
        $this->iconClose = 'close';
        $this->bootstrap = true;
        parent::__construct();
    }

    public function procesarDatos($action)
    {
        $resources = new Resources();
        return $resources->$action(
            Tools::getValue('nombre', ' '),
            Tools::getValue('edad', ' '),
            Tools::getValue('fecha', ' ')
        );
    }
    public function ajaxProcessValidate()
    {
        $action = 'validate';
        $resultados = $this->procesarDatos($action);
        die(json_encode($resultados));
    }
    public function ajaxProcessSave()
    {
        $action = 'save_validate';
        $resultados = $this->procesarDatos($action);
        die(json_encode($resultados));
    }
    public function ajaxProcessUpdate()
    {
        $resources = new Resources();
        $resultados = $resources->update((int)Tools::getValue('id', 0));
        die(json_encode($resultados));
    }
    public function ajaxProcessDelete()
    {
        $resources = new Resources();
        $resultados = $resources->delete((int)Tools::getValue('id', 0));
        die(json_encode($resultados));
    }

    public function form()
    {
        $form = new HelperForm();
        $formulario = [
            [
                'form' => [
                    'input' => [
                        [
                            'label' => 'Nombre: ',
                            'type' => 'text',
                            'name' => 'nombre',
                            'class' => 'name',
                        ],
                        [
                            'label' => 'Edad: ',
                            'type' => 'text',
                            'name' => 'edad',
                            'class' => 'age',
                        ],
                        [
                            'label' => 'Fecha: ',
                            'type' => 'date',
                            'name' => 'date',
                            'class' => 'data',
                        ],

                    ],
                    'buttons' => [
                        'button1' =>
                        [
                            'title' => $this->l('Validar'),
                            'class' => 'btn btn-primary',
                            'name' => 'enviar',
                            'id' => 'validar',
                        ],
                        'button2' =>
                        [
                            'title' => $this->l('Save'),
                            'class' => 'btn btn-primary',
                            'name' => 'enviar',
                            'id' => 'guardar',
                        ],
                    ],

                ],
            ],
        ];
        return $form->generateForm($formulario);
    }
    public function dataQuery($word, $data)
    {
        $where = [];
        if (!empty($data[0])) {
            $where[] = "$word >= '$data[0]'";
        }
        if (!empty($data[1])) {
            $where[] = "$word <= '$data[1]'";
        }
        return $where;
    }
    public function pagination()
    {
        $page = Tools::getValue('submitFilterod_first_formulario', 1);
        $pagination = Tools::getValue('od_first_formulario_pagination', 20);
        $inicio = 0;
        if ($page > 1) {
            $inicio = ($page * $pagination) - $pagination;
        }
        $limit = " LIMIT $inicio,$pagination";
        return $limit;
    }
    public function getQuery()
    {
        $where = [];
        foreach ($_POST as $key => $i) {
            if (strpos($key, 'local') === 0) {
                $palabra = str_replace('local_od_first_formularioFilter_', '', $key);
                foreach ($this->dataQuery($palabra, $i) as $key => $i) {
                    $where[] = "$i";
                }
                continue;
            }
            if (strpos($key, 'od_first_formularioFilter') === 0) {
                $palabra = str_replace('od_first_formularioFilter_', '', $key);
                if (strpos($palabra, 'fecha') || empty(trim($i))) {
                    continue;
                }
                if ($palabra == 'nombre') {
                    $where[] =  "$palabra LIKE '$i%'";
                } else {
                    $where[] = "$palabra = '$i'";
                }
            }
        }
        $result = 'SELECT * FROM ' .  _DB_PREFIX_ . 'od_first_formulario ';
        if (count($where) > 0) {
            $result .= 'WHERE ';
        }
        $result .= implode(' AND ', $where);
        $_POST['od_first_formularioFilter_fecha'] = $_POST['local_od_first_formularioFilter_fecha'];
        $_POST['od_first_formularioFilter_fecha_creacion'] = $_POST['local_od_first_formularioFilter_fecha_creacion'];
        $_POST['od_first_formularioFilter_fecha_modificacion'] = $_POST['local_od_first_formularioFilter_fecha_modificacion'];
        $_POST['od_first_formularioFilter_fecha_borrado'] = $_POST['local_od_first_formularioFilter_fecha_borrado'];
        return $result;
    }
    public function getIcons($value)
    {
        $this->context->smarty->assign(
            [
                'icono' => $value,
                'enabled' => $this->iconCheck,
                'disabled' => $this->iconClose,
            ]
        );
        return $this->context->smarty->fetch('file:C:/xampp/htdocs/prestashop/modules/od_first/views/templates/admin/icons.tpl');
    }
    public function deleteLine($nombre)
    {
        $resources = new Resources();
        return $resources->delete($nombre);
    }

    public function list()
    {
        $list = new HelperList();
        $list->orderBy = 'id';
        $list->orderWay = 'asc';
        if (Tools::getValue('od_first_formularioOrderby')) {
            $list->orderBy = Tools::getValue('od_first_formularioOrderby');
            $list->orderWay = Tools::getValue('od_first_formularioOrderway');
        }
        $sentence = " ORDER BY $list->orderBy $list->orderWay";
        $list->simple_header = false;
        $list->shopLinkType = '';
        $list->no_link = true;
        $list->_pagination = [1, 5, 10, 20, 50];
        $list->_default_pagination = 20;
        $list->token = Tools::getAdminTokenLite('AdminFirst');
        $list->identifier = 'id';
        $list->table = 'od_first_formulario';
        $list->currentIndex = AdminController::$currentIndex;


        $fields_list = [
            'id' => [
                'title' => $this->l('ID'),
                'type' => 'text',
                'search' => false,
            ],
            'nombre' => [
                'title' => $this->l('Nombre'),
                'width' => 140,
                'type' => 'text',
            ],
            'edad' => [
                'title' => $this->l('Edad'),
                'width' => 140,
                'type' => 'number',
            ],
            'fecha' => [
                'title' => $this->l('Fecha'),
                'width' => 140,
                'type' => 'date',
            ],
            'fecha_creacion' => [
                'title' => $this->l('Fecha de creacion'),
                'width' => 140,
                'type' => 'datetime',
            ],
            'fecha_modificacion' => [
                'title' => $this->l('Fecha de modificacion'),
                'width' => 140,
                'type' => 'datetime',
            ],
            'borrado' => [
                'title' => $this->l('Borrado'),
                'width' => 140,
                'type' => 'number',
                'search' => false,
                'callback' => 'getIcons',
            ],
            'fecha_borrado' => [
                'title' => $this->l('Fecha de borrado'),
                'width' => 140,
                'type' => 'datetime',
            ],
        ];
        if (Tools::isSubmit('submitResetod_first_formulario')) {
            $this->processResetFilters($fields_list);
        }
        $total = Db::getInstance()->executeS($this->getQuery());
        $resultado = Db::getInstance()->executeS($this->getQuery() . $sentence . $this->pagination());
        $list->listTotal = count($total);
        return $list->generateList($resultado, $fields_list);
    }
    public function initContent()
    {
        $check = 0;
        if (Tools::isSubmit('submitFilterod_first_formulario') || Tools::getValue('od_first_formularioOrderby')) {
            $check = 1;
        }
        $formulario_show = $this->form();
        $table_show = $this->list();

        $this->context->smarty->assign(
            [
                'check' => $check,
                'formulario' => $formulario_show,
                'lista' => $table_show,
            ]
        );
        $tpl = $this->context->smarty->fetch('file:C:/xampp/htdocs/prestashop/modules/od_first/views/templates/admin/config.tpl');
        $this->context->smarty->assign(
            [
                'content' => $tpl
            ]
        );
    }
}

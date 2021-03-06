<?php

declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}


use OrbitaDigital\OdFirst\Resources;

require_once __DIR__ . '/vendor/autoload.php';

class od_first extends Module
{
    public function __construct()
    {
        $this->name = 'od_first';
        $this->author = 'AlejandroA';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => '1.7.99'];
        $this->bootstrap = true;

        parent::__construct();

        $this->description = $this->l('My first module');
        $this->displayName = $this->l('Module Easy');
        $this->confirmUninstall = $this->l('Do you want delete this module?');
    }

    public function install()
    {
        return
            Resources::installTable()
            && Configuration::updateValue('Nombre', ' ')
            && Configuration::updateValue('Edad', ' ')
            && Configuration::updateValue('Fecha', ' ')
            && parent::install()
            && $this->registerHook('actionAdminControllerSetMedia');
    }

    public function uninstall()
    {
        return Resources::uninstallTable()
            && Configuration::deleteByName('Nombre')
            && Configuration::deleteByName('Edad')
            && Configuration::deleteByName('Fecha')
            && $this->unregisterHook('actionAdminControllerSetMedia')
            && parent::uninstall();
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminFirst'));
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ('AdminFirst' === Tools::getValue('controller')) {
            $end_point = $this->context->link->getAdminLink('AdminFirst');
            Media::addJsDef(['od_module' => ['end_point' => $end_point]]);
            $this->context->controller->addJS(_MODULE_DIR_ . 'od_first/views/js/od_first.js');
            $this->context->controller->addCss(_MODULE_DIR_ . 'od_first/views/css/od_first.css');
        }
    }
}

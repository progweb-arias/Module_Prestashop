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
        $this->tab = 'front_office_features';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => '1.7.99'];
        $this->bootstrap = true;

        parent::__construct();

        $this->description = $this->l('My first module');
        $this->displayName = $this->l('Module Easy');
        $this->confirmUninstall = $this->l('Do you want delete this module?');
    }
    public function installDb()
    {
        $resources = new Resources();
        return $resources->installTable();
    }
    public function install()
    {
        return parent::install()
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->installDb()
            && Configuration::updateValue('Nombre', ' ')
            && Configuration::updateValue('Edad', ' ')
            && Configuration::updateValue('Fecha', ' ');
    }
    public function uninstallDb()
    {
        $resources = new Resources();
        return $resources->uninstallTable();
    }
    public function uninstall()
    {
        return parent::uninstall()
            && $this->unregisterHook('actionAdminControllerSetMedia')
            && $this->uninstallDb()
            && Configuration::deleteByName('Nombre')
            && Configuration::deleteByName('Edad')
            && Configuration::deleteByName('Fecha');
    }
    public function getContent()
    {
        tools::redirectAdmin($this->context->link->getAdminLink('AdminFirst'));
    }
}

<?php

declare(strict_types=1);

namespace OrbitaDigital\OdFirst;

use Db;

class Resources
{
    /**
     * Funcion validar campos
     * @param string $nombre 
     * @param string $edad
     * @param string $fecha 
     * 
     * @return array      
     */
    public function validate(string $nombre, string $edad, string $fecha)
    {
        $resultados = ["correcto" => [], "error" => []];
        $cadena = ["nombre" => $nombre, "edad" => $edad, "date" => $fecha];
        foreach ($cadena as $key => $i) {
            if (empty(trim($i))) {
                $resultados["error"][] = $key;
            } else {
                $resultados["correcto"][] = $key;
            }
        }
        return $resultados;
    }

    /**
     * Funcion de validar y guardar los datos en una tabla
     * @param string $nombre
     * @param string $edad  
     * @param string $fecha
     * @return bool
     */
    public function save_validate(string $nombre, string $edad, string $fecha)
    {
        $variable = $this->validate($nombre, $edad, $fecha);
        if (count($variable['correcto']) == 3) {
            $fecha2 = date('Y-m-d', (int)strtotime($fecha));
            return Db::getInstance()->execute("INSERT INTO " . _DB_PREFIX_ . "od_first_formulario(nombre,edad,fecha,fecha_creacion,fecha_modificacion,borrado,fecha_borrado) VALUES('$nombre',$edad,'$fecha2',NOW(),NOW(), 0, NULL)");
        }
        return $variable;
    }
    /**
     * Funcion de actualizar campo borrado en la tabla
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id)
    {
        if ($id > 0) {
            return Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "od_first_formulario SET fecha_modificacion = NOW() , borrado=1 , fecha_borrado=NOW() WHERE id= $id");
        }
        return [];
    }
    /**
     * Funcion mostrar tabla
     * @param string $nombre
     * @param string $fecha_desde
     * @param string $fecha_hasta
     * @param string $borrado es la opcion de on y off de si esta borrado
     * @param string $fecha_eleccion
     * @param string $pagina que son los botones de las paginas
     * 
     * @return array
     */
    public function search(string $nombre, string $fecha_desde, string $fecha_hasta, string $borrado, string $fecha_eleccion, string $pagina)
    {
        $array = ['Nombre' => $nombre, 'Fecha_creacion' => $fecha_desde, 'Fecha_creacion2' => $fecha_hasta, 'deleted' => $borrado];
        $query = "SELECT * FROM "  . _DB_PREFIX_ .  "formulario WHERE ";
        $where = [];
        $rows = 5;
        $page = intval($pagina);
        $limit = ($page * $rows);
        $offset = $rows;
        foreach ($array as $columna => $valor) {
            if ($columna == 'Nombre') {
                if (!empty(trim($valor))) {
                    $where[] =  "$columna LIKE '$valor%'";
                }
            } elseif (strpos($columna, 'Fecha')) {
                if (!empty(trim($valor))) {
                    $valor = date('Y-m-d', strtotime($valor));
                    if (!strpos($columna, 'creacion')) {
                        $where[] = "$fecha_eleccion < '$valor'";
                    }
                    $where[] = "$fecha_eleccion > '$valor'";
                }
            } else {
                if (!$valor) {
                    $where[] = "$columna=0";
                }
                $where[] = "$columna=1";
            }
        }
        $query .= implode(' AND ', $where);
        if (Db::getInstance()->executeS($query)) {
            $numero_registros = count(Db::getInstance()->executeS($query));
        }
        $query .= ' LIMIT ' . $limit . ',' . $offset;
        return ["datos" => Db::getInstance()->executeS($query), "contador" => $numero_registros];
    }

    /**
     * Funcion actualizar campo borrado de la tabla de 1 a 0
     * @param int $id
     * 
     * @return bool
     */
    public function update(int $id)
    {
        if ($id > 0) {
            return Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "od_first_formulario SET fecha_modificacion = NOW(), borrado= 0 , fecha_borrado= NULL WHERE id= $id");
        }
        return [];
    }
    /**
     * Funcion instalar tabla
     * 
     * @return bool
     */
    public static function installTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'od_first_formulario`(`id` INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `nombre` VARCHAR(25) NOT NULL, `edad` INT NOT NULL, `fecha` Date NOT NULL, `fecha_creacion` datetime NOT NULL,`fecha_modificacion` datetime NOT NULL,`borrado` bool NOT NULL,`fecha_borrado` datetime NULL) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;');
    }
    /**
     * Funcion desinstalar tabla
     * 
     * @return bool
     */
    public static function uninstallTable()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'od_first_formulario`');;
    }
}

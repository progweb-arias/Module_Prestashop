<?php

declare(strict_types=1);

namespace OrbitaDigital\OdFirst;

use Db;

// TODO esto que es y para que lo usas?
class Resources
{
    /**
     * Funcion validar campos
     * @param string $texto 
     * @param int $numero
     * @param string $fecha
     * 
     * @return array      
     */
    public function validate(string $nombre, string $edad, string $date)
    {
        $resultados = ["correcto" => [], "error" => []];
        $cadena = array("nombre" => $nombre, "edad" => $edad, "date" => $date);
        foreach ($cadena as $key => $i) {
            if (empty(trim($cadena[$key]))) {
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
     * @param int $edad  
     * @param string $fecha
     * @param string $borrado
     * @return bool
     */
    public function save_validate(string $nombre, string $edad, string $fecha)
    {
        $variable = $this->validate($nombre, $edad, $fecha);
        if (count($variable['correcto']) == 3) {
            $fecha = date('Y-m-d', strtotime($fecha));
            return Db::getInstance()->execute("INSERT INTO " . _DB_PREFIX_ . "od_first_formulario(nombre,edad,fecha,fecha_creacion,fecha_modificacion,borrado,fecha_borrado) VALUES('$nombre',$edad,'$fecha',NOW(),NOW(), 0, NULL)");
        }
        return $variable;
    }
    /**
     * Funcion de borrar en la tabla
     * @param string $nombre
     * 
     * @return bool
     */
    public function delete(string $nombre)
    {
        $array = [];
        if (count(Db::getInstance()->executeS("SELECT id FROM " . _DB_PREFIX_ . "od_first_formulario WHERE id='$nombre'"))) {
            return Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "od_first_formulario SET fecha_modificacion=NOW() , borrado=1 , fecha_borrado=NOW() WHERE id='$nombre'");
        } else {
            return $array;
        }
    }
    /**
     * Funcion mostrar tabla
     * @param string $nombre
     * @param string $fecha_desde
     * @param string $fecha_hasta
     * @param string $borrado es la opcion de on y off de si esta borrado
     * @param string $fecha_eleccion
     * @param string $boton1 que son los botones de las paginas
     * 
     * @return array
     */
    public function search(string $nombre, string $fecha_desde, string $fecha_hasta, string $borrado, string $fecha_eleccion, string $boton1)
    {
        $array = array('Nombre' => $nombre, 'Fecha_creacion' => $fecha_desde, 'Fecha_creacion2' => $fecha_hasta, 'deleted' => $borrado);
        $query = "SELECT * FROM "  . _DB_PREFIX_ .  "formulario WHERE ";
        $query2 = "SELECT * FROM " . _DB_PREFIX_ . "formulario WHERE ";
        $where = [];
        $rows = 5;
        // echo $fecha_eleccion;
        $page = intval($boton1);
        $limit = ($page * $rows);
        $offset = $rows;
        // echo $fecha_eleccion;
        foreach ($array as $columna => $valor) {
            if ($columna == 'Nombre') {
                if (!empty(trim($valor))) {
                    $where[] =  "$columna LIKE '$valor%'";
                }
            } elseif ($columna == 'Fecha_creacion') {
                if (!empty(trim($valor))) {
                    $valor = date('Y-m-d', strtotime($fecha_desde));
                    $where[] = "$fecha_eleccion > '$valor'";
                }
            } elseif ($columna == 'Fecha_creacion2') {
                if (!empty(trim($valor))) {
                    $valor = date('Y-m-d', strtotime($fecha_hasta));
                    $where[] = "$fecha_eleccion < '$valor'";
                }
            } else {
                if ($valor == 'true') {
                    $where[] = "$columna=1";
                } else {
                    $where[] = "$columna=0";
                }
            }
            continue;
        }
        $query2 .= implode(' AND ', $where);
        $numero_registros = count(Db::getInstance()->executeS($query2));
        // echo $query2;
        $query .= implode(' AND ', $where);
        $query .= ' LIMIT ' . $limit . ',' . $offset;
        // echo $query;
        return ["datos" => Db::getInstance()->executeS($query), "contador" => $numero_registros];
    }

    // TODO no tiene explicacion
    /**
     * Funcion actualizar campos
     * @param string $nombre
     * @param int $edad
     * @param string $fecha
     * @param string $fecha_desde
     * 
     * @return bool
     */
    public function update(int $id)
    {
        $array = array("id" => $id);
        foreach ($array as $variables) {
            if ($variables == 0) {
                return $array;
            }
        }
        return Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "od_first_formulario SET borrado='0', fecha_borrado= NULL WHERE id= $id");
    }
    public function update2(int $id)
    {
        $array = array("id" => $id);
        foreach ($array as $variables) {
            if ($variables == 0) {
                return $array;
            }
        }
        return Db::getInstance()->execute("UPDATE " . _DB_PREFIX_ . "od_first_formulario SET borrado='1', fecha_borrado= NOW() WHERE id= $id");
    }

    /**
     * Funcion instalar tabla
     * 
     * @return bool
     */
    public function installTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'od_first_formulario`(`id` INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `nombre` VARCHAR(25) NOT NULL, `edad` INT NOT NULL, `fecha` Date NOT NULL, `fecha_creacion` datetime NOT NULL,`fecha_modificacion` datetime NOT NULL,`borrado` bool NOT NULL,`fecha_borrado` datetime NULL) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;');
    }
    /**
     * Funcion desinstalar tabla
     * 
     * @return bool
     */
    public function uninstallTable()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'od_first_formulario`');;
    }
}

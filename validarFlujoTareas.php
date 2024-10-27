<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    global $db;
    $id_contrato = $_POST['id_contrato'];

    $GLOBALS['log']->error("ESTADO: " . $_SESSION['estado_anterior']);


    if ($_SESSION['estado_anterior'] != 'EnEjecucion'){
        $query = "SELECT name 
        FROM tasks 
        WHERE 
        (name = 'Relacionar los deberes y obligaciones contractuales' 
        OR name = 'Relacionar las garantías de contrato aprobadas' 
        OR name = 'Relacionar equipo de trabajo del contrato' 
        OR name = 'Registrar las bolsas financieras del contrato' 
        OR name = 'Diligenciar matriz de contacto del contratista' 
        OR name = 'Enviar acta de inicio' 
        OR name = 'Kick-off del contrato') 
        AND status != 'Completed' 
        AND parent_id = '$id_contrato';";
    
        $pendientes = "";
        $cont = 0;
    
        $result = $db->query($query,true);
        while ($row = $db->fetchByAssoc($result)) {
            $pendientes = $pendientes . $row['name'] . PHP_EOL;
            $cont++;
        }
    
        if ($cont > 0){
            echo $pendientes;
        }
        else{
            echo 0;
        }
    }
    else{
        echo 0;
    }

?>


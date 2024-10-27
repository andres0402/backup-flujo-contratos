<?php

//Por Jaime Orjuela 2017-05-09 11:31
class Contrato {
   function initialStat(&$bean, $event, $arguments) {
   
		//IMPORTANTE: cuando los valores iniciales correspondan a relaciones se deben borrar las variables antes de asignarlas para evitar valores espúreos.
        $_SESSION['old_supervisor'] = '';
        $_SESSION['old_tecnico'] = '';
        $_SESSION['old_soporte'] = '';
        $_SESSION['old_ordenador'] = '';
        $_SESSION['old_proveedor'] = '';
		if (empty($bean->fecha_inicio)) {
			$bean->fecha_inicio = '1900-01-01';
		}
		if (empty($bean->fecha_finalizacion)) {
			$bean->fecha_finalizacion = '2999-12-31';
		}
		if (empty($bean->fecha_suscripcion)) {
			$bean->fecha_suscripcion = '1900-01-01';
		}
		$_SESSION['fecha_fin_cto'] = $bean->fecha_finalizacion;
		$_SESSION['estado_cto'] = $bean->estado;
		$_SESSION['v_id_cto'] = $bean->id;		   
		$_SESSION['v_numero_cto'] = $bean->name;		
		$_SESSION['v_cto_asignado_a'] = $bean->assigned_user_id;
		$supervisores = $bean->get_linked_beans('c_contratos_users','Users');
        foreach($supervisores as $supervisor){
           $_SESSION['old_supervisor'] = $supervisor->user_name;
        }
		// $GLOBALS['log']->error("VALIDACION CTO: supervisor " . $bean->name . ":" .  $_SESSION['old_supervisor']);

		$tecnicos = $bean->get_linked_beans('c_contratos_users_1','Users');
		foreach($tecnicos as $tecnico){
			$_SESSION['old_tecnico'] = $tecnico->user_name;
        }
		// $GLOBALS['log']->error("VALIDACION CTO: tecnico: " . $bean->name . ":" . $_SESSION['old_tecnico']);

		$soportes = $bean->get_linked_beans('c_contratos_users_2','Users');
			foreach($soportes as $soporte){
				$_SESSION['old_soporte'] = $soporte->user_name;
        }
		// $GLOBALS['log']->error("VALIDACION CTO: soporte: " . $bean->name . ":" . $_SESSION['old_soporte']);

		$ordenadores = $bean->get_linked_beans('c_contratos_users_3','Users');
        foreach($ordenadores as $ordenador) {
           $_SESSION['old_ordenador'] = $ordenador->user_name;
        }
		// $GLOBALS['log']->error("VALIDACION CTO: ordenador: " . $bean->name . ":" . $_SESSION['old_ordenador']);
	  
		$proveedores = $bean->get_linked_beans('accounts_c_contratos_1','Accounts');
        foreach($proveedores as $proveedor){
           $_SESSION['old_proveedor'] = $proveedor->name;
        }

      $_SESSION['estado_anterior'] = $bean->estado;

      $pendientes = $this->validateTasks($bean);
      if ($pendientes == -1 && $bean->estado == 'PendienteActaInicio'){
         $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar equipo de trabajo del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar equipo de trabajo del contrato (Supervisor técnico, soporte administrativo, proveedor, facultado para contratar, facultado que suscribio el contrato)', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Kick-off del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Realizar el kick-off del contrato', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Registrar las bolsas financieras del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Registrar en el modulo definido para este fin, las bolsas financieras del contrato (Delimitaciones de consumo de recursos en bienes y/o servicios específicos, relacionadas en la minuta)', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar los deberes y obligaciones contractuales', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar en el módulo de actividades, los deberes y obligaciones contractuales del contrato', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar las garantías de contrato aprobadas', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar en módulo de pólizas, las garantías de contrato aprobadas', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Diligenciar matriz de contacto del contratista', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Diligenciar la matriz de contacto del contratista', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Enviar acta de inicio', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Enviar acta de inicio al contratista', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
      }


	  }
	
    function preSave(&$bean, $event, $arguments) {
        // if ($bean->assigned_user_id <> $_SESSION['v_cto_asignado_a']) {
        $query = "UPDATE c_items AS i INNER JOIN c_contratos_c_items_c AS x ON i.id = x.c_contratos_c_itemsc_items_idb
SET i.assigned_user_id = '" . $bean->assigned_user_id . "'
WHERE x.c_contratos_c_itemsc_contratos_ida = '" . $bean->id . "'";
         $bean->db->query($query);
        // }

        // Si el contrato comienza en 666 el único estado posible es 'PendienteActaInicio'
        if (substr($bean->name,0,3)=='666') {
           $bean->estado = 'PendienteActaInicio';
        } 
				

        if ($bean->date_modified == $bean->date_entered && $bean->estado == 'PendienteActaInicio') {	
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar equipo de trabajo del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar equipo de trabajo del contrato (Supervisor técnico, soporte administrativo, proveedor, facultado para contratar, facultado que suscribio el contrato)', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Kick-off del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Realizar el kick-off del contrato', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Registrar las bolsas financieras del contrato', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Registrar en el modulo definido para este fin, las bolsas financieras del contrato (Delimitaciones de consumo de recursos en bienes y/o servicios específicos, relacionadas en la minuta)', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar los deberes y obligaciones contractuales', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar en el módulo de actividades, los deberes y obligaciones contractuales del contrato', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Relacionar las garantías de contrato aprobadas', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Relacionar en módulo de pólizas, las garantías de contrato aprobadas', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Diligenciar matriz de contacto del contratista', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Diligenciar la matriz de contacto del contratista', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
   
           $query = "INSERT INTO tasks (id, name, date_entered, date_modified, modified_user_id, created_by, description, deleted, assigned_user_id, status, date_due_flag, date_start_flag, date_start, parent_type, parent_id, priority) 
           VALUES (create_id(), 'Enviar acta de inicio', DATE_ADD(NOW(), INTERVAL 5 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '$bean->assigned_user_id', '$bean->assigned_user_id', 'Enviar acta de inicio al contratista', 0, '$bean->assigned_user_id', 'In Progress', 0, 0, DATE_ADD(NOW(), INTERVAL 5 HOUR), 'c_contratos', '$bean->id', 'Normal')";
           $bean->db->query($query);
        }
    }
	
	function posSave (&$bean, $event, $arguments) {
	
		// Después de registrada alguna modificación se actualiza el campo de PMO.
		$query = "CALL act_contrato_x_id_contrato ('" . $bean->id . "')";
		$bean->db->query($query);
	
      $supervisores = $bean->get_linked_beans('c_contratos_users','Users');
      foreach($supervisores as $supervisor) {
         $id_supervisor = $supervisor->id;
		 $new_supervisor = $supervisor->user_name;  
      }	 
	  
        if ($_SESSION['old_supervisor'] != $new_supervisor) {
           $auditoria = array();
           $auditoria['field_name'] = 'Supervisor Administrativo';
           $auditoria['data_type'] = 'relate';
           $auditoria['before'] = $_SESSION['old_supervisor'];
           $auditoria['after'] = $new_supervisor;
           $bean->db->save_audit_records($bean,$auditoria);

		   // $GLOBALS['log']->error("VALIDACION CTO: id_supervisor: " . $id_supervisor);
		  $query = "UPDATE f_pedidos AS f INNER JOIN c_contratos_f_pedidos_1_c AS x 
					ON f.id = x.c_contratos_f_pedidos_1f_pedidos_idb 
					SET user_id1_c='" . $id_supervisor . "' 
					WHERE x.c_contratos_f_pedidos_1c_contratos_ida = '" . $bean->id . "'";
		  //$GLOBALS['log']->error("VALIDACION CTO: Query: " . $query);
		  $bean->db->query($query);

	   }
	  
	    $tecnicos = $bean->get_linked_beans('c_contratos_users_1','Users');
        foreach($tecnicos as $tecnico){
           $new_tecnico = $tecnico->user_name;
        }
        if ($_SESSION['old_tecnico'] != $new_tecnico) {
           $auditoria = array();
           $auditoria['field_name'] = 'Supervisor Técnico';
           $auditoria['data_type'] = 'relate';
           $auditoria['before'] = $_SESSION['old_tecnico'];
           $auditoria['after'] = $new_tecnico;
           $bean->db->save_audit_records($bean,$auditoria);
        }
		
	    $soportes = $bean->get_linked_beans('c_contratos_users_2','Users');
        foreach($soportes as $soporte){
           $new_soporte = $soporte->user_name;
        }
        if ($_SESSION['old_soporte'] != $new_soporte) {
           $auditoria = array();
           $auditoria['field_name'] = 'Soporte Administrativo';
           $auditoria['data_type'] = 'relate';
           $auditoria['before'] = $_SESSION['old_soporte'];
           $auditoria['after'] = $new_soporte;
           $bean->db->save_audit_records($bean,$auditoria);
        }
		
	    $ordenadores = $bean->get_linked_beans('c_contratos_users_3','Users');
        foreach($ordenadores as $ordenador){
           $new_ordenador = $ordenador->user_name;
        }
        if ($_SESSION['old_ordenador'] != $new_ordenador) {
           $auditoria = array();
           $auditoria['field_name'] = 'Facultado para contratar';
           $auditoria['data_type'] = 'relate';
           $auditoria['before'] = $_SESSION['old_ordenador'];
           $auditoria['after'] = $new_ordenador;
           $bean->db->save_audit_records($bean,$auditoria);
        }

	    $proveedores = $bean->get_linked_beans('accounts_c_contratos_1','Accounts');
        foreach($proveedores as $proveedor){
           $new_proveedor = $proveedor->name;
        }
        if ($_SESSION['old_proveedor'] != $new_proveedor) {
           $auditoria = array();
           $auditoria['field_name'] = 'Proveedores';
           $auditoria['data_type'] = 'relate';
           $auditoria['before'] = $_SESSION['old_proveedor'];
           $auditoria['after'] = $new_proveedor;
           $bean->db->save_audit_records($bean,$auditoria);
        }
		
    }      		

    function relationCreated(&$bean, $event, $arguments) {

		if ($arguments['related_module'] == 'l_lineas_presupuesto') {

			global $app_list_strings;
			global $current_user;
			$modulo = strtolower($arguments['module']);
			$nombre_modulo = $app_list_strings['moduleList'][$arguments['module']];
			$modulo_rel = strtolower($arguments['related_module']);
			$nombre_modulo_rel = $app_list_strings['moduleList'][$arguments['related_module']];
				$query = "INSERT INTO " . $modulo . "_audit (id, parent_id, date_created, created_by, field_name, data_type, before_value_string, after_value_string) SELECT create_id(), '" . $bean->id . "', date_add(now(), interval 5 hour), '" . $current_user->id . "','(+) " . $nombre_modulo_rel . "','relate','',name FROM " . $modulo_rel . " WHERE id = '" . $arguments['related_id'] . "'  AND deleted=0";
				// $GLOBALS['log']->error("SQL AUDITORIA RELACION EN MODULO:" . $query);
				$bean->db->query($query);
				$query = "INSERT INTO " . $modulo_rel . "_audit (id, parent_id, date_created, created_by, field_name, data_type, before_value_string, after_value_string) SELECT create_id(), '" . $arguments['related_id'] . "', date_add(now(), interval 5 hour), '" . $current_user->id . "','(+) " . $nombre_modulo . "','relate','','" . $bean->name . "'";
			// $GLOBALS['log']->error("SQL AUDITORIA RELACION EN MODULO RELACIONADO:" . $query);
				$bean->db->query($query);
		}
	   
    }

	function relationDeleted(&$bean, $event, $arguments) {

		if ($arguments['related_module'] == 'l_lineas_presupuesto') {

			global $app_list_strings;
			global $current_user;
			$modulo = strtolower($arguments['module']);
			$nombre_modulo = $app_list_strings['moduleList'][$arguments['module']];
			$modulo_rel = strtolower($arguments['related_module']);
			$nombre_modulo_rel = $app_list_strings['moduleList'][$arguments['related_module']];
				$query = "INSERT INTO " . $modulo . "_audit (id, parent_id, date_created, created_by, field_name, data_type, before_value_string, after_value_string) SELECT create_id(), '" . $bean->id . "', date_add(now(), interval 5 hour), '" . $current_user->id . "','(-) " . $nombre_modulo_rel . "','relate',name,'' FROM " . $modulo_rel . " WHERE id = '" . $arguments['related_id'] . "' AND deleted=0";
				// $GLOBALS['log']->error("SQL AUDITORIA RELACION EN MODULO:" . $query);
				$bean->db->query($query);
				$query = "INSERT INTO " . $modulo_rel . "_audit (id, parent_id, date_created, created_by, field_name, data_type, before_value_string, after_value_string) SELECT create_id(), '" . $arguments['related_id'] . "', date_add(now(), interval 5 hour), '" . $current_user->id . "','(-) " . $nombre_modulo . "','relate','" . $bean->name . "',''";
				// $GLOBALS['log']->error("SQL AUDITORIA RELACION EN MODULO RELACIONADO:" . $query);
				$bean->db->query($query);
		}

	}
   
   function validateTasks(&$bean){
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
      AND parent_id = '$bean->id';";
         
      $result = $bean->db->query($query);
      while ($row = $bean->db->fetchByAssoc($result)) {
         return 1;
      }

      return -1;
   }
	
}


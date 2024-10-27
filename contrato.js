function parseLocalNum(str) {
	//Funci�n para cambiar los puntos por nada y las comas por punto, para que posteriormente se pueda realizar el parseInt()
	// Tener en cuenta que el primer replace usa la misma estructura de la instrucci�n sed de shell.
	return parseFloat(str.replace(/\./g, "").replace(",", "."));
}
function check_form(formname) {
    bValid = false;
    if(typeof(siw)!='undefined'&&siw&&typeof(siw.selectingSomething)!='undefined'&&siw.selectingSomething) return false;
    bValid = validate_form(formname,'');
    if(!bValid) return false;
    // var exento_iva = document.getElementById("exento_iva_c").checked;
    // var valor_unitario_usd = parseLocalNum(document.getElementById("valor_unitario_usd").value);
    // var cantidad = parseLocalNum(document.getElementById("cantidad").value);
	// var iva = parseLocalNum(document.getElementById("iva").value);
	// var trm = parseLocalNum(document.getElementById("trm").value); 

	// if (valor_unitario_cop == 0 && valor_unitario_usd == 0) { 
	// 	alert('Debe especificar un valor ya sea en pesos o en d�lares para este item!!!. Por favor corrija ese inconveniente para poder continuar.');
	// 	return false;
	// } else if (cantidad == 0) {
	// 	alert('Debe especificar una cantidad de items requeridos para este item!!!. Por favor corrija ese inconveniente para poder continuar.');
	// 	return false;
	// } else if (valor_unitario_usd > 0 && trm == 0) {   
	// 	alert('Si especific� un pago en d�lares, debe especificar tambi�n una TRM!!!. Por favor corrija ese inconveniente para poder continuar.');
	// 	return false;
	// } else {
	// return true;
	// } 

	var id_contrato = document.getElementById("EditView").elements["record"].value;	
	var estado = document.getElementById("estado").value;
	var isValid = false;




	if (estado == 'EnEjecucion'){
		$.ajax({
				type: 'POST',
				url: "index.php?entryPoint=validarFlujoTareas",
				data: {id_contrato: id_contrato},
				async: false,
				success:function(response){
					if (!response.includes("0")){
						alert('Para pasar el estado del contrato a "En ejecución" se deben registrar como completadas en el módulo de actividades las siguientes tareas: \n' + response);
						isValid = false;
					}
					else{
						isValid = true;
					}         
			 },
			 error: function(xhr, status, error) {
				console.error("Error en la llamada AJAX:", error);
				console.error("Status:", status);
				console.error("Response:", xhr.responseText);
			}
	   });
	   return isValid;
	}
	else{
		return true;
	}


	
	
}

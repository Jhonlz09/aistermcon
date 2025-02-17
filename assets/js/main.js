// var conn = new WebSocket('ws:/192.168.100.50:8080');
// conn.onopen = function(e) {
//     console.log("Connection established!");
// };

// conn.onmessage = function(e) {
//     console.log(e.data);
//     // Actualiza la parte visual aquí
//     let message = JSON.parse(e.data);
//     if (message.action === "update") {
//         // Reload table or update visual elements as needed
//         console.log('Datos actualizados a las ' + new Date().toLocaleTimeString());
//         // $('#data').text('Datos actualizados a las ' + new Date().toLocaleTimeString());
//         tabla.ajax.reload(null, false); // Reload the DataTable
//     }
// };


function confirmarEliminar(art, name, callback, opcion = 'eliminar', subtitle = 'Una vez eliminado no podrá recuperarlo') {
  Swal.fire({
    title: "¿Está seguro de " + opcion + " " + art + " " + name + "?",
    text: subtitle,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, Eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      callback(true);
    }
  });
}

function limpiar(btn) {
  if (selectedTab === "4") {
    const salida_radio = document.getElementById("radio-2");
    setChange(cboConductor, conductorPorDefecto);
    setChange(cboDespachado, bodegueroPorDefecto);
    setChange(cboResponsable, 0);
    fecha.value = `${year}-${mes}-${dia}`;
    nro_guia.value = "";
    nro_orden.value = "";
    motivo.value = "";
    setChange(cboClientes, 0);
    salida_radio.value = "2";
    salida_radio.dispatchEvent(new Event("change"));

    btn.style.display = "none";
  } else if (selectedTab === "6") {
    const retorno = document.getElementById("radio-3");
    setChange(cboConductor, conductorPorDefecto);
    cboConductor.disabled = false;
    setChange(cboDespachado, bodegueroPorDefecto);
    cboDespachado.disabled = false;

    setChange(cboResponsable, 0);
    cboResponsable.disabled = false;

    // fecha_retorno.value = `${year}-${mes}-${dia}`;
    // fecha_retorno.disabled = false;

    nro_guiaEntrada.value = "";

    nro_ordenEntrada.value = "";
    nro_ordenEntrada.disabled = false;

    motivo.value = "";
    // motivo.disabled = false;

    setChange(cboClienteEntrada, 0);
    cboClienteEntrada.disabled = false;

    retorno.value = "3";
    retorno.dispatchEvent(new Event("change"));

    btn.style.display = "none";
  } else if (selectedTab === "5") {
    const entrada_radio = document.getElementById("radio-1");
    const nro_factura = document.getElementById('nro_fac');

    fecha.value = `${year}-${mes}-${dia}`;
    setChange(cboProveedores, 0);
    nro_factura.value = "";

    entrada_radio.value = "1";
    entrada_radio.dispatchEvent(new Event("change"));

    btn.style.display = "none";
  }
}


function cargarOpcionesSelect(selectElement, value, size = '110%') {
  selectElement.select2({
    data: datos_uni,
    minimumResultsForSearch: -1,
    width: size,
  });
  if (value) {
    selectElement.val(value).trigger('change');
  }
}

function cargarCombo(id, s, a = 1, isDataCbo = false) {
  return new Promise((resolve, reject) => {
    const cbo = document.getElementById("cbo" + id);
    $(cbo).empty();
    let tabla = "tbl" + id.toLowerCase();
    $.ajax({
      url: "controllers/combo.controlador.php",
      method: "POST",
      dataType: "json",
      data: {
        tabla: tabla,
        accion: a,
      },
      success: function (respuesta) {
        let dataCbo = [];
        var options = "";
        for (let index = 0; index < respuesta.length; index++) {
          options += `<option value="${respuesta[index][0]}">${respuesta[index][1]}</option>`;
          dataCbo.push({ id: respuesta[index][0], text: respuesta[index][1] });
        }
        $(cbo).html(options);
        $(cbo)
          .val(s !== "" ? s : 0)
          .trigger("change");
        // }
        if (isDataCbo) {
          resolve(dataCbo);
        }
      },
    });
  });
}

function cargarComboFabricado(s = 0) {
  const cbo1 = document.getElementById("cboFabricado");
  $(cbo1).empty();
  $.ajax({
    url: "controllers/combo.controlador.php",
    method: "POST",
    dataType: "json",
    data: {
      accion: 8,
    },
    success: function (respuesta) {
      // let dataCbo = [];
      var options = "";
      for (let index = 0; index < respuesta.length; index++) {
        options += `<option data-cant="${respuesta[index][2]}" data-und="${respuesta[index][3]}" data-name="${respuesta[index][4]}" value="${respuesta[index][0]}">${respuesta[index][1]}</option>`;
        // dataCbo.push({ id: respuesta[index][0], text: respuesta[index][1] });
      }
      $(cbo1).html(options);
      // $(cbo2).html(options);
      $(cbo1).val(s).trigger("change");
      // $(cbo2).val(0).trigger("change");
    },
  });
}

// function formatInputOrden(input, cbo, isformart = true) {
//   let value = input.value.replace(/\D/g, "");

//   // Refactorización: Uso de una expresión regular para insertar espacios
//   value = value.replace(/(\d{2})(\d{3})?(\d+)?/, (match, p1, p2, p3) => {
//     return p1 + (p2 ? " " + p2 : "") + (p3 ? " " + p3 : "");
//   });

//   input.value = value;



// }



function formatInputOrden(input) {
  let value = input.value;

  // Agregar un espacio después de los dos primeros números si aún no está presente
  if (/^\d{2}\S/.test(value)) {
    value = value.replace(/^(\d{2})(\S)/, "$1 $2");
  }

  // Agregar un espacio antes del sexto carácter sin importar si es un número o letra
  if (/^\d{2} \S{3}\S/.test(value)) {
    value = value.replace(/^(\d{2} \S{3})(\S)/, "$1 $2");
  }

  input.value = value;
}

function fetchOrderId(nombre, cbo) {
  $.ajax({
    url: "controllers/orden.controlador.php", // Cambia 'tu_script_php.php' por la ruta correcta de tu script PHP
    type: "POST",
    dataSrc: "",
    data: {
      nombre: nombre,
      accion: 4,
    },
    cache: false,
    dataType: "json",
    success: function (response) {
      const cboCli = document.getElementById(cbo);
      let id_cli = response[0]["id_cliente"];
      if (id_cli === 0) {
        cboCli.disabled = false;
      } else {
        setChange(cboCli, id_cli);
        cboCli.disabled = true;
      }
    },
  });
}

function fetchOrderId(nombre, callback) {
  $.ajax({
    url: "controllers/orden.controlador.php",
    type: "POST",
    data: {
      nombre: nombre,
      accion: 4,
    },
    cache: false,
    dataType: "json",
    success: function (response) {
      callback(response);
    },
  });
}

function moveFocusOnTab(event) {
  if (event.key === "Tab") {
    event.preventDefault(); // Evitar el comportamiento predeterminado del Tab

    // Obtener la fila actual y la tabla
    var currentRow = $(this).closest("tr");
    var currentTable = currentRow.closest("table").DataTable();

    // Encontrar la siguiente fila
    var nextRow = currentRow.next();

    // Si hay una siguiente fila, enfocar el input en esa fila
    if (nextRow.length) {
      nextRow.find("input.cantidad").focus();
    } else {
      // Si no hay una siguiente fila, enfocar el input en la primera fila de la siguiente tabla (opcional)
      var nextTable = currentTable.table().node()
        .parentElement.nextElementSibling;
      if ($(nextTable).hasClass("dataTable")) {
        $(nextTable).find("tbody tr:first-child input.cantidad").focus();
      }
    }
  }
}

function opcionSelect(select, name) {
  if (select.value !== "") {
    $("." + name + " .dis").show();
    return false;
  } else {
    $("." + name + " .dis").hide();
    return true;
  }
}

function formatNumberInput(input) {
  // Remove all non-numeric characters except for the decimal point
  let value = input.value.replace(/[^0-9.]/g, '');

  // Split the value into integer and decimal parts
  let parts = value.split('.');
  let integerPart = parts[0];
  let decimalPart = parts.length > 1 ? '.' + parts[1].slice(0, 2) : '';

  // Format the integer part with commas
  integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

  // Set the formatted value back to the input
  input.value = integerPart + decimalPart;
}

function confirmarAccion(datos, ruta, tabla, modal = "", callback, time = 5500, showToast = true) {
  $.ajax({
    url: "controllers/" + ruta + ".controlador.php",
    method: "POST",
    data: datos,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    success: function (r) {
      let isSuccess = r.status === "success";
      if (isSuccess && tabla !== null) {
        accion_inv = 0;
        tabla.ajax.reload(null, false);
        $(modal).modal("hide");
      } else if (isSuccess) {
        $(modal).modal("hide");
      }
      if (showToast) {
        mostrarToast(
          r.status,
          isSuccess ? "Completado" : "Error",
          isSuccess ? "fa-check" : "fa-xmark",
          r.m, time
        );
      }
      if (typeof callback === "function") {
        callback(isSuccess ? r : false);
      }
    },
  });
}

function mostrarToast(status, title, icon, m, time = 5500, position = "topRight") {
  $(document).Toasts("create", {
    autohide: true,
    delay: time,
    position: position,
    class: "bg-" + status,
    title: title,
    icon: 'fa-solid ' + icon + ' fa-lg',
    body: m,
  });
}

function obtenerFila(fila, tabla) {
  let row = tabla.row($(fila).parents("tr")).data();
  if (tabla.row(fila).child.isShown()) {
    row = tabla.row(fila).data();
  }
  return row;
}

function cambiarModal(span, title, iconE, icon, e, bg1, bg2, modalElement, m1, m2) {
  span.textContent = title;
  iconE.classList.remove(...iconE.classList);
  iconE.classList.add("fa-solid", icon);
  if (modalElement !== "") {
    modalElement.classList.add(m1);
    modalElement.classList.remove(m2);
  }

  if (e !== "") {
    e.forEach(function (el) {
      el.classList.remove(bg1);
      el.classList.add(bg2);
    });
  }
}

function displayModal(elements, ...args) {
  // Verificar si el número de elementos coincide con el número de argumentos
  if (elements.length !== args.length) {
    console.error(
      "El número de elementos no coincide con el número de argumentos."
    );
    return;
  }

  // Iterar sobre los elementos y establecer sus estilos de visualización
  elements.forEach((element, index) => {
    element.style.display = args[index];
  });
}

function Buscar(tabla, s) {
  scrollPosition = $(window).scrollTop();

  tabla.search($(s).val()).draw();
  $(window).scrollTop(scrollPosition);
}

function addPadding(b, s, w) {
  if (s > w) {
    b.classList.remove("no-scroll");
  } else {
    b.classList.add("no-scroll");
  }
}

// function debounce(func, wait) {
//   let timeout;
//   return function(...args) {
//       clearTimeout(timeout);
//       timeout = setTimeout(() => func.apply(this, args), wait);
//   };
// }

function handleScroll(b, s, w) {
  if (!scroll && s > w) {
    scroll = true;
    addPadding(b, s, w);
    console.log(b + " " + s + " hanfle " + w);
  } else if (scroll) {
    addPadding(b, s, w);
    console.log(b + " " + s + " hanfle " + w);
  }
}

function selecTexto(input) {
  input.select();
}

function masInfo(modulo) {
  let button = document.getElementById(modulo);
  button.click();
}

function setChange(selectE, value) {
  selectE.value = value;
  selectE.dispatchEvent(new Event("change"));
}

function convertirArray(arr) {
  if (arr === null || arr === "{NULL}") {
    // Devolver un arreglo vacío
    return [];
  }

  if (typeof arr === "string" && arr.includes("{")) {
    // El arreglo tiene llaves, lo convertimos a corchetes y luego a un array
    return JSON.parse(arr.replace("{", "[").replace("}", "]"));
  } else {
    // El arreglo ya es un array válido
    return arr;
  }
}

function validarNumber(input, regex, ten = false, decimal = 2) {
  input.value = input.value.replace(regex, "");
  if (ten) {
    // $(".ten").toggle(!(input.value.length === 10 || input.value.length === 0));
    // let padreTelefono = input.closest(".input-data");
    // let mensajeTen = padreTelefono.querySelector(".ten");
    var mensajeTen = input.parentNode.querySelector(".ten");
    mensajeTen.style.display =
      input.value.length !== 10 && input.value.length !== 0 ? "block" : "none";

    // mensajeTen.toggle(!(input.value.length === 10 || input.value.length === 0));
  } else {
    if ((input.value.match(/\./g) || []).length > 1) {
      input.value = input.value.slice(0, -1);
    }
    const partes = input.value.split(".");
    if (partes[1] && partes[1].length > decimal) {
      input.value = partes[0] + "." + partes[1].slice(0, decimal);
    }
  }
}

function validarPegado(input, e) {
  let pastedText = (e.clipboardData || window.clipboardData).getData("text");
  pastedText = pastedText.replace(/[^0-9.]/g, "");
  // Obtener el valor actual del input
  const currentValue = input.value;
  const newValue =
    currentValue.slice(0, input.selectionStart) +
    pastedText +
    currentValue.slice(input.selectionEnd);

  if ((newValue.match(/\./g) || []).length > 1) {
    e.preventDefault();
    return;
  }

  input.value = newValue;
  e.preventDefault();
}

function validarTecla(e, input) {
  if (e.key === "ArrowUp" || e.key === "ArrowDown") {
    e.preventDefault();
    const valorActual = parseInt(input.value, 10) || 0; // Si el valor no es un número, se establece como 0
    let nuevoValor = valorActual + (e.key === "ArrowUp" ? 1 : -1);

    nuevoValor = Math.max(nuevoValor, 1);
    input.value = nuevoValor.toString();
  }
}

function toggleWithEnter(e, checkbox, pre = false) {
  if (e.key === "Enter") {
    e.preventDefault();
    checkbox.checked = !checkbox.checked;
  }

  if (pre) {
    changeToggle(checkbox);
  }
}

function obtenerEspacioLibreLocalStorage() {
  // Tamaño máximo en bytes (aproximado)
  var tamanoMaximo = 5 * 1024 * 1024; // 5 MB
  var tamanoOcupado = JSON.stringify(localStorage).length;
  var espacioLibre = tamanoMaximo - tamanoOcupado;

  // Retornar el resultado en KB o MB, según tu preferencia
  return espacioLibre < 1024
    ? espacioLibre.toFixed(2) + " KB"
    : (espacioLibre / 1024).toFixed(2) + " MB";
}

function obtenerEspacioOcupadoLocalStorage() {
  var tamanoOcupado = 0;

  // Iterar sobre las claves y sumar sus tamaños
  for (var i = 0; i < localStorage.length; i++) {
    var clave = localStorage.key(i);
    var valor = localStorage.getItem(clave);

    // Sumar el tamaño de la clave y el valor (aproximadamente)
    tamanoOcupado += (clave.length + valor.length) * 2; // Multiplicar por 2 para tener una estimación en bytes (UTF-16)
  }

  // Retornar el resultado en KB o MB, según tu preferencia
  return tamanoOcupado < 1024
    ? tamanoOcupado.toFixed(2) + " bytes"
    : (tamanoOcupado / 1024).toFixed(2) + " KB";
}

function estilosSelect2(cbo, lbl, container = "single") {
  let labelElement = $("#" + lbl);
  let select2Container = $(cbo).next(".select2-container");
  let select2Span = select2Container.find(".select2-selection--" + container);
  // let select2SpanMultiple = select2Container.find(".select2-selection--multiple");

  // Añade la clase al select actual si tiene una opción seleccionada
  if ($(cbo).val() === null || $(cbo).val().length === 0) {
    select2Span.removeClass("selected-bor");
    // select2SpanMultiple.removeClass("selected-bor");
    labelElement.removeClass("selected-bor");
  } else {
    select2Span.addClass("selected-bor");
    // select2SpanMultiple.addClass("selected-bor");
    labelElement.addClass("selected-bor");
  }
}

function validarClave(input, sub) {
  if (sub) {
    let input3 = document.getElementById("clave_con");
    const isInputValid = input.value.length > 0;
    const arePasswordsMatching =
      input.value === input3.value && input3.value.length > 0;

    $("#ten").toggle(isInputValid && input.value.length <= 5);
    $("#c").toggle(!arePasswordsMatching && input3.value.length > 0);
  }
}

async function updateAll(element) {
  console.log("Iniciando updateAll");

  // Deshabilitar el botón (usando pointer-events) y cambiar el fondo a gris
  $(element).css({
    'pointer-events': 'none',  // Deshabilitar clics
    'background-color': '#d3d3d3'  // Cambiar el fondo a gris
  });

  // Ejecutar la recarga de la tabla
  try {
    console.log("Recargando tabla");
    tabla.ajax.reload(null, false);
    // Usar Promise.all para esperar todas las promesas
    await cargarDatos();

  } catch (error) {
    console.error("Error en la ejecución de alguna de las promesas:", error);
  } finally {
    // Este bloque se ejecuta siempre
    console.log("Todos los procesos han terminado.");
    // Restaurar el fondo y habilitar los clics nuevamente
    $(element).css({
      'pointer-events': 'auto',  // Reactivar el enlace
      'background-color': ''  // Restaurar el color original
    });
    mostrarToast("success", "Completado", "fas fa-check", "Datos actualizados correctamente", 2000, "bottomRight");
  }
}

async function cargarDatos() {
  // Ejecutar todas las promesas en paralelo y esperar a que terminen
  const [autocompletado, proveedores, clientes, orden] = await Promise.all([
    cargarAutocompletado(),
    cargarCombo('Proveedores', '', 1, true),
    cargarCombo('Clientes', '', 1, true),
    cargarCombo('Orden', '', 3, true)
  ]);
  // Asignar los resultados de las promesas
  datos_prove = proveedores;
  datos_cliente = clientes;
  datos_orden = orden;
}

function cargarAutocompletado(callback = false, input = 'codProducto', ruta = 'inventario', action = 7) {
  $.ajax({
    url: "controllers/" + ruta + ".controlador.php",
    method: "POST",
    data: {
      accion: action,
    },
    dataType: "json",
    success: function (respuesta) {
      var items = [];
      for (let i = 0; i < respuesta.length; i++) {
        var formattedItem = {
          cod: respuesta[i][0],
          label: respuesta[i][1],
          value: respuesta[i][1],
          cantidad: respuesta[i][2],
          anio: respuesta[i][3],
        };
        items.push(formattedItem);
      }
      if (typeof callback === "function") {
        callback(items);
      } else {
        $('#' + input).autocomplete("option", "source", items);
      }
    },
  });
}

function evitarEnvio(event) {
  if (event.keyCode === 13) {
    // 13 es el código de la tecla "Enter"
    event.preventDefault(); // Evita que el formulario se envíe
    return false; // Evita el envío del formulario
  }
  return true;
}
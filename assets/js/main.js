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

function confirmarEliminar(
  art,
  name,
  callback,
  opcion = "eliminar",
  subtitle = "Una vez eliminado no podrá recuperarlo"
) {
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

function limpiar(btn = false) {
  if (selectedTab === "1") {
    setChange(cboProveedores, 0);
    nro_factura.value = "";
  } else if (selectedTab === "2") {
    setChange(cboConductor, conductorPorDefecto);
    setChange(cboDespachado, bodegueroPorDefecto);
    setChange(cboResponsable, 0);
    fecha.value = `${year}-${mes}-${dia}`;
    nro_guia.value = "";
    nro_orden.readOnly = false; // Desbloquea el input
    nro_orden.value = "";
    clearButton.style.display = "none";
    motivo.value = "";
    inpAutorizado.value = "";
  } else if (selectedTab === "3") {
    setChange(cboConductorEntrada, conductorPorDefecto);
    setChange(cboDespachado, bodegueroPorDefecto);
    setChange(cboResponsable, 0);
    fecha.value = `${year}-${mes}-${dia}`;
    fecha_retorno.value = `${year}-${mes}-${dia}`;
    nro_ordenEntrada.readOnly = false;
    nro_ordenEntrada.value = "";
    inpAutorizado.value = "";
    clearButtonEntrada.style.display = "none";
    motivo.value = "";
  } else if (selectedTab === "4") {
    const salida_radio = document.getElementById("radio-2");
    setChange(cboConductor, conductorPorDefecto);
    setChange(cboDespachado, bodegueroPorDefecto);
    setChange(cboResponsable, 0);
    fecha.value = `${year}-${mes}-${dia}`;
    nro_guia.value = "";
    nro_orden.value = "";
    nro_orden.readOnly = false;
    clearButton.style.display = "none";
    motivo.value = "";
    inpAutorizado.value = "";
    salida_radio.value = "2";
    salida_radio.dispatchEvent(new Event("change"));
    btn ? (btn.style.display = "none") : null;
  } else if (selectedTab === "6") {
    const retorno = document.getElementById("radio-3");
    setChange(cboConductor, conductorPorDefecto);
    cboConductor.disabled = false;
    setChange(cboDespachado, bodegueroPorDefecto);
    cboDespachado.disabled = false;
    setChange(cboResponsable, 0);
    inpAutorizado.value = "";
    cboResponsable.disabled = false;
    nro_guiaEntrada.value = "";
    nro_ordenEntrada.readOnly = false;
    nro_ordenEntrada.value = "";
    clearButtonEntrada.style.display = "none";
    nro_ordenEntrada.disabled = false;
    motivo.value = "";
    retorno.value = "3";
    retorno.dispatchEvent(new Event("change"));
    btn ? (btn.style.display = "none") : null;
  } else if (selectedTab === "5") {
    const entrada_radio = document.getElementById("radio-1");
    const nro_factura = document.getElementById("nro_fac");

    fecha.value = `${year}-${mes}-${dia}`;
    setChange(cboProveedores, 0);
    nro_factura.value = "";
    entrada_radio.value = "1";
    entrada_radio.dispatchEvent(new Event("change"));
    btn ? (btn.style.display = "none") : null;
  } else if (
    selectedTab === "7" ||
    selectedTab === "8" ||
    selectedTab === "9"
  ) {
    const radio = document.getElementById("radio-7");
    const nro_gui = document.getElementById("nro_guiaFab");

    setChange(cboConductor, conductorPorDefecto);
    setChange(cboDespachado, bodegueroPorDefecto);
    setChange(cboResponsable, 0);
    fecha.value = `${year}-${mes}-${dia}`;
    nro_ordenFab.value = "";
    nro_ordenFab.readOnly = false;
    clearButtonFab.style.display = "none";
    motivo.value = "";
    inpAutorizado.value = "";
    nro_gui.value = "";
    radio.value = "7";
    radio.dispatchEvent(new Event("change"));
    btn ? (btn.style.display = "none") : null;
    isTrasFab.disabled = false;
  }
  dropzone.removeAllFilesWithoutServer(false);
}

function cargarOpcionesSelect(selectElement, value, size = "110%") {
  selectElement.select2({
    data: datos_uni,
    minimumResultsForSearch: -1,
    width: size,
  });
  if (value) {
    selectElement.val(value).trigger("change");
  }
}

function cargarCombo(id, s, a = 1, isDataCbo = false, anio = null) {
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
        anio: anio,
      },
      success: function (respuesta) {
        let dataCbo = [];
        let options = "";
        for (let index = 0; index < respuesta.length; index++) {
          options += `<option value="${respuesta[index][0]}">${respuesta[index][1]}</option>`;
          if (anio !== null) {
            let text = respuesta[index][1];
            if (respuesta[index][2]) {
              text +=
                ' <span class="alert alert-default-dark mb-0"><i class="fas fa-hammer-crash"></i> FAB</span>';
            }
            dataCbo.push({
              id: respuesta[index][0],
              text: respuesta[index][1],
              fab: respuesta[index][2],
              html: `<div style="display:flex;justify-content:space-between;align-items:center;height:1.6rem;overflow:hidden;white-space:nowrap;width:auto;text-overflow:ellipsis">${text}</div>`, // HTML personalizado
            });
          } else if (isDataCbo) {
            dataCbo.push({
              id: respuesta[index][0],
              text: respuesta[index][1],
            });
          }
        }
        $(cbo).html(options);
        $(cbo)
          .val(s !== "" ? s : 0)
          .trigger("change");
        if (isDataCbo) {
          resolve(dataCbo);
        } else if (anio !== null) {
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
      }
      $(cbo1).html(options);
      $(cbo1).val(s).trigger("change");
    },
  });
}

function formatInputOrden(input) {
  let value = input.value;

  if (/^\d{2}\S/.test(value)) {
    value = value.replace(/^(\d{2})(\S)/, "$1 $2");
  }

  if (/^\d{2} \S{3}\S/.test(value)) {
    value = value.replace(/^(\d{2} \S{3})(\S)/, "$1 $2");
  }

  input.value = value;
}

function cargarFilesDropzone(datos, drop, ruta) {
  drop.removeAllFilesWithoutServer();
  $.ajax({
    url: 'controllers/' + ruta + '.controlador.php',
    type: 'POST',
    "dataSrc": '',
    dataType: 'json',
    contentType: false,
    processData: false,
    data: datos,
    success: function (response) {
      console.log(response);
      response.files.forEach(file => {
        if (!file.nombre_file) return;
        const filename = file.nombre_file.split('/').pop();
        file
        const extMap = {
          pdf: 'application/pdf',
          xls: 'application/vnd.ms-excel',
          xlsx: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          doc: 'application/msword',
          docx: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        };

        const ext = filename.split('.').pop().toLowerCase();
        const type = extMap[ext] || '';

        const mockFile = {
          name: filename,
          size: 123456,
          type: type,
          ruta: file.nombre_file,
          tipo: file.tipo,
          isExisting: true
        };
        console.log(mockFile);
        drop.emit('addedfile', mockFile);
        drop.emit('complete', mockFile);
        drop.files.push(mockFile);
      });
    }
  });
}

function fetchOrderId(nombre, fecha, callback) {
  $.ajax({
    url: "controllers/orden.controlador.php",
    type: "POST",
    data: {
      nombre: nombre,
      fecha: fecha,
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
    event.preventDefault();

    var currentRow = $(this).closest("tr");
    var currentTable = currentRow.closest("table").DataTable();

    var nextRow = currentRow.next();

    if (nextRow.length) {
      nextRow.find("input.cantidad").focus();
    } else {
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
  let value = input.value.replace(/[^0-9.]/g, "");

  let parts = value.split(".");
  let integerPart = parts[0];
  let decimalPart = parts.length > 1 ? "." + parts[1].slice(0, 2) : "";

  integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  input.value = integerPart + decimalPart;
}

function confirmarAccion(
  datos,
  ruta,
  tabla,
  modal = "",
  callback,
  time = 5500,
  showToast = true
) {
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
          r.m,
          time
        );
      }
      if (typeof callback === "function") {
        callback(isSuccess ? r : false);
      }
    },
  });
}

function mostrarToast(
  status,
  title,
  icon,
  m,
  time = 5500,
  position = "topRight"
) {
  $(document).Toasts("create", {
    autohide: true,
    delay: time,
    position: position,
    class: "bg-" + status,
    title: title,
    icon: "fas " + icon + " fa-lg",
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

function cambiarModal(
  span,
  title,
  iconE,
  icon,
  e,
  bg1,
  bg2,
  modalElement,
  m1,
  m2
) {
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
  if (elements.length !== args.length) {
    console.error(
      "El número de elementos no coincide con el número de argumentos."
    );
    return;
  }

  elements.forEach((element, index) => {
    element.style.display = args[index];
  });
}

function trimPaste(e, el) {
  e.preventDefault();
  const pastedText = (e.clipboardData || window.clipboardData)
    .getData("text")
    .trim();

  const start = el.selectionStart;
  const end = el.selectionEnd;
  const textBefore = el.value.substring(0, start);
  const textAfter = el.value.substring(end);
  el.value = textBefore + pastedText + textAfter;

  const newCursorPos = textBefore.length + pastedText.length;
  el.setSelectionRange(newCursorPos, newCursorPos);

  if (typeof el.oninput === "function") {
    el.oninput();
  }
  return false;
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
    return [];
  }

  if (typeof arr === "string" && arr.includes("{")) {
    return JSON.parse(arr.replace("{", "[").replace("}", "]"));
  } else {
    return arr;
  }
}
// function parsePgArray(pgArrayStr) {
//     if (!pgArrayStr || pgArrayStr === '{}') return [];

//     // Extrae todo lo que esté entre comillas simples o dobles
//     const matches = pgArrayStr.match(/(['"])(.*?)\1/g);

//     if (!matches) return [];

//     // Quita las comillas externas y trim
//     return matches.map(s => s.slice(1, -1).trim());
// }

function parsePgArray(pgArrayStr) {
  if (!pgArrayStr) return [];
  let str = pgArrayStr.replace(/^{|}$/g, '').trim();
  if (!str) return [];

  const arr = [];
  let current = '';
  let inQuotes = false;
  let quoteChar = '';

  for (let i = 0; i < str.length; i++) {
    const c = str[i];

    if ((c === '"' || c === "'") && !inQuotes) {
      inQuotes = true;
      quoteChar = c;
      continue;
    } else if (c === quoteChar && inQuotes) {
      inQuotes = false;
      quoteChar = '';
      continue;
    }

    if (c === ',' && !inQuotes) {
      arr.push(current.trim());
      current = '';
    } else {
      current += c;
    }
  }

  if (current) arr.push(current.trim());

  console.log(arr.map(s => s.replace(/^'+|'+$/g, '').trim()));
  return arr.map(s => s.replace(/^'+|'+$/g, '').trim());
}


function validarNumber(input, regex, ten = false, decimal = 2) {
  input.value = input.value.replace(regex, "");
  if (ten) {
    var mensajeTen = input.parentNode.querySelector(".ten");
    mensajeTen.style.display =
      input.value.length !== 10 && input.value.length !== 0 ? "block" : "none";
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

function getFileIconSVG(file) {
  const pdfSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#de0000ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial" font-weight="bold">PDF</text></svg>`;
  const excelSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#059500ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="18" font-family="Arial" font-weight="bold">XLS</text></svg>`;
  const imgSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#004ca3ff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">IMG</text></svg>`;
  const wordSVG = `<svg width="40" height="40" viewBox="0 0 48 48"><rect width="48" height="48" rx="8" fill="#0045cfff"/><text x="24" y="32" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial" font-weight="bold">DOC</text></svg>`;
  if (file.type === "application/pdf") {
    return pdfSVG;
  }
  if (
    file.type === "application/vnd.ms-excel" ||
    file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
  ) {
    return excelSVG;
  }
  if (
    file.type === "application/msword" ||
    file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
  ) {
    return wordSVG;
  }
  if (file.type.startsWith("image/")) {
    return null;
  }
  return imgSVG;
}

function obtenerEspacioLibreLocalStorage() {
  var tamanoMaximo = 5 * 1024 * 1024; // 5 MB
  var tamanoOcupado = JSON.stringify(localStorage).length;
  var espacioLibre = tamanoMaximo - tamanoOcupado;

  return espacioLibre < 1024
    ? espacioLibre.toFixed(2) + " KB"
    : (espacioLibre / 1024).toFixed(2) + " MB";
}

function obtenerEspacioOcupadoLocalStorage() {
  var tamanoOcupado = 0;

  for (var i = 0; i < localStorage.length; i++) {
    var clave = localStorage.key(i);
    var valor = localStorage.getItem(clave);
    tamanoOcupado += (clave.length + valor.length) * 2;
  }

  return tamanoOcupado < 1024
    ? tamanoOcupado.toFixed(2) + " bytes"
    : (tamanoOcupado / 1024).toFixed(2) + " KB";
}

function estilosSelect2(cbo, lbl, container = "single") {
  let labelElement = $("#" + lbl);
  let select2Container = $(cbo).next(".select2-container");
  let select2Span = select2Container.find(".select2-selection--" + container);

  if ($(cbo).val() === null || $(cbo).val().length === 0) {
    select2Span.removeClass("selected-bor");
    labelElement.removeClass("selected-bor");
  } else {
    select2Span.addClass("selected-bor");
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
  $(element).css({
    "pointer-events": "none",
    "background-color": "#d3d3d3",
  });

  cargarAutocompletado(
    function (items) {
      items_orden = items;
      $("#nro_orden").autocomplete("option", "source", items);
      $("#nro_ordenEntrada").autocomplete("option", "source", items);
      $("#nro_ordenFab").autocomplete("option", "source", items);
      $("#nro_ordenHorario").autocomplete("option", "source", items);
    },
    null,
    "orden",
    6
  );
  try {
    tabla.ajax.reload(null, false);
    await cargarDatos();
  } catch (error) {
    console.error("Error en la ejecución de alguna de las promesas:", error);
  } finally {
    $(element).css({
      "pointer-events": "auto",
      "background-color": "",
    });
    mostrarToast(
      "success",
      "Completado",
      "fas fa-check",
      "Datos actualizados correctamente",
      2000,
      "bottomRight"
    );
  }
}

async function cargarDatos() {
  const [autocompletado, proveedores, clientes, orden] = await Promise.all([
    cargarAutocompletado(),
    cargarCombo("Proveedores", "", 1, true),
    cargarCombo("Clientes", "", 1, true),
    cargarCombo("Orden", "", 3, true),
  ]);
  datos_prove = proveedores;
  datos_cliente = clientes;
  datos_orden = orden;
}

function cargarAutocompletado(
  callback = false,
  input = "codProducto",
  ruta = "inventario",
  action = 7,
  filterType = 0
) {
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
        const row = respuesta[i];
        let formattedItem = {};
        if (row && typeof row === 'object' && !Array.isArray(row)) {
          formattedItem = {
            id: row.id || null,
            cod: row.cod || row.codigo || row.id || null,
            label: row.label || row.descripcion || '',
            value: row.cod || row.codigo || row.id || '',
            cantidad: row.cantidad || 0,
            anio: row.anio || row.year || '',
            descripcion_orden: row.descripcion_orden || '',
            img: row.img || null,
            raw: row
          };
        } else if (Array.isArray(row)) {
          formattedItem = {
            id: row[0] || null,
            cod: row[1] || null,
            label: row[2] || '',
            value: row[1] || '',
            cantidad: row[3] || 0,
            anio: row[5] || '',
            descripcion_orden: '',
            img: row[4] || null,
            raw: row
          };
        } else {
          formattedItem = { id: null, cod: row, label: String(row), value: String(row), cantidad: 0, anio: '', descripcion_orden: '', img: null, raw: row };
        }
        items.push(formattedItem);
      }
      if (typeof callback === "function") {
        callback(items, filterType);
      } else {
        if (filterType > 0) {
          $("#" + input).autocomplete("option", "source", function (request, response) {
            const filteredItems = filterAutocompletadoItems(items, request.term, filterType);
            response(filteredItems);
          });
        } else {
          $("#" + input).autocomplete("option", "source", items);
        }
      }
    },
  });
}

function filterAutocompletadoItems(items, searchTerm, filterType) {
  const input = searchTerm.toLowerCase().trim();
  let resultados = [];

  if (filterType === 2) {
    const esPatronDimensiones = /^\d+\s*x\s*\d+/i.test(input);

    if (esPatronDimensiones) {
      const regex = new RegExp(input.replace(/\s+/g, '\\s*'), 'i');
      resultados = items.filter(item => {
        const label = item.label.toLowerCase();
        return regex.test(label);
      });

      resultados.sort((a, b) => {
        const labelA = a.label.toLowerCase();
        const labelB = b.label.toLowerCase();
        const numeros = input.match(/\d+/g);
        if (!numeros || numeros.length < 2) {
          return labelA.localeCompare(labelB);
        }

        const num1 = numeros[0];
        const num2 = numeros[1];
        const patronExacto = new RegExp(`\\b${num1}\\s*x\\s*${num2}\\b`, 'i');
        const matchA = patronExacto.test(labelA);
        const matchB = patronExacto.test(labelB);

        if (matchA && !matchB) return -1;
        if (!matchA && matchB) return 1;
        if (matchA && matchB) return labelA.localeCompare(labelB);

        const posA = labelA.indexOf(input.replace(/\s+/g, ' '));
        const posB = labelB.indexOf(input.replace(/\s+/g, ' '));
        return posA - posB;
      });
    } else {
      const palabras = input.split(/\s+/).filter(p => p.length > 0);
      resultados = items.filter(item => {
        const label = item.label.toLowerCase();
        return palabras.every(palabra => label.includes(palabra));
      });

      resultados.sort((a, b) => {
        const labelA = a.label.toLowerCase();
        const labelB = b.label.toLowerCase();
        const posA = labelA.indexOf(palabras[0]);
        const posB = labelB.indexOf(palabras[0]);
        if (posA === 0 && posB !== 0) return -1;
        if (posA !== 0 && posB === 0) return 1;
        return posA - posB;
      });
    }
  } else if (filterType === 1) {
    const palabras = input.split(/\s+/).filter(p => p.length > 0);
    if (palabras.length === 0) {
      resultados = items;
    } else {
      resultados = items.filter(item => {
        const label = item.label.toLowerCase();
        return palabras.some(palabra => label.includes(palabra));
      });
    }
  } else {
    resultados = items;
  }

  return resultados;
}

function clearInput(inputId, btn) {
  const input = document.getElementById(inputId);
  if (input && btn) {
    input.readOnly = false;
    input.value = "";
    input.removeAttribute("data-id");
    input.focus();
    btn.style.display = "none";
  }
}

function evitarEnvio(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    return false;
  }
  return true;
}

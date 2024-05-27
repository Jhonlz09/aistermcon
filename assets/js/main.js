function confirmarEliminar(art, name, callback) {
  Swal.fire({
    title: "¿Está seguro de eliminar " + art + " " + name + "?",
    text: "Una vez eliminado no podrá recuperarlo",
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
        // if (a === 3) {
        //   $("#cbo" + id)
        //     .val(res)
        //     .trigger("change");
        // } else {
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

function cargarComboFabricado(s= 0) {
    const cbo1 = document.getElementById("cboFabricado");
    // const cbo2 = document.getElementById("cboFabricadoCon");

    $(cbo1).empty();
    // $(cbo2).empty();
    // let tabla = "tbl" + id.toLowerCase();
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
          options += `<option data-orden="${respuesta[index][2]}" data-cant="${respuesta[index][3]}" data-und="${respuesta[index][4]}" data-name="${respuesta[index][5]}" value="${respuesta[index][0]}">${respuesta[index][1]}</option>`;
          // dataCbo.push({ id: respuesta[index][0], text: respuesta[index][1] });
        }
        $(cbo1).html(options);
        // $(cbo2).html(options);
        $(cbo1).val(s).trigger("change");
        // $(cbo2).val(0).trigger("change");

      },
    });
}

function moveFocusOnTab(event) {
  if (event.key === 'Tab') {
      event.preventDefault(); // Evitar el comportamiento predeterminado del Tab

      // Obtener la fila actual y la tabla
      var currentRow = $(this).closest('tr');
      var currentTable = currentRow.closest('table').DataTable();

      // Encontrar la siguiente fila
      var nextRow = currentRow.next();

      // Si hay una siguiente fila, enfocar el input en esa fila
      if (nextRow.length) {
          nextRow.find('input.cantidad').focus();
      } else {
          // Si no hay una siguiente fila, enfocar el input en la primera fila de la siguiente tabla (opcional)
          var nextTable = currentTable.table().node().parentElement.nextElementSibling;
          if ($(nextTable).hasClass('dataTable')) {
              $(nextTable).find('tbody tr:first-child input.cantidad').focus();
          }
      }
  }
}

function agregarProductoProduccion(){

}

function opcionSelect(select, name) {
  if (select.value !== '') {
      $('.' + name + ' .dis').show();
      return false
  } else {
      $('.' + name + ' .dis').hide();
      return true
  }
}

function agregarProductoProduccion(){
  
}

function confirmarAccion(datos, ruta, tabla, modal = "", callback) {
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
      }
      mostrarToast(
        r.status,
        isSuccess ? "Completado" : "Error",
        isSuccess ? "fa-solid fa-check fa-lg" : "fa-solid fa-xmark fa-lg",
        r.m
      );

      if (typeof callback === "function") {
        callback(isSuccess ? r : false);
      }
      $(modal).modal("hide");
    },
  });
}

function mostrarToast(status, title, icon, m) {
  $(document).Toasts("create", {
    autohide: true,
    delay: 5500,
    class: "bg-" + status,
    title: title,
    icon: icon,
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

  tabla.search($(s).val()).draw(false);
  $(window).scrollTop(scrollPosition);

}

function addPadding(b, s, w ) {
  // let isNav = (nav === null) ? true : false;
  if (s > w) {
    b.classList.remove("no-scroll");
  } else {
    b.classList.add("no-scroll");
    // if(!isNav){
    //   nav.classList.add("no-scroll");
    // }
  }
}

function debounce(func, wait) {
  let timeout;
  return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
  };
}


function handleScroll(b, s, w) {
  if (!scroll && s > w) {
    scroll = true;
    addPadding(b, s, w );
  } else if (scroll) {
    addPadding(b, s, w);
  }
}

function setChange(selectE, value) {
  selectE.value = value;
  selectE.dispatchEvent(new Event("change"));
}

function convertirArray(arr) {
if (arr === null || arr === '{NULL}') {
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

function validarNumber(input, regex, ten = false) {
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
    if (partes[1] && partes[1].length > 2) {
      input.value = partes[0] + "." + partes[1].slice(0, 2);
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

    nuevoValor = Math.max(nuevoValor, 0);
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

function cargarAutocompletado(callback = false) {
  $.ajax({
    url: "controllers/inventario.controlador.php",
    method: "POST",
    data: {
      accion: 7,
    },
    dataType: "json",
    success: function (respuesta) {
      var items = [];
      for (let i = 0; i < respuesta.length; i++) {
        var formattedItem = {
          cod: respuesta[i]["codigo"],
          label: respuesta[i]["descripcion"],
          value: respuesta[i]["descripcion"],
          cantidad: respuesta[i]["cantidad"],
        };
        items.push(formattedItem);
      }
      if (typeof callback === "function") {
        callback(items);
      } else {
        $("#codProducto").autocomplete("option", "source", items);
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

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
      // $.ajax({
      //   url: "controllers/" + ruta + ".controlador.php",
      //   method: "POST",
      //   data: src,
      //   cache: false,
      //   dataType: "json",
      //   contentType: false,
      //   processData: false,
      //   success: function (r) {
      //     if (r.status === "success") {
      //       // tabla.ajax.reload(null, false);
      //       tabla === null
      //         ? cargarCombo(name, s)
      //         : tabla.ajax.reload(null, false);
      //       mostrarToast(r.status, "Completado", "fas fa-check fa-lg", r.m);
      //     } else {
      //       mostrarToast(r.status, "Error", "fas fa-xmark fa-lg", r.m);
      //     }
      //   },
      // });

      // if (s !== '') {
      //   confirmarAccion(src,ruta, null, null, function(res){
      //     if(res){
      //       cargarCombo(name, s)
      //     }
      //   })
      // }else{
      //   confirmarAccion(src,ruta, null ,tabla, function(res){
      //   cargarAutocompletado();
      //   })
      // }
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

function confirmarAccion(datos, ruta, tabla, modal = "", callback) {
  $.ajax({
    url: "controllers/" + ruta + ".controlador.php",
    method: "POST",
    data: datos,
    cache: false,
    dataType: "json",
    contentType: false,
    processData: false,
    // success: function (r) {
    //   if (r.status === "success") {
    //     tabla !== null
    //        ?  tabla.ajax.reload(null, false)//cargarCombo(name, s, a, r.res)
    //     //:

    //     $(modal).modal("hide");

    //     if (typeof callback === "function") {
    //       callback(r.res); // Llama al callback con el valor de r.res como segundo argumento
    //     }
    //     mostrarToast(r.status, "Completado", "fa-solid fa-check fa-lg", r.m);
    //   } else {
    //     if (typeof callback === "function") {
    //       callback(false); // Llama al callback con el valor de r.res como segundo argumento
    //     }
    //     mostrarToast(r.status, "Error", "fa-solid fa-xmark fa-lg", r.m);
    //   }
    //   // if (auto) {
    //   //   cargarAutocompletado();
    //   // }
    //   // if (combo) {
    //   //   cargarCombo(name);
    //   // }
    // },
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
    delay: 3500,
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

function cambiarModal(span, title, iconE, icon, e, bg1, bg2, modalE, m1, m2) {
  span.textContent = title;
  iconE.classList.remove(...iconE.classList);
  iconE.classList.add("fa-solid", icon);
  if (modalE !== "") {
    modalE.classList.add(m1);
    modalE.classList.remove(m2);
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
  tabla.search($(s).val()).draw();
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
    console.log("El scroll es true (primera vez)");
    addPadding(b, s, w);
  } else if (scroll) {
    addPadding(b, s, w);
  }
}
function setChange(selectE, value) {
  selectE.value = value;
  selectE.dispatchEvent(new Event("change"));
}

function validarNumber(input, regex, ten = false) {
  input.value = input.value.replace(regex, "");
  if (ten) {
    $(".ten").toggle(!(input.value.length === 10 || input.value.length === 0));
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

function estilosSelect2(cbo, lbl) {
  let labelElement = $("#" + lbl);
  let select2Container = $(cbo).next(".select2-container");
  let select2Span = select2Container.find(".select2-selection--single");

  // Añade la clase al select actual si tiene una opción seleccionada
  if ($(cbo).val() === null) {
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

function cargarAutocompletado(callback= false) {
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
        callback(items)
      } else {
        $("#codProducto").autocomplete("option", "source", items);
      }
    },
  });
}

function evitarEnvio(event) {
  if (event.keyCode === 13) { // 13 es el código de la tecla "Enter"
    event.preventDefault(); // Evita que el formulario se envíe
    return false; // Evita el envío del formulario
  }
  return true; 
}
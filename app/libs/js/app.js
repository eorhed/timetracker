function actualizarPrecioHora() {

  precio_hora = document.getElementById('precio_x_hora').value;
  document.getElementById('info_precio_hora').innerHTML = precio_hora + " €";

}


function actualizarTareasAlCambiarActividad() {

  var idactividad = document.getElementById('idactividad').value;

  // var posting = $.post(url,$( "#form" ).serialize(),"json");
  // console.log("Lanzando .post");
  console.log(idactividad);
  var url = "app/libs/php/gestor_tareas_actividad.php";
  var posting = $.post(url, {
    idactividad: idactividad
  }, "json");


  posting.done(function (response) {
    if (response.exito) {
      $("#idtarea").html("");
      //$("#info-tarea").hide();

      if (response.tareas) {
        // Por cada tarea creamos una etiqueta option
        var cont = 0;
        response.tareas.forEach(function (tarea) {
          if (tarea.idtarea !== "0") {
            if (cont == 0) {
              $("#idtarea").append("<option value='" + tarea.idtarea + "' selected> " + tarea.nombre + "</option>");
            } else {
              $("#idtarea").append("<option value='" + tarea.idtarea + "'> " + tarea.nombre + "</option>");
            }
          }
          cont++;
        }, this);

      } else {
        $("#tarea").after("<div class='info-warning' id='info-tarea' role='alert'>No puedes trackear porque este proyecto no tiene ninguna tarea asignada todavía.<br/> Puedes crear nuevas tareas desde <a href='tareas'>Administrar->Tareas</a> y luego asignarlas desde <a href='proyectos'>Administrar->Proyectos</a></div>");
      }
    } else {
      if (response) {
        // Se ha producido un error al obtener los datos en la BD
        // Mostramos el correspondiente error por pantalla
        $("#tarea").after("<div class='info-error' id='info' role='alert'>Se ha producido un error al obtener los datos</div>");
      }
    }
  });
}

/**
 * Obtenemos la fechahora actual y la devolvemos en el formato dd/mm/YYYY hh:mm:ss
 */
function obtFechaHoraActual() {
  // Obtenemos los datos de la fecha completa
  var d = new Date();

  var dia = d.getDate();
  var mes = d.getMonth() + 1;
  var anio = d.getFullYear();
  var horas = d.getHours();
  var minutos = d.getMinutes();
  var segundos = d.getSeconds();

  // Antenponemos un 0 si la var es < 10 para que quede la fecha en formato dd/mm/YYYY HH:ii:ss
  dia = dia < 10 ? 0 + dia.toString() : dia;
  mes = mes < 10 ? 0 + mes.toString() : mes;
  horas = horas < 10 ? 0 + horas.toString() : horas;
  minutos = minutos < 10 ? 0 + minutos.toString() : minutos;
  segundos = segundos < 10 ? 0 + segundos.toString() : segundos;

  return dia + "/" + mes + "/" + anio + " " + horas + ":" + minutos + ":" + segundos;
}

tiempo = 0;
sesion = null;

function comenzarSeguimiento() {

  $(".form-trackear").hide("slow");
  $("#seguimientos").show("slow");

  tiempo = 0;

  $(".btn-comenzar2").hide(); // Hacemos desaparecer el boton Trackear2 de la lista de sesiones

  var tarea_seleccionada = $("#idtarea option:selected").html(); // Obtenemos el nombre de la tarea seleccionada

  var fecha = obtFechaHoraActual(); // Obtenemos la fecha y hora actuales

  var num = $("#seguimientos ul > li").length + 1;

  // Abrimos un nuevo elemento de la lista para mostrar la info de la nueva sesion
  $("#seguimientos ul").append("<li><div class='info_sesion'><div class='num'>#" + num + "</div>" +
    "<div class='fecha'>" + fecha + " </div>" +
    "<div class='tarea_seleccionada'>" + tarea_seleccionada + " </div>" +
    "<div class='tiempo'>00:00:00</div></div></li>");

  $(".btn-comenzar").prop("disabled", true);
  sesion = setInterval(seguimiento, 1000);
  // $(".btn-pausar").html("<span class='glyphicon glyphicon-pause'></span> Pausar");
  $(".btn-pausar").show();
  $(".btn-guardar").show();
  $("#info").empty();
}

/**
 * Pausamos la sesión de seguimiento actualmente activa
 */
function conmutarSeguimiento() {

  if (sesion) {
    $(".acciones-cronometro .btn-reproducir-tiempo").show("fast");
    $(".acciones-cronometro .btn-pausar-tiempo").hide("fast");
    $(".btn-pausar").html("<span class='glyphicon glyphicon-play'></span> Seguir");
    clearInterval(sesion);
    sesion = null;
  } else {
    $(".acciones-cronometro .btn-reproducir-tiempo").hide("fast");
    $(".acciones-cronometro .btn-pausar-tiempo").show("fast");
    $(".btn-pausar").html("<span class='glyphicon glyphicon-pause'></span> Pausar");
    sesion = setInterval(seguimiento, 1000);
  }

}

/**
 * Finalizamos la sesión de seguimiento activa y la guardamos
 */
function guardarSeguimiento() {
  //Paramos la sesion y la guardamos en la BD
  if (sesion)
    clearInterval(sesion);

  // Guardamos (y validamos dentro de guardar) la sesion en la BD
  guardar_sesion_tarea();
  $(".marcador").html("00:00:00");
  $(".btn-pausar-tiempo").hide();
  $(".btn-reproducir-tiempo").show();
  $(".btn-pausar").hide();
  $(".btn-guardar").hide();
  $(".btn-comenzar").prop("disabled", false);
  $(".btn-comenzar2").show();
}

/**
 * Suma un segundo al cronómetro en formato hh:mm:ss
 * En caso de que sean correctos, se guarda la sesión en la BD
 */
function seguimiento() {
  tiempo = tiempo + 1;
  var t = transformar_tiempo(tiempo);
  $("ul > li:last-child() .tiempo").html(t);
  $(".marcador").html(t);
}


/**
 * Función que dado un tiempo expresado en segundos
 * lo transforma a horas:minutos:segundos. Formato tipo hh:mm:ss
 */
function transformar_tiempo(tiempo) {
  var horas = Math.floor(tiempo / 3600);
  var minutos = Math.floor((tiempo % 3600) / 60);
  var segundos = tiempo % 60;


  horas = horas < 10 ? "0" + horas : horas; //Anteponiendo un 0 a las horas si son menos de 10 
  minutos = minutos < 10 ? "0" + minutos : minutos; //Anteponiendo un 0 a los minutos si son menos de 10 
  segundos = segundos < 10 ? "0" + segundos : segundos; //Anteponiendo un 0 a los segundos si son menos de 10 

  var t = horas + ":" + minutos + ":" + segundos;

  return t;
}


/**
 * Comprueba si el idproyecto y el idtarea obtenidos del formulario son de verdad del propio usuario
 * En caso de que sean correctos, se guarda la sesión en la BD
 */
function guardar_sesion_tarea() {

  $("#info").html("<img class='center-block' src='application/public/img/cargando.gif' width='20' height='20' style='display:inline-block;'> <span  style='display:inline-block;'>Guardando...</span>");
  var url = "app/libs/php/guardar_sesion_tarea.php";
  var idtarea = $("#idtarea").val();
  var comentarios = $("#comentarios").val();
  var duracion = "00:00:00";
  var fecha_inicio = $("ul > li:last-child() .fecha").html();


  // Si existe el campo duracion_sesion porque estamos en la pantalla de agregar registro manualmente
  // Transformamos duracion en segs (minutos*60) en formato 00:00:00
  // Si no, estamos en tracking automatico y lo tenemos que coger del html de la lista ul
  if ($("#duracion_sesion") && !isNaN($("#duracion_sesion").val()) && $("#duracion_sesion").val() > 0) {
    if ($("#error_duracion_isNaN"))
      $("#error_duracion_isNaN").remove();
    tiempo = $("#duracion_sesion").val() * 60; // Variable global tiempo
    duracion = transformar_tiempo(tiempo);
  } else if ($("#duracion_sesion").val() != null && isNaN($("#duracion_sesion"))) {
    $("#info").html("");
    $("#duracion_sesion").after("<div id='error_duracion_isNaN' class='alert alert-danger'>Introduce un n&uacute;mero entero</div>");
    return false;
  } else
    duracion = $("ul > li:last-child() .tiempo").html();



  var precio_por_hora = $("#precio_por_hora").val();
  var comentarios = $("#comentarios").val();

  console.log(idtarea);
  console.log(fecha_inicio);
  console.log(duracion);
  console.log(comentarios);

  var posting = $.post(url, {
    idtarea: idtarea,
    duracion: tiempo,
    fecha_inicio: fecha_inicio,
    comentarios: comentarios,
  }, "json");

  posting.done(function (response) {
    if (response.exito) {
      console.log(response.registro);
      $("#info").html("<div class='alert alert-success marginT20' role='alert'>La sesi&oacute;n ha sido a&ntilde;adida con &eacute;xito</div>");
    } else {
      /*
       * Si se ha producido un error al realizar la operación de guardado en la BD 
       * o porque la validación de los datos del formulario ha fallado
       */

      // Los datos del formulario han sido manipulados adrede cambiando el campo value de los campos proyecto y tarea
      if (response.error_manipulacion_datos)
        $("#info").html("<div class='alert alert-danger marginT20' role='alert'>No se ha podido guardar la sesi&oacute;n.<br>El proyecto y/o la tarea no pertenece/n a este usuario o la tarea seleccionada no est&aacute; asignada a dicho proyecto</div>");
      else
        $("#info").html("<div class='alert alert-danger marginT20' role='alert'>La sesi&oacute;n no se ha podido guardar debido a un error interno</div>");
    }

    $("#info").fadeIn("slow");
  });
}


function mostrarGrafico(informeActividad) {
  const config = {
    type: 'doughnut',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Gráfico distribucion temporal de tareas'
        }
      }
    },
  };



  const DATA_COUNT = 5;
  const NUMBER_CFG = {
    count: DATA_COUNT,
    min: 0,
    max: 100
  };

  const data = {
    labels: ['Seguridad', 'Pruebas', 'Cumplimiento legislación RGPD/LSSI', 'Green', 'Blue'],
    datasets: [{
      label: 'Dataset 1',
      data: Utils.numbers(NUMBER_CFG),
      backgroundColor: Object.values(Utils.CHART_COLORS),
    }]
  };

  const actions = [{
      name: 'Randomize',
      handler(chart) {
        chart.data.datasets.forEach(dataset => {
          dataset.data = Utils.numbers({
            count: chart.data.labels.length,
            min: 0,
            max: 100
          });
        });
        chart.update();
      }
    },
    {
      name: 'Add Dataset',
      handler(chart) {
        const data = chart.data;
        const newDataset = {
          label: 'Dataset ' + (data.datasets.length + 1),
          backgroundColor: [],
          data: [],
        };

        for (let i = 0; i < data.labels.length; i++) {
          newDataset.data.push(Utils.numbers({
            count: 1,
            min: 0,
            max: 100
          }));

          const colorIndex = i % Object.keys(Utils.CHART_COLORS).length;
          newDataset.backgroundColor.push(Object.values(Utils.CHART_COLORS)[colorIndex]);
        }

        chart.data.datasets.push(newDataset);
        chart.update();
      }
    },
    {
      name: 'Add Data',
      handler(chart) {
        const data = chart.data;
        if (data.datasets.length > 0) {
          data.labels.push('data #' + (data.labels.length + 1));

          for (let index = 0; index < data.datasets.length; ++index) {
            data.datasets[index].data.push(Utils.rand(0, 100));
          }

          chart.update();
        }
      }
    },
    {
      name: 'Hide(0)',
      handler(chart) {
        chart.hide(0);
      }
    },
    {
      name: 'Show(0)',
      handler(chart) {
        chart.show(0);
      }
    },
    {
      name: 'Hide (0, 1)',
      handler(chart) {
        chart.hide(0, 1);
      }
    },
    {
      name: 'Show (0, 1)',
      handler(chart) {
        chart.show(0, 1);
      }
    },
    {
      name: 'Remove Dataset',
      handler(chart) {
        chart.data.datasets.pop();
        chart.update();
      }
    },
    {
      name: 'Remove Data',
      handler(chart) {
        chart.data.labels.splice(-1, 1); // remove the label first

        chart.data.datasets.forEach(dataset => {
          dataset.data.pop();
        });

        chart.update();
      }
    }
  ];
}


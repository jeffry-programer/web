//Funciones que se ejecutan cuando se carga la pagina
$(document).ready(() => {
    var nameCity = localStorage.getItem('name_city');
    if(nameCity !== null){
        var cityId = localStorage.getItem('id_city');
        var stateId = localStorage.getItem('id_state');
        var countryId = localStorage.getItem('id_country');

        $("#btn-ubi").html(`${nameCity}`);
        $("#value-city").val();

        $("#value-country").val(countryId);
        $("#value-state").val(stateId);
        $("#value-city").val(cityId);
    }
    var category = localStorage.getItem('categories_id');
    if(category !== null){
        $("#select-search-categories").val(category);
    }
    testQr();

    initImages();

    $("#subscribe").click(() => {
        $("#subscribe-form").submit();
    });

    $("#unsubscribe").click(() => {
        $("#unsubscribe-form").submit();
    });
});


$(document).ready(() => {
  Dropzone.autoDiscover = false;

  var isset_images = false;

  let myDropzone = new Dropzone("#myDropzone24", { 
      url: "{{route('imgs-store')}}",
      headers: {
          'X-CSRF-TOKEN' : "{{csrf_token()}}",
      },
      dictDefaultMessage: `Arrastre o haga click para agregar imágenes <br>(máximo de imágenes: 2)`,
      dictMaxFilesExceeded: "No puedes subir más archivos",
      dictCancelUpload: "Cancelar subida",
      dictInvalidFileType: "No puedes subir archivos de este tipo",
      dictRemoveFile: "Remover archivo",
      acceptedFiles: 'image/*',
      maxFilesize : 5,
      maxFiles: 2,
      autoProcessQueue: false,
      addRemoveLinks: true,
      parallelUploads: 5,
      init: function(){
          this.on("sending", function(file, xhr, formData){
              formData.append("id", `${$("#id_table").val()}`);
              formData.append("table", `${$("#table").val()}`);
          });

          this.on("success", function(file, response) {
              if(file.status != 'success'){
                  return false;
              }
              if(this.getUploadingFiles().length === 0){
                  isset_images = true;
                  hideAlertTime();
              }
          });
      }
  });      
});



$(document).ready(function(){
    // Variable para almacenar el último valor seleccionado
    var ultimoValorSeleccionado = "";
  
    // Función para reiniciar el autocompletado
    function reiniciarAutocompletado() {
      $("#myUL li").show(); // Mostrar todas las opciones
    }
  
    // Mostrar la lista al hacer clic en el input
    $("#myInput").click(function() {
      $("#myUL").show();
      reiniciarAutocompletado(); // Reiniciar autocompletado al hacer clic en el input
    });
    
    // Seleccionar una opción de la lista
    $("#myUL").on("click", "li", function() {
      var value = $(this).text();
      $("#myInput").val(value); // Colocar el valor seleccionado en el input
      ultimoValorSeleccionado = value; // Actualizar el último valor seleccionado
      $("#myUL").hide(); // Ocultar la lista después de seleccionar
    });
    
    // Filtrar opciones según la entrada del usuario
    $("#myInput").on("input", function() {
      reiniciarAutocompletado(); // Reiniciar autocompletado al escribir en el input
      var value = $(this).val().toLowerCase();
      $("#myUL li").each(function() {
        var text = $(this).text().toLowerCase();
        if (text.indexOf(value) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
    
    // Controlar clic fuera del área de autocompletado
    $(document).click(function(event) {
      var $target = $(event.target);
      var inputValue = $("#myInput").val();
      if(!$target.closest('.autocomplete').length) {
        if (inputValue !== ultimoValorSeleccionado) {
          $("#myInput").val(""); // Vaciar el input si no se seleccionó una opción recientemente
        }
        $("#myUL").hide(); // Ocultar la lista en cualquier caso
      }
    });
});

function seleccionarCiudad(id){
    $("#city_store_data_id").val(id);
}  

function goPagePublicity(id){
    window.location.replace("/publicities/"+id);
}

$(document).ready(function() {
    $("#imagenPrincipal").mousemove((e) => {
        $("#lupa").removeClass('d-none');
        var offset = $("#imagenPrincipal").offset();
        var relX = e.pageX - offset.left;
        var relY = e.pageY - offset.top;
        var percentageX = relX / $("#imagenPrincipal").width() * 100;
        var percentageY = relY / $("#imagenPrincipal").height() * 100;

        $("#lupa").css({
            backgroundPosition: percentageX + '% ' + percentageY + '%'
        });
    });
    $("#imagenPrincipal").mouseout((e) => {
        $("#lupa").addClass('d-none');
    });
    // Manejar clic en las imágenes secundarias
    $('.thumbnail').click(function(){
      var nuevaRuta = $(this).attr('src');
      $('#imagenPrincipal').attr('src', nuevaRuta);
      $("#lupa").css({backgroundImage: `url("${nuevaRuta}")`});
    });
  });

function initImages(){
    $('.fotorama').fotorama({
      nav: 'thumbs',
      width: '100%',
      height: 400
    });

    $('.fotorama').magnificPopup({
      delegate: 'a',
      type: 'image',
      gallery: {
        enabled: true
      }
    });
}

//Verificar codigo qr
function testQr(){
    var URLactual = String(window.location);

    if(!URLactual.includes('tienda')){
        return false;
    }
    
    // Configura el objeto qrcode
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: URLactual,
        width: 128,
        height: 128
    });
}

//Guardar ubicación
$("#btn-save-ubi").click(() => {
    var cityId = $("#value-city").val();
    var nameCity = $("#city").val();
    var stateId = $("#state").val();
    var countryId = $("#country").val();
    //guardamos en local storage la información
    localStorage.setItem("id_city", cityId);
    localStorage.setItem("name_city", nameCity);
    localStorage.setItem("id_state", stateId);
    localStorage.setItem("id_country", countryId);
    
    //guardamos la información en los inputs que seran enviados en el formulario de busqueda de tiendas
    $("#value-country").val(countryId);
    $("#value-state").val(stateId);
    $("#value-city").val(cityId);

    $("#btn-ubi").html(`${$("#city").val()}`);
    $("#btn-save-ubi").attr('disabled', true);
    $("#exampleModal").modal('hide');
});

//metodo que se llama al seleccionar una ciudad
function seleccionar(nameCity, id){
    $("#city").val(nameCity);
    $("#value-city").val(id);
    $("#btn-save-ubi").removeAttr('disabled');
    cerrarLista();
}

//metodo que cierra la lista del autocompletado de ciudad
function cerrarLista(){
    const items = document.querySelectorAll(".lista-autocompletar-items");
    items.forEach(item =>{item.parentNode.removeChild(item);});
    indexFocus = -1;
}

//cerrar lista al captar un click en la pagina
$(document).click(() => {
    cerrarLista();
});

//Enviar formulario para buscar las tiendas
function searchData(){
    $("#form-search").submit();
}

//Guardar categoria en local storage
$("#select-search-categories").change(() => {
    localStorage.setItem('categories_id', $("#select-search-categories").val());
});

//Ir al detalle de la tienda
function goStore(name){
    name = name.replaceAll(' ','-');
    window.location.replace('/tienda/'+name);
}

//ir al detalle de la tienda y del producto
function goStoreProduct(nameStore, link){
    nameStore = nameStore.replaceAll(' ','-');
    window.location.replace('/tienda/'+nameStore+'/'+link);
}

function magnificar(elemento) {
    // Obtener la imagen principal y la lupa
    const imagenPrincipal = document.getElementById('imagenPrincipal').querySelector('img');
    const lupa = document.createElement('div');
    lupa.className = 'lupa';
  
    // Configurar la imagen de la lupa
    lupa.style.backgroundImage = `url(${elemento.querySelector('img').src})`;
  
    // Mostrar la lupa al colocar el mouse sobre la miniatura
    elemento.addEventListener('mouseover', () => {
      imagenPrincipal.parentElement.appendChild(lupa);
      lupa.style.display = 'block';
    });
  
    // Ocultar la lupa al retirar el mouse de la miniatura
    elemento.addEventListener('mouseout', () => {
      lupa.style.display = 'none';
      lupa.remove();
    });
  
    // Actualizar la posición de la lupa en respuesta al movimiento del mouse
    elemento.addEventListener('mousemove', (evento) => {
      const rect = elemento.getBoundingClientRect();
      const x = evento.clientX - rect.left;
      const y = evento.clientY - rect.top;
  
      const backgroundPosX = (x / rect.width) * 100;
      const backgroundPosY = (y / rect.height) * 100;
  
      lupa.style.backgroundPosition = `${backgroundPosX}% ${backgroundPosY}%`;
    });
}

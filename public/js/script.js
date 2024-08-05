//Funciones que se ejecutan cuando se carga la pagina
$(document).ready(() => {
    testQr();

    initImages();

    $("#subscribe").click(() => {
        $("#subscribe-form").submit();
    });

    $("#unsubscribe").click(() => {
        $("#unsubscribe-form").submit();
    });

    var arraySelects = ['','2','3','4','5', '6'];
    var ultimoValorSeleccionado = [];
    var reiniciarAutocompletado = [];


    arraySelects.forEach((element, index) => { 
        // Función para reiniciar el autocompletado
        reiniciarAutocompletado[index] = () => {
          $(`#myUL${element} li`).show(); // Mostrar todas las opciones
        }
      
        // Mostrar la lista al hacer clic en el input
        $(`#myInput${element}`).click(function() {
          $(`#myUL${element}`).show();
          reiniciarAutocompletado[index](); // Reiniciar autocompletado al hacer clic en el input
        });
        
        // Seleccionar una opción de la lista
        $(`#myUL${element}`).on("click", "li", function() {
          var value = $(this).text();
          $(`#myInput${element}`).val(value); // Colocar el valor seleccionado en el input
          ultimoValorSeleccionado[index] = value; // Actualizar el último valor seleccionado
          $(`#myUL${element}`).hide(); // Ocultar la lista después de seleccionar
        });
        
        // Filtrar opciones según la entrada del usuario
        $(`#myInput${element}`).on("input", function() {
          reiniciarAutocompletado[index](); // Reiniciar autocompletado al escribir en el input
          var value = $(this).val().toLowerCase();
          $(`#myUL${element} li`).each(function() {
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
          var inputValue = $(`#myInput${element}`).val();
          if(!$target.closest('.autocomplete').length) {
            if (inputValue !== ultimoValorSeleccionado[index]) {
              $(`#myInput${element}`).val(""); // Vaciar el input si no se seleccionó una opción recientemente
            }
            $(`#myUL${element}`).hide(); // Ocultar la lista en cualquier caso
          }
        });
    });
});

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

function togglePassword(){
  var passwordInput = document.getElementById('password');
  var eyeIcon = document.getElementById('eye-icon');

  if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.innerHTML = '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm-5-5a5 5 0 0110 0 5 5 0 01-10 0z"/>'; // icono de ojo abierto
  } else {
      passwordInput.type = 'password';
      eyeIcon.innerHTML = '<path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z"/>'; // icono de ojo cerrado
  }
}
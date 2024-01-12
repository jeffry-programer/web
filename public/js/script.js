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
});

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
  


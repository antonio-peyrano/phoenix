/*
 * El presente segmento de codigo controla las acciones del menu de navegación
 * en lo concerniente al despliegue de elementos de submenu de opciones.
 */
$(document).ready(function(){ 
	$("ul.subnavegador").not('.selected').hide();
	$("a.desplegable").click(function(e){
			var desplegable = $(this).parent().find("ul.subnavegador");
			$('.desplegable').parent().find("ul.subnavegador").not(desplegable).slideUp('slow');
			desplegable.slideToggle('slow');
			e.preventDefault();
			})
        });

/* 
 * Carga de la referencia al boton y barra de ajuste del menu en pantalla.
 */

$(document).ready(function() {
  $("[data-toggle]").click(function() {
    var toggle_el = $(this).data("toggle");
    $(toggle_el).toggleClass("abrir-MenuLateral");
  });
     
});

/*
 * Carga de las referencias de acción sobre los eventos del botón de menu en pantalla.
 */
$(".Area-Deslizar").swipe({
    swipeStatus:function(event, phase, direction, distance, duration, fingers)
        {
            if (phase=="move" && direction =="right") {
                 $(".Contenedor").addClass("abrir-MenuLateral");
                 return false;
            }
            if (phase=="move" && direction =="left") {
                 $(".Contenedor").removeClass("abrir-MenuLateral");
                 return false;
            }
        }
});


function nuevoAjax(){
	//Esta función crea las instancias necesarias para el manejo de objetos Ajax.
    var xmlhttp=false; //Se define el objeto de plantilla en falso.
    
    try {
    	//Se carga una referencia de objeto ActiveX para la plantilla mediante instancia Msxml2.
    	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    	} 
    catch (e) {
    	try {
    		//En caso de ocurrir un error de instanciamiento de plantilla,
    		//se carga una referencia de objeto ActiveX para la plantilla mediante instancia Microsoft.
    		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    		} 
    	catch (E) {
    		//En caso de ocurrir un error no controlable, se establece la propiedad a falso.
    		xmlhttp = false;
    		}
    	}

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    	//En caso de ocurrir la excepción no controlable, se define el parametro con referencia
    	//al navegador origen de la solicitud.
    	xmlhttp = new XMLHttpRequest();
    	}
    
    return xmlhttp;
    
	} 
            
function cargar(url,parametro,objetivo){
	//Esta función establece los elementos de carga de la nueva url
	//sobre el div de contenido.
	var contenido = document.getElementById(objetivo);

	ajax=nuevoAjax(); //Se crea la instancia del nuevo objeto Ajax.
	ajax.open("GET", url + parametro,true); //Se carga la referencia del url sobre el objeto Ajax.
	
	ajax.onreadystatechange=function(){
		//Se valida que el estado de llamada del nuevo objeto Ajax corresponda
		//a un estado valido de carga.
		if(ajax.readyState==4){
			contenido.innerHTML=ajax.responseText; //Se carga en el contenido HTML del div destino el resultado del Ajax.
			}
		}
     
	ajax.send(null); //Aqui se determina que valores se envian como parametro a la pagina.	
	}

function cargarSync(url,parametro,objetivo){
	/*
	 * Esta función establece los elementos de carga de la nueva url
	 * sobre el div de contenido.
	 * ADVERTENCIA: DEBIDO A QUE ESTA FUNCION ESTABLECE CARGA SINCRONA DE LOS ELEMENTOS INVOCADOS,
	 * SE RECOMIENDA SOLO SU USO PARA LOS CASOS DE CARGAS MULTIPLES EN SECUENCIA SI NO HAY OTRA
	 * ALTERNATIVA.
	 */
	var contenido = document.getElementById(objetivo);

	ajax=nuevoAjax(); //Se crea la instancia del nuevo objeto Ajax.
	ajax.open("GET", url + parametro,false); //Se carga la referencia del url sobre el objeto Ajax.
	
	ajax.onreadystatechange=function(){
		//Se valida que el estado de llamada del nuevo objeto Ajax corresponda
		//a un estado valido de carga.
		if(ajax.readyState==4){
			contenido.innerHTML=ajax.responseText; //Se carga en el contenido HTML del div destino el resultado del Ajax.
			}
		}
     
	ajax.send(null); //Aqui se determina que valores se envian como parametro a la pagina.	
	}
let paso = 1
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id:"",
    nombre :"",
    fecha : "",
    hora : "",
    servicios: []
}

document.addEventListener('DOMContentLoaded',function(){
    iniciarApp();
})

function iniciarApp() {
    mostrarSeccion() //muestra la seccion dependiendo el paso
    tabs(); //Cambia la seccion cuando se precionen los tabs
    paginadores()  //mostrar u ocultar los paginadores 
    logicaPaginadores() 

    consultarApi() //consulta la api en el backend de php

    idCliente() //agregar el id del cliente a la cita
    nombreCliente() //agrega el nombre del cliente a la cita

    seleccionarFecha() //añade la fecha a la cita
    seleccionarHora() //añade la hora a la cita

    mostrarResumen()
}

function logicaPaginadores() {
    const botonAnterior = document.querySelector('#anterior')
    const botonSiguiente = document.querySelector('#siguiente')
    botonAnterior.addEventListener('click' , ()=>{
        if(paso<=pasoInicial)return; 
        paso--;
        mostrarSeccion();
        paginadores();
    })
    botonSiguiente.addEventListener('click' , ()=>{
        if(paso>= pasoFinal) return;
        paso++;
        mostrarSeccion();
        paginadores();
        if(paso == 3){
            mostrarResumen();
        }
    })
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button')
    botones.forEach(boton => {
        boton.addEventListener('click', (e)=>{

            //mostrar la nueva seccion
            paso= parseInt(e.target.dataset.paso)
            mostrarSeccion();
            paginadores();
            if(paso == 3){
                mostrarResumen();
            }
        })
    });
}

function paginadores() {
    const botonAnterior = document.querySelector('#anterior')
    const botonSiguiente = document.querySelector('#siguiente')

    if(paso == 1){
        botonSiguiente.classList.remove('ocultar')
        botonAnterior.classList.add('ocultar')
    } else if (paso == 3) {
        botonAnterior.classList.remove('ocultar')
        botonSiguiente.classList.add('ocultar')
    } else{
        botonSiguiente.classList.remove('ocultar')
        botonAnterior.classList.remove('ocultar')
    }
}

function mostrarSeccion() {
    //ocultar la seccion que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) seccionAnterior.classList.remove('mostrar');

    //cambiar a la seccion actual
    const selector = `#paso-${paso}`
    const seccion = document.querySelector(selector)
    seccion.classList.add('mostrar');
    
    //remover tab anterio
    const tabAnterior = document.querySelector('.actual')
    if(tabAnterior) tabAnterior.classList.remove('actual')

    //marcar tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual')
}

//las funciones asincronas esperan a que terminen las lineas que tengan await para seguir con su ejecucion
async function consultarApi() {
    try{
        const url = "http://localhost:3000/api/servicios"
        const resultado = await fetch(url); //se realiza la consulta a la api
        const servicios = await resultado.json(resultado) //se consigue el json de la consulta

        mostrarServicios(servicios);

    } catch(error){
        console.log(error)
    }

}

function mostrarServicios(servicios) {
    servicios.forEach(servicio=>{
        const {id,nombre,precio} = servicio; //destructuring, crea la variables basado en las keys de el arreglo a la derecha de la igualdad

        const nombreServicio = document.createElement('P')
        nombreServicio.classList.add('nombre-servicio')
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.classList.add('precio-servicio')
        precioServicio.textContent = `$${precio}`

        const servicioDiv = document.createElement('DIV')
        servicioDiv.classList.add('servicio')
        servicioDiv.dataset.idServicio = id
        // servicioDiv.onclick = seleccionarServicio(); si le pongo parentesis a la funcion es como si la estuviera llamando
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio)
        }

        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)
        
        document.querySelector('#servicios').appendChild(servicioDiv)
    })
}

function seleccionarServicio(servicio ) {
    const{id} = servicio
    const {servicios} = cita

    if(!cita.servicios.includes(servicio)) {
        cita.servicios = [...servicios,servicio];
    }
    else {
        cita.servicios = servicios.filter( agregado => agregado.id !== id ) //le paso como parametro una funcion que retorna un booleano a filter, y devuelve un arreglo con todos los objetos que cumplan la arrow function
    }

    const servicioDiv = document.querySelector(`[data-id-servicio="${id}"]`)
    servicioDiv.classList.toggle('seleccionado') 
}

function nombreCliente() {
   cita.nombre = document.querySelector('#nombre').value;
}
function idCliente() {
    cita.id = document.querySelector('#id').value;
 }

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');

   // const d = new Date()

    // agregar min a la fecha con js
    //inputFecha.setAttribute('min',`${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`)

    inputFecha.addEventListener('input',e=>{
        
        const dia = new Date(e.target.value).getUTCDay()

        // dia es el dia de la semana en el cual se esta eleigiendo la cita, si se incluye en el arreglo creado no lo permito y mando un arreglo
        if( [6,0].includes(dia) ){
            e.target.value = ""
            mostrarAlerta('Fines de semana no permitido', 'error','formulario');
        } else{
            cita.fecha = inputFecha.value
        }

    })
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora')
    inputHora.addEventListener('input',e=>{
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0]

        if(hora<10 || hora>=18){
            mostrarAlerta('hora invalida', 'error','formulario')
            e.target.value =""
        } else{
            cita.hora = horaCita;
        }
    })
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //limpiar el contenido
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild)
    }


    if(Object.values(cita).includes('') || cita.servicios.length===0) {
        mostrarAlerta('hacen falta datos o servicios crack', 'error','contenido-resumen',false)
        return
    } 
    
    const {nombre, fecha, hora, servicios} = cita;

    //titulo de la cita
    const headingCita=document.createElement('H3');
    headingCita.textContent = "Resumen de la cita";

    //informacion de cliente y cita
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`

    //Formatear la fecha en español
    const fechaObj = new Date(fecha);

    const mes = fechaObj.getMonth()
    const dia = fechaObj.getDate()+2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year,mes,dia))

    const opciones = {weekday: 'long', year: 'numeric', month: 'long', day:'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR',opciones)
    //const fechaFormateada = fechaUTC.toLocaleDateString('en-US',opciones) si cambio el idioma, la fecha se formatea basada en eso 

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`

    const horaCita = document.createElement('P');
    if(parseInt(hora.split(':')[0])<12) horaCita.innerHTML = `<span>Hora: </span>${hora} AM`
    else horaCita.innerHTML = `<span>Hora: </span>${hora} PM`
    

    //agregar los datos del usuario
    resumen.appendChild(headingCita)
    resumen.appendChild(nombreCliente)
    resumen.appendChild(fechaCita)
    resumen.appendChild(horaCita)

    //titulo de servicios
    const headingServicios=document.createElement('H3');
    headingServicios.textContent = "Resumen de servicios";
    headingServicios.classList.add('heading-servicios-resumen')

    //agregar los servicios
    resumen.appendChild(headingServicios)
    servicios.forEach(servicio=>{
        const {nombre,precio} = servicio; 

        const nombreServicio = document.createElement('P')
        nombreServicio.classList.add('nombre-servicio')
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement('P')
        precioServicio.classList.add('precio-servicio')
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`

        const servicioDiv = document.createElement('DIV')
        servicioDiv.classList.add('contenedor-servicio')

        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)

        resumen.appendChild(servicioDiv)
    })

    // Boton para Crear la cita
    const botonReservar= document.createElement('BUTTON')
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Recervar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar)

}

async function reservarCita() {
    const datos = new FormData() //form data es como el submit de un formulario
    //datos.append('nombre','Agustin') le agregamos los inputs de un formulario
    const{id , fecha, hora, servicios} = cita

    const idServicios = servicios.map( servicio => servicio.id)   //el for each solo itera, mientras que el map las coincidencias las va a ir agregando a la variable
    
    datos.append('hora', hora);
    datos.append('fecha', fecha);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);
   
    // console.log([...datos]) forma para ver si estamos creando el objeto bien

    try {
    // Peticion hacia la api
    const url = "http://localhost:3000/api/citas";

    //al pasarle el url a fetch ya esta haciendo el llamado a la api
    const respuesta = await fetch(url,{
        method: 'POST', //a fetch le especificamos el metodo de envio de la data
        body: datos, //aca estamos definiendo el contenido que estamos enviando a travez del metodo, en este caso definimos el $_post

    });

    const resultado = await respuesta.json()

    if(resultado.resultado){
        Swal.fire({
            icon: 'success',
            title: 'Cita Creada',
            text: `Tu cita fue creada correctamente para el: ${cita.fecha}`,
            button: 'OK'
        }).then(()=>{
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        })
    } 
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Hubo un error inesperado al guardar la cita!',
        }).then(()=>{
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        })
        
    }

    

    //console.log([...datos]) Como ver los datos para chekear el form data
}

function mostrarAlerta( mensaje, tipo,classContenedor,desaparece = true) {

    //prevenir que se genere mas de una alerta
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    //crear y agregar alerta al final del formulario
    const alerta = document.createElement('DIV')
    alerta.textContent = mensaje
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)

    const contenedor = document.querySelector(`.${classContenedor}`)
    contenedor.appendChild(alerta)

    //eliminar alerta
    if(desaparece){
        setTimeout(() => {
            alerta.remove()
        }, 3000);
    }
    
}


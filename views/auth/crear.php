<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>
<div class="alertas">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

</div>
<form action="/crear" method="post" class="formulario">
    <div class="campo">
        <label for="nombre">Nombre: </label>
        <input 
            type="text" 
            name="nombre" 
            id="nombre"
            placeholder="Tu nombre"
            value="<?php echo s($usuario->nombre) ?>"
        />
    </div>
    <div class="campo">
        <label for="apellido">Apellido: </label>
        <input 
            type="text" 
            name="apellido" 
            id="apellido"
            placeholder="Tu apellido"
            value="<?php echo s($usuario->apellido) ?>"
        />
    </div>
    <div class="campo">
        <label for="telefono">Teléfono: </label>
        <input 
            type="tel" 
            name="telefono" 
            id="telefono"
            placeholder="Tu telefono"
            value="<?php echo s($usuario->telefono) ?>"
        />
    </div>
    <div class="campo">
        <label for="email">E-mail: </label>
        <input 
            type="email" 
            name="email" 
            id="email"
            placeholder="Tu email"
            value="<?php echo s($usuario->email) ?>"
        />
    </div>
    <div class="campo">
        <label for="password">Password: </label>
        <input 
            type="password" 
            name="password" 
            id="password"
            placeholder="Crea un password"
        />
    </div>

    <input type="submit" value="Crear cuenta" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>
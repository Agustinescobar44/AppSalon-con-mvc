<h1 class="nombre-pagina">Olvidé mi password</h1>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/olvide" class="formulario" method="post">
    <div class="campo">
        <label for="email">Tu email</label>
        <input 
            type="email" 
            name="email" 
            id="email"
            placeholder="Tu email"
        />
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
    <a href="/crear">¿Aún no tienes una cuenta? Crea una!</a>
</div>
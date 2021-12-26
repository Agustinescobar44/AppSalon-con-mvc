<h1 class="nombre-pagina">Recuperar password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuacion</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error) return ?>
<form  class="formulario" method="POST">
    <div class="campo">
        <label for="password">Nuevo password:</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu nuevo password"
        >
    </div>

    <input type="submit" value="Guardar nuevo password" class="boton">
</form>

<div class="acciones">
    <a href="/">¿ya tienes cuenta? iniciar sesion</a>
    <a href="/crear">¿Aún no tienes una cuenta? Crea una!</a>
</div>
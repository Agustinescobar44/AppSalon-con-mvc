<h1 class="nombre-pagina">Crear Servicio</h1>
<p class="descripcion-pagina">Llena todos los campos para crear un nuevo servicio</p>

<?php include_once __DIR__ . '/../templates/barra.php' ?>
<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form class="formulario" action="/servicios/crear" method="post">
    <?php include_once __DIR__ . '/formulario.php' ?>

    <input type="submit" value="Guardar Servicio" class="boton">
</form>
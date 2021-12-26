<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php include_once __DIR__ . '/../templates/barra.php' ?>
<ul class="servicios">
<?php foreach($servicios as $servicio): ?>
    <li class="servicio">
        <p class="nombre-servicio"><?php echo $servicio->nombre ?></p>
        <p class="precio-servicio">Precio: <span>$<?php echo $servicio->precio ?></span></p>
        <div class="acciones">
            <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id ?>">Actualizar</a>

            <form action="/servicios/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $servicio->id ?>">
                <input class="boton-eliminar" type="submit" value="Eliminar">
            </form>
        </div>
    </li>
<?php endforeach; ?>
</ul>
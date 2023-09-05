<?php
include("../../bd.php");

if($_POST){
    print_r(($_POST));
    //recolectamos los datos del metodo POST
    $nombre_puesto=(isset($_POST["nombre_puesto"])? $_POST["nombre_puesto"]:"");
    //Preparar la insercion de los datos
    $sentencia=$conexion->prepare("INSERT INTO tbl_puestos(id, nombre) VALUES (null, :nombre)");
    // Asignando los valores que vienen del método POST (Los que vienen del formulario)
    $sentencia->bindParam(":nombre", $nombre_puesto);
    $sentencia->execute();
    $mensaje="Registro Creado";
    header("Location:index.php?mensaje=".$mensaje);
}

?>

<?php include("../../templates/header.php")?>
<br />
<div class="card">
    <div class="card-header">
        Puestos
    </div>
    <div class="card-body">
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="nombre_puesto" class="form-label">Nombre del Puesto</label>
      <input type="text"
        class="form-control" name="nombre_puesto" id="nombre_puesto" aria-describedby="helpId" placeholder="Nombre del puesto">
    </div>
    <button type="submit" class="btn btn-success">Agregar</button>
    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
</form>
    </div>
</div>
<?php include("../../templates/footer.php")?>

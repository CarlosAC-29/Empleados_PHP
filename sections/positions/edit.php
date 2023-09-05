<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT * FROM tbl_puestos WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    $nombre_puesto = $registro["nombre"];
}

if($_POST){
    //recolectamos los datos del metodo POST
    $nombre_puesto=(isset($_POST["nombre_puesto"])? $_POST["nombre_puesto"]:"");
    //Preparar la insercion de los datos
    $sentencia=$conexion->prepare("UPDATE tbl_puestos SET nombre=:nombre WHERE id=:id");
    // Asignando los valores que vienen del método POST (Los que vienen del formulario)
    $sentencia->bindParam(":nombre", $nombre_puesto);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $mensaje="Registro Actualizado";
    header("Location:index.php?mensaje=".$mensaje);

}

?>

<?php include("../../templates/header.php") ?>
<br />
<div class="card">
    <div class="card-header">
        Puestos
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="nombre_puesto" class="form-label">Nombre del Puesto</label>
                <input type="text" value="<?php echo $nombre_puesto; ?>" class="form-control" name="nombre_puesto" id="nombre_puesto" aria-describedby="helpId" placeholder="Nombre del puesto">
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php") ?>
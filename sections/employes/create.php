<?php
include("../../bd.php");

if ($_POST) {
    print_r($_POST);
    print_r($_FILES);

    $primer_nombre = (isset($_POST["primer_nombre"]) ? $_POST["primer_nombre"] : "");
    $segundo_nombre = (isset($_POST["segundo_nombre"]) ? $_POST["segundo_nombre"] : "");
    $primer_apellido = (isset($_POST["primer_apellido"]) ? $_POST["primer_apellido"] : "");
    $segundo_apellido = (isset($_POST["segundo_apellido"]) ? $_POST["segundo_apellido"] : "");


    $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");
    $cv = (isset($_FILES["cv"]['name']) ? $_FILES["cv"]['name'] : "");

    $id_puesto = (isset($_POST["id_puesto"]) ? $_POST["id_puesto"] : "");

    $fecha_ingreso = (isset($_POST["fecha_ingreso"]) ? $_POST["fecha_ingreso"] : "");

    $sentencia = $conexion->prepare("INSERT INTO `tbl_empleados` 
    (`id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`,
     `foto`, `cv`, `id_puesto`, `fecha_ingreso`) 
     VALUES (NULL, :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido, 
     :foto, :cv, :id_puesto, :fecha_ingreso);");

    $sentencia->bindParam(":primer_nombre", $primer_nombre);
    $sentencia->bindParam(":segundo_nombre", $segundo_nombre);
    $sentencia->bindParam(":primer_apellido", $primer_apellido);
    $sentencia->bindParam(":segundo_apellido", $segundo_apellido);

    $fecha = new DateTime();
    $nombreArchivo_foto = ($foto != '') ? $fecha->getTimestamp() . "" . $_FILES["foto"]['name'] : "";

    $tmp_foto=$_FILES["foto"]["tmp_name"];

    if($tmp_foto!=""){
        move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);
    }
    
    $sentencia->bindParam(":foto", $nombreArchivo_foto);

    $nombreArchivo_cv = ($cv != '') ? $fecha->getTimestamp() . "" . $_FILES["cv"]['name'] : "";

    $tmp_cv=$_FILES["cv"]["tmp_name"];

    if($tmp_cv!=""){
        move_uploaded_file($tmp_cv,"./".$nombreArchivo_cv);
    }
    $sentencia->bindParam(":cv", $nombreArchivo_cv);

    $sentencia->bindParam(":id_puesto", $id_puesto);
    $sentencia->bindParam(":fecha_ingreso", $fecha_ingreso);
    $sentencia->execute();



    $mensaje="Registro Creado";
    header("Location:index.php?mensaje=".$mensaje);
}
$sentencia = $conexion->prepare("SELECT * FROM `tbl_puestos`");
$sentencia->execute();
$list_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// $sentencia = $conexion->prepare("SELECT * FROM tbl_puestos");
// $sentencia->execute();
// $list_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include("../../templates/header.php") ?>
<br />
<div class="card">
    <div class="card-header">
        Datos del empleado
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="primer_nombre" class="form-label">Primer Nombre</label>
                <input type="text" class="form-control" name="primer_nombre" id="primer_nombre" aria-describedby="helpId" placeholder="Primer nombre">
            </div>
            <div class="mb-3">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" name="segundo_nombre" id="sedundo_nombre" aria-describedby="helpId" placeholder="Segundo nombre">
            </div>
            <div class="mb-3">
                <label for="primer_apellido" class="form-label">Primer apellido</label>
                <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" aria-describedby="helpId" placeholder="Primer apellido">
            </div>
            <div class="mb-3">
                <label for="segundo_apellido" class="form-label">Segundo apellido</label>
                <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" aria-describedby="helpId" placeholder="Segundo apellido">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">CV(PDF)</label>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV" aria-describedby="fileHelpId">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Puesto:</label>
                <select class="form-select form-select-sm" name="id_puesto" id="idpuesto">
                    <?php foreach ($list_tbl_puestos as $registro) { ?>
                        <option value="<?php echo $registro["id"] ?>">
                            <?php echo $registro["nombre"] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
                <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" aria-describedby="emailHelpId" placeholder="fecha de ingreso">
            </div>
            <button type="submit" class="btn btn-success">Agregar registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php") ?>
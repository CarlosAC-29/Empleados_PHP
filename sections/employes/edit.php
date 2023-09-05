<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    //Buscar archivo relacionado con el empleado
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_empleados` WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $primer_nombre = $registro["primer_nombre"];
    $segundo_nombre = $registro["segundo_nombre"];
    $primer_apellido = $registro["primer_apellido"];
    $segundo_apellido = $registro["segundo_apellido"];
    $foto = $registro["foto"];
    $cv = $registro["cv"];
    $id_puesto = $registro["id_puesto"];
    $fecha_ingreso = $registro["fecha_ingreso"];


    $sentencia = $conexion->prepare("SELECT * FROM `tbl_puestos`");
    $sentencia->execute();
    $list_tbl_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);


    // header("Location:index.php");
}

if ($_POST) {
    print_r($_POST);
    print_r($_FILES);

    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $primer_nombre = (isset($_POST["primer_nombre"]) ? $_POST["primer_nombre"] : "");
    $segundo_nombre = (isset($_POST["segundo_nombre"]) ? $_POST["segundo_nombre"] : "");
    $primer_apellido = (isset($_POST["primer_apellido"]) ? $_POST["primer_apellido"] : "");
    $segundo_apellido = (isset($_POST["segundo_apellido"]) ? $_POST["segundo_apellido"] : "");
    $id_puesto = (isset($_POST["id_puesto"]) ? $_POST["id_puesto"] : "");
    $fecha_ingreso = (isset($_POST["fecha_ingreso"]) ? $_POST["fecha_ingreso"] : "");

    $sentencia = $conexion->prepare("UPDATE `tbl_empleados` SET
    primer_nombre=:primer_nombre, 
    segundo_nombre=:segundo_nombre, 
    primer_apellido=:primer_apellido, 
    segundo_apellido=:segundo_apellido,
    id_puesto=:id_puesto,
    fecha_ingreso=:fecha_ingreso
    WHERE id=:id");

    $sentencia->bindParam(":primer_nombre", $primer_nombre);
    $sentencia->bindParam(":segundo_nombre", $segundo_nombre);
    $sentencia->bindParam(":primer_apellido", $primer_apellido);
    $sentencia->bindParam(":segundo_apellido", $segundo_apellido);
    $sentencia->bindParam(":id_puesto", $id_puesto);
    $sentencia->bindParam(":fecha_ingreso", $fecha_ingreso);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");

    $fecha = new DateTime();
    $nombreArchivo_foto = ($foto != '') ? $fecha->getTimestamp() . "" . $_FILES["foto"]['name'] : "";

    $tmp_foto = $_FILES["foto"]["tmp_name"];

    if ($tmp_foto != "") {
        move_uploaded_file($tmp_foto, "./" . $nombreArchivo_foto);

        //Buscar archivo relacionado con el empleado
        $sentencia = $conexion->prepare("SELECT foto FROM `tbl_empleados` WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "") {
            if (file_exists("./" . $registro_recuperado["foto"])) {
                unlink("./" . $registro_recuperado["foto"]);
            }
        }
        $sentencia = $conexion->prepare("UPDATE `tbl_empleados` SET foto=:foto WHERE id=:id");
        $sentencia->bindParam(":foto", $nombreArchivo_foto);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }

    $cv = (isset($_FILES["cv"]['name']) ? $_FILES["cv"]['name'] : "");

    $nombreArchivo_cv = ($cv != '') ? $fecha->getTimestamp() . "" . $_FILES["cv"]['name'] : "";
    $tmp_cv = $_FILES["cv"]["tmp_name"];
    if ($tmp_cv != "") {
        move_uploaded_file($tmp_cv, "./" . $nombreArchivo_cv);

        $sentencia = $conexion->prepare("SELECT cv FROM `tbl_empleados` WHERE id=:id");
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
        $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

        if (isset($registro_recuperado["cv"]) && $registro_recuperado["cv"] != "") {
            if (file_exists("./" . $registro_recuperado["cv"])) {
                unlink("./" . $registro_recuperado["cv"]);
            }
        }

        $sentencia = $conexion->prepare("UPDATE `tbl_empleados` SET cv=:cv WHERE id=:id");
        $sentencia->bindParam(":cv", $nombreArchivo_cv);
        $sentencia->bindParam(":id", $txtID);
        $sentencia->execute();
    }
    $sentencia->bindParam(":cv", $nombreArchivo_cv);


    $mensaje="Registro Actualizado";
    header("Location:index.php?mensaje=".$mensaje);
}

$sentencia = $conexion->prepare("SELECT *,
(SELECT nombre FROM tbl_puestos WHERE tbl_puestos.id=tbl_empleados.id_puesto limit 1) as puesto 
    FROM `tbl_empleados`");
$sentencia->execute();
$list_tbl_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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
                <label for="txtID" class="form-label">ID:</label>
                <input type="text" value="<?php echo $txtID; ?>" class="form-control" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="primer_nombre" class="form-label">Primer Nombre</label>
                <input value="<?php echo $primer_nombre; ?>" type="text" class="form-control" name="primer_nombre" id="primer_nombre" aria-describedby="helpId" placeholder="Primer nombre">
            </div>
            <div class="mb-3">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input value="<?php echo $segundo_nombre; ?>" type="text" class="form-control" name="segundo_nombre" id="sedundo_nombre" aria-describedby="helpId" placeholder="Segundo nombre">
            </div>
            <div class="mb-3">
                <label for="primer_apellido" class="form-label">Primer apellido</label>
                <input value="<?php echo $primer_apellido; ?>" type="text" class="form-control" name="primer_apellido" id="primer_apellido" aria-describedby="helpId" placeholder="Primer apellido">
            </div>
            <div class="mb-3">
                <label for="segundo_apellido" class="form-label">Segundo apellido</label>
                <input value="<?php echo $segundo_apellido; ?>" type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" aria-describedby="helpId" placeholder="Segundo apellido">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Foto:</label>
                <br />
                <img width="100" src="<?php echo $foto; ?>" class="rounded" alt="">
                <br /> <br />
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="Foto">
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">CV(PDF) :</label>
                <br />
                <a href="<?php echo $cv; ?>"><?php echo $cv; ?></a>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV" aria-describedby="fileHelpId">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Puesto:</label>
                <select class="form-select form-select-sm" name="id_puesto" id="idpuesto">
                    <?php foreach ($list_tbl_puestos as $registro) { ?>
                        <option <?php echo ($id_puesto == $registro["id"]) ? "selected" : ""; ?> value="<?php echo $registro["id"] ?>">
                            <?php echo $registro["nombre"] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
                <input value="<?php echo $fecha_ingreso; ?>" type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" aria-describedby="emailHelpId" placeholder="fecha de ingreso">
            </div>
            <button type="submit" class="btn btn-success">Actualizar registro</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php") ?>
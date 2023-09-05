<?php
include("../../bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("SELECT *,
    (SELECT nombre FROM tbl_puestos WHERE tbl_puestos.id=tbl_empleados.id_puesto limit 1) as puesto 
    FROM `tbl_empleados` WHERE id=:id");

    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    $primer_nombre = $registro["primer_nombre"];
    $segundo_nombre = $registro["segundo_nombre"];
    $primer_apellido = $registro["primer_apellido"];
    $segundo_apellido = $registro["segundo_apellido"];

    $nombre_completo = $primer_nombre . " " . $segundo_nombre . " " . $primer_apellido . " " . $segundo_apellido;

    $foto = $registro["foto"];
    $cv = $registro["cv"];
    $id_puesto = $registro["id_puesto"];
    $puesto = $registro["puesto"];
    $fecha_ingreso = $registro["fecha_ingreso"];

    $fechaInicio = new DateTime($fecha_ingreso);
    $fechaFin = new DateTime(date('Y-m-d'));
    $diferencia = date_diff($fechaInicio, $fechaFin);
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta recomendacion</title>
</head>

<body>
    <h1>Carta de recomendacion Laboral</h1>
    <br /><br />
    Cali, Colimbia a <strong><?php echo date('d M Y') ?></strong>
    <br /><br />
    A quien pueda interesar
    <br /><br />
    Reciba un cordial y respetuoso saludo.
    <br /><br />
    A tráves de estas líneas deseo hacer de su conocimiento que Sr(a) <strong><?php echo $nombre_completo ?></strong>,
    quien laboró en mi organizacion durante <strong><?php echo $diferencia->y ?> año(s)</strong>
    es un ciudadno con una conducta intachable. Ha demostrado ser un gran trabajador,
    compormetido, responsable y fiel cumplidor de sus tareas. Siempre ha manisfestado preocupacion por mejorar, capacitarse, y actualizar sus conocimientos.
    <br /><br />
    Durante estos años se ha desempeñado como: <strong> <?php echo $puesto ?> </strong>
    <br /><br />
    Es por eso que sugiero considere esta recomendacion
    <br /><br />
    Atentamente.
    <br /><br />
    Ing. Jose Manuel Martinez
</body>

</html>

<?php
$HTML = ob_get_clean();
require_once("../../lib/autoload.inc.php");

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$opciones = $dompdf->getOptions();
$opciones->set(array("isRemoteEnabled" => true));
$dompdf->setOptions($opciones);
$dompdf->loadHTML($HTML);
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment" => false));

?>
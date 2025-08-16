<?php
// Variables para la conexión
$host = '91.134.186.41';
$dbName = 'smartcret_dev';
$user = 'smartcret_dev';
$password = '8caMDgdJH@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertar'])) {
        $query = "SELECT MAX(id_vocabulario) AS max_id FROM vocabulario";
        $stmt = $pdo->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextIdVocabulario = $row ? $row['max_id'] + 1 : 1;

        $sql = "INSERT INTO vocabulario (id_vocabulario, id_idioma, valor) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Definir los placeholders según el requerimiento
        $placeholders = ['1' => 'ES', '2' => 'FR', '3' => 'EN-GB', '4' => 'EN-US', '5' => 'IT', '6' => 'DE'];

        foreach ($placeholders as $idIdioma => $idioma) {
            $valor = $_POST["lg$idIdioma"] ?? '';
            if (strlen($valor) >= 2) {
                $stmt->execute([$nextIdVocabulario, $idIdioma, $valor]);
            }
        }

        echo "Datos insertados correctamente.";
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Lenguajes</title>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $buscarValor = $_POST['valor_buscar'] ?? '';

    if (!empty($buscarValor)) {
        // Encuentra coincidencias y selecciona el id_vocabulario
        $stmt = $pdo->prepare("SELECT id_vocabulario FROM vocabulario WHERE valor LIKE ?");
        $stmt->execute(["%$buscarValor%"]);
        $coincidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ids = [];
        foreach ($coincidencias as $coincidencia) {
            $ids[] = $coincidencia['id_vocabulario'];
        }

        if (!empty($ids)) {
            echo "Resultados encontrados:<br>";
            $ids = array_unique($ids);
            foreach ($ids as $id) {
                $stmt = $pdo->prepare("SELECT valor, id_vocabulario FROM vocabulario WHERE id_vocabulario = ?");
                $stmt->execute([$id]);
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($resultados as $resultado) {
                    echo $resultado['id_vocabulario'] . ' - ' . $resultado['valor'] . "<br>";
                }
            }
        } else {
            echo "No se encontraron coincidencias.";
        }
    }
}
?>

<div class="contenido" style="max-width: 400px; margin: 0 auto;">
<b style="text-decoration: underline;">BUSQUEDA</b>
<br>
<br>
    <form method="post">
        <label for="valor_buscar">Buscar Valor:</label>
        <input type="text" id="valor_buscar" name="valor_buscar">
        <input type="submit" value="Buscar" name="buscar">
    </form>

<br>
<br>
<b style="text-decoration: underline;">NUEVA ENTRADA </b>
<br>
<br>
    <form method="post">
        <?php
        // Placeholders según especificación
        $placeholders = ['1' => 'ES', '2' => 'FR', '3' => 'EN-GB', '4' => 'EN-US', '5' => 'IT', '6' => 'DE'];
        foreach ($placeholders as $id => $placeholder):
        ?>
            <label for="lg<?= $id ?>">Lenguaje <?= $placeholder ?>:</label>
            <input type="text" id="lg<?= $id ?>" name="lg<?= $id ?>" placeholder="<?= $placeholder ?>">
            <br><br>
        <?php endforeach; ?>
        <br>
        <input type="submit" value="Insertar" name="insertar">
    </form>

</div>

</body>
</html>
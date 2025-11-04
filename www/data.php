<?php
$host = "192.168.56.20"; // VM db
$db   = "appdb";
$user = "appuser";
$pass = "appsecret";
$dsn  = "pgsql:host=$host;port=5432;dbname=$db";

try {
  $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  $stmt = $pdo->query("SELECT id, name, email FROM people ORDER BY id");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
  http_response_code(500);
  echo "<h1>Error conectando a PostgreSQL</h1>";
  echo "<pre>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Datos</title></head>
<body>
<h1>Personas</h1>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Nombre</th><th>Email</th></tr>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['id']) ?></td>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['email']) ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<p><a href="index.html">Volver</a></p>
</body>
</html>

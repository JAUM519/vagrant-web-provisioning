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
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Datos</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="container">
    <section class="card">
      <h1>Personas</h1>
      <p class="<?= $ok ? 'ok' : 'err' ?>">
        <?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?>
      </p>

      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th></tr>
          </thead>
          <tbody>
            <?php if (count($rows) === 0): ?>
              <tr><td colspan="3">Sin datos</td></tr>
            <?php else: ?>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r['id']) ?></td>
                  <td><?= htmlspecialchars($r['name']) ?></td>
                  <td><?= htmlspecialchars($r['email']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="nav">
        <a class="button" href="index.html">Volver</a>
      </div>
    </section>
  </main>
</body>
</html>
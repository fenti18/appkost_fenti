<?php
// Backup database (mysqldump)
if(isset($_POST['backup'])) {
    $file = 'backup_db_kost_'.date('Ymd_His').'.sql';
    $cmd = "mysqldump -u root db_kost > ../sql/$file";
    system($cmd);
    echo "<div class='msg success'>Backup berhasil: $file</div>";
}
// Restore database
if(isset($_POST['restore'])) {
    $file = $_POST['file_restore'];
    $cmd = "mysql -u root db_kost < ../sql/$file";
    system($cmd);
    echo "<div class='msg success'>Restore berhasil dari $file</div>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup/Restore Database Kos</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <div class="container">
        <h2>Backup Database</h2>
        <form method="post">
            <button class="btn" type="submit" name="backup">Backup Sekarang</button>
        </form>
        <h2>Restore Database</h2>
        <form method="post">
            <select name="file_restore">
                <?php foreach(glob('../sql/*.sql') as $f): ?>
                <option value="<?= basename($f) ?>"><?= basename($f) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn" type="submit" name="restore">Restore</button>
        </form>
    </div>
</body>
</html>

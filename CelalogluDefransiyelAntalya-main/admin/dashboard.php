<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$message = '';
$messageType = '';

// Yükleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
    $allowedImages = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $allowedVideos = ['video/mp4', 'video/webm', 'video/ogg'];
    $allowedTypes  = array_merge($allowedImages, $allowedVideos);
    $maxSize = 50 * 1024 * 1024; // 50 MB

    $files = $_FILES['media'];
    $uploadedCount = 0;
    $errorCount = 0;

    foreach ($files['tmp_name'] as $i => $tmpName) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $mimeType  = mime_content_type($tmpName);
        $fileSize  = $files['size'][$i];
        $origName  = basename($files['name'][$i]);
        $ext       = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedTypes)) {
            $errorCount++;
            continue;
        }
        if ($fileSize > $maxSize) {
            $errorCount++;
            continue;
        }

        $newName  = time() . '_' . uniqid() . '.' . $ext;
        $destPath = $uploadDir . $newName;

        if (move_uploaded_file($tmpName, $destPath)) {
            $uploadedCount++;
        } else {
            $errorCount++;
        }
    }

    if ($uploadedCount > 0) {
        $message = "$uploadedCount dosya başarıyla yüklendi!";
        $messageType = 'success';
    }
    if ($errorCount > 0) {
        $message .= ($message ? ' ' : '') . "$errorCount dosya yüklenemedi (tip/boyut hatası).";
        $messageType = $uploadedCount > 0 ? 'warning' : 'error';
    }
}

// Silme işlemi
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = $uploadDir . $fileToDelete;
    if (file_exists($filePath) && is_file($filePath)) {
        unlink($filePath);
        $message = 'Dosya silindi.';
        $messageType = 'success';
    }
}

// Çıkış
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Yüklenen dosyaları listele
$imageExts = ['jpg','jpeg','png','gif','webp'];
$videoExts = ['mp4','webm','ogg'];
$allFiles  = array_filter(scandir($uploadDir), fn($f) => $f !== '.' && $f !== '..');
usort($allFiles, fn($a,$b) => filemtime($uploadDir.$b) - filemtime($uploadDir.$a));
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – Celaloğlu Defransiyel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f0f0f; color: #ccc; min-height: 100vh; }

        header {
            background: #1a1a1a;
            border-bottom: 2px solid #e8b84b;
            padding: 16px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header h1 { color: #e8b84b; font-size: 18px; }
        header a { color: #888; font-size: 13px; text-decoration: none; }
        header a:hover { color: #e8b84b; }

        .container { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }

        .message {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .message.success { background: #1a3a1a; border: 1px solid #27ae60; color: #2ecc71; }
        .message.error   { background: #3a1a1a; border: 1px solid #c0392b; color: #e74c3c; }
        .message.warning { background: #3a2e1a; border: 1px solid #e67e22; color: #f39c12; }

        .upload-box {
            background: #1a1a1a;
            border: 2px dashed #444;
            border-radius: 12px;
            padding: 35px;
            text-align: center;
            margin-bottom: 40px;
            transition: border-color 0.2s;
        }
        .upload-box:hover { border-color: #e8b84b; }
        .upload-box h2 { color: #fff; margin-bottom: 8px; font-size: 17px; }
        .upload-box p  { color: #666; font-size: 13px; margin-bottom: 20px; }

        .upload-box input[type="file"] { display: none; }
        .btn-choose {
            display: inline-block;
            padding: 10px 24px;
            background: #2a2a2a;
            border: 1px solid #555;
            border-radius: 8px;
            color: #ccc;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: all 0.2s;
        }
        .btn-choose:hover { border-color: #e8b84b; color: #e8b84b; }

        .btn-upload {
            padding: 10px 28px;
            background: #e8b84b;
            color: #000;
            font-weight: 700;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-upload:hover { background: #d4a535; }

        #file-label { display: block; margin-top: 12px; font-size: 13px; color: #888; }

        .section-title {
            font-size: 16px;
            color: #fff;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }
        .section-title span { color: #e8b84b; }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }
        .media-item {
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #2a2a2a;
            position: relative;
        }
        .media-item:hover .overlay { opacity: 1; }
        .media-item img, .media-item video {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .btn-delete {
            padding: 8px 16px;
            background: #c0392b;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-delete:hover { background: #e74c3c; }
        .media-name {
            padding: 8px 10px;
            font-size: 11px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .empty { color: #555; font-size: 14px; padding: 20px 0; }

        .stats {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 16px 24px;
            text-align: center;
            flex: 1;
            min-width: 120px;
        }
        .stat-card .num { font-size: 28px; font-weight: 700; color: #e8b84b; }
        .stat-card .lbl { font-size: 12px; color: #666; margin-top: 4px; }
    </style>
</head>
<body>
<header>
    <h1>⚙️ Celaloğlu Defransiyel – Admin Paneli</h1>
    <div>
        <a href="../gallery.php" target="_blank" style="margin-right:20px;">🔗 Galeriyi Gör</a>
        <a href="?logout=1">Çıkış Yap</a>
    </div>
</header>

<div class="container">

    <?php if ($message): ?>
        <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php
        $imgCount = 0; $vidCount = 0;
        foreach ($allFiles as $f) {
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if (in_array($ext, $imageExts)) $imgCount++;
            elseif (in_array($ext, $videoExts)) $vidCount++;
        }
    ?>
    <div class="stats">
        <div class="stat-card">
            <div class="num"><?= $imgCount ?></div>
            <div class="lbl">Fotoğraf</div>
        </div>
        <div class="stat-card">
            <div class="num"><?= $vidCount ?></div>
            <div class="lbl">Video</div>
        </div>
        <div class="stat-card">
            <div class="num"><?= $imgCount + $vidCount ?></div>
            <div class="lbl">Toplam Dosya</div>
        </div>
    </div>

    <!-- Yükleme Formu -->
    <form method="POST" enctype="multipart/form-data">
        <div class="upload-box">
            <h2>📁 Fotoğraf veya Video Yükle</h2>
            <p>JPG, PNG, GIF, WEBP, MP4, WEBM — Maksimum 50 MB</p>
            <label class="btn-choose" for="mediaInput">Dosya Seç</label>
            <button type="submit" class="btn-upload">Yükle</button>
            <input type="file" id="mediaInput" name="media[]" multiple
                   accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/ogg">
            <span id="file-label">Henüz dosya seçilmedi</span>
        </div>
    </form>

    <!-- Medya Listesi -->
    <div class="section-title">
        Yüklenen Medyalar <span>(<?= $imgCount + $vidCount ?>)</span>
    </div>

    <?php if (empty($allFiles)): ?>
        <p class="empty">Henüz hiç dosya yüklenmedi.</p>
    <?php else: ?>
        <div class="media-grid">
            <?php foreach ($allFiles as $file):
                $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $path = '../uploads/' . $file;
                $isImg = in_array($ext, $imageExts);
                $isVid = in_array($ext, $videoExts);
                if (!$isImg && !$isVid) continue;
            ?>
            <div class="media-item">
                <?php if ($isImg): ?>
                    <img src="<?= htmlspecialchars($path) ?>" alt="<?= htmlspecialchars($file) ?>">
                <?php else: ?>
                    <video src="<?= htmlspecialchars($path) ?>" muted></video>
                <?php endif; ?>
                <div class="overlay">
                    <a href="?delete=<?= urlencode($file) ?>"
                       class="btn-delete"
                       onclick="return confirm('Bu dosyayı silmek istediğine emin misin?')">🗑️ Sil</a>
                </div>
                <div class="media-name"><?= htmlspecialchars($file) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
document.getElementById('mediaInput').addEventListener('change', function() {
    const count = this.files.length;
    document.getElementById('file-label').textContent =
        count > 0 ? count + ' dosya seçildi' : 'Henüz dosya seçilmedi';
});
</script>
</body>
</html>

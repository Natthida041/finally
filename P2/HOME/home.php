<?php
session_start();
require_once 'db.php';  // เรียกการเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ายังล็อกอินอยู่หรือไม่
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>จัดการข่าวสาร</title>
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --success-color: #4CAF50;
            --error-color: #f44336;
            --border-radius: 8px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #FCE4EC;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
                background-color: #FB6F92;
                color: white;
                padding: 20px;
                font-size: 24px;
                font-weight: 600;
                box-shadow: 0 4px 19px rgba(0, 0, 0, 0.1);
                position: relative;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .header-content {
                display: flex;
                align-items: center;
                justify-content: flex-start; /* ให้โลโก้และชื่อชิดซ้าย */
            }

            .logo {
                width: 80px; /* ขนาดของโลโก้ */
                height: auto;
                margin-right: 10px; /* ระยะห่างระหว่างโลโก้กับชื่อ */
            }

            .school-name {
                font-size: 24px;
                font-weight: bold;
            }

            .nav-links {
                margin-left: auto; /* ให้เมนูชิดขอบขวา */
                display: flex;
                justify-content: flex-end;
            }

            .nav-links a {
                color: white;
                text-decoration: none;
                margin-left: 20px;
                font-size: 18px;
            }
        form {
            background-color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #FB6F92;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        input[type="file"] {
            border: 1px solid #ddd;
            padding: 0.5rem;
            border-radius: var(--border-radius);
        }

        .submit-button {
            background-color: #FB6F92;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 1rem auto;
            text-align: center;
        }

        .submit-button:hover {
            background-color: #FFBCCD;
        }

        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .popup button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            form {
                padding: 1rem;
            }

            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav {
                margin-top: 1rem;
            }

            nav a {
                margin-left: 0;
                margin-right: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <div class="header-content">
        <img src="logo.png" alt="Logo" class="logo">
        <div class="school-name">เพิ่มข่าวสารใหม่</div>
    </div>
    <div class="nav-links">
        <a href="/P2/HOME/manage/page.php">หน้าหลัก</a>
    </div>
</div>


    <div class="container">
        <form action="save.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="type">ประเภทข่าว *</label>
                <select name="type" id="type" required>
                    <option value="1">งานวิชาการ</option>
                    <option value="2">อบรม/สัมมนา</option>
                    <option value="3">กิจกรรม</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">หัวข้อข่าว *</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="content">เนื้อข่าว *</label>
                <textarea name="content" id="content" rows="10" required></textarea>
            </div>

                        <div class="form-group">
                <label for="images">
                    <i class="fas fa-image" style="color: #4a90e2;"></i> แนบไฟล์ภาพ
                </label>
                <input type="file" name="images[]" id="images" accept="image/*" multiple>
            </div>

            <div class="form-group">
                <label for="pdf_file">
                    <i class="fas fa-file-pdf" style="color: #e74c3c;"></i> แนบไฟล์ PDF
                </label>
                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf">
            </div>

            <div class="form-group">
                <label for="video_file">
                    <i class="fas fa-video" style="color: #8e44ad;"></i> แนบไฟล์วิดีโอ
                </label>
                <input type="file" name="video_file" id="video_file" accept="video/*">
            </div>

            <div class="form-group">
                <label for="additional_details">รายละเอียดเพิ่มเติม</label>
                <input type="text" name="additional_details" id="additional_details" placeholder="เช่น https://www.example.com">
            </div>

            <div class="form-group">
                <label for="start_date">เริ่มเสนอข่าวตั้งแต่ *</label>
                <input type="date" name="start_date" id="start_date" required>
            </div>

            <button type="submit" class="submit-button">บันทึก</button>
        </form>
    </div>

    <div id="successPopup" class="popup">
        <p id="popupMessage"></p>
        <button onclick="closePopup()">ปิด</button>
    </div>

    <script>
        function showPopup(message) {
            document.getElementById('popupMessage').innerText = message;
            document.getElementById('successPopup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
        }

        <?php
        if (isset($_SESSION['success'])) {
            echo "showPopup('" . addslashes($_SESSION['success']) . "');";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "showPopup('" . addslashes($_SESSION['error']) . "');";
            unset($_SESSION['error']);
        }
        ?>
    </script>
</body>
</html>

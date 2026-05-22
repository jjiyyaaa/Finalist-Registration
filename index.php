<?php
// --- SETUP DATABASE ---
$config_path = __DIR__ . '/config.php';
if (!file_exists($config_path)) {
    die('Configuration file not found. Copy config.example.php to config.php and update database credentials.');
}
require_once $config_path;

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) { die("Database Connection Failed: " . mysqli_connect_error()); }

$step = 1;
$email_input = "";
$message = "";
$msg_type = "";

// --- Check Email (GATEKEEPER) ---
if (isset($_POST['check_email'])) {
    $email_input = mysqli_real_escape_string($conn, $_POST['email_check']);
    
    // VALIDASI EMAIL: semua email valid
    if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        $message = "Access Denied: Please enter a valid email address.";
        $msg_type = "error";
    } else {
        $check_query = "SELECT id, fullname FROM finalists WHERE email = '$email_input' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $row = mysqli_fetch_assoc($check_result);
            $message = "Access Denied! The email <strong>$email_input</strong> is already registered under the name <strong>" . $row['fullname'] . "</strong>.";
            $msg_type = "fatal_error"; 
        } else {
            $step = 2; 
        }
    }
}

// --- Submit Final Form ---
if (isset($_POST['submit_final'])) {
    $email = mysqli_real_escape_string($conn, $_POST['locked_email']);
    
    $check_again = mysqli_query($conn, "SELECT id FROM finalists WHERE email = '$email' LIMIT 1");
    if (mysqli_num_rows($check_again) > 0) {
        $message = "Security Alert: This email is already registered!";
        $msg_type = "fatal_error";
        $step = 1;
    } else {
        $fullname = mysqli_real_escape_string($conn, $_POST['fullname'] ?? '');
        $nickname = mysqli_real_escape_string($conn, $_POST['nickname'] ?? '');
        $major = mysqli_real_escape_string($conn, $_POST['major'] ?? '');
        $batch = mysqli_real_escape_string($conn, $_POST['batch'] ?? '');
        $ig = mysqli_real_escape_string($conn, $_POST['instagram'] ?? '');
        $wa = mysqli_real_escape_string($conn, $_POST['whatsapp'] ?? '');
        $motivation = mysqli_real_escape_string($conn, $_POST['motivation'] ?? '');
        
        $height_mr = (!empty($_POST['height_mr']) && is_numeric($_POST['height_mr'])) ? $_POST['height_mr'] : "NULL";
        $height_ms = (!empty($_POST['height_ms']) && is_numeric($_POST['height_ms'])) ? $_POST['height_ms'] : "NULL";
        $gpa_temp = !empty($_POST['gpa']) ? str_replace(',', '.', $_POST['gpa']) : "";
        $gpa = (is_numeric($gpa_temp)) ? $gpa_temp : "NULL"; 
        
        $glasses = $_POST['glasses'] ?? '';
        $prescription = ($glasses == 'No') ? '-' : mysqli_real_escape_string($conn, $_POST['prescription'] ?? '-');
        $contact = $_POST['contact_lenses'] ?? '';
        $medical = mysqli_real_escape_string($conn, $_POST['medical'] ?? '-');

        $final_cv_path = "";
        if (!empty($_FILES['cv']['name'])) {
            $cv_name = basename($_FILES['cv']['name']);
            $cv_ext = strtolower(pathinfo($cv_name, PATHINFO_EXTENSION));
            $cv_tmp = $_FILES['cv']['tmp_name'];
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            if ($cv_ext === 'pdf') {
                $final_cv_path = $upload_dir . time() . "_" . preg_replace('/[^A-Za-z0-9_\-.]/', '_', $cv_name);
                move_uploaded_file($cv_tmp, $final_cv_path);
            } else {
                $message = "Only PDF files are allowed for CV uploads.";
                $msg_type = "error";
                $step = 2;
            }
        }

        $sql = "INSERT INTO finalists (fullname, nickname, email, major, batch, instagram, whatsapp, motivation, cv_path, height_mr, height_ms, gpa, wear_glasses, prescription, contact_lenses, medical_history) 
                VALUES ('$fullname', '$nickname', '$email', '$major', '$batch', '$ig', '$wa', '$motivation', '$final_cv_path', $height_mr, $height_ms, $gpa, '$glasses', '$prescription', '$contact', '$medical')";

        if (mysqli_query($conn, $sql)) {
            $step = 3;
            $message = "Your masterpiece has been submitted successfully!";
        } else {
            $message = "Database Error: " . mysqli_error($conn);
            $msg_type = "error";
            $step = 2;
            $email_input = $email;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | Mr & Ms President University 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            min-height: 100vh;
            background-image: url('background.png');
            background-size: cover;
            background-position: center;
            color: #e2e8f0; 
            font-family: 'Montserrat', sans-serif; 
            display: flex; flex-direction: column; align-items: center; padding: 40px 20px;
            background-color: #0a192f;
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .gold-text { color: #d4af37; text-shadow: 2px 2px 4px rgba(0,0,0,1), -1px -1px 0 rgba(0,0,0,1); }
        .gold-bg { background-color: #d4af37; }
        .glass-card { 
            background: rgba(10, 25, 47, 0.50); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(212, 175, 55, 0.4); box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            width: 100%; max-width: 768px;
        }
        .form-input { 
            background: rgba(17, 34, 64, 0.9); border: 1px solid rgba(212, 175, 55, 0.3); width: 100%; padding: 0.75rem; 
            border-radius: 0.375rem; color: white; 
        }
        .form-input:focus { border-color: #d4af37; outline: none; box-shadow: 0 0 10px rgba(212,175,55,0.2); }
        .form-input:read-only { background: rgba(0,0,0,0.5); color: #888; border-color: #444; cursor: not-allowed; }
        .form-input:disabled { background: rgba(0,0,0,0.5); color: #666; border-color: #444; cursor: not-allowed; }
        
        input[type=number]::-webkit-outer-spin-button, input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        input[type="file"]::file-selector-button {
            background-color: #d4af37; color: #0a192f; border: none; padding: 5px 12px; margin-right: 15px; border-radius: 4px; 
            cursor: pointer; font-family: 'Montserrat', sans-serif; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 10px;
        }
        .hidden { display: none; }
        .animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Agar teks broadcast rapi sesuai aslinya */
        .broadcast-text {
            white-space: pre-wrap; /* Menjaga spasi dan enter */
            text-align: left;
            font-size: 0.85rem;
            line-height: 1.5;
            color: #cbd5e1;
        }

        /* Security CSS Only (No JS conflict) */
        body { -webkit-user-select: none; user-select: none; }
        input, textarea { -webkit-user-select: text; user-select: text; }

        @media (max-width: 768px) {
            body { background-attachment: scroll; padding: 15px 10px; }
            .glass-card { padding: 1.5rem; background: rgba(10, 25, 47, 0.75); }
            h1 { font-size: 1.8rem !important; } 
        }
    </style>
</head>
<body>

    <div class="glass-card p-8 rounded-lg text-center mb-6 relative overflow-hidden">
        <h1 class="font-serif text-3xl md:text-5xl gold-text mb-2 uppercase tracking-widest drop-shadow-lg">
            Mr. & Ms. President University
        </h1>
        <p class="font-serif italic text-lg text-gray-300">"The Museum of Living Art"</p>
        <div class="flex items-center justify-center mt-4">
            <div class="h-px w-10 gold-bg opacity-50"></div>
            <span class="mx-4 text-[9px] tracking-[0.4em] gold-text uppercase font-bold">Where Legacy is Painted in Motion</span>
            <div class="h-px w-10 gold-bg opacity-50"></div>
        </div>
    </div>

    <div class="glass-card p-8 rounded-lg shadow-2xl relative" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center;">
        
        <?php if($message): ?>
            <div class="mb-6 p-4 rounded text-center border animate-fadeIn 
                <?= $msg_type == 'error' || $msg_type == 'fatal_error' ? 'bg-red-900/50 border-red-500 text-red-200' : 'bg-green-900/50 border-green-500 text-green-200' ?>">
                <p class="font-serif"><?= $message ?></p>
            </div>
        <?php endif; ?>

        <?php if($step == 1 && $msg_type != 'fatal_error'): ?>
            
            <div class="animate-fadeIn space-y-6">
                <div class="broadcast-text mb-8 border-b border-gold/20 pb-8">
🏛️ 𝐌𝐑. & 𝐌𝐒. 𝐏𝐑𝐄𝐒𝐈𝐃𝐄𝐍𝐓 𝐔𝐍𝐈𝐕𝐄𝐑𝐒𝐈𝐓𝐘 𝟐𝟎𝟐𝟔: 𝐎𝐏𝐄𝐍 𝐑𝐄𝐆𝐈𝐒𝐓𝐑𝐀𝐓𝐈𝐎𝐍 𝐅𝐈𝐍𝐀𝐋𝐈𝐒𝐓 🏛️

Greetings, Living Masterpieces! 👋🏻

Mr. & Ms. President University 2026 invites you to step into "𝗧𝗵𝗲 𝗠𝘂𝘀𝗲𝘂𝗺 𝗼𝗳 𝗟𝗶𝘃𝗶𝗻𝗴 𝗔𝗿𝘁." 🎨✨

Mr. & Ms. President University 2026 is an annual university ambassador and pageant-style event that showcases students’ leadership, intellect, character, creativity, and talent, while representing the values and identity of President University on and beyond campus.

Are you ready to transform from a sketch into a living masterpiece and carve your legacy?

🗓️ 𝗠𝗮𝗿𝗸 𝘆𝗼𝘂𝗿 𝗰𝗮𝗹𝗲𝗻𝗱𝗮𝗿: 16th - 20th January 2026

📝 𝗥𝘂𝗹𝗲𝘀 & 𝗥𝗲𝗴𝘂𝗹𝗮𝘁𝗶𝗼𝗻𝘀 𝗟𝗶𝗻𝗸𝘀: <a href="https://bit.ly/RnR" target="_blank" class="text-blue-400 hover:underline">https://bit.ly/RnR</a>
                    
🔗 𝗥𝗲𝗴𝗶𝘀𝘁𝗲𝗿 𝗵𝗲𝗿𝗲: <a href="https://bit.ly/regist" target="_blank" class="text-blue-400 hover:underline">https://bit.ly/regist</a>

Unleash your potential and let your legacy be painted in motion! 🖼️💫

𝗛𝗮𝘃𝗲 𝗾𝘂𝗲𝘀𝘁𝗶𝗼𝗻𝘀? 𝗖𝗼𝗻𝘁𝗮𝗰𝘁 𝘂𝘀: 
📲 WA: <a href="https://wa.me/6289602403137" target="_blank" class="text-green-400 hover:underline">+62 896-0240-3137</a> (Ghaziya)
                    
𝗕𝗲𝘀𝘁 𝗥𝗲𝗴𝗮𝗿𝗱𝘀, 
𝗣𝗥. 𝗜𝗻𝘁𝗲𝗿𝗻𝗮𝗹 𝗠𝗿. & 𝗠𝘀. 𝗣𝗿𝗲𝘀𝗶𝗱𝗲𝗻𝘁 𝗨𝗻𝗶𝘃𝗲𝗿𝘀𝗶𝘁𝘆 𝟮𝟬𝟮𝟲
                </div>

                <form action="" method="POST">
                    <div class="text-center mb-4">
                        <h2 class="text-lg gold-text uppercase tracking-widest font-bold">Identify Yourself</h2>
                        <p class="text-xs text-gray-400">Enter your email to proceed.</p>
                    </div>
                    
                    <div>
                        <input type="email" name="email_check" placeholder="ex@gmail.com" required class="form-input text-center text-lg py-3">
                    </div>

                    <button type="submit" name="check_email" class="w-full py-4 mt-4 gold-bg text-black font-bold uppercase tracking-[0.2em] hover:brightness-110 transition-all text-xs">
                        Start Registration
                    </button>
                </form>
            </div>
        
        <?php elseif($msg_type == 'fatal_error'): ?>
            <div class="text-center animate-fadeIn">
                <div class="text-6xl mb-4">🚫</div>
                <h2 class="text-2xl font-serif gold-text mb-2">Access Restricted</h2>
                <p class="text-gray-400 text-sm">You have already completed this registration.</p>
                <p class="text-gray-500 text-xs mt-8">Mr & Ms President University 2026</p>
            </div>

        <?php elseif($step == 2): ?>
            <form action="" method="POST" enctype="multipart/form-data" id="regForm" class="flex-grow flex flex-col justify-between animate-fadeIn">
                
                <input type="hidden" name="locked_email" value="<?= htmlspecialchars($email_input) ?>">

                <div class="mb-6 border-b border-gold/20 pb-4 text-center">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400">Registering as:</p>
                    <p class="gold-text font-bold text-lg"><?= htmlspecialchars($email_input) ?></p>
                </div>
                
                <div id="slide1" class="space-y-5">
                    <h3 class="gold-text font-serif text-xl border-b border-gold/30 pb-2 italic">I. Personal Canvas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Full Name</label><input type="text" id="fullname" name="fullname" required class="form-input"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Nickname</label><input type="text" id="nickname" name="nickname" required class="form-input"></div>
                        
                        <div>
                            <label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Email (Locked)</label>
                            <input type="text" value="<?= htmlspecialchars($email_input) ?>" readonly class="form-input">
                        </div>

                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Major</label><input type="text" id="major" name="major" required class="form-input"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Batch</label><input type="text" id="batch" name="batch" placeholder="e.g. 2025" required class="form-input"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Instagram (Use @)</label><input type="text" id="instagram" name="instagram" placeholder="@username" required class="form-input"></div>
                        <div class="md:col-span-2"><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">WhatsApp (Start with 08...)</label><input type="number" id="whatsapp" name="whatsapp" placeholder="08xxxxxxxxxx" required class="form-input"></div>
                    </div>
                    <button type="button" onclick="validateAndNext()" class="w-full py-4 mt-6 border border-gold-text gold-text font-bold uppercase tracking-[0.2em] hover:bg-gold-text hover:text-navy-900 transition-all text-xs">Next Gallery</button>
                </div>

                <div id="slide2" class="space-y-5 hidden animate-fadeIn">
                    <h3 class="gold-text font-serif text-xl border-b border-gold/30 pb-2 italic">II. The Portrait</h3>
                    <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Motivation</label><textarea name="motivation" rows="2" required class="form-input"></textarea></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Upload CV (PDF)</label><input type="file" name="cv" accept=".pdf" required class="form-input text-xs"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">GPA (4.00)</label><input type="text" id="gpa" name="gpa" placeholder="Use DOT, e.g. 4.00" required class="form-input"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Height - Mr. (cm)</label><input type="number" id="height_mr" name="height_mr" oninput="toggleHeight('mr')" placeholder="Fill here if Male" class="form-input"></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Height - Ms. (cm)</label><input type="number" id="height_ms" name="height_ms" oninput="toggleHeight('ms')" placeholder="Fill here if Female" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Wear glasses?</label><select name="glasses" id="glasses" onchange="togglePrescription()" required class="form-input"><option value="" disabled selected>Select One</option><option value="No">No</option><option value="Yes">Yes</option></select></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Prescription</label><input type="text" id="prescription" name="prescription" placeholder="Type '-' if none" class="form-input" disabled></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Willing to use lenses?</label><select name="contact_lenses" required class="form-input"><option value="" disabled selected>Select One</option><option value="Yes">Yes</option><option value="No">No</option></select></div>
                        <div><label class="block text-[10px] uppercase gold-text mb-1 tracking-widest">Medical History</label><input type="text" name="medical" placeholder="Type '-' if none" required class="form-input"></div>
                    </div>
                    <div class="flex gap-4 mt-6">
                        <button type="button" onclick="prevSlide()" class="flex-1 py-4 border border-gray-500 text-gray-400 uppercase tracking-widest font-bold text-xs hover:bg-gray-800">Back</button>
                        <button type="submit" name="submit_final" onclick="return validateFinal()" class="flex-1 py-4 gold-bg text-black font-bold uppercase tracking-widest hover:brightness-110 text-xs">Finish Masterpiece</button>
                    </div>
                </div>
            </form>

        <?php elseif($step == 3): ?>
            <div class="text-center animate-fadeIn py-6">
                <div class="text-6xl mb-4">✨</div>
                <h2 class="text-2xl font-serif gold-text mb-6">Registration Completed</h2>
                
                <div class="broadcast-text mb-8">
Thank you for taking the first step toward becoming a part of Mr. & Ms. President University 2026. Your courage to register and challenge yourself reflects your passion, confidence, and potential to represent President University.

To stay updated with important announcements, selection schedules, and further information, please join our official WhatsApp group through the link below:
                </div>

                <a href="https://chat.whatsapp.com/" target="_blank" class="block w-full py-4 mb-8 bg-green-600 text-white font-bold uppercase tracking-[0.2em] rounded hover:bg-green-500 transition-all text-xs shadow-lg">
                    Click the link to join the WhatsApp Group👉
                </a>

                <div class="broadcast-text text-center text-xs text-gray-400">
Once again, thank you for your interest and enthusiasm. We look forward to seeing you grow, shine, and take part in an unforgettable journey as a finalist candidate.

Warm regards,
PR. Internal of Mr. & Ms. President University 2026
                </div>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function validateAndNext() {
            let ig = document.getElementById('instagram').value;
            let wa = document.getElementById('whatsapp').value;
            let allInputs = document.querySelectorAll('#slide1 input:not([readonly])');
            for (let input of allInputs) { if(input.value.trim() === "") { alert("Please fill in all fields."); return; } }
            if (!ig.startsWith('@')) { alert("Instagram username must start with '@'"); return; }
            if (!wa.startsWith('08')) { alert("WhatsApp number must start with '08'"); return; }
            document.getElementById('slide1').classList.add('hidden'); document.getElementById('slide2').classList.remove('hidden'); window.scrollTo(0, 0);
        }
        function prevSlide() { document.getElementById('slide2').classList.add('hidden'); document.getElementById('slide1').classList.remove('hidden'); window.scrollTo(0, 0); }
        function toggleHeight(gender) {
            let mr = document.getElementById('height_mr'); let ms = document.getElementById('height_ms');
            if (gender === 'mr') { if (mr.value.length > 0) { ms.disabled = true; ms.value = ""; ms.placeholder = "Disabled"; ms.required = false; } else { ms.disabled = false; ms.placeholder = "Fill here if Female"; ms.required = true; } } 
            else { if (ms.value.length > 0) { mr.disabled = true; mr.value = ""; mr.placeholder = "Disabled"; mr.required = false; } else { mr.disabled = false; mr.placeholder = "Fill here if Male"; mr.required = true; } }
        }
        function togglePrescription() {
            let glasses = document.getElementById('glasses').value; let presInput = document.getElementById('prescription');
            if (glasses === 'No') { presInput.value = "-"; presInput.disabled = true; presInput.style.backgroundColor = "rgba(0,0,0,0.5)"; } else { presInput.value = ""; presInput.disabled = false; presInput.style.backgroundColor = "rgba(17, 34, 64, 0.9)"; }
        }
        function validateFinal() {
            let gpa = document.getElementById('gpa').value; let mr = document.getElementById('height_mr').value; let ms = document.getElementById('height_ms').value;
            if (gpa.includes(',')) { alert("GPA must use DOT (.) separator!"); return false; }
            if (mr === "" && ms === "") { alert("Please fill in your Height!"); return false; }
            return true;
        }
        // [SECURITY] DISABLE RIGHT CLICK & F12 ONLY. ALLOW TEXT INPUT.
        document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        document.onkeydown = function(e) { if (e.keyCode == 123 || (e.ctrlKey && e.shiftKey && e.keyCode == 73) || (e.ctrlKey && e.shiftKey && e.keyCode == 74) || (e.ctrlKey && e.keyCode == 85) || (e.ctrlKey && e.keyCode == 67)) { return false; } };
    </script>
    <style> 
        body { -webkit-user-select: none; user-select: none; } 
        /* Pastikan input bisa di-klik dan diisi */
        input, textarea { -webkit-user-select: text !important; user-select: text !important; } 
    </style>
</body>
</html>
<?php
// 1. Koneksi ke Database
$config_path = __DIR__ . '/config.php';
if (!file_exists($config_path)) {
    die('Configuration file not found. Copy config.example.php to config.php and update database credentials.');
}
require_once $config_path;

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Ambil data dari tabel 'finalists'
$query = "SELECT * FROM finalists ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$sampleRows = [];
if (mysqli_num_rows($result) === 0) {
    $sampleRows = [
        [
            'fullname' => 'Ari Putra',
            'nickname' => 'Ari',
            'email' => 'ari@example.com',
            'major' => 'Computer Science',
            'batch' => '2024',
            'instagram' => '@ari.cs',
            'whatsapp' => '081234567890',
            'motivation' => 'I want to represent the university with passion and integrity.',
            'cv_path' => '',
            'height_mr' => 175,
            'height_ms' => 170,
            'gpa' => '3.85',
            'wear_glasses' => 'No',
            'prescription' => '-',
            'contact_lenses' => 'No',
            'medical_history' => 'No medical issues',
            'created_at' => date('Y-m-d H:i:s'),
        ],
        [
            'fullname' => 'Nadia Sari',
            'nickname' => 'Nadia',
            'email' => 'nadia@example.com',
            'major' => 'Communication',
            'batch' => '2023',
            'instagram' => '@nadiacomm',
            'whatsapp' => '081298765432',
            'motivation' => 'I am ready to be the best ambassador for my university.',
            'cv_path' => '',
            'height_mr' => 165,
            'height_ms' => 160,
            'gpa' => '3.92',
            'wear_glasses' => 'Yes',
            'prescription' => 'SPH -1.75',
            'contact_lenses' => 'No',
            'medical_history' => 'Minor allergies',
            'created_at' => date('Y-m-d H:i:s'),
        ],
        [
            'fullname' => 'Rizky Ahmad',
            'nickname' => 'Rizky',
            'email' => 'rizky@example.com',
            'major' => 'Design',
            'batch' => '2025',
            'instagram' => '@rizkydesign',
            'whatsapp' => '081212345678',
            'motivation' => 'My creative energy will bring a new color to the stage.',
            'cv_path' => '',
            'height_mr' => 178,
            'height_ms' => 168,
            'gpa' => '3.70',
            'wear_glasses' => 'No',
            'prescription' => '-',
            'contact_lenses' => 'No',
            'medical_history' => 'None',
            'created_at' => date('Y-m-d H:i:s'),
        ],
    ];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery | Mr & Ms President University 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { 
            min-height: 100vh;
            background-image: url('background.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #e2e8f0; 
            font-family: 'Montserrat', sans-serif; 
            padding: 40px 20px;
            background-color: #0a192f;
        }

        .font-serif { font-family: 'Playfair Display', serif; }
        .gold-text { color: #d4af37; text-shadow: 2px 2px 4px rgba(0,0,0,1), -1px -1px 0 rgba(0,0,0,1); }
        .gold-bg { background-color: #d4af37; }
        .gold-border { border-color: #d4af37; }

        .glass-card { 
            background: rgba(10, 25, 47, 0.9); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(212, 175, 55, 0.4);
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
            border-radius: 0.5rem;
        }

        th { font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase; color: #d4af37; }
        td { font-size: 13px; vertical-align: top; }
    </style>
</head>
<body>

    <div class="max-w-7xl mx-auto w-full glass-card p-8 mb-8 flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
        <div class="text-center md:text-left z-10">
            <h1 class="font-serif text-3xl gold-text uppercase tracking-widest drop-shadow-lg">
                The Masterpiece Gallery
            </h1>
            <p class="font-serif italic text-sm text-gray-300 mt-1">"Curating the finest artworks of President University"</p>
        </div>

        <div class="mt-6 md:mt-0 z-10">
            <a href="index.php" class="inline-block px-6 py-3 border border-[#d4af37] rounded font-bold uppercase tracking-[0.2em] text-[10px] text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0a192f] transition-colors">
                Open Registration Form
            </a>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4af37] to-transparent opacity-30"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#081221] border-b border-[#d4af37]/30">
                    <tr>
                        <th class="p-6">No</th>
                        <th class="p-6">Candidate Identity</th>
                        <th class="p-6">Academics & Stats</th>
                        <th class="p-6">Contacts</th>
                        <th class="p-6">Medical & CV</th>
                        <th class="p-6">Motivation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d4af37]/20">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-white/5 transition duration-300">
                            
                            <td class="p-6 text-gray-500 text-xs"><?= $no++; ?></td>

                            <td class="p-6">
                                <div class="font-bold text-white text-lg font-serif tracking-wide"><?= htmlspecialchars($row['fullname'] ?? ''); ?></div>
                                <div class="text-xs gold-text mt-1 uppercase tracking-widest">aka <?= htmlspecialchars($row['nickname'] ?? ''); ?></div>
                                <div class="text-[10px] text-gray-400 mt-2">Registered: <?= date('d M Y', strtotime($row['created_at'])); ?></div>
                            </td>
                            
                            <td class="p-6">
                                <div class="mb-2">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Major / Batch</span>
                                    <span class="text-sm"><?= htmlspecialchars($row['major'] ?? '-'); ?> / <?= htmlspecialchars($row['batch'] ?? '-'); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">GPA</span>
                                    <span class="text-sm font-bold gold-text"><?= $row['gpa'] ? number_format((float)$row['gpa'], 2, '.', '') : '-'; ?></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Height</span>
                                    <span class="text-xs">Mr: <?= $row['height_mr'] ?: '-'; ?> | Ms: <?= $row['height_ms'] ?: '-'; ?></span>
                                </div>
                            </td>
                            
                            <td class="p-6">
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">WhatsApp</span>
                                    <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['whatsapp'])); ?>" target="_blank" class="text-green-400 hover:underline text-xs">
                                        <?= htmlspecialchars($row['whatsapp']); ?>
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Email</span>
                                    <span class="text-xs text-gray-300"><?= htmlspecialchars($row['email']); ?></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Instagram</span>
                                    <span class="text-xs gold-text"><?= htmlspecialchars($row['instagram']); ?></span>
                                </div>
                            </td>
                            
                            <td class="p-6">
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Visual Aid</span>
                                    <span class="text-xs">Glasses: <?= $row['wear_glasses']; ?></span>
                                    <?php if($row['wear_glasses'] == 'Yes'): ?>
                                        <div class="text-[10px] text-yellow-500">(<?= htmlspecialchars($row['prescription']); ?>)</div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Medical History</span>
                                    <span class="text-[10px] italic text-gray-300"><?= $row['medical_history'] ? htmlspecialchars($row['medical_history']) : '-'; ?></span>
                                </div>
                                
                                <?php if (!empty($row['cv_path'])): ?>
                                    <a href="<?= htmlspecialchars($row['cv_path']); ?>" target="_blank" 
                                       class="inline-flex items-center px-3 py-1 border border-[#d4af37] rounded text-[10px] uppercase font-bold text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0a192f] transition-colors mt-1">
                                        Open CV (PDF)
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-400 text-[10px] uppercase font-bold">No CV</span>
                                <?php endif; ?>
                            </td>

                            <td class="p-6 w-64">
                                <div class="max-h-32 overflow-y-auto pr-2 text-xs text-gray-300 italic scrollbar-thin scrollbar-thumb-gold scrollbar-track-navy">
                                    "<?= htmlspecialchars($row['motivation'] ?? ''); ?>"
                                </div>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    <?php elseif (count($sampleRows) > 0): ?>
                        <?php $no = 1; foreach ($sampleRows as $row): ?>
                        <tr class="hover:bg-white/5 transition duration-300">
                            <td class="p-6 text-gray-500 text-xs"><?= $no++; ?></td>
                            <td class="p-6">
                                <div class="font-bold text-white text-lg font-serif tracking-wide"><?= htmlspecialchars($row['fullname']); ?></div>
                                <div class="text-xs gold-text mt-1 uppercase tracking-widest">aka <?= htmlspecialchars($row['nickname']); ?></div>
                                <div class="text-[10px] text-gray-400 mt-2">Registered: <?= date('d M Y', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td class="p-6">
                                <div class="mb-2">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Major / Batch</span>
                                    <span class="text-sm"><?= htmlspecialchars($row['major']); ?> / <?= htmlspecialchars($row['batch']); ?></span>
                                </div>
                                <div class="mb-2">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">GPA</span>
                                    <span class="text-sm font-bold gold-text"><?= htmlspecialchars($row['gpa']); ?></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Height</span>
                                    <span class="text-xs">Mr: <?= htmlspecialchars($row['height_mr']); ?> | Ms: <?= htmlspecialchars($row['height_ms']); ?></span>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">WhatsApp</span>
                                    <span class="text-green-400 text-xs"><?= htmlspecialchars($row['whatsapp']); ?></span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Email</span>
                                    <span class="text-xs text-gray-300"><?= htmlspecialchars($row['email']); ?></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Instagram</span>
                                    <span class="text-xs gold-text"><?= htmlspecialchars($row['instagram']); ?></span>
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Visual Aid</span>
                                    <span class="text-xs">Glasses: <?= htmlspecialchars($row['wear_glasses']); ?></span>
                                </div>
                                <div class="mb-3">
                                    <span class="block text-[9px] uppercase text-gray-400 tracking-wider">Medical History</span>
                                    <span class="text-[10px] italic text-gray-300"><?= htmlspecialchars($row['medical_history']); ?></span>
                                </div>
                                <span class="text-red-400 text-[10px] uppercase font-bold">No CV</span>
                            </td>
                            <td class="p-6 w-64">
                                <div class="max-h-32 overflow-y-auto pr-2 text-xs text-gray-300 italic scrollbar-thin scrollbar-thumb-gold scrollbar-track-navy">
                                    "<?= htmlspecialchars($row['motivation']); ?>"
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-20 text-center text-gray-500 italic font-serif tracking-widest">
                                The gallery is currently empty. Waiting for the first masterpiece...
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-12 text-center text-gray-500 text-[10px] uppercase tracking-[0.3em] font-serif">
        &copy; 2026 Mr & Ms President University | Museum of Living Art
    </footer>

</body>
</html>
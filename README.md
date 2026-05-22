# Mr. & Ms. President University 2026 — Registration System

A lightweight web application for **Mr. & Ms. President University 2026** finalist registration. Built with plain PHP and MySQL, it provides a multi-step public registration form and an admin dashboard to review submitted candidates.

**Theme:** *"The Museum of Living Art"*

---

## Features

### Public registration (`index.php`)
- **Email gatekeeper** — validates email format and blocks duplicate registrations
- **Two-step form** — personal details, then academic/medical information
- **Client-side validation** — Instagram handle (`@`), WhatsApp (`08...`), GPA (dot separator), height (Mr or Ms)
- **PDF CV upload** — stored in `uploads/` (gitignored)
- **Success flow** — confirmation screen with WhatsApp group link placeholder

### Admin dashboard (`admin.php`)
- **Candidate gallery** — sortable table of all registrations (newest first)
- **Quick actions** — WhatsApp deep links, CV download links
- **Sample data** — demo rows when the database is empty (for UI preview)

---

## Tech Stack

| Layer        | Technology                          |
| ------------ | ----------------------------------- |
| Backend      | PHP (mysqli)                        |
| Database     | MySQL 8.x                           |
| Frontend     | HTML, Tailwind CSS (CDN), vanilla JS |
| Fonts        | Playfair Display, Montserrat        |
| Local server | Apache/Nginx + PHP (e.g. Laragon)   |

---

## Prerequisites

- PHP 7.4+ (8.x recommended)
- MySQL or MariaDB
- Web server (Apache/Nginx) or [Laragon](https://laragon.org/) on Windows
- `background.png` in the project root (used as the page background)

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/jjiyyaaa/mmpuRegistration.git
cd mmpuRegistration
```

### 2. Create the database

Import the SQL schema:

```bash
mysql -u root -p < database_mmpu.sql
```

Or import `database_mmpu.sql` via phpMyAdmin / HeidiSQL.

This creates:
- Database: `mmpu_db`
- Table: `finalists` (with optional sample rows)

### 3. Configure database credentials

```bash
cp config.example.php config.php
```

Edit `config.php`:

```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'your_password';
$DB_NAME = 'mmpu_db';
```

> `config.php` is listed in `.gitignore` — never commit real credentials.

### 4. Prepare upload directory

The app creates `uploads/` automatically on first CV upload. Ensure the web server can write to the project folder, or create it manually:

```bash
mkdir uploads
chmod 755 uploads   # Linux/macOS
```

### 5. Add assets

Place `background.png` in the project root (same folder as `index.php`).

### 6. Run locally

**Laragon (Windows):** place the project under `C:\laragon\www\mmpuRegistration` and open:

- Registration: `http://mmpuregistration.test/` or `http://localhost/mmpuRegistration/`
- Admin: `http://mmpuregistration.test/admin.php`

**PHP built-in server (quick test):**

```bash
php -S localhost:8000
```

Then visit `http://localhost:8000/index.php`.

---

## Project Structure

```
mmpuRegistration/
├── index.php              # Public registration form
├── admin.php              # Admin candidate gallery
├── config.example.php     # Database config template
├── config.php             # Local credentials (not in git)
├── database_mmpu.sql      # MySQL schema + sample data
├── background.png         # Page background (add locally)
├── uploads/               # Uploaded CVs (gitignored)
├── .gitignore
└── README.md
```

---

## Usage

| URL            | Purpose                                      |
| -------------- | -------------------------------------------- |
| `/index.php`   | Public finalist registration                 |
| `/admin.php`   | View and manage submitted registrations      |

### Registration flow

1. User enters email → system checks for duplicates  
2. If available → multi-step form (personal → portrait/medical)  
3. On submit → data saved to `finalists`, CV stored in `uploads/`  
4. Success page → optional WhatsApp group link

### Admin panel

Open `admin.php` to see all candidates with:
- Identity, academics (major, batch, GPA, height)
- Contact (WhatsApp, email, Instagram)
- Medical info and CV link

---

## Database Schema

**Table:** `finalists`

| Column            | Type           | Notes                    |
| ----------------- | -------------- | ------------------------ |
| `id`              | INT, PK        | Auto-increment           |
| `fullname`        | VARCHAR(255)   | Required                 |
| `nickname`        | VARCHAR(100)   |                          |
| `email`           | VARCHAR(100)   | Unique per registration  |
| `major`, `batch`  | VARCHAR        |                          |
| `instagram`       | VARCHAR(100)   | e.g. `@username`         |
| `whatsapp`        | VARCHAR(50)    | Indonesian format `08…`  |
| `motivation`      | TEXT           |                          |
| `cv_path`         | VARCHAR(255)   | Relative path to PDF     |
| `height_mr/ms`    | INT            | cm; one is typically set |
| `gpa`             | DECIMAL(3,2)   | e.g. 3.85                |
| `wear_glasses`    | VARCHAR(10)    | Yes / No                 |
| `prescription`    | VARCHAR(100)   |                          |
| `contact_lenses`  | VARCHAR(10)    |                          |
| `medical_history` | TEXT           |                          |
| `created_at`      | TIMESTAMP      | Default: current time    |

---

## Security Notes

This project is intended for **event registration**, not high-security production use. Be aware:

- Admin panel has **no authentication** — protect `admin.php` via server config, VPN, or add auth before public deployment
- SQL queries use `mysqli_real_escape_string`; consider **prepared statements** for stronger protection
- Uploaded files are limited to **PDF** extension checks
- `config.php` and `uploads/` are excluded from version control

Before going live, restrict admin access and use HTTPS.

---

## Customization

- **Event copy & links** — edit broadcast text and URLs in `index.php` (rules, WhatsApp contact, group link)
- **Styling** — gold/navy theme via inline CSS and Tailwind CDN
- **Sample admin data** — remove or adjust the `$sampleRows` block in `admin.php` if you do not want demo rows when the DB is empty

---

## License

This project was built for **Mr. & Ms. President University 2026** at President University.  
Use and modify as needed for your event; attribution is appreciated.

---

## Contact

For questions about the event (not the code), reach the PR team via the contacts listed on the registration page.
"# Finalist-Registration" 

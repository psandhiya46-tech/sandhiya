# AI StudyBuddy
Simple AI learning assistant using HTML, CSS, JavaScript, PHP, MySQL and Google Gemini.

## Requirements
- XAMPP (Apache + PHP 8+ + MySQL)
- Gemini API key

## Setup
1. Copy the `ai-studybuddy` folder to `C:/xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import `database.sql`.
4. Edit `config/config.php` and add your Gemini API key.
5. Open `http://localhost/ai-studybuddy/`.

The project intentionally uses simple PHP sessions and REST-style PHP endpoints. Study-material upload accepts `.txt` files so no Composer package is required.

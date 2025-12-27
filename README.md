# 🟦 Orkut Clone (PHP)

> **A pixel-perfect, 1:1 recreation of the classic Orkut social network.**  
> *Developed as a technical portfolio project to demonstrate core backend development skills.*

## 📋 About The Project

This project is a **functional tribute** to the nostalgic social network "Orkut". The goal was not just to copy the frontend, but to build a robust, **backend-driven application** from scratch without relying on modern heavy frameworks (like Laravel or Symfony).

It demonstrates mastery of:
- **Vanilla PHP (8.x)**: Modern syntax, typing, and strict standards.
- **MVC Architecture**: Custom lightweight routing, controller logic, and view rendering.
- **Database Design**: Normalized interactions using **SQLite** (PDO) for portability.
- **Security**: Password hashing, session management, and input sanitization.
- **Frontend**: Hand-crafted CSS to mimic the specific "2004 aesthetic" (no Bootstrap/Tailwind).

## ✨ Features Implemented

### 👤 Profile & Social
- **Authentication**: Secure Login and Registration.
- **Profile Page**: Displays stats (reliable, cool, sexy), birthday, city, and relationships.
- **Karma System**: "Ice cubes", "Smileys", and "Hearts" rating system.
- **Friends**: Add/Remove friends and view the "Friends Network" graph.

### 📝 Interactions
- **Scraps (Recados)**: Public wall for messages with HTML parsing for basic formatting.
- **Testimonials (Depoimentos)**: Send testimonials that require approval to appear.
- **Photos**: Create albums and upload photos.
- **Videos**: Embed YouTube videos directly on the profile.
- **Communities**: (Visual/Partial) Explore community topics and forums.

## 🛠️ Technical Stack

- **Language**: PHP 8.2+
- **Database**: SQLite 3
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Architecture**: Custom MVC (Model-View-Controller)
- **Server**: Built-in PHP Development Server

## 🚀 How to Run

1.  **Clone the repository**
    ```bash
    git clone https://github.com/DannyahIA/orkut.git
    cd orkut
    ```

2.  **Initialize the Database**
    The project comes with a setup script to create the SQLite tables.
    ```bash
    # Ensure raw sqlite file is created
    php setup_database.php
    php migrate_photos.php
    php migrate_videos.php
    ```

3.  **Start the Server**
    Use PHP's built-in server for instant running (no Apache/Nginx config needed).
    ```bash
    php -S localhost:8000 -t public
    ```

4.  **Access**
    Open your browser and navigate to: `http://localhost:8000`

## 📂 Project Structure

```
orkut-php/
├── public/           # Public entry point (index.php, css, uploads)
├── src/
│   ├── Controllers/  # Logic (Auth, Profile, Scraps, etc.)
│   ├── Models/       # Database interactions
│   ├── Views/        # HTML Templates (organized by feature)
│   └── Core/         # Router, Database, and App setup
└── ...
```

---
*Disclaimer: This is an educational project. All rights to the original Orkut brand belong to their respective owners.*


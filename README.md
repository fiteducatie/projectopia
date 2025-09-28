# Projectopia 🎮  
*A gamified project simulation tool for students, built with Laravel + Filament.*  

## 🚀 Overview
**Projectopia** is an educational web app where students practice project management skills by working with **AI-simulated stakeholders**. Instead of only reading about agile or project planning, students get to experience it in a safe, playful simulation:

- Define the **context** of a project (e.g., building a website, launching a marketing campaign, organizing an event).  
- Add **user stories, features, or deliverables**.  
- Generate **stakeholder personas** (client, product owner, end-users) — complete with AI-generated faces.  
- Interact with personas in a **chat simulation** to refine requirements.  
- Experience **iterations, feedback, and scope changes** — just like in real life.  

## ✨ Features
- 🧑‍🤝‍🧑 **AI Personas** – realistic stakeholders with roles, goals, and communication styles.  
- 🎭 **Interactive Dialogue** – students can ask questions and negotiate requirements.  
- 📋 **Backlog Generation** – automatic breakdown of context into epics and user stories.  
- 🎨 **Avatar Faces** – AI-generated profile pictures for each persona.  
- 🏫 **Teacher Mode** – define pre-made scenarios, difficulty levels, or stakeholder quirks.  

## 🛠️ Tech Stack
- **Backend**: [Laravel 12](https://laravel.com/)  
- **Admin Panel**: [FilamentPHP](https://filamentphp.com/)  
- **Database**: SQLite / MySQL
- **AI Integration**: OpenAI API (for persona + backlog generation)  
- **Avatars**: Integration with AI face generation APIs (e.g. Generated Photos / This Person Does Not Exist)  
- **Frontend**: Filament components + TailwindCSS  

## Requirements

- PHP 8.3+
- Composer
- Node.js 22.12+
- SQLite or MySQL
- PHP Extensions (uncomment these in your `php.ini`):
    - `extension=pdo_sqlite`
    - `extension=pdo_mysql`
    - `extension=zip`
- OpenAI API Key (for AI features)

## Installation

1. Clone the repo

    ```bash
    git clone https://github.com/your-org/projectopia.git

    cd projectopia
    ```

2. Install dependencies

    ```bash
    composer install
    npm install && npm run build
    ```

3. Copy environment file and set your variables

    ```bash
    cp .env.example .env
    ```

4. Edit the `.env` file and set your database connection and OpenAI API keys:
    * You can get your OpenAI API key for the `OPENAI_API_KEY` field from the [OpenAI Platform website](https://platform.openai.com/settings/organization/api-keys).
    * To get the organization ID for the `OPENAI_ORGANIZATION` field go to [OpenAI Organization Settings](https://platform.openai.com/settings/organization/general).

5. Generate app key

    ```bash
    php artisan key:generate
    ```

6. Create the sqlite database file

    ```bash
    touch database/database.sqlite
    ```
    
7. Run migrations

    ```bash
    php artisan migrate --seed
    ```

8. Start dev server

    ```bash
    php artisan serve
    ```

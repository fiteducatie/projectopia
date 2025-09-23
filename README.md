# Projectopia 🎮  
*A gamified project simulation tool for students, built with Laravel + Filament.*  

## 🚀 Overview
**Projectopia** is an educational web app where students practice project management skills by working with **AI-simulated stakeholders**. Instead of only reading about agile or project planning, students get to experience it in a safe, playful simulation:

- Define the **context** of a project (e.g., building a website, launching a marketing campaign, organizing an event).  
- Add **user stories, features, or deliverables**.  
- Generate **stakeholder personas** (client, product owner, end-users) — complete with AI-generated faces.  
- Interact with personas in a **chat simulation** to refine requirements.  
- Build a **backlog, sprint plan, and timeline**.  
- Experience **iterations, feedback, and scope changes** — just like in real life.  

## ✨ Features
- 🧑‍🤝‍🧑 **AI Personas** – realistic stakeholders with roles, goals, and communication styles.  
- 🎭 **Interactive Dialogue** – students can ask questions and negotiate requirements.  
- 📋 **Backlog Generation** – automatic breakdown of context into epics and user stories.  
- 📆 **Timeline Builder** – sprint planning and milestone generation.  
- 🎨 **Avatar Faces** – AI-generated profile pictures for each persona.  
- 🏫 **Teacher Mode** – define pre-made scenarios, difficulty levels, or stakeholder quirks.  
- 🎮 **Gamified Learning** – project challenges, feedback rounds, and scenario-based play.  

## 🛠️ Tech Stack
- **Backend**: [Laravel 11](https://laravel.com/)  
- **Admin Panel**: [FilamentPHP](https://filamentphp.com/)  
- **Database**: MySQL / PostgreSQL  
- **AI Integration**: OpenAI API (for persona + backlog generation)  
- **Avatars**: Integration with AI face generation APIs (e.g. Generated Photos / This Person Does Not Exist)  
- **Frontend**: Filament components + TailwindCSS  

## Installation

# Clone the repo
git clone https://github.com/your-org/projectopia.git

cd projectopia

# Install dependencies
composer install
npm install && npm run build

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Start dev server
php artisan serve


# Task Manager

A full-stack Task Management web application built using **Laravel (backend)** and **Vue 3 with Inertia.js (frontend)**. 
This app allows users to create, edit, delete, and manage tasks with status filters and motivational quotes.


## Screenshots

###  Dashboard with Tasks
[Dashboard Screenshot] ![alt text](<Screenshot 2025-07-15 140448.png>)

###  Task Form
![Task Form Screenshot]  ![alt text](<Screenshot 2025-07-15 162944.png>)

###  Motivational Quote Display
![Quote Screenshot] ![alt text](<Screenshot 2025-07-15 162959.png>)

---

##  Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/RizwanSheriff24434/Task-Management.git

cd task-manager


Laravel Backend Setup
# Install dependencies
composer install

# Create .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Set up your database credentials in .env
# Then run migrations
php artisan migrate

(optional)
php artisan db:seed
DatabaseSeeder email and password




Vue Frontend Setup (with Vite)
# Install frontend dependencies
npm install

# Build frontend assets (dev)
npm run dev

# Or to build for production
npm run build

Run the App Locally


# Start Laravel server
php artisan serve
Now, open your browser and go to:
http://127.0.0.1:8000



├── app/Http/Controllers     # Laravel controllers
├── resources/js/Pages       # Vue components
├── resources/views          # Blade + Inertia view entry
├── routes/web.php           # Routes
├── public/                  # Public assets
├── .env     
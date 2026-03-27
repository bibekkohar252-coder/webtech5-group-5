# 🎓 Gorkha Institute of Technology (GIT) - Student Course Hub

A comprehensive web application for Gorkha Institute of Technology designed to help prospective students explore academic programmes, learn about modules, view faculty information, and register interest in courses. The system includes three distinct interfaces: Student Portal, Staff Portal, and Admin Panel.

---

## 🔐 Login Credentials

> ⚠️ **Important:** These credentials are for testing purposes only. Change them in production.

| Role | URL | Username | Password |
|------|-----|----------|----------|
| **👨‍💻 Admin** | `/course_hub/admin/` | `admin` | `password` |
| **👩‍🏫 Staff** | `/course_hub/staff/` | `alice.johnson` | `password` |
| **🎓 Student** | `/course_hub/student/` | No login required | - |

### Additional Staff Credentials
| Username | Password | Name |
|----------|----------|------|
| `brian.lee` | `password` | Dr. Brian Lee |

---

## 📑 Table of Contents
- [Login Credentials](#-login-credentials)
- [Student Interface - Detailed Overview](#student-interface---detailed-overview)
- [Staff Interface](#staff-interface)
- [Admin Interface](#admin-interface)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Design Features](#design-features)
- [Future Enhancements](#future-enhancements)

---

## 🎓 Student Interface - Detailed Overview

The student-facing portal is the heart of the application, designed to provide an engaging and intuitive experience for prospective students exploring educational opportunities at GIT.

### 🏠 Homepage Features

| Section | Description |
|---------|-------------|
| **Hero Section** | Full-width background image with overlay, animated welcome badge, and call-to-action buttons |
| **Modern Search Bar** | Glass-effect search bar with search icon, animated button, and popular search suggestions (Computer Science, Cyber Security, AI, Data Science) |
| **Animated Statistics** | Live counter animation showing total Programmes, Modules, and Expert Faculty numbers |
| **Featured Programmes** | Grid display of 6 programmes with images, level badges, and "Learn More" links |
| **Why Choose GIT** | 4 feature cards with icons: Expert Faculty, Cutting-Edge Curriculum, Modern Facilities, Global Community |
| **Campus Life Gallery** | 6-image grid showcasing library, tech labs, study spaces, graduation, sports, and student life with hover overlay effects |
| **Student Testimonials** | Real student reviews with avatars, star ratings, and course details |
| **Upcoming Events** | Event cards with dates, descriptions, and registration buttons |
| **Latest News** | News cards with award badges, headlines, and read more links |
| **Quick Links** | Download Prospectus, Fee Structure, Scholarships, Contact Admissions |
| **Newsletter Signup** | Email subscription form for programme updates |
| **CTA Section** | Final call-to-action button to explore all programmes |

### 📚 Programme Browsing (`programmes.php`)

| Feature | Description |
|---------|-------------|
| **Filter by Level** | Toggle between Undergraduate and Postgraduate programmes |
| **Keyword Search** | Search programmes by name or description |
| **Results Count** | Displays number of programmes found |
| **Programme Cards** | Image, level badge, title, description, and "Learn More" button |
| **Clear Filters** | One-click reset of all filters |

### 📖 Programme Details (`programme.php`)

| Feature | Description |
|---------|-------------|
| **Programme Header** | Gradient background with programme name, level, and leader information |
| **Programme Image** | Display programme image if available |
| **Description Section** | Detailed programme overview |
| **Modules by Year** | Modules organized by academic year in grid layout |
| **Module Cards** | Each module shows name, leader, and description with icon |
| **Register Interest Form** | Collect student name and email with duplicate prevention |
| **Withdraw Interest Form** | Remove registration using email address |
| **Alert Messages** | Success/error messages for registration and withdrawal |

### 📋 Modules Page (`modules.php`)

| Feature | Description |
|---------|-------------|
| **Module Listing** | All modules with names and leaders |
| **Module Description** | Detailed module information |
| **Programme Links** | Shows which programmes include each module with direct links |

### 👥 Staff Page (`staff.php`)

| Feature | Description |
|---------|-------------|
| **Staff Directory** | Grid display of all academic staff members |
| **Module Leadership** | Lists modules each staff member leads |

### 📝 Registration & Withdrawal

| Feature | Description |
|---------|-------------|
| **Form Validation** | Validates name, email format, and prevents empty submissions |
| **Duplicate Prevention** | Prevents multiple registrations for same programme with same email |
| **Session Messages** | Displays success/error messages with proper styling |
| **Redirect Handling** | Returns to programme page after action |

---

## 👔 Staff Interface

The staff portal provides faculty members with personalized dashboards to view their teaching responsibilities and student interests.

### Staff Login
- **URL:** `/course_hub/staff/`
- **Username:** `alice.johnson` or `brian.lee`
- **Password:** `password`

### Staff Dashboard Features

| Feature | Description |
|---------|-------------|
| **Welcome Message** | Personalized greeting with staff name |
| **Modules I Lead** | List of modules where staff is the module leader |
| **Module Programmes** | Shows which programmes include each module with direct links |
| **Programmes I Lead** | List of programmes where staff is the programme leader |
| **Interested Students Table** | Displays student name, email, and registration date for programmes they lead |

---

## 🔐 Admin Interface

The admin panel provides complete control over all system data with full CRUD operations.

### Admin Login
- **URL:** `/course_hub/admin/`
- **Username:** `admin`
- **Password:** `password`

### Admin Dashboard Features

| Feature | Description |
|---------|-------------|
| **Statistics Cards** | Total Programmes, Modules, Staff, Interested Students |
| **Quick Actions** | Add Programme, Add Module, Add Staff, View Students, Export CSV |

### Programme Management

| Feature | Description |
|---------|-------------|
| **View All Programmes** | Table with ID, Name, Level, Published Status |
| **Add Programme** | Form with name, level, leader, description, image URL |
| **Edit Programme** | Pre-filled form to update existing programmes |
| **Delete Programme** | Confirmation dialog before deletion |
| **Publish/Unpublish** | Toggle visibility on student interface (stores in JSON) |

### Module Management

| Feature | Description |
|---------|-------------|
| **View All Modules** | Table with ID, Name, Module Leader |
| **Add Module** | Form with name, leader, description, image URL |
| **Edit Module** | Update existing module details |
| **Delete Module** | Remove module with confirmation |

### Staff Management

| Feature | Description |
|---------|-------------|
| **View All Staff** | Table with ID and Name |
| **Add Staff** | Simple form for adding staff members |
| **Edit Staff** | Update staff names |
| **Delete Staff** | Remove staff with confirmation |

### Student Interest Management

| Feature | Description |
|---------|-------------|
| **View Students** | Table showing all interested students with programme names |
| **Export CSV** | Download complete student interest data as CSV file |

---

## 🛠 Technologies Used

| Category | Technologies |
|----------|--------------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **CSS Framework** | Custom CSS with modern design |
| **Icons** | Font Awesome 6 |
| **Animations** | AOS (Animate on Scroll) |
| **Fonts** | Google Fonts (Inter) |
| **Backend** | PHP |
| **Version Control** | Git & GitHub |

---

## 📁 Project Structure

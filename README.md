# UMS Food Review Corner

A PHP and MySQL web application for recording, rating and managing reviews of meals eaten around the Universiti Malaysia Sabah (UMS) campus. Developed as **Lab Test 2** coursework using the MVC pattern.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Problem Statement](#problem-statement)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Database](#database)
- [Installation](#installation)
- [Usage](#usage)
- [Known Limitations](#known-limitations)
- [Documentation](#documentation)
- [Acknowledgements](#acknowledgements)
- [Author](#author)

---

## Project Overview

UMS Food Review Corner lets a student keep a personal record of the food they have tried on campus. Each review stores the dish, the cafe or restaurant, the food category, the dining date, a 1 to 5 rating and a written comment. Reviews can be created, viewed, edited and deleted, and a profile page shows the reviewer's details.

The application is organised as a small MVC project: a front controller (`index.php`) routes requests to `ReviewController`, which talks to the model classes and renders the matching view.

## Problem Statement

Students often choose where to eat by word of mouth, and good (or bad) experiences at campus cafes are quickly forgotten or scattered across chat groups and social media. There is no simple, structured place to record what a dish was like, how it was priced, or whether it is worth ordering again.

This project provides a lightweight web application where reviews are stored in one place with consistent fields, so they can be browsed, corrected and reused later.

## Features

- **Create** a review with food name, restaurant, food category, dining date, rating and comment.
- **Read** all reviews on the home page, or open a single review in detail.
- **Update** an existing review through a pre-filled edit form.
- **Delete** a review.
- **Input validation:** required fields are checked and ratings must be between 1 and 5.
- **Output sanitisation:** user input is trimmed and escaped with `htmlspecialchars` before storage.
- **Feedback messages:** success and error notices are shown after each action.
- **Profile page** showing the reviewer's details (matric number, name, age, hometown, photo).
- **Referential integrity:** reviews are linked to users with a foreign key that cascades on delete.

## Technology Stack

| Layer | Technology |
| --- | --- |
| Language | PHP 8 |
| Architecture | MVC (controllers, models, views) |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Local server | XAMPP (Apache, MariaDB, phpMyAdmin) |
| Version control | Git and GitHub |

## Project Structure

```
UMS-Food-Review/
├── code/
│   ├── assets/              # CSS and JavaScript
│   ├── config/
│   │   └── database.php     # Database connection settings
│   ├── controllers/
│   │   └── ReviewController.php
│   ├── img/                 # Images, including the profile photo
│   ├── models/              # UserModel, ReviewModel
│   ├── views/               # home, review_form, view_review, edit_review, profile, header, footer
│   └── index.php            # Front controller / router
├── food_review_db.sql       # Database schema and sample data
└── LabTest_2_documentation.pdf
```

## Database

Database name: `food_review_db`

| Table | Purpose | Key columns |
| --- | --- | --- |
| `users` | Reviewer profile | `id`, `matric_no` (unique), `name`, `age`, `hometown`, `profile_image` |
| `reviews` | Food reviews | `id`, `user_id` (FK to `users`), `food_name`, `restaurant_name`, `food_category`, `dining_date`, `rating`, `review_text`, `created_at`, `updated_at` |

## Installation

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any stack with PHP 8, Apache and MySQL/MariaDB)
- Git

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/abigailsadom1903/UMS-Food-Review.git
   ```

2. **Copy the application into your web root.** Copy the `code` folder into XAMPP's `htdocs` directory and rename it, for example:

   ```
   C:\xampp\htdocs\ums-food-review
   ```

3. **Start Apache and MySQL** from the XAMPP Control Panel.

4. **Create and import the database**
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Create a new database named `food_review_db` (collation `utf8mb4_general_ci`).
   - Select it, choose **Import**, and upload `food_review_db.sql` from the repository root.

5. **Check the database settings.** Open `config/database.php` and confirm the host, database name, username and password match your local setup. XAMPP's defaults are usually host `localhost`, user `root` and an empty password.

6. **Open the application**

   [http://localhost/ums-food-review/](http://localhost/ums-food-review/)

## Usage

| Action | URL |
| --- | --- |
| Home (all reviews) | `index.php` |
| View a review | `index.php?action=view&id=1` |
| Edit a review | `index.php?action=edit&id=1` |
| Delete a review | `index.php?action=delete&id=1` |

Use the form on the home page to add new reviews.

## Known Limitations

- There is no login system. The application uses a single, pre-seeded reviewer profile.
- The delete action is triggered by a link, so it should be protected with a confirmation step and a POST request before any real deployment.
- Intended for local, educational use; it has not been hardened for production.

## Documentation

The full lab report is included in the repository: [`LabTest_2_documentation.pdf`](LabTest_2_documentation.pdf).

## Acknowledgements

ChatGPT (OpenAI) was used for guidance on the MVC structure and CRUD implementation, as declared in the source code.

## Author

**Abigail Alau R. Sadom**
Bachelor of Computer Science (Software Engineering), Universiti Malaysia Sabah
GitHub: [@abigailsadom1903](https://github.com/abigailsadom1903)

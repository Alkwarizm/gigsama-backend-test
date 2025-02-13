## Hospital Backend System Test

---
### Overview

---
Develop a backend system for a hospital that handles user signups, patient–doctor assignments, doctor note submissions, and dynamic scheduling of actionable steps based on live LLM processing. The system must secure sensitive data and use a live LLM to extract actionable steps—divided into a checklist (immediate tasks) and a plan (scheduled actions). New note submissions should cancel any existing actionable steps and create new ones.


### Requirements

---

### Setup
* PHPv8.3
* Laravel 11.x
* MySQL 8.x | Sqlite 3.x
1. Clone the repository
2. Run `composer install`
3. Run `php artisan migrate`

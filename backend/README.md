# TicketFlow — Backend API

TicketFlow is a CRM-style ticketing system inspired by real-world customer service workflows.  
This repository contains the **backend core**, built with Laravel and MySQL.

---

## Features (Backend Core)

- Authentication & Authorization (Laravel Sanctum)
- Role-based access control (Admin, Agent, Customer)
- Ticket creation (guest & registered users)
- Automatic agent assignment based on ticket category
- SLA tracking & first response monitoring
- Admin ticket management (reassign & close)
- Rating system after ticket resolution
- Policy-based authorization & form request validation

---

## Tech Stack

- **Laravel** 12.x
- **MySQL**
- Laravel Sanctum (API authentication)
- Eloquent ORM

---

## Roles

- **Admin**: manage agents, categories, and tickets
- **Agent**: handle assigned tickets
- **Customer**: create tickets & submit ratings
- **Guest**: create tickets via email

---

Notes

Frontend (Vue.js) and deployment will be added in the next phase.
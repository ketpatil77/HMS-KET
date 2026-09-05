# Hospital Management System

## 2. Short Project Description
Hospital Management System is a web-based application built to manage hospital operations from one place. It supports doctor directory management, appointment booking, staff records, department management, prescriptions, user access, and Indian medical billing. The project uses a PHP CodeIgniter backend with local JSON file storage, so it can run without MySQL setup. It also includes a portable Windows launcher that checks runtime requirements and starts the project automatically.

## 3. Problem Statement
Many hospital tasks are difficult to manage manually because records are stored in registers, spreadsheets, or separate files. Manual appointment handling can cause duplicate bookings, missing schedules, and slow updates. Billing and prescription records can become hard to track when they are not connected to doctor and appointment data. Searching staff, department, appointment, and payment information manually takes time and increases the chance of mistakes. This project solves these problems by keeping the hospital workflow in one digital system.

## 4. Project Objectives
- To provide a centralized system for hospital management tasks.
- To manage doctors, nurses, departments, users, appointments, and bills digitally.
- To allow registered users to view doctors, book appointments, and manage profile details.
- To generate Indian medical bills in INR with printable bill format.
- To make the project portable using local JSON data and a one-click Windows launcher.

## 5. Scope of the Project
This project can be used in small clinics, hospitals, medical centers, and healthcare management demos.

- Reception or administration staff can manage departments, users, doctors, nurses, and billing.
- Doctors can view appointments, manage schedules, and add prescriptions.
- Registered users can search doctors, book appointments, and view their appointments.
- The system can be used as a lightweight local hospital management app without external database setup.

## 6. Technologies Used

### Frontend
- HTML5, CSS3, JavaScript
- Custom responsive UI using `assets/css/style.css` and `assets/js/main.js`

### Backend
- PHP 8.x
- CodeIgniter MVC framework

### Database
- Local JSON file storage in `application/data/*.json`

### Other Tools / Libraries
- Windows PowerShell launcher scripts
- Bundled/project-local PHP runtime support in `.runtime/php`
- 3D profile avatar images in `assets/images/profiles`
- CodeIgniter helpers, controllers, models, and views

## 7. Main Modules

### Module 1: Authentication and User Access
- Login system for authorized users.
- Session-based access control.
- Role-based page access for admin, doctor, nurse, employee, and registered user flows.

### Module 2: Doctor and Schedule Management
- Add, update, view, and delete doctor records.
- Assign doctor profile photos automatically.
- Create and manage doctor schedules with time, day, fees, and appointment limits.

### Module 3: Appointment Management
- Public doctor listing for appointment selection.
- Appointment booking with schedule and date selection.
- Appointment list, status tracking, delete action, and doctor-side appointment view.

### Module 4: Billing and Invoice Management
- Create Indian medical bills.
- Add consultation, lab test, medicine, and other bill items.
- Display total amount in INR.
- Print medical bill with Indian medical center details.

### Module 5: Department and Staff Management
- Manage hospital departments.
- Manage nurse records.
- Manage user records and access roles.

### Module 6: Portable Runtime Module
- `START.bat` checks and starts the project.
- Works with PowerShell 7 or normal Windows PowerShell.
- Uses existing PHP if available or downloads project-local PHP if needed.
- Starts the app on a free local port.

## 8. Main Features
- Modern dashboard with hospital statistics and quick actions.
- Doctor directory with profile photos and department badges.
- Appointment booking and appointment tracking.
- Doctor schedule creation and appointment capacity limit.
- Prescription update support for doctor appointments.
- Indian medical billing with INR formatting and printable bill.
- Local JSON database, no MySQL setup required.
- One-click Windows startup using `START.bat`.
- Automatic profile avatars for doctors, nurses, users, and new registrations.
- Responsive dark/light theme support.

## 9. System Workflow
1. User opens the project using `START.bat`.
2. Launcher checks PowerShell, PHP runtime, PHP extensions, data folders, and free port.
3. Application starts locally in the browser.
4. Admin manages doctors, departments, nurses, users, appointments, and billing.
5. Registered user views doctors, books an appointment, and checks appointment status.
6. Doctor views appointments, manages schedules, and adds prescriptions.
7. Billing module creates and prints Indian medical bills in INR.

## 10. User Roles

### Role 1: Admin
- Manage dashboard and hospital setup.
- Manage doctors, nurses, departments, users, and settings.
- Create and review medical bills.

### Role 2: Doctor
- View assigned appointments.
- Create and manage schedules.
- Add prescription details for appointments.

### Role 3: Nurse / Employee
- Access assigned management areas based on role.
- View and manage operational records.
- Support hospital staff workflows.

### Role 4: Registered User
- View doctor directory.
- Book appointments.
- View appointment status and manage profile details.

## 11. Database Details
Database Name: Local JSON Database

Data Location: `application/data/`

### Important Tables / Collections

#### Table 1: `user.json`
Fields:
- `id`
- `user_name`
- `full_name`
- `email`
- `role`
- `picture`
- `profile_id`

#### Table 2: `doctor.json`
Fields:
- `id`
- `name`
- `department`
- `email`
- `phone`
- `country`
- `about`
- `picture`

#### Table 3: `doctors_schedule.json`
Fields:
- `id`
- `doctor_id`
- `day_of_week`
- `start_time`
- `end_time`
- `fees`
- `max_num_of_patients`

#### Table 4: `appoinment.json`
Fields:
- `id`
- `doctor_id`
- `patient_id`
- `schedule_id`
- `date`
- `serial_no`
- `status`
- `details`
- `prescription`

#### Table 5: `invoice.json`
Fields:
- `id`
- `title`
- `patient`
- `data`
- `total`
- `created_by`
- `date`

#### Table 6: `department.json`
Fields:
- `id`
- `name`
- `description`

#### Table 7: `nurse.json`
Fields:
- `id`
- `name`
- `phone`
- `email`
- `address`
- `picture`
- `about`

## 12. Important Pages / Routes / Screens
- Home Page: `/` or `/page/doctors`
- Login Page: `/login`
- Dashboard: `/dashboard`
- Admin Page: `/dashboard`, `/user`, `/department`, `/settings`
- Doctor Management: `/doctors`
- Doctor Profile: `/doctors/about/{id}`
- Schedule Page: `/doctors/createSchedule/{id}`
- Appointment Booking: `/page/TakeAppoinment/{doctor_id}`
- Appointment List: `/page/appoinments`
- Billing Page: `/invoice`
- New Medical Bill: `/invoice/add`
- Print Medical Bill: `/invoice/print/{id}`
- Staff Page: `/nurse`
- Public Register Page: `/page/register`
- Profile Page: `/page/profile`

## 13. Important Source Code Files
List of important files/folders from the project:

- `START.bat` : One-click Windows launcher.
- `tools/start_app.ps1` : Checks requirements, finds a free port, and starts the PHP server.
- `tools/bootstrap_php.ps1` : Checks PHP, downloads project-local PHP if required, and enables extensions.
- `requirements.txt` : Runtime settings for PHP version, extensions, and port range.
- `index.php` : CodeIgniter application entry point.
- `application/config/config.php` : Main application configuration.
- `application/config/routes.php` : Route configuration.
- `application/libraries/Json_store.php` : JSON database read/write layer.
- `application/models/Hospital_model.php` : Shared model layer for JSON storage.
- `application/controllers/Dashboard.php` : Dashboard controller.
- `application/controllers/Doctors.php` : Doctor, schedule, appointment, and prescription controller.
- `application/controllers/Page.php` : Public doctor listing, profile, register, and appointment booking controller.
- `application/controllers/Invoice.php` : Medical bill creation, list, print, and delete controller.
- `application/controllers/User.php` : User management and logout controller.
- `application/controllers/Nurse.php` : Nurse management controller.
- `application/controllers/Department.php` : Department management controller.
- `application/helpers/functions_helper.php` : Common helper functions, media paths, avatars, and login helpers.
- `application/views/header.php` : Main app layout header.
- `application/views/sidebar.php` : Sidebar navigation.
- `application/views/dashboard.php` : Dashboard screen.
- `application/views/page/doctors.php` : Public doctor directory.
- `application/views/page/take_appoinment.php` : Appointment booking screen.
- `application/views/invoice/print.php` : Printable Indian medical bill.
- `assets/css/style.css` : Main UI styling.
- `assets/js/main.js` : UI behavior, theme, modal, schedule selection, invoice row add.
- `assets/images/profiles/` : Profile avatar images.
- `application/data/` : Local JSON database files.

## 14. Available Screenshots
Recommended screenshot/page names for report:

1. Home / Public Doctors Page
2. Login Page
3. Admin Dashboard
4. Doctor Management Page
5. Doctor Profile and Schedule Page
6. Appointment Booking Page
7. Appointment List Page
8. Indian Medical Billing Page
9. Printable Medical Bill Page
10. Settings Page

## 15. Charts / Diagrams Required in Report
Recommended diagrams to add:

- System Architecture Diagram
- Data Flow Diagram
- Use Case Diagram
- ER Diagram
- Flowchart
- Module Chart
- Bar Chart for dashboard counts
- Pie Chart for appointment status
- Other: Portable launcher workflow diagram

## 16. Testing Details
Tests performed during project verification:

- PHP syntax test
- Local server startup test
- Home page HTTP response test
- Static CSS and JavaScript asset loading test
- Profile avatar image loading test
- JSON data parsing test
- Appointment page smoke test
- Invoice page syntax and INR format check
- Portable launcher test with normal Windows PowerShell
- PHP runtime bootstrap test

## 17. Advantages
- Reduces manual paperwork and duplicate record handling.
- Stores hospital records in one local system.
- Does not require MySQL setup because data is stored in JSON files.
- Portable Windows startup with automatic PHP runtime support.
- Clean and modern UI for doctors, appointments, staff, and billing.
- Indian medical bill format with INR currency support.

## 18. Limitations
- JSON storage is best for small to medium local use, not large multi-branch production deployment.
- No online payment gateway integration is included.
- No SMS, email notification, or calendar integration is included.
- Advanced analytics and reporting charts are limited.
- Multi-user concurrency is limited compared to a full database server.

## 19. Future Scope
- Add MySQL or SQLite option for larger deployment.
- Add SMS and email appointment notifications.
- Add payment gateway integration for medical billing.
- Add advanced reports for revenue, appointment trends, and doctor workload.
- Add export options for PDF, Excel, and CSV reports.
- Add audit logs for every admin action.
- Add role permission customization.
- Add online appointment confirmation and cancellation workflow.

## 20. Conclusion
Hospital Management System is a complete local web application for managing hospital operations such as doctors, schedules, appointments, users, staff, departments, prescriptions, and Indian medical bills. The project replaces manual record handling with a centralized digital workflow. Its JSON-based local database and portable Windows launcher make it easy to run without complex setup. Overall, the system is useful for small healthcare centers, clinics, and demonstration environments where fast setup and simple management are required.

## 21. References
- PHP Official Documentation: https://www.php.net/docs.php
- CodeIgniter User Guide: https://codeigniter.com/userguide3/
- Windows PHP Downloads: https://windows.php.net/download/
- MDN Web Docs: https://developer.mozilla.org/
- JSON Data Format: https://www.json.org/

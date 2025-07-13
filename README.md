# Smart Home Management System

A web-based Smart Home Management System built using **PHP**, **Oracle 10g XE**, and **OCI8**. The system allows users to control devices, schedule their operation, and manage access to rooms. Designed as a database project for academic use.

---

## Project Info

- **Project Title:** Smart Home Management System  
- **Course Title:** Database Management System Lab  
- **Course Code:** CSE-2424  
- **Semester:** 4th  
- **Student:** Nusrat Jahan Chaity  
- **Student ID:** C233470  
- **Instructor:** Ms. Mysha Sarin Kabisha  
- **Department:** CSE, IIUC  
- **Submission Date:** 13/07/2025  

---

##  Project Features

- Add and manage users (admin or regular)
- Create rooms and add devices to rooms
- Assign users to specific rooms (access control)
- Toggle device status (ON/OFF)
- Schedule device operations (start and end time by day)
- SQL execution mode for admin (optional)

---

##  Database Design

### Tables Used

- `Users(user_id, name, email, password, is_admin)`
- `Rooms(room_id, room_name)`
- `Room_Access(access_id, user_id, room_id)`
- `Devices(device_id, device_name, room_id, status, updated_at)`
- `Schedules(schedule_id, device_id, day_of_week, start_time, end_time, action)`

### Relationships

- One user → many rooms (via Room_Access)
- One room → many devices
- One device → many schedules

---

## Setup Instructions

1. **Install XAMPP (win32-7.3.2-0-VC15)**

   * Download from SourceForge.
   * Install to: `C:\xampp`

2. **Clone the Project inside C:\xampp\htdocs**

   ```bash
   git clone https://github.com/your-username/Smart-Home-Management-System.git
   ```

3. **Install Oracle 10g XE**

   * Use default configuration (SID = `XE`, Port = `1521`)
   * Remember your SYSTEM password

4. **Enable OCI8 in PHP**

   * Open `C:\xampp\php\php.ini`
   * Uncomment:

     ```
     extension=oci8.dll
     ```
   * Restart Apache from the XAMPP Control Panel

5. **Create Database and Insert Data**

   * Open SQL\*Plus or SQL Developer
   * Login as SYSTEM
   * Run the provided `CREATE TABLE` and `INSERT INTO` SQL scripts

6. **Run the Project**

   * Start Apache from XAMPP
   * Open browser and go to:

     ```
     http://localhost/Smart-Home-Management-System/
     ```

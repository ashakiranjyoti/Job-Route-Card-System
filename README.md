# Job Route Card Management System  
A lightweight ERP-style Job Workflow and Production Tracking System built using **PHP, MySQL, HTML, CSS, JavaScript, Bootstrap**, and **TCPDF**.

---

## 🚀 Overview  
This system helps manufacturing units track their **job cards**, **production operations**, and **workflow status** from start to completion.  
It includes **Admin and User roles**, automated order ID generation, operation tracking, status updates, and PDF reporting.

---

## 🧩 Features

### 🔐 User Roles
- **Admin**
  - Create new job cards  
  - Edit job details  
  - Delete job cards  
  - Generate PDF reports  
- **User**
  - Add operations for assigned job cards  
  - Update production quantities  
  - Upload operation-related documents  
  - Mark operations as completed  

---
## 🔒 Security Features
- Password hashing
- Session management
- SQL injection prevention
- File upload validation
- Role-based access control

## 📝 Job Card Creation
When admin creates a new job card, an **Order ID auto-generates** based on date:  
"Order Accepted",
"Material receipt",
"Inspection",
"Material Storage",
"Material moved for PCB assembly",
"Inspection of assembled PCB",
"Storage",
"Remaining assembly, components, like connector",
"Programming & testing",
"Conformal Coating",
"Final assembly",
"Final Testing",
"Pre-Dispatch inspection",
"Storage - Ready Material",
"Wiring",
"Partial Dispatch",
"On Hold",
"Packing",
"Billing Done",
"Delivery Done",
"Fabrication",
"Dispatch" 


Once **Dispatch** is completed → Job moves to **Completed Job Cards**.

---

## ✔ Completed Job Cards
Displays:
- S.No  
- Order ID  
- Product Model  
- Customer  
- Quantity  
- Status (Completed)  
- Actions → **View | Delete | PDF**

---

## 📄 PDF Generation  
All Job Card details + operations can be exported as PDF using **TCPDF**.

---

## 🗄 Tech Stack  
- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP (Core PHP)  
- **Database:** MySQL  
- **Server:** XAMPP (Apache, PHP, phpMyAdmin)  
- **IDE:** VS Code  
- **PDF Generator:** TCPDF  

---

## 🏷 Is This an ERP System?  
Yes — this project functions as a **Production ERP Module** because it includes:  
✔ Job Card Creation  
✔ Workflow Routing  
✔ Multi-stage Operations  
✔ Production Tracking  
✔ User Roles  
✔ PDF Reports  
✔ Document Attachments  
 



---

## 📦 Installation  
1. Clone the repository  
2. Copy project folder to `/xampp/htdocs`  
3. Import the included SQL file into MySQL (phpMyAdmin)  
4. Update `config.php` with DB credentials  
5. Run in browser:


![Screenshot_12-12-2025_1805_localhost](https://github.com/user-attachments/assets/e4cea27a-6a2b-4ba3-8887-665352933982) &nbsp;&nbsp;&nbsp;&nbsp;

<img width="1908" height="894" alt="screenshot-1765542464842" src="https://github.com/user-attachments/assets/50e76f5f-a370-45bd-971d-0b1e322a13b9" /> &nbsp;&nbsp;&nbsp;&nbsp;

![Screenshot_12-12-2025_175928_localhost](https://github.com/user-attachments/assets/4b396db2-d78e-44bf-8b72-9ae52433d78f) &nbsp;&nbsp;&nbsp;&nbsp;

![Screenshot_12-12-2025_175949_localhost](https://github.com/user-attachments/assets/eb6a8179-da5f-4eb2-951b-d774b9536fa7) &nbsp;&nbsp;&nbsp;&nbsp;

![Screenshot_12-12-2025_1828_localhost](https://github.com/user-attachments/assets/a20a1557-937c-49d7-b8a4-e61e946f31e7)


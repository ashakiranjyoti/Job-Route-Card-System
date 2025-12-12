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
✔ Status-based Workflow  

This system can easily be extended into a complete ERP.

---

## 📦 Installation  
1. Clone the repository  
2. Copy project folder to `/xampp/htdocs`  
3. Import the included SQL file into MySQL (phpMyAdmin)  
4. Update `config.php` with DB credentials  
5. Run in browser:

<img width="1908" height="894" alt="screenshot-1765542464842" src="https://github.com/user-attachments/assets/50e76f5f-a370-45bd-971d-0b1e322a13b9" />


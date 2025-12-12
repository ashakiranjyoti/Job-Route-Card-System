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


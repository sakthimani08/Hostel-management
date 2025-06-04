 🔍 **Extended Description of Entities (Hostel Management System)**

1. **Student**:
   Represents students enrolled in the hostel system. Each student is associated with a specific hostel, mess, and room.

2. **Hostel**:
   Represents each hostel building with its name, location, and total capacity.

3. **Hostel\_Room**:
   Contains details of individual rooms under a hostel, including the type (single, shared), and status (occupied/vacant).

4. **Mess**:
   Represents mess dining facilities, each having a name, menu, and operational timings.

5. **Caretaker**:
   Staff responsible for hostel management. Each caretaker is assigned to a hostel and works in different shift times.

6. **Complaint**:
   Contains grievances or issues raised by students, including the category and handling caretaker.

7. **Fees**:
   Represents the fees a student needs to pay for hostel and mess facilities, including status and due dates.

8. **User**:
   This table handles authentication and authorization. Users can be students, caretakers, or admin based on `user_type`.

---

## 🛠️ **SQL Commands to Create Tables**

```sql
-- 1. Hostel Table
CREATE TABLE hostel (
    hostel_id INT PRIMARY KEY AUTO_INCREMENT,
    hostel_name VARCHAR(100),
    location VARCHAR(100),
    capacity INT
);

-- 2. Mess Table
CREATE TABLE mess (
    mess_id INT PRIMARY KEY AUTO_INCREMENT,
    mess_name VARCHAR(100),
    menu TEXT,
    timings VARCHAR(100)
);

-- 3. Hostel_Room Table
CREATE TABLE hostel_room (
    room_id INT PRIMARY KEY AUTO_INCREMENT,
    room_number VARCHAR(10),
    hostel_id INT,
    room_type VARCHAR(50),
    status VARCHAR(50),
    FOREIGN KEY (hostel_id) REFERENCES hostel(hostel_id)
);

-- 4. Caretaker Table
CREATE TABLE caretaker (
    caretaker_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    shift_time VARCHAR(50),
    hostel_id INT,
    FOREIGN KEY (hostel_id) REFERENCES hostel(hostel_id)
);

-- 5. Student Table
CREATE TABLE student (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    department VARCHAR(100),
    hostel_id INT,
    mess_id INT,
    room_id INT,
    FOREIGN KEY (hostel_id) REFERENCES hostel(hostel_id),
    FOREIGN KEY (mess_id) REFERENCES mess(mess_id),
    FOREIGN KEY (room_id) REFERENCES hostel_room(room_id)
);

-- 6. Complaint Table
CREATE TABLE complaint (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    category VARCHAR(100),
    description TEXT,
    caretaker_id INT,
    status VARCHAR(50),
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY (caretaker_id) REFERENCES caretaker(caretaker_id)
);

-- 7. Fees Table
CREATE TABLE fees (
    fees_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    hostel_fees DECIMAL(10,2),
    mess_fees DECIMAL(10,2),
    payment_status VARCHAR(50),
    due_date DATE,
    FOREIGN KEY (student_id) REFERENCES student(student_id)
);

-- 8. User Table
CREATE TABLE user (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(20),
    user_type ENUM('admin', 'student', 'caretaker') NOT NULL
);
```

---

### ✅ Summary of Relationships:

* A **hostel** has many **rooms**, **students**, and **caretakers**.
* A **student** is assigned to **hostel**, **mess**, and **room**.
* A **student** can raise multiple **complaints**, handled by a **caretaker**.
* Each **student** has a corresponding **fees** entry.
* The **user** table handles login for all user types.

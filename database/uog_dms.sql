-- =============================================================
-- UoG Dormitory Management System Database
-- University of Gondar - Atse Tewodros Campus
-- =============================================================

CREATE DATABASE IF NOT EXISTS uog_dms CHARACTER SET utf8 COLLATE utf8_general_ci;
USE uog_dms;

-- ---------------------------------------------------------------
-- Users Table (admin, proctor_manager, proctor, student_dean, student)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    fname       VARCHAR(50) NOT NULL,
    lname       VARCHAR(50) NOT NULL,
    user_id_no  VARCHAR(30) UNIQUE,
    username    VARCHAR(50) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    email       VARCHAR(100),
    sex         ENUM('Male','Female'),
    user_type   ENUM('admin','proctor_manager','proctor','student_dean','student') NOT NULL,
    status      ENUM('active','inactive') DEFAULT 'active',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------
-- Blocks Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS blocks (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    block_number    VARCHAR(20) NOT NULL UNIQUE,
    block_name      VARCHAR(100),
    capacity        INT NOT NULL DEFAULT 0,
    gender          ENUM('Male','Female','Mixed') DEFAULT 'Mixed',
    proctor_id      INT,
    description     TEXT,
    status          ENUM('active','inactive') DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proctor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------
-- Rooms (Dorms) Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS rooms (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    block_id    INT NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    capacity    INT NOT NULL DEFAULT 4,
    occupied    INT DEFAULT 0,
    gender      ENUM('Male','Female') DEFAULT 'Male',
    floor_no    INT DEFAULT 1,
    status      ENUM('available','full','maintenance') DEFAULT 'available',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_room (block_id, room_number),
    FOREIGN KEY (block_id) REFERENCES blocks(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------
-- Student Allocations Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS student_allocations (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    student_id      INT NOT NULL,
    block_id        INT NOT NULL,
    room_id         INT NOT NULL,
    bed_number      VARCHAR(10) NOT NULL,
    batch_year      VARCHAR(10),
    faculty         VARCHAR(100),
    department      VARCHAR(100),
    academic_year   VARCHAR(10),
    allocated_by    INT,
    allocated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status          ENUM('active','vacated','transferred') DEFAULT 'active',
    UNIQUE KEY unique_bed (room_id, bed_number),
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (block_id) REFERENCES blocks(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (allocated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------
-- News & Events Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS news_events (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200) NOT NULL,
    content     TEXT NOT NULL,
    type        ENUM('news','event','announcement') DEFAULT 'news',
    event_date  DATE,
    posted_by   INT,
    image_path  VARCHAR(255),
    status      ENUM('published','draft','archived') DEFAULT 'published',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------
-- Comments / Feedback Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS comments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(100),
    comment     TEXT NOT NULL,
    user_id     INT,
    status      ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------
-- Attendance Table
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS attendance (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    student_id      INT NOT NULL,
    block_id        INT,
    room_id         INT,
    date            DATE NOT NULL,
    status          ENUM('present','absent') DEFAULT 'present',
    recorded_by     INT,
    notes           VARCHAR(255),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (block_id) REFERENCES blocks(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------------
-- Activity Log
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS activity_log (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT,
    action      VARCHAR(255) NOT NULL,
    table_name  VARCHAR(100),
    record_id   INT,
    ip_address  VARCHAR(50),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================
-- Default Data
-- =============================================================

-- Admin account (password: Admin@1234)
INSERT INTO users (fname, lname, username, password, email, user_type, sex, status) VALUES
('System', 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@uog.edu.et', 'admin', 'Male', 'active');

-- Note: default password is 'password' (bcrypt). Change immediately.

-- Sample proctor manager
INSERT INTO users (fname, lname, username, password, email, user_type, sex, status) VALUES
('Almaz', 'Birhane', 'proctor_mgr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'almaz@uog.edu.et', 'proctor_manager', 'Female', 'active');

-- Sample proctor
INSERT INTO users (fname, lname, username, password, email, user_type, sex, status) VALUES
('Habtamu', 'Tesfaye', 'proctor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'habtamu@uog.edu.et', 'proctor', 'Male', 'active');

-- Sample student dean
INSERT INTO users (fname, lname, username, password, email, user_type, sex, status) VALUES
('Meron', 'Yizelkal', 'student_dean', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'meron@uog.edu.et', 'student_dean', 'Female', 'active');

-- Sample student
INSERT INTO users (fname, lname, user_id_no, username, password, email, user_type, sex, status) VALUES
('Tsion', 'Endale', 'GUR/01867/09', 'student1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tsion@uog.edu.et', 'student', 'Female', 'active');

-- Sample blocks
INSERT INTO blocks (block_number, block_name, capacity, gender) VALUES
('B1', 'Block 1 - Male', 120, 'Male'),
('B2', 'Block 2 - Female', 120, 'Female'),
('B3', 'Block 3 - Male', 100, 'Male'),
('B4', 'Block 4 - Female', 100, 'Female');

-- Sample rooms for Block 1
INSERT INTO rooms (block_id, room_number, capacity, gender) VALUES
(1, '101', 4, 'Male'),
(1, '102', 4, 'Male'),
(1, '103', 4, 'Male'),
(1, '104', 4, 'Male'),
(1, '105', 4, 'Male'),
(2, '201', 4, 'Female'),
(2, '202', 4, 'Female'),
(2, '203', 4, 'Female'),
(2, '204', 4, 'Female'),
(2, '205', 4, 'Female');

-- Sample news
INSERT INTO news_events (title, content, type, posted_by) VALUES
('Welcome to UoG Dormitory Management System', 'Welcome to the web-based dormitory management system for Atse Tewodros Campus. Students can now view their dormitory allocation information online.', 'announcement', 1),
('Dormitory Allocation for 2019/2020 Academic Year', 'All new students are advised to check their dormitory allocation through this system. Please bring your student ID when moving in.', 'news', 1);

const express = require('express');
const cors = require('cors');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const path = require('path');
const { db, initDb } = require('./db');

const app = express();
const PORT = 5000;
const SECRET_KEY = 'student-management-secret';

initDb();

app.use(cors());
app.use(express.json());
app.use(express.static(path.join(__dirname, '../frontend')));

const logAction = (username, action, module) => {
  db.run(
    'INSERT INTO system_logs (username, action, module) VALUES (?, ?, ?)',
    [username, action, module]
  );
};

const authenticateToken = (req, res, next) => {
  const authHeader = req.headers.authorization;
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) {
    return res.status(401).json({ message: 'Thiếu token truy cập.' });
  }

  jwt.verify(token, SECRET_KEY, (err, user) => {
    if (err) {
      return res.status(403).json({ message: 'Token không hợp lệ.' });
    }
    req.user = user;
    next();
  });
};

app.post('/api/auth/login', (req, res) => {
  const { username, password } = req.body;

  db.get('SELECT * FROM users WHERE username = ?', [username], async (err, user) => {
    if (err) return res.status(500).json({ message: 'Lỗi server.' });
    if (!user) return res.status(400).json({ message: 'Tài khoản không tồn tại.' });

    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) {
      return res.status(400).json({ message: 'Sai mật khẩu.' });
    }

    const token = jwt.sign(
      {
        id: user.id,
        username: user.username,
        role: user.role,
        fullName: user.full_name,
      },
      SECRET_KEY,
      { expiresIn: '8h' }
    );

    logAction(user.username, 'Đăng nhập hệ thống', 'Auth');

    res.json({
      message: 'Đăng nhập thành công.',
      token,
      user: {
        id: user.id,
        username: user.username,
        fullName: user.full_name,
        role: user.role,
        email: user.email,
      },
    });
  });
});

app.get('/api/dashboard', authenticateToken, (req, res) => {
  db.serialize(() => {
    db.get('SELECT COUNT(*) AS totalStudents FROM students', [], (err1, students) => {
      db.get("SELECT COUNT(*) AS studying FROM students WHERE academic_status = 'Đang học'", [], (err2, studying) => {
        db.get("SELECT COUNT(*) AS reserved FROM students WHERE academic_status = 'Bảo lưu'", [], (err3, reserved) => {
          db.get('SELECT COUNT(*) AS totalNotifications FROM notifications', [], (err4, notifications) => {
            if (err1 || err2 || err3 || err4) {
              return res.status(500).json({ message: 'Không thể tải dashboard.' });
            }
            res.json({
              totalStudents: students.totalStudents,
              studying: studying.studying,
              reserved: reserved.reserved,
              totalNotifications: notifications.totalNotifications,
            });
          });
        });
      });
    });
  });
});

app.get('/api/students', authenticateToken, (req, res) => {
  const keyword = req.query.keyword || '';
  const sql = `
    SELECT * FROM students
    WHERE student_code LIKE ?
       OR full_name LIKE ?
       OR class_name LIKE ?
       OR faculty LIKE ?
    ORDER BY id DESC
  `;
  const search = `%${keyword}%`;

  db.all(sql, [search, search, search, search], (err, rows) => {
    if (err) return res.status(500).json({ message: 'Không thể lấy danh sách sinh viên.' });
    res.json(rows);
  });
});

app.post('/api/students', authenticateToken, (req, res) => {
  const {
    student_code,
    full_name,
    birth_date,
    gender,
    cccd,
    phone,
    email,
    permanent_address,
    temporary_address,
    parent_name,
    parent_phone,
    faculty,
    major,
    class_name,
    course_year,
    education_type,
    academic_status,
    scholarship_info,
  } = req.body;

  const sql = `
    INSERT INTO students (
      student_code, full_name, birth_date, gender, cccd, phone, email,
      permanent_address, temporary_address, parent_name, parent_phone,
      faculty, major, class_name, course_year, education_type,
      academic_status, scholarship_info
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `;

  db.run(
    sql,
    [
      student_code,
      full_name,
      birth_date,
      gender,
      cccd,
      phone,
      email,
      permanent_address,
      temporary_address,
      parent_name,
      parent_phone,
      faculty,
      major,
      class_name,
      course_year,
      education_type,
      academic_status,
      scholarship_info,
    ],
    function (err) {
      if (err) {
        return res.status(400).json({ message: 'Không thể thêm sinh viên. Có thể mã sinh viên đã tồn tại.' });
      }
      logAction(req.user.username, `Thêm sinh viên ${student_code}`, 'Students');
      res.json({ message: 'Thêm sinh viên thành công.', id: this.lastID });
    }
  );
});

app.put('/api/students/:id', authenticateToken, (req, res) => {
  const { id } = req.params;
  const {
    student_code,
    full_name,
    birth_date,
    gender,
    cccd,
    phone,
    email,
    permanent_address,
    temporary_address,
    parent_name,
    parent_phone,
    faculty,
    major,
    class_name,
    course_year,
    education_type,
    academic_status,
    scholarship_info,
  } = req.body;

  const sql = `
    UPDATE students SET
      student_code = ?,
      full_name = ?,
      birth_date = ?,
      gender = ?,
      cccd = ?,
      phone = ?,
      email = ?,
      permanent_address = ?,
      temporary_address = ?,
      parent_name = ?,
      parent_phone = ?,
      faculty = ?,
      major = ?,
      class_name = ?,
      course_year = ?,
      education_type = ?,
      academic_status = ?,
      scholarship_info = ?,
      updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
  `;

  db.run(
    sql,
    [
      student_code,
      full_name,
      birth_date,
      gender,
      cccd,
      phone,
      email,
      permanent_address,
      temporary_address,
      parent_name,
      parent_phone,
      faculty,
      major,
      class_name,
      course_year,
      education_type,
      academic_status,
      scholarship_info,
      id,
    ],
    function (err) {
      if (err) return res.status(400).json({ message: 'Không thể cập nhật sinh viên.' });
      logAction(req.user.username, `Cập nhật sinh viên ID ${id}`, 'Students');
      res.json({ message: 'Cập nhật sinh viên thành công.' });
    }
  );
});

app.delete('/api/students/:id', authenticateToken, (req, res) => {
  const { id } = req.params;
  db.run('DELETE FROM students WHERE id = ?', [id], function (err) {
    if (err) return res.status(400).json({ message: 'Không thể xóa sinh viên.' });
    logAction(req.user.username, `Xóa sinh viên ID ${id}`, 'Students');
    res.json({ message: 'Xóa sinh viên thành công.' });
  });
});

app.get('/api/notifications', authenticateToken, (req, res) => {
  db.all('SELECT * FROM notifications ORDER BY id DESC', [], (err, rows) => {
    if (err) return res.status(500).json({ message: 'Không thể lấy thông báo.' });
    res.json(rows);
  });
});

app.get('/api/logs', authenticateToken, (req, res) => {
  db.all('SELECT * FROM system_logs ORDER BY id DESC LIMIT 20', [], (err, rows) => {
    if (err) return res.status(500).json({ message: 'Không thể lấy nhật ký hệ thống.' });
    res.json(rows);
  });
});

app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, '../frontend/index.html'));
});

app.listen(PORT, () => {
  console.log(`Server đang chạy tại http://localhost:${PORT}`);
});

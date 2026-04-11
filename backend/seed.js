const bcrypt = require('bcryptjs');
const { db, initDb } = require('./db');

initDb();

const seed = async () => {
  const adminPassword = await bcrypt.hash('123456', 10);

  db.serialize(() => {
    db.run(
      `INSERT OR IGNORE INTO users (username, password, full_name, role, email)
       VALUES (?, ?, ?, ?, ?)`,
      ['admin', adminPassword, 'Quản trị hệ thống', 'admin', 'admin@school.edu.vn']
    );

    const students = [
      ['SV001', 'Nguyễn Văn An', '2004-01-15', 'Nam', '079123456789', '0912345678', 'an@gmail.com', 'TP.HCM', 'Thủ Đức', 'Nguyễn Văn B', '0909000001', 'CNTT', 'Công nghệ phần mềm', 'DCT121C1', '2022', 'Chính quy', 'Đang học', 'Học bổng khuyến khích'],
      ['SV002', 'Trần Thị Bình', '2004-05-20', 'Nữ', '079123456788', '0912345679', 'binh@gmail.com', 'Long An', 'TP.HCM', 'Trần Văn C', '0909000002', 'Kinh tế', 'Quản trị kinh doanh', 'QTKD22A', '2022', 'Chính quy', 'Đang học', 'Không'],
      ['SV003', 'Lê Hoàng Minh', '2003-11-02', 'Nam', '079123456787', '0912345680', 'minh@gmail.com', 'Đồng Nai', 'TP.HCM', 'Lê Văn D', '0909000003', 'CNTT', 'Hệ thống thông tin', 'HTTT21B', '2021', 'Chính quy', 'Bảo lưu', 'Không']
    ];

    students.forEach((student) => {
      db.run(
        `INSERT OR IGNORE INTO students (
          student_code, full_name, birth_date, gender, cccd, phone, email,
          permanent_address, temporary_address, parent_name, parent_phone,
          faculty, major, class_name, course_year, education_type,
          academic_status, scholarship_info
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        student
      );
    });

    db.run(
      `INSERT OR IGNORE INTO notifications (id, title, content, audience)
       VALUES (1, 'Thông báo đăng ký học phần', 'Sinh viên theo dõi lịch đăng ký học phần học kỳ mới trên hệ thống.', 'student')`
    );
  });

  console.log('Seed dữ liệu thành công.');
};

seed();

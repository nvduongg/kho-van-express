# Hệ thống Quản lý Chuỗi Cung ứng (SCM)

## Giới thiệu

Đây là một ứng dụng web được xây dựng bằng Laravel, cung cấp các chức năng cốt lõi cho việc quản lý chuỗi cung ứng. Hệ thống này giúp doanh nghiệp theo dõi và quản lý hiệu quả các hoạt động từ nhập xuất tồn kho, quản lý đơn hàng, vận chuyển đến báo cáo tổng quan.

## Tính năng chính

Hệ thống SCM này bao gồm các module chính sau:

* **Quản lý Sản phẩm**:
    * Tạo, xem, chỉnh sửa và xóa thông tin sản phẩm.
* **Quản lý Kho hàng**:
    * Tạo, xem, chỉnh sửa và xóa các kho lưu trữ hàng hóa.
* **Quản lý Tồn kho**:
    * Theo dõi số lượng tồn kho của từng sản phẩm tại từng kho.
    * Chức năng nhập/xuất kho thủ công.
    * Tự động cập nhật tồn kho khi đơn hàng được tạo hoặc chuyến hàng được thực hiện.
* **Quản lý Khách hàng**:
    * Lưu trữ thông tin chi tiết của khách hàng.
    * Tạo, xem, chỉnh sửa và xóa hồ sơ khách hàng.
* **Quản lý Đơn hàng**:
    * Tạo đơn hàng mới với nhiều sản phẩm.
    * Theo dõi trạng thái đơn hàng (đang chờ, đã hoàn thành, đã hủy).
    * Chức năng hoàn tất và hủy đơn hàng.
* **Quản lý Phương tiện**:
    * Quản lý thông tin các phương tiện vận chuyển (xe tải, xe van, v.v.).
    * Tạo, xem, chỉnh sửa và xóa phương tiện.
* **Quản lý Chuyến hàng (Shipments)**:
    * Lên kế hoạch và theo dõi các chuyến hàng.
    * Gán đơn hàng, phương tiện, kho xuất phát và kho đích cho chuyến hàng.
    * Cập nhật trạng thái chuyến hàng (đang chờ, đang vận chuyển, đã giao, đã hủy).
    * Đảm bảo xử lý các quan hệ dữ liệu linh hoạt (nullable relationships).
* **Báo cáo**:
    * **Báo cáo Doanh thu**: Thống kê tổng doanh thu theo ngày, tuần, tháng, năm với biểu đồ trực quan (sử dụng Chart.js).
    * **Báo cáo Tồn kho**: Hiển thị chi tiết số lượng sản phẩm tồn kho tại mỗi kho.
    * **Báo cáo Vận chuyển**: Thống kê các chuyến hàng theo trạng thái, phương tiện và danh sách chi tiết các chuyến.
    * Các báo cáo đều có tính năng lọc dữ liệu theo thời gian và các tiêu chí khác.

## Công nghệ sử dụng

* **Framework**: Laravel (phiên bản mới nhất)
* **Database**: MySQL (hoặc PostgreSQL, SQLite)
* **Frontend**:
    * Blade Templates
    * Tailwind CSS (cho UI)
    * Alpine.js (cho tương tác JavaScript nhẹ)
    * Chart.js (cho biểu đồ báo cáo)
* **Authentication**: Laravel Breeze
* **Development Tools**: Composer, npm

## Yêu cầu hệ thống

* PHP >= 8.1
* Composer
* Node.js & npm (hoặc Yarn)
* Cơ sở dữ liệu (MySQL, PostgreSQL, SQLite)

## Cài đặt dự án

Để thiết lập dự án trên máy cục bộ của bạn, hãy làm theo các bước sau:

1.  **Clone repository:**
    ```bash
    git clone <URL_CỦA_REPOSITORY_CỦA_BẠN>
    cd ten-du-an-scm
    ```

2.  **Cài đặt các dependency của Composer:**
    ```bash
    composer install
    ```

3.  **Cài đặt các dependency của Node.js và build tài nguyên frontend:**
    ```bash
    npm install
    npm run dev # Hoặc npm run build cho production
    ```

4.  **Tạo file `.env` và cấu hình môi trường:**
    * Sao chép file `.env.example` thành `.env`:
        ```bash
        cp .env.example .env
        ```
    * Mở file `.env` và cấu hình thông tin cơ sở dữ liệu của bạn (DB_DATABASE, DB_USERNAME, DB_PASSWORD). Ví dụ:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=scm_db
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Tạo khóa ứng dụng:**
    ```bash
    php artisan key:generate
    ```

6.  **Chạy migrations để tạo bảng trong cơ sở dữ liệu:**
    ```bash
    php artisan migrate
    ```
    * *Tùy chọn*: Để điền dữ liệu mẫu (ví dụ: người dùng, sản phẩm, kho, v.v.), bạn có thể chạy seeder:
        ```bash
        php artisan db:seed
        ```

7.  **Chạy máy chủ phát triển Laravel:**
    ```bash
    php artisan serve
    ```

Ứng dụng bây giờ sẽ có sẵn tại `http://127.0.0.1:8000`.

## Sử dụng ứng dụng

1.  **Đăng ký/Đăng nhập**: Truy cập `http://127.0.0.1:8000/register` để tạo tài khoản hoặc `http://127.0.0.1:8000/login` nếu bạn đã có tài khoản (hoặc sử dụng tài khoản từ `db:seed` nếu có).
2.  **Dashboard**: Sau khi đăng nhập, bạn sẽ được đưa đến trang Dashboard.
3.  **Điều hướng**: Sử dụng thanh điều hướng bên trái để truy cập các module khác nhau:
    * **Sản phẩm**: Quản lý thông tin các mặt hàng.
    * **Kho hàng**: Quản lý các địa điểm lưu trữ.
    * **Tồn kho**: Xem và điều chỉnh số lượng tồn kho.
    * **Khách hàng**: Quản lý thông tin khách hàng.
    * **Đơn hàng**: Tạo và theo dõi các đơn đặt hàng.
    * **Phương tiện**: Quản lý phương tiện vận chuyển.
    * **Chuyến hàng**: Lập kế hoạch và theo dõi quá trình giao hàng.
    * **Báo cáo**: Xem các báo cáo phân tích doanh thu, tồn kho và vận chuyển.

## Đóng góp

Mọi đóng góp cho dự án đều được hoan nghênh! Nếu bạn tìm thấy lỗi hoặc có ý tưởng cải tiến, vui lòng mở một issue hoặc gửi pull request.

## Giấy phép

Dự án này được cấp phép theo Giấy phép MIT. Xem file `LICENSE` để biết thêm chi tiết.

---
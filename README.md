# Hotel Management System

A comprehensive hotel management system built with CodeIgniter 3, featuring separate panels for administrators, staff, and customers.

## Features

### 🔐 Authentication System
- **Admin Panel**: Login and register functionality for administrators
- **Staff Panel**: Login for staff members (registered by admin)
- **Customer Panel**: Self-registration and login for customers
- Secure password hashing and session management

### 👨‍💼 Admin Panel
- **Dashboard**: Overview of hotel statistics and recent activities
- **User Management**: 
  - View all customers and staff
  - Add new staff members with department assignments
  - Edit and delete users
- **Room Management**:
  - Add, edit, and delete rooms
  - Set room types, prices, and amenities
  - Monitor room status
- **Booking Management**:
  - View all bookings
  - Update booking status
  - Generate reports
- **Reports**: Revenue tracking and booking statistics

### 👨‍💻 Staff Panel
- **Dashboard**: Room and booking overview
- **Booking Management**:
  - View and manage bookings
  - Check-in and check-out guests
  - Update booking status
- **Room Management**:
  - View room status
  - Update room availability
- **Search**: Search bookings by guest name, room, or status
- **Profile Management**: Update personal information and change password

### 👤 Customer Panel
- **Dashboard**: Personal booking overview
- **Room Browsing**: View available rooms with details
- **Booking System**:
  - Book rooms with date selection
  - View booking history
  - Cancel bookings (if allowed)
- **Profile Management**: Update personal information and change password

## Database Structure

The system uses the following main tables:
- `users`: Stores all user information (admin, staff, customers)
- `rooms`: Room details and availability
- `bookings`: Booking information and status
- `payments`: Payment tracking
- `staff_assignments`: Staff department and position information

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- CodeIgniter 3.x

### Setup Instructions

1. **Clone or download the project**
   ```bash
   git clone <repository-url>
   cd hotel
   ```

2. **Database Setup**
   - Create a new MySQL database named `hotel_management`
   - Import the `database.sql` file to create tables and sample data
   ```bash
   mysql -u root -p hotel_management < database.sql
   ```

3. **Configuration**
   - Update database settings in `application/config/database.php`:
     ```php
     'hostname' => 'localhost',
     'username' => 'your_username',
     'password' => 'your_password',
     'database' => 'hotel_management',
     ```

4. **Web Server Configuration**
   - Point your web server to the project directory
   - Ensure URL rewriting is enabled for CodeIgniter

5. **Access the System**
   - Main site: `http://your-domain/`
   - Admin login: `http://your-domain/admin/login`
   - Staff login: `http://your-domain/staff/login`
   - Customer login: `http://your-domain/customer/login`

## Default Login Credentials

### Admin
- **Username**: admin
- **Password**: admin123
- **Email**: admin@hotel.com

### Staff
- **Username**: staff1
- **Password**: admin123
- **Email**: staff1@hotel.com

- **Username**: staff2
- **Password**: admin123
- **Email**: staff2@hotel.com

## System Architecture

### Controllers
- `Auth.php`: Handles authentication for all user types
- `Admin.php`: Admin panel functionality
- `Staff.php`: Staff panel functionality
- `Customer.php`: Customer panel functionality
- `Pages.php`: Public pages

### Models
- `User_model.php`: User management and authentication
- `Room_model.php`: Room management and availability
- `Booking_model.php`: Booking operations and management

### Views
- `auth/`: Authentication views (login/register)
- `admin/`: Admin panel views
- `staff/`: Staff panel views
- `customer/`: Customer panel views

## Security Features

- Password hashing using PHP's `password_hash()`
- Session-based authentication
- Role-based access control
- Input validation and sanitization
- CSRF protection (CodeIgniter built-in)

## File Structure

```
hotel/
├── application/
│   ├── controllers/
│   │   ├── Auth.php
│   │   ├── Admin.php
│   │   ├── Staff.php
│   │   ├── Customer.php
│   │   └── Pages.php
│   ├── models/
│   │   ├── User_model.php
│   │   ├── Room_model.php
│   │   └── Booking_model.php
│   ├── views/
│   │   ├── auth/
│   │   ├── admin/
│   │   ├── staff/
│   │   ├── customer/
│   │   └── layout/
│   └── config/
│       ├── database.php
│       └── routes.php
├── system/
├── assets/
├── database.sql
└── index.php
```

## Usage

### For Administrators
1. Login with admin credentials
2. Access the admin dashboard
3. Manage users, rooms, and bookings
4. Generate reports and view statistics

### For Staff
1. Login with staff credentials (provided by admin)
2. Access the staff dashboard
3. Manage bookings and room status
4. Check-in and check-out guests

### For Customers
1. Register a new account or login
2. Browse available rooms
3. Make bookings
4. View booking history and manage profile

## Customization

### Adding New Features
- Create new controllers in `application/controllers/`
- Add corresponding models in `application/models/`
- Create views in appropriate directories
- Update routes in `application/config/routes.php`

### Styling
- The system uses Bootstrap 5 for responsive design
- Custom CSS can be added to individual view files
- Font Awesome icons are used throughout the interface

## Troubleshooting

### Common Issues
1. **Database Connection Error**: Check database credentials in `database.php`
2. **404 Errors**: Ensure URL rewriting is properly configured
3. **Session Issues**: Check PHP session configuration
4. **Permission Errors**: Ensure proper file permissions on upload directories

### Logs
- Check CodeIgniter logs in `application/logs/`
- Enable error reporting in development environment

## Support

For support and questions:
- Check the CodeIgniter documentation
- Review the code comments for implementation details
- Ensure all prerequisites are met

## License

This project is open source and available under the MIT License.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

---

**Note**: This is a demonstration system. For production use, ensure proper security measures, data validation, and error handling are implemented. 
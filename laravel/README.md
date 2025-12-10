# Smart Healthcare Management System

A complete, production-ready healthcare management system built with Laravel 10/11 and MySQL, featuring role-based access control, AI-powered symptom checker, and comprehensive healthcare modules.

## Features

### Core Modules
- **Patient Module**: Dashboard, appointment booking, medical history, prescriptions, billing, and AI symptom checker
- **Doctor Module**: Real-time appointments, patient history, prescriptions, lab requests
- **Lab Technician Module**: Test management, result uploads, status updates
- **Pharmacy Module**: Prescription management, medicine dispensing
- **Receptionist Module**: Patient check-in, billing, appointment management
- **Admin Module**: Analytics, user management, system reports

### Key Features
- **Role-based Authentication**: 6 user roles (Admin, Doctor, Patient, Lab, Pharmacy, Reception)
- **AI Symptom Checker**: Mock AI service for disease prediction
- **Responsive Design**: Built with Tailwind CSS
- **API Integration**: RESTful API for mobile app integration
- **Database Management**: MySQL with comprehensive schema
- **Billing System**: Payment processing and invoice generation

## Tech Stack

- **Backend**: PHP 8.2+, Laravel 10/11
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Breeze with custom role management
- **API**: RESTful API with JWT authentication

## Installation

### Prerequisites
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- Node.js and npm

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd shms-group2
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=SmartHealthcareDB
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed database**
   ```bash
   php artisan migrate --seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Database Setup

The system uses MySQL with the following database structure:

### Main Tables
- `users` - User authentication and role management
- `tbldoctor` - Doctor profiles
- `tblpatient` - Patient profiles
- `tbllabtechnician` - Lab technician profiles
- `tblappointment` - Appointment scheduling
- `tblprescription` - Medical prescriptions
- `tblmedicalrecord` - Patient medical records
- `tblbilling` - Billing and invoices
- `tbllabtest` - Laboratory tests
- `tblsymptominput` - Symptom checker inputs
- `tblsymptomresponse` - AI responses

### Sample Data
The system includes sample data for testing:
- 1 Admin user
- 3 Doctor users
- 3 Patient users
- 2 Lab Technician users
- 1 Receptionist user
- 1 Pharmacy user

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@hospital.com | admin123 |
| Doctor | ahmed@hospital.com | doctor123 |
| Patient | rahim@gmail.com | patient123 |
| Lab | tanvir@hospital.com | lab123 |
| Reception | reception@hospital.com | reception123 |
| Pharmacy | pharmacy@hospital.com | pharmacy123 |

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout

### Patient API
- `GET /api/patient/appointments` - Get patient appointments
- `GET /api/patient/medical-history` - Get medical history
- `GET /api/patient/prescriptions` - Get prescriptions
- `POST /api/patient/book-appointment` - Book appointment
- `POST /api/patient/check-symptoms` - Check symptoms with AI

### Doctor API
- `GET /api/doctor/appointments` - Get doctor appointments
- `GET /api/doctor/patient-history/{id}` - Get patient history
- `POST /api/doctor/create-prescription` - Create prescription
- `POST /api/doctor/request-lab-test` - Request lab test

### Lab API
- `GET /api/lab/tests` - Get lab tests
- `POST /api/lab/update-status/{id}` - Update test status
- `POST /api/lab/upload-report/{id}` - Upload test report

### Pharmacy API
- `GET /api/pharmacy/prescriptions` - Get prescriptions
- `POST /api/pharmacy/mark-dispensed/{id}` - Mark as dispensed

### Reception API
- `GET /api/reception/appointments` - Get appointments
- `POST /api/reception/book-appointment` - Book appointment
- `POST /api/reception/create-patient` - Create patient

### Admin API
- `GET /api/admin/dashboard` - Get dashboard analytics
- `GET /api/admin/users` - Get users
- `GET /api/admin/analytics` - Get analytics data

## Role-Based Access Control

The system implements role-based access control with the following middleware:

- `role:admin` - Administrator access
- `role:doctor` - Doctor access
- `role:patient` - Patient access
- `role:lab` - Lab technician access
- `role:pharmacy` - Pharmacy access
- `role:reception` - Receptionist access

## AI Symptom Checker

The AI Symptom Checker provides mock disease predictions based on symptom descriptions:

### Features
- Keyword-based disease matching
- Confidence scoring
- Treatment recommendations
- Health tips
- Severity assessment

### Usage
1. Navigate to Patient Dashboard → Symptom Checker
2. Enter symptom description
3. View AI predictions and recommendations
4. Get health tips based on symptoms

## Dashboard Features

### Patient Dashboard
- Upcoming appointments
- Medical history
- Prescriptions
- Billing status
- Symptom checker

### Doctor Dashboard
- Daily appointments
- Patient queue
- Recent prescriptions
- Pending lab tests

### Lab Dashboard
- Pending tests
- In-progress tests
- Completed tests
- Test management

### Pharmacy Dashboard
- New prescriptions
- Prescription details
- Dispensing status

### Reception Dashboard
- Today's appointments
- Patient check-in
- Appointment management
- Billing creation

### Admin Dashboard
- System analytics
- User management
- Revenue tracking
- Disease trends
- Report generation

## Testing

### Running Tests
```bash
php artisan test
```

### Manual Testing Checklist
- [ ] User authentication and role-based access
- [ ] Appointment booking and management
- [ ] Prescription creation and tracking
- [ ] Medical record management
- [ ] Billing and payment processing
- [ ] Lab test workflow
- [ ] AI symptom checker
- [ ] API endpoints
- [ ] Responsive design
- [ ] Data validation

## Security Features

- Role-based access control
- Input validation and sanitization
- CSRF protection
- SQL injection prevention
- XSS protection
- Secure password hashing

## Performance Optimization

- Database indexing
- Query optimization
- Caching implementation
- Asset optimization
- Pagination for large datasets

## Deployment

### Production Setup
1. Set up production environment
2. Configure environment variables
3. Run migrations
4. Optimize configuration
5. Set up cron jobs for scheduled tasks
6. Configure SSL certificates

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=SmartHealthcareDB
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

## Future Enhancements

- Real AI integration for symptom checker
- Mobile app development
- Telemedicine features
- Advanced reporting
- Inventory management
- Insurance integration
- Multi-language support
- Advanced analytics
- Patient portal
- Doctor portal

---

**Note**: This is a comprehensive healthcare management system designed for educational and demonstration purposes. Always ensure proper security measures and compliance with healthcare regulations before deploying to production.

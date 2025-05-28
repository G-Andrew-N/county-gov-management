# County Government Management System

This is a mini web application designed for managing county government staff and their accounts. The application allows for admin and staff login capabilities, staff registration, and document uploads.

## Features

- **Admin Dashboard**: Admins can manage staff accounts, view statistics, and approve new staff registrations.
- **Staff Dashboard**: Staff members can view their information, update their profiles, and check the status of their account approval.
- **User Authentication**: Secure login and registration processes for both staff and admin users.
- **Document Uploads**: Staff can upload necessary professional documents such as degree certificates, certifications, and insurance files during registration.

## File Structure

The project is organized into the following directories:

- **public/**: Contains all publicly accessible files including entry points, login, registration, and dashboards.
- **src/**: Contains the application logic including controllers, models, and views.
- **assets/**: Contains CSS, JavaScript, and uploaded documents.

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd county-gov-management
   ```

3. Install dependencies using Composer:
   ```
   composer install
   ```

4. Configure the database settings in `src/config/database.php`.

5. Start the web server and access the application through your browser.

## Usage

- To register as a new staff member, navigate to `public/register.php`.
- Admins can log in and access the dashboard at `public/admin/dashboard.php`.
- Staff can log in and view their dashboard at `public/staff/dashboard.php`.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for more details.
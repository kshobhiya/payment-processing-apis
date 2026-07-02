## Database Setup

Create a MySQL database:

```sql
CREATE DATABASE payment_processing;
```

Import the database:

```bash
mysql -u root -p payment_processing < database/payment_processing.sql
```

Or simply run the Laravel migrations:

```bash
php artisan migrate
```
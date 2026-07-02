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

## Screenshots

API testing was performed using **Postman**. Sample request and response screenshots are included in the project.

**Screenshots Location**


## Future Enhancement – Payment Gateway Webhook Integration

The current implementation simulates payment processing by generating **Approved** or **Rejected** responses. In a production environment, the payment processing workflow would be integrated with an external payment gateway using **webhooks**.

### Proposed Webhook Flow

Merchant
    │
Create Payment Order
    │
External Payment Gateway
(Stripe / Razorpay / PayPal)
    │
Customer Completes Payment
    │
Payment Gateway Sends Webhook
    │
HTTP POST /api/webhooks/payment
Webhook Controller
    │
Verify Signature / Authentication
    │
Validate Payload
    │
Update Payment Order Status
    │
Create Payment Transaction
    │
Trigger Notifications
(Email / SMS / In-App)

### Technical Design

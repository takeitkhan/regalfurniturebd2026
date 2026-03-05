# Custom Orders Feature Documentation

## Overview
The Custom Orders feature allows administrators to manually create orders for customers without using the regular online shopping cart. This is useful for phone orders, special requests, or wholesale orders.

## Features Implemented

### 1. Backend (OrdersManagement Controller)
**Location:** `app/Http/Controllers/Admin/OrdersManagement.php`

- **`customOrder()` method**: Displays the custom order creation form
- **`customOrderStore()` method**: Validates and saves the custom order to database

#### Key Features:
- Complete input validation
- Automatic generation of unique order and secret keys
- Support for multiple products in a single order
- Automatic email notification to customer
- Integration with One-Click Buy feature
- Activity logging for audit trail
- Proper error handling with fallbacks

#### Data Validation:
- Customer Name (required)
- Phone (required)
- Email (required, must be valid email format)
- Address (required)
- Products (required, at least one product)
- Total Price (required, numeric)
- Delivery Price (required, numeric)

### 2. Frontend (Views)
**Location:** `resources/views/order/custom_order.blade.php`

#### Customer Information Section:
- Customer Name
- Phone Number
- Emergency Phone
- Email
- Division Selection (with AJAX)
- District Selection (dynamic, based on division)
- Address
- Notes

#### Payment Information:
- Payment Method (Cash on Delivery, Debit/Credit Card, Mobile Banking)
- Payment Status (COD, Pending, Success, Cancelled)
- Order Status (Placed, Received, Processing, Picked, Shipped, Delivered, Cancelled)

#### Cart Management:
- Product Search (Select2 AJAX)
- Product Selection (Auto-populated fields)
- Quantity Input
- Price Input (editable)
- Discount Input
- Remove Product Button
- Cart Table Display

#### Totals Calculation:
- Sub Total (sum of all products)
- Total Discount (calculated from discount percentage)
- Delivery Charge (editable)
- Grand Total (auto-calculated)

### 3. JavaScript Functionality
**Location:** In the view file, `@push('scripts')` section

#### Features:
- **Select2 Integration**: AJAX-based product search with minimum 3 characters
- **Product Selection**: Auto-populates product details (image, code, price, etc.)
- **Cart Management**: 
  - Add products to cart with validation
  - Remove products from cart
  - Update quantity and price dynamically
  - Real-time total calculation
- **Location Selection**: 
  - Division selection loads corresponding districts
  - District selection sets delivery charge
- **Form Validation**: Required field validation before submission
- **Error Handling**: User-friendly error messages

## Routes

### Custom Order Routes
```php
Route::get('orders/custom-order', [...])
    ->name('order.custom_order')
    // Displays the custom order form

Route::post('orders/custom-order/store', [...])
    ->name('order.custom_order_store')
    // Stores the custom order

Route::get('custom-order/search_products', [...])
    ->name('custom_order_search_poduct')
    // AJAX endpoint for product search

Route::get('custom-order/select_products', [...])
    ->name('custom_order_select_poduct')
    // AJAX endpoint for product details
```

## Database Tables Used

### orders_master
Stores the main order information:
- order_random (unique identifier)
- customer_name, phone, email, address
- order_date, delivery_date
- payment_method, payment_term_status
- order_status
- currency, division, district, thana
- total_amount, delivery_fee, grand_total
- order_from (source: 'custom' or 'one click Buy')

### orders_detail
Stores individual products in the order:
- order_random (links to orders_master)
- product_id, product_name, product_code
- qty (quantity)
- local_selling_price, local_purchase_price
- delivery_charge, discount
- order_date, od_status (order detail status)

### activity_log (optional)
Tracks all custom order creation for audit purposes

## API Endpoints Used

### Product Search
```
GET /custom-order/search_products?keyword=<search_term>
```
Returns: JSON with paginated product list

### Product Details
```
GET /custom-order/select_products?productId=<id>
```
Returns: JSON with complete product information including image, price, etc.

### District by Division
```
GET /api/common/districts-by-diviison/<division>
```
Returns: JSON array of districts in the division

## Workflow

### Order Creation Flow:
1. Admin navigates to "Custom Order" menu
2. Fills in customer information (name, phone, email, address)
3. Selects division (automatically loads districts)
4. Selects district (automatically sets delivery charge)
5. Searches for products by typing (minimum 3 characters)
6. Selects a product from dropdown
7. System loads product details (image, code, price)
8. Admin adjusts quantity and price if needed
9. Admin sets discount percentage
10. Clicks "ADD" button to add to cart
11. Repeats steps 5-10 for additional products
12. Sets payment method and payment/order status
13. Reviews totals (auto-calculated)
14. Clicks "Submit" button
15. Order is created and customer receives email notification

## Error Handling

### Validation Errors:
- Empty required fields
- Invalid email format
- No products in cart
- Product not found during checkout

### API Errors:
- Product search failure
- District loading failure
- All errors show user-friendly messages

### Database Errors:
- Failed order creation
- Email sending failure (non-blocking)
- Activity logging failure (non-blocking)

## Features with One-Click Buy Integration

If an order is created from a One-Click Buy request:
1. The one-click buy record is marked as 'approve'
2. Customer information is pre-populated from one-click buy
3. Products from one-click buy are pre-loaded in cart
4. User is redirected to one-click buy list after order creation

## Security Features

1. CSRF Token Protection (via @csrf in form)
2. User Authentication (requires admin login)
3. Validation on both frontend and backend
4. Activity logging for audit trail
5. Proper error messages without exposing sensitive info

## Testing Checklist

- [ ] Customer information validation
- [ ] Product search returns results
- [ ] Product selection populates details
- [ ] Adding products to cart
- [ ] Removing products from cart
- [ ] Quantity and price updates
- [ ] Discount calculation
- [ ] Delivery charge calculation
- [ ] Grand total calculation
- [ ] Form submission
- [ ] Order saved to database
- [ ] Customer email sent
- [ ] One-click buy integration
- [ ] District/Division selection
- [ ] Error messages display correctly

## Files Modified/Created

1. **Controller**: `app/Http/Controllers/Admin/OrdersManagement.php`
   - Updated `customOrder()` method
   - Updated `customOrderStore()` method

2. **View**: `resources/views/order/custom_order.blade.php`
   - Added @csrf token
   - Fixed form field names
   - Improved cart table structure
   - Enhanced JavaScript functionality

3. **Routes**: `routes/web.php`
   - Already configured (no changes needed)

## Dependencies

- Laravel Framework (>=8.0)
- Select2 JavaScript library
- jQuery
- Blade Template Engine
- Laravel Models: Product, OrdersMaster, OrdersDetail, Oneclickbuy
- Helpers: OrderMailHelper, ActivityLog

## Known Limitations

1. Product images must be accessible via the icon_size_directory path
2. Division/District API must return proper JSON format
3. Delivery charges are set per district
4. One product can only be added once per order (user must remove and re-add to modify)

## Future Enhancements

1. Bulk product import
2. Customer quick-select from recent orders
3. Order templates for repeat customers
4. Custom discount codes
5. Product bundle support
6. Inventory deduction
7. Multiple order creation from template
8. Export order to PDF/receipt

## Support & Troubleshooting

### Products not loading in search:
- Check that product search route is working
- Verify products exist in database
- Check Select2 JavaScript library is loaded

### Delivery charge not updating:
- Verify district data is being fetched
- Check district API endpoint
- Verify charge values in PaymentSetting

### Order not saving:
- Check database connection
- Verify all required fields have values
- Check error logs in storage/logs

### Email not sending:
- Non-blocking, order still creates
- Check mail configuration in .env
- Verify customer email is valid

## Contact & Support
For issues or questions about this feature, please contact the development team.

# Custom Orders - System Architecture & Flow Diagrams

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                      ADMIN INTERFACE                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Custom Order Form (Blade Template)                      │   │
│  │  - Customer Information                                  │   │
│  │  - Product Search (Select2 AJAX)                         │   │
│  │  - Cart Management                                       │   │
│  │  - Payment Details                                       │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────┬──────────────────────────────────────────────────────┘
           │ HTTP POST/GET
           │
┌──────────▼──────────────────────────────────────────────────────┐
│                  Laravel Backend                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  OrdersManagement Controller                            │    │
│  │  - customOrder()          [Display Form]                │    │
│  │  - customOrderStore()     [Validate & Save]             │    │
│  │  - Product Search API                                   │    │
│  │  - Product Details API                                  │    │
│  └─────────────────────────────────────────────────────────┘    │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  Models & Repositories                                  │    │
│  │  - OrdersMaster                                         │    │
│  │  - OrdersDetail                                         │    │
│  │  - Product                                              │    │
│  │  - Oneclickbuy                                          │    │
│  │  - ActivityLog                                          │    │
│  └─────────────────────────────────────────────────────────┘    │
└──────────┬──────────────────────────────────────────────────────┘
           │ Database Write
           │
┌──────────▼──────────────────────────────────────────────────────┐
│                    MySQL Database                               │
│  ┌─────────────────┐ ┌──────────────────┐ ┌──────────────────┐ │
│  │ orders_master   │ │ orders_detail    │ │ products         │ │
│  │ - id            │ │ - id             │ │ - id             │ │
│  │ - order_random  │ │ - order_random   │ │ - title          │ │
│  │ - customer_name │ │ - product_id     │ │ - product_code   │ │
│  │ - email         │ │ - qty            │ │ - price          │ │
│  │ - grand_total   │ │ - local_selling_ │ │ - user_id        │ │
│  │ - ...           │ │   price          │ │ - ...            │ │
│  └─────────────────┘ └──────────────────┘ └──────────────────┘ │
└──────────────────────────────────────────────────────────────────┘
           │ Query Data
           │
┌──────────▼──────────────────────────────────────────────────────┐
│                    External Services                             │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  Email Service                                          │    │
│  │  - Order confirmation to customer                       │    │
│  └─────────────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Request Flow Diagram

```
USER ACTION                JAVASCRIPT                 BACKEND              DATABASE
─────────────────────────────────────────────────────────────────────────────
    │
    ├─ Type product name
    │                     ├─ Validate input (≥3 chars)
    │                     │
    │                     ├─ AJAX GET request
    │                     │                        ├─ Route: /custom-order/search_products
    │                     │                        ├─ Method: search_product()
    │                     │                        ├─ Query database
    │                     │                        │
    │                     │                        ├─ Return JSON
    │                     │                        │
    │                     ├─ Display dropdown results
    │
    ├─ Select product
    │                     ├─ AJAX GET request
    │                     │                        ├─ Route: /custom-order/select_products
    │                     │                        ├─ Method: product_details_by_id()
    │                     │                        ├─ Query database
    │                     │                        │
    │                     │                        ├─ Return product data
    │                     │                        │
    │                     ├─ Populate form fields
    │
    ├─ Enter quantity & price
    │                     ├─ No backend call
    │
    ├─ Click ADD button
    │                     ├─ Validate all fields
    │                     ├─ Add row to cart table
    │                     ├─ Clear form
    │                     ├─ Calculate totals
    │
    ├─ (Repeat for more products)
    │
    ├─ Select division
    │                     ├─ AJAX GET request
    │                     │                        ├─ Route: /api/common/districts-by-diviison
    │                     │                        ├─ Query database
    │                     │                        │
    │                     │                        ├─ Return districts
    │                     │                        │
    │                     ├─ Load district dropdown
    │
    ├─ Select district
    │                     ├─ Get delivery charge
    │                     ├─ Update delivery field
    │                     ├─ Calculate grand total
    │
    ├─ Fill remaining fields
    │                     ├─ No backend call
    │
    ├─ Click SUBMIT
    │                     ├─ Validate form
    │                     ├─ Check products exist
    │                     ├─ POST request
    │                     │                        ├─ Route: /orders/custom-order/store
    │                     │                        ├─ Validate input
    │                     │                        ├─ Create order_master   ──┐
    │                     │                        ├─ Create order_details  ──┤─ Database Write
    │                     │                        ├─ Update oneclickbuy    ──┤
    │                     │                        ├─ Log activity          ──┤
    │                     │                        ├─ Send email            ──┘
    │                     │                        │
    │                     │                        ├─ Return redirect
    │                     │                        │
    │                     ├─ Redirect to /orders
    │
    └─ Success page displayed
```

---

## 📊 Data Flow Diagram

```
FORM INPUT
│
├─ Customer Information
│  ├─ Name
│  ├─ Phone
│  ├─ Email
│  ├─ Address
│  ├─ Division
│  └─ District
│
├─ Product Cart
│  ├─ Product ID
│  ├─ Quantity
│  ├─ Price
│  └─ Discount
│
├─ Payment Information
│  ├─ Payment Method
│  ├─ Payment Status
│  └─ Order Status
│
└─ Calculated Values
   ├─ Subtotal
   ├─ Total Discount
   ├─ Delivery Charge
   └─ Grand Total
       │
       ▼
   VALIDATION LAYER
       │
       ├─ Required fields
       ├─ Email format
       ├─ Numeric values
       ├─ At least 1 product
       │
       ▼
   BACKEND PROCESSING
       │
       ├─ Generate Order ID
       ├─ Create Order Master Record
       │  ├─ order_random
       │  ├─ secret_key
       │  ├─ customer info
       │  ├─ totals
       │  └─ status
       │
       ├─ Create Order Detail Records
       │  └─ For each product:
       │     ├─ product_id
       │     ├─ qty
       │     ├─ price
       │     ├─ discount
       │     └─ status
       │
       ├─ Update Related Records
       │  ├─ Oneclickbuy (if applicable)
       │  └─ ActivityLog
       │
       ├─ Send Communications
       │  └─ Email to customer
       │
       ▼
   DATABASE PERSISTENCE
       │
       ├─ orders_master ✓
       ├─ orders_detail ✓
       ├─ activity_log ✓
       └─ oneclickbuy ✓
           │
           ▼
       ADMIN REDIRECT
       │
       └─ Orders List
```

---

## 🔌 API Interaction Flow

```
CLIENT (Browser)
│
├─ GET /orders/custom-order
│  │
│  └─► OrdersManagement::customOrder()
│      └─► Return custom_order.blade.php
│
├─ GET /custom-order/search_products?keyword=shirt
│  │
│  └─► ProductController::search_product()
│      ├─► Query products WHERE title LIKE %shirt%
│      └─► Return JSON: { products: [...] }
│
├─ GET /custom-order/select_products?productId=123
│  │
│  └─► ProductController::product_details_by_id()
│      ├─► Query product WHERE id=123
│      └─► Return JSON: { product: {...} }
│
├─ GET /api/common/districts-by-diviison/Dhaka
│  │
│  └─► CommonController::getDistrictsByDivision()
│      ├─► Query districts WHERE division='Dhaka'
│      └─► Return JSON: [{...}, {...}]
│
└─ POST /orders/custom-order/store
   │
   └─► OrdersManagement::customOrderStore()
       ├─► Validate all input
       ├─► Create orders_master
       ├─► Create orders_detail
       ├─► Update oneclickbuy (if exists)
       ├─► Create activity_log
       ├─► Send email
       └─► Return redirect to /orders
```

---

## 💾 Database Schema Relationships

```
┌─────────────────────┐
│  orders_master      │
├─────────────────────┤
│ id (PK)             │◄─────────┐
│ order_random (UQ)   │          │ (1:N)
│ customer_name       │          │
│ phone               │          │
│ email               │          │
│ address             │          │
│ payment_method      │          │
│ payment_term_status │          │
│ order_status        │          │
│ total_amount        │          │
│ delivery_fee        │          │
│ grand_total         │          │
│ user_id (FK)        │          │
│ division            │          │
│ district            │          │
└─────────────────────┘          │
                                 │
┌─────────────────────┐          │
│  orders_detail      │          │
├─────────────────────┤          │
│ id (PK)             │          │
│ order_random (FK)   ├──────────┘
│ product_id (FK)     │
│ product_name        │
│ qty                 │
│ local_selling_price │
│ local_purchase_price│
│ discount            │
│ od_status           │
│ order_date          │
└─────────────────────┘
        ▲
        │ (N:1)
        │
┌─────────────────────┐
│  products           │
├─────────────────────┤
│ id (PK)             │
│ title               │
│ product_code        │
│ sku                 │
│ product_price_now   │
│ user_id (FK)        │
│ description         │
│ is_active           │
└─────────────────────┘
```

---

## 🎯 Calculation Flow

```
USER INPUT
├─ Qty: 2
├─ Price: 500
├─ Discount: 10%
│
└─► CALCULATION ENGINE
    │
    ├─ Line Total = Qty × Price
    │  = 2 × 500 = 1000
    │
    ├─ Discount Amount = (Discount% / 100) × Line Total
    │  = (10 / 100) × 1000 = 100
    │
    └─► OUTPUT
        ├─ Line Subtotal: 1000
        ├─ Line Discount: 100
        └─ Line Total: 900


CART AGGREGATION
├─ Product 1: Subtotal 1000, Discount 100
├─ Product 2: Subtotal 2000, Discount 200
├─ Product 3: Subtotal 500, Discount 0
│
└─► GRAND TOTAL CALCULATION
    │
    ├─ Sub Total = 1000 + 2000 + 500 = 3500
    ├─ Total Discount = 100 + 200 + 0 = 300
    ├─ Delivery Charge = 100 (from district)
    │
    └─► Grand Total = 3500 - 300 + 100 = 3300
```

---

## 🚦 Status Transitions

```
Order Status Flow:
┌─────────┐
│ Placed  │ ─────────► Received ─────────► Processing
└─────────┘
   │                                          │
   │                                          ▼
   │                                    ┌──────────┐
   │                                    │ Picked   │
   │                                    └──────────┘
   │                                          │
   │                                          ▼
   │                                    ┌──────────┐
   │                                    │ Shipped  │
   │                                    └──────────┘
   │                                          │
   │                                          ▼
   │                                    ┌──────────┐
   │                                    │Delivered│
   │                                    └──────────┘
   │
   └──────────────► Cancelled

Payment Status Flow:
┌─────────┐
│   COD   │ ─────────► Pending ─────────► Success
└─────────┘
   │
   └──────────────► Failed
```

---

## 📦 Module Dependencies

```
Custom Orders Feature
│
├─ Laravel Framework
│  ├─ Blade Templates
│  ├─ Request Validation
│  ├─ Controllers
│  ├─ Models/Eloquent
│  └─ Database
│
├─ Frontend Libraries
│  ├─ jQuery
│  ├─ Select2 (AJAX dropdowns)
│  └─ Bootstrap (CSS framework)
│
├─ Models
│  ├─ OrdersMaster
│  ├─ OrdersDetail
│  ├─ Product
│  ├─ Oneclickbuy
│  └─ ActivityLog
│
├─ Helpers
│  ├─ OrderMailHelper (Email)
│  └─ Other common helpers
│
└─ External Services
   └─ Email Service (Mail)
```

---

## 🔐 Security Flow

```
USER REQUEST
│
├─► CSRF Token Check ✓
│   └─ Form includes @csrf
│
├─► Authentication Check ✓
│   └─ Must be logged in
│
├─► Authorization Check ✓
│   └─ Must be admin user
│
├─► Input Validation ✓
│   ├─ Required fields
│   ├─ Email format
│   ├─ Data type checking
│   └─ Range validation
│
├─► Sanitization ✓
│   └─ HTML escaping in Blade
│
├─► SQL Injection Prevention ✓
│   └─ Parameterized queries (Eloquent)
│
├─► XSS Prevention ✓
│   └─ Output escaping
│
└─► Error Message Sanitization ✓
    └─ No sensitive info exposed
```

---

## 📈 Performance Optimization

```
Database Queries
├─ Single query to create order_master
├─ Single bulk insert for order_details
├─ Separate query for oneclickbuy update
└─ Separate query for activity_log

Frontend Optimization
├─ AJAX requests (asynchronous)
├─ Real-time calculations (client-side)
├─ Lazy loading (Select2)
├─ Event delegation (jQuery)
└─ No unnecessary page reloads

Caching Strategy
├─ Products cached (if configured)
├─ Division/District data cached
└─ No cache invalidation needed
```

---

## 🧪 Testing Strategy

```
Unit Tests
├─ Model validation
├─ Helper functions
└─ Route definitions

Integration Tests
├─ Form submission
├─ Database writes
├─ Email sending
└─ Redirect responses

Functional Tests
├─ User workflows
├─ Error scenarios
├─ Edge cases
└─ Cross-browser compatibility

Performance Tests
├─ Response time
├─ Database query time
├─ AJAX response time
└─ Page load time
```

---

## 🔍 Debugging Points

```
When Creating Order:
1. Check browser console (F12) for JS errors
2. Check Network tab for failed AJAX
3. Check Laravel logs in storage/logs/
4. Check database - did records get created?
5. Check email logs
6. Verify form data submitted

Search Debug:
1. Check keyword length (min 3)
2. Check products exist in DB
3. Check AJAX response in Network tab
4. Check search_product method in controller

Calculation Debug:
1. Check input values in form
2. Check JavaScript console for values
3. Verify formula: (qty × price) - discount + delivery
4. Check database values
```

---

## 📊 Metrics & Monitoring

```
Track These Metrics:
├─ Orders created per day
├─ Average order value
├─ Products per order
├─ Most searched products
├─ Payment method distribution
├─ Order status distribution
└─ System errors/failures

Alert Conditions:
├─ Order creation failure
├─ Email sending failure
├─ Database connection error
├─ Server response time > 2s
└─ AJAX request timeout
```

---

This documentation provides a complete technical overview of the Custom Orders system architecture, flow, and integration points.

For more details, see the other documentation files.

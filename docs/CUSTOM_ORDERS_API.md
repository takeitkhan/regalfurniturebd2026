# Custom Orders API Endpoints Documentation

## Overview
This document describes all API endpoints used by the Custom Orders feature.

## Endpoints

### 1. Custom Order Form Display
**Endpoint:** `GET /orders/custom-order`
**Route Name:** `order.custom_order`
**Method:** `customOrder()` in OrdersManagement

**Response:**
- Returns HTML form for creating custom orders
- No parameters required
- Requires admin authentication

**Example:**
```
GET /orders/custom-order HTTP/1.1
```

---

### 2. Store Custom Order
**Endpoint:** `POST /orders/custom-order/store`
**Route Name:** `order.custom_order_store`
**Method:** `customOrderStore()` in OrdersManagement

**Required Parameters:**
```json
{
    "customerName": "John Doe",
    "phone": "01712345678",
    "emergencyPhone": "01787654321",
    "email": "john@example.com",
    "address": "123 Main St, Dhaka",
    "notes": "Delivery after 5 PM",
    "division": "Dhaka",
    "district": "Dhaka",
    "thana": "Motijheel",
    "paymentmethod": "cash_on_delivery",
    "payment_term_status": "Pending",
    "order_status": "placed",
    "total_price": "5000",
    "delivery_price": "100",
    "grand_total": "5100",
    "product": {
        "1": {
            "product_id": "123",
            "product_name": "Product Name",
            "product_code": "SKU123",
            "qty": "2",
            "price": "2500",
            "product_discount": "0"
        },
        "2": {
            "product_id": "124",
            "product_name": "Product Name 2",
            "product_code": "SKU124",
            "qty": "1",
            "price": "5000",
            "product_discount": "10"
        }
    },
    "order_source": "custom",
    "oneclickbuy_id": null
}
```

**Validation Rules:**
- `customerName`: required, string
- `phone`: required, string
- `email`: required, email
- `address`: required, string
- `product`: required, array (at least one product)
- `total_price`: required, numeric
- `delivery_price`: required, numeric

**Response on Success:**
```
Redirect to /orders with success message
Status: 302 (Redirect)
Headers: Location: /orders
Flash Message: "Custom order successfully created"
```

**Response on Validation Error:**
```
Redirect back with error messages
Status: 302 (Redirect)
Headers: Location: /orders/custom-order
Response contains validation errors
```

**Response on Duplicate One-Click Order:**
```
Redirect to one-click-buy-now
Status: 302 (Redirect)
Headers: Location: /orders/one-click-buy-now
Flash Message: "This one-click order has already been approved"
```

**Database Changes:**
- Creates new record in `orders_master`
- Creates records in `orders_detail` (one per product)
- Creates activity log entry
- Updates `oneclickbuy` record if applicable

**Side Effects:**
- Sends email to customer
- Creates activity log entry
- Triggers webhook (if configured)

---

### 3. Search Products (AJAX)
**Endpoint:** `GET /custom-order/search_products`
**Route Name:** `custom_order_search_poduct`
**Method:** `search_product()` in ProductController

**Query Parameters:**
```
keyword = "shirt" (minimum 3 characters)
```

**Request Example:**
```
GET /custom-order/search_products?keyword=shirt HTTP/1.1
Accept: application/json
```

**Response:**
```json
{
    "products": {
        "data": [
            {
                "id": 123,
                "title": "Blue Shirt",
                "sub_title": "Premium Cotton",
                "product_code": "SKU123",
                "product_price_now": 1500,
                "first_image": {
                    "icon_size_directory": "uploads/products/123.jpg"
                }
            },
            {
                "id": 124,
                "title": "Red Shirt",
                "sub_title": "Premium Cotton",
                "product_code": "SKU124",
                "product_price_now": 1500,
                "first_image": {
                    "icon_size_directory": "uploads/products/124.jpg"
                }
            }
        ],
        "total": 2,
        "per_page": 15,
        "current_page": 1
    }
}
```

**Error Response:**
```json
{
    "products": {
        "data": [],
        "total": 0
    }
}
```

**HTTP Status:**
- 200 OK on success
- 400 Bad Request if keyword length < 3

---

### 4. Get Product Details (AJAX)
**Endpoint:** `GET /custom-order/select_products`
**Route Name:** `custom_order_select_poduct`
**Method:** `product_details_by_id()` in ProductController

**Query Parameters:**
```
productId = "123"
OR
sku = "SKU123"
```

**Request Example:**
```
GET /custom-order/select_products?productId=123 HTTP/1.1
Accept: application/json
```

**Response on Success:**
```json
{
    "product": {
        "id": 123,
        "title": "Blue Shirt",
        "sub_title": "Premium Cotton",
        "product_code": "SKU123",
        "sku": "SKU123",
        "product_price_now": 1500,
        "product_price": 1500,
        "first_image": {
            "icon_size_directory": "uploads/products/123.jpg",
            "id": 1001
        },
        "user_id": 5,
        "description": "...",
        "is_active": 1
    }
}
```

**Response on Not Found:**
```json
false
```

**HTTP Status:**
- 200 OK with product data on success
- 200 OK with false if product not found

---

### 5. Get Districts by Division (AJAX)
**Endpoint:** `GET /api/common/districts-by-diviison/{division}`
**Method:** Districts API endpoint

**URL Parameters:**
```
division = "Dhaka" | "Chittagong" | "Khulna" | "Sylhet" | "Barisal" | "Rajshahi" | "Rangpur"
```

**Request Example:**
```
GET /api/common/districts-by-diviison/Dhaka HTTP/1.1
Accept: application/json
```

**Response:**
```json
[
    {
        "id": 1,
        "district": "Dhaka",
        "division": "Dhaka",
        "is_active": 1
    },
    {
        "id": 2,
        "district": "Gazipur",
        "division": "Dhaka",
        "is_active": 1
    },
    {
        "id": 3,
        "district": "Narayanganj",
        "division": "Dhaka",
        "is_active": 1
    }
]
```

**HTTP Status:**
- 200 OK on success
- 404 Not Found if division invalid
- 500 Server Error on database error

---

## Request/Response Examples

### Example 1: Complete Order Creation Flow

**Step 1: Display Form**
```
GET /orders/custom-order
```
Response: HTML form

**Step 2: Search for Product**
```
GET /custom-order/search_products?keyword=shirt
```
Response: JSON array of products

**Step 3: Get Product Details**
```
GET /custom-order/select_products?productId=123
```
Response: Complete product data

**Step 4: Get Districts**
```
GET /api/common/districts-by-diviison/Dhaka
```
Response: District list

**Step 5: Submit Order**
```
POST /orders/custom-order/store
Content-Type: application/x-www-form-urlencoded

customerName=John&phone=01712345678&email=john@example.com&address=123 Main St&paymentmethod=cash_on_delivery&payment_term_status=Pending&order_status=placed&total_price=5000&delivery_price=100&division=Dhaka&district=Dhaka&product[1][product_id]=123&product[1][qty]=2&product[1][price]=2500
```

**Response: Redirect**
```
HTTP/1.1 302 Found
Location: /orders
Set-Cookie: XSRF-TOKEN=...
```

---

## Error Handling

### Common Errors and Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| 404 Not Found | Route doesn't exist | Check routes/web.php |
| 419 Token Mismatch | CSRF token missing/invalid | Include `@csrf` in form |
| 422 Unprocessable Entity | Validation failed | Check all required fields |
| 500 Server Error | Database error | Check database connection |
| No products in response | Search keyword < 3 chars | Require minimum 3 characters |

---

## Authentication

All endpoints require:
- User must be authenticated
- User must have admin role
- CSRF token (for POST requests)

```php
// In controller
$this->middleware('auth');
$this->middleware('admin'); // or similar role check
```

---

## Rate Limiting

No specific rate limiting configured for these endpoints.
Recommendation: Implement rate limiting for product search to prevent abuse.

---

## CORS Headers

Not applicable for same-origin requests.
No CORS headers required unless frontend is on different domain.

---

## Changelog

### v1.0.0 (Current)
- Initial implementation
- Product search with Select2
- Order creation and storage
- Email notification
- One-Click Buy integration

### Future Versions
- Bulk product import
- Order templates
- Custom discount codes
- Inventory tracking


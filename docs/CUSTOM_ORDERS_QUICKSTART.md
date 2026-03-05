# Custom Orders - Quick Start Guide

## 🚀 Getting Started

### For Admin Users

#### Step 1: Access Custom Orders
```
1. Log in to Admin Dashboard
2. Click "Orders" in sidebar
3. Click "Custom Order"
```

#### Step 2: Fill Customer Information
```
- Customer Name: Enter full name
- Phone: Enter phone number  
- Emergency Phone: (optional) Another contact number
- Email: Valid email address
- Division: Select from dropdown
- District: Auto-loads after selecting Division
- Address: Enter delivery address
- Notes: (optional) Any special instructions
```

#### Step 3: Add Products
```
1. Search for product by typing product name (min. 3 characters)
2. Select product from dropdown
3. Quantity: Enter number of units
4. Price: Adjust if needed
5. Discount: Enter discount percentage (e.g., 10)
6. Click "ADD" button
```

#### Step 4: Review Cart
```
- Check products in cart table below
- Edit quantity or price if needed
- View line totals automatically calculated
- Remove any product with red X button
```

#### Step 5: Set Payment Details
```
- Payment Method: Select (Cash on Delivery, Card, Mobile Banking)
- Payment Status: Select (COD, Pending, Success, Cancelled)
- Order Status: Select (Placed, Processing, etc.)
```

#### Step 6: Review Totals
```
- Sub Total: Auto-calculated from all products
- Discount: Auto-calculated from discount percentages
- Delivery Charge: Auto-set from district, editable
- Grand Total: Auto-calculated (Sub - Discount + Delivery)
```

#### Step 7: Submit Order
```
Click "Submit" button
Order is created and customer receives confirmation email
You're redirected to Orders list
```

---

## ✅ Verification Checklist

After implementation, verify these work:

- [ ] Can access `/orders/custom-order` page
- [ ] Product search returns results (type ≥3 characters)
- [ ] Product details populate when selected
- [ ] Can add product to cart
- [ ] Can remove product from cart
- [ ] Totals update when qty/price change
- [ ] District dropdown loads on division select
- [ ] Delivery charge updates when district changes
- [ ] Form submits successfully
- [ ] Order appears in Orders list
- [ ] Customer receives email
- [ ] Order details display correctly

---

## 🔧 Troubleshooting

### Issue: Products not appearing in search
**Solution:**
- Wait for AJAX response (small delay expected)
- Type at least 3 characters
- Check that products exist in database
- Check browser console for errors (F12)

### Issue: Form won't submit
**Solution:**
- Ensure all required fields are filled
- Ensure at least one product is in cart
- Check for red validation messages
- Verify email format is correct

### Issue: Totals not calculating
**Solution:**
- Refresh the page
- Check browser console for JS errors
- Ensure JavaScript is enabled
- Try in different browser

### Issue: Email not received
**Solution:**
- Check if customer email is correct
- Email might be in spam folder
- Check server mail configuration
- See CUSTOM_ORDERS_README.md for details

---

## 📊 Data Fields Reference

### Customer Information
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Customer Name | Text | Yes | Full name |
| Phone | Text | Yes | Phone number |
| Emergency Phone | Text | No | Alternative contact |
| Email | Email | Yes | Valid email address |
| Division | Dropdown | Yes | Geographic division |
| District | Dropdown | Yes | Auto-loaded from division |
| Address | Text | Yes | Delivery address |
| Notes | Text | No | Special instructions |

### Product Information
| Field | Type | Notes |
|-------|------|-------|
| Product | Search | Type ≥3 characters |
| Quantity | Number | ≥1 |
| Price | Number | Per unit |
| Discount | Number | As percentage |

### Payment Information
| Field | Type | Options |
|-------|------|---------|
| Payment Method | Dropdown | Cash on Delivery, Card, Mobile Banking |
| Payment Status | Dropdown | COD, Pending, Success, Cancelled |
| Order Status | Dropdown | Placed, Received, Processing, Picked, Shipped, Delivered, Cancelled |

---

## 💰 Calculation Examples

### Example 1: Single Product, No Discount
```
Product: T-Shirt
Qty: 2
Price: 500 BDT each
Discount: 0%

Sub Total: 2 × 500 = 1000 BDT
Total Discount: 1000 × 0% = 0 BDT
Delivery Charge: 100 BDT (from district)
Grand Total: 1000 - 0 + 100 = 1100 BDT
```

### Example 2: Multiple Products with Discount
```
Product 1: Shirt
Qty: 1, Price: 800, Discount: 10%

Product 2: Pants  
Qty: 2, Price: 1000, Discount: 5%

Sub Total: 800 + (2 × 1000) = 2800 BDT
Item 1 Discount: 800 × 10% = 80 BDT
Item 2 Discount: 2000 × 5% = 100 BDT
Total Discount: 180 BDT
Delivery Charge: 100 BDT
Grand Total: 2800 - 180 + 100 = 2720 BDT
```

---

## 🎯 Common Tasks

### Task: Create order for phone customer
```
1. Customer calls with order details
2. Open Custom Order form
3. Enter customer name, phone, email, address
4. Search for each product customer wants
5. Add to cart with quantity and confirm price
6. Set payment method (usually Cash on Delivery)
7. Submit order
8. Confirm order ID to customer
```

### Task: Create order from One-Click Buy
```
1. Go to "One Click Buy Now" menu
2. Find pending request
3. Click "Create Custom Order" link
4. Customer info auto-fills
5. Products auto-populate if available
6. Review and adjust as needed
7. Submit order
```

### Task: Modify order during creation
```
1. Product already in cart but need different qty?
   → Remove and re-add with correct qty
2. Need to change price?
   → Click price field and edit
3. Wrong district selected?
   → Change division, then district
4. Need to cancel?
   → Just navigate away, nothing is saved until Submit
```

---

## 📋 Order Status Meanings

| Status | Meaning |
|--------|---------|
| Placed | Order created, waiting to be processed |
| Received | Order received and confirmed |
| Processing | Order is being prepared |
| Picked | Items picked from inventory |
| Shipped | Order dispatched to customer |
| Delivered | Order delivered to customer |
| Cancelled | Order cancelled by admin or customer |

---

## 💻 API Response Codes

| Code | Meaning | Action |
|------|---------|--------|
| 200 | Success | Proceed normally |
| 302 | Redirect | Follow redirect (usually after form submit) |
| 400 | Bad Request | Check form data |
| 404 | Not Found | Check product/route exists |
| 422 | Validation Error | Check validation messages |
| 500 | Server Error | Check server logs |

---

## 🔐 Security Notes

- All forms include CSRF protection
- Requires admin authentication
- No sensitive information in error messages
- Activity logged for audit trail
- All inputs validated server-side

---

## 📞 Getting Help

### Documentation
- Full guide: `CUSTOM_ORDERS_README.md`
- API reference: `CUSTOM_ORDERS_API.md`
- Testing guide: `CUSTOM_ORDERS_TEST.php`

### Common Issues
See "Troubleshooting" section above

### Contact Support
For technical issues, check:
1. Browser console (F12)
2. Server logs (`storage/logs/`)
3. Database records
4. Documentation files

---

## 🎓 Tips & Tricks

1. **Faster Product Search**: Type product code or SKU instead of full name
2. **Bulk Entry**: You can add multiple products before submitting
3. **Discount Flexibility**: Apply discount on per-product basis
4. **Pre-fill from Recent**: Customer info can be copied from similar orders
5. **Delivery Charge**: Changes automatically based on district selection
6. **Totals Update**: All calculations happen in real-time as you type

---

## ⚡ Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Tab | Move to next field |
| Enter | Submit form (if form is ready) |
| Esc | Cancel/Close dropdown |
| Ctrl+A | Select all text |

---

## 📱 Responsive Design

- Desktop: Full layout with all fields visible
- Tablet: Responsive grid layout
- Mobile: Stacked layout (if accessed on mobile)

---

## 🔄 Workflow Summary

```
Start → Enter Customer Info → Select Division → Select District
  ↓
Search Product → Select Product → Add to Cart → Review Totals
  ↓
Repeat above for multiple products
  ↓
Select Payment Method → Select Payment Status → Select Order Status
  ↓
Click Submit → Order Created → Email Sent → Success Message
```

---

## ✨ Next Steps

1. **Test the feature** using the checklist above
2. **Read full documentation** in CUSTOM_ORDERS_README.md
3. **Train staff** on how to use Custom Orders
4. **Set up email** if not already configured
5. **Monitor orders** in admin dashboard

---

**Version:** 1.0.0  
**Status:** Production Ready ✅  
**Last Updated:** February 24, 2024

For detailed information, see CUSTOM_ORDERS_SUMMARY.md
